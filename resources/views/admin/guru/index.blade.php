<x-app-layout>
    <x-slot name="header">Kelola Data Guru</x-slot>
<div x-data="{ 
    editActionUrl: '{{ old('edit_url') }}', 
    editData: { 
        id: '{{ old('id') }}', 
        name: '{{ old('name') }}', 
        email: '{{ old('email') }}'
    },
    openEdit(data, url) {
        this.editData = data;
        this.editActionUrl = url;
        $dispatch('open-modal', 'edit-guru');
    }
}">
    <div class="p-4 md:p-6 lg:p-8">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-slate-800">Data Guru</h1>
                    @if($tahunAktifGlobal)
                            <span class="bg-indigo-50 border border-indigo-100 text-indigo-600 px-2.5 py-1 rounded-lg text-xs font-semibold">
                                {{ $tahunAktifGlobal->nama_tahun }}
                            </span>
                    @endif
                </div>
                <p class="text-sm text-slate-400 mt-1">Kelola data guru pengajar di sekolah.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.guru.export') }}"
                   class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-xs font-semibold hover:bg-slate-50 hover:border-slate-300 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    Ekspor
                </a>

                <button x-on:click.prevent="$dispatch('open-modal', 'import-guru')"
                        class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-xs font-semibold hover:bg-slate-50 hover:border-slate-300 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                    Impor
                </button>

                <button x-on:click.prevent="$dispatch('open-modal', 'tambah-guru')"
                   class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm shadow-indigo-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Tambah Guru
                </button>
            </div>
        </div>

        <!-- SEARCH -->
        <form action="{{ route('admin.guru.index') }}" method="GET" class="bg-white rounded-2xl border border-slate-200/60 shadow-sm mb-6">
            <div class="flex items-center gap-3 p-3">
                <div class="flex-1 relative">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari berdasarkan nama atau email..." 
                           class="w-full pl-10 pr-4 py-2.5 border-0 bg-slate-50 rounded-xl text-sm text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition">
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-xs font-semibold hover:bg-indigo-700 transition shadow-sm">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.guru.index') }}" class="bg-slate-100 text-slate-500 px-4 py-2.5 rounded-xl text-xs font-semibold hover:bg-slate-200 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- NOTIFICATIONS -->

        @if(session('import_failures'))
            <div x-data="{ show: true }" x-show="show" class="bg-amber-50 border border-amber-200 text-amber-700 p-4 rounded-xl mb-6 relative">
                <button @click="show = false" class="absolute top-4 right-4 text-amber-500 hover:text-amber-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <p class="font-semibold text-sm pr-6">Beberapa data dilewati:</p>
                <ul class="list-disc ml-5 mt-1 text-xs space-y-0.5">
                    @foreach(session('import_failures') as $failure)
                        <li>{{ $failure }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- DATA LIST -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Email / Login</th>
                            <th class="px-6 py-4 text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                    @forelse($guru as $g)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 text-xs font-bold">
                                        {{ strtoupper(substr($g->name, 0, 2)) }}
                                    </div>
                                    <span class="font-semibold text-sm text-slate-700">{{ $g->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-mono text-xs">{{ $g->email }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-1">
                                    <button @click="openEdit({ id: '{{ $g->id }}', name: '{{ addslashes($g->name) }}', email: '{{ $g->email }}' }, '{{ route('admin.guru.update', $g->id) }}')"
                                       class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </button>
                                    <button @click="$dispatch('confirm-dialog', { title: 'Hapus Guru?', message: 'Hapus akun {{ addslashes($g->name) }}?', confirmText: 'Hapus', type: 'danger', formId: 'delete-guru-{{ $g->id }}' })"
                                       class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    <form id="delete-guru-{{ $g->id }}" action="{{ route('admin.guru.destroy', $g->id) }}" method="POST">@csrf @method('DELETE')</form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-12 text-center text-slate-400">Belum ada data guru.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden divide-y divide-slate-100">
                @forelse($guru as $g)
                    <div class="p-4 hover:bg-slate-50 transition active:bg-slate-100">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 text-xs font-bold shrink-0">
                                {{ strtoupper(substr($g->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-slate-800 text-sm truncate">{{ $g->name }}</h4>
                                <p class="text-[11px] text-slate-400 font-mono truncate">{{ $g->email }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button @click="openEdit({ id: '{{ $g->id }}', name: '{{ addslashes($g->name) }}', email: '{{ $g->email }}' }, '{{ route('admin.guru.update', $g->id) }}')"
                               class="flex-1 bg-slate-50 text-slate-600 py-2 rounded-lg text-xs font-semibold border border-slate-200 flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                Edit
                            </button>
                            <button @click="$dispatch('confirm-dialog', { title: 'Hapus Guru?', message: 'Hapus akun {{ addslashes($g->name) }}?', confirmText: 'Hapus', type: 'danger', formId: 'delete-guru-{{ $g->id }}' })"
                               class="flex-1 bg-white text-red-500 py-2 rounded-lg text-xs font-semibold border border-red-100 flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-sm italic">Belum ada data guru.</div>
                @endforelse
                </tbody>
            </table>
            </div>
        </div>

    </div>

    <!-- MODAL IMPOR -->
    <x-modal name="import-guru" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-1">Impor Data Guru</h2>
            <p class="text-sm text-slate-400 mb-1">Pilih file Excel (.xlsx, .xls) atau CSV.</p>
            <p class="text-[10px] text-amber-600 font-bold uppercase tracking-wider mb-6 bg-amber-50 px-3 py-1 rounded-lg inline-block border border-amber-100">
                Format: nama, email (Password otomatis: guru12345)
            </p>

            <form action="{{ route('admin.guru.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-indigo-300 transition">
                    <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                    <input type="file" name="file" required
                           class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 cursor-pointer">
                </div>

                <div class="flex justify-between items-center pt-2">
                    <a href="{{ route('admin.guru.template') }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">
                        Download Template (Excel)
                    </a>
                    <div class="flex gap-2">
                        <button type="button" x-on:click="$dispatch('close')"
                                class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-xs font-semibold transition">Batal</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm">Upload</button>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- MODAL TAMBAH -->
    <x-modal name="tambah-guru" :show="old('_method') !== 'PUT' && $errors->any()" focusable>
        <div class="p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-1">Tambah Guru Baru</h2>
            <p class="text-sm text-slate-400 mb-6">Masukkan informasi guru pengajar.</p>
            
            <form action="{{ route('admin.guru.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama guru"
                           class="w-full border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@sekolah.sch.id"
                           class="w-full border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('email') border-red-400 @enderror">
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Password</label>
                    <input type="text" name="password" value="guru12345" placeholder="Minimal 8 karakter"
                           class="w-full border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('password') border-red-400 @enderror">
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium italic">Default: guru12345 (bisa diganti)</p>
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" x-on:click="$dispatch('close')"
                       class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-xs font-semibold transition">
                        Batal
                    </button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- MODAL EDIT -->
    <x-modal name="edit-guru" :show="old('_method') === 'PUT' && $errors->any()" focusable>
        <div class="p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-1">Edit Data Guru</h2>
            <p class="text-sm text-slate-400 mb-6">Perbarui informasi guru.</p>
            
            <form :action="editActionUrl" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="edit_url" :value="editActionUrl">
                <input type="hidden" name="id" :value="editData.id">
                
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" :value="editData.name" required
                           class="w-full border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email</label>
                    <input type="email" name="email" :value="editData.email" required
                           class="w-full border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('email') border-red-400 @enderror">
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="reset_password" value="1"
                               class="w-4 h-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                        <span class="ml-3 text-sm font-medium text-amber-800">
                            Reset Password ke default (guru12345)
                        </span>
                    </label>
                    <p class="text-xs text-amber-600 mt-1.5 ml-7">Centang jika guru lupa password.</p>
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
</div>
</x-app-layout>
