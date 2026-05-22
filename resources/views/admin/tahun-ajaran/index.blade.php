<x-app-layout>
    <x-slot name="header">Kelola Tahun Ajaran</x-slot>
    <div class="p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight">📅 Manajemen Tahun Ajaran</h1>
                <p class="text-sm text-gray-500">Kelola siklus periode pendidikan dan pengarsipan data lama.</p>
            </div>

            <button x-data x-on:click.prevent="$dispatch('open-modal', 'tambah-tahun')"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-lg shadow-blue-100 transition font-bold text-sm">
                + Tahun Ajaran Baru
            </button>
        </div>

        @php
            $aktif = $tahunAjarans->firstWhere('status', 'aktif');
        @endphp

        @if($aktif)
            <div class="mb-8 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-8 shadow-xl shadow-blue-100 relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-white text-center md:text-left">
                        <span class="bg-white/20 text-white text-[10px] px-3 py-1 rounded-full font-bold uppercase tracking-widest backdrop-blur-md mb-3 inline-block">
                            Periode Aktif Saat Ini
                        </span>
                        <h2 class="text-4xl font-black mb-2">{{ $aktif->nama_tahun }}</h2>
                        <p class="text-blue-100 text-sm font-medium flex items-center justify-center md:justify-start gap-2">
                            <span>📅</span> 
                            {{ $aktif->tanggal_mulai?->translatedFormat('d F Y') }} - {{ $aktif->tanggal_selesai?->translatedFormat('d F Y') }}
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <button x-data x-on:click.prevent="$dispatch('open-modal', 'edit-tahun-{{ $aktif->id }}')"
                           class="bg-white text-blue-700 px-6 py-3 rounded-2xl font-bold text-sm shadow-lg hover:bg-blue-50 transition flex items-center gap-2">
                            ✏️ Edit Periode
                        </button>
                        <a href="{{ route('admin.tahun-ajaran.archive', $aktif->id) }}" 
                           class="bg-blue-500/30 text-white border border-white/20 px-6 py-3 rounded-2xl font-bold text-sm hover:bg-blue-500/50 transition flex items-center gap-2 backdrop-blur-md">
                            📦 Unduh Arsip
                        </a>
                    </div>
                </div>
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            </div>
        @endif

        <div class="mb-4">
             <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Histori & Tahun Ajaran Lain</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tahunAjarans->where('status', '!=', 'aktif') as $ta)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition hover:shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">{{ $ta->nama_tahun }}</h3>
                                <p class="text-xs text-gray-500 mt-1 font-medium flex items-center gap-1">
                                    <span>📅</span> 
                                    {{ $ta->tanggal_mulai?->format('d/m/Y') }} - {{ $ta->tanggal_selesai?->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-xl text-gray-400">
                                📅
                            </div>
                        </div>

                        <div class="space-y-3 pt-4 border-t border-gray-50">
                            @if($ta->status !== 'aktif')
                                <form id="form-aktif-{{ $ta->id }}" action="{{ route('admin.tahun-ajaran.set-aktif', $ta->id) }}" method="POST">
                                    @csrf
                                    <button type="button" 
                                            @click="$dispatch('confirm-dialog', { 
                                                title: 'Aktifkan Tahun Ajaran?', 
                                                message: 'Anda akan mengaktifkan periode {{ $ta->nama_tahun }}. Tahun ajaran lain akan dinonaktifkan secara otomatis.', 
                                                confirmText: 'Ya, Aktifkan', 
                                                type: 'info', 
                                                formId: 'form-aktif-{{ $ta->id }}' 
                                            })"
                                            class="w-full py-2 bg-gray-800 text-white rounded-xl text-xs font-bold hover:bg-black transition uppercase tracking-widest">
                                        Set Sebagai Aktif
                                    </button>
                                </form>
                            @endif

                            <div class="flex gap-2">
                                <a href="{{ route('admin.tahun-ajaran.archive', $ta->id) }}" 
                                   class="flex-1 text-center py-2 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-bold hover:bg-blue-100 transition uppercase tracking-wider">
                                    📦 Arsipkan
                                </a>

                                @if($ta->status !== 'aktif')
                                    <form id="form-hapus-{{ $ta->id }}" action="{{ route('admin.tahun-ajaran.destroy', $ta->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                @click="$dispatch('confirm-dialog', { 
                                                    title: 'Hapus Tahun Ajaran?', 
                                                    message: '⚠️ PERINGATAN KRITIKAL: Menghapus tahun ajaran ini akan menghapus PERMANEN semua data absensi, penempatan kelas, dan wali kelas di tahun {{ $ta->nama_tahun }}. Pastikan Anda sudah mengunduh arsipnya. Lanjutkan?', 
                                                    confirmText: 'Hapus Permanen', 
                                                    type: 'danger', 
                                                    formId: 'form-hapus-{{ $ta->id }}' 
                                                })"
                                                class="px-3 py-2 bg-red-50 text-red-600 rounded-xl text-[10px] font-bold hover:bg-red-600 hover:text-white transition uppercase tracking-wider">
                                            🗑️
                                        </button>
                                    </form>
                                @endif
                                
                                <button x-data x-on:click.prevent="$dispatch('open-modal', 'edit-tahun-{{ $ta->id }}')"
                                        class="px-3 py-2 bg-slate-100 text-slate-600 rounded-xl text-[10px] font-bold hover:bg-slate-200 transition uppercase tracking-wider">
                                    ✏️
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MODAL EDIT TAHUN -->
                <x-modal name="edit-tahun-{{ $ta->id }}" :show="false" focusable>
                    <div class="p-6 text-left">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-lg font-bold text-slate-800">Edit Tahun Ajaran</h2>
                                <p class="text-sm text-slate-400 mt-1">Sesuaikan nama dan tanggal periode tahun ajaran.</p>
                            </div>
                            <button type="button" x-on:click="$dispatch('close')" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        
                        @php
                            $editData = [
                                "existingNames" => $tahunAjarans->reject(fn($t) => $t->id == $ta->id)->pluck("nama_tahun")->values(),
                                "ranges" => $tahunAjarans->reject(fn($t) => $t->id == $ta->id)->map(fn($t) => ["start" => $t->tanggal_mulai?->format("Y-m-d"), "end" => $t->tanggal_selesai?->format("Y-m-d")])->filter(fn($r) => $r["start"] && $r["end"])->values()
                            ];
                        @endphp
                        <form id="form-edit-tahun-{{ $ta->id }}" action="{{ route('admin.tahun-ajaran.update', $ta->id) }}" method="POST" class="space-y-4" 
                              x-data='{
                                  data: @json($editData),
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
                                <div class="grid grid-cols-2 gap-4">
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
            @endforeach
        </div>
    </div>

    <!-- MODAL TAMBAH TAHUN -->
    <x-modal name="tambah-tahun" :show="$errors->any()" focusable>
        @php
            $tambahData = [
                "nama" => old("nama_tahun", ""),
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
        <div class="p-6" x-data='{
            ...@json($tambahData),
            errorNama: "",
            errorTanggal: ""
        }'>
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Tambah Tahun Ajaran Baru</h2>
                    <p class="text-sm text-slate-400 mt-1">Tambahkan periode pendidikan baru ke dalam sistem.</p>
                </div>
                <button type="button" x-on:click="$dispatch('close')" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form id="form-tambah-tahun" action="{{ route('admin.tahun-ajaran.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Tahun Ajaran</label>
                    <input type="text" name="nama_tahun" x-model="nama" placeholder="Contoh: 2025/2026" required
                           class="w-full border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition"
                           :class="errorNama ? 'border-red-500 ring-1 ring-red-500 bg-red-50' : ''"
                           @input="errorNama = ''">
                    @error('nama_tahun') <p class="text-xs text-red-600 mt-1.5 font-semibold">{{ $message }}</p> @enderror
                    <p x-show="errorNama" x-transition x-text="errorNama" class="text-xs text-red-600 mt-1.5 font-semibold" style="display: none;"></p>
                </div>

                <div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" x-model="mulai" required
                                   class="w-full border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition"
                                   :class="errorTanggal ? 'border-red-500 ring-1 ring-red-500 bg-red-50' : ''"
                                   @input="errorTanggal = ''">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" x-model="selesai" required
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
                                let hasError = false;
                                
                                if (!nama) {
                                    errorNama = "Nama tahun ajaran wajib diisi.";
                                    hasError = true;
                                } else if(existingNames.includes(nama)) {
                                    errorNama = "Tahun ajaran " + nama + " sudah terdaftar.";
                                    hasError = true;
                                }
                                
                                if (!mulai || !selesai) {
                                    errorTanggal = "Tanggal mulai dan selesai wajib diisi.";
                                    hasError = true;
                                } else if(new Date(selesai) <= new Date(mulai)) {
                                    errorTanggal = "Tanggal Selesai harus setelah Tanggal Mulai.";
                                    hasError = true;
                                } else {
                                    const overlap = ranges.find(r => (mulai <= r.end && selesai >= r.start));
                                    if(overlap) {
                                        errorTanggal = "Jadwal bentrok dengan periode " + overlap.start + " s/d " + overlap.end;
                                        hasError = true;
                                    }
                                }
                                
                                if(!hasError) {
                                    document.getElementById("form-tambah-tahun").submit();
                                }
                            '
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm">
                        Simpan Tahun
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>

