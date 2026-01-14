@extends('layouts.app')

@section('content')
    <div class="mb-10">
        <h1
            class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-600 tracking-tight">
            Monitoring Barang Per User</h1>
        <p class="text-gray-500 mt-2 text-lg">Pantau kondisi aset yang dipegang oleh setiap petugas.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($users as $user)
            <div
                class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-indigo-100 transition-all duration-300 hover:-translate-y-1 group">
                <div class="p-8">
                    <div class="flex items-center space-x-4 mb-8">
                        <div
                            class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-50 to-indigo-100 flex items-center justify-center text-indigo-600 font-black text-2xl shadow-inner group-hover:from-indigo-600 group-hover:to-indigo-700 group-hover:text-white transition-all duration-300 transform group-hover:scale-110">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-900 text-xl truncate">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500 truncate mb-1">{{ $user->email }}</p>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-gray-100 text-gray-600 uppercase tracking-wide">
                                User
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3 mb-8">
                        <!-- Baik -->
                        <div
                            class="bg-emerald-50 p-3 rounded-2xl border border-emerald-100 group-hover:border-emerald-200 transition-colors text-center">
                            <span
                                class="block text-2xl font-black text-emerald-600 tracking-tight">{{ $user->items_sum_qty_baik ?? 0 }}</span>
                            <span class="text-[10px] uppercase font-bold text-emerald-400 tracking-wider">Baik</span>
                        </div>
                        <!-- Rusak -->
                        <div
                            class="bg-orange-50 p-3 rounded-2xl border border-orange-100 group-hover:border-orange-200 transition-colors text-center">
                            <span
                                class="block text-2xl font-black text-orange-600 tracking-tight">{{ $user->items_sum_qty_rusak ?? 0 }}</span>
                            <span class="text-[10px] uppercase font-bold text-orange-400 tracking-wider">Rusak</span>
                        </div>
                        <!-- Hilang -->
                        <div
                            class="bg-red-50 p-3 rounded-2xl border border-red-100 group-hover:border-red-200 transition-colors text-center">
                            <span
                                class="block text-2xl font-black text-red-600 tracking-tight">{{ $user->items_sum_qty_hilang ?? 0 }}</span>
                            <span class="text-[10px] uppercase font-bold text-red-400 tracking-wider">Hilang</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-gray-500 font-medium">Menu Monitoring</span>
                        </div>

                        <a href="{{ route('admin.monitoring.user', $user->id) }}"
                            class="w-full flex items-center justify-center py-3.5 bg-gray-900 text-white rounded-xl hover:bg-black transition-all text-sm font-bold shadow-md hover:shadow-lg group/btn">
                            <span>Lihat Detail Barang</span>
                            <svg class="w-4 h-4 ml-2 transform group-hover/btn:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>

                        <div class="pt-4 border-t border-gray-50">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Laporan
                                    {{ date('M Y') }}</span>
                            </div>
                            <a href="{{ route('admin.reports.generate', ['report_type' => 'items', 'user_id' => $user->id, 'start_month' => now()->month, 'end_month' => now()->month, 'year' => now()->year, 'action' => 'export']) }}"
                                class="w-full flex items-center justify-center py-2.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-xl hover:bg-emerald-100 hover:border-emerald-200 transition-all text-xs font-bold uppercase tracking-wider group/xls">
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

        @if($users->isEmpty())
            <div class="col-span-full text-center py-16 bg-white rounded-3xl border border-dashed border-gray-200">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-gray-900 font-bold text-lg">Belum ada user</h3>
                <p class="text-gray-500 mt-1">Silakan tambahkan petugas baru untuk memulai monitoring.</p>
            </div>
        @endif
    </div>
@endsection