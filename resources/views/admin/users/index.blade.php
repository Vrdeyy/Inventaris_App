@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>
        <a href="{{ route('admin.users.create') }}"
            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm">
            + Tambah User
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 uppercase">{{ $user->role }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->trashed())
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Deleted</span>
                            @elseif($user->is_active)
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">


                            @if(!$user->trashed())
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                            @endif

                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('{{ $user->trashed() ? 'Hapus permanen user ini?' : 'Nonaktifkan user ini?' }}');">
                                    @csrf
                                    @method('DELETE')
                                    @if($user->trashed())
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-xs uppercase underline">Delete Permanently</button>
                                    @else
                                        <button type="submit" class="text-amber-600 hover:text-amber-900">Deactivate</button>
                                    @endif
                                </form>

                                @if($user->trashed())
                                    <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Aktifkan kembali user ini?');">
                                        @csrf
                                        <button type="submit" class="ml-3 text-emerald-600 hover:text-emerald-900">Restore</button>
                                    </form>
                                @endif
                            @else
                                <span class="text-gray-400 italic">Akun Anda</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>
@endsection