<x-app-layout>
    <x-slot name="header">Ganti Password</x-slot>
    <div class="p-6 lg:p-8 max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Ubah Password</h1>
            <p class="text-sm text-slate-400 mt-1">Pastikan akun Anda menggunakan password panjang dan acak agar tetap aman.</p>
        </div>


        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden p-6">
            <form method="post" action="{{ route('guru.password.update') }}" class="space-y-6">
                @csrf
                @method('put')

                <div>
                    <label for="update_password_current_password" class="block text-sm font-medium text-slate-700">Password Saat Ini</label>
                    <input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm" autocomplete="current-password" />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>

                <div>
                    <label for="update_password_password" class="block text-sm font-medium text-slate-700">Password Baru</label>
                    <input id="update_password_password" name="password" type="password" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>

                <div>
                    <label for="update_password_password_confirmation" class="block text-sm font-medium text-slate-700">Konfirmasi Password Baru</label>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm shadow-indigo-200">
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

