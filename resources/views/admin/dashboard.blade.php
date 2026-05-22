<x-app-layout>
    <div class="p-6 lg:p-8">
        <!-- GREETING -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Selamat Datang, {{ auth()->user()->name }} 👋</h1>
            <p class="text-sm text-slate-400 mt-1">Berikut ringkasan data sekolah Anda saat ini.</p>
        </div>



        <!-- STATS GRID -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-800">{{ $siswaAktif }}</p>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Siswa Aktif</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-800">{{ $totalGuru }}</p>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Total Guru</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-800">{{ $totalKelas }}</p>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Total Kelas</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-800">{{ $totalMapel }}</p>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Mata Pelajaran</p>
            </div>
        </div>

        <!-- QUICK LINKS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('admin.qr-absensi.index') }}" class="group bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-200 hover:shadow-xl hover:shadow-indigo-300 transition-all hover:-translate-y-0.5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/15 rounded-xl flex items-center justify-center backdrop-blur">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">QR Absensi</h3>
                        <p class="text-indigo-200 text-xs">Kelola absensi guru via QR Code</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.laporan.siswa.index') }}" class="group bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Laporan Siswa</h3>
                        <p class="text-slate-400 text-xs">Lihat rekap absensi siswa</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.laporan.guru.index') }}" class="group bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Laporan Guru</h3>
                        <p class="text-slate-400 text-xs">Lihat rekap absensi guru</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
