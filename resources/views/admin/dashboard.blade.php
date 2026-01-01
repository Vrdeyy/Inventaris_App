@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Administrator Dashboard</h1>
        <p class="text-gray-600">Monitoring sistem dan pengelolaan user.</p>
    </div>

    <!-- Alerts -->
    @if(isset($alerts) && count($alerts) > 0)
        <div class="mb-8">
            @foreach($alerts as $alert)
                <div class="p-4 mb-2 bg-red-50 text-red-700 border border-red-200 rounded-lg flex items-center">
                    <span class="mr-2">⚠️</span> {{ $alert }}
                </div>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="text-gray-500 text-sm font-medium">Total Barang Terdata</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_items'] }}</div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="text-gray-500 text-sm font-medium">User Aktif</div>
            <div class="mt-2 text-3xl font-bold text-indigo-600">{{ $stats['active_users'] }}</div>
        </div>

        <!-- Monitoring Ringkas -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 col-span-1 lg:col-span-2">
            <div class="text-gray-500 text-sm font-medium mb-3">Kondisi Barang</div>
            <div class="flex items-center space-x-4">
                <div class="flex-1 bg-emerald-50 p-3 rounded-lg text-center">
                    <span class="block text-emerald-800 font-bold">{{ $stats['conditions']['baik'] }}</span>
                    <span class="text-xs text-emerald-600">Baik</span>
                </div>
                <div class="flex-1 bg-orange-50 p-3 rounded-lg text-center">
                    <span class="block text-orange-800 font-bold">{{ $stats['conditions']['rusak'] }}</span>
                    <span class="text-xs text-orange-600">Rusak</span>
                </div>
                <div class="flex-1 bg-red-50 p-3 rounded-lg text-center">
                    <span class="block text-red-800 font-bold">{{ $stats['conditions']['hilang'] }}</span>
                    <span class="text-xs text-red-600">Hilang</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.users.create') }}"
                    class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition flex justify-between items-center">
                    <span>Tambah User Baru</span>
                    <span class="text-gray-400">→</span>
                </a>
                <a href="{{ route('admin.monitoring') }}"
                    class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition flex justify-between items-center">
                    <span>Lihat Semua Barang</span>
                    <span class="text-gray-400">→</span>
                </a>
            </div>
        </div>

        <!-- Placeholder for charts or logs -->
        <div
            class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-center text-gray-400">
            Grafik Statistik (Coming Soon)
        </div>
    </div>
@endsection