@extends('layouts.app')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard Petugas</h1>
            <p class="text-gray-500 mt-1">Selamat datang kembali, mari kelola inventaris dengan teliti.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('user.items.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg hover:bg-indigo-700 hover:-translate-y-0.5 transition-all duration-200">
                <span class="mr-2">➕</span> Tambah Barang Baru
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Card Total -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center group hover:border-indigo-200 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mr-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20 7h-4V5c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zM10 5h4v2h-4V5zm10 15H4V9h16v11z"/></svg>
            </div>
            <div>
                <div class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Total Kelolaan</div>
                <div class="text-2xl font-black text-gray-900 leading-none mt-1">{{ $stats['total'] }} <span class="text-gray-400 text-xs font-bold uppercase">Unit</span></div>
            </div>
        </div>

        <!-- Card Baik -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center group hover:border-emerald-200 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mr-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            </div>
            <div>
                <div class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Kondisi Baik</div>
                <div class="text-2xl font-black text-emerald-600 leading-none mt-1">{{ $stats['baik'] }}</div>
            </div>
        </div>

        <!-- Card Rusak -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center group hover:border-orange-200 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center mr-4 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
            </div>
            <div>
                <div class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Rusak</div>
                <div class="text-2xl font-black text-orange-600 leading-none mt-1">{{ $stats['rusak'] }}</div>
            </div>
        </div>

        <!-- Card Hilang -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center group hover:border-red-200 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center mr-4 group-hover:bg-red-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
            </div>
            <div>
                <div class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Hilang</div>
                <div class="text-2xl font-black text-red-600 leading-none mt-1">{{ $stats['hilang'] }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Shortcut & Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 overflow-hidden relative">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-50 rounded-full opacity-50"></div>
                <div class="relative">
                    <h3 class="text-xl font-extrabold text-gray-800 mb-6 flex items-center">
                        <span class="w-1.5 h-6 bg-indigo-600 rounded-full mr-3"></span>
                        Aksi Cepat Petugas
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('user.items.index') }}" 
                           class="flex items-center p-4 bg-white border border-gray-200 rounded-2xl hover:border-indigo-600 hover:shadow-md transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mr-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div class="text-left">
                                <span class="block text-sm font-bold text-gray-800">Daftar Barang</span>
                                <span class="text-[10px] text-gray-500 font-medium">Lihat dan update aset Anda</span>
                            </div>
                        </a>
                        <a href="{{ route('user.items.export') }}" 
                           class="flex items-center p-4 bg-white border border-gray-200 rounded-2xl hover:border-emerald-600 hover:shadow-md transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mr-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div class="text-left">
                                <span class="block text-sm font-bold text-gray-800">Cetak Laporan</span>
                                <span class="text-[10px] text-gray-500 font-medium">Export Excel bulan berjalan</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl p-8 text-white shadow-xl relative overflow-hidden">
                <div class="absolute right-0 bottom-0 opacity-10">
                    <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                </div>
                <div class="relative z-10">
                    <h3 class="text-lg font-bold mb-3 flex items-center">
                        <span class="mr-2">💡</span> Informasi Penting
                    </h3>
                    <p class="text-sm text-gray-300 leading-relaxed mb-4">
                        Setiap perubahan data barang (jumlah atau kondisi) akan tercatat dalam log aktivitas sistem. Pastikan Anda mengisi <span class="text-white font-bold italic">Catatan Perubahan</span> dengan jelas untuk memudahkan Admin dalam melakukan audit bulanan.
                    </p>
                    <div class="inline-flex items-center px-4 py-2 bg-indigo-500/20 border border-indigo-400/30 rounded-lg text-xs font-bold text-indigo-300">
                        Periode Aktif: {{ now()->translatedFormat('F Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Logs (Sidebar) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col">
            <h3 class="text-lg font-extrabold text-gray-800 mb-6 flex items-center">
                <span class="w-1.5 h-6 bg-emerald-500 rounded-full mr-3"></span>
                Aktivitas Saya
            </h3>
            <div class="space-y-6 flex-1 relative">
                <div class="absolute left-4 top-2 bottom-6 w-0.5 bg-gray-50"></div>
                @forelse($recentLogs as $log)
                    <div class="relative flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-full border-4 border-white shadow-sm flex items-center justify-center flex-shrink-0 z-10
                            {{ $log->action === 'create' ? 'bg-emerald-500' : ($log->action === 'update' ? 'bg-indigo-500' : 'bg-red-500') }}">
                            @if($log->action === 'create')
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
                            @elseif($log->action === 'update')
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            @else
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-tighter">{{ strtoupper($log->action) }}</p>
                            <p class="text-xs font-bold text-gray-800 truncate">{{ $log->item->name ?? 'Barang Dihapus' }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5 font-medium">{{ $log->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-center py-10">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300 font-black text-2xl italic">!</div>
                        <p class="text-xs text-gray-400 italic">Belum ada aktivitas.</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-8">
                <p class="text-[10px] text-center text-gray-400 font-bold uppercase tracking-widest">Sistem Inventaris v2.1</p>
            </div>
        </div>
    </div>
@endsection