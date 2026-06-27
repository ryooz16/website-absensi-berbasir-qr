<x-app-layout>
    <x-slot name="header">
        Laporan Rekapitulasi Mengajar
    </x-slot>

    <div class="p-4 md:p-6 lg:p-8" x-data="{ 
        detailModal: false, 
        loading: false, 
        selectedTitle: '',
        rekapSiswa: [],
        startDate: '{{ $startDate }}',
        endDate: '{{ $endDate }}',
        openDetail(kelasId, mapelId, title) {
            this.selectedTitle = title;
            this.loading = true;
            this.detailModal = true;
            fetch(`{{ route('guru.rekap-absensi.show') }}?kelas_id=${kelasId}&mata_pelajaran_id=${mapelId}&start_date=${this.startDate}&end_date=${this.endDate}`)
                .then(res => res.json())
                .then(res => {
                    this.rekapSiswa = res.data;
                    this.loading = false;
                });
        }
    }">
        <!-- HEADER PAGE -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Laporan Rekapitulasi</h1>
            <p class="text-sm text-slate-400 mt-1">Akumulasi kehadiran siswa pada mata pelajaran yang Anda ampu.</p>
        </div>

        <!-- FILTER RANGE -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 md:p-6 mb-8">
            <form action="{{ route('guru.rekap-absensi.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Dari Tanggal</label>
                    <input type="date" name="start_date" x-model="startDate"
                           class="w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                </div>
                <div class="flex-1 w-full">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="end_date" x-model="endDate"
                           class="w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                </div>
                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit" class="flex-1 md:flex-none bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm shadow-indigo-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter Data
                    </button>
                    <a href="{{ route('guru.rekap-absensi.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                       class="flex-1 md:flex-none bg-emerald-500 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-emerald-600 transition shadow-sm shadow-emerald-200 flex items-center justify-center gap-2 text-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export Semua
                    </a>
                </div>
            </form>
        </div>

        <!-- INFO TA -->
        <!-- <div class="bg-indigo-600 rounded-2xl p-6 mb-8 text-white relative overflow-hidden shadow-lg shadow-indigo-200">
            <div class="relative z-10">
                <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest mb-1">Tahun Ajaran Aktif</p>
                <h2 class="text-2xl font-bold">{{ $tahunAktif->nama_tahun }}</h2>
            </div>
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"/><path d="M7 10h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z"/></svg>
            </div>
        </div> -->

        <!-- GRID REKAP -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($rekap as $subject)
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                    <!-- Subject Header -->
                    <div class="p-6 bg-slate-50/50 border-b border-slate-100">
                        <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-100 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg leading-tight">{{ $subject['nama_mapel'] }}</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Daftar Kelas Terdaftar</p>
                    </div>
                    
                    <!-- Classes List -->
                    <div class="flex-1 p-4 space-y-3">
                        @foreach($subject['classes'] as $cls)
                            <div class="p-3 rounded-2xl border border-slate-100 bg-white hover:border-indigo-100 transition group/item">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas</p>
                                        <p class="text-sm font-black text-slate-800">{{ $cls['nama_kelas'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] font-bold text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100">
                                            {{ $cls['total_pertemuan'] }} Pertemuan
                                        </p>
                                    </div>
                                </div>
                                <button @click="openDetail({{ $cls['kelas_id'] }}, {{ $subject['mata_pelajaran_id'] }}, '{{ $subject['nama_mapel'] }} - {{ $cls['nama_kelas'] }}')"
                                        class="w-full bg-slate-800 hover:bg-indigo-600 text-white py-2.5 rounded-xl text-[11px] font-bold transition flex items-center justify-center gap-2">
                                    Detail Absensi Siswa
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <p class="text-slate-400 font-medium italic">Belum ada data absensi yang Anda input.</p>
                </div>
            @endforelse
        </div>

        <!-- DETAIL MODAL -->
        <template x-if="detailModal">
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 md:p-6 overflow-hidden">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="detailModal = false"></div>
                
                <div class="bg-white w-full max-w-4xl max-h-full rounded-3xl shadow-2xl relative flex flex-col overflow-hidden" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-8"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    
                    <!-- MODAL HEADER -->
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
                        <div>
                            <h2 class="text-xl font-bold text-slate-800" x-text="selectedTitle"></h2>
                            <p class="text-xs text-slate-400 mt-1">Akumulasi kehadiran per siswa.</p>
                        </div>
                        <button @click="detailModal = false" class="p-2 hover:bg-slate-50 rounded-xl text-slate-400 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- MODAL CONTENT -->
                    <div class="flex-1 overflow-y-auto p-0 md:p-6 bg-slate-50/30">
                        <div x-show="loading" class="py-20 text-center">
                            <div class="inline-block w-8 h-8 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                            <p class="text-sm text-slate-500 font-medium">Memuat data rekap...</p>
                        </div>

                        <div x-show="!loading" class="bg-white md:rounded-2xl border-y md:border border-slate-200 overflow-hidden shadow-sm">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left">
                                    <thead>
                                        <tr class="bg-slate-50/50 border-b border-slate-100">
                                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Siswa</th>
                                            <th class="px-4 py-4 text-center text-[10px] font-bold text-emerald-500 uppercase tracking-widest">H</th>
                                            <th class="px-4 py-4 text-center text-[10px] font-bold text-amber-500 uppercase tracking-widest">S</th>
                                            <th class="px-4 py-4 text-center text-[10px] font-bold text-sky-500 uppercase tracking-widest">I</th>
                                            <th class="px-4 py-4 text-center text-[10px] font-bold text-rose-500 uppercase tracking-widest">A</th>
                                            <th class="px-6 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        <template x-for="siswa in rekapSiswa" :key="siswa.nis">
                                            <tr class="hover:bg-slate-50/50 transition">
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-col">
                                                        <span class="font-bold text-slate-800 text-sm" x-text="siswa.nama"></span>
                                                        <span class="text-[10px] font-mono text-slate-400" x-text="siswa.nis"></span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 text-center">
                                                    <span class="inline-block min-w-[28px] py-1 bg-emerald-50 text-emerald-600 font-bold rounded-lg text-xs" x-text="siswa.hadir"></span>
                                                </td>
                                                <td class="px-4 py-4 text-center">
                                                    <span class="inline-block min-w-[28px] py-1 bg-amber-50 text-amber-600 font-bold rounded-lg text-xs" x-text="siswa.sakit"></span>
                                                </td>
                                                <td class="px-4 py-4 text-center">
                                                    <span class="inline-block min-w-[28px] py-1 bg-sky-50 text-sky-600 font-bold rounded-lg text-xs" x-text="siswa.izin"></span>
                                                </td>
                                                <td class="px-4 py-4 text-center">
                                                    <span class="inline-block min-w-[28px] py-1 bg-rose-50 text-rose-600 font-bold rounded-lg text-xs" x-text="siswa.alpha"></span>
                                                </td>
                                                <td class="px-6 py-4 text-center font-bold text-slate-600" x-text="siswa.total"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- MODAL FOOTER -->
                    <div class="p-6 border-t border-slate-100 bg-white flex justify-end">
                        <button @click="detailModal = false" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">Tutup Laporan</button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>

