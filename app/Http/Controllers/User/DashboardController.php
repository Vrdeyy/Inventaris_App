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
            'total' => Item::count(),
            'baik' => Item::where('condition', 'baik')->count(),
            'rusak' => Item::where('condition', 'rusak')->count(),
            'hilang' => Item::where('condition', 'hilang')->count(),
        ];

        return view('user.dashboard', compact('stats'));
    }
}
