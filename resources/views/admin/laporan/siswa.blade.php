<x-app-layout>
    <x-slot name="header">
        Laporan Absensi Siswa
    </x-slot>

    <div class="p-4 md:p-6 lg:p-8" x-data>
        <!-- HEADER -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Laporan Absensi Siswa</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau statistik kehadiran dan rekapitulasi absensi siswa secara detail.</p>
        </div>

        <!-- FILTER SECTION -->
        <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-slate-200 mb-8" x-data="{ periode: '{{ $periodeAktif ?? 'hari_ini' }}' }">
            <form action="{{ route('admin.laporan.siswa.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    <!-- Periode -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Periode</label>
                        <select name="periode" x-model="periode" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="hari_ini">Hari Ini</option>
                            <option value="minggu_ini">Minggu Ini</option>
                            <option value="bulan_ini">Bulan Ini</option>
                            <option value="tahun_ajaran">Tahun Ajaran Aktif</option>
                            <option value="kustom">Kustom Tanggal</option>
                        </select>
                    </div>

                    <!-- Tanggal Awal -->
                    <div x-show="periode === 'kustom'" x-cloak>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" value="{{ $tanggalAwal }}" :required="periode === 'kustom'"
                               class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                    
                    <!-- Tanggal Akhir -->
                    <div x-show="periode === 'kustom'" x-cloak>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}" :required="periode === 'kustom'"
                               class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>

                    <!-- Kelas -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kelas</label>
                        <select name="kelas_id" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">Semua Kelas</option>
                            @foreach($semuaKelas as $k)
                                <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Mapel -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mata Pelajaran</label>
                        <select name="mata_pelajaran_id" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">Semua Mata Pelajaran</option>
                            @foreach($semuaMapel as $m)
                                <option value="{{ $m->id }}" {{ $mapelId == $m->id ? 'selected' : '' }}>{{ $m->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cari Nama</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." 
                               class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                </div>

                <div class="flex flex-col md:flex-row justify-end gap-3 pt-4 border-t border-slate-100 mt-4">
                    @if(request()->hasAny(['periode', 'search', 'kelas_id', 'mata_pelajaran_id']))
                    <a href="{{ route('admin.laporan.siswa.index') }}" class="w-full md:w-auto px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition text-center">
                        Reset
                    </a>
                    @endif
                    <button type="submit" class="w-full md:w-auto px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Terapkan Filter
                    </button>
                    <!-- Tombol Export menyesuaikan parameter -->
                    <a href="{{ route('admin.laporan.siswa.export', request()->all()) }}" class="w-full md:w-auto px-5 py-2.5 rounded-xl text-sm font-bold text-emerald-700 bg-emerald-100 hover:bg-emerald-200 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export Excel
                    </a>
                </div>
            </form>
        </div>

        <!-- KPI CARDS -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase">Hadir</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-2xl font-black text-slate-800">{{ $kpiStats['hadir'] }}</h3>
                        <span class="text-xs font-bold text-emerald-500">({{ $kpiStats['persentase_hadir'] }}%)</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase">Sakit</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $kpiStats['sakit'] }}</h3>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase">Izin</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $kpiStats['izin'] }}</h3>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-red-200 shadow-sm flex items-center gap-4 relative overflow-hidden">
                <div class="absolute inset-y-0 left-0 w-1 bg-red-500"></div>
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 ml-2">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-red-500 uppercase">Alpha</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $kpiStats['alpha'] }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden"
             x-data="{
                data: {{ json_encode($rekapSiswa) }},
                perPage: 10,
                currentPage: 1,
                get totalPages() {
                    return Math.max(1, Math.ceil(this.data.length / this.perPage));
                },
                get paginatedData() {
                    const start = (this.currentPage - 1) * this.perPage;
                    const end = start + parseInt(this.perPage);
                    return this.data.slice(start, end);
                },
                nextPage() {
                    if (this.currentPage < this.totalPages) this.currentPage++;
                },
                prevPage() {
                    if (this.currentPage > 1) this.currentPage--;
                },
                changePerPage() {
                    this.currentPage = 1;
                }
             }">
             
            <!-- Custom Load (Per Page) -->
            <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-slate-500">Tampilkan</span>
                    <select x-model="perPage" @change="changePerPage" class="rounded-xl border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1.5 pl-3 pr-8">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm font-medium text-slate-500">siswa</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Kelas</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Hadir</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Sakit</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Izin</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Alpha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-if="data.length === 0">
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    Tidak ada data absensi untuk rentang tanggal tersebut.
                                </td>
                            </tr>
                        </template>
                        
                        <template x-for="(siswa, index) in paginatedData" :key="index">
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-semibold text-slate-800" x-text="siswa.nama"></td>
                                <td class="px-6 py-4 text-center text-sm font-medium text-slate-600" x-text="siswa.kelas"></td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-bold"
                                          :class="siswa.hadir > 0 ? 'bg-emerald-100 text-emerald-700' : 'text-slate-300'"
                                          x-text="siswa.hadir">
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-bold"
                                          :class="siswa.sakit > 0 ? 'bg-amber-100 text-amber-700' : 'text-slate-300'"
                                          x-text="siswa.sakit">
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-bold"
                                          :class="siswa.izin > 0 ? 'bg-blue-100 text-blue-700' : 'text-slate-300'"
                                          x-text="siswa.izin">
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-bold"
                                          :class="siswa.alpha > 0 ? 'bg-red-100 text-red-700' : 'text-slate-300'"
                                          x-text="siswa.alpha">
                                    </span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Buttons -->
            <div class="p-4 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-50/50" x-show="data.length > 0" x-cloak>
                <span class="text-sm text-slate-500">
                    Menampilkan <span class="font-bold text-slate-700" x-text="Math.min((currentPage - 1) * perPage + 1, data.length)"></span> 
                    sampai <span class="font-bold text-slate-700" x-text="Math.min(currentPage * perPage, data.length)"></span> 
                    dari <span class="font-bold text-slate-700" x-text="data.length"></span> siswa
                </span>
                <div class="flex gap-2">
                    <button @click="prevPage" :disabled="currentPage === 1" 
                            class="px-4 py-2 rounded-xl text-sm font-bold transition border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed text-slate-600">
                        Sebelumnya
                    </button>
                    <button @click="nextPage" :disabled="currentPage === totalPages" 
                            class="px-4 py-2 rounded-xl text-sm font-bold transition border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed text-slate-600">
                        Selanjutnya
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

