<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();

        // User biasa hanya bisa melihat barang milik mereka sendiri
        // Admin bisa melihat semua barang
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($location = $request->input('location')) {
            $query->where('location', $location);
        }

        if ($placement_type = $request->input('placement_type')) {
            $query->where('placement_type', $placement_type);
        }

        // Admin bisa filter berdasarkan user_id
        if (Auth::user()->role === 'admin' && $user_id = $request->input('user_id')) {
            $query->where('user_id', $user_id);
        }

        if ($condition = $request->input('condition')) {
            $query->where('condition', $condition);
        }

        $items = $query->latest()->paginate(10);

        return view('user.items.index', compact('items'));
    }

    public function create()
    {
        return view('user.items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'category' => 'required',
            'location' => 'required',
            'placement_type' => 'required|in:dalam_ruang,dalam_lemari',
            'qty_baik' => 'required|integer|min:0',
            'qty_rusak' => 'required|integer|min:0',
            'qty_hilang' => 'required|integer|min:0',
            'date_input' => 'required|date',
        ]);

        // Calculate total and condition
        $qtyBaik = $validated['qty_baik'];
        $qtyRusak = $validated['qty_rusak'];
        $qtyHilang = $validated['qty_hilang'];
        $total = $qtyBaik + $qtyRusak + $qtyHilang;

        // Determine condition
        if ($total == 0) {
            $condition = 'baik';
        } elseif ($qtyHilang == $total) {
            $condition = 'hilang';
        } elseif ($qtyRusak == $total) {
            $condition = 'rusak';
        } elseif ($qtyBaik == $total) {
            $condition = 'baik';
        } else {
            $condition = 'sebagian_rusak';
        }

        $item = Item::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'location' => $validated['location'],
            'placement_type' => $validated['placement_type'],
            'quantity' => $total,
            'qty_baik' => $qtyBaik,
            'qty_rusak' => $qtyRusak,
            'qty_hilang' => $qtyHilang,
            'condition' => $condition,
            'user_id' => Auth::id(),
            'created_at' => $validated['date_input'],
        ]);

        ItemLog::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'action' => 'create',
            'new_condition' => $condition,
            'new_quantity' => $total,
            'description' => 'Input baru (Baik: ' . $qtyBaik . ', Rusak: ' . $qtyRusak . ', Hilang: ' . $qtyHilang . ')',
        ]);

        return redirect()->route('user.items.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Item $item)
    {
        // User biasa hanya bisa mengedit barang milik mereka sendiri
        if (Auth::user()->role !== 'admin' && $item->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit barang ini.');
        }

        return view('user.items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        // User biasa hanya bisa mengupdate barang milik mereka sendiri
        if (Auth::user()->role !== 'admin' && $item->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate barang ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'placement_type' => 'required|in:dalam_ruang,dalam_lemari',
            'qty_baik' => 'required|integer|min:0',
            'qty_rusak' => 'required|integer|min:0',
            'qty_hilang' => 'required|integer|min:0',
            'description' => 'required|string|min:5', // Mewajibkan catatan yang bermakna
        ], [
            'qty_baik.required' => 'Jumlah barang baik harus diisi (boleh 0).',
            'qty_rusak.required' => 'Jumlah barang rusak harus diisi (boleh 0).',
            'qty_hilang.required' => 'Jumlah barang hilang harus diisi (boleh 0).',
            'description.required' => 'Harap isi catatan perubahan untuk histori barang.',
            'description.min' => 'Catatan terlalu pendek, harap berikan penjelasan yang agak detail.',
        ]);

        $old_condition = $item->condition;
        $old_quantity = $item->quantity;

        // Calculate total and condition
        $qtyBaik = $validated['qty_baik'];
        $qtyRusak = $validated['qty_rusak'];
        $qtyHilang = $validated['qty_hilang'];
        $total = $qtyBaik + $qtyRusak + $qtyHilang;

        // Determine condition
        if ($total == 0) {
            $condition = 'baik';
        } elseif ($qtyHilang == $total) {
            $condition = 'hilang';
        } elseif ($qtyRusak == $total) {
            $condition = 'rusak';
        } elseif ($qtyBaik == $total) {
            $condition = 'baik';
        } else {
            $condition = 'sebagian_rusak';
        }

        $item->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'location' => $validated['location'],
            'placement_type' => $validated['placement_type'],
            'quantity' => $total,
            'qty_baik' => $qtyBaik,
            'qty_rusak' => $qtyRusak,
            'qty_hilang' => $qtyHilang,
            'condition' => $condition,
        ]);

        ItemLog::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'action' => 'update',
            'old_condition' => $old_condition,
            'new_condition' => $condition,
            'old_quantity' => $old_quantity,
            'new_quantity' => $total,
            'description' => $validated['description'] . ' (Baik: ' . $qtyBaik . ', Rusak: ' . $qtyRusak . ', Hilang: ' . $qtyHilang . ')',
        ]);

        return redirect()->route('user.items.index')->with('success', 'Barang berhasil diupdate.');
    }

    public function destroy(Item $item)
    {
        // User biasa hanya bisa menghapus barang milik mereka sendiri
        if (Auth::user()->role !== 'admin' && $item->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus barang ini.');
        }

        $item->delete();

        ItemLog::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'action' => 'delete',
            'description' => 'Barang dihapus (Soft Delete)',
        ]);

        return redirect()->route('user.items.index')->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Export Items for User (current month only, 1st to end of month)
     */
    public function export()
    {
        $user = Auth::user();

        // Admin ingin petugas hanya bisa cetak data barang BULAN BERJALAN saja (Snapshot Inventory)
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        // Ambil data barang yang ada hingga akhir bulan ini
        $endOfMonth = Carbon::now()->endOfMonth();
        $items = Item::where('user_id', $user->id)
            ->where('created_at', '<=', $endOfMonth)
            ->get();

        $userInfo = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $user->name));

        // Gunakan exportMultiMonthItems agar hasilnya bersih (hanya sheet bulan ini)
        // dan mendapatkan fitur split per penempatan (Dalam Ruang/Lemari)
        return $this->exportMultiMonthItems($items, $month, $month, $year, $userInfo);
    }

    /**
     * Export Items for Admin (supports day, month, year filtering)
     * When spanning multiple months, each month gets its own sheet
     */
    public function exportItems(Request $request, $preFetchedItems = null)
    {
        // 1. Setup Dates & Range
        $currentMonth = Carbon::now()->month;
        $startMonth = $request->input('start_month') ?: $currentMonth;
        $endMonth = $request->input('end_month') ?: $currentMonth;
        $selectedYear = $request->input('year') ?: Carbon::now()->year;

        // 2. Determine Data Source
        if ($preFetchedItems) {
            $items = $preFetchedItems;
        } else {
            $query = Item::with('user');

            // Handle both 'user_id' (Report Center) and 'target_user' (Maintenance)
            $userId = $request->input('user_id') ?: $request->input('target_user');
            if ($userId && $userId !== 'all') {
                $query->where('user_id', $userId);
            }

            // Calculate Date Boundary
            $endDate = Carbon::create($selectedYear, $endMonth, 1)->endOfMonth()->endOfDay();
            $query->where('created_at', '<=', $endDate);

            if ($condition = $request->input('condition')) {
                $query->where('condition', $condition);
            }
            if ($category = $request->input('category')) {
                $query->where('category', $category);
            }

            $items = $query->get();
        }

        // 3. User Info for Filename
        $userInfo = 'Semua-Petugas';
        $uid = $request->input('user_id') ?: $request->input('target_user');
        if ($uid && $uid !== 'all') {
            if ($user = User::find($uid)) {
                $userInfo = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $user->name));
            }
        }

        // 4. Always use multi-month logic (supports splitting by placement: Ruang/Lemari)
        // This also handles renamed sheets correctly.
        return $this->exportMultiMonthItems($items, $startMonth, $endMonth, $selectedYear, $userInfo);
    }

    /**
     * Export items spanning multiple months - each month gets its own sheet
     * Now creates 2 sheets per month: "Bulan - Dalam Ruang" and "Bulan - Dalam Lemari"
     */
    private function exportMultiMonthItems($allItems, $startMonth, $endMonth, $year, $userInfo)
    {
        $templatePath = storage_path('app/templates/template_items.xlsx');
        $hasTemplate = Storage::exists('templates/template_items.xlsx');
        if ($hasTemplate) {
            $templatePath = Storage::path('templates/template_items.xlsx');
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // Remove default sheet

        // Load template for reference (if exists)
        $templateSpreadsheet = null;
        if ($hasTemplate && file_exists($templatePath)) {
            $templateSpreadsheet = IOFactory::load($templatePath);
        }

        $placementTypes = ['dalam_ruang' => 'Dalam Ruang', 'dalam_lemari' => 'Dalam Lemari'];

        // Determine if we need to split by User (for Mass Resets)
        $usersFound = $allItems->pluck('user.name')->unique();
        $isMultiUser = $usersFound->count() > 1;

        for ($m = $startMonth; $m <= $endMonth; $m++) {
            $monthName = $this->getIndonesianMonth($m);
            $monthEnd = Carbon::create($year, $m, 1)->endOfMonth()->endOfDay();
            $monthItems = $allItems->filter(function ($item) use ($monthEnd) {
                return $item->created_at <= $monthEnd;
            });

            if ($isMultiUser) {
                // PARTISI PER USER
                $itemsByUser = $monthItems->groupBy('user_id');
                foreach ($itemsByUser as $uid => $userItems) {
                    $u = $userItems->first()->user;
                    $shortUserName = substr($u->name, 0, 10);

                    foreach ($placementTypes as $placementKey => $placementLabel) {
                        $pItems = $userItems->where('placement_type', $placementKey);
                        if ($pItems->count() === 0)
                            continue;

                        $sheet = $spreadsheet->createSheet();
                        $sheetTitle = substr($shortUserName . ' - ' . $placementLabel, 0, 31);
                        $sheet->setTitle($sheetTitle);

                        if ($templateSpreadsheet) {
                            $this->copyTemplateToSheet($templateSpreadsheet->getActiveSheet(), $sheet);
                            $title = strtoupper($u->name) . ' - ' . strtoupper($monthName) . ' ' . $year;
                            $sheet->setCellValue('A1', $title);
                            $sheet->setCellValue('A2', "Periode: " . $monthName . " " . $year);
                        } else {
                            $this->createDefaultItemsHeader($sheet, $monthName . ' (' . $u->name . ')', $year);
                        }
                        $this->fillItemsData($sheet, $pItems, 7);
                        $lastRow = 6 + $pItems->count();
                        if ($pItems->count() > 0)
                            $this->applyDataStyles($sheet, 7, $lastRow, 'A', 'H');
                    }
                }
            } else {
                // STANDAR PER BULAN
                foreach ($placementTypes as $placementKey => $placementLabel) {
                    $placementItems = $monthItems->where('placement_type', $placementKey);
                    $sheet = $spreadsheet->createSheet();
                    $sheetTitle = substr($monthName . ' - ' . $placementLabel, 0, 31);
                    $sheet->setTitle($sheetTitle);

                    if ($templateSpreadsheet) {
                        $this->copyTemplateToSheet($templateSpreadsheet->getActiveSheet(), $sheet);
                        $title = 'DAFTAR INVENTARIS BARANG - ' . strtoupper($monthName) . ' ' . $year;
                        $sheet->setCellValue('A1', $title);
                        $sheet->setCellValue('A2', "Periode: " . $monthName . " " . $year);
                    } else {
                        $this->createDefaultItemsHeader($sheet, $monthName, $year);
                    }
                    $this->fillItemsData($sheet, $placementItems, 7);
                    $lastRow = 6 + $placementItems->count();
                    if ($placementItems->count() > 0)
                        $this->applyDataStyles($sheet, 7, $lastRow, 'A', 'H');
                }
            }
        }

        if ($spreadsheet->getSheetCount() === 0) {
            $spreadsheet->createSheet()->setTitle('Data Kosong');
        }

        $spreadsheet->setActiveSheetIndex(0);
        $monthRange = ($startMonth == $endMonth) ? $this->getIndonesianMonth($startMonth) : $this->getIndonesianMonth($startMonth) . '-' . $this->getIndonesianMonth($endMonth);
        $filename = "arsip-barang-{$userInfo}-{$monthRange}-{$year}.xlsx";

        return $this->outputExcel($spreadsheet, $filename);
    }

    /**
     * Generate Excel file from template
     */
    private function generateExcelFromTemplate($items, $filename, $type = 'items', $month = null, $year = null)
    {
        $templatePath = storage_path("app/templates/template_{$type}.xlsx");
        $hasTemplate = Storage::exists("templates/template_{$type}.xlsx");

        if ($hasTemplate) {
            $templatePath = Storage::path("templates/template_{$type}.xlsx");
        }

        if ($hasTemplate && file_exists($templatePath) && class_exists(IOFactory::class)) {
            // Load Template
            $spreadsheet = IOFactory::load($templatePath);

            // FIX: Hapus semua sheet bawaan template kecuali yang sedang aktif
            // Ini mencegah munculnya "sheet bulan lain" jika template memiliki banyak sheet
            $activeSheetIndex = $spreadsheet->getActiveSheetIndex();
            $sheetCount = $spreadsheet->getSheetCount();
            for ($i = $sheetCount - 1; $i >= 0; $i--) {
                if ($i !== $activeSheetIndex) {
                    $spreadsheet->removeSheetByIndex($i);
                }
            }

            $sheet = $spreadsheet->getActiveSheet();

            // Rename sheet if month is provided
            if ($month) {
                $sheet->setTitle($month);
            }

            // CLEANUP: Pastikan baris 1-4 bersih dari warna background
            $this->cleanHeaderArea($sheet);

            // Fill data starting from row 7
            $this->fillItemsData($sheet, $items, 7);

            // Apply styling to data rows
            $lastRow = 6 + $items->count();
            if ($items->count() > 0) {
                $this->applyDataStylesFromTemplate($sheet, 7, $lastRow, 'A', 'H');
            }

            return $this->outputExcel($spreadsheet, $filename);
        }

        // Fallback: Create new spreadsheet with default styling
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        $placementTypes = ['dalam_ruang' => 'Dalam Ruang', 'dalam_lemari' => 'Dalam Lemari'];

        foreach ($placementTypes as $placementKey => $placementLabel) {
            $placementItems = $items->where('placement_type', $placementKey);

            $sheet = $spreadsheet->createSheet();
            $sheetTitle = ($month ? substr($month, 0, 15) : 'Semua') . ' - ' . substr($placementLabel, 0, 12);
            $sheet->setTitle($sheetTitle);

            // Create styled header (Nested)
            $this->createDefaultItemsHeader($sheet, ($month ?: 'Semua') . ' - ' . $placementLabel, $year);

            // Fill data starting from row 7
            $this->fillItemsData($sheet, $placementItems, 7);

            // Apply styling to data rows
            $lastRow = 6 + $placementItems->count();
            if ($placementItems->count() > 0) {
                $this->applyDataStyles($sheet, 7, $lastRow, 'A', 'H');
            }
        }

        $spreadsheet->setActiveSheetIndex(0);

        return $this->outputExcel($spreadsheet, $filename);
    }

    /**
     * Create default header with styling for items
     * Using Nested Header (Header Bertingkat)
     */
    private function createDefaultItemsHeader($sheet, $month = null, $year = null)
    {
        // Title (Baris 1)
        $sheet->mergeCells('A1:H1');
        $title = 'DAFTAR INVENTARIS BARANG' . ($month ? ' - ' . strtoupper($month) : '') . ($year ? ' ' . $year : '');
        $sheet->setCellValue('A1', $title);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Periode (Baris 2)
        $sheet->mergeCells('A2:H2');
        if ($month && $year) {
            $sheet->setCellValue('A2', "Periode: " . $month . " " . $year);
        } else {
            $sheet->setCellValue('A2', "Laporan Status Barang Terbaru");
        }
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 10],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(20);

        // Baris 3, 4 dikosongkan agar ada jarak sesuai template
        $sheet->getRowDimension(3)->setRowHeight(10);

        // Header Row 5 & 6 (Nested) - Label dibuat LOWERCASE sesuai template
        $headers = [
            'A5:A6' => 'no',
            'B5:B6' => 'nama',
            'C5:C6' => 'kategori',
            'D5:F5' => 'kondisi per unit',
            'G5:G6' => 'total',
            'H5:H6' => 'tanggal input'
        ];

        foreach ($headers as $range => $label) {
            $sheet->mergeCells($range);
            $sheet->setCellValue(explode(':', $range)[0], $label);
        }

        // Sub-header Kondisi (Baris 6)
        $sheet->setCellValue('D6', 'baik');
        $sheet->setCellValue('E6', 'rusak');
        $sheet->setCellValue('F6', 'hilang');

        // Style Header (A5:H6)
        $sheet->getStyle('A5:H6')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'], // Font Hitam sesuai template
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FDE9D9'], // Warna Peach sesuai template
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Column Widths
        $widths = ['A' => 5, 'B' => 28, 'C' => 15, 'D' => 8, 'E' => 8, 'F' => 8, 'G' => 8, 'H' => 14];
        foreach ($widths as $col => $w)
            $sheet->getColumnDimension($col)->setWidth($w);

        $sheet->getRowDimension(5)->setRowHeight(20);
        $sheet->getRowDimension(6)->setRowHeight(20);
    }

    /**
     * Fill items data to sheet
     * Updated with breakdown columns
     */
    private function fillItemsData($sheet, $items, $startRow)
    {
        $row = $startRow;
        $no = 1;
        foreach ($items as $item) {
            $conditionLabel = $item->condition === 'sebagian_rusak' ? 'Sebagian Rusak' : ucfirst($item->condition);

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item->name);
            $sheet->setCellValue('C' . $row, $item->category);

            // Tulis Angka & Paksa Format menjadi General/Number (Menghindari "00/01/1900")
            $sheet->setCellValue('D' . $row, $item->qty_baik);
            $sheet->setCellValue('E' . $row, $item->qty_rusak);
            $sheet->setCellValue('F' . $row, $item->qty_hilang);
            $sheet->setCellValue('G' . $row, $item->quantity);

            // Reset format kolom D-H ke General agar tidak jadi tanggal
            $sheet->getStyle("D{$row}:G{$row}")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL);

            $sheet->setCellValue('H' . $row, $item->created_at->format('d/m/Y'));
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
        $sheet->getStyle("D{$startRow}:G{$endRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("H{$startRow}:H{$endRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Force Number Formatting
        $sheet->getStyle("D{$startRow}:G{$endRow}")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("H{$startRow}:H{$endRow}")->getNumberFormat()->setFormatCode('dd/mm/yyyy');

        // Alternating row colors (Sangat tipis agar tetap bersih)
        for ($row = $startRow; $row <= $endRow; $row++) {
            if (($row - $startRow) % 2 == 1) {
                $sheet->getStyle("A{$row}:H{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FAF8F6');
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
        $sheet->getStyle("D{$startRow}:G{$endRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("H{$startRow}:H{$endRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Force Number/Date Formatting
        $sheet->getStyle("D{$startRow}:G{$endRow}")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("H{$startRow}:H{$endRow}")->getNumberFormat()->setFormatCode('dd/mm/yyyy');
    }

    /**
     * Copy template sheet to new sheet (header rows 1-6)
     * Sekarang mendukung Nested Header (2 baris header: 5 & 6)
     */
    private function copyTemplateToSheet($templateSheet, $targetSheet)
    {
        // Copy rows 1-6 (header)
        for ($row = 1; $row <= 6; $row++) {
            for ($col = 'A'; $col <= 'H'; $col++) {
                $cellValue = $templateSheet->getCell($col . $row)->getValue();
                $targetSheet->setCellValue($col . $row, $cellValue);

                // Copy style
                $style = $templateSheet->getStyle($col . $row);

                // Jika Baris 5 atau 6, copy seluruh style (termasuk background/warna)
                if ($row >= 5) {
                    $targetSheet->duplicateStyle($style, $col . $row);
                } else {
                    // Baris 1-4: Hanya copy font dan alignment, buang background warna
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

        // Copy column widths A sampai H
        foreach (range('A', 'H') as $col) {
            $targetSheet->getColumnDimension($col)->setWidth(
                $templateSheet->getColumnDimension($col)->getWidth()
            );
        }

        // Copy merged cells (terutama untuk judul dan penempatan)
        foreach ($templateSheet->getMergeCells() as $mergeCell) {
            // Hanya copy merge yang ada di baris 1-6
            preg_match('/([A-Z]+)(\d+):([A-Z]+)(\d+)/', $mergeCell, $matches);
            if (isset($matches[2]) && $matches[2] <= 6) {
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
            $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
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

    public function exportUserItems(Request $request, User $user)
    {
        // Wrapper for backward compatibility or simple redirection to main export
        $request->merge(['user_id' => $user->id]);
        return $this->exportItems($request);
    }
}
