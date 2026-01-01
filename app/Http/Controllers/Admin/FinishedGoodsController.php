<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinishedGoodsController extends Controller
{
    public function index()
    {
        // Get items categorized as 'Barang Jadi'
        // If 'Barang Jadi' is not the exact string, we might need to adjust.
        $items = Item::where('category', 'Barang Jadi')->latest()->paginate(10);

        // Get available months/years from logs for the dropdowns
        $dates = ItemLog::select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.monitoring.finished_goods', compact('items', 'dates'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'month' => 'required|numeric',
            'year' => 'required|numeric',
        ]);

        $month = $request->month;
        $year = $request->year;

        // Fetch logs for the selected period
        $logs = ItemLog::with(['item', 'user'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        // Generate CSV
        $filename = "export_history_{$month}_{$year}.csv";
        $handle = fopen('php://output', 'w');

        // Headers for download
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($logs, $handle) {
            fputcsv($handle, ['Date', 'User', 'Item Code', 'Item Name', 'Action', 'Old Qty', 'New Qty', 'Description']);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->created_at,
                    $log->user->name ?? 'Unknown',
                    $log->item->code ?? 'N/A',
                    $log->item->name ?? 'N/A',
                    $log->action,
                    $log->old_quantity,
                    $log->new_quantity,
                    $log->description
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroyHistory()
    {
        // Delete history (logs) for 'Barang Jadi' items only
        ItemLog::whereHas('item', function ($query) {
            $query->where('category', 'Barang Jadi');
        })->delete();

        return redirect()->back()->with('success', 'Riwayat data Barang Jadi berhasil dihapus.');
    }
}
