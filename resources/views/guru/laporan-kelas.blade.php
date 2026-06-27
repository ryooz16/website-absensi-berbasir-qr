<x-app-layout>
    <x-slot name="header">Laporan Absensi Kelas</x-slot>
    <div class="p-4 md:p-6 lg:p-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Laporan Absensi Kelas {{ $kelas->nama_kelas }}</h1>
            <p class="text-sm text-slate-400 mt-1">Rekapitulasi kehadiran siswa di kelas yang Anda pimpin.</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 md:p-6 mb-6">
            <form action="{{ route('guru.laporan-kelas') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           class="w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                </div>
                <div class="flex-1 w-full">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           class="w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                </div>
                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit" class="flex-1 md:flex-none bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm shadow-indigo-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Tampilkan
                    </button>
                    <a href="{{ route('guru.laporan-kelas.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                       class="flex-1 md:flex-none bg-emerald-500 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-emerald-600 transition shadow-sm shadow-emerald-200 flex items-center justify-center gap-2 text-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export Excel
                    </a>
                </div>
            </form>
        </div>

        <!-- DATA LIST -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider w-10 text-center">No</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Siswa</th>
                            <th class="px-4 py-4 text-center text-[11px] font-bold text-emerald-500 uppercase tracking-wider w-20">H</th>
                            <th class="px-4 py-4 text-center text-[11px] font-bold text-amber-500 uppercase tracking-wider w-20">S</th>
                            <th class="px-4 py-4 text-center text-[11px] font-bold text-blue-500 uppercase tracking-wider w-20">I</th>
                            <th class="px-4 py-4 text-center text-[11px] font-bold text-red-500 uppercase tracking-wider w-20">A</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($siswa as $index => $s)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-center text-slate-400 font-medium text-xs">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700">{{ $s->nama }}</span>
                                        <span class="text-[10px] font-mono text-slate-400">{{ $s->nis }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center font-bold text-emerald-600 bg-emerald-50/30">{{ $rekap[$s->pivot->id]['hadir'] ?? 0 }}</td>
                                <td class="px-4 py-4 text-center font-bold text-amber-600 bg-amber-50/30">{{ $rekap[$s->pivot->id]['sakit'] ?? 0 }}</td>
                                <td class="px-4 py-4 text-center font-bold text-blue-600 bg-blue-50/30">{{ $rekap[$s->pivot->id]['izin'] ?? 0 }}</td>
                                <td class="px-4 py-4 text-center font-bold text-red-600 bg-red-50/30">{{ $rekap[$s->pivot->id]['alpha'] ?? 0 }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada data siswa.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden divide-y divide-slate-100">
                @forelse($siswa as $index => $s)
                    <div class="p-4 hover:bg-slate-50 transition">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-500 text-xs font-bold shrink-0">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm leading-tight">{{ $s->nama }}</h4>
                                    <p class="text-[10px] text-slate-400 font-mono mt-1">{{ $s->nis }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            <div class="bg-emerald-50 rounded-lg p-2 text-center">
                                <p class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest mb-0.5">HADIR</p>
                                <p class="text-sm font-black text-emerald-700">{{ $rekap[$s->pivot->id]['hadir'] ?? 0 }}</p>
                            </div>
                            <div class="bg-amber-50 rounded-lg p-2 text-center">
                                <p class="text-[9px] font-bold text-amber-500 uppercase tracking-widest mb-0.5">SAKIT</p>
                                <p class="text-sm font-black text-amber-700">{{ $rekap[$s->pivot->id]['sakit'] ?? 0 }}</p>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-2 text-center">
                                <p class="text-[9px] font-bold text-blue-500 uppercase tracking-widest mb-0.5">IZIN</p>
                                <p class="text-sm font-black text-blue-700">{{ $rekap[$s->pivot->id]['izin'] ?? 0 }}</p>
                            </div>
                            <div class="bg-red-50 rounded-lg p-2 text-center">
                                <p class="text-[9px] font-bold text-red-500 uppercase tracking-widest mb-0.5">ALPHA</p>
                                <p class="text-sm font-black text-red-700">{{ $rekap[$s->pivot->id]['alpha'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-sm italic">Belum ada data siswa.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

