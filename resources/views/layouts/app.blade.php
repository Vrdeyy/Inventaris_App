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
    </style>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="h-full">
    <div class="min-h-full flex" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 flex-col w-64 bg-slate-900 shadow-xl transition-transform transform lg:translate-x-0 z-30"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

            <div class="flex items-center justify-center h-16 bg-slate-800">
                <span class="text-white font-bold text-xl tracking-tight">📦 Inventaris</span>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-4 py-3 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="truncate">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center px-4 py-3 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="truncate">Kelola Users</span>
                    </a>
                    <a href="{{ route('admin.monitoring') }}"
                        class="flex items-center px-4 py-3 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.monitoring') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="truncate">Monitoring Barang</span>
                    </a>

                @else
                    <a href="{{ route('user.dashboard') }}"
                        class="flex items-center px-4 py-3 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('user.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="truncate">Dashboard</span>
                    </a>
                    <a href="{{ route('user.items.index') }}"
                        class="flex items-center px-4 py-3 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('user.items.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="truncate">Data Barang</span>
                    </a>
                @endif
            </nav>

            <div class="p-4 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center justify-center w-full px-4 py-2 text-sm text-red-400 hover:text-white hover:bg-red-600 rounded-lg transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Overlay for mobile sidebar -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"></div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 lg:pl-64 h-screen">
            <header
                class="flex items-center justify-between flex-shrink-0 h-16 px-6 bg-white border-b border-gray-200 shadow-sm z-10">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="text-gray-500 hover:text-gray-700 lg:hidden focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="flex ml-auto items-center space-x-4">
                    <span class="text-sm font-medium text-gray-700">Hi, {{ auth()->user()->name }} <span
                            class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded ml-1">{{ strtoupper(auth()->user()->role) }}</span></span>
                </div>
            </header>

            <main class="flex-1 p-6 overflow-y-auto bg-gray-50">
                @if(session('success'))
                    <div
                        class="mb-6 p-4 text-green-700 bg-green-50 rounded-lg border border-green-200 flex items-center shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-6 p-4 text-red-700 bg-red-50 rounded-lg border border-red-200 shadow-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>