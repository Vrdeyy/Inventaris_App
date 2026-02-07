@extends('layouts.app')

@section('content')
    <div class="mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-600 tracking-tight">Pusat Kendali Admin</h1>
            <p class="text-gray-500 mt-2 text-base sm:text-lg">Gambaran umum performa sistem dan pemantauan aset sekolah.</p>
        </div>
        <div class="flex items-center space-x-3 bg-white p-2 rounded-2xl shadow-sm border border-gray-100 w-fit">
            <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold bg-indigo-50 text-indigo-700">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 mr-2 animate-pulse"></span>
                Sistem Aktif
            </span>
            <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold bg-gray-50 text-gray-700">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ now()->translatedFormat('d F Y') }}
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
        <!-- Total Barang -->
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-3xl shadow-xl shadow-indigo-200 group transition-all duration-300 hover:shadow-2xl hover:shadow-indigo-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <svg class="w-32 h-32 text-white transform rotate-12 group-hover:scale-110 transition-transform duration-500" fill="currentColor" viewBox="0 0 24 24"><path d="M20 7h-4V5c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zM10 5h4v2h-4V5zm10 15H4V9h16v11z"/></svg>
            </div>
            <div class="p-6 sm:p-8 relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 backdrop-blur-sm rounded-2xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                </div>
                <p class="text-indigo-100 text-sm font-bold uppercase tracking-wider mb-1">Total Barang</p>
                <div class="flex items-baseline space-x-2">
                    <h3 class="text-3xl sm:text-5xl font-black text-white tracking-tight">{{ number_format($stats['total_items']) }}</h3>
                    <span class="text-indigo-200 font-medium">Unit</span>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="relative overflow-hidden bg-gradient-to-br from-fuchsia-500 to-purple-600 rounded-3xl shadow-xl shadow-purple-200 group transition-all duration-300 hover:shadow-2xl hover:shadow-purple-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <svg class="w-32 h-32 text-white transform rotate-12 group-hover:scale-110 transition-transform duration-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
            <div class="p-6 sm:p-8 relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 backdrop-blur-sm rounded-2xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <p class="text-purple-100 text-sm font-bold uppercase tracking-wider mb-1">Petugas Aktif</p>
                <div class="flex items-baseline space-x-2">
                    <h3 class="text-3xl sm:text-5xl font-black text-white tracking-tight">{{ number_format($stats['active_users']) }}</h3>
                    <span class="text-purple-200 font-medium">Orang</span>
                </div>
            </div>
        </div>

        <!-- Aktivitas Logs -->
        <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl shadow-xl shadow-emerald-200 group transition-all duration-300 hover:shadow-2xl hover:shadow-emerald-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <svg class="w-32 h-32 text-white transform rotate-12 group-hover:scale-110 transition-transform duration-500" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM7 7h10V5h2v14H5V5h2v2z"/></svg>
            </div>
            <div class="p-6 sm:p-8 relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 backdrop-blur-sm rounded-2xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                </div>
                <p class="text-emerald-100 text-sm font-bold uppercase tracking-wider mb-1">Total Log</p>
                <div class="flex items-baseline space-x-2">
                    <h3 class="text-3xl sm:text-5xl font-black text-white tracking-tight">{{ number_format($stats['total_logs']) }}</h3>
                    <span class="text-emerald-200 font-medium">Aktivitas</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Section -->
    @if(isset($alerts) && count($alerts) > 0)
        <div class="mb-10 space-y-4">
            @foreach($alerts as $alert)
                <div class="flex items-center p-5 bg-red-50 border-l-4 border-red-500 rounded-r-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-red-100 rounded-full text-red-600 mr-4 animate-pulse">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-red-900">Perhatian Diperlukan</h4>
                        <p class="text-sm font-medium text-red-700">{{ $alert }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- Item Conditions -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 flex flex-col h-full hover:shadow-lg transition-shadow duration-300">
            <h3 class="text-xl font-extrabold text-gray-900 mb-8 flex items-center">
                <span class="w-2 h-8 bg-indigo-600 rounded-full mr-3"></span>
                Kondisi Aset
            </h3>
            <div class="space-y-8 flex-1 flex flex-col justify-center">
                <!-- Baik -->
                <div class="group">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-gray-600">Kondisi Baik</span>
                        <div class="flex items-end gap-1">
                            <span class="text-lg font-black text-gray-900">{{ $stats['conditions']['baik'] }}</span>
                            <span class="text-xs font-bold text-emerald-500 mb-1">Unit</span>
                        </div>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden p-1">
                        <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-2 rounded-full transition-all duration-1000 group-hover:shadow-[0_0_10px_rgba(16,185,129,0.5)]" style="width: {{ $stats['total_items'] > 0 ? ($stats['conditions']['baik'] / $stats['total_items'] * 100) : 0 }}%"></div>
                    </div>
                </div>
                <!-- Rusak -->
                <div class="group">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-gray-600">Rusak</span>
                        <div class="flex items-end gap-1">
                            <span class="text-lg font-black text-gray-900">{{ $stats['conditions']['rusak'] }}</span>
                            <span class="text-xs font-bold text-orange-500 mb-1">Unit</span>
                        </div>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden p-1">
                        <div class="bg-gradient-to-r from-orange-400 to-orange-600 h-2 rounded-full transition-all duration-1000 group-hover:shadow-[0_0_10px_rgba(249,115,22,0.5)]" style="width: {{ $stats['total_items'] > 0 ? ($stats['conditions']['rusak'] / $stats['total_items'] * 100) : 0 }}%"></div>
                    </div>
                </div>
                <!-- Hilang -->
                <div class="group">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-gray-600">Hilang</span>
                        <div class="flex items-end gap-1">
                            <span class="text-lg font-black text-gray-900">{{ $stats['conditions']['hilang'] }}</span>
                            <span class="text-xs font-bold text-red-500 mb-1">Unit</span>
                        </div>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden p-1">
                        <div class="bg-gradient-to-r from-red-400 to-red-600 h-2 rounded-full transition-all duration-1000 group-hover:shadow-[0_0_10px_rgba(239,68,68,0.5)]" style="width: {{ $stats['total_items'] > 0 ? ($stats['conditions']['hilang'] / $stats['total_items'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
            <div class="mt-8 p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100/50 flex items-center justify-center gap-3">
                <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                <span class="text-xs font-bold text-indigo-700 uppercase tracking-widest">Data Realtime</span>
            </div>
        </div>

        <!-- Top Categories -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 hover:shadow-lg transition-shadow duration-300">
            <h3 class="text-xl font-extrabold text-gray-900 mb-8 flex items-center">
                <span class="w-2 h-8 bg-purple-600 rounded-full mr-3"></span>
                Kategori Populer
            </h3>
            <div class="space-y-4">
                @forelse($categories as $cat)
                    <div class="flex items-center justify-between p-4 rounded-xl border border-gray-50 bg-gray-50/50 hover:border-purple-200 hover:bg-purple-50 transition-all group cursor-default">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-black text-lg mr-4 group-hover:bg-purple-600 group-hover:text-white transition-all shadow-sm group-hover:shadow-purple-200">
                                {{ substr($cat->category, 0, 1) }}
                            </div>
                            <span class="text-sm font-bold text-gray-800">{{ $cat->category }}</span>
                        </div>
                        <span class="text-xs font-black px-3 py-1.5 bg-white border border-gray-200 text-gray-600 rounded-lg group-hover:border-purple-200 group-hover:text-purple-700 shadow-sm">{{ $cat->total }} Item</span>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                            <span class="text-3xl">📂</span>
                        </div>
                        <p class="text-gray-400 text-sm font-medium">Belum ada kategori data.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Activity Logs -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 lg:row-span-2 hover:shadow-lg transition-shadow duration-300 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 rounded-full opacity-20 -mr-16 -mt-16 pointer-events-none"></div>
            
            <div class="flex items-center justify-between mb-8 relative z-10">
                <h3 class="text-xl font-extrabold text-gray-900 flex items-center">
                    <span class="w-2 h-8 bg-emerald-600 rounded-full mr-3"></span>
                    Log Terbaru
                </h3>
                <a href="{{ route('admin.items.history') }}" class="group flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded-lg hover:bg-indigo-100 transition-colors">
                    Lihat Semua
                    <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
            
            <div class="space-y-8 relative z-10">
                <div class="absolute left-[19px] top-4 bottom-4 w-0.5 bg-gray-100"></div>
                @forelse($recentLogs as $log)
                    <div class="relative flex items-start space-x-4 pl-1 group">
                        <div class="w-10 h-10 rounded-full border-4 border-white shadow-sm flex items-center justify-center flex-shrink-0 z-10 transition-transform group-hover:scale-110
                            {{ $log->action === 'create' ? 'bg-gradient-to-br from-emerald-400 to-emerald-600' : ($log->action === 'update' ? 'bg-gradient-to-br from-indigo-400 to-indigo-600' : 'bg-gradient-to-br from-red-400 to-red-600') }}">
                            @if($log->action === 'create')
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            @elseif($log->action === 'update')
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            @else
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0 bg-gray-50 rounded-xl p-3 border border-transparent group-hover:border-gray-200 group-hover:bg-white transition-all shadow-sm group-hover:shadow-md">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-bold text-gray-900">
                                    {{ $log->user->name ?? 'User' }}
                                </p>
                                <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full {{ $log->action === 'create' ? 'bg-emerald-100 text-emerald-700' : ($log->action === 'update' ? 'bg-indigo-100 text-indigo-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $log->action }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">
                                <span class="text-gray-400 mr-1">pada item</span>
                                <span class="font-bold text-gray-800">"{{ $log->item->name ?? 'Deleted Item' }}"</span>
                            </p>
                            <p class="text-[10px] text-gray-400 mt-2 font-medium flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $log->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-4 text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-sm text-gray-500 font-medium">Belum ada aktivitas tercatat.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Top Contributors -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 lg:col-span-2 hover:shadow-lg transition-shadow duration-300">
            <h3 class="text-xl font-extrabold text-gray-900 mb-8 flex items-center">
                <span class="w-2 h-8 bg-orange-500 rounded-full mr-3"></span>
                Garda Depan (Top Petugas)
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($topUsers as $top)
                    <div class="flex items-center p-5 rounded-2xl bg-white border border-gray-100 shadow-sm hover:border-orange-200 hover:shadow-lg hover:shadow-orange-50 transition-all group">
                        <div class="relative">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-100 to-orange-200 text-orange-700 flex items-center justify-center font-black text-2xl mr-4 group-hover:scale-105 transition-transform shadow-inner">
                                {{ substr($top->name, 0, 1) }}
                            </div>
                            <div class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5 border border-gray-100 shadow-sm">
                                <div class="w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-base font-bold text-gray-900 group-hover:text-orange-900 transition-colors">{{ $top->name }}</h4>
                            <div class="flex items-center mt-1 space-x-2">
                                <span class="px-2 py-0.5 bg-gray-100 rounded text-[10px] font-bold text-gray-500 uppercase tracking-wide">Petugas</span>
                                <span class="text-xs text-orange-600 font-bold">{{ $top->items_count }} Item</span>
                            </div>
                        </div>
                        <div class="text-right pl-4">
                            <a href="{{ route('admin.monitoring.user', $top->id) }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 group-hover:bg-orange-500 group-hover:text-white transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-10">
                        <p class="text-gray-400 italic">Data petugas belum tersedia.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-black transition-colors shadow-lg hover:shadow-xl flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Kelola User
                </a>
                <a href="{{ route('admin.maintenance.index') }}" class="px-6 py-3 bg-white border-2 border-gray-200 text-gray-700 rounded-xl text-sm font-bold hover:border-gray-300 hover:bg-gray-50 transition-all flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Menu Pemeliharaan
                </a>
            </div>
        </div>
    </div>
@endsection