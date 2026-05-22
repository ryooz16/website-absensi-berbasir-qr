<x-app-layout>
    <x-slot name="header">Input Absensi Siswa</x-slot>
    <div class="p-4 md:p-6 lg:p-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Input Absensi Siswa</h1>
            <p class="text-sm text-slate-400 mt-1">Pilih kelas dan mata pelajaran untuk mencatat kehadiran siswa hari ini.</p>
        </div>


        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-4 md:p-6 mb-6">
            <form action="{{ route('guru.absensi-siswa.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Pilih Kelas</label>
                    <select name="kelas_id" class="w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ ($selectedKelas?->id == $k->id) ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 w-full">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Pilih Mata Pelajaran</label>
                    <select name="mapel_id" class="w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($mapel as $m)
                            <option value="{{ $m->id }}" {{ ($selectedMapel?->id == $m->id) ? 'selected' : '' }}>{{ $m->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 w-full">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" class="w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" required>
                </div>
                <button type="submit" class="w-full md:w-auto bg-slate-800 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-700 transition">
                    Tampilkan
                </button>
            </form>
        </div>

        @if($selectedKelas && $selectedMapel)
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <div>
                        <h2 class="font-bold text-slate-800">Daftar Siswa - Kelas {{ $selectedKelas->nama_kelas }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Mapel: {{ $selectedMapel->nama_mapel }} | Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>
                    </div>
                </div>

                <form action="{{ route('guru.absensi-siswa.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
                    <input type="hidden" name="mata_pelajaran_id" value="{{ $selectedMapel->id }}">
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left min-w-[650px]">
                            <thead class="bg-white border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider w-10 text-center">No</th>
                                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">NIS</th>
                                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Lengkap</th>
                                    <th class="px-6 py-4 text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($siswa as $index => $s)
                                    @php
                                        $status = isset($presensi[$s->pivot->id]) ? $presensi[$s->pivot->id]->status : 'hadir';
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4 text-center text-slate-400 font-medium text-xs">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $s->nis }}</td>
                                        <td class="px-6 py-4 font-semibold text-slate-700">{{ $s->nama }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center gap-4">
                                                <label class="flex items-center gap-1.5 cursor-pointer">
                                                    <input type="radio" name="status[{{ $s->pivot->id }}]" value="hadir" {{ $status == 'hadir' ? 'checked' : '' }} class="text-emerald-500 focus:ring-emerald-500">
                                                    <span class="text-xs font-semibold text-emerald-600">Hadir</span>
                                                </label>
                                                <label class="flex items-center gap-1.5 cursor-pointer">
                                                    <input type="radio" name="status[{{ $s->pivot->id }}]" value="sakit" {{ $status == 'sakit' ? 'checked' : '' }} class="text-amber-500 focus:ring-amber-500">
                                                    <span class="text-xs font-semibold text-amber-600">Sakit</span>
                                                </label>
                                                <label class="flex items-center gap-1.5 cursor-pointer">
                                                    <input type="radio" name="status[{{ $s->pivot->id }}]" value="izin" {{ $status == 'izin' ? 'checked' : '' }} class="text-blue-500 focus:ring-blue-500">
                                                    <span class="text-xs font-semibold text-blue-600">Izin</span>
                                                </label>
                                                <label class="flex items-center gap-1.5 cursor-pointer">
                                                    <input type="radio" name="status[{{ $s->pivot->id }}]" value="alpha" {{ $status == 'alpha' ? 'checked' : '' }} class="text-red-500 focus:ring-red-500">
                                                    <span class="text-xs font-semibold text-red-600">Alpha</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-medium text-sm">
                                            Belum ada siswa di kelas ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(count($siswa) > 0)
                    <div class="p-5 border-t border-slate-100 bg-slate-50 flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm shadow-indigo-200">
                            Simpan Absensi
                        </button>
                    </div>
                    @endif
                </form>
            </div>
        @endif
    </div>
</x-app-layout>

