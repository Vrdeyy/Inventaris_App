@extends('layouts.app')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center space-x-2 mb-2">
                <a href="{{ route('admin.items.history') }}"
                    class="text-indigo-600 hover:text-indigo-800 text-sm font-bold flex items-center transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Timeline Aktivitas <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-indigo-800 block sm:inline">{{ $user->name }}</span>
            </h1>
            <p class="text-gray-500 mt-1">Jejak digital pengelolaan inventaris oleh petugas.</p>
        </div>

        <div class="flex items-center space-x-3">
            @php
                $m = request('month');
                $monthName = $m ? date('F', mktime(0, 0, 0, $m, 1)) : now()->format('F');
            @endphp
            <a href="{{ route('admin.reports.generate', ['report_type' => 'history', 'user_id' => $user->id, 'start_month' => request('month') ?: now()->month, 'end_month' => request('month') ?: now()->month, 'year' => request('year') ?: now()->year, 'date' => request('date'), 'action' => 'export']) }}"
                class="w-full sm:w-auto px-5 py-2.5 bg-white text-emerald-700 border border-emerald-200 rounded-xl hover:bg-emerald-50 hover:border-emerald-300 transition-all text-sm font-bold shadow-sm hover:shadow flex items-center group justify-center">
                <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel ({{ $monthName }})
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 hover:shadow-md transition-shadow">
        <form action="{{ route('admin.items.user_history', $user->id) }}" method="GET"
            class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <div class="group">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 group-focus-within:text-indigo-600 transition-colors">Tanggal Spesifik</label>
                <input type="date" name="date" value="{{ request('date') }}"
                    class="w-full rounded-xl border-gray-200 text-sm p-3 border focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all cursor-pointer">
            </div>
            <div class="group">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 group-focus-within:text-indigo-600 transition-colors">Bulan</label>
                <select name="month" class="w-full rounded-xl border-gray-200 text-sm p-3 border focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all cursor-pointer">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="group">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 group-focus-within:text-indigo-600 transition-colors">Tahun</label>
                <select name="year" class="w-full rounded-xl border-gray-200 text-sm p-3 border focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all cursor-pointer">
                    <option value="">Semua Tahun</option>
                    @foreach(range(now()->year, 2020) as $y)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-all text-sm font-bold shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-0.5">Filter Data</button>
                <a href="{{ route('admin.items.user_history', $user->id) }}"
                    class="px-4 py-3 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-all text-sm font-bold hover:text-gray-800">Reset</a>
            </div>
        </form>
    </div>

    <!-- Timeline List -->
    <div class="bg-white rounded-3xl shadow-xl shadow-gray-100 border border-gray-100 overflow-visible sm:overflow-hidden relative">
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-full opacity-30 -mr-16 -mt-16 pointer-events-none"></div>
        <div class="p-4 sm:p-8 relative z-10">
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @forelse ($logs as $log)
                        <li>
                            <div class="relative pb-10 group">
                                @if (!$loop->last)
                                    <span class="absolute left-5 top-10 -ml-px h-full w-0.5 bg-gray-100 group-hover:bg-indigo-100 transition-colors"></span>
                                @endif
                                <div class="relative flex space-x-4 sm:space-x-6">
                                    <div class="relative">
                                        <span
                                            class="flex h-10 w-10 items-center justify-center rounded-full 
                                            {{ $log->action === 'create' ? 'bg-gradient-to-br from-emerald-400 to-emerald-600' : ($log->action === 'update' ? 'bg-gradient-to-br from-indigo-400 to-indigo-600' : 'bg-gradient-to-br from-red-400 to-red-600') }} shadow-lg ring-4 ring-white z-10 relative transition-transform group-hover:scale-110">
                                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($log->action === 'create')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                @elseif($log->action === 'update') 
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                @else 
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path> 
                                                @endif
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0 bg-gray-50 rounded-2xl p-4 sm:p-5 border border-transparent group-hover:bg-white group-hover:border-gray-100 group-hover:shadow-lg transition-all duration-300">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2 gap-2">
                                            <div class="flex items-center space-x-3">
                                                <span class="px-2.5 py-1 rounded-lg text-[10px] sm:text-xs font-black uppercase tracking-wide
                                                    {{ $log->action === 'create' ? 'bg-emerald-100 text-emerald-700' : ($log->action === 'update' ? 'bg-indigo-100 text-indigo-700' : 'bg-red-100 text-red-700') }}">
                                                    {{ $log->action }}
                                                </span>
                                                <span class="text-[10px] sm:text-xs font-bold text-gray-400 flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    {{ $log->created_at->format('H:i') }}
                                                </span>
                                            </div>
                                            <time class="text-[10px] sm:text-xs font-bold text-gray-500 bg-white px-2 py-1 rounded-md border border-gray-100 shadow-sm w-fit">{{ $log->created_at->format('d M Y') }}</time>
                                        </div>
                                        
                                        <h3 class="text-base sm:text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                             {{ $log->item->name ?? ($log->item_id ? 'Barang #' . $log->item_id : 'Unknown') }}
                                        </h3>

                                        <div class="mt-3 text-sm text-gray-600">
                                            @if($log->action === 'update')
                                                <div class="flex items-center space-x-3 text-xs font-medium mb-3 bg-white w-fit px-3 py-1.5 rounded-lg border border-gray-100 shadow-sm">
                                                    <span class="text-gray-400 line-through">{{ $log->old_condition }}</span>
                                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                                    <span class="text-indigo-600 font-bold uppercase">{{ $log->new_condition }}</span>
                                                </div>
                                            @endif
                                            <div class="flex items-start">
                                                <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                                <p class="italic text-gray-500">"{{ $log->description }}"</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="py-16 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-gray-900 font-bold text-lg">Tidak ada aktivitas</h3>
                            <p class="text-gray-500">Belum ada riwayat tercatat untuk periode ini.</p>
                        </li>
                    @endforelse
                </ul>
            </div>
            <div class="mt-8 border-t border-gray-100 pt-8">
                {{ $logs->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection