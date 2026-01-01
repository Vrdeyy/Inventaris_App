<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($location = $request->input('location')) {
            $query->where('location', $location);
        }

        if ($user_id = $request->input('user_id')) {
            $query->where('user_id', $user_id);
        }

        if ($condition = $request->input('condition')) {
            $query->where('condition', $condition);
        }

        $items = $query->latest()->paginate(10);

        return view('user.items.index', compact('items'));
    }

    public function create()
    {
        return view('user.items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:items',
            'name' => 'required',
            'category' => 'required',
            'location' => 'required',
            'quantity' => 'required|integer|min:0',
            'condition' => 'required|in:baik,rusak,hilang',
            'date_input' => 'required|date',
        ]);

        $item = Item::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'category' => $validated['category'],
            'location' => $validated['location'],
            'quantity' => $validated['quantity'],
            'condition' => $validated['condition'],
            'user_id' => Auth::id(),
            'created_at' => $validated['date_input'],
        ]);

        ItemLog::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'action' => 'create',
            'new_condition' => $validated['condition'],
            'new_quantity' => $validated['quantity'],
            'description' => 'Input baru',
        ]);

        return redirect()->route('user.items.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Item $item)
    {
        return view('user.items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required',
            'location' => 'required',
            'quantity' => 'required|integer|min:0',
            'condition' => 'required|in:baik,rusak,hilang',
            'description' => 'required|string', // Note for history
        ]);

        $old_condition = $item->condition;
        $old_quantity = $item->quantity;

        $item->update([
            'name' => $validated['name'],
            'location' => $validated['location'],
            'quantity' => $validated['quantity'],
            'condition' => $validated['condition'],
        ]);

        ItemLog::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'action' => 'update',
            'old_condition' => $old_condition,
            'new_condition' => $validated['condition'],
            'old_quantity' => $old_quantity,
            'new_quantity' => $validated['quantity'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('user.items.index')->with('success', 'Barang berhasil diupdate.');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        ItemLog::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'action' => 'delete',
            'description' => 'Barang dihapus (Soft Delete)',
        ]);

        return redirect()->route('user.items.index')->with('success', 'Barang berhasil dihapus.');
    }

    public function export()
    {
        $filename = "inventaris-" . date('Y-m-d') . ".csv";
        $items = Item::all();

        $handle = fopen('php://output', 'w');
        ob_start();

        fputcsv($handle, ['Kode', 'Nama', 'Kategori', 'Lokasi', 'Jumlah', 'Kondisi', 'Tanggal Input']);

        foreach ($items as $item) {
            fputcsv($handle, [
                $item->code,
                $item->name,
                $item->category,
                $item->location,
                $item->quantity,
                $item->condition,
                $item->created_at->format('Y-m-d'),
            ]);
        }

        fclose($handle);
        $content = ob_get_clean();

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    public function exportUserItems(Request $request, \App\Models\User $user)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $action = $request->input('action'); // 'export' or 'delete'

        $query = Item::where('user_id', $user->id);

        if ($year) {
            $query->whereYear('created_at', $year);
        }
        if ($month) {
            $query->whereMonth('created_at', $month);
        }

        $items = $query->get();

        if ($action === 'delete') {
            // Check if admin
            if (Auth::user()->role !== 'admin') {
                abort(403);
            }

            $count = $items->count();
            foreach ($items as $item) {
                // Gunakan Soft Delete agar history tetap aman (atau bisa direstore jika butuh)
                $item->delete();

                ItemLog::create([
                    'item_id' => $item->id,
                    'user_id' => Auth::id(),
                    'action' => 'delete',
                    'description' => 'Penghapusan massal berdasarkan periode',
                ]);
            }

            return back()->with('success', "$count data berhasil dihapus dari periode tersebut.");
        }

        // Default: Export
        $filename = "inventaris-" . $user->name . "-" . ($year ? $year : 'All') . "-" . ($month ? $month : 'All') . ".csv";

        $handle = fopen('php://output', 'w');
        ob_start();

        fputcsv($handle, ['Kode', 'Nama', 'Kategori', 'Lokasi', 'Jumlah', 'Kondisi', 'Tanggal Input']);

        foreach ($items as $item) {
            fputcsv($handle, [
                $item->code,
                $item->name,
                $item->category,
                $item->location,
                $item->quantity,
                $item->condition,
                $item->created_at->format('Y-m-d'),
            ]);
        }

        fclose($handle);
        $content = ob_get_clean();

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }
}
