<x-app-layout>
    <x-slot name="header">Dashboard Guru</x-slot>
    <div class="p-6 lg:p-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Dashboard Guru</h1>
            <p class="text-sm text-slate-400 mt-1">Selamat datang kembali, {{ $guru->name }} 👋</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6 hover:shadow-md transition">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Tahun Ajaran Aktif</h3>
                        <p class="text-slate-500 text-sm">{{ $tahunAktif ? $tahunAktif->nama_tahun : 'Belum diatur' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6 hover:shadow-md transition">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Status Wali Kelas</h3>
                        @if($kelasWali)
                            <p class="text-emerald-600 text-sm font-semibold">Wali Kelas {{ $kelasWali->nama_kelas }}</p>
                        @else
                            <p class="text-slate-500 text-sm">Bukan Wali Kelas</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('guru.scan') }}" class="group bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Scan Absensi</h3>
                        <p class="text-slate-400 text-xs mt-0.5">Lakukan absensi harian</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('guru.riwayat-absensi') }}" class="group bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-cyan-50 rounded-xl flex items-center justify-center text-cyan-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Riwayat Absensi</h3>
                        <p class="text-slate-400 text-xs mt-0.5">Lihat catatan kehadiran Anda</p>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('guru.absensi-siswa.index') }}" class="group bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Input Absensi Siswa</h3>
                        <p class="text-slate-400 text-xs mt-0.5">Catat kehadiran kelas</p>
                    </div>
                </div>
            </a>
            
            @if($kelasWali)
            <a href="{{ route('guru.laporan-kelas') }}" class="group bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Laporan Kelas</h3>
                        <p class="text-slate-400 text-xs mt-0.5">Rekap absensi kelas Anda</p>
                    </div>
                </div>
            </a>
            @endif

            <a href="{{ route('guru.rekap-absensi.index') }}" class="group bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Laporan Mengajar</h3>
                        <p class="text-slate-400 text-xs mt-0.5">Rekapitulasi kehadiran mapel</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>

