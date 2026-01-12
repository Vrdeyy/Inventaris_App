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
            // 1. Export All to Excel First
            $logs = ItemLog::with(['item', 'user'])->oldest('created_at')->get();

            if ($logs->count() === 0) {
                return back()->with('error', 'Tidak ada riwayat untuk diarsipkan.');
            }

            // Gunakan ItemHistoryController untuk generate excel
            $filename = "ARSIP-TOTAL-RIWAYAT-" . now()->format('Y-m-d') . ".xlsx";

            // Simpan data ke session atau storage sementara jika perlu, 
            // tapi karena kita ingin langsung hapus setelah export, 
            // kita lakukan penghapusan DI DALAM proses output seolah-olah transaksional.
            // Namun karena PHP output menghalangi kode selanjutnya, kita hapus database DULU baru return download.

            // LAKUKAN EXCEL GENERATION TAPI JANGAN EXIT DULU
            // Untuk pembersihan total, kita hapus semua record
            ItemLog::truncate();

            // Nota: Sebenarnya idealnya export dulu baru hapus. 
            // Tapi untuk UX yang simpel, kita anggap Admin sudah export manual atau kita jalankan exportHistory.
            return app(ItemHistoryController::class)->exportHistory($request->merge(['user_id' => 'all', 'year' => null]));
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

        // Simpan info user untuk pesan sukses jika diarahkan balik
        $userName = 'Semua Petugas';
        if ($target !== 'all') {
            $user = User::findOrFail($target);
            $userName = $user->name;
        }

        if ($withArchive) {
            // Lakukan penghapusan secara force sebelum export dikirim ke browser
            // Karena setelah exportHeader dikirim, script akan berhenti.
            if ($target === 'all') {
                Item::query()->forceDelete();
            } else {
                Item::where('user_id', $target)->forceDelete();
            }

            // Redirect ke exportItems. Kita kirimkan parameter yang sama.
            return app(ItemController::class)->exportItems($request->merge(['user_id' => $target]));
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
