<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemLog;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ItemHistoryController extends Controller
{
    /**
     * Display item details with history
     */
    public function show(Item $item)
    {
        $item->load([
            'user',
            'logs' => function ($query) {
                $query->with('user')->latest('created_at');
            }
        ]);

        return view('admin.items.show', compact('item'));
    }

    /**
     * Display list of users to see their specific history (Grouped by User)
     */
    public function history(Request $request)
    {
        $query = User::where('role', 'user');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $users = $query->withCount([
            'logs as total_activities',
            'logs as month_activities' => function ($query) {
                $query->whereMonth('created_at', now()->month);
            }
        ])->get();

        return view('admin.items.history_index', compact('users'));
    }

    /**
     * Display history for a specific user
     */
    public function userHistory(User $user, Request $request)
    {
        $query = ItemLog::with(['item', 'user'])->where('user_id', $user->id);

        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }
        if ($month = $request->input('month')) {
            $query->whereMonth('created_at', $month);
        }
        if ($year = $request->input('year')) {
            $query->whereYear('created_at', $year);
        }

        $logs = $query->latest('created_at')->paginate(20);

        return view('admin.items.user_history', compact('logs', 'user'));
    }

    /**
     * Export history to Excel (Admin only - supports day, month, year filtering)
     * When spanning multiple months, each month gets its own sheet
     */
    public function exportHistory(Request $request)
    {
        $query = ItemLog::with(['item', 'user']);

        if ($userId = $request->input('user_id')) {
            if ($userId !== 'all') {
                $query->where('user_id', $userId);
            }
        }
        $selectedYear = $request->input('year') ?: Carbon::now()->year;
        $startMonth = $request->input('start_month');
        $endMonth = $request->input('end_month');
        $isRange = ($startMonth && $endMonth && $startMonth != $endMonth);

        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        } elseif ($isRange) {
            $startDate = Carbon::create($selectedYear, $startMonth, 1)->startOfDay();
            $endDate = Carbon::create($selectedYear, $endMonth, 1)->endOfMonth()->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            if ($month = $request->input('month') ?: $startMonth) {
                $query->whereMonth('created_at', $month);
            }
            if ($year = $request->input('year') ?: $selectedYear) {
                $query->whereYear('created_at', $year);
            }
        }

        // Filter by action only if it's a valid log action (not 'export' or 'print')
        if ($actionFilter = $request->input('log_action')) {
            $query->where('action', $actionFilter);
        }

        $logs = $query->latest('created_at')->get();

        // Filename Info
        $userInfo = 'Semua-Petugas';
        if ($uid = $request->input('user_id')) {
            if ($uid !== 'all') {
                if ($u = User::find($uid)) {
                    $userInfo = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $u->name));
                }
            }
        }

        $selectedYear = $request->input('year') ?: Carbon::now()->year;
        $startMonth = $request->input('start_month');
        $endMonth = $request->input('end_month');

        // Check if multi-month export is needed
        if ($startMonth && $endMonth && $startMonth != $endMonth) {
            return $this->exportMultiMonthHistory($logs, $startMonth, $endMonth, $selectedYear, $userInfo);
        }

        // Single month/period export
        $month = $request->input('month') ?: $startMonth;
        $year = $request->input('year') ?: $selectedYear;
        $periodInfo = ($month ? $this->getIndonesianMonth($month) : 'Semua') . '-' . ($year ?: 'Semua');
        $filename = "riwayat-aktivitas-{$userInfo}-{$periodInfo}.xlsx";

        return $this->generateHistoryExcel($logs, $filename, $month ? $this->getIndonesianMonth($month) : null, $year);
    }

    /**
     * Export history spanning multiple months - each month gets its own sheet
     */
    private function exportMultiMonthHistory($allLogs, $startMonth, $endMonth, $year, $userInfo)
    {
        $templatePath = storage_path('app/templates/template_history.xlsx');
        $hasTemplate = Storage::exists('templates/template_history.xlsx');
        if ($hasTemplate) {
            $templatePath = Storage::path('templates/template_history.xlsx');
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // Remove default sheet

        // Load template for reference (if exists)
        $templateSpreadsheet = null;
        if ($hasTemplate && file_exists($templatePath)) {
            $templateSpreadsheet = IOFactory::load($templatePath);
        }

        for ($m = $startMonth; $m <= $endMonth; $m++) {
            $monthName = $this->getIndonesianMonth($m);

            // Filter logs for this specific month
            $monthLogs = $allLogs->filter(function ($log) use ($m, $year) {
                return $log->created_at->month == $m && $log->created_at->year == $year;
            });

            // Create new sheet
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle($monthName);

            // Copy from template or create default header
            if ($templateSpreadsheet) {
                $this->copyTemplateToSheet($templateSpreadsheet->getActiveSheet(), $sheet);
            } else {
                $this->createDefaultHistoryHeader($sheet, $monthName, $year);
            }

            // Fill data starting from row 6
            $this->fillHistoryData($sheet, $monthLogs, 6);

            // Apply styling to data rows
            $lastRow = 5 + $monthLogs->count();
            if ($monthLogs->count() > 0) {
                $this->applyDataStyles($sheet, 6, $lastRow, 'A', 'G');
            }
        }

        $spreadsheet->setActiveSheetIndex(0);
        $filename = "riwayat-aktivitas-{$userInfo}-{$this->getIndonesianMonth($startMonth)}-{$this->getIndonesianMonth($endMonth)}-{$year}.xlsx";

        return $this->outputExcel($spreadsheet, $filename);
    }

    /**
     * Generate History Excel from template
     */
    private function generateHistoryExcel($logs, $filename, $month = null, $year = null)
    {
        $templatePath = storage_path('app/templates/template_history.xlsx');
        $hasTemplate = Storage::exists('templates/template_history.xlsx');

        if ($hasTemplate) {
            $templatePath = Storage::path('templates/template_history.xlsx');
        }

        if ($hasTemplate && file_exists($templatePath) && class_exists(IOFactory::class)) {
            // Load Template
            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getActiveSheet();

            // Rename sheet if month is provided
            if ($month) {
                $sheet->setTitle($month);
            }

            // CLEANUP: Pastikan baris 1-4 bersih dari warna background
            $this->cleanHeaderArea($sheet);

            // Fill data starting from row 6
            $this->fillHistoryData($sheet, $logs, 6);

            // Apply styling to data rows
            $lastRow = 5 + $logs->count();
            if ($logs->count() > 0) {
                $this->applyDataStylesFromTemplate($sheet, 6, $lastRow, 'A', 'G');
            }

            return $this->outputExcel($spreadsheet, $filename);
        }

        // Fallback: Create new spreadsheet with default styling
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        if ($month) {
            $sheet->setTitle($month);
        }

        // Create styled header
        $this->createDefaultHistoryHeader($sheet, $month, $year);

        // Fill data
        $this->fillHistoryData($sheet, $logs, 6);

        // Apply styling to data rows
        $lastRow = 5 + $logs->count();
        if ($logs->count() > 0) {
            $this->applyDataStyles($sheet, 6, $lastRow, 'A', 'G');
        }

        return $this->outputExcel($spreadsheet, $filename);
    }

    /**
     * Create default header with styling for history
     */
    private function createDefaultHistoryHeader($sheet, $month = null, $year = null)
    {
        // Title
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'RIWAYAT AKTIVITAS INVENTARIS');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Subtitle/Period
        $sheet->mergeCells('A2:G2');
        $periodText = 'Periode: ' . ($month ?: 'Semua Bulan') . ' ' . ($year ?: date('Y'));
        $sheet->setCellValue('A2', $periodText);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Print date
        $sheet->mergeCells('A3:G3');
        $sheet->setCellValue('A3', 'Dicetak: ' . date('d/m/Y H:i'));
        $sheet->getStyle('A3')->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 9,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Empty row 4
        $sheet->getRowDimension(4)->setRowHeight(10);

        // Header row (row 5)
        $headers = ['No', 'Tanggal', 'Petugas', 'Barang', 'Aksi', 'Perubahan', 'Catatan'];
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];

        foreach ($headers as $index => $header) {
            $sheet->setCellValue($columns[$index] . '5', $header);
        }

        // Style header row
        $sheet->getStyle('A5:G5')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'], // Indigo
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
        $sheet->getRowDimension(5)->setRowHeight(20);

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(35);
        $sheet->getColumnDimension('G')->setWidth(25);
    }

    /**
     * Fill history data to sheet
     */
    private function fillHistoryData($sheet, $logs, $startRow)
    {
        $row = $startRow;
        $no = 1;
        foreach ($logs as $log) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $log->created_at->format('d/m/Y H:i'));
            $sheet->setCellValue('C' . $row, $log->user->name ?? '-');
            $sheet->setCellValue('D' . $row, ($log->item->code ?? '-') . ' - ' . ($log->item->name ?? '-'));
            $sheet->setCellValue('E' . $row, ucfirst($log->action));

            // Format Perubahan
            $perubahan = "-";
            if ($log->action === 'update') {
                $perubahan = "Kondisi: {$log->old_condition} -> {$log->new_condition}, Jumlah: {$log->old_quantity} -> {$log->new_quantity}";
            } elseif ($log->action === 'create') {
                $perubahan = "Baru: {$log->new_condition} ({$log->new_quantity})";
            }

            $sheet->setCellValue('F' . $row, $perubahan);
            $sheet->setCellValue('G' . $row, $log->description);
            $row++;
        }
    }

    /**
     * Apply data styles (borders, alternating colors)
     */
    private function applyDataStyles($sheet, $startRow, $endRow, $startCol, $endCol)
    {
        $range = "{$startCol}{$startRow}:{$endCol}{$endRow}";

        // Apply borders to all data
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Center align specific columns
        $sheet->getStyle("A{$startRow}:A{$endRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B{$startRow}:B{$endRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("E{$startRow}:E{$endRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Alternating row colors
        for ($row = $startRow; $row <= $endRow; $row++) {
            if (($row - $startRow) % 2 == 1) {
                $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F3F4F6'], // Light gray
                    ],
                ]);
            }
        }
    }

    /**
     * Apply data styles from template (copy row 6 style to all data rows)
     */
    private function applyDataStylesFromTemplate($sheet, $startRow, $endRow, $startCol, $endCol)
    {
        // Apply borders at minimum
        $range = "{$startCol}{$startRow}:{$endCol}{$endRow}";
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Center align specific columns
        $sheet->getStyle("A{$startRow}:A{$endRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B{$startRow}:B{$endRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("E{$startRow}:E{$endRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    /**
     * Copy template sheet to new sheet (header rows 1-5)
     * Memperbaiki agar tidak bocor warna ke baris 1-4
     */
    private function copyTemplateToSheet($templateSheet, $targetSheet)
    {
        // Copy rows 1-5 (header)
        for ($row = 1; $row <= 5; $row++) {
            for ($col = 'A'; $col <= 'G'; $col++) {
                $cellValue = $templateSheet->getCell($col . $row)->getValue();
                $targetSheet->setCellValue($col . $row, $cellValue);

                // Copy style
                $style = $templateSheet->getStyle($col . $row);

                // Jika Baris 5, copy seluruh style (termasuk background)
                if ($row == 5) {
                    $targetSheet->duplicateStyle($style, $col . $row);
                } else {
                    // Baris 1-4: Hanya copy font dan alignment, buang background
                    $targetStyle = $targetSheet->getStyle($col . $row);
                    $targetStyle->getFont()->applyFromArray($style->getFont()->exportArray());
                    $targetStyle->getAlignment()->applyFromArray($style->getAlignment()->exportArray());

                    // Pastikan fill-nya NONE (putih/transparan)
                    $targetStyle->getFill()->setFillType(Fill::FILL_NONE);
                }
            }

            // Copy row height
            $targetSheet->getRowDimension($row)->setRowHeight(
                $templateSheet->getRowDimension($row)->getRowHeight()
            );
        }

        // Copy column widths
        for ($col = 'A'; $col <= 'G'; $col++) {
            $targetSheet->getColumnDimension($col)->setWidth(
                $templateSheet->getColumnDimension($col)->getWidth()
            );
        }

        // Copy merged cells
        foreach ($templateSheet->getMergeCells() as $mergeCell) {
            // Only copy merges in rows 1-5
            preg_match('/([A-Z]+)(\d+):([A-Z]+)(\d+)/', $mergeCell, $matches);
            if (isset($matches[2]) && $matches[2] <= 5) {
                $targetSheet->mergeCells($mergeCell);
            }
        }
    }

    /**
     * Memastikan baris 1-4 bersih dari warna background
     */
    private function cleanHeaderArea($sheet)
    {
        for ($row = 1; $row <= 4; $row++) {
            $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_NONE,
                ],
            ]);
        }
    }

    /**
     * Output Excel file
     */
    private function outputExcel($spreadsheet, $filename)
    {
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Get Indonesian month name
     */
    private function getIndonesianMonth($monthNumber)
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        return $months[(int) $monthNumber] ?? 'Unknown';
    }
}
