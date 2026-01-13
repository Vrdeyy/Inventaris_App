<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withTrashed()->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,user',
            'password' => 'nullable|string|min:8',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_active' => $request->has('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        // Restore if active checks? No, just update. If deleted, we should restore if asked?
        // Let's keep it simple: soft deleted users are listed. Update should work.
        // But if they want to restore, we might need a specific action. For now, update works on soft deleted models if we fetch them.

        if ($user->trashed() && $request->has('restore')) {
            $user->restore();
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if ($id == auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user = User::withTrashed()->findOrFail($id);

        if ($user->trashed()) {
            // Use database transaction for safety
            \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
                // 1. Delete all logs WHERE this user was the person who made the change
                \App\Models\ItemLog::where('user_id', $user->id)->delete();

                // 2. Delete all items belonging to this user
                // Logs for these items will be automatically deleted because of onDelete('cascade') in migration
                \App\Models\Item::where('user_id', $user->id)->forceDelete();

                // 3. Finally, force delete the user
                $user->forceDelete();
            });
            $message = 'User dan seluruh data terkait berhasil dihapus secara permanen.';
        } else {
            $user->delete();
            $message = 'User berhasil dinonaktifkan (Soft Delete).';
        }

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        $user->update(['is_active' => true]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diaktifkan kembali.');
    }
}
