<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        // Get all users with their item stats
        $users = User::where('role', 'user')->withCount([
            'items as total_items',
            'items as baik_items' => function ($query) {
                $query->where('condition', 'baik');
            },
            'items as rusak_items' => function ($query) {
                $query->where('condition', 'rusak');
            },
            'items as hilang_items' => function ($query) {
                $query->where('condition', 'hilang');
            }
        ])->get();

        return view('admin.monitoring.index', compact('users'));
    }

    public function showUser(Request $request, User $user)
    {
        // 1. Fetch Items with filtering
        $itemsQuery = $user->items();
        if ($search = $request->input('search')) {
            $itemsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }
        if ($category = $request->input('category')) {
            $itemsQuery->where('category', $category);
        }
        if ($condition = $request->input('condition')) {
            $itemsQuery->where('condition', $condition);
        }
        $items = $itemsQuery->latest()->paginate(10, ['*'], 'items_page');

        // 2. Fetch User Logs
        $logs = \App\Models\ItemLog::with('item')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(15, ['*'], 'logs_page');

        return view('admin.monitoring.show', compact('items', 'logs', 'user'));
    }

    public function printUserItems(Request $request, User $user)
    {
        $query = $user->items();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }
        if ($condition = $request->input('condition')) {
            $query->where('condition', $condition);
        }

        $items = $query->latest()->get();

        return view('admin.reports.items_print', compact('items', 'user'));
    }
}
