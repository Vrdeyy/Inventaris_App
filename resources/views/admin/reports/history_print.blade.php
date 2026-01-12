<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Riwayat - {{ $user ? $user->name : 'Semua Petugas' }} - {{ date('d/m/Y') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none;
            }

            body {
                background-color: white !important;
                -webkit-print-color-adjust: exact;
            }
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }
    </style>
</head>

<body class="p-8">
    <div
        class="max-w-5xl mx-auto bg-white p-8 shadow-sm print:shadow-none border border-gray-100 print:border-none rounded-xl">
        <!-- Header Laporan -->
        <div class="flex justify-between items-start border-b-2 border-indigo-800 pb-6 mb-8">
            <div>
                <h1 class="text-3xl font-black text-indigo-800 tracking-tight">LAPORAN RIWAYAT AKTIVITAS</h1>
                <p class="text-slate-500 font-medium italic">Audit Kronologis Perubahan Inventaris</p>
            </div>
            <div class="text-right text-sm">
                <p class="font-bold text-slate-800">Petugas: {{ $user ? $user->name : 'Semua Petugas' }}</p>
                @if($user)
                <p class="text-slate-500 italic">{{ $user->email }}</p> @endif
                <div class="mt-2 bg-indigo-50 px-3 py-1 rounded-md border border-indigo-100">
                    <p class="text-indigo-800 font-black text-xs uppercase tracking-widest">Periode Laporan</p>
                    <p class="text-indigo-600 font-bold text-lg leading-tight">
                        {{ request('date') ?: (request('month') ? date('F', mktime(0, 0, 0, request('month'), 1)) . ' ' . request('year') : 'Seluruh Waktu') }}
                    </p>
                </div>
            </div>
        </div>

        <table class="w-full text-left border-collapse border border-slate-200">
            <thead>
                <tr class="bg-indigo-600 text-white">
                    <th class="p-2 text-[10px] font-bold uppercase border border-indigo-700">Tanggal/Jam</th>
                    @if(!$user)
                        <th class="p-2 text-[10px] font-bold uppercase border border-indigo-700">Petugas</th>
                    @endif
                    <th class="p-2 text-[10px] font-bold uppercase border border-indigo-700">Barang</th>
                    <th class="p-2 text-[10px] font-bold uppercase border border-indigo-700">Aksi</th>
                    <th class="p-2 text-[10px] font-bold uppercase border border-indigo-700">Perubahan Data</th>
                    <th class="p-2 text-[10px] font-bold uppercase border border-indigo-700">Catatan/Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach($logs as $log)
                    <tr class="text-xs">
                        <td class="p-2 border border-slate-200 font-mono">{{ $log->created_at->format('d/m/y H:i') }}</td>
                        @if(!$user)
                            <td class="p-2 border border-slate-200 font-bold text-slate-700">{{ $log->user->name ?? '-' }}</td>
                        @endif
                        <td class="p-2 border border-slate-200">
                            <span class="font-bold">{{ $log->item->name ?? '-' }}</span><br>
                            <span class="text-[10px] text-slate-500">{{ $log->item->code ?? '-' }}</span>
                        </td>
                        <td class="p-2 border border-slate-200">
                            <span
                                class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase balance
                                            {{ $log->action === 'create' ? 'bg-emerald-100 text-emerald-800' : ($log->action === 'update' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="p-2 border border-slate-200">
                            @if($log->action === 'update')
                                <div class="text-[10px]">
                                    <span class="text-slate-400 line-through">{{ $log->old_condition }}</span> → <span
                                        class="font-bold">{{ $log->new_condition }}</span>
                                    <br>
                                    <span class="text-slate-400 line-through">{{ $log->old_quantity }}</span> → <span
                                        class="font-bold">{{ $log->new_quantity }}</span>
                                </div>
                            @elseif($log->action === 'create')
                                <span class="text-[10px]">{{ $log->new_condition }} ({{ $log->new_quantity }} unit)</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="p-2 border border-slate-200 italic text-slate-600">"{{ $log->description }}"</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-12 flex justify-between items-end">
            <div class="text-[10px] text-slate-400">
                * Dokumen ini dihasilkan secara otomatis oleh sistem inventaris real-time.
            </div>
            <div class="flex flex-col items-center">
                <p class="text-xs text-slate-500 mb-12">Dicetak oleh {{ auth()->user()->role }} pada
                    {{ date('d/m/Y H:i') }}
                </p>
                <div class="w-40 border-b border-slate-800"></div>
                <p class="mt-1 text-sm font-bold text-slate-800">{{ auth()->user()->name }}</p>
            </div>
        </div>

        <!-- Print Action -->
        <div class="mt-8 flex justify-center no-print">
            <button onclick="window.print()"
                class="px-8 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition font-black shadow-lg">
                CETAK RIWAYAT LOG
            </button>
        </div>
    </div>
</body>

</html>