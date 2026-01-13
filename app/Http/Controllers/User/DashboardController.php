<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Item::where('user_id', auth()->id())->sum('quantity'),
            'baik' => Item::where('user_id', auth()->id())->sum('qty_baik'),
            'rusak' => Item::where('user_id', auth()->id())->sum('qty_rusak'),
            'hilang' => Item::where('user_id', auth()->id())->sum('qty_hilang'),
        ];

        $recentLogs = \App\Models\ItemLog::with('item')
            ->where('user_id', auth()->id())
            ->latest()
            ->limit(5)
            ->get();

        return view('user.dashboard', compact('stats', 'recentLogs'));
    }
}
