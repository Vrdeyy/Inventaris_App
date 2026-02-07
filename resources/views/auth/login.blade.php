<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Inventaris</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Instrument Sans', 'Inter', sans-serif;
        }
    </style>
</head>

<body class="h-full flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 to-indigo-50/30">
    <div class="sm:mx-auto sm:w-full sm:max-w-md animate-fade-in text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-3xl shadow-xl shadow-indigo-100 border border-indigo-50 mb-6 group hover:scale-110 transition-transform duration-500">
            <span class="text-4xl group-hover:rotate-12 transition-transform">📦</span>
        </div>
        <h2 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight mb-2">
            Inventaris Lab
        </h2>
        <p class="text-sm sm:text-base text-gray-500 font-medium">
            Sistem Manajemen Aset & Inventaris Sekolah
        </p>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white/80 backdrop-blur-xl py-10 px-6 sm:px-12 shadow-2xl shadow-indigo-100/50 border border-white rounded-[2.5rem] relative overflow-hidden group">
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-50 rounded-full blur-3xl opacity-50 group-hover:bg-indigo-100 transition-colors"></div>
            
            <form class="space-y-6 relative z-10" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="space-y-1">
                    <label for="email" class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Alamat Email</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within/input:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path></svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="appearance-none block w-full pl-12 pr-4 py-4 bg-gray-50 border-gray-100 rounded-2xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 focus:bg-white transition-all sm:text-sm font-bold text-gray-700"
                            placeholder="nama@email.com"
                            value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-2 text-xs font-bold text-red-500 flex items-center ml-1">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="password" class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Kata Sandi</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within/input:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="appearance-none block w-full pl-12 pr-4 py-4 bg-gray-50 border-gray-100 rounded-2xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 focus:bg-white transition-all sm:text-sm font-bold text-gray-700"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between px-1">
                    <label class="relative flex items-center cursor-pointer group">
                        <input id="remember" name="remember" type="checkbox"
                            class="peer sr-only">
                        <div class="h-5 w-5 bg-gray-100 border-2 border-gray-200 rounded-md peer-checked:bg-indigo-600 peer-checked:border-indigo-600 transition-all flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white scale-0 peer-checked:scale-100 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="ml-2 text-xs font-bold text-gray-500 group-hover:text-gray-700 transition-colors">Ingat saya</span>
                    </label>

                    <div class="text-xs">
                        <a href="#" class="font-black text-indigo-600 hover:text-indigo-500 transition-colors underline decoration-indigo-200 underline-offset-4">
                            Lupa sandi?
                        </a>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-xl shadow-indigo-100 text-sm font-black text-white bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 hover:shadow-indigo-200 hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-indigo-200 transition-all duration-300">
                        MASUK KE SISTEM
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-10 text-center animate-fade-in" style="animation-delay: 0.2s">
            <p class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">
                &copy; {{ date('Y') }} Dev by ASLAB PPLG & IT Support.
            </p>
        </div>
    </div>
</body>

</html>