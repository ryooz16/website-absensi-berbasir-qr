<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ (isset($header) ? $header . ' - ' : '') . config('app.name', 'Absensi Sekolah') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo-tbg.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        * { font-family: 'Inter', sans-serif; }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        /* Smooth transitions */
        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover { transform: translateX(4px); }
        .sidebar-link.active { background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); color: white; }
    </style>
</head>

<body class="bg-slate-50 font-sans antialiased">

<div class="flex h-screen overflow-hidden bg-slate-50" x-data="{ sidebarOpen: false }" style="-webkit-transform: translateZ(0); transform: translateZ(0);">

    <!-- SIDEBAR -->
    @include('layouts.sidebar')

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col min-w-0 h-screen">

        <!-- TOP BAR -->
        <header class="bg-white md:bg-white/80 md:backdrop-blur-xl border-b border-slate-200 z-30 shrink-0 sticky top-0">
            <div class="flex justify-between items-center px-4 md:px-8 py-3 md:py-4">

                <div class="flex items-center gap-3 min-w-0">
                    <!-- Mobile Hamburger -->
                    <button @click="sidebarOpen = true" class="md:hidden p-2 -ml-2 text-slate-600 hover:bg-slate-100 rounded-xl transition flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Mobile Logo -->
                    <div class="md:hidden flex-shrink-0">
                        <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-10 h-10 rounded-lg object-cover shadow-sm">
                    </div>

                    <!-- <div class="text-sm md:text-base font-bold text-slate-800 truncate">
                        {{ $header ?? '' }}
                    </div> -->
                </div>

                <div class="flex items-center gap-2 md:gap-4 shrink-0">
                    <div x-data class="flex items-center gap-2 md:gap-3 bg-slate-50 pl-3 md:pl-4 pr-2 py-1.5 rounded-full">
                        <div class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="hidden sm:inline text-sm font-semibold text-slate-700">{{ auth()->user()->name }}</span>
                        <form id="logout-form" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="button" @click="$dispatch('confirm-dialog', { title: 'Konfirmasi Logout', message: 'Apakah Anda yakin ingin keluar dari aplikasi?', confirmText: 'Ya, Logout', type: 'warning', formId: 'logout-form' })" class="text-slate-400 hover:text-red-500 transition p-1.5 hover:bg-red-50 rounded-full" title="Logout">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        <!-- CONTENT (scrollable) -->
        <main class="flex-1 overflow-y-auto">
            {{ $slot }}
        </main>

    </div>

</div>

<!-- Global Confirm Dialog -->
<x-confirm-dialog />

<!-- Global Toast Notification -->
<x-toast-notification />

@stack('scripts')
</body>
</html>
