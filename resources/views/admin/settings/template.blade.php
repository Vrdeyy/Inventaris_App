@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Pengaturan Template Excel</h1>
        <p class="text-gray-600">Unggah file Excel (.xlsx) custom untuk digunakan sebagai format laporan.</p>

        @if($errors->any())
            <div class="mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="mt-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg font-bold">
                ✅ {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Template Monitoring Barang -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group">
            <div class="p-6 border-b border-gray-50 bg-emerald-50">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-emerald-600 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Template Daftar Barang</h2>
                        <p class="text-xs text-gray-500">Digunakan saat export Excel dari menu Monitoring.</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <!-- Status File -->
                <div class="mb-6 flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-600">Status File Saat Ini:</span>
                    @if(Storage::exists('templates/template_items.xlsx'))
                        <div class="flex items-center text-emerald-600">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-xs font-bold uppercase">Tersedia</span>
                        </div>
                    @else
                        <span class="text-xs font-bold text-slate-400 uppercase">Belum Ada</span>
                    @endif
                </div>

                <!-- Kolom Info -->
                <div class="mb-4 p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                    <p class="text-xs font-bold text-emerald-700 mb-2">📋 Struktur Kolom yang Digunakan:</p>
                    <div class="grid grid-cols-7 gap-1 text-[10px] text-center">
                        <div class="bg-white p-1 rounded font-mono">A: No</div>
                        <div class="bg-white p-1 rounded font-mono">B: Nama</div>
                        <div class="bg-white p-1 rounded font-mono">C: Kategori</div>
                        <div class="bg-white p-1 rounded font-mono">D: Lokasi</div>
                        <div class="bg-white p-1 rounded font-mono">E: Kondisi</div>
                        <div class="bg-white p-1 rounded font-mono">F: Jumlah</div>
                        <div class="bg-white p-1 rounded font-mono">G: Tanggal</div>
                    </div>
                </div>

                <form action="{{ route('admin.templates.upload') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" value="items">

                    <div
                        class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:bg-gray-50 transition cursor-pointer relative">
                        <input type="file" name="file" accept=".xlsx" required
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="mt-1 text-sm text-gray-600">Klik untuk upload template (.xlsx)</p>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-bold shadow-sm">
                        Simpan Template Daftar Barang
                    </button>

                    @if(Storage::exists('templates/template_items.xlsx'))
                        <a href="{{ route('admin.templates.download', 'items') }}"
                            class="block text-center text-xs text-emerald-600 font-bold hover:underline mt-2">
                            ⬇️ Unduh Template Aktif
                        </a>
                    @endif
                </form>
            </div>
            <div class="bg-amber-50 p-4 text-xs text-amber-800 border-t border-amber-100">
                <strong>📌 Panduan:</strong> Baris ke-1 sampai ke-5 adalah Header/Kop Surat Anda. Sistem akan mulai
                mengisi data secara otomatis mulai dari <strong>Baris ke-6</strong>.
            </div>
        </div>

        <!-- Template Riwayat -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group">
            <div class="p-6 border-b border-gray-50 bg-indigo-50">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-indigo-600 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Template Riwayat Aktivitas</h2>
                        <p class="text-xs text-gray-500">Digunakan saat export Excel dari menu Riwayat.</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <!-- Status File -->
                <div class="mb-6 flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-600">Status File Saat Ini:</span>
                    @if(Storage::exists('templates/template_history.xlsx'))
                        <div class="flex items-center text-emerald-600">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-xs font-bold uppercase">Tersedia</span>
                        </div>
                    @else
                        <span class="text-xs font-bold text-slate-400 uppercase">Belum Ada</span>
                    @endif
                </div>

                <!-- Kolom Info -->
                <div class="mb-4 p-3 bg-indigo-50 rounded-lg border border-indigo-100">
                    <p class="text-xs font-bold text-indigo-700 mb-2">📋 Struktur Kolom yang Digunakan:</p>
                    <div class="grid grid-cols-7 gap-1 text-[10px] text-center">
                        <div class="bg-white p-1 rounded font-mono">A: No</div>
                        <div class="bg-white p-1 rounded font-mono">B: Tanggal</div>
                        <div class="bg-white p-1 rounded font-mono">C: Petugas</div>
                        <div class="bg-white p-1 rounded font-mono">D: Barang</div>
                        <div class="bg-white p-1 rounded font-mono">E: Aksi</div>
                        <div class="bg-white p-1 rounded font-mono">F: Perubahan</div>
                        <div class="bg-white p-1 rounded font-mono">G: Catatan</div>
                    </div>
                </div>

                <form action="{{ route('admin.templates.upload') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" value="history">

                    <div
                        class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:bg-gray-50 transition cursor-pointer relative">
                        <input type="file" name="file" accept=".xlsx" required
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="mt-1 text-sm text-gray-600">Klik untuk upload template (.xlsx)</p>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-bold shadow-sm">
                        Simpan Template Riwayat
                    </button>

                    @if(Storage::exists('templates/template_history.xlsx'))
                        <a href="{{ route('admin.templates.download', 'history') }}"
                            class="block text-center text-xs text-indigo-600 font-bold hover:underline mt-2">
                            ⬇️ Unduh Template Aktif
                        </a>
                    @endif
                </form>
            </div>
            <div class="bg-amber-50 p-4 text-xs text-amber-800 border-t border-amber-100">
                <strong>📌 Panduan:</strong> Sistem akan mengisi data mulai dari <strong>Baris ke-6</strong>. Anda bebas
                mendesain Header, Logo, dan Judul pada baris 1-5.
            </div>
        </div>
    </div>

    <!-- Multi-Sheet Info -->
    <div class="mt-8 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-100">
        <h3 class="font-bold text-gray-800 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            Fitur Multi-Sheet untuk Export
        </h3>
        <div class="text-sm text-gray-600 space-y-2">
            <p>Saat admin mengexport data dengan rentang <strong>lebih dari 1 bulan</strong>, sistem akan otomatis membuat
                sheet terpisah untuk setiap bulan:</p>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li>Nama sheet akan menggunakan nama bulan dalam Bahasa Indonesia (Januari, Februari, dst)</li>
                <li>Setiap sheet akan memiliki header yang sama sesuai template</li>
                <li>Data di setiap sheet hanya untuk bulan tersebut</li>
            </ul>
        </div>
    </div>

    <!-- User Access Info -->
    <div class="mt-4 bg-amber-50 rounded-2xl p-6 border border-amber-200">
        <h3 class="font-bold text-amber-800 mb-2 flex items-center">
            <span class="mr-2">⚠️</span> Perbedaan Akses Export
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-amber-800">
            <div class="bg-white p-3 rounded-lg">
                <p class="font-bold mb-1">👤 User (Petugas)</p>
                <ul class="list-disc list-inside text-xs space-y-1">
                    <li>Hanya bisa export <strong>Daftar Barang</strong></li>
                    <li>Periode: <strong>Bulan berjalan saja</strong> (Tgl 1 - akhir bulan)</li>
                    <li>Hanya data milik sendiri</li>
                </ul>
            </div>
            <div class="bg-white p-3 rounded-lg">
                <p class="font-bold mb-1">👨‍💼 Admin</p>
                <ul class="list-disc list-inside text-xs space-y-1">
                    <li>Bisa export <strong>Daftar Barang</strong> & <strong>Riwayat Aktivitas</strong></li>
                    <li>Periode: <strong>Per hari, bulan, atau tahun</strong></li>
                    <li>Multi-sheet untuk rentang lebih dari 1 bulan</li>
                    <li>Bisa mengelola template Excel</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('input[type="file"]');

            inputs.forEach(input => {
                input.addEventListener('change', function (e) {
                    const fileName = e.target.files[0]?.name;
                    const textContainer = e.target.nextElementSibling.querySelector('p');
                    const uploadBox = e.target.parentElement;

                    if (fileName) {
                        textContainer.textContent = "Terpilih: " + fileName;
                        textContainer.classList.add('font-bold', 'text-emerald-600');
                        uploadBox.classList.add('border-emerald-500', 'bg-emerald-50');
                        uploadBox.classList.remove('border-gray-200');
                    } else {
                        textContainer.textContent = "Klik untuk upload template (.xlsx)";
                        textContainer.classList.remove('font-bold', 'text-emerald-600');
                        uploadBox.classList.remove('border-emerald-500', 'bg-emerald-50');
                        uploadBox.classList.add('border-gray-200');
                    }
                });
            });
        });
    </script>
@endsection