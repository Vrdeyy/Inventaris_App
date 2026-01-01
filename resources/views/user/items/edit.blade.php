@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Edit Barang: {{ $item->name }}</h1>
            <a href="{{ route('user.items.index') }}" class="text-indigo-600 hover:text-indigo-800">← Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <form action="{{ route('user.items.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Read Only Code -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Kode Barang</label>
                        <input type="text" value="{{ $item->code }}"
                            class="w-full rounded-lg border-gray-200 bg-gray-100 text-gray-500 p-2 border cursor-not-allowed"
                            disabled>
                    </div>

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang</label>
                        <input type="text" name="name" value="{{ old('name', $item->name) }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                        <input type="text" name="location" value="{{ old('location', $item->location) }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                    </div>

                    <!-- Jumlah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                        <input type="number" name="quantity" value="{{ old('quantity', $item->quantity) }}" min="0"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                    </div>

                    <!-- Kondisi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi</label>
                        <select name="condition"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                            <option value="baik" {{ $item->condition == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak" {{ $item->condition == 'rusak' ? 'selected' : '' }}>Rusak</option>
                            <option value="hilang" {{ $item->condition == 'hilang' ? 'selected' : '' }}>Hilang</option>
                        </select>
                    </div>

                    <!-- Catatan (Required for logs) -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Perubahan (Wajib)</label>
                        <textarea name="description"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            rows="3" required
                            placeholder="Contoh: Barang dipindahkan ke gudang B / Kondisi rusak karena jatuh">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm">Update
                        Barang</button>
                </div>
            </form>
        </div>

        <!-- Logs History -->
        <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
            <h3 class="font-bold text-gray-800 mb-4">Riwayat Perubahan</h3>
            <div class="space-y-4">
                @foreach($item->logs()->latest()->get() as $log)
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-xs font-bold uppercase text-gray-500 tracking-wide">{{ $log->action }}</span>
                                <p class="text-sm font-medium mt-1">{{ $log->description ?: '-' }}</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $log->created_at->format('d M Y H:i') }}</span>
                        </div>
                        @if($log->old_condition != $log->new_condition || $log->old_quantity != $log->new_quantity)
                            <div class="mt-2 text-xs text-gray-600 bg-gray-50 p-2 rounded">
                                @if($log->old_condition != $log->new_condition)
                                    <div>Kondisi: <span class="line-through text-red-400">{{ $log->old_condition }}</span> → <span
                                            class="text-green-600 font-bold">{{ $log->new_condition }}</span></div>
                                @endif
                                @if($log->old_quantity != $log->new_quantity)
                                    <div>Jumlah: <span class="line-through text-red-400">{{ $log->old_quantity }}</span> → <span
                                            class="text-green-600 font-bold">{{ $log->new_quantity }}</span></div>
                                @endif
                            </div>
                        @endif
                        <div class="mt-2 text-xs text-gray-400">Oleh: {{ $log->user->name ?? 'Unknown' }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection