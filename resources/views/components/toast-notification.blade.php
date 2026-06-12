<div x-data="{
        show: false,
        message: '',
        type: 'success', // 'success' or 'error'
        timeout: null,
        
        init() {
            // Check session on load
            @if(session('success'))
                this.notify('{{ session('success') }}', 'success');
            @elseif(session('error'))
                this.notify('{{ session('error') }}', 'error');
            @elseif(session('warning'))
                this.notify('{{ session('warning') }}', 'warning');
            @endif
        },

        notify(msg, msgType = 'success') {
            this.message = msg;
            this.type = msgType;
            this.show = true;

            if (this.timeout) {
                clearTimeout(this.timeout);
            }

            this.timeout = setTimeout(() => {
                this.show = false;
            }, 4000);
        }
     }"
     @notify.window="notify($event.detail.message, $event.detail.type)"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-10"
     x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0 scale-95"
     class="fixed bottom-4 right-4 left-4 md:left-auto md:bottom-6 md:right-6 z-50 md:max-w-sm bg-white shadow-2xl rounded-2xl border border-slate-200/60 overflow-hidden"
     style="display: none;"
     x-cloak>
    
    <div class="p-4 flex items-start gap-4">
        <!-- Icon -->
        <div class="shrink-0 pt-0.5">
            <!-- Success Icon -->
            <template x-if="type === 'success'">
                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </template>
            <!-- Error Icon -->
            <template x-if="type === 'error'">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </template>
            <!-- Warning Icon -->
            <template x-if="type === 'warning'">
                <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </template>
        </div>
        
        <!-- Content -->
        <div class="flex-1 min-w-0">
            <h3 class="text-sm font-bold text-slate-800" x-text="type === 'success' ? 'Berhasil!' : (type === 'warning' ? 'Perhatian' : 'Terjadi Kesalahan')"></h3>
            <p class="text-sm text-slate-500 mt-0.5 leading-relaxed" x-text="message"></p>
        </div>
        
        <!-- Close Button -->
        <div class="shrink-0 flex">
            <button @click="show = false" class="text-slate-400 hover:text-slate-600 focus:outline-none p-1 rounded-lg hover:bg-slate-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <!-- Progress Bar (Optional Visual Touch) -->
    <div class="h-1 w-full bg-slate-100">
        <div class="h-full transition-all duration-[4000ms] ease-linear"
             :class="type === 'success' ? 'bg-emerald-500' : (type === 'warning' ? 'bg-yellow-500' : 'bg-red-500')"
             :style="show ? 'width: 0%' : 'width: 100%'"
             x-effect="if(show) { $el.style.transition = 'none'; $el.style.width = '100%'; setTimeout(() => { $el.style.transition = 'width 4000ms linear'; $el.style.width = '0%'; }, 50); }">
        </div>
    </div>
</div>

