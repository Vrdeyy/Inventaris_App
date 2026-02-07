@extends('layouts.app')

@section('content')
    <div class="mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-600 tracking-tight">Dashboard Petugas</h1>
            <p class="text-gray-500 mt-2 text-base sm:text-lg">Selamat datang kembali, mari kelola inventaris dengan teliti.</p>
        </div>
        <div class="md:mt-0">
            <a href="{{ route('user.items.create') }}" 
               class="inline-flex items-center w-full sm:w-auto px-6 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-2xl font-bold shadow-xl shadow-indigo-200 hover:shadow-2xl hover:shadow-indigo-300 hover:-translate-y-1 transition-all duration-300 group justify-center">
                <span class="mr-2 text-xl group-hover:rotate-90 transition-transform duration-300">➕</span> 
                <span>Tambah Barang Baru</span>
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <!-- Card Total -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center group hover:border-indigo-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mr-5 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M20 7h-4V5c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zM10 5h4v2h-4V5zm10 15H4V9h16v11z"/></svg>
            </div>
            <div>
                <div class="text-indigo-900/50 text-[10px] font-black uppercase tracking-widest mb-1">Total Kelolaan</div>
                <div class="flex items-baseline">
                    <div class="text-3xl font-black text-gray-900 leading-none">{{ $stats['total'] }}</div>
                    <span class="text-gray-400 text-xs font-bold uppercase ml-1">Unit</span>
                </div>
            </div>
        </div>

        <!-- Card Baik -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center group hover:border-emerald-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mr-5 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            </div>
            <div>
                <div class="text-emerald-900/50 text-[10px] font-black uppercase tracking-widest mb-1">Kondisi Baik</div>
                <div class="flex items-baseline">
                    <div class="text-3xl font-black text-emerald-600 leading-none">{{ $stats['baik'] }}</div>
                    <span class="text-emerald-400 text-xs font-bold uppercase ml-1">Unit</span>
                </div>
            </div>
        </div>

        <!-- Card Rusak -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center group hover:border-orange-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="w-16 h-16 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center mr-5 group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
            </div>
            <div>
                <div class="text-orange-900/50 text-[10px] font-black uppercase tracking-widest mb-1">Rusak</div>
                <div class="flex items-baseline">
                    <div class="text-3xl font-black text-orange-600 leading-none">{{ $stats['rusak'] }}</div>
                    <span class="text-orange-400 text-xs font-bold uppercase ml-1">Unit</span>
                </div>
            </div>
        </div>

        <!-- Card Hilang -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center group hover:border-red-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mr-5 group-hover:bg-red-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
            </div>
            <div>
                <div class="text-red-900/50 text-[10px] font-black uppercase tracking-widest mb-1">Hilang</div>
                <div class="flex items-baseline">
                    <div class="text-3xl font-black text-red-600 leading-none">{{ $stats['hilang'] }}</div>
                    <span class="text-red-400 text-xs font-bold uppercase ml-1">Unit</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Shortcut & Info -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 overflow-hidden relative group hover:shadow-lg transition-all duration-300">
                <div class="absolute -right-24 -top-24 w-80 h-80 bg-indigo-50 rounded-full opacity-50 blur-3xl group-hover:bg-indigo-100/50 transition-colors"></div>
                <div class="relative">
                    <h3 class="text-xl font-extrabold text-gray-900 mb-8 flex items-center">
                        <span class="w-2 h-8 bg-indigo-600 rounded-full mr-3"></span>
                        Aksi Cepat
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <a href="{{ route('user.items.index') }}" 
                           class="flex items-center p-5 bg-white border border-gray-100 rounded-2xl shadow-sm hover:border-indigo-600 hover:shadow-indigo-100 hover:shadow-lg transition-all group/card hover:-translate-y-1">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mr-5 group-hover/card:bg-indigo-600 group-hover/card:text-white transition-colors duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div class="text-left">
                                <span class="block text-base font-bold text-gray-800 mb-1 group-hover/card:text-indigo-700">Daftar Barang</span>
                                <span class="text-xs text-gray-500 font-medium whitespace-nowrap">Lihat dan update aset Anda</span>
                            </div>
                        </a>
                        <a href="{{ route('user.items.export') }}" 
                           class="flex items-center p-5 bg-white border border-gray-100 rounded-2xl shadow-sm hover:border-emerald-600 hover:shadow-emerald-100 hover:shadow-lg transition-all group/card hover:-translate-y-1">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mr-5 group-hover/card:bg-emerald-600 group-hover/card:text-white transition-colors duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div class="text-left">
                                <span class="block text-base font-bold text-gray-800 mb-1 group-hover/card:text-emerald-700">Cetak Laporan</span>
                                <span class="text-xs text-gray-500 font-medium whitespace-nowrap">Export Excel bulan berjalan</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl p-8 text-white shadow-2xl shadow-slate-200 relative overflow-hidden group hover:scale-[1.01] transition-transform duration-300">
                <div class="absolute right-0 bottom-0 opacity-5 group-hover:opacity-10 transition-opacity duration-300">
                    <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                </div>
                <div class="relative z-10">
                    <h3 class="text-xl font-bold mb-4 flex items-center text-indigo-200">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Informasi Penting
                    </h3>
                    <p class="text-gray-300 leading-relaxed mb-6 text-sm">
                        Setiap perubahan data barang (jumlah atau kondisi) akan tercatat dalam log aktivitas sistem. Pastikan Anda mengisi <span class="text-white font-bold bg-white/20 px-2 py-0.5 rounded">Catatan Perubahan</span> dengan jelas untuk memudahkan Admin dalam melakukan audit bulanan.
                    </p>
                    <div class="inline-flex items-center px-4 py-2 bg-indigo-500/20 border border-indigo-400/30 rounded-xl text-xs font-bold text-indigo-300">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse mr-2"></span>
                        Periode Aktif: {{ now()->translatedFormat('F Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Logs (Sidebar) -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 flex flex-col h-full hover:shadow-lg transition-all duration-300">
            <h3 class="text-xl font-extrabold text-gray-900 mb-8 flex items-center">
                <span class="w-2 h-8 bg-emerald-500 rounded-full mr-3"></span>
                Aktivitas Saya
            </h3>
            <div class="space-y-8 flex-1 relative">
                <div class="absolute left-4 top-4 bottom-4 w-0.5 bg-gray-100"></div>
                @forelse($recentLogs as $log)
                    <div class="relative flex items-start space-x-4 pl-1 group">
                        <div class="w-8 h-8 rounded-full border-4 border-white shadow-sm flex items-center justify-center flex-shrink-0 z-10 transition-transform group-hover:scale-110
                            {{ $log->action === 'create' ? 'bg-gradient-to-br from-emerald-400 to-emerald-600' : ($log->action === 'update' ? 'bg-gradient-to-br from-indigo-400 to-indigo-600' : 'bg-gradient-to-br from-red-400 to-red-600') }}">
                            @if($log->action === 'create')
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            @elseif($log->action === 'update')
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            @else
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-black uppercase tracking-widest px-2 py-0.5 rounded-md w-fit
                                    {{ $log->action === 'create' ? 'bg-emerald-50 text-emerald-600' : ($log->action === 'update' ? 'bg-indigo-50 text-indigo-600' : 'bg-red-50 text-red-600') }}">
                                    {{ strtoupper($log->action) }}
                                </p>
                                <span class="text-[10px] text-gray-300 font-bold">{{ $log->created_at->format('H:i') }}</span>
                            </div>
                            <p class="text-xs font-bold text-gray-800 mt-1 truncate group-hover:text-indigo-600 transition-colors">{{ $log->item->name ?? 'Barang Dihapus' }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5 font-medium">{{ $log->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-center py-10 opacity-50">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400 font-black text-2xl italic">!</div>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Belum ada aktivitas.</p>
                    </div>
                @endforelse
            </div>
            @if(count($recentLogs) > 0)
                <div class="mt-8 pt-6 border-t border-gray-50">
                    <p class="text-[10px] text-center text-gray-400 font-bold uppercase tracking-widest">Sistem Inventaris v2.1</p>
                </div>
            @endif
        </div>
    </div>
@endsection