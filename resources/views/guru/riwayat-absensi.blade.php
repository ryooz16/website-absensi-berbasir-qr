<x-app-layout>
    <x-slot name="header">Riwayat Absensi</x-slot>

    <div class="p-4 md:p-6 lg:p-8" x-data="{ }">

        <!-- HEADER -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Riwayat Absensi</h1>
            <p class="text-sm text-slate-400 mt-1">Catatan kehadiran pribadi Anda, {{ $guru->name }}.</p>
        </div>

        <!-- STATUS HARI INI -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 md:p-6 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg
                    @if($absensiHariIni)
                        @if($absensiHariIni->status === 'hadir') bg-emerald-500 shadow-emerald-200
                        @elseif($absensiHariIni->status === 'terlambat') bg-amber-500 shadow-amber-200
                        @elseif($absensiHariIni->status === 'izin') bg-sky-500 shadow-sky-200
                        @elseif($absensiHariIni->status === 'sakit') bg-orange-500 shadow-orange-200
                        @else bg-rose-500 shadow-rose-200
                        @endif
                    @else bg-slate-200 shadow-slate-100
                    @endif
                ">
                    @if($absensiHariIni)
                        @if($absensiHariIni->status === 'hadir')
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @elseif($absensiHariIni->status === 'terlambat')
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @else
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    @else
                        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status Hari Ini — {{ \Carbon\Carbon::today()->locale('id')->translatedFormat('l, d F Y') }}</p>
                    @if($absensiHariIni)
                        <p class="text-lg font-black text-slate-800 mt-0.5">
                            {{ ucfirst($absensiHariIni->status) }}
                            @if($absensiHariIni->jam_masuk)
                                <span class="text-sm font-semibold text-slate-400 ml-1">pukul {{ \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i') }}</span>
                            @endif
                        </p>
                    @else
                        <p class="text-lg font-bold text-slate-400 mt-0.5">Belum Absensi</p>
                    @endif
                </div>
                @if(!$absensiHariIni)
                    <a href="{{ route('guru.scan') }}" class="hidden md:flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5z"/></svg>
                        Scan Absensi
                    </a>
                @endif
            </div>
        </div>

        <!-- STATISTIK CARDS -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 md:gap-4 mb-6">
            <!-- Hadir -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 md:p-5 hover:shadow-md transition group">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Hadir</p>
                </div>
                <p class="text-3xl font-black text-emerald-600">{{ $stats['hadir'] }}</p>
            </div>

            <!-- Terlambat -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 md:p-5 hover:shadow-md transition group">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Terlambat</p>
                </div>
                <p class="text-3xl font-black text-amber-600">{{ $stats['terlambat'] }}</p>
            </div>

            <!-- Izin -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 md:p-5 hover:shadow-md transition group">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-sky-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Izin</p>
                </div>
                <p class="text-3xl font-black text-sky-600">{{ $stats['izin'] }}</p>
            </div>

            <!-- Sakit -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 md:p-5 hover:shadow-md transition group">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sakit</p>
                </div>
                <p class="text-3xl font-black text-orange-600">{{ $stats['sakit'] }}</p>
            </div>

            <!-- Alpha -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 md:p-5 hover:shadow-md transition group col-span-2 sm:col-span-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Alpha</p>
                </div>
                <p class="text-3xl font-black text-rose-600">{{ $stats['alpha'] }}</p>
            </div>
        </div>

        <!-- FILTER + TABLE -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <!-- Filter -->
            <div class="p-4 md:p-6 border-b border-slate-100">
                <form action="{{ route('guru.riwayat-absensi') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Bulan</label>
                        <select name="bulan" class="w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            @foreach($bulanList as $key => $nama)
                                <option value="{{ $key }}" {{ (int)$bulan === $key ? 'selected' : '' }}>{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tahun</label>
                        <select name="tahun" class="w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            @foreach($tahunList as $thn)
                                <option value="{{ $thn }}" {{ (int)$tahun === $thn ? 'selected' : '' }}>{{ $thn }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm shadow-indigo-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                </form>
            </div>

            <!-- Period Label -->
            <div class="px-4 md:px-6 py-3 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500">
                    Menampilkan: <span class="text-indigo-600">{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} — {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</span>
                </p>
                <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-lg">{{ $stats['total'] }} data</span>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-4 md:px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">No</th>
                            <th class="px-4 md:px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Hari & Tanggal</th>
                            <th class="px-4 md:px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jam Masuk</th>
                            <th class="px-4 md:px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($riwayat as $index => $item)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-4 md:px-6 py-4 text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-4 md:px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 text-sm">{{ $item->tanggal->locale('id')->translatedFormat('l') }}</span>
                                        <span class="text-[11px] text-slate-400 font-medium">{{ $item->tanggal->locale('id')->translatedFormat('d F Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-4">
                                    @if($item->jam_masuk)
                                        <span class="font-mono font-bold text-slate-700 text-sm">{{ \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') }}</span>
                                    @else
                                        <span class="text-slate-300 text-xs font-medium">—</span>
                                    @endif
                                </td>
                                <td class="px-4 md:px-6 py-4">
                                    @php
                                        $statusConfig = [
                                            'hadir' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500'],
                                            'terlambat' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'dot' => 'bg-amber-500'],
                                            'izin' => ['bg' => 'bg-sky-50', 'text' => 'text-sky-700', 'border' => 'border-sky-200', 'dot' => 'bg-sky-500'],
                                            'sakit' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200', 'dot' => 'bg-orange-500'],
                                            'alpha' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'dot' => 'bg-rose-500'],
                                        ];
                                        $cfg = $statusConfig[$item->status] ?? $statusConfig['alpha'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold border {{ $cfg['bg'] }} {{ $cfg['text'] }} {{ $cfg['border'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                    </div>
                                    <p class="text-slate-400 font-medium text-sm">Belum ada data absensi pada periode ini.</p>
                                    <p class="text-slate-300 text-xs mt-1">Coba ubah filter bulan atau tahun.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Percentage Bar -->
            @if($stats['total'] > 0)
                <div class="p-4 md:p-6 border-t border-slate-100 bg-slate-50/30">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Persentase Kehadiran</p>
                    <div class="w-full h-4 bg-slate-100 rounded-full overflow-hidden flex">
                        @if($stats['hadir'] > 0)
                            <div class="bg-emerald-500 h-full transition-all duration-500" style="width: {{ ($stats['hadir'] / $stats['total']) * 100 }}%" title="Hadir {{ round(($stats['hadir'] / $stats['total']) * 100) }}%"></div>
                        @endif
                        @if($stats['terlambat'] > 0)
                            <div class="bg-amber-500 h-full transition-all duration-500" style="width: {{ ($stats['terlambat'] / $stats['total']) * 100 }}%" title="Terlambat {{ round(($stats['terlambat'] / $stats['total']) * 100) }}%"></div>
                        @endif
                        @if($stats['izin'] > 0)
                            <div class="bg-sky-500 h-full transition-all duration-500" style="width: {{ ($stats['izin'] / $stats['total']) * 100 }}%" title="Izin {{ round(($stats['izin'] / $stats['total']) * 100) }}%"></div>
                        @endif
                        @if($stats['sakit'] > 0)
                            <div class="bg-orange-500 h-full transition-all duration-500" style="width: {{ ($stats['sakit'] / $stats['total']) * 100 }}%" title="Sakit {{ round(($stats['sakit'] / $stats['total']) * 100) }}%"></div>
                        @endif
                        @if($stats['alpha'] > 0)
                            <div class="bg-rose-500 h-full transition-all duration-500" style="width: {{ ($stats['alpha'] / $stats['total']) * 100 }}%" title="Alpha {{ round(($stats['alpha'] / $stats['total']) * 100) }}%"></div>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-x-5 gap-y-1 mt-3">
                        <span class="flex items-center gap-1.5 text-[11px] text-slate-500 font-medium"><span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Hadir {{ round(($stats['hadir'] / $stats['total']) * 100) }}%</span>
                        <span class="flex items-center gap-1.5 text-[11px] text-slate-500 font-medium"><span class="w-2 h-2 bg-amber-500 rounded-full"></span> Terlambat {{ round(($stats['terlambat'] / $stats['total']) * 100) }}%</span>
                        <span class="flex items-center gap-1.5 text-[11px] text-slate-500 font-medium"><span class="w-2 h-2 bg-sky-500 rounded-full"></span> Izin {{ round(($stats['izin'] / $stats['total']) * 100) }}%</span>
                        <span class="flex items-center gap-1.5 text-[11px] text-slate-500 font-medium"><span class="w-2 h-2 bg-orange-500 rounded-full"></span> Sakit {{ round(($stats['sakit'] / $stats['total']) * 100) }}%</span>
                        <span class="flex items-center gap-1.5 text-[11px] text-slate-500 font-medium"><span class="w-2 h-2 bg-rose-500 rounded-full"></span> Alpha {{ round(($stats['alpha'] / $stats['total']) * 100) }}%</span>
                    </div>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
