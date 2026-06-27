<x-app-layout>
    <x-slot name="header">Ganti Password</x-slot>
    <div class="p-6 lg:p-8 max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Ubah Password</h1>
            <p class="text-sm text-slate-400 mt-1">Pastikan akun Anda menggunakan password panjang dan acak agar tetap aman.</p>
        </div>


        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6">
            <form method="post" action="{{ route('kepsek.password.update') }}" class="space-y-6"
                  x-data="{
                      showPwd1: false, hasVal1: false,
                      showPwd2: false, hasVal2: false,
                      showPwd3: false, hasVal3: false,
                      loading: false,
                      success: '{{ session('success') }}',
                      errors: {
                          current_password: @js($errors->updatePassword->get('current_password')),
                          password: @js($errors->updatePassword->get('password')),
                          password_confirmation: @js($errors->updatePassword->get('password_confirmation')),
                      },
                  }"
                  @submit.prevent="
                      loading = true;
                      success = '';
                      errors = { current_password: [], password: [], password_confirmation: [] };
                      let formData = new FormData($el);
                      fetch($el.action, {
                          method: 'POST',
                          headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                          body: formData,
                      })
                      .then(async r => {
                          if (r.status === 422) {
                              const data = await r.json();
                              const errs = data.errors || {};
                              errors.current_password = errs.current_password || [];
                              errors.password = errs.password || [];
                              errors.password_confirmation = errs.password_confirmation || [];
                              loading = false;
                          } else if (r.ok) {
                              success = 'Password berhasil diubah!';
                              $refs.pwd1.value = '';
                              $refs.pwd2.value = '';
                              $refs.pwd3.value = '';
                              hasVal1 = false; hasVal2 = false; hasVal3 = false;
                              showPwd1 = false; showPwd2 = false; showPwd3 = false;
                              loading = false;
                          } else {
                              errors.current_password = ['Terjadi kesalahan, silakan coba lagi.'];
                              loading = false;
                          }
                      })
                      .catch(() => { errors.current_password = ['Terjadi kesalahan jaringan.']; loading = false; });
                  ">
                @csrf
                @method('put')

                <!-- Success Message -->
                <div x-show="success" x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span x-text="success"></span>
                </div>

                <div>
                    <label for="update_password_current_password" class="block text-sm font-medium text-slate-700">Password Saat Ini</label>
                    <div class="relative mt-1">
                        <input id="update_password_current_password" name="current_password" x-bind:type="showPwd1 ? 'text' : 'password'" class="block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm pr-12" :class="errors.current_password && errors.current_password.length ? 'border-red-400 ring-2 ring-red-100' : ''" autocomplete="current-password" x-ref="pwd1" @input="hasVal1 = $refs.pwd1.value.length > 0; errors.current_password = []" />
                        <button type="button" x-cloak x-show="hasVal1" @click="showPwd1 = !showPwd1" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-indigo-600 transition-colors focus:outline-none">
                            <svg x-show="!showPwd1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg x-show="showPwd1" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                    <template x-if="errors.current_password && errors.current_password.length">
                        <p class="text-xs text-red-500 mt-2 font-medium" x-text="errors.current_password[0]"></p>
                    </template>
                </div>

                <div>
                    <label for="update_password_password" class="block text-sm font-medium text-slate-700">Password Baru</label>
                    <div class="relative mt-1">
                        <input id="update_password_password" name="password" x-bind:type="showPwd2 ? 'text' : 'password'" class="block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm pr-12" :class="errors.password && errors.password.length ? 'border-red-400 ring-2 ring-red-100' : ''" autocomplete="new-password" x-ref="pwd2" @input="hasVal2 = $refs.pwd2.value.length > 0; errors.password = []" />
                        <button type="button" x-cloak x-show="hasVal2" @click="showPwd2 = !showPwd2" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-indigo-600 transition-colors focus:outline-none">
                            <svg x-show="!showPwd2" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg x-show="showPwd2" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                    <template x-if="errors.password && errors.password.length">
                        <p class="text-xs text-red-500 mt-2 font-medium" x-text="errors.password[0]"></p>
                    </template>
                </div>

                <div>
                    <label for="update_password_password_confirmation" class="block text-sm font-medium text-slate-700">Konfirmasi Password Baru</label>
                    <div class="relative mt-1">
                        <input id="update_password_password_confirmation" name="password_confirmation" x-bind:type="showPwd3 ? 'text' : 'password'" class="block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm pr-12" :class="errors.password_confirmation && errors.password_confirmation.length ? 'border-red-400 ring-2 ring-red-100' : ''" autocomplete="new-password" x-ref="pwd3" @input="hasVal3 = $refs.pwd3.value.length > 0; errors.password_confirmation = []" />
                        <button type="button" x-cloak x-show="hasVal3" @click="showPwd3 = !showPwd3" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-indigo-600 transition-colors focus:outline-none">
                            <svg x-show="!showPwd3" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg x-show="showPwd3" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                    <template x-if="errors.password_confirmation && errors.password_confirmation.length">
                        <p class="text-xs text-red-500 mt-2 font-medium" x-text="errors.password_confirmation[0]"></p>
                    </template>
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                    <button type="submit" :disabled="loading" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm shadow-indigo-200 disabled:opacity-60 disabled:cursor-not-allowed">
                        <span x-show="!loading">Simpan Password</span>
                        <span x-show="loading" x-cloak class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
