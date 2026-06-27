<x-app-layout>
<div x-data="{ 
    editActionUrl: '{{ old('edit_url') }}', 
    editData: { id: '{{ old('id') }}', name: '{{ old('name') }}', email: '{{ old('email') }}' },
    openEdit(data, url) { this.editData = data; this.editActionUrl = url; $dispatch('open-modal', 'edit-admin'); }
}">
    <div class="p-4 md:p-6 lg:p-8">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Manajemen Admin, Kepsek & Wakepsek</h1>
                <p class="text-sm text-slate-400 mt-1">Kelola akun dengan akses Administrator, Kepala Sekolah, dan Wakil Kepala Sekolah.</p>
            </div>
            <button x-on:click.prevent="$dispatch('open-modal', 'tambah-admin')" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm shadow-indigo-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Tambah Akun
            </button>
        </div>

        <!-- NOTIFICATIONS -->

        <!-- TABLE -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm min-w-[500px]">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Administrator</th>
                            <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Email / Login</th>
                            <th class="px-6 py-4 text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                    @forelse($admins as $admin)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold text-sm text-slate-700">{{ $admin->name }}</span>
                                        <span class="ml-2 text-[9px] bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded font-bold uppercase">{{ $admin->role === 'kepala_sekolah' ? 'Kepala Sekolah' : ($admin->role === 'wakil_kepala_sekolah' ? 'Wakil Kepala Sekolah' : 'Admin') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $admin->email }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-1">
                                    <button @click="openEdit({ id: '{{ $admin->id }}', name: '{{ addslashes($admin->name) }}', email: '{{ $admin->email }}' }, '{{ route('admin.admins.update', $admin->id) }}')" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </button>
                                    @php
                                        $canDelete = ($admin->role === 'admin' && $admins->where('role', 'admin')->count() > 1) || 
                                                     ($admin->role === 'kepala_sekolah' && $admins->where('role', 'kepala_sekolah')->count() > 1) ||
                                                     ($admin->role === 'wakil_kepala_sekolah' && $admins->where('role', 'wakil_kepala_sekolah')->count() > 1);
                                    @endphp
                                    @if($canDelete)
                                        <form id="delete-admin-{{ $admin->id }}" action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST">@csrf @method('DELETE')</form>
                                        <button @click="$dispatch('confirm-dialog', { title: 'Hapus Akun?', message: 'Akses masuk {{ addslashes($admin->name) }} akan dicabut secara permanen.', confirmText: 'Ya, Hapus', type: 'danger', formId: 'delete-admin-{{ $admin->id }}' })" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    @else
                                        <span class="p-2 text-slate-300 cursor-not-allowed" title="Akun terakhir untuk role ini tidak bisa dihapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-12 text-center"><p class="text-sm text-slate-400">Tidak ada data admin.</p></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden divide-y divide-slate-100">
                @forelse($admins as $admin)
                    <div class="p-4 hover:bg-slate-50 transition active:bg-slate-100">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center text-white text-xs font-bold shadow-sm shrink-0">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-slate-800 text-sm truncate">{{ $admin->name }}</h4>
                                    <span class="text-[9px] bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded font-bold uppercase shrink-0">{{ $admin->role === 'kepala_sekolah' ? 'Kepala Sekolah' : ($admin->role === 'wakil_kepala_sekolah' ? 'Wakil Kepala Sekolah' : 'Admin') }}</span>
                                </div>
                                <p class="text-[11px] text-slate-400 font-mono truncate mt-0.5">{{ $admin->email }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button @click="openEdit({ id: '{{ $admin->id }}', name: '{{ addslashes($admin->name) }}', email: '{{ $admin->email }}' }, '{{ route('admin.admins.update', $admin->id) }}')"
                               class="flex-1 bg-slate-50 text-slate-600 py-2 rounded-lg text-xs font-semibold border border-slate-200 flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                Edit
                            </button>
                            @php
                                $canDeleteMobile = ($admin->role === 'admin' && $admins->where('role', 'admin')->count() > 1) || 
                                             ($admin->role === 'kepala_sekolah' && $admins->where('role', 'kepala_sekolah')->count() > 1) ||
                                             ($admin->role === 'wakil_kepala_sekolah' && $admins->where('role', 'wakil_kepala_sekolah')->count() > 1);
                            @endphp
                            @if($canDeleteMobile)
                                <button @click="$dispatch('confirm-dialog', { title: 'Hapus Akun?', message: 'Akses masuk {{ addslashes($admin->name) }} akan dicabut secara permanen.', confirmText: 'Ya, Hapus', type: 'danger', formId: 'delete-admin-{{ $admin->id }}' })"
                                   class="flex-1 bg-white text-red-500 py-2 rounded-lg text-xs font-semibold border border-red-100 flex items-center justify-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            @else
                                <span class="flex-1 bg-slate-50 text-slate-300 py-2 rounded-lg text-xs font-semibold border border-slate-200 flex items-center justify-center gap-1.5 cursor-not-allowed">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                    Hapus
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-sm italic">Tidak ada data admin.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH -->
    <x-modal name="tambah-admin" :show="old('_method') !== 'PUT' && $errors->any()" focusable>
        <div class="p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-1">Tambah Akun Baru</h2>
            <p class="text-sm text-slate-400 mb-6">Buat akun administrator, kepala sekolah, atau wakil kepala sekolah baru.</p>
            <form action="{{ route('admin.admins.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama pengguna" class="w-full border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Role</label>
                    <select name="role" class="w-full border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('role') border-red-400 @enderror">
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        @if(!$hasKepsek)
                            <option value="kepala_sekolah" {{ old('role') === 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                        @endif
                        @if(!$hasWakepsek)
                            <option value="wakil_kepala_sekolah" {{ old('role') === 'wakil_kepala_sekolah' ? 'selected' : '' }}>Wakil Kepala Sekolah</option>
                        @endif
                    </select>
                    @error('role') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email Login</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@sekolah.sch.id" class="w-full border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('email') border-red-400 @enderror">
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Password</label>
                    <input type="password" name="password" placeholder="Minimal 8 karakter" class="w-full border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('password') border-red-400 @enderror">
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" x-on:click="$dispatch('close')" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-xs font-semibold transition">Batal</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- MODAL EDIT -->
    <x-modal name="edit-admin" :show="old('_method') === 'PUT' && $errors->any()" focusable>
        <div class="p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-1">Edit Data Akun</h2>
            <p class="text-sm text-slate-400 mb-6">Perbarui informasi akun.</p>
            <form :action="editActionUrl" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <input type="hidden" name="edit_url" :value="editActionUrl">
                <input type="hidden" name="id" :value="editData.id">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama</label>
                    <input type="text" name="name" :value="editData.name" required class="w-full border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email Login</label>
                    <input type="email" name="email" :value="editData.email" required class="w-full border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('email') border-red-400 @enderror">
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4">
                    <label class="block text-xs font-semibold text-indigo-700 mb-1.5">Ganti Password (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full border border-indigo-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 transition">
                    <p class="text-[11px] text-indigo-500 mt-1.5">Minimal 8 karakter jika ingin mengganti.</p>
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

