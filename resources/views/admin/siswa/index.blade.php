<x-app-layout>
    <x-slot name="header">Kelola Data Siswa</x-slot>
    <div x-data="{ 
        editActionUrl: '{{ old('edit_url') }}', 
        editData: { 
            id: '{{ old('id') }}', 
            nis: '{{ old('nis') }}', 
            nama: '{{ old('nama') }}' 
        },
        openEdit(data, url) {
            this.editData = data;
            this.editActionUrl = url;
            $dispatch('open-modal', 'edit-siswa');
        }
    }">
        <div class="p-4 md:p-6 lg:p-8">

            <!-- HEADER -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-bold text-slate-800">Data Siswa</h1>
                        @if($tahunAktifGlobal)
                                <span class="bg-indigo-50 border border-indigo-100 text-indigo-600 px-2.5 py-1 rounded-lg text-xs font-semibold">
                                    {{ $tahunAktifGlobal->nama_tahun }}
                                </span>
                        @endif
                    </div>
                    <p class="text-sm text-slate-400 mt-1">Kelola data peserta didik dan status keaktifan.</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @if($graduatedCount > 0)
                        <button x-on:click.prevent="$dispatch('open-modal', 'clean-graduated-modal')"
                           class="inline-flex items-center gap-2 bg-rose-50 hover:bg-rose-100 text-rose-600 px-4 py-2.5 rounded-xl text-xs font-semibold transition border border-rose-100 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Bersihkan Siswa Lulus
                            <span class="bg-rose-200 text-rose-800 text-[10px] px-1.5 py-0.2 rounded-full font-bold ml-0.5">{{ $graduatedCount }}</span>
                        </button>
                    @endif
                    <button x-on:click.prevent="$dispatch('open-modal', 'tambah-siswa')"
                       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm shadow-indigo-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Siswa
                    </button>
                </div>
            </div>

            <!-- STATS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/60 shadow-sm">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Siswa</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalSiswa }}</p>
                </div>
                <div class="bg-emerald-50 p-5 rounded-2xl border border-emerald-100 shadow-sm">
                    <p class="text-xs font-semibold text-emerald-500 uppercase tracking-wider mb-1">Siswa Aktif</p>
                    <p class="text-2xl font-bold text-emerald-700">{{ $siswaAktif }}</p>
                </div>
                <div class="bg-amber-50 p-5 rounded-2xl border border-amber-100 shadow-sm">
                    <p class="text-xs font-semibold text-amber-500 uppercase tracking-wider mb-1">Non-Aktif</p>
                    <p class="text-2xl font-bold text-amber-700">{{ $siswaNonAktif }}</p>
                </div>
            </div>

            <!-- FILTER & SEARCH -->
            <form action="{{ route('admin.siswa.index') }}" method="GET" class="bg-white rounded-2xl border border-slate-200/60 shadow-sm mb-6">
                <div class="flex flex-wrap gap-3 p-3 items-end">
                    <div class="flex-1 min-w-[200px] relative">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari nama atau NIS..." 
                               class="w-full pl-10 pr-4 py-2.5 border-0 bg-slate-50 rounded-xl text-sm placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition">
                    </div>

                    <select name="kelas_id" class="border-0 bg-slate-50 rounded-xl py-2.5 px-4 text-sm text-slate-600 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition min-w-[160px]">
                        <option value="">Semua Kelas</option>
                        @foreach($allKelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>Kelas {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>

                    <select name="status" class="border-0 bg-slate-50 rounded-xl py-2.5 px-4 text-sm text-slate-600 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition min-w-[140px]">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="non-aktif" {{ request('status') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>

                    <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-xs font-semibold hover:bg-indigo-700 transition shadow-sm">Cari</button>
                    @if(request()->anyFilled(['search', 'kelas_id', 'status']))
                        <a href="{{ route('admin.siswa.index') }}" class="bg-slate-100 text-slate-500 px-4 py-2.5 rounded-xl text-xs font-semibold hover:bg-slate-200 transition">Reset</a>
                    @endif
                </div>
            </form>

            <!-- NOTIFICATIONS -->

            <!-- DATA LIST -->
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                
                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">NIS</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                        @forelse($siswa as $s)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $s->nis }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 text-xs font-bold">
                                            {{ strtoupper(substr($s->nama, 0, 2)) }}
                                        </div>
                                        <span class="font-semibold text-sm text-slate-700">{{ $s->nama }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($s->kelasAktif->isNotEmpty())
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                            <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg">Aktif · Kelas {{ $s->kelasAktif->first()->nama_kelas }}</span>
                                        </div>
                                    @elseif($s->kelasTerakhir->isNotEmpty())
                                        @php $last = $s->kelasTerakhir->first(); @endphp
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 bg-slate-300 rounded-full"></span>
                                            <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">
                                                Non-Aktif (Eks: {{ $last->nama_kelas }} ({{ $last->pivot->tahunAjaran->nama_tahun ?? '-' }}))
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-xs font-semibold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-lg">Belum Ada Kelas</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-1">
                                        <button @click="openEdit({ id: '{{ $s->id }}', nis: '{{ $s->nis }}', nama: '{{ addslashes($s->nama) }}' }, '{{ route('admin.siswa.update', $s->id) }}')"
                                           class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                        </button>
                                        <button @click="$dispatch('confirm-dialog', { title: 'Hapus Siswa?', message: 'Hapus permanen {{ addslashes($s->nama) }}?', confirmText: 'Hapus', type: 'danger', formId: 'delete-siswa-{{ $s->id }}' })"
                                           class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                        <form id="delete-siswa-{{ $s->id }}" action="{{ route('admin.siswa.destroy', $s->id) }}" method="POST">@csrf @method('DELETE')</form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400">Belum ada data siswa.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden divide-y divide-slate-100">
                    @forelse($siswa as $s)
                        <div class="p-4 hover:bg-slate-50 transition active:bg-slate-100">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 text-xs font-bold shrink-0">
                                        {{ strtoupper(substr($s->nama, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800 text-sm leading-tight">{{ $s->nama }}</h4>
                                        <p class="text-[10px] font-mono text-slate-400 mt-1 uppercase tracking-wider">NIS: {{ $s->nis }}</p>
                                    </div>
                                </div>
                                @if($s->kelasAktif->isNotEmpty())
                                    <span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider border border-emerald-100">Kelas {{ $s->kelasAktif->first()->nama_kelas }}</span>
                                @elseif($s->kelasTerakhir->isNotEmpty())
                                    @php $lastMobile = $s->kelasTerakhir->first(); @endphp
                                    <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider border border-slate-200">
                                        Eks: {{ $lastMobile->nama_kelas }} ({{ $lastMobile->pivot->tahunAjaran->nama_tahun ?? '-' }})
                                    </span>
                                @else
                                    <span class="bg-slate-50 text-slate-400 px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider border border-slate-100">Tanpa Kelas</span>
                                @endif
                            </div>
                            
                            <div class="flex justify-end gap-2 pt-1">
                                <button @click="openEdit({ id: '{{ $s->id }}', nis: '{{ $s->nis }}', nama: '{{ addslashes($s->nama) }}' }, '{{ route('admin.siswa.update', $s->id) }}')"
                                   class="flex-1 bg-slate-50 text-slate-600 py-2 rounded-lg text-xs font-semibold border border-slate-200 flex items-center justify-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    Edit
                                </button>
                                <button @click="$dispatch('confirm-dialog', { title: 'Hapus Siswa?', message: 'Hapus permanen {{ addslashes($s->nama) }}?', confirmText: 'Hapus', type: 'danger', formId: 'delete-siswa-{{ $s->id }}' })"
                                   class="flex-1 bg-white text-red-500 py-2 rounded-lg text-xs font-semibold border border-red-100 flex items-center justify-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400 text-sm italic">Belum ada data siswa.</div>
                    @endforelse
                </div>

            </div>
        </div>

        <!-- MODAL TAMBAH -->
        <x-modal name="tambah-siswa" :show="old('_method') !== 'PUT' && $errors->any()" focusable>
            <div class="p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-1">Tambah Siswa Baru</h2>
                <p class="text-sm text-slate-400 mb-6">Masukkan data peserta didik.</p>
                
                <form action="{{ route('admin.siswa.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">NIS</label>
                        <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Nomor Induk Siswa"
                               class="w-full border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('nis') border-red-400 @enderror">
                        @error('nis') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap siswa"
                               class="w-full border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('nama') border-red-400 @enderror">
                        @error('nama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <button type="button" x-on:click="$dispatch('close')" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-xs font-semibold transition">Batal</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </x-modal>

        <!-- MODAL EDIT -->
        <x-modal name="edit-siswa" :show="old('_method') === 'PUT' && $errors->any()" focusable>
            <div class="p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-1">Edit Data Siswa</h2>
                <p class="text-sm text-slate-400 mb-6">Perbarui informasi siswa.</p>
                
                <form :action="editActionUrl" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="edit_url" :value="editActionUrl">
                    <input type="hidden" name="id" :value="editData.id">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">NIS</label>
                        <input type="text" name="nis" :value="editData.nis"
                               class="w-full border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('nis') border-red-400 @enderror">
                        @error('nis') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="nama" :value="editData.nama"
                               class="w-full border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('nama') border-red-400 @enderror">
                        @error('nama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <button type="button" x-on:click="$dispatch('close')" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-xs font-semibold transition">Batal</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </x-modal>

        <!-- MODAL BERSIHKAN SISWA LULUS -->
        <x-modal name="clean-graduated-modal" focusable>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Bersihkan Siswa Lulus</h2>
                        <p class="text-xs text-slate-400">Pembersihan data alumni kelas 9 untuk melegakan database.</p>
                    </div>
                </div>

                <div class="mt-6 space-y-4">
                    <!-- Ringkasan Statistik -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/60">
                            <span class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Siswa Lulus</span>
                            <span class="text-xl font-bold text-slate-700">{{ $graduatedCount }}</span>
                        </div>
                        <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100">
                            <span class="block text-[10px] font-semibold text-emerald-500 uppercase tracking-wider mb-1">Siap Dibersihkan</span>
                            <span class="text-xl font-bold text-emerald-700">{{ $eligibleCount }}</span>
                        </div>
                    </div>

                    @if($unfrozenYearsNeeded->isNotEmpty())
                        <!-- Alert Tahun Ajaran Belum Dibekukan -->
                        <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div>
                                    <h4 class="text-xs font-bold text-amber-800">Arsip Tahun Ajaran Belum Dibekukan</h4>
                                    <p class="text-xs text-amber-700/90 mt-1 leading-relaxed">
                                        Ada {{ $graduatedCount - $eligibleCount }} siswa lulus yang belum bisa dibersihkan karena riwayat kelas/absensinya berada di tahun ajaran berikut yang belum dibekukan secara statis:
                                    </p>
                                    <div class="flex flex-wrap gap-1.5 mt-2.5">
                                        @foreach($unfrozenYearsNeeded as $year)
                                            <span class="bg-amber-100/80 border border-amber-200/60 text-amber-800 px-2 py-0.5 rounded text-[10px] font-semibold">
                                                {{ $year }}
                                            </span>
                                        @endforeach
                                    </div>
                                    <p class="text-[10px] text-amber-600 mt-2 leading-relaxed">
                                        *Silakan masuk ke menu <a href="{{ route('admin.kelas.index') }}" class="underline font-semibold hover:text-amber-800">Manajemen Kelas -> Siklus & Histori</a> untuk membekukan arsip tahun ajaran di atas terlebih dahulu.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($eligibleCount > 0)
                        <div class="bg-rose-50/60 border border-rose-100 rounded-xl p-4">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <h4 class="text-xs font-bold text-rose-800">Perhatian Sebelum Membersihkan</h4>
                                    <p class="text-xs text-rose-700/90 mt-1 leading-relaxed">
                                        Pembersihan akan menghapus secara permanen data profil siswa, pendaftaran kelas, dan semua riwayat absensi harian mereka di database. Karena arsip tahun ajaran terkait sudah dibekukan, laporan cetak / unduhan Excel statis untuk tahun ajaran tersebut akan tetap aman dan dapat diunduh kapan saja.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-center">
                            <p class="text-xs text-slate-500">
                                Tidak ada data siswa lulus eks kelas 9 yang siap dibersihkan saat ini. Harap bekukan arsip tahun ajaran yang diperlukan terlebih dahulu agar data siswa lulus aman untuk dihapus.
                            </p>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end gap-2 pt-4 mt-6 border-t border-slate-100">
                    <button type="button" x-on:click="$dispatch('close')" 
                            class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-xs font-semibold transition">
                        Batal
                    </button>
                    @if($eligibleCount > 0)
                        <form action="{{ route('admin.siswa.clean-graduated') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-rose-600 hover:bg-rose-700 text-white px-5 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm shadow-rose-200">
                                Bersihkan {{ $eligibleCount }} Siswa
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </x-modal>
    </div>
</x-app-layout>
