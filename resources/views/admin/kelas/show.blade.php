<x-app-layout>
<div class="p-4 md:p-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <nav class="flex text-sm text-slate-400 mb-1" aria-label="Breadcrumb">
                <a href="{{ route('admin.kelas.index') }}" class="hover:text-indigo-600 transition">Kelas</a>
                <span class="mx-2">/</span>
                <span class="text-slate-600 font-bold">Detail Kelas {{ $kela->nama_kelas }}</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800">Detail Kelas {{ $kela->nama_kelas }}</h1>
            <p class="text-sm text-slate-400 mt-1">Kelola data siswa di dalam kelas ini.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.kelas.template') }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-xs font-semibold hover:bg-slate-50 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Download Template
            </a>
        </div>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- KIRI: MANAJEMEN SISWA --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Form Assign Manual --}}
            <div class="bg-white p-5 rounded-2xl border border-slate-200/60 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Masukkan Siswa
                </h3>
                <form action="{{ route('admin.kelas.assign', $kela->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Pilih Siswa</label>
                            <select name="siswa_ids[]" multiple class="w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 min-h-[150px]">
                                @foreach($siswaTersedia as $s)
                                    <option value="{{ $s->id }}">{{ $s->nis }} - {{ $s->nama }}</option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-slate-400 mt-2">Menampilkan siswa yang belum punya kelas aktif. Tahan CTRL untuk memilih banyak.</p>
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 text-white py-2.5 rounded-xl text-xs font-semibold hover:bg-indigo-700 transition shadow-sm shadow-indigo-200">
                            Konfirmasi
                        </button>
                    </div>
                </form>
            </div>

            {{-- Form Import Excel --}}
            <div class="bg-white p-5 rounded-2xl border border-slate-200/60 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                    Import via Excel
                </h3>
                <form action="{{ route('admin.kelas.import', $kela->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:bg-slate-50 hover:border-slate-300 transition">
                            <input type="file" name="file" id="file" class="hidden" onchange="updateFilename(this)">
                            <label for="file" class="cursor-pointer">
                                <svg id="file-icon" class="w-8 h-8 mx-auto text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                <div id="file-name" class="text-xs font-semibold text-slate-500">Klik untuk pilih file</div>
                                <div class="text-[10px] text-slate-400 mt-1">Format: .xlsx, .xls, .csv</div>
                            </label>
                        </div>
                        <button type="submit" class="w-full bg-emerald-600 text-white py-2.5 rounded-xl text-xs font-semibold hover:bg-emerald-700 transition shadow-sm shadow-emerald-200">
                            Mulai Import
                        </button>
                    </div>
                </form>
            </div>

        </div>

        {{-- KANAN: DAFTAR SISWA AKTIF --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden flex flex-col h-full">
                <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-white">
                    <h2 class="font-bold text-slate-800">Daftar Siswa Aktif</h2>
                    <span class="text-xs bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg font-semibold">
                        {{ $siswaAktif->count() }} Siswa
                    </span>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-sm text-left min-w-[500px]">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider w-10 text-center">No</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">NIS</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Lengkap</th>
                                <th class="px-6 py-4 text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($siswaAktif as $index => $s)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4 text-center text-slate-400 font-medium text-xs">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $s->nis }}</td>
                                    <td class="px-6 py-4 font-semibold text-slate-700">{{ $s->nama }}</td>
                                    <td class="px-6 py-4">
                                        <div x-data="{ showModal: false }" class="flex justify-center">
                                            <button @click="showModal = true" type="button" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Keluarkan Siswa">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>

                                            <!-- Modal Konfirmasi -->
                                            <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/50 backdrop-blur-sm" x-cloak>
                                                <div @click.away="showModal = false" class="relative w-full max-w-md p-4 bg-white rounded-2xl shadow-xl transform transition-all"
                                                     x-transition:enter="ease-out duration-300"
                                                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                     x-transition:leave="ease-in duration-200"
                                                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                                    
                                                    <div class="p-6 text-center">
                                                        <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                                                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                            </svg>
                                                        </div>
                                                        <h3 class="mb-2 text-xl font-bold text-slate-800">Keluarkan Siswa?</h3>
                                                        <p class="mb-6 text-sm text-slate-500 whitespace-normal text-left">Apakah Anda yakin ingin mengeluarkan <b>{{ $s->nama }}</b> dari kelas ini? Seluruh histori absensi siswa ini di kelas <b>{{ $kela->nama_kelas }}</b> juga akan ikut <b>terhapus permanen</b>.</p>
                                                        
                                                        <div class="flex justify-center gap-3">
                                                            <button @click="showModal = false" type="button" class="px-5 py-2.5 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                                                                Batal
                                                            </button>
                                                            <form action="{{ route('admin.kelas.remove-siswa', [$kela->id, $s->id]) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="px-5 py-2.5 rounded-xl font-semibold text-white bg-red-600 hover:bg-red-700 transition shadow-sm">
                                                                    Ya, Keluarkan
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <svg class="w-12 h-12 mx-auto text-slate-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                                        <p class="text-sm text-slate-400 font-medium">Kelas masih kosong.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function updateFilename(input) {
        const fileName = input.files[0].name;
        document.getElementById('file-name').innerText = fileName;
        document.getElementById('file-icon').innerText = '📄';
    }
</script>

</x-app-layout>

