{{-- 
    Universal Confirm Dialog Component
    Usage: 
    <x-confirm-dialog />
    Then trigger with Alpine:
    $dispatch('confirm-dialog', { 
        title: 'Hapus Data?', 
        message: 'Data akan dihapus permanen.',
        confirmText: 'Ya, Hapus',
        type: 'danger', // 'danger', 'warning', 'info'
        formId: 'delete-form-123'
    })
--}}

<div x-data="{ 
        show: false, 
        title: '', 
        message: '', 
        confirmText: 'Konfirmasi', 
        type: 'danger',
        formId: null,
        callback: null,
        typeConfig: {
            danger:  { bg: 'bg-red-50', text: 'text-red-600', btn: 'bg-red-600 hover:bg-red-700', icon: 'text-red-500', iconBg: 'bg-red-100' },
            warning: { bg: 'bg-amber-50', text: 'text-amber-600', btn: 'bg-amber-600 hover:bg-amber-700', icon: 'text-amber-500', iconBg: 'bg-amber-100' },
            info:    { bg: 'bg-indigo-50', text: 'text-indigo-600', btn: 'bg-indigo-600 hover:bg-indigo-700', icon: 'text-indigo-500', iconBg: 'bg-indigo-100' }
        }
     }"
     x-on:confirm-dialog.window="
        title = $event.detail.title || 'Konfirmasi';
        message = $event.detail.message || 'Apakah Anda yakin?';
        confirmText = $event.detail.confirmText || 'Konfirmasi';
        type = $event.detail.type || 'danger';
        formId = $event.detail.formId || null;
        callback = $event.detail.callback || null;
        show = true;
     "
     x-show="show"
     x-cloak
     class="fixed inset-0 z-[60] overflow-y-auto"
     style="display: none;">

    <!-- Backdrop -->
    <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="show = false"></div>

    <!-- Dialog -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div x-show="show"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-auto overflow-hidden ring-1 ring-slate-900/5">

            <div class="p-6 text-center">
                <!-- Icon -->
                <div class="mx-auto w-14 h-14 rounded-full flex items-center justify-center mb-4"
                     :class="typeConfig[type]?.iconBg">
                    <!-- Danger Icon -->
                    <template x-if="type === 'danger'">
                        <svg class="w-7 h-7" :class="typeConfig[type]?.icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                    </template>
                    <!-- Warning Icon -->
                    <template x-if="type === 'warning'">
                        <svg class="w-7 h-7" :class="typeConfig[type]?.icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                    </template>
                    <!-- Info Icon -->
                    <template x-if="type === 'info'">
                        <svg class="w-7 h-7" :class="typeConfig[type]?.icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    </template>
                </div>

                <h3 class="text-lg font-bold text-slate-800" x-text="title"></h3>
                <p class="text-sm text-slate-500 mt-2 leading-relaxed" x-text="message"></p>
            </div>

            <div class="flex border-t border-slate-100">
                <button @click="show = false" class="flex-1 py-3.5 text-sm font-semibold text-slate-500 hover:bg-slate-50 transition">
                    Batal
                </button>
                <button @click="
                    if (formId) { document.getElementById(formId).submit(); }
                    if (callback) { callback(); }
                    show = false;
                " class="flex-1 py-3.5 text-sm font-bold text-white transition border-l border-slate-100"
                :class="typeConfig[type]?.btn"
                x-text="confirmText">
                </button>
            </div>

        </div>
    </div>
</div>

