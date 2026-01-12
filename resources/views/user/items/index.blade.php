@extends('layouts.app')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            @if(request()->routeIs('admin.monitoring.user'))
                Monitoring Barang <span class="text-indigo-600">/
                    {{ $user->name ?? ($items->first()->user->name ?? 'User') }}</span>
            @else
                Data Barang
            @endif
        </h1>

        @if(auth()->user()->role === 'admin' && request()->routeIs('admin.monitoring.user') && $items->isNotEmpty())
            <form action="{{ route('admin.users.export_items', $items->first()->user_id) }}" method="GET"
                class="flex flex-wrap items-center gap-2 mt-4 sm:mt-0">
                <select name="month" class="rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}">
                            {{ ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$m] }}
                        </option>
                    @endforeach
                </select>
                <select name="year" class="rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Tahun</option>
                    @foreach(range(date('Y'), 2020) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
                <button type="submit" name="action" value="export"
                    class="px-3 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm transition">
                    📊 Export Excel
                </button>
                <a href="{{ route('admin.monitoring') }}"
                    class="px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm transition">
                    ← Kembali
                </a>
            </form>
        @elseif(auth()->user()->role === 'user')
            <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2 mt-4 sm:mt-0">
                <a href="{{ route('user.items.export') }}"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition shadow-sm text-sm">
                    📊 Export Excel ({{ now()->translatedFormat('F Y') }})
                </a>
                <a href="{{ route('user.items.create') }}"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    + Tambah Barang
                </a>
            </div>
        @endif
    </div>

    @if(auth()->user()->role === 'user')
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4 flex items-center space-x-2 text-sm text-amber-800">
            <span>ℹ️</span>
            <p>Export Excel hanya untuk <strong>bulan berjalan</strong> (Tgl 1 - {{ now()->endOfMonth()->day }}
                {{ now()->translatedFormat('F Y') }})
            </p>
        </div>
    @endif


    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form action="" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode / Nama..."
                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <input type="text" name="category" value="{{ request('category') }}" placeholder="Semua Kategori"
                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Penempatan</label>
                <select name="placement_type"
                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border">
                    <option value="">Semua Penempatan</option>
                    <option value="dalam_ruang" {{ request('placement_type') == 'dalam_ruang' ? 'selected' : '' }}>Dalam Ruang
                    </option>
                    <option value="dalam_lemari" {{ request('placement_type') == 'dalam_lemari' ? 'selected' : '' }}>Dalam
                        Lemari</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi</label>
                <select name="condition"
                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('condition') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak" {{ request('condition') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="hilang" {{ request('condition') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                    <option value="sebagian_rusak" {{ request('condition') == 'sebagian_rusak' ? 'selected' : '' }}>Sebagian
                        Rusak</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit"
                    class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition">Filter</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>

                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                            Barang</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Penempatan</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($items as $item)
                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 font-bold">{{ $item->name }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->category }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->location }}</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $placementColor = $item->placement_type === 'dalam_lemari'
                                        ? 'bg-purple-100 text-purple-800'
                                        : 'bg-blue-100 text-blue-800';
                                @endphp
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $placementColor }}">
                                    {{ $item->placement_label }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <div class="text-sm font-bold text-gray-700">{{ $item->quantity }}</div>
                                <div class="text-xs text-gray-500">
                                    @if($item->qty_baik > 0)<span class="text-emerald-600">{{ $item->qty_baik }}B</span>@endif
                                    @if($item->qty_rusak > 0)<span
                                    class="text-orange-600 ml-1">{{ $item->qty_rusak }}R</span>@endif
                                    @if($item->qty_hilang > 0)<span
                                    class="text-red-600 ml-1">{{ $item->qty_hilang }}H</span>@endif
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $color = match ($item->condition) {
                                        'baik' => 'bg-emerald-100 text-emerald-800',
                                        'rusak' => 'bg-orange-100 text-orange-800',
                                        'hilang' => 'bg-red-100 text-red-800',
                                        'sebagian_rusak' => 'bg-amber-100 text-amber-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                    {{ $item->condition === 'sebagian_rusak' ? 'Sebagian Rusak' : ucfirst($item->condition) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if(auth()->user()->role === 'user')
                                    <a href="{{ route('user.items.edit', $item->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="{{ route('user.items.destroy', $item->id) }}" method="POST" class="inline-block"
                                        onsubmit="return confirm('Yakin hapus?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                @elseif(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.items.show', $item->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900">Detail →</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada data barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $items->withQueryString()->links() }}
        </div>
    </div>
@endsection