@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tambah Barang Baru</h1>
                <p class="text-gray-500 mt-1">Isi formulir di bawah ini untuk mendaftarkan aset baru.</p>
            </div>
            <a href="{{ route('user.items.index') }}"
                class="group flex items-center text-sm font-bold text-gray-500 hover:text-indigo-600 transition-colors bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 hover:border-indigo-100 hover:shadow-md">
                <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
            <div class="bg-gray-50/50 px-8 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-red-400"></span>
                    <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                    <span class="w-3 h-3 rounded-full bg-green-400"></span>
                </div>
                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">Form Input Inventaris</div>
            </div>

            <form action="{{ route('user.items.store') }}" method="POST" class="p-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Nama -->
                    <div class="md:col-span-2 group">
                        <label
                            class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">Nama
                            Barang</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </span>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all font-medium text-gray-800 placeholder-gray-400"
                                placeholder="Contoh: Laptop Asus ROG" required>
                        </div>
                    </div>

                    <!-- Kategori -->
                    <div class="group">
                        <label
                            class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">Kategori</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                    </path>
                                </svg>
                            </span>
                            <input type="text" name="category" value="{{ old('category') }}"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all font-medium text-gray-800 placeholder-gray-400"
                                placeholder="Contoh: Elektronik" required>
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div class="group">
                        <label
                            class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">Lokasi
                            Penyimpanan</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </span>
                            <input type="text" name="location" value="{{ old('location') }}"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all font-medium text-gray-800 placeholder-gray-400"
                                placeholder="Contoh: Lab Komputer 1" required>
                        </div>
                    </div>

                    <!-- Penempatan -->
                    <div class="group">
                        <label
                            class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">Jenis
                            Penempatan</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                            </span>
                            <select name="placement_type"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all font-medium text-gray-800 appearance-none"
                                required>
                                <option value="dalam_ruang" {{ old('placement_type') == 'dalam_ruang' ? 'selected' : '' }}>
                                    Dalam Ruang</option>
                                <option value="dalam_lemari" {{ old('placement_type') == 'dalam_lemari' ? 'selected' : '' }}>
                                    Dalam Lemari</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Input -->
                    <div class="group">
                        <label
                            class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">Tanggal
                            Input</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </span>
                            <input type="date" name="date_input" value="{{ old('date_input', date('Y-m-d')) }}"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all font-medium text-gray-800"
                                required>
                        </div>
                    </div>
                </div>

                <!-- Breakdown Jumlah per Kondisi -->
                <div
                    class="mb-8 p-6 bg-gray-50 rounded-2xl border border-gray-100 relative group transition-all hover:bg-indigo-50/30 hover:border-indigo-100">
                    <div class="absolute -top-3 left-4">
                        <span
                            class="bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md uppercase tracking-wide">📦
                            Detail Kondisi</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-2">
                        <!-- Jumlah Baik -->
                        <div
                            class="relative p-4 bg-white rounded-xl border border-gray-100 shadow-sm hover:border-emerald-200 transition-colors duration-200 group/item">
                            <label class="flex items-center text-sm font-bold text-emerald-700 mb-2">
                                <div
                                    class="w-6 h-6 rounded-lg bg-emerald-100 flex items-center justify-center mr-2 text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                Jumlah Baik
                            </label>
                            <input type="number" name="qty_baik" value="{{ old('qty_baik', 0) }}" min="0" id="qty_baik"
                                class="w-full text-center text-2xl font-black text-gray-700 border-0 border-b-2 border-emerald-100 focus:border-emerald-500 focus:ring-0 rounded-none bg-transparent p-2 transition-all"
                                oninput="calculateTotal()" onclick="this.select()">
                        </div>

                        <!-- Jumlah Rusak -->
                        <div
                            class="relative p-4 bg-white rounded-xl border border-gray-100 shadow-sm hover:border-orange-200 transition-colors duration-200 group/item">
                            <label class="flex items-center text-sm font-bold text-orange-700 mb-2">
                                <div
                                    class="w-6 h-6 rounded-lg bg-orange-100 flex items-center justify-center mr-2 text-orange-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                </div>
                                Jumlah Rusak
                            </label>
                            <input type="number" name="qty_rusak" value="{{ old('qty_rusak', 0) }}" min="0" id="qty_rusak"
                                class="w-full text-center text-2xl font-black text-gray-700 border-0 border-b-2 border-orange-100 focus:border-orange-500 focus:ring-0 rounded-none bg-transparent p-2 transition-all"
                                oninput="calculateTotal()" onclick="this.select()">
                        </div>

                        <!-- Jumlah Hilang -->
                        <div
                            class="relative p-4 bg-white rounded-xl border border-gray-100 shadow-sm hover:border-red-200 transition-colors duration-200 group/item">
                            <label class="flex items-center text-sm font-bold text-red-700 mb-2">
                                <div
                                    class="w-6 h-6 rounded-lg bg-red-100 flex items-center justify-center mr-2 text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                                Jumlah Hilang
                            </label>
                            <input type="number" name="qty_hilang" value="{{ old('qty_hilang', 0) }}" min="0"
                                id="qty_hilang"
                                class="w-full text-center text-2xl font-black text-gray-700 border-0 border-b-2 border-red-100 focus:border-red-500 focus:ring-0 rounded-none bg-transparent p-2 transition-all"
                                oninput="calculateTotal()" onclick="this.select()">
                        </div>
                    </div>

                    <!-- Total Calculated -->
                    <div
                        class="mt-6 flex items-center justify-between p-4 bg-indigo-600 rounded-xl text-white shadow-lg shadow-indigo-200">
                        <span class="text-sm font-bold uppercase tracking-wider opacity-80">Total Unit Barang:</span>
                        <div class="flex items-center">
                            <span id="total_display" class="text-3xl font-black tracking-tight">0</span>
                            <span class="text-sm ml-1 opacity-75 font-medium">pcs</span>
                        </div>
                        <input type="hidden" name="quantity" id="quantity" value="0">
                    </div>
                </div>

                <div class="flex items-center justify-end pt-6 border-t border-gray-100">
                    <button type="button" onclick="window.history.back()"
                        class="mr-4 px-6 py-3 rounded-xl text-sm font-bold text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-all">Batal</button>
                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-xl hover:shadow-lg hover:shadow-indigo-300 hover:-translate-y-0.5 transition-all duration-300 font-bold flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Barang
                    </button>
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