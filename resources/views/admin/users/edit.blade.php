@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Edit User: {{ $user->name }}</h1>
            <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-800">← Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Petugas (User)</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reset Password (Opsional)</label>
                        <input type="password" name="password"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            minlength="8" placeholder="Biarkan kosong jika tidak ingin mengubah">
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" id="is_active"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            {{ $user->is_active && !$user->trashed() ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">Akun Aktif</label>
                    </div>

                    @if($user->trashed())
                        <div class="flex items-center">
                            <input type="checkbox" name="restore" value="1" id="restore"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm">
                            <label for="restore" class="ml-2 block text-sm text-gray-900">Restore Akun (Batalkan Hapus)</label>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end pt-6 mt-6 border-t border-gray-100">
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm">Update
                        User</button>
                </div>
            </form>
        </div>
    </div>
@endsection