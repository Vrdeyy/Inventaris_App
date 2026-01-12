@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Edit Barang: {{ $item->name }}</h1>
            <a href="{{ route('user.items.index') }}" class="text-indigo-600 hover:text-indigo-800">← Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('user.items.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Kode (Read Only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Barang (Gak Bisa Diubah)</label>
                        <input type="text" value="{{ $item->code }}"
                            class="w-full rounded-lg bg-gray-50 border-gray-200 text-gray-500 p-2 border cursor-not-allowed"
                            readonly>
                    </div>

                    <!-- Kategori (Read Only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori (Gak Bisa Diubah)</label>
                        <input type="text" value="{{ $item->category }}"
                            class="w-full rounded-lg bg-gray-50 border-gray-200 text-gray-500 p-2 border cursor-not-allowed"
                            readonly>
                    </div>

                    <!-- Nama -->
                    <div class="md:col-span-2">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Saat Ini</label>
                        <select name="condition"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>
                            <option value="baik" {{ old('condition', $item->condition) == 'baik' ? 'selected' : '' }}>Baik
                            </option>
                            <option value="rusak" {{ old('condition', $item->condition) == 'rusak' ? 'selected' : '' }}>Rusak
                            </option>
                            <option value="hilang" {{ old('condition', $item->condition) == 'hilang' ? 'selected' : '' }}>
                                Hilang</option>
                        </select>
                    </div>

                    <!-- Catatan Perubahan (Wajib) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Perubahan (Penting untuk
                            Histori)</label>
                        <textarea name="description" rows="3"
                            placeholder="Contoh: Perbaikan rutin, Barang jatuh, atau Penambahan stok baru"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                            required>{{ old('description') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500 italic">Harap jelaskan kenapa data ini diubah.</p>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm font-bold">
                        Update Data Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection