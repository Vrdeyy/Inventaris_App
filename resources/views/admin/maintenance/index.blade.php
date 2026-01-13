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
                        class="space-y-4">
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

                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-3 px-1">Jenis Aksi
                                Pembersihan</label>
                            <div class="space-y-3">
                                <label
                                    class="flex items-center p-3 bg-white border border-gray-200 rounded-lg cursor-pointer hover:border-amber-500 transition-colors group">
                                    <input type="radio" name="mode" value="only_period" checked
                                        class="w-4 h-4 text-amber-600 focus:ring-amber-500 border-gray-300">
                                    <div class="ml-3">
                                        <span class="block text-sm font-bold text-gray-800">Hanya di bulan terpilih</span>
                                        <span class="block text-[10px] text-gray-500">Hapus permanen log yang dibuat pada
                                            bulan & tahun ini.</span>
                                    </div>
                                </label>
                                <label
                                    class="flex items-center p-3 bg-white border border-gray-200 rounded-lg cursor-pointer hover:border-amber-500 transition-colors group">
                                    <input type="radio" name="mode" value="before_period"
                                        class="w-4 h-4 text-amber-600 focus:ring-amber-500 border-gray-300">
                                    <div class="ml-3">
                                        <span class="block text-sm font-bold text-gray-800">Semua sebelum bulan
                                            terpilih</span>
                                        <span class="block text-[10px] text-gray-500">Hapus semua riwayat yang ada sebelum
                                            tanggal 1
                                            {{ ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][now()->month] }}.</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                            <input type="checkbox" name="archive" value="1" id="archive_history"
                                class="rounded text-blue-600">
                            <label for="archive_history" class="text-xs text-blue-800 font-medium">Download arsip filter ini
                                ke Excel sebelum dihapus</label>
                        </div>

                        <button type="submit"
                            class="w-full py-2 bg-amber-100 text-amber-800 font-bold border border-amber-200 rounded-lg hover:bg-amber-200 transition">
                            Hapus Riwayat Secara Permanen
                        </button>
                    </form>
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
                            <input type="checkbox" name="archive" value="1" id="archive_items" checked
                                class="rounded text-emerald-600">
                            <label for="archive_items" class="text-sm text-emerald-800 font-medium">Download Arsip Excel
                                sebelum dihapus (Sangat Disarankan)</label>
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