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
            'total' => Item::where('user_id', auth()->id())->count(),
            'baik' => Item::where('user_id', auth()->id())->where('condition', 'baik')->count(),
            'rusak' => Item::where('user_id', auth()->id())->where('condition', 'rusak')->count(),
            'hilang' => Item::where('user_id', auth()->id())->where('condition', 'hilang')->count(),
        ];

        $recentLogs = \App\Models\ItemLog::with('item')
            ->where('user_id', auth()->id())
            ->latest()
            ->limit(5)
            ->get();

        return view('user.dashboard', compact('stats', 'recentLogs'));
    }
}
