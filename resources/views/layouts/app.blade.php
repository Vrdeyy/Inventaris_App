<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Instrument Sans', 'Inter', sans-serif;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c7c7c7;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="h-full">
    <div class="min-h-full flex" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 flex-col w-64 bg-gradient-to-b from-slate-900 to-slate-800 shadow-2xl transition-transform transform lg:translate-x-0 z-30"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

            <div
                class="flex items-center justify-center h-16 bg-slate-900/50 backdrop-blur-sm border-b border-slate-700/50">
                <span class="text-white font-bold text-xl tracking-tight flex items-center gap-2">
                    <span class="text-2xl">📦</span> Inventaris Lab
                </span>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="group flex items-center px-4 py-3 text-sm font-medium transition-all duration-200 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 translate-x-1' : 'text-slate-300 hover:bg-slate-800 hover:text-white hover:translate-x-1' }}">
                        <span class="truncate">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="group flex items-center px-4 py-3 text-sm font-medium transition-all duration-200 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 translate-x-1' : 'text-slate-300 hover:bg-slate-800 hover:text-white hover:translate-x-1' }}">
                        <span class="truncate">Kelola Users</span>
                    </a>
                    <a href="{{ route('admin.monitoring') }}"
                        class="group flex items-center px-4 py-3 text-sm font-medium transition-all duration-200 rounded-lg {{ request()->routeIs('admin.monitoring*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 translate-x-1' : 'text-slate-300 hover:bg-slate-800 hover:text-white hover:translate-x-1' }}">
                        <span class="truncate">Monitoring Barang</span>
                    </a>
                    <a href="{{ route('admin.items.history') }}"
                        class="group flex items-center px-4 py-3 text-sm font-medium transition-all duration-200 rounded-lg {{ request()->routeIs('admin.items.history') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 translate-x-1' : 'text-slate-300 hover:bg-slate-800 hover:text-white hover:translate-x-1' }}">
                        <span class="truncate">Riwayat Barang</span>
                    </a>
                    <a href="{{ route('admin.reports.index') }}"
                        class="group flex items-center px-4 py-3 text-sm font-medium transition-all duration-200 rounded-lg {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 translate-x-1' : 'text-slate-300 hover:bg-slate-800 hover:text-white hover:translate-x-1' }}">
                        <span class="truncate">Cetak Laporan</span>
                    </a>
                    <a href="{{ route('admin.templates.index') }}"
                        class="group flex items-center px-4 py-3 text-sm font-medium transition-all duration-200 rounded-lg {{ request()->routeIs('admin.templates.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 translate-x-1' : 'text-slate-300 hover:bg-slate-800 hover:text-white hover:translate-x-1' }}">
                        <span class="truncate">Template Laporan</span>
                    </a>
                    <a href="{{ route('admin.maintenance.index') }}"
                        class="group flex items-center px-4 py-3 text-sm font-medium transition-all duration-200 rounded-lg {{ request()->routeIs('admin.maintenance.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 translate-x-1' : 'text-slate-300 hover:bg-slate-800 hover:text-white hover:translate-x-1' }}">
                        <span class="truncate">Pemeliharaan</span>
                    </a>
                @else
                    <a href="{{ route('user.dashboard') }}"
                        class="group flex items-center px-4 py-3 text-sm font-medium transition-all duration-200 rounded-lg {{ request()->routeIs('user.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 translate-x-1' : 'text-slate-300 hover:bg-slate-800 hover:text-white hover:translate-x-1' }}">
                        <span class="truncate">Dashboard</span>
                    </a>
                    <a href="{{ route('user.items.index') }}"
                        class="group flex items-center px-4 py-3 text-sm font-medium transition-all duration-200 rounded-lg {{ request()->routeIs('user.items.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 translate-x-1' : 'text-slate-300 hover:bg-slate-800 hover:text-white hover:translate-x-1' }}">
                        <span class="truncate">Data Barang</span>
                    </a>
                @endif
            </nav>

            <div class="p-4 border-t border-slate-700/50 bg-slate-900/30">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center justify-center w-full px-4 py-2 text-sm text-red-400 hover:text-white hover:bg-red-600/90 rounded-lg transition-all duration-200 hover:shadow-lg hover:shadow-red-500/20 group">
                        <span class="group-hover:scale-105 transition-transform">Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Overlay for mobile sidebar -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-20 bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 lg:pl-64 h-screen">
            <header
                class="flex items-center justify-between flex-shrink-0 h-16 px-6 bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm z-10 sticky top-0">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="text-gray-500 hover:text-gray-700 lg:hidden focus:outline-none transition-transform active:scale-95">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="flex ml-auto items-center space-x-4">
                    <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                        <div class="flex flex-col items-end">
                            <span class="text-sm font-bold text-gray-800">{{ auth()->user()->name }}</span>
                            <span
                                class="text-[10px] font-bold tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100">{{ strtoupper(auth()->user()->role) }}</span>
                        </div>
                        <div
                            class="h-8 w-8 rounded-full bg-indigo-100 border border-indigo-200 flex items-center justify-center text-indigo-700 font-bold shadow-sm">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 flex flex-col p-4 md:p-6 overflow-y-auto bg-gray-50 animate-fade-in">
                @if(session('success'))
                    <div
                        class="mb-6 p-4 text-emerald-700 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center shadow-sm animate-pulse">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div
                        class="mb-6 p-4 text-red-700 bg-red-50 rounded-xl border border-red-200 flex items-center shadow-sm animate-pulse">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-6 p-4 text-red-700 bg-red-50 rounded-xl border border-red-200 shadow-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="font-medium">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')

                <footer class="mt-auto py-6 text-center text-xs text-slate-400 font-medium uppercase tracking-wider">
                    &copy; {{ date('Y') }} Dev by ASLAB PPLG, Assisted by IT Support Dev Team.
                </footer>
            </main>
        </div>
    </div>
    @stack('scripts')
</body>

</html>