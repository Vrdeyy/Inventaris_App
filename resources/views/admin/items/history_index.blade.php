@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Aktivitas Petugas</h1>
            <p class="text-gray-600">Pilih petugas untuk melihat kronologi perubahan barang yang mereka lakukan.</p>
        </div>
        <div class="w-full md:w-64">
            <form action="{{ route('admin.items.history') }}" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama petugas..."
                    class="w-full rounded-lg border-gray-300 text-sm p-2 border focus:ring-indigo-500 focus:border-indigo-500">
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($users as $user)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div
                            class="w-12 h-12 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-xl">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">{{ $user->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-slate-50 p-3 rounded-lg">
                            <span class="block text-2xl font-bold text-slate-800">{{ $user->total_activities }}</span>
                            <span class="text-xs text-slate-500">Total Aktivitas</span>
                        </div>
                        <div class="bg-indigo-50 p-3 rounded-lg">
                            <span class="block text-2xl font-bold text-indigo-700">{{ $user->month_activities }}</span>
                            <span class="text-xs text-indigo-600">Bulan Ini</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <a href="{{ route('admin.items.user_history', $user->id) }}"
                            class="w-full flex items-center justify-center py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-bold shadow-sm group">
                            Riwayat Lengkap →
                        </a>

                        <div class="mt-2 pt-2 border-t border-gray-50">
                            <span class="text-[9px] uppercase font-bold text-gray-400 block mb-1">Audit Log
                                {{ date('M Y') }}:</span>
                            <div class="grid grid-cols-1 gap-2">
                                <a href="{{ route('admin.reports.generate', ['report_type' => 'history', 'user_id' => $user->id, 'start_month' => now()->month, 'end_month' => now()->month, 'year' => now()->year, 'action' => 'export']) }}"
                                    class="flex items-center justify-center py-1.5 bg-emerald-50 text-emerald-700 rounded-lg hover:bg-emerald-100 transition text-[9px] font-bold uppercase tracking-wider">
                                    📊 Export Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection