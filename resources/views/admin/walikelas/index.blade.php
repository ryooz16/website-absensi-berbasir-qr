<x-app-layout>
    <x-slot name="header">Penugasan Wali Kelas</x-slot>
<div x-data="{ 
    editActionUrl: '{{ old('edit_url') }}', 
    editData: { 
        id: '{{ old('id') }}', 
        guru_id: '{{ old('guru_id') }}', 
        kelas_id: '{{ old('kelas_id') }}',
        guru_name: '{{ old('guru_name') }}',
        kelas_name: '{{ old('kelas_name') }}'
    },
    openEdit(data, url) {
        this.editData = data;
        this.editActionUrl = url;
        $dispatch('open-modal', 'edit-walikelas');
    }
}">
    <div class="p-4 md:p-6 lg:p-8">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-slate-800">Wali Kelas</h1>
                    @if($tahunAktifGlobal)
                            <span class="bg-indigo-50 border border-indigo-100 text-indigo-600 px-2.5 py-1 rounded-lg text-xs font-semibold">
                                {{ $tahunAktifGlobal->nama_tahun }}
                            </span>
                    @endif
                </div>
                <p class="text-sm text-slate-400 mt-1">Kelola penugasan wali kelas untuk setiap rombel.</p>
            </div>
            <button x-on:click.prevent="$dispatch('open-modal', 'tambah-walikelas')" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm shadow-indigo-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Assign Wali Kelas
            </button>
        </div>

        <!-- SEARCH -->
        <form action="{{ route('admin.walikelas.index') }}" method="GET" class="bg-white rounded-2xl border border-slate-200/60 shadow-sm mb-6">
            <div class="flex items-center gap-3 p-3">
                <div class="flex-1 relative">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama guru atau kelas..." class="w-full pl-10 pr-4 py-2.5 border-0 bg-slate-50 rounded-xl text-sm placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition">
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-xs font-semibold hover:bg-indigo-700 transition shadow-sm">Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.walikelas.index') }}" class="bg-slate-100 text-slate-500 px-4 py-2.5 rounded-xl text-xs font-semibold hover:bg-slate-200 transition">Reset</a>
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
                            <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Guru</th>
                            <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                    @forelse($wali as $w)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 text-xs font-bold">
                                        {{ strtoupper(substr($w->guru->name ?? '-', 0, 2)) }}
                                    </div>
                                    <span class="font-semibold text-sm text-slate-700">{{ $w->guru->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-lg text-xs font-bold">{{ $w->kelas->nama_kelas ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($w->status === 'aktif')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Aktif
                                    </span>
                                @else
                                    <span class="text-xs font-semibold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-lg">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-1">
                                    <button @click="openEdit({ id: '{{ $w->id }}', guru_id: '{{ $w->guru_id }}', kelas_id: '{{ $w->kelas_id }}', guru_name: '{{ addslashes($w->guru->name) }}', kelas_name: '{{ $w->kelas->nama_kelas }}' }, '{{ route('admin.walikelas.update', $w->id) }}')" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </button>
                                    <button @click="$dispatch('confirm-dialog', { title: 'Hapus Wali Kelas?', message: 'Dilepas dari {{ addslashes($w->kelas->nama_kelas ?? '') }}?', confirmText: 'Hapus', type: 'danger', formId: 'delete-wali-{{ $w->id }}' })" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    <form id="delete-wali-{{ $w->id }}" action="{{ route('admin.walikelas.destroy', $w->id) }}" method="POST">@csrf @method('DELETE')</form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400">Belum ada data wali kelas.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden divide-y divide-slate-100">
                @forelse($wali as $w)
                    <div class="p-4 hover:bg-slate-50 transition active:bg-slate-100">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($w->guru->name ?? '-', 0, 2)) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm leading-tight">{{ $w->guru->name ?? '-' }}</h4>
                                    <span class="inline-block mt-1 bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-[10px] font-bold">Kelas {{ $w->kelas->nama_kelas ?? '-' }}</span>
                                </div>
                            </div>
                            @if($w->status === 'aktif')
                                <span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider border border-emerald-100">Aktif</span>
                            @else
                                <span class="bg-slate-100 text-slate-400 px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider">Non-Aktif</span>
                            @endif
                        </div>
                        
                        <div class="flex gap-2">
                            <button @click="openEdit({ id: '{{ $w->id }}', guru_id: '{{ $w->guru_id }}', kelas_id: '{{ $w->kelas_id }}', guru_name: '{{ addslashes($w->guru->name) }}', kelas_name: '{{ $w->kelas->nama_kelas }}' }, '{{ route('admin.walikelas.update', $w->id) }}')"
                               class="flex-1 bg-slate-50 text-slate-600 py-2 rounded-lg text-xs font-semibold border border-slate-200 flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                Edit
                            </button>
                            <button @click="$dispatch('confirm-dialog', { title: 'Hapus Wali Kelas?', message: 'Dilepas dari {{ addslashes($w->kelas->nama_kelas ?? '') }}?', confirmText: 'Hapus', type: 'danger', formId: 'delete-wali-{{ $w->id }}' })"
                               class="flex-1 bg-white text-red-500 py-2 rounded-lg text-xs font-semibold border border-red-100 flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-sm italic">Belum ada data wali kelas.</div>
                @endforelse
            </div>

        </div>
    </div>

    <!-- MODAL TAMBAH -->
    <x-modal name="tambah-walikelas" :show="(old('_method') !== 'PUT' && $errors->any()) || session('error_tambah')" focusable>
        <div class="p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-1">Assign Wali Kelas</h2>
            <p class="text-sm text-slate-400 mb-6">Tetapkan guru sebagai wali kelas.</p>
            
            @if(session('error_tambah'))
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-4 text-xs font-medium">{{ session('error_tambah') }}</div>
            @endif
            
            <form action="{{ route('admin.walikelas.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Guru</label>
                    <select name="guru_id" class="w-full border border-slate-200 rounded-xl p-2.5 text-sm bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('guru_id') border-red-400 @enderror">
                        <option value="">Pilih Guru (belum memiliki kelas)</option>
                        @foreach($guruTersedia as $g)
                            <option value="{{ $g->id }}" {{ old('guru_id') == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                        @endforeach
                    </select>
                    @error('guru_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kelas</label>
                    <select name="kelas_id" class="w-full border border-slate-200 rounded-xl p-2.5 text-sm bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('kelas_id') border-red-400 @enderror">
                        <option value="">Pilih Kelas (belum ada wali)</option>
                        @foreach($kelasTersedia as $k)
                            <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                    @error('kelas_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" x-on:click="$dispatch('close')" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-xs font-semibold transition">Batal</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm">Assign</button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- MODAL EDIT -->
    <x-modal name="edit-walikelas" :show="(old('_method') === 'PUT' && $errors->any()) || session('error_edit')" focusable>
        <div class="p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-1">Edit Wali Kelas</h2>
            <p class="text-sm text-slate-400 mb-6">Ubah penugasan wali kelas.</p>
            
            @if(session('error_edit'))
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-4 text-xs font-medium">{{ session('error_edit') }}</div>
            @endif
            
            <form :action="editActionUrl" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <input type="hidden" name="edit_url" :value="editActionUrl">
                <input type="hidden" name="status" value="aktif">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Guru</label>
                    <select name="guru_id" x-model="editData.guru_id" class="w-full border border-slate-200 rounded-xl p-2.5 text-sm bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        <option :value="editData.guru_id" x-text="editData.guru_name" selected></option>
                        <option disabled>──────────</option>
                        @foreach($guruTersedia as $g)
                            <option value="{{ $g->id }}" x-show="editData.guru_id != {{ $g->id }}">{{ $g->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kelas</label>
                    <select name="kelas_id" x-model="editData.kelas_id" class="w-full border border-slate-200 rounded-xl p-2.5 text-sm bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        <option :value="editData.kelas_id" x-text="editData.kelas_name" selected></option>
                        <option disabled>──────────</option>
                        @foreach($kelasTersedia as $k)
                            <option value="{{ $k->id }}" x-show="editData.kelas_id != {{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" x-on:click="$dispatch('close')" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-xs font-semibold transition">Batal</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </x-modal>
</div>
</x-app-layout>

