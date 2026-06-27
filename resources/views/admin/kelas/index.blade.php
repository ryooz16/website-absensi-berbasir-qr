<x-app-layout>
    <x-slot name="header">Kelola Data Kelas</x-slot>
<div x-data="{ 
    editActionUrl: '{{ old('edit_url') }}', 
    editData: { 
        id: '{{ old('id') }}', 
        nama_kelas: '{{ old('nama_kelas') }}'
    },
    openEdit(data, url) {
        this.editData = data;
        this.editActionUrl = url;
        $dispatch('open-modal', 'edit-kelas');
    }
}">
    <div class="p-6">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-slate-800">Manajemen Kelas</h1>
                    @if($tahunAktifGlobal)
                        <span class="bg-indigo-50 border border-indigo-100 text-indigo-600 px-2.5 py-1 rounded-lg text-xs font-semibold">
                            {{ $tahunAktifGlobal->nama_tahun }}
                        </span>
                    @endif
                </div>
                <p class="text-sm text-slate-400 mt-1">Kelola penempatan siswa aktif per tahun ajaran.</p>
            </div>

            <div class="flex gap-2">
                <button x-on:click.prevent="$dispatch('open-modal', 'kelola-siklus')"
                   class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-xs font-semibold hover:bg-slate-50 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Siklus & Histori
                </button>
                <button x-on:click.prevent="$dispatch('open-modal', 'tambah-kelas')"
                   class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm shadow-indigo-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Pengaturan Kelas
                </button>
            </div>
        </div>



        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($kelasAktif as $k)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:shadow-md transition group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-lg font-bold">
                            {{ substr($k->nama_kelas, 0, 1) }}
                        </div>
                    </div>

                    <h3 class="text-lg font-bold text-slate-800 mb-1">Kelas {{ $k->nama_kelas }}</h3>
                    
                    <div class="mb-4">
                        @if($k->waliAktif)
                            <p class="text-xs font-medium text-slate-500 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                                {{ $k->waliAktif->guru->name }}
                            </p>
                        @else
                            <p class="text-[11px] font-semibold text-red-500 bg-red-50 px-2.5 py-1 rounded-lg inline-flex items-center gap-1.5">
                                Wali Kelas belum ditentukan
                            </p>
                        @endif
                    </div>

                    <p class="text-sm text-slate-500 mb-5 flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                        {{ $k->siswa_count }} Siswa Aktif
                    </p>

                    <a href="{{ route('admin.kelas.show', $k->id) }}" class="block w-full text-center py-2.5 bg-slate-50 text-slate-600 rounded-xl text-xs font-semibold hover:bg-indigo-50 hover:text-indigo-600 transition">
                        Kelola Siswa
                    </a>
                </div>
            @empty
                <div class="col-span-full bg-white p-12 text-center rounded-2xl border border-slate-200 shadow-sm">
                    <svg class="w-12 h-12 mx-auto text-slate-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/></svg>
                    <p class="font-semibold text-slate-600">Belum ada kelas aktif.</p>
                    <p class="text-sm text-slate-400 mt-1">Gunakan tombol <span class="text-indigo-600 font-semibold">Pengaturan Kelas</span> untuk menambahkan.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- MODAL PENGATURAN KELAS -->
    <x-modal name="tambah-kelas" :show="old('_method') !== 'PUT' && $errors->any()" maxWidth="4xl" focusable>
        <div class="p-6">
            <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Pengaturan & Penempatan Kelas</h2>
                    <p class="text-sm text-slate-400 mt-1">Kelola pembuatan kelas baru dan penempatan siswa massal.</p>
                </div>
                <button type="button" x-on:click="$dispatch('close')" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- KIRI: FORM TAMBAH -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Buat Kelas Baru
                        </h3>
                        <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="text-xs font-semibold text-slate-600 block mb-1.5">Nama Kelas</label>
                                <input type="text" name="nama_kelas" 
                                       placeholder="Misal: 7-A"
                                       class="w-full border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('nama_kelas') border-red-500 @enderror"
                                       value="{{ old('nama_kelas') }}">
                                @error('nama_kelas')
                                    <p class="text-red-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl text-xs font-semibold transition shadow-sm shadow-indigo-200">
                                Simpan Kelas
                            </button>
                        </form>
                    </div>

                    <div class="bg-indigo-50 p-5 rounded-2xl border border-indigo-100">
                        <h4 class="text-xs font-bold text-indigo-800 mb-2 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Tips
                        </h4>
                        <p class="text-xs text-indigo-600/80 leading-relaxed">
                            Kelas yang sudah dibuat akan muncul di daftar sebelah kanan. Gunakan fitur impor untuk memindahkan kelas ke halaman utama.
                        </p>
                    </div>
                </div>

                <!-- KANAN: LIST KELAS KOSONG -->
                <div class="lg:col-span-2" x-data="{ 
                    files: {}, 
                    dragging: null,
                    handleDrop(e, id) {
                        const file = e.dataTransfer.files[0];
                        if (file && (file.type.includes('spreadsheet') || file.name.endsWith('.xlsx') || file.name.endsWith('.csv'))) {
                            this.files[id] = file.name;
                            const input = document.getElementById('file-' + id);
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            input.files = dataTransfer.files;
                        }
                        this.dragging = null;
                    },
                    handleSelect(e, id) {
                        const file = e.target.files[0];
                        if (file) {
                            this.files[id] = file.name;
                        }
                    }
                }">
                    <form action="{{ route('admin.kelas.bulk-import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                Daftar Kelas Kosong 
                                <span class="bg-slate-100 text-slate-600 text-xs px-2.5 py-0.5 rounded-full font-semibold">{{ count($kelasKosong) }}</span>
                            </h3>
                            <button type="submit" 
                                    x-show="Object.keys(files).length > 0"
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-xs font-semibold transition shadow-sm shadow-emerald-200 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                Simpan Semua (<span x-text="Object.keys(files).length"></span>)
                            </button>
                        </div>

                        <div class="border border-slate-200 rounded-2xl overflow-x-auto bg-white shadow-sm">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-slate-50 text-slate-500 text-[11px] uppercase font-bold tracking-wider border-b border-slate-100">
                                    <tr>
                                        <th class="px-4 py-3">Nama Kelas</th>
                                        <th class="px-4 py-3">File Siswa (Drag & Drop)</th>
                                        <th class="px-4 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($kelasKosong as $kk)
                                        <tr class="hover:bg-slate-50/50 transition group"
                                            :class="dragging === '{{ $kk->id }}' ? 'bg-indigo-50 ring-2 ring-inset ring-indigo-400' : ''"
                                            @dragover.prevent="dragging = '{{ $kk->id }}'"
                                            @dragleave.prevent="dragging = null"
                                            @drop.prevent="handleDrop($event, '{{ $kk->id }}')">
                                            
                                            <td class="px-4 py-3 font-semibold text-slate-700">
                                                {{ $kk->nama_kelas }}
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                <div class="relative">
                                                    <input type="file" name="imports[{{ $kk->id }}]" 
                                                           id="file-{{ $kk->id }}" 
                                                           class="hidden" 
                                                           @change="handleSelect($event, '{{ $kk->id }}')"
                                                           accept=".xlsx,.xls,.csv">
                                                    
                                                    <template x-if="files['{{ $kk->id }}']">
                                                        <div class="flex items-center gap-2 text-indigo-700 bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                            <span class="text-xs font-semibold truncate max-w-[150px]" x-text="files['{{ $kk->id }}']"></span>
                                                            <button type="button" @click="delete files['{{ $kk->id }}']; document.getElementById('file-{{ $kk->id }}').value = ''" class="ml-auto text-indigo-400 hover:text-red-500">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                            </button>
                                                        </div>
                                                    </template>
                                                    
                                                    <template x-if="!files['{{ $kk->id }}']">
                                                        <label for="file-{{ $kk->id }}" class="cursor-pointer text-[11px] font-semibold text-slate-400 border border-dashed border-slate-300 px-3 py-2 rounded-xl hover:border-indigo-400 hover:text-indigo-600 hover:bg-indigo-50 transition block text-center">
                                                            Pilih / Drop File
                                                        </label>
                                                    </template>
                                                </div>
                                            </td>

                                            <td class="px-4 py-3">
                                                <div class="flex justify-center">
                                                    @if($kk->history_siswa_count == 0)
                                                        <button type="button" 
                                                                @click="$dispatch('confirm-dialog', { title: 'Hapus Kelas?', message: 'Hapus kelas {{ addslashes($kk->nama_kelas) }} secara permanen?', confirmText: 'Ya, Hapus', type: 'danger', formId: 'delete-kelas-{{ $kk->id }}' })"
                                                                class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" 
                                                                title="Hapus Kelas">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    @else
                                                        <span class="p-2 text-slate-300 cursor-not-allowed" title="Ada data historis">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-8 text-center text-slate-400 text-sm">
                                                Semua kelas telah memiliki siswa.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>

                    {{-- Hidden Delete Forms --}}
                    @foreach($kelasKosong as $kk)
                        @if($kk->history_siswa_count == 0)
                            <form id="delete-kelas-{{ $kk->id }}" action="{{ route('admin.kelas.destroy', $kk->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    @endforeach

                    <div class="mt-4 flex flex-col sm:flex-row justify-between items-center gap-2 px-2">
                        <p class="text-xs text-slate-400">Tarik file Excel ke baris kelas untuk menyiapkan.</p>
                        <a href="{{ route('admin.kelas.template') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download Template Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-modal>

    <!-- MODAL EDIT KELAS -->
    <x-modal name="edit-kelas" :show="old('_method') === 'PUT' && $errors->any()" focusable>
        <div class="p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-1">Edit Nama Kelas</h2>
            <p class="text-sm text-slate-400 mb-6">Ubah nama kelas yang sudah ada.</p>
            
            <form :action="editActionUrl" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="edit_url" :value="editActionUrl">
                <input type="hidden" name="id" :value="editData.id">
                
                <div>
                    <label class="text-xs font-semibold text-slate-600 block mb-1.5">Nama Kelas</label>
                    <input type="text" name="nama_kelas" :value="editData.nama_kelas"
                           class="w-full border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('nama_kelas') border-red-500 @enderror">
                    @error('nama_kelas')
                        <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" x-on:click="$dispatch('close')"
                       class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-xs font-semibold transition">
                        Batal
                    </button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- MODAL KELOLA SIKLUS & HISTORI --}}
    <x-modal name="kelola-siklus" maxWidth="2xl" :show="session('error_siklus') || $errors->has('nama_tahun_baru')">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Siklus & Histori Tahun</h2>
                    <p class="text-sm text-slate-400 mt-1">Kelola arsip tahunan dan transisi tahun ajaran baru.</p>
                </div>
                <button type="button" x-on:click="$dispatch('close')" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- BAGIAN 1: MULAI TAHUN BARU -->
            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 mb-8 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Mulai Tahun Ajaran Baru
                </h3>
                @php
                    $resetData = [
                        "nama" => old("nama_tahun_baru", ""),
                        "mulai" => old("tanggal_mulai", ""),
                        "selesai" => old("tanggal_selesai", ""),
                        "existingNames" => $tahunAjarans->pluck("nama_tahun"),
                        "ranges" => $tahunAjarans->map(function($t) {
                            return [
                                "start" => $t->tanggal_mulai ? $t->tanggal_mulai->format("Y-m-d") : null,
                                "end" => $t->tanggal_selesai ? $t->tanggal_selesai->format("Y-m-d") : null,
                            ];
                        })->filter(function($r) {
                            return $r["start"] && $r["end"];
                        })->values()
                    ];
                @endphp
                <form id="form-reset-tahun" action="{{ route('admin.kelas.reset') }}" method="POST" x-data='@json($resetData)'>
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Tahun Ajaran Baru</label>
                            <input type="text" name="nama_tahun_baru" x-model="nama" placeholder="Contoh: 2026/2027" required
                                   class="w-full border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            @error('nama_tahun_baru') <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" x-model="mulai" required
                                       class="w-full border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" x-model="selesai" required
                                       class="w-full border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="button" 
                                    @click='
                                        if(!nama || !mulai || !selesai) {
                                            $dispatch("confirm-dialog", { title: "Data Belum Lengkap", message: "Silakan isi semua kolom.", confirmText: "OK", type: "warning" });
                                            return;
                                        }
                                        if(existingNames.includes(nama)) {
                                            $dispatch("confirm-dialog", { title: "Nama Sudah Ada", message: "Tahun ajaran " + nama + " sudah terdaftar.", confirmText: "OK", type: "warning" });
                                            return;
                                        }
                                        
                                        // Pengecekan Overlap (Tabrakan Tanggal)
                                        const overlap = ranges.find(r => (mulai <= r.end && selesai >= r.start));
                                        if(overlap) {
                                            $dispatch("confirm-dialog", { 
                                                title: "Jadwal Bentrok", 
                                                message: "Rentang tanggal ini bertabrakan dengan periode yang sudah ada (" + overlap.start + " s/d " + overlap.end + "). Harap gunakan tanggal di luar rentang tersebut.", 
                                                confirmText: "Atur Ulang", 
                                                type: "warning"
                                            });
                                            return;
                                        }

                                        if(new Date(selesai) <= new Date(mulai)) {
                                            $dispatch("confirm-dialog", { title: "Rentang Tanggal Salah", message: "Tanggal Selesai harus setelah Tanggal Mulai.", confirmText: "OK", type: "warning" });
                                            return;
                                        }
                                        $dispatch("confirm-dialog", { 
                                            title: "Mulai Tahun Ajaran Baru?", 
                                            message: "TINDAKAN KRITIKAL: Memulai tahun baru akan mengosongkan semua kelas dan melepas semua wali kelas. Pastikan data tahun ini sudah benar. Lanjutkan?", 
                                            confirmText: "Mulai Sekarang", 
                                            type: "danger", 
                                            formId: "form-reset-tahun" 
                                        })
                                    '
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Mulai Tahun Ajaran Baru
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- BAGIAN 2: HISTORI TAHUN AJARAN -->
            <div>
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    Histori & Arsip Data
                </h3>
                <div class="overflow-x-auto border border-slate-200 rounded-2xl shadow-sm">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">Tahun Ajaran</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($tahunAjarans as $ta)
                                <tr class="{{ $ta->status === 'aktif' ? 'bg-indigo-50/30' : 'hover:bg-slate-50/50 transition' }}">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="font-semibold text-slate-700">{{ $ta->nama_tahun }}</div>
                                        @if($ta->tanggal_mulai && $ta->tanggal_selesai)
                                            <div class="text-[11px] text-slate-500 mt-0.5">
                                                {{ $ta->tanggal_mulai->format('d/m/Y') }} - {{ $ta->tanggal_selesai->format('d/m/Y') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if($ta->status === 'aktif')
                                            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg uppercase tracking-wider border border-emerald-100">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Aktif
                                            </span>
                                        @else
                                            <div class="flex flex-col gap-1 items-start">
                                                <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg uppercase tracking-wider border border-slate-200">
                                                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> Non-Aktif
                                                </span>
                                                @if($ta->path_arsip)
                                                    <span class="inline-flex items-center gap-1 text-[9px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded uppercase border border-blue-100 mt-1">
                                                        ❄️ Statis
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex justify-center items-center gap-1 text-xs">
                                            <a href="{{ route('admin.tahun-ajaran.archive', $ta->id) }}" 
                                               class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Unduh Arsip">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            </a>
                                            @if($ta->status === 'aktif')
                                                <button type="button" 
                                                        @click.prevent="$dispatch('open-modal', 'edit-tahun-{{ $ta->id }}')"
                                                        class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit Periode Aktif">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                </button>
                                            @endif
                                            @if($ta->status !== 'aktif')
                                                @if(!$ta->path_arsip)
                                                    <form id="form-freeze-{{ $ta->id }}" action="{{ route('admin.tahun-ajaran.freeze', $ta->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="button"
                                                                @click="$dispatch('confirm-dialog', { 
                                                                    title: 'Bekukan Arsip?', 
                                                                    message: 'Membekukan arsip tahun {{ $ta->nama_tahun }} akan menyimpan seluruh data kehadiran siswa secara permanen dalam bentuk Excel. Tindakan ini disarankan sebelum Anda melakukan pembersihan siswa lulus. Lanjutkan?', 
                                                                    confirmText: 'Ya, Bekukan', 
                                                                    type: 'warning', 
                                                                    formId: 'form-freeze-{{ $ta->id }}' 
                                                                })"
                                                                class="p-2 text-sky-600 hover:bg-sky-50 rounded-lg transition" title="Bekukan Arsip Statis">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="p-2 text-emerald-600 cursor-default" title="Arsip Statis Terkunci">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                    </span>
                                                @endif
                                                
                                                <form id="form-hapus-histori-{{ $ta->id }}" action="{{ route('admin.tahun-ajaran.destroy', $ta->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                            @click="$dispatch('confirm-dialog', { 
                                                                title: 'Hapus Tahun Ajaran?', 
                                                                message: 'PERINGATAN KRITIKAL: Menghapus data tahun {{ $ta->nama_tahun }} akan menghapus permanen semua histori absensi dan penempatan siswa. Lanjutkan?', 
                                                                confirmText: 'Hapus Permanen', 
                                                                type: 'danger', 
                                                                formId: 'form-hapus-histori-{{ $ta->id }}' 
                                                            })"
                                                            class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus Permanen">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @foreach($tahunAjarans as $ta)
                <!-- MODAL EDIT TAHUN (Khusus untuk Active di sini) -->
                @if($ta->status === 'aktif')
                    <x-modal name="edit-tahun-{{ $ta->id }}" :show="false" focusable>
                        <div class="p-6 text-left">
                            <h2 class="text-lg font-bold text-slate-800 mb-1">Edit Periode Aktif</h2>
                            <p class="text-sm text-slate-400 mb-6">Sesuaikan nama dan tanggal periode tahun ajaran aktif.</p>
                            
                            <form id="form-edit-tahun-{{ $ta->id }}" action="{{ route('admin.tahun-ajaran.update', $ta->id) }}" method="POST" class="space-y-4" 
                                  x-data='{
                                      data: {
                                          existingNames: @json($tahunAjarans->reject(fn($t) => $t->id == $ta->id)->pluck("nama_tahun")->values()),
                                          ranges: @json($tahunAjarans->reject(fn($t) => $t->id == $ta->id)->map(fn($t) => ["start" => $t->tanggal_mulai?->format("Y-m-d"), "end" => $t->tanggal_selesai?->format("Y-m-d")])->filter(fn($r) => $r["start"] && $r["end"])->values())
                                      },
                                      errorNama: "",
                                      errorTanggal: ""
                                  }'>
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Tahun Ajaran</label>
                                    <input type="text" name="nama_tahun" value="{{ $ta->nama_tahun }}" required
                                           class="w-full border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition"
                                           :class="errorNama ? 'border-red-500 ring-1 ring-red-500 bg-red-50' : ''"
                                           @input="errorNama = ''">
                                    <p x-show="errorNama" x-transition x-text="errorNama" class="text-xs text-red-600 mt-1.5 font-semibold" style="display: none;"></p>
                                </div>

                                <div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Mulai</label>
                                            <input type="date" name="tanggal_mulai" value="{{ $ta->tanggal_mulai ? $ta->tanggal_mulai->format('Y-m-d') : '' }}" required
                                                   class="w-full border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition"
                                                   :class="errorTanggal ? 'border-red-500 ring-1 ring-red-500 bg-red-50' : ''"
                                                   @input="errorTanggal = ''">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Selesai</label>
                                            <input type="date" name="tanggal_selesai" value="{{ $ta->tanggal_selesai ? $ta->tanggal_selesai->format('Y-m-d') : '' }}" required
                                                   class="w-full border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition"
                                                   :class="errorTanggal ? 'border-red-500 ring-1 ring-red-500 bg-red-50' : ''"
                                                   @input="errorTanggal = ''">
                                        </div>
                                    </div>
                                    <p x-show="errorTanggal" x-transition x-text="errorTanggal" class="text-xs text-red-600 mt-1.5 font-semibold" style="display: none;"></p>
                                </div>

                                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                                    <button type="button" x-on:click="$dispatch('close')"
                                       class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-xs font-semibold transition">
                                        Batal
                                    </button>
                                    <button type="button" 
                                            @click='
                                                errorNama = "";
                                                errorTanggal = "";
                                                const form = document.getElementById("form-edit-tahun-{{ $ta->id }}");
                                                const nama = form.querySelector("[name=nama_tahun]").value;
                                                const start = form.querySelector("[name=tanggal_mulai]").value;
                                                const end = form.querySelector("[name=tanggal_selesai]").value;
                                                
                                                let hasError = false;
                                                
                                                if (!nama) {
                                                    errorNama = "Nama tahun ajaran wajib diisi.";
                                                    hasError = true;
                                                } else if (data.existingNames.includes(nama)) {
                                                    errorNama = "Tahun ajaran " + nama + " sudah terdaftar.";
                                                    hasError = true;
                                                }
                                                
                                                if (!start || !end) {
                                                    errorTanggal = "Tanggal mulai dan selesai wajib diisi.";
                                                    hasError = true;
                                                } else if(new Date(end) <= new Date(start)) {
                                                    errorTanggal = "Tanggal Selesai harus setelah Tanggal Mulai.";
                                                    hasError = true;
                                                } else {
                                                    const overlap = data.ranges.find(r => (start <= r.end && end >= r.start));
                                                    if(overlap) {
                                                        errorTanggal = "Jadwal bentrok dengan periode " + overlap.start + " s/d " + overlap.end;
                                                        hasError = true;
                                                    }
                                                }
                                                
                                                if(!hasError) {
                                                    form.submit();
                                                }
                                            '
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </x-modal>
                @endif
            @endforeach
        </div>
    </x-modal>
</div>
</x-app-layout>
