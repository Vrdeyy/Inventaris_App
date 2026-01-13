@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Pusat Laporan & Export Excel</h1>
        <p class="text-gray-600">Pilih jenis laporan dan periode untuk export data ke Excel.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Monitoring Report Card (Daftar Barang) -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-300">
            <div class="p-6 border-b border-gray-50 bg-slate-50">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-emerald-600 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Laporan Daftar Barang</h2>
                        <p class="text-xs text-gray-500">Export daftar barang inventaris ke Excel</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.reports.generate') }}" method="GET" class="space-y-4">
                    <input type="hidden" name="report_type" value="items">
                    <input type="hidden" name="action" value="export">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Petugas</label>
                        <select name="user_id" required
                            class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm p-3 border">
                            <option value="">-- Pilih Petugas --</option>
                            <option value="all" class="font-bold">Semua Petugas</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>



                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kondisi</label>
                            <select name="condition"
                                class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm p-3 border">
                                <option value="">Semua</option>
                                <option value="baik">Baik</option>
                                <option value="rusak">Rusak</option>
                                <option value="hilang">Hilang</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                            <input type="text" name="category" placeholder="Misal: Elektronik"
                                class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm p-3 border">
                        </div>
                    </div>

                    <!-- Date Filter for Admin -->
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Filter Periode
                            </span>
                        </label>
                        
                        <div class="space-y-3">
                            <!-- Per Bulan/Tahun -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-gray-500 mb-1 block">Bulan Awal:</label>
                                    <select name="start_month"
                                        class="w-full rounded-lg border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm p-2 border">
                                        <option value="">Pilih</option>
                                        @foreach(range(1, 12) as $m)
                                            <option value="{{ $m }}">{{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$m] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 mb-1 block">Bulan Akhir:</label>
                                    <select name="end_month"
                                        class="w-full rounded-lg border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm p-2 border">
                                        <option value="">Pilih</option>
                                        @foreach(range(1, 12) as $m)
                                            <option value="{{ $m }}">{{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$m] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Tahun:</label>
                                <select name="year"
                                    class="w-full rounded-lg border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm p-2 border">
                                    <option value="">Semua Tahun</option>
                                    @foreach(range(now()->year, 2020) as $y)
                                        <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-3 p-2 bg-amber-50 rounded-lg text-xs text-amber-700">
                            <strong>💡 Tips:</strong> Jika memilih rentang bulan (misal Januari - Maret), setiap bulan akan menjadi sheet terpisah di Excel.
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition font-bold shadow-lg flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        <span>📊 Export Excel Daftar Barang</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- History Report Card (Riwayat Aktivitas) -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-300">
            <div class="p-6 border-b border-gray-50 bg-indigo-50">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-indigo-600 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Laporan Riwayat Aktivitas</h2>
                        <p class="text-xs text-gray-500">Export riwayat perubahan barang ke Excel</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.reports.generate') }}" method="GET" class="space-y-4">
                    <input type="hidden" name="report_type" value="history">
                    <input type="hidden" name="action" value="export">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Petugas</label>
                        <select name="user_id" required
                            class="w-full rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm p-3 border">
                            <option value="">-- Pilih Petugas --</option>
                            <option value="all" class="font-bold">Semua Petugas</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>



                    <!-- Date Filter for Admin -->
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Filter Periode
                            </span>
                        </label>
                        
                        <div class="space-y-3">
                            <!-- Per Bulan/Tahun -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-gray-500 mb-1 block">Bulan Awal:</label>
                                    <select name="start_month"
                                        class="w-full rounded-lg border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm p-2 border">
                                        <option value="">Pilih</option>
                                        @foreach(range(1, 12) as $m)
                                            <option value="{{ $m }}">{{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$m] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 mb-1 block">Bulan Akhir:</label>
                                    <select name="end_month"
                                        class="w-full rounded-lg border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm p-2 border">
                                        <option value="">Pilih</option>
                                        @foreach(range(1, 12) as $m)
                                            <option value="{{ $m }}">{{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$m] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Tahun:</label>
                                <select name="year"
                                    class="w-full rounded-lg border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm p-2 border">
                                    <option value="">Semua Tahun</option>
                                    @foreach(range(now()->year, 2020) as $y)
                                        <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-3 p-2 bg-amber-50 rounded-lg text-xs text-amber-700">
                            <strong>💡 Tips:</strong> Jika memilih rentang bulan (misal Januari - Maret), setiap bulan akan menjadi sheet terpisah di Excel.
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition font-bold shadow-lg flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        <span>📊 Export Excel Riwayat</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="mt-8 bg-gradient-to-r from-slate-50 to-gray-50 rounded-2xl p-6 border border-gray-200">
        <h3 class="font-bold text-gray-800 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Informasi Template Excel
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
            <div class="flex items-start space-x-2">
                <span class="text-emerald-600">✓</span>
                <p><strong>Template Daftar Barang:</strong> Kolom berisi No, Nama Barang, Kategori, Lokasi, Kondisi, Jumlah, Tanggal</p>
            </div>
            <div class="flex items-start space-x-2">
                <span class="text-emerald-600">✓</span>
                <p><strong>Template Riwayat:</strong> Kolom berisi No, Tanggal, Petugas, Barang, Aksi, Perubahan, Catatan</p>
            </div>
            <div class="flex items-start space-x-2">
                <span class="text-indigo-600">📝</span>
                <p><strong>Multi-Sheet:</strong> Jika export lebih dari 1 bulan, setiap bulan akan menjadi sheet terpisah (contoh: Januari, Februari, dst)</p>
            </div>
            <div class="flex items-start space-x-2">
                <span class="text-indigo-600">⚙️</span>
                <p><strong>Custom Template:</strong> <a href="{{ route('admin.templates.index') }}" class="text-indigo-600 hover:underline font-medium">Upload template custom di sini →</a></p>
            </div>
        </div>
    </div>
@push('scripts')
<script>
// Logic for multi-user export is now handled efficiently on the server-side 
// to prevent browser blocking and ensure data integrity.
</script>
@endpush
@endsection