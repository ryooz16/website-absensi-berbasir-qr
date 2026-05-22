<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ (isset($title) ? $title . ' - ' : '') . config('app.name', 'Absensi Sekolah') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('logo-tbg.png') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>* { font-family: 'Inter', sans-serif; }</style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            <!-- LEFT PANEL - Branding -->
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-indigo-600 via-indigo-700 to-slate-800 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-20 left-20 w-72 h-72 bg-white rounded-full blur-3xl"></div>
                    <div class="absolute bottom-20 right-20 w-96 h-96 bg-indigo-300 rounded-full blur-3xl"></div>
                </div>
                <div class="relative z-10 flex flex-col justify-center px-16">
                    <div class="mb-8">
                        <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-20 h-20 rounded-2xl shadow-2xl border-2 border-white/20 object-cover">
                    </div>
                    <h1 class="text-4xl font-bold text-white leading-tight mb-4">Sistem Absensi<br>Sekolah</h1>
                    <p class="text-indigo-200 text-lg leading-relaxed max-w-md">Platform manajemen kehadiran modern untuk mengelola absensi guru dan siswa secara efisien.</p>
                    
                    <div class="mt-12 flex gap-8">
                        <div>
                            <div class="text-3xl font-bold text-white">24/7</div>
                            <div class="text-xs text-indigo-300 font-medium mt-1">Monitoring</div>
                        </div>
                        <div class="w-px bg-white/20"></div>
                        <div>
                            <div class="text-3xl font-bold text-white">QR</div>
                            <div class="text-xs text-indigo-300 font-medium mt-1">Absensi</div>
                        </div>
                        <div class="w-px bg-white/20"></div>
                        <div>
                            <div class="text-3xl font-bold text-white">Real</div>
                            <div class="text-xs text-indigo-300 font-medium mt-1">Time Data</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT PANEL - Login Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-slate-50">
                <div class="w-full max-w-md">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden flex items-center gap-3 mb-10">
                        <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-12 h-12 rounded-xl object-cover shadow-lg shadow-indigo-200">
                        <h1 class="text-xl font-bold text-slate-800">Absensi Sekolah</h1>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-slate-800 mb-1">Selamat Datang</h2>
                        <p class="text-slate-500 text-sm">Silakan masuk ke akun Anda untuk melanjutkan.</p>
                    </div>

                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>

