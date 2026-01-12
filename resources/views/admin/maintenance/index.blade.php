@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Pemeliharaan Sistem</h1>
        <p class="text-gray-600">Alat bantu untuk mengelola kapasitas database dan mereset data inventaris.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Section 1: Pengelolaan Log Riwayat -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-amber-500 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-white">Manajemen Riwayat (Log)</h2>
                    <span
                        class="bg-amber-600 text-white text-xs px-2 py-1 rounded-full font-bold">{{ number_format($totalLogs) }}
                        Baris</span>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-6">Bersihkan riwayat perubahan untuk mempercepat performa database.
                        Sangat disarankan untuk mengarsipkan data sebelum menghapusnya.</p>

                    <!-- Form 1: Pembersihan Periodik -->
                    <form action="{{ route('admin.maintenance.clear_history') }}" method="POST"
                        onsubmit="return confirm('APAKAH ANDA YAKIN? Data riwayat akan dihapus secara PERMANEN.');"
                        class="space-y-4 mb-8">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pilih Bulan</label>
                                <select name="month" class="w-full rounded-lg border-gray-300 text-sm">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                            {{ ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$m] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pilih Tahun</label>
                                <select name="year" class="w-full rounded-lg border-gray-300 text-sm">
                                    @foreach($logYears as $y)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jenis Aksi</label>
                            <div class="flex gap-4">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="mode" value="only_period" checked class="text-amber-600">
                                    <span class="text-sm">Hanya bulan terpilih</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="mode" value="before_period" class="text-amber-600">
                                    <span class="text-sm">Semua sebelum bulan terpilih</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-2 bg-amber-100 text-amber-800 font-bold border border-amber-200 rounded-lg hover:bg-amber-200 transition">
                            Hapus Riwayat Secara Permanen
                        </button>
                    </form>

                    <hr class="my-6">

                    <!-- Opsi Arsip & Reset -->
                    <div class="bg-gray-50 border border-gray-100 rounded-lg p-4">
                        <h3 class="text-sm font-bold text-gray-800 mb-2">Arsip & Reset Total</h3>
                        <p class="text-xs text-gray-500 mb-4">Export seluruh riwayat dari awal sistem hingga hari ini ke
                            Excel, lalu kosongkan total tabel riwayat.</p>
                        <form action="{{ route('admin.maintenance.clear_history') }}" method="POST"
                            onsubmit="return confirm('Sistem akan mendownload semua riwayat lalu MENGHAPUS SEMUA LOG. Lanjutkan?');">
                            @csrf
                            <input type="hidden" name="mode" value="archive_all">
                            <button type="submit"
                                class="flex items-center justify-center w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-bold shadow-sm">
                                📊 Export Arsip & Kosongkan Log
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Reset Data Barang -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-red-600 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-white">Reset Data Barang (Aset)</h2>
                    <span
                        class="bg-red-700 text-white text-xs px-2 py-1 rounded-full font-bold">{{ number_format($totalItems) }}
                        Item</span>
                </div>
                <div class="p-6">
                    <div
                        class="flex items-start space-x-3 bg-red-50 p-4 rounded-lg mb-6 border border-red-100 text-red-800">
                        <span class="text-xl">⚠️</span>
                        <div class="text-xs">
                            <p class="font-bold mb-1">PERHATIAN KERAS!</p>
                            <p>Tindakan ini akan menghapus data barang (aset) secara permanen. User/Petugas harus menginput
                                ulang data dari nol jika dihapus.</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.maintenance.clear_items') }}" method="POST" id="resetItemsForm"
                        class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Target Reset</label>
                            <select name="target_user"
                                class="w-full rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
                                <option value="all" class="font-bold text-red-600">-- RESET SELURUH BARANG (SEMUA USER) --
                                </option>
                                <optgroup label="Reset Berdasarkan Petugas">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">Milik: {{ $user->name }}
                                            ({{ $user->items_count ?? 'Data ada' }})</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>

                        <div class="flex items-center space-x-2 p-3 bg-emerald-50 border border-emerald-100 rounded-lg">
                            <input type="checkbox" name="archive" value="1" id="archive_items" checked class="rounded text-emerald-600">
                            <label for="archive_items" class="text-sm text-emerald-800 font-medium">Download Arsip Excel sebelum dihapus (Sangat Disarankan)</label>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-3">
                            <label class="block text-xs font-bold text-gray-600">Verifikasi Keamanan</label>
                            <p class="text-xs text-gray-500 italic">Ketik kata <span
                                    class="text-red-600 font-bold">RESET</span> di bawah untuk mengaktifkan tombol:</p>
                            <input type="text" id="security_verify" placeholder="..."
                                class="w-full rounded-lg border-gray-300 text-sm p-2 uppercase">
                        </div>

                        <button type="submit" id="btnResetItems" disabled
                            class="w-full py-3 bg-gray-200 text-gray-400 font-bold rounded-lg cursor-not-allowed transition">
                            Reset Data Barang Terpilih
                        </button>
                    </form>
                </div>
            </div>

            <!-- Catatan Keamanan -->
            <div class="bg-indigo-900 rounded-xl p-6 text-white">
                <h3 class="font-bold mb-2 flex items-center">
                    <span class="mr-2">💡</span> Tips Pemeliharaan
                </h3>
                <ul class="text-xs text-indigo-200 space-y-2 list-disc list-inside">
                    <li>Lakukan backup database secara manual sebelum melakukan pembersihan besar-besaran.</li>
                    <li>Pembersihan log setiap 6 bulan sekali sangat disarankan untuk menjaga kecepatan dashboard.</li>
                    <li>Data User dan Akun Password TIDAK AKAN terhapus oleh fitur ini.</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        // Script untuk proteksi tombol Reset Data Barang
        const inputVerify = document.getElementById('security_verify');
        const btnReset = document.getElementById('btnResetItems');

        inputVerify.addEventListener('input', function () {
            if (this.value.toUpperCase() === 'RESET') {
                btnReset.disabled = false;
                btnReset.classList.remove('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
                btnReset.classList.add('bg-red-600', 'text-white', 'hover:bg-red-700');
            } else {
                btnReset.disabled = true;
                btnReset.classList.add('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
                btnReset.classList.remove('bg-red-600', 'text-white', 'hover:bg-red-700');
            }
        });

        const resetForm = document.getElementById('resetItemsForm');
        resetForm.onsubmit = function () {
            return confirm('Tindakan ini BERBAHAYA dan tidak bisa dibatalkan. Lanjutkan?');
        };
    </script>
@endsection