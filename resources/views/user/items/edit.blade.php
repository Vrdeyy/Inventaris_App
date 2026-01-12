@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Edit Barang: {{ $item->name }}</h1>
            <a href="{{ route('user.items.index') }}" class="text-indigo-600 hover:text-indigo-800">← Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('user.items.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Kode (Read Only) -->


                    <!-- Kategori (Read Only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori (Gak Bisa Diubah)</label>
                        <input type="text" value="{{ $item->category }}"
                            class="w-full rounded-lg bg-gray-50 border-gray-200 text-gray-500 p-2 border cursor-not-allowed"
                            readonly>
                    </div>

                    <!-- Nama -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang</label>
                        <input type="text" name="name" value="{{ old('name', $item->name) }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                        <input type="text" name="location" value="{{ old('location', $item->location) }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                    </div>

                    <!-- Penempatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Penempatan</label>
                        <select name="placement_type"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                            <option value="dalam_ruang" {{ old('placement_type', $item->placement_type) == 'dalam_ruang' ? 'selected' : '' }}>Dalam Ruang</option>
                            <option value="dalam_lemari" {{ old('placement_type', $item->placement_type) == 'dalam_lemari' ? 'selected' : '' }}>Dalam Lemari</option>
                        </select>
                    </div>
                </div>

                <!-- Breakdown Jumlah per Kondisi -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">📦 Update Jumlah per Kondisi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Jumlah Baik -->
                        <div>
                            <label class="block text-sm font-medium text-emerald-700 mb-2">
                                <span class="inline-block w-3 h-3 bg-emerald-500 rounded-full mr-1"></span>
                                Jumlah Baik
                            </label>
                            <input type="number" name="qty_baik" value="{{ old('qty_baik', $item->qty_baik) }}" min="0"
                                id="qty_baik"
                                class="w-full rounded-lg border-emerald-300 focus:ring-emerald-500 focus:border-emerald-500 p-2 border bg-emerald-50"
                                oninput="calculateTotal()">
                        </div>

                        <!-- Jumlah Rusak -->
                        <div>
                            <label class="block text-sm font-medium text-orange-700 mb-2">
                                <span class="inline-block w-3 h-3 bg-orange-500 rounded-full mr-1"></span>
                                Jumlah Rusak
                            </label>
                            <input type="number" name="qty_rusak" value="{{ old('qty_rusak', $item->qty_rusak) }}" min="0"
                                id="qty_rusak"
                                class="w-full rounded-lg border-orange-300 focus:ring-orange-500 focus:border-orange-500 p-2 border bg-orange-50"
                                oninput="calculateTotal()">
                        </div>

                        <!-- Jumlah Hilang -->
                        <div>
                            <label class="block text-sm font-medium text-red-700 mb-2">
                                <span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-1"></span>
                                Jumlah Hilang
                            </label>
                            <input type="number" name="qty_hilang" value="{{ old('qty_hilang', $item->qty_hilang) }}"
                                min="0" id="qty_hilang"
                                class="w-full rounded-lg border-red-300 focus:ring-red-500 focus:border-red-500 p-2 border bg-red-50"
                                oninput="calculateTotal()">
                        </div>
                    </div>

                    <!-- Total Calculated -->
                    <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between">
                        <div>
                            <span class="text-sm font-medium text-gray-600">Total Baru:</span>
                            <span id="total_display" class="text-xl font-bold text-indigo-600">{{ $item->quantity }}</span>
                            <input type="hidden" name="quantity" id="quantity" value="{{ $item->quantity }}">
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-500 block">Target Total Barang:</span>
                            <span class="text-sm font-bold text-gray-700">{{ $item->quantity }} Unit</span>
                            <input type="hidden" id="target_total" value="{{ $item->quantity }}">
                        </div>
                    </div>
                </div>

                <!-- Alert jika jumlah tidak pas -->
                <div id="quantity_alert"
                    class="hidden mb-6 p-3 bg-red-100 border border-red-200 text-red-700 rounded-lg text-sm">
                    ⚠️ <strong>Peringatan:</strong> Total barang yang Anda masukkan (<span id="alert_total">0</span>)
                    tidak sama dengan jumlah stok awal ({{ $item->quantity }}).
                    Pastikan Anda tidak mengada-ngada data jumlah barang.
                </div>

                <!-- Catatan Perubahan (Wajib) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Perubahan (Penting untuk
                        Histori)</label>
                    <textarea name="description" rows="3"
                        placeholder="Contoh: Perbaikan rutin, Barang jatuh, atau Penambahan stok baru"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                        required>{{ old('description') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500 italic">Harap jelaskan kenapa data ini diubah.</p>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm font-bold">
                        Update Data Barang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calculateTotal() {
            const targetTotal = parseInt(document.getElementById('target_total').value) || 0;
            const qtyBaik = parseInt(document.getElementById('qty_baik').value) || 0;
            const qtyRusak = parseInt(document.getElementById('qty_rusak').value) || 0;
            const qtyHilang = parseInt(document.getElementById('qty_hilang').value) || 0;

            const total = qtyBaik + qtyRusak + qtyHilang;

            const display = document.getElementById('total_display');
            const alertBox = document.getElementById('quantity_alert');
            const alertTotal = document.getElementById('alert_total');
            const btnSubmit = document.querySelector('button[type="submit"]');

            display.textContent = total;
            document.getElementById('quantity').value = total;

            // Logika agar tidak mengada-ngada
            if (total !== targetTotal) {
                display.classList.remove('text-indigo-600');
                display.classList.add('text-red-600');
                alertBox.classList.remove('hidden');
                alertTotal.textContent = total;

                // Opsional: Jika ingin benar-benar melarang simpan jika tidak pas
                // btnSubmit.disabled = true;
                // btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                display.classList.remove('text-red-600');
                display.classList.add('text-indigo-600');
                alertBox.classList.add('hidden');
                // btnSubmit.disabled = false;
                // btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        // Calculate on page load
        document.addEventListener('DOMContentLoaded', calculateTotal);
    </script>
@endsection