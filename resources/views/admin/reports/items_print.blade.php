<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris - {{ $user ? $user->name : 'Semua Petugas' }} - {{ date('d/m/Y') }}</title>
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

            .print-border {
                border: 1px solid #e5e7eb !important;
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
        class="max-w-4xl mx-auto bg-white p-8 shadow-sm print:shadow-none border border-gray-100 print:border-none rounded-xl">
        <!-- Header Laporan -->
        <div class="flex justify-between items-start border-b-2 border-slate-800 pb-6 mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">LAPORAN INVENTARIS</h1>
                <p class="text-slate-500 font-medium">Monitoring Aset Real-Time</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-slate-800">Petugas: {{ $user ? $user->name : 'Semua Petugas' }}</p>
                <p class="text-sm text-slate-500">Dicetak pada: {{ date('d M Y, H:i') }}</p>
            </div>
        </div>

        <!-- Filter Info -->
        <div
            class="mb-6 p-4 bg-slate-50 rounded-lg text-sm text-slate-600 border border-slate-100 flex justify-between items-center">
            <span class="italic">Menampilkan status terkini seluruh barang
                {{ $user ? 'yang dikelola oleh petugas tersebut' : 'yang dikelola oleh semua petugas yang terdaftar' }}.</span>
            <span class="font-bold text-slate-800 bg-white px-3 py-1 rounded shadow-sm border border-slate-200">
                Periode:
                {{ request('month') ? date('F', mktime(0, 0, 0, request('month'), 1)) . ' ' . request('year') : 'Semua Waktu' }}
            </span>
        </div>

        <!-- Table -->
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-800 text-white">
                    <th class="p-3 text-[10px] font-bold uppercase tracking-wider rounded-tl-lg text-center w-12">No
                    </th>
                    <th class="p-3 text-[10px] font-bold uppercase tracking-wider">Nama Barang</th>
                    <th class="p-3 text-[10px] font-bold uppercase tracking-wider">Kategori</th>
                    <th class="p-3 text-[10px] font-bold uppercase tracking-wider">Lokasi</th>
                    <th class="p-3 text-[10px] font-bold uppercase tracking-wider text-center">Kondisi</th>
                    <th class="p-3 text-[10px] font-bold uppercase tracking-wider text-center">Jumlah</th>
                    <th class="p-3 text-[10px] font-bold uppercase tracking-wider text-center rounded-tr-lg">Tanggal
                        Input</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach($items as $index => $item)
                    <tr class="hover:bg-slate-50">
                        <td class="p-3 text-sm font-mono text-slate-700 font-bold text-center">{{ $index + 1 }}</td>
                        <td class="p-3">
                            <div class="text-sm font-bold text-slate-800">{{ $item->name }}</div>
                        </td>
                        <td class="p-3 text-sm text-slate-600">
                            {{ $item->category }}
                        </td>
                        <td class="p-3 text-sm text-slate-600">
                            {{ $item->location }}
                        </td>
                        <td class="p-3 text-center">
                            <span
                                class="px-2 py-0.5 rounded text-[10px] font-black border uppercase
                                                {{ $item->condition === 'baik' ? 'border-emerald-200 text-emerald-700 bg-emerald-50' : ($item->condition === 'rusak' ? 'border-orange-200 text-orange-700 bg-orange-50' : 'border-red-200 text-red-700 bg-red-50') }}">
                                {{ $item->condition }}
                            </span>
                        </td>
                        <td class="p-3 text-sm font-bold text-slate-800 text-center">{{ $item->quantity }}</td>
                        <td class="p-3 text-center text-xs text-slate-600">
                            {{ $item->created_at->format('d/m/Y') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Footer Tanda Tangan -->
        <div class="mt-16 grid grid-cols-2 gap-12 text-center">
            <div></div>
            <div class="flex flex-col items-center">
                <p class="text-sm text-slate-500 mb-16">Administrator Inventaris</p>
                <div class="w-48 border-b border-slate-800"></div>
                <p class="mt-2 font-bold text-slate-800">{{ auth()->user()->name }}</p>
            </div>
        </div>

        <!-- Print Action -->
        <div class="mt-8 flex justify-center no-print">
            <button onclick="window.print()"
                class="px-6 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition font-bold shadow-lg">
                Klik untuk Cetak Laporan
            </button>
        </div>
    </div>
</body>

</html>