@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Monitoring Barang Per User</h1>
        <p class="text-gray-600">Pantau kondisi aset yang dipegang oleh setiap petugas.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($users as $user)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">{{ $user->name }}</h3>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-2 py-1 bg-gray-100 text-gray-600 rounded">User</span>
                    </div>

                    <div class="grid grid-cols-3 gap-2 mb-4 text-center">
                        <div class="bg-emerald-50 p-2 rounded-lg">
                            <span class="block text-lg font-bold text-emerald-700">{{ $user->baik_items }}</span>
                            <span class="text-xs text-emerald-600">Baik</span>
                        </div>
                        <div class="bg-orange-50 p-2 rounded-lg">
                            <span class="block text-lg font-bold text-orange-700">{{ $user->rusak_items }}</span>
                            <span class="text-xs text-orange-600">Rusak</span>
                        </div>
                        <div class="bg-red-50 p-2 rounded-lg">
                            <span class="block text-lg font-bold text-red-700">{{ $user->hilang_items }}</span>
                            <span class="text-xs text-red-600">Hilang</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center text-sm border-t border-gray-100 pt-4">
                        <span class="text-gray-500">Total: <strong>{{ $user->total_items }}</strong> Barang</span>
                        <a href="{{ route('admin.monitoring.user', $user->id) }}"
                            class="text-indigo-600 hover:text-indigo-800 font-medium">Lihat Detail →</a>
                    </div>
                </div>
            </div>
        @endforeach

        @if($users->isEmpty())
            <div class="col-span-full text-center py-10 bg-white rounded-xl border border-dashed border-gray-300">
                <p class="text-gray-500">Belum ada user yang terdaftar.</p>
            </div>
        @endif
    </div>
@endsection