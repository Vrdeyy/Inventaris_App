@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <a href="{{ route('admin.items.history') }}"
                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Semua Petugas
                </a>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Riwayat: <span class="text-indigo-600">{{ $user->name }}</span>
            </h1>
        </div>

        <div class="flex items-center space-x-3">
            <div class="flex items-center bg-emerald-50 border border-emerald-100 rounded-lg p-1.5 space-x-2">
                @php
                    $m = request('month');
                    $monthName = $m ? date('F', mktime(0, 0, 0, $m, 1)) : now()->format('F');
                @endphp
                <a href="{{ route('admin.reports.generate', ['report_type' => 'history', 'user_id' => $user->id, 'start_month' => request('month') ?: now()->month, 'end_month' => request('month') ?: now()->month, 'year' => request('year') ?: now()->year, 'date' => request('date'), 'action' => 'export']) }}"
                    class="px-3 py-1 bg-white text-emerald-700 border border-emerald-200 rounded-md hover:bg-emerald-50 transition text-xs font-bold shadow-sm">
                    📊 Export Excel ({{ $monthName }})
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form action="{{ route('admin.items.user_history', $user->id) }}" method="GET"
            class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal</label>
                <input type="date" name="date" value="{{ request('date') }}"
                    class="w-full rounded-lg border-gray-300 text-sm p-2 border">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Bulan</label>
                <select name="month" class="w-full rounded-lg border-gray-300 text-sm p-2 border">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Tahun</label>
                <select name="year" class="w-full rounded-lg border-gray-300 text-sm p-2 border">
                    <option value="">Semua Tahun</option>
                    @foreach(range(now()->year, 2020) as $y)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">Filter</button>
                <a href="{{ route('admin.items.user_history', $user->id) }}"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">Reset</a>
            </div>
        </form>
    </div>

    <!-- Timeline List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @forelse ($logs as $log)
                        <li>
                            <div class="relative pb-8">
                                @if (!$loop->last)
                                    <span class="absolute left-4 top-8 -ml-px h-full w-0.5 bg-gray-200"></span>
                                @endif
                                <div class="relative flex space-x-4">
                                    <div>
                                        <span
                                            class="flex h-8 w-8 items-center justify-center rounded-full 
                                                                                                    {{ $log->action === 'create' ? 'bg-emerald-500' : ($log->action === 'update' ? 'bg-blue-500' : 'bg-red-500') }} shadow">
                                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                @if($log->action === 'create')
                                                    <path d="M12 4v16m8-8H4"></path>
                                                @elseif($log->action === 'update') <path
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                    </path>
                                                @else <path
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path> @endif
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900 capitalize">
                                                {{ $log->action }} Barang: <span
                                                    class="text-indigo-600 font-bold">{{ $log->item->name ?? ($log->item_id ? 'Barang #' . $log->item_id : 'Unknown') }}</span>
                                            </p>
                                            <time
                                                class="text-xs text-gray-500">{{ $log->created_at->format('d M Y, H:i') }}</time>
                                        </div>
                                        <div class="mt-2 text-sm text-gray-700">
                                            @if($log->action === 'update')
                                                <div class="flex items-center space-x-2 text-xs mb-1">
                                                    <span
                                                        class="px-1.5 py-0.5 bg-gray-100 rounded line-through">{{ $log->old_condition }}</span>
                                                    <span>→</span>
                                                    <span
                                                        class="px-1.5 py-0.5 bg-indigo-50 text-indigo-700 rounded font-bold">{{ $log->new_condition }}</span>
                                                </div>
                                            @endif
                                            <p class="text-gray-600 italic">"{{ $log->description }}"</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="py-10 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Belum ada riwayat aktivitas ditemukan.
                        </li>
                    @endforelse
                </ul>
            </div>
            <div class="mt-8 border-t border-gray-100 pt-6">
                {{ $logs->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection