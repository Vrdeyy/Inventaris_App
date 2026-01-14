@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <a href="{{ url()->previous() }}"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:text-indigo-600 hover:border-indigo-100 hover:shadow-md transition-all group">
            <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Item Details Card -->
        <div class="lg:col-span-1">
            <div
                class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden sticky top-6">
                <div class="bg-gradient-to-br from-indigo-600 to-purple-700 px-8 py-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10 transform translate-x-4 -translate-y-4">
                        <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-black text-white tracking-tight relative z-10">Detail Barang</h2>
                    <p class="text-indigo-100 text-sm font-medium relative z-10 mt-1">Informasi lengkap aset inventaris</p>
                </div>

                <div class="p-8 space-y-6">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Nama
                            Barang</label>
                        <p class="text-2xl font-black text-gray-900 leading-tight">{{ $item->name }}</p>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Kategori &
                            Lokasi</label>
                        <div class="flex flex-wrap gap-2">
                            <span
                                class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-bold border border-gray-200">
                                <svg class="w-3 h-3 mr-1.5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                    </path>
                                </svg>
                                {{ $item->category }}
                            </span>
                            <span
                                class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-bold border border-gray-200">
                                <svg class="w-3 h-3 mr-1.5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $item->location }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Penempatan</label>
                            @php
                                $placementClass = $item->placement_type === 'dalam_lemari'
                                    ? 'bg-purple-100 text-purple-700 border-purple-200'
                                    : 'bg-blue-100 text-blue-700 border-blue-200';
                                $icon = $item->placement_type === 'dalam_lemari'
                                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>'
                                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>'; // default icon
                            @endphp
                            <span
                                class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-lg border {{ $placementClass }}">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">{!! $icon !!}</svg>
                                {{ $item->placement_label }}
                            </span>
                        </div>
                        <div>
                            <label
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Status</label>
                            @php
                                $conditionColors = [
                                    'baik' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'rusak' => 'bg-orange-100 text-orange-700 border-orange-200',
                                    'hilang' => 'bg-red-100 text-red-700 border-red-200',
                                    'sebagian_rusak' => 'bg-amber-100 text-amber-700 border-amber-200',
                                ];
                                $conditionLabel = $item->condition === 'sebagian_rusak' ? 'Sebagian Rusak' : ucfirst($item->condition);
                            @endphp
                            <span
                                class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-lg border {{ $conditionColors[$item->condition] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $conditionLabel }}
                            </span>
                        </div>
                    </div>

                    <!-- Breakdown Jumlah -->
                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-4">Detail
                            Jumlah</label>
                        <div class="grid grid-cols-3 gap-3 text-center mb-4">
                            <div class="p-3 bg-white rounded-xl border border-gray-100 shadow-sm">
                                <div class="text-xl font-black text-emerald-600">{{ $item->qty_baik }}</div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase mt-1">Baik</div>
                            </div>
                            <div class="p-3 bg-white rounded-xl border border-gray-100 shadow-sm">
                                <div class="text-xl font-black text-orange-600">{{ $item->qty_rusak }}</div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase mt-1">Rusak</div>
                            </div>
                            <div class="p-3 bg-white rounded-xl border border-gray-100 shadow-sm">
                                <div class="text-xl font-black text-red-600">{{ $item->qty_hilang }}</div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase mt-1">Hilang</div>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-gray-200 flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-500 uppercase">Total Unit</span>
                            <span class="text-2xl font-black text-indigo-600 tracking-tight">{{ $item->quantity }}</span>
                        </div>
                    </div>

                    <div>
                        <label
                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Deskripsi</label>
                        <p class="text-sm text-gray-700 leading-relaxed bg-gray-50 p-3 rounded-xl border border-gray-100">
                            {{ $item->description ?? 'Tidak ada deskripsi.' }}</p>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div>
                            <label
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Petugas</label>
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs mr-2 shadow-sm">
                                    {{ substr($item->user->name ?? 'U', 0, 1) }}
                                </div>
                                <span class="text-sm font-bold text-gray-800">{{ $item->user->name ?? 'Unknown' }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Tgl
                                Input</label>
                            <span class="text-sm font-bold text-gray-800">{{ $item->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Timeline -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden relative">
                <div
                    class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-full opacity-30 -mr-16 -mt-16 pointer-events-none">
                </div>

                <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between relative z-10">
                    <div>
                        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Timeline Kondisi</h2>
                        <p class="text-gray-500 text-xs mt-1">Riwayat perubahan aset ini</p>
                    </div>
                    <span
                        class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-black uppercase tracking-wide">{{ $item->logs->count() }}
                        LOG</span>
                </div>

                <div class="p-8 relative z-10">
                    @if ($item->logs->count() > 0)
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach ($item->logs as $index => $log)
                                                    <li>
                                                        <div class="relative pb-10 group">
                                                            @if (!$loop->last)
                                                                <span
                                                                    class="absolute left-5 top-10 -ml-px h-full w-0.5 bg-gray-100 group-hover:bg-indigo-100 transition-colors"
                                                                    aria-hidden="true"></span>
                                                            @endif
                                                            <div class="relative flex space-x-6">
                                                                <div>
                                                                    @php
                                                                        $iconClass = $log->action === 'create' ? 'bg-gradient-to-br from-emerald-400 to-emerald-600' : ($log->action === 'update' ? 'bg-gradient-to-br from-indigo-400 to-indigo-600' : 'bg-gradient-to-br from-red-400 to-red-600');
                                                                        $icons = [
                                                                            'create' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>',
                                                                            'update' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>',
                                                                            'delete' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>',
                                                                        ];
                                                                    @endphp
                                     <span
                                                                        class="flex h-10 w-10 items-center justify-center rounded-full {{ $iconClass }} shadow-lg ring-4 ring-white z-10 relative transition-transform group-hover:scale-110">
                                                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            {!! $icons[$log->action] ?? '' !!}
                                                                        </svg>
                                                                    </span>
                                                                </div>
                                                                <div
                                                                    class="flex-1 min-w-0 bg-gray-50 rounded-2xl p-5 border border-transparent group-hover:bg-white group-hover:border-gray-100 group-hover:shadow-lg transition-all duration-300">
                                                                    <div class="flex items-center justify-between mb-2">
                                                                        <span
                                                                            class="px-2.5 py-1 rounded-lg text-xs font-black uppercase tracking-wide
                                                                                        {{ $log->action === 'create' ? 'bg-emerald-100 text-emerald-700' : ($log->action === 'update' ? 'bg-indigo-100 text-indigo-700' : 'bg-red-100 text-red-700') }}">
                                                                            {{ ucfirst($log->action ?? 'Action') }}
                                                                        </span>
                                                                        <time
                                                                            class="text-xs font-bold text-gray-500 bg-white px-2 py-1 rounded-md border border-gray-100 shadow-sm">
                                                                            {{ $log->created_at ? $log->created_at->format('d M Y, H:i') : '-' }}
                                                                        </time>
                                                                    </div>

                                                                    <div class="text-sm text-gray-600 space-y-3 mt-3">
                                                                        @if ($log->action === 'update')
                                                                            <div class="flex flex-wrap gap-3">
                                                                                @if ($log->old_condition !== $log->new_condition)
                                                                                    <div
                                                                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-white border border-gray-200 shadow-sm">
                                                                                        <span class="text-gray-400 uppercase mr-2">Kondisi:</span>
                                                                                        <span
                                                                                            class="line-through text-red-400 mr-2">{{ ucfirst($log->old_condition ?? '-') }}</span>
                                                                                        <svg class="w-3 h-3 text-gray-400 mr-2" fill="none"
                                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                                                        </svg>
                                                                                        <span
                                                                                            class="text-emerald-600 uppercase">{{ ucfirst($log->new_condition ?? '-') }}</span>
                                                                                    </div>
                                                                                @endif
                                                                                @if ($log->old_quantity !== $log->new_quantity)
                                                                                    <div
                                                                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-white border border-gray-200 shadow-sm">
                                                                                        <span class="text-gray-400 uppercase mr-2">Jumlah:</span>
                                                                                        <span
                                                                                            class="line-through text-red-400 mr-2">{{ $log->old_quantity ?? '-' }}</span>
                                                                                        <svg class="w-3 h-3 text-gray-400 mr-2" fill="none"
                                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                                                        </svg>
                                                                                        <span
                                                                                            class="text-emerald-600">{{ $log->new_quantity ?? '-' }}</span>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        @elseif($log->action === 'create')
                                                                            <div class="flex flex-wrap gap-2">
                                                                                <span
                                                                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                                                    Kondisi: {{ ucfirst($log->new_condition ?? '-') }}
                                                                                </span>
                                                                                <span
                                                                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                                                    Jumlah: {{ $log->new_quantity ?? '-' }}
                                                                                </span>
                                                                            </div>
                                                                        @endif

                                                                        @if ($log->description)
                                                                            <div
                                                                                class="flex items-start bg-yellow-50/50 p-2.5 rounded-lg border border-yellow-100/50">
                                                                                <svg class="w-4 h-4 text-yellow-500 mr-2 mt-0.5 flex-shrink-0"
                                                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                                                                    </path>
                                                                                </svg>
                                                                                <p class="text-gray-600 italic">"{{ $log->description }}"</p>
                                                                            </div>
                                                                        @endif

                                                                        <p class="text-xs text-gray-400 font-medium flex items-center pt-1">
                                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                                                </path>
                                                                            </svg>
                                                                            Updated by: <span
                                                                                class="text-gray-600 ml-1">{{ $log->user->name ?? 'Unknown' }}</span>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div
                                class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-bold">Belum ada riwayat perubahan.</p>
                            <p class="text-gray-400 text-xs mt-1">Setiap aksi akan tercatat di sini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection