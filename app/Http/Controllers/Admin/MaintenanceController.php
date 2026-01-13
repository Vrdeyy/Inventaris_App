<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\ItemHistoryController;
use App\Http\Controllers\ItemController;

class MaintenanceController extends Controller
{
    /**
     * Tampilan Halaman Pemeliharaan
     */
    public function index()
    {
        $users = User::where('role', 'user')->get();
        $totalLogs = ItemLog::count();
        $totalItems = Item::count();

        // Dapatkan rentang tahun yang tersedia di logs
        $logYears = ItemLog::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($logYears->isEmpty()) {
            $logYears = [now()->year];
        }

        return view('admin.maintenance.index', compact('users', 'totalLogs', 'totalItems', 'logYears'));
    }

    /**
     * Bersihkan Riwayat (Item Logs)
     */
    public function clearHistory(Request $request)
    {
        $mode = $request->input('mode'); // 'archive_all', 'only_period', 'before_period'
        $month = $request->input('month');
        $year = $request->input('year');

        if ($mode === 'archive_all') {
            // 1. Fetch data for archive
            $logs = ItemLog::with(['item', 'user'])->oldest('created_at')->get();

            if ($logs->count() === 0) {
                return back()->with('error', 'Tidak ada riwayat untuk diarsipkan.');
            }

            // 2. Clear Database
            ItemLog::truncate();

            // 3. Export the fetched data
            return app(ItemHistoryController::class)->exportHistory($request, $logs);
        }

        $query = ItemLog::query();
        if ($mode === 'only_period') {
            $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
            $msg = "Riwayat bulan " . $month . "/" . $year . " berhasil dihapus.";
        } else {
            // before_period
            $date = Carbon::create($year, $month, 1)->startOfMonth();
            $query->where('created_at', '<', $date);
            $msg = "Semua riwayat sebelum " . $date->format('M Y') . " berhasil dihapus.";
        }

        $withArchive = $request->has('archive');
        if ($withArchive) {
            // Fetch before delete
            $logs = (clone $query)->with(['item', 'user'])->get();

            if ($logs->count() === 0) {
                return back()->with('error', 'Tidak ada data riwayat yang cocok dengan filter untuk diarsipkan.');
            }

            // Delete
            $count = $query->count();
            $query->delete();

            // Export the logs we pre-fetched (The exporter will auto-split sheets)
            return app(ItemHistoryController::class)->exportHistory($request, $logs);
        }

        $count = $query->count();
        $query->delete();

        return redirect()->route('admin.maintenance.index')->with('success', "{$msg} ({$count} baris data).");
    }

    /**
     * Bersihkan Data Barang (Reset Items)
     */
    public function clearItems(Request $request)
    {
        $target = $request->input('target_user'); // 'all' or user_id
        $withArchive = $request->has('archive');

        $userName = 'Semua Petugas';
        if ($target !== 'all') {
            $user = User::findOrFail($target);
            $userName = $user->name;
        }

        if ($withArchive) {
            // 1. Fetch items while they still exist
            $itemQuery = Item::with('user');
            if ($target !== 'all') {
                $itemQuery->where('user_id', $target);
            }
            $items = $itemQuery->get();

            if ($items->count() === 0) {
                return back()->with('error', "Tidak ada data barang untuk diarsipkan ({$userName}).");
            }

            // 2. Delete from DB
            if ($target === 'all') {
                Item::query()->forceDelete();
            } else {
                Item::where('user_id', $target)->forceDelete();
            }

            // 3. Export pre-fetched items
            return app(ItemController::class)->exportItems($request, $items);
        }

        // Jika tidak ada arsip, hanya hapus dan redirect balik
        if ($target === 'all') {
            Item::query()->forceDelete();
            $msg = "Seluruh data barang inventaris telah dihapus (Reset Total).";
        } else {
            Item::where('user_id', $target)->forceDelete();
            $msg = "Data barang milik petugas '{$userName}' telah berhasil direset.";
        }

        return redirect()->route('admin.maintenance.index')->with('success', $msg);
    }
}
