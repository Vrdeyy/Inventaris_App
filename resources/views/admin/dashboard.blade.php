@extends('layouts.app')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pusat Kendali Admin</h1>
            <p class="text-gray-500 mt-1">Gambaran umum performa sistem dan pemantauan aset sekolah.</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">
                <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2 animate-pulse"></span>
                Sistem Aktif
            </span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                {{ now()->translatedFormat('d F Y') }}
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Barang -->
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl shadow-lg p-6 group transition-all duration-300 hover:shadow-indigo-200 hover:-translate-y-1">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M20 7h-4V5c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zM10 5h4v2h-4V5zm10 15H4V9h16v11z"/></svg>
            </div>
            <div class="relative">
                <p class="text-indigo-100 text-sm font-bold uppercase tracking-wider mb-1">Total Barang Inventaris</p>
                <div class="flex items-baseline space-x-2">
                    <h3 class="text-4xl font-black text-white">{{ number_format($stats['total_items']) }}</h3>
                    <span class="text-indigo-200 text-xs font-medium">Unit Terdata</span>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="relative overflow-hidden bg-gradient-to-br from-purple-600 to-purple-800 rounded-2xl shadow-lg p-6 group transition-all duration-300 hover:shadow-purple-200 hover:-translate-y-1">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
            <div class="relative">
                <p class="text-purple-100 text-sm font-bold uppercase tracking-wider mb-1">Total Petugas Aktif</p>
                <div class="flex items-baseline space-x-2">
                    <h3 class="text-4xl font-black text-white">{{ number_format($stats['active_users']) }}</h3>
                    <span class="text-purple-200 text-xs font-medium">Akun Terdaftar</span>
                </div>
            </div>
        </div>

        <!-- Aktivitas Logs -->
        <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl shadow-lg p-6 group transition-all duration-300 hover:shadow-emerald-200 hover:-translate-y-1">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM7 7h10V5h2v14H5V5h2v2z"/></svg>
            </div>
            <div class="relative">
                <p class="text-emerald-50 text-sm font-bold uppercase tracking-wider mb-1">Aktivitas Sistem</p>
                <div class="flex items-baseline space-x-2">
                    <h3 class="text-4xl font-black text-white">{{ number_format($stats['total_logs']) }}</h3>
                    <span class="text-emerald-100 text-xs font-medium">Log Tercatat</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Section -->
    @if(isset($alerts) && count($alerts) > 0)
        <div class="mb-8 space-y-3">
            @foreach($alerts as $alert)
                <div class="flex items-center p-4 bg-red-50 border border-red-100 rounded-xl animate-pulse">
                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-red-100 rounded-full text-red-600 mr-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-red-800">{{ $alert }}</p>
                </div>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Item Conditions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col h-full">
            <h3 class="text-lg font-extrabold text-gray-800 mb-6 flex items-center">
                <span class="w-1.5 h-6 bg-indigo-600 rounded-full mr-2"></span>
                Status Kondisi Aset
            </h3>
            <div class="space-y-6 flex-1 flex flex-col justify-center">
                <!-- Baik -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-gray-600">Kondisi Baik</span>
                        <span class="text-sm font-black text-emerald-600">{{ $stats['conditions']['baik'] }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-emerald-500 h-3 rounded-full" style="width: {{ $stats['total_items'] > 0 ? ($stats['conditions']['baik'] / $stats['total_items'] * 100) : 0 }}%"></div>
                    </div>
                </div>
                <!-- Rusak -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-gray-600">Rusak</span>
                        <span class="text-sm font-black text-orange-600">{{ $stats['conditions']['rusak'] }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-orange-500 h-3 rounded-full" style="width: {{ $stats['total_items'] > 0 ? ($stats['conditions']['rusak'] / $stats['total_items'] * 100) : 0 }}%"></div>
                    </div>
                </div>
                <!-- Hilang -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-gray-600">Hilang</span>
                        <span class="text-sm font-black text-red-600">{{ $stats['conditions']['hilang'] }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-red-500 h-3 rounded-full" style="width: {{ $stats['total_items'] > 0 ? ($stats['conditions']['hilang'] / $stats['total_items'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
            <div class="mt-8 p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">Akurasi Data</p>
                <div class="text-xl font-bold text-gray-800 mt-1">100% Verified</div>
            </div>
        </div>

        <!-- Top Categories -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-extrabold text-gray-800 mb-6 flex items-center">
                <span class="w-1.5 h-6 bg-purple-600 rounded-full mr-2"></span>
                Kategori Terbanyak
            </h3>
            <div class="space-y-4">
                @forelse($categories as $cat)
                    <div class="flex items-center justify-between p-3 rounded-xl border border-transparent hover:border-purple-100 hover:bg-purple-50 transition-all group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold mr-3 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                {{ substr($cat->category, 0, 1) }}
                            </div>
                            <span class="text-sm font-bold text-gray-700 leading-none">{{ $cat->category }}</span>
                        </div>
                        <span class="text-xs font-black px-2 py-1 bg-gray-100 text-gray-500 rounded-md group-hover:bg-white group-hover:text-purple-700">{{ $cat->total }} Item</span>
                    </div>
                @empty
                    <p class="text-center text-gray-400 py-10 italic">Data kategori belum tersedia.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Activity Logs -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:row-span-2">
            <h3 class="text-lg font-extrabold text-gray-800 mb-6 flex items-center justify-between">
                <span class="flex items-center">
                    <span class="w-1.5 h-6 bg-emerald-600 rounded-full mr-2"></span>
                    Aktivitas Terbaru
                </span>
                <a href="{{ route('admin.items.history') }}" class="text-xs text-indigo-600 hover:underline">Lihat Semua</a>
            </h3>
            <div class="space-y-6 relative">
                <div class="absolute left-[17px] top-2 bottom-6 w-0.5 bg-gray-100"></div>
                @forelse($recentLogs as $log)
                    <div class="relative flex items-start space-x-4">
                        <div class="w-9 h-9 rounded-full border-4 border-white shadow-sm flex items-center justify-center flex-shrink-0 z-10
                            {{ $log->action === 'create' ? 'bg-emerald-500' : ($log->action === 'update' ? 'bg-indigo-500' : 'bg-red-500') }}">
                            @if($log->action === 'create')
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
                            @elseif($log->action === 'update')
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            @else
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-900 leading-tight">
                                {{ $log->user->name ?? 'User' }} 
                                <span class="text-xs font-medium text-gray-500">melakukan</span> 
                                <span class="text-xs px-1.5 py-0.5 rounded {{ $log->action === 'create' ? 'bg-emerald-100 text-emerald-800' : ($log->action === 'update' ? 'bg-indigo-100 text-indigo-800' : 'bg-red-100 text-red-800') }}">
                                    {{ strtoupper($log->action) }}
                                </span>
                            </p>
                            <p class="text-xs text-gray-600 mt-1 font-medium italic">"{{ $log->item->name ?? 'Barang Dihapus' }}"</p>
                            <p class="text-[10px] text-gray-400 mt-1 font-bold">{{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 py-10 italic">Belum ada aktivitas tercatat.</p>
                @endforelse
            </div>
        </div>

        <!-- Top Contributors -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-2">
            <h3 class="text-lg font-extrabold text-gray-800 mb-6 flex items-center">
                <span class="w-1.5 h-6 bg-orange-500 rounded-full mr-2"></span>
                Petugas Paling Aktif (Garis Depan)
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($topUsers as $top)
                    <div class="flex items-center p-4 rounded-2xl bg-gray-50 border border-gray-100 hover:border-orange-200 hover:bg-orange-50 transition-all group">
                        <div class="w-12 h-12 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center font-black text-xl mr-4 group-hover:scale-110 transition-transform">
                            {{ substr($top->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-gray-900 group-hover:text-orange-900">{{ $top->name }}</h4>
                            <p class="text-xs text-gray-500">Mengelola {{ $top->items_count }} Item</p>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('admin.monitoring.user', $top->id) }}" class="text-[10px] font-black uppercase text-orange-600 hover:underline">Monitor →</a>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 py-4 italic col-span-2">Data petugas belum tersedia.</p>
                @endforelse
            </div>
            
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 bg-gray-800 text-white rounded-xl text-sm font-bold hover:bg-gray-900 transition flex items-center">
                    Ganti Password/Kelola User
                </a>
                <a href="{{ route('admin.maintenance.index') }}" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-50 transition flex items-center">
                    <span class="mr-2">🔧</span> Pemeliharaan
                </a>
            </div>
        </div>
    </div>
@endsection