@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <a href="{{ url()->previous() }}"
            class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Item Details Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                    <h2 class="text-lg font-bold text-white">Detail Barang</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Barang</label>
                        <p class="text-lg font-bold text-gray-900">{{ $item->code }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $item->name }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</label>
                        <p class="text-gray-700">{{ $item->category }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</label>
                            <p class="text-gray-700">{{ $item->location }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Penempatan</label>
                            @php
                                $placementColor = $item->placement_type === 'dalam_lemari' 
                                    ? 'bg-purple-100 text-purple-800 border-purple-200' 
                                    : 'bg-blue-100 text-blue-800 border-blue-200';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full border {{ $placementColor }}">
                                {{ $item->placement_label }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Breakdown Jumlah -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider block mb-3">Jumlah per Kondisi</label>
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="p-2 bg-emerald-50 rounded-lg border border-emerald-100">
                                <div class="text-xl font-bold text-emerald-600">{{ $item->qty_baik }}</div>
                                <div class="text-xs text-emerald-700">Baik</div>
                            </div>
                            <div class="p-2 bg-orange-50 rounded-lg border border-orange-100">
                                <div class="text-xl font-bold text-orange-600">{{ $item->qty_rusak }}</div>
                                <div class="text-xs text-orange-700">Rusak</div>
                            </div>
                            <div class="p-2 bg-red-50 rounded-lg border border-red-100">
                                <div class="text-xl font-bold text-red-600">{{ $item->qty_hilang }}</div>
                                <div class="text-xs text-red-700">Hilang</div>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total:</span>
                            <span class="text-2xl font-bold text-indigo-600">{{ $item->quantity }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi Keseluruhan</label>
                        @php
                            $conditionColors = [
                                'baik' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'rusak' => 'bg-orange-100 text-orange-800 border-orange-200',
                                'hilang' => 'bg-red-100 text-red-800 border-red-200',
                                'sebagian_rusak' => 'bg-amber-100 text-amber-800 border-amber-200',
                            ];
                            $conditionLabel = $item->condition === 'sebagian_rusak' ? 'Sebagian Rusak' : ucfirst($item->condition);
                        @endphp
                        <span
                            class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full border {{ $conditionColors[$item->condition] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $conditionLabel }}
                        </span>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</label>
                        <p class="text-gray-700">{{ $item->description ?? '-' }}</p>
                    </div>
                    <div class="pt-4 border-t border-gray-100">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</label>
                        <div class="flex items-center mt-1">
                            <div
                                class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm mr-2">
                                {{ substr($item->user->name ?? 'U', 0, 1) }}
                            </div>
                            <span class="text-gray-800 font-medium">{{ $item->user->name ?? 'Unknown' }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Input</label>
                        <p class="text-gray-700">{{ $item->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Timeline -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800">Riwayat Perubahan Kondisi</h2>
                    <span class="text-sm text-gray-500">{{ $item->logs->count() }} log</span>
                </div>
                <div class="p-6">
                    @if ($item->logs->count() > 0)
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach ($item->logs as $index => $log)
                                    <li>
                                        <div class="relative pb-8">
                                            @if (!$loop->last)
                                                <span class="absolute left-4 top-8 -ml-px h-full w-0.5 bg-gray-200"
                                                    aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-4">
                                                <div>
                                                    @php
                                                        $iconColors = [
                                                            'create' => 'bg-emerald-500',
                                                            'update' => 'bg-blue-500',
                                                            'delete' => 'bg-red-500',
                                                        ];
                                                        $icons = [
                                                            'create' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>',
                                                            'update' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>',
                                                            'delete' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>',
                                                        ];
                                                    @endphp
                                                    <span
                                                        class="flex h-8 w-8 items-center justify-center rounded-full {{ $iconColors[$log->action] ?? 'bg-gray-500' }} shadow">
                                                        <svg class="h-4 w-4 text-white" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            {!! $icons[$log->action] ?? '' !!}
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between">
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ ucfirst($log->action ?? 'Action') }}
                                                        </p>
                                                        <time class="text-xs text-gray-500">
                                                            {{ $log->created_at ? $log->created_at->format('d M Y, H:i') : '-' }}
                                                        </time>
                                                    </div>
                                                    <div class="mt-2 text-sm text-gray-700">
                                                        @if ($log->action === 'update')
                                                            <div class="flex flex-wrap gap-2 mb-2">
                                                                @if ($log->old_condition !== $log->new_condition)
                                                                    <span
                                                                        class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700">
                                                                        Kondisi:
                                                                        <span
                                                                            class="line-through text-red-500 mx-1">{{ ucfirst($log->old_condition ?? '-') }}</span>
                                                                        →
                                                                        <span
                                                                            class="text-emerald-600 ml-1">{{ ucfirst($log->new_condition ?? '-') }}</span>
                                                                    </span>
                                                                @endif
                                                                @if ($log->old_quantity !== $log->new_quantity)
                                                                    <span
                                                                        class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700">
                                                                        Jumlah:
                                                                        <span
                                                                            class="line-through text-red-500 mx-1">{{ $log->old_quantity ?? '-' }}</span>
                                                                        →
                                                                        <span
                                                                            class="text-emerald-600 ml-1">{{ $log->new_quantity ?? '-' }}</span>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @elseif($log->action === 'create')
                                                            <div class="flex flex-wrap gap-2 mb-2">
                                                                <span
                                                                    class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700">
                                                                    Kondisi: {{ ucfirst($log->new_condition ?? '-') }}
                                                                </span>
                                                                <span
                                                                    class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700">
                                                                    Jumlah: {{ $log->new_quantity ?? '-' }}
                                                                </span>
                                                            </div>
                                                        @endif

                                                        @if ($log->description)
                                                            <p class="text-gray-600 italic">"{{ $log->description }}"</p>
                                                        @endif

                                                        <p class="text-xs text-gray-500 mt-1">
                                                            Oleh: <span
                                                                class="font-medium">{{ $log->user->name ?? 'Unknown' }}</span>
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
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <p class="mt-2 text-gray-500">Belum ada riwayat perubahan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
