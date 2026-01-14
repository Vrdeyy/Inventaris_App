@extends('layouts.app')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1
                class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-600 tracking-tight">
                Riwayat Aktivitas Petugas</h1>
            <p class="text-gray-500 mt-2">Pilih petugas untuk melihat kronologi audit perubahan barang.</p>
        </div>
        <div class="w-full md:w-72">
            <form action="{{ route('admin.items.history') }}" method="GET" class="relative group">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama petugas..."
                    class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all text-sm font-medium shadow-sm">
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($users as $user)
            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-indigo-100 transition-all duration-300 hover:-translate-y-1 group">
                <div class="p-6">
                    <div class="flex items-center space-x-4 mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-50 to-indigo-100 flex items-center justify-center text-indigo-600 font-black text-2xl shadow-inner group-hover:from-indigo-600 group-hover:to-indigo-700 group-hover:text-white transition-all duration-300 transform group-hover:scale-110">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-900 text-lg truncate">{{ $user->name }}</h3>
                            <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div
                            class="bg-gray-50 p-4 rounded-xl border border-gray-100 group-hover:border-gray-200 transition-colors">
                            <span
                                class="block text-3xl font-black text-gray-800 tracking-tight">{{ $user->total_activities }}</span>
                            <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Total Aktivitas</span>
                        </div>
                        <div
                            class="bg-indigo-50 p-4 rounded-xl border border-indigo-100 group-hover:border-indigo-200 transition-colors">
                            <span
                                class="block text-3xl font-black text-indigo-600 tracking-tight">{{ $user->month_activities }}</span>
                            <span class="text-[10px] uppercase font-bold text-indigo-400 tracking-wider">Bulan Ini</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('admin.items.user_history', $user->id) }}"
                            class="w-full flex items-center justify-center py-3 bg-gray-900 text-white rounded-xl hover:bg-black transition-all text-sm font-bold shadow-md hover:shadow-lg group/btn">
                            <span>Lihat Timeline</span>
                            <svg class="w-4 h-4 ml-2 transform group-hover/btn:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>

                        <div class="pt-3 border-t border-gray-50">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] uppercase font-bold text-gray-400">Audit Log {{ date('M Y') }}</span>
                            </div>
                            <a href="{{ route('admin.reports.generate', ['report_type' => 'history', 'user_id' => $user->id, 'start_month' => now()->month, 'end_month' => now()->month, 'year' => now()->year, 'action' => 'export']) }}"
                                class="w-full flex items-center justify-center py-2 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-xl hover:bg-emerald-100 hover:border-emerald-200 transition-all text-xs font-bold uppercase tracking-wider group/xls">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Export Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection