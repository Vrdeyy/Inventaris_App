@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Tambah Barang Baru</h1>
            <a href="{{ route('user.items.index') }}" class="text-indigo-600 hover:text-indigo-800">← Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('user.items.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Kode -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Barang</label>
                        <input type="text" name="code" value="{{ old('code') }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                    </div>

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <input type="text" name="category" value="{{ old('category') }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                        <input type="text" name="location" value="{{ old('location') }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                    </div>

                    <!-- Jumlah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                        <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="0"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                    </div>

                    <!-- Kondisi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Awal</label>
                        <select name="condition"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                            <option value="baik">Baik</option>
                            <option value="rusak">Rusak</option>
                            <option value="hilang">Hilang</option>
                        </select>
                    </div>

                    <!-- Tanggal Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Input</label>
                        <input type="date" name="date_input" value="{{ old('date_input', date('Y-m-d')) }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm">Simpan
                        Barang</button>
                </div>
            </form>
        </div>
    </div>
@endsection