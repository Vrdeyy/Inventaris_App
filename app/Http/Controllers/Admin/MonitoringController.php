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

    public function showUser(User $user)
    {
        // Re-use user items view or a specific admin view
        // Let's perform the query here to pass items to the view
        $items = $user->items()->latest()->paginate(10);

        // We can reuse user.items.index or create admin.monitoring.show
        // reusing user.items.index might need tweaks to hide "Delete/Edit" if we want read-only
        // But Controller already handles logic. View checks role.

        return view('user.items.index', compact('items'));
        // Note: The view user.items.index checks Auth role to show/hide Update/Delete buttons.
        // So this is safe and efficient reuse.
    }
}
