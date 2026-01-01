<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_items' => Item::count(),
            'active_users' => User::where('is_active', true)->where('role', 'user')->count(),
            'conditions' => [
                'baik' => Item::where('condition', 'baik')->count(),
                'rusak' => Item::where('condition', 'rusak')->count(),
                'hilang' => Item::where('condition', 'hilang')->count(),
            ],
        ];

        // Simple alerts
        $alerts = [];
        if ($stats['conditions']['rusak'] > 5) {
            $alerts[] = 'Jumlah barang rusak meningkat (Total: ' . $stats['conditions']['rusak'] . ')';
        }
        if ($stats['conditions']['hilang'] > 0) {
            $alerts[] = 'Ada ' . $stats['conditions']['hilang'] . ' barang hilang!';
        }

        return view('admin.dashboard', compact('stats', 'alerts'));
    }
}
