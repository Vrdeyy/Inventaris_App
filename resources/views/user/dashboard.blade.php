@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Petugas</h1>
        <p class="text-gray-600">Selamat datang kembali, mari kelola inventaris dengan baik.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card Total -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="text-gray-500 text-sm font-medium">Total Barang</div>
            <div class="mt-2 flex items-baseline">
                <div class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                <span class="ml-2 text-sm text-gray-500">Unit</span>
            </div>
        </div>

        <!-- Card Baik -->
        <div class="bg-emerald-50 p-6 rounded-xl border border-emerald-100">
            <div class="text-emerald-700 text-sm font-medium">Kondisi Baik</div>
            <div class="mt-2 text-3xl font-bold text-emerald-900">{{ $stats['baik'] }}</div>
        </div>

        <!-- Card Rusak -->
        <div class="bg-orange-50 p-6 rounded-xl border border-orange-100">
            <div class="text-orange-700 text-sm font-medium">Rusak</div>
            <div class="mt-2 text-3xl font-bold text-orange-900">{{ $stats['rusak'] }}</div>
        </div>

        <!-- Card Hilang -->
        <div class="bg-red-50 p-6 rounded-xl border border-red-100">
            <div class="text-red-700 text-sm font-medium">Hilang</div>
            <div class="mt-2 text-3xl font-bold text-red-900">{{ $stats['hilang'] }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Shortcut Cepat</h2>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('user.items.create') }}"
                class="flex items-center px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm">
                <span class="mr-2">➕</span> Tambah Barang
            </a>
            <a href="{{ route('user.items.index') }}"
                class="flex items-center px-4 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                <span class="mr-2">📋</span> Data Barang
            </a>
            <a href="{{ route('user.items.export') }}"
                class="flex items-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-sm">
                <span class="mr-2">📤</span> Export Excel
            </a>
        </div>
    </div>
@endsection