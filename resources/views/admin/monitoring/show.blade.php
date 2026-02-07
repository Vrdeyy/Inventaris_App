@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.monitoring') }}" class="text-gray-500 hover:text-gray-700 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Monitoring Barang: <span class="text-indigo-600 truncate block sm:inline">{{ $user->name }}</span></h1>
            </div>
            <div class="flex items-center">
                <a href="{{ route('admin.reports.generate', ['report_type' => 'items', 'user_id' => $user->id, 'start_month' => now()->month, 'end_month' => now()->month, 'year' => now()->year, 'action' => 'export']) }}" 
                   class="w-full sm:w-auto px-4 py-2 bg-white text-emerald-700 border border-emerald-100 rounded-xl hover:bg-emerald-50 transition text-sm font-bold shadow-sm flex items-center justify-center">
                   <span class="mr-2">📊</span> Export Excel Bulan Ini
                </a>
            </div>
        </div>
        
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col sm:flex-row sm:items-center gap-6 sm:gap-8">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xl mr-4">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Petugas Lab</p>
                    <p class="text-gray-900 font-extrabold leading-none">{{ $user->name }}</p>
                </div>
            </div>
            <div class="sm:border-l sm:border-gray-100 sm:pl-8">
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Beban Aset</p>
                <p class="text-xl font-black text-indigo-600 leading-none">{{ $totalUnits }} <span class="text-sm font-medium text-gray-400">Unit</span></p>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('admin.monitoring.user', $user->id) }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Barang..." class="w-full rounded-lg border-gray-200 text-sm p-3 border focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all">
            </div>
            <div>
                <select name="category" class="w-full rounded-lg border-gray-200 text-sm p-3 border outline-none cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @php 
                        $categories = \App\Models\Item::where('user_id', $user->id)->distinct()->pluck('category');
                    @endphp
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="placement_type" class="w-full rounded-lg border-gray-200 text-sm p-3 border outline-none cursor-pointer">
                    <option value="">Semua Penempatan</option>
                    <option value="dalam_ruang" {{ request('placement_type') == 'dalam_ruang' ? 'selected' : '' }}>Dalam Ruang</option>
                    <option value="dalam_lemari" {{ request('placement_type') == 'dalam_lemari' ? 'selected' : '' }}>Dalam Lemari</option>
                </select>
            </div>
            <div>
                <select name="condition" class="w-full rounded-lg border-gray-200 text-sm p-3 border outline-none cursor-pointer">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('condition') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak" {{ request('condition') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="hilang" {{ request('condition') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                    <option value="sebagian_rusak" {{ request('condition') == 'sebagian_rusak' ? 'selected' : '' }}>Sebagian Rusak</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition text-sm font-black shadow-md shadow-indigo-100">FILTER</button>
        </form>
    </div>

    <!-- Items List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 table-responsive">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Penempatan</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kondisi</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Opsi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($items as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 whitespace-nowrap" data-label="Barang">
                                <div class="text-sm font-bold text-gray-900">{{ $item->name }}</div>
                                <div class="text-xs text-gray-500 font-medium">{{ $item->location }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 font-bold" data-label="Kategori">{{ $item->category }}</td>
                            <td class="px-4 py-4 whitespace-nowrap" data-label="Penempatan">
                                @php
                                    $placementColor = $item->placement_type === 'dalam_lemari' 
                                        ? 'bg-purple-100 text-purple-800 border-purple-200' 
                                        : 'bg-blue-100 text-blue-800 border-blue-200';
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black border uppercase {{ $placementColor }}">
                                    {{ $item->placement_label }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center" data-label="Stok">
                                <div class="text-sm font-black text-gray-900">{{ $item->quantity }}</div>
                                <div class="text-[10px] font-bold">
                                    @if($item->qty_baik > 0)<span class="text-emerald-600 bg-emerald-50 px-1 rounded">{{ $item->qty_baik }}B</span>@endif
                                    @if($item->qty_rusak > 0)<span class="text-orange-600 bg-orange-50 px-1 rounded ml-0.5">{{ $item->qty_rusak }}R</span>@endif
                                    @if($item->qty_hilang > 0)<span class="text-red-600 bg-red-50 px-1 rounded ml-0.5">{{ $item->qty_hilang }}H</span>@endif
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap" data-label="Kondisi">
                                @php
                                    $conditionColors = [
                                        'baik' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'rusak' => 'bg-orange-50 text-orange-700 border-orange-200',
                                        'hilang' => 'bg-red-50 text-red-700 border-red-200',
                                        'sebagian_rusak' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    ];
                                    $conditionLabel = $item->condition === 'sebagian_rusak' ? 'SEBAGIAN' : strtoupper($item->condition);
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black border {{ $conditionColors[$item->condition] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                    {{ $conditionLabel }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm" data-label="Aksi">
                                <a href="{{ route('admin.items.show', $item->id) }}" class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-600 hover:text-white transition-all text-xs font-black uppercase tracking-wider border border-indigo-100">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-gray-500 italic font-medium">Data barang tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $items->withQueryString()->links() }}
        </div>
    </div>
@endsection
