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
                    <!-- Nama -->
                    <div class="md:col-span-2">
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

                    <!-- Penempatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Penempatan</label>
                        <select name="placement_type"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                            <option value="dalam_ruang" {{ old('placement_type') == 'dalam_ruang' ? 'selected' : '' }}>Dalam
                                Ruang</option>
                            <option value="dalam_lemari" {{ old('placement_type') == 'dalam_lemari' ? 'selected' : '' }}>Dalam
                                Lemari</option>
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

                <!-- Breakdown Jumlah per Kondisi -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">📦 Input Jumlah per Kondisi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Jumlah Baik -->
                        <div>
                            <label class="block text-sm font-medium text-emerald-700 mb-2">
                                <span class="inline-block w-3 h-3 bg-emerald-500 rounded-full mr-1"></span>
                                Jumlah Baik
                            </label>
                            <input type="number" name="qty_baik" value="{{ old('qty_baik', 0) }}" min="0" id="qty_baik"
                                class="w-full rounded-lg border-emerald-300 focus:ring-emerald-500 focus:border-emerald-500 p-2 border bg-emerald-50"
                                oninput="calculateTotal()">
                        </div>

                        <!-- Jumlah Rusak -->
                        <div>
                            <label class="block text-sm font-medium text-orange-700 mb-2">
                                <span class="inline-block w-3 h-3 bg-orange-500 rounded-full mr-1"></span>
                                Jumlah Rusak
                            </label>
                            <input type="number" name="qty_rusak" value="{{ old('qty_rusak', 0) }}" min="0" id="qty_rusak"
                                class="w-full rounded-lg border-orange-300 focus:ring-orange-500 focus:border-orange-500 p-2 border bg-orange-50"
                                oninput="calculateTotal()">
                        </div>

                        <!-- Jumlah Hilang -->
                        <div>
                            <label class="block text-sm font-medium text-red-700 mb-2">
                                <span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-1"></span>
                                Jumlah Hilang
                            </label>
                            <input type="number" name="qty_hilang" value="{{ old('qty_hilang', 0) }}" min="0"
                                id="qty_hilang"
                                class="w-full rounded-lg border-red-300 focus:ring-red-500 focus:border-red-500 p-2 border bg-red-50"
                                oninput="calculateTotal()">
                        </div>
                    </div>

                    <!-- Total Calculated -->
                    <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Total Jumlah:</span>
                        <span id="total_display" class="text-xl font-bold text-indigo-600">0</span>
                        <input type="hidden" name="quantity" id="quantity" value="0">
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

    <script>
        function calculateTotal() {
            const qtyBaik = parseInt(document.getElementById('qty_baik').value) || 0;
            const qtyRusak = parseInt(document.getElementById('qty_rusak').value) || 0;
            const qtyHilang = parseInt(document.getElementById('qty_hilang').value) || 0;
            const total = qtyBaik + qtyRusak + qtyHilang;

            document.getElementById('total_display').textContent = total;
            document.getElementById('quantity').value = total;
        }

        // Calculate on page load
        document.addEventListener('DOMContentLoaded', calculateTotal);
    </script>
@endsection