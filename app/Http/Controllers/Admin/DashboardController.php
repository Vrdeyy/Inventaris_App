<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemLog;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_items' => Item::count(),
            'active_users' => User::where('role', 'user')->count(),
            'total_logs' => ItemLog::count(),
            'conditions' => [
                'baik' => Item::where('condition', 'baik')->count(),
                'rusak' => Item::where('condition', 'rusak')->count(),
                'hilang' => Item::where('condition', 'hilang')->count(),
            ],
        ];

        // Recent Activities
        $recentLogs = ItemLog::with(['item', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        // Items by Category
        $categories = Item::select('category', \DB::raw('count(*) as total'))
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Active Users (Top contributors)
        $topUsers = User::where('role', 'user')
            ->withCount('items')
            ->orderBy('items_count', 'desc')
            ->limit(4)
            ->get();

        // Simple alerts
        $alerts = [];
        if ($stats['conditions']['rusak'] > 10) {
            $alerts[] = 'Perhatian! Jumlah barang rusak sudah lebih dari 10 unit.';
        }

        return view('admin.dashboard', compact('stats', 'alerts', 'recentLogs', 'categories', 'topUsers'));
    }
}
