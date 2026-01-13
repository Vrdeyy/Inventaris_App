<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemLog;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Admin\ItemHistoryController;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.reports.index', compact('users'));
    }

    public function generate(Request $request)
    {
        $type = $request->input('report_type'); // 'items' or 'history'
        $action = $request->input('action', 'export'); // Default to 'export' (Excel only)
        $userId = $request->input('user_id');

        $user = ($userId && $userId !== 'all') ? User::find($userId) : null;

        if ($type === 'items') {
            $query = Item::with('user');

            if ($user) {
                $query->where('user_id', $user->id);
            }

            if ($category = $request->input('category')) {
                $query->where('category', $category);
            }
            if ($condition = $request->input('condition')) {
                $query->where('condition', $condition);
            }

            // Always export to Excel (no PDF option)
            if (!$user)
                $request->merge(['user_id' => null]);
            return app(ItemController::class)->exportItems($request);
        } else {
            $query = ItemLog::with(['item', 'user']);

            if ($user) {
                $query->where('user_id', $user->id);
            }


            if ($month = $request->input('month')) {
                $query->whereMonth('created_at', $month);
            }
            if ($year = $request->input('year')) {
                $query->whereYear('created_at', $year);
            }

            // Always export to Excel (no PDF option)
            if (!$user)
                $request->merge(['user_id' => null]);
            return app(ItemHistoryController::class)->exportHistory($request);
        }
    }
}
