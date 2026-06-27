<x-app-layout>
    <x-slot name="header">QR Absensi Guru</x-slot>
<div class="p-4 md:p-6 lg:p-8">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-slate-800">Manajemen QR & Absensi Guru</h1>
                @if($tahunAktifGlobal)
                    <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-[11px] font-bold border border-indigo-100">
                        {{ $tahunAktifGlobal->nama_tahun }}
                    </span>
                @endif
            </div>
            <p class="text-sm text-slate-400 mt-1">Monitor kehadiran guru secara real-time.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-white px-4 py-2.5 rounded-xl shadow-sm border border-slate-200 flex items-center gap-3 text-sm text-slate-600">
                <span class="font-bold border-r border-slate-200 pr-3 font-mono"><span id="server-clock">--:--:--</span> WIB</span>
                <span class="text-slate-400">{{ $today->locale('id')->translatedFormat('d F Y') }}</span>
            </div>
        </div>
    </div>

    {{-- FLASH --}}

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- KIRI: QR CODE & SETTINGS --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- CARD: QR CODE --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="bg-slate-800 p-4 text-white flex justify-between items-center rounded-t-2xl">
                    <div class="flex items-center gap-3">
                        <h2 class="font-bold text-sm">QR Absensi</h2>
                        <a href="{{ route('admin.qr-absensi.full') }}" target="_blank" class="bg-indigo-500 hover:bg-indigo-600 text-[10px] px-2 py-0.5 rounded font-bold transition flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Full View
                        </a>
                    </div>
                    <span id="timer-badge" class="bg-slate-700 text-[10px] px-2.5 py-1 rounded-full font-mono {{ $qrcode ? '' : 'hidden' }}">--:--</span>
                </div>
                <div class="p-8 text-center">
                    <div id="qr-container" class="inline-block p-4 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 transition-all duration-500 min-h-[232px] flex items-center justify-center">
                        @if($qrcode)
                            {!! $qrcode !!}
                        @else
                            <div class="p-6">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"/></svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-700">{{ $message }}</h3>
                                <p class="text-[11px] text-slate-400 mt-1 uppercase font-semibold tracking-wider">{{ $subMessage }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-5">
                        <p id="qr-status-text" class="text-[11px] font-bold uppercase tracking-widest {{ $qrcode ? 'text-emerald-600' : 'text-red-400' }}">
                            ● {{ $message }}
                        </p>
                        @if($qrcode)
                            <p class="text-[10px] text-slate-400 font-medium mt-1">Sesi sedang berlangsung</p>
                        @endif
                    </div>

                    @if($qrcode)
                        <div class="mt-5 pt-5 border-t border-slate-100">
                            <div class="w-full bg-slate-100 rounded-full h-1.5 mb-2">
                                <div id="timer-progress" class="bg-indigo-500 h-1.5 rounded-full transition-all duration-1000" style="width: 100%"></div>
                            </div>
                            <p id="timer-text" class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold">
                                Berlaku hingga: <span id="expiry-time">{{ $endTime }} WIB</span>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- CARD: MASTER SWITCH --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-700 text-sm mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"/></svg>
                    Master Switch
                </h3>
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                    <div>
                        <p class="text-xs font-semibold text-slate-600">Status Sesi</p>
                        <p class="text-[11px] text-slate-400 font-semibold mt-0.5">
                            {{ $isActive ? 'Aktif (Auto)' : 'Dimatikan Manual' }}
                        </p>
                    </div>
                    <form action="{{ route('admin.qr-absensi.status.toggle') }}" method="POST">
                        @csrf
                        <button class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/20 {{ $isActive ? 'bg-emerald-500' : 'bg-slate-300' }}">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform {{ $isActive ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- CARD: PENGATURAN --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-700 text-sm mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Pengaturan Operasional
                </h3>
                <form action="{{ route('admin.qr-absensi.settings.update') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Jam Mulai</label>
                                <input type="time" name="qr_start_time" value="{{ $startTime }}"
                                       class="w-full border-0 bg-slate-50 rounded-xl py-2.5 px-3 text-sm text-slate-600 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Jam Selesai</label>
                                <input type="time" name="qr_end_time" value="{{ $endTime }}"
                                       class="w-full border-0 bg-slate-50 rounded-xl py-2.5 px-3 text-sm text-slate-600 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Interval Refresh (Menit)</label>
                            <input type="number" name="qr_interval" value="{{ $interval }}" min="1" max="60"
                                   class="w-full border-0 bg-slate-50 rounded-xl py-2.5 px-3 text-sm text-slate-600 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition">
                        </div>
                        <button class="w-full bg-slate-800 text-white py-2.5 rounded-xl text-xs font-semibold hover:bg-slate-900 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- KANAN: DAFTAR GURU --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-2 md:gap-0">
                    <h2 class="font-bold text-slate-700 text-sm">Daftar Guru Hari Ini</h2>
                    <div class="flex gap-2">
                        <span class="text-[11px] bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-lg font-bold border border-indigo-100">Total: {{ $totalGuru }}</span>
                        <span class="text-[11px] bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-lg font-bold border border-emerald-100">Sudah Absensi: {{ $sudahAbsensi }}</span>
                    </div>
                </div>

                <!-- DATA LIST -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Guru</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jam Masuk</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aksi Manual</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($guruList as $guru)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 text-xs font-bold">
                                                {{ strtoupper(substr($guru->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <span class="font-semibold text-sm text-slate-700">{{ $guru->name }}</span>
                                                <p class="text-[10px] text-slate-400 font-mono">ID-{{ str_pad($guru->id, 5, '0', STR_PAD_LEFT) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-sm text-slate-500">
                                        {{ $guru->jam_masuk ?? '--:--:--' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($guru->status)
                                            @php
                                                $sc = [
                                                    'hadir'=>'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'terlambat'=>'bg-amber-50 text-amber-700 border-amber-200',
                                                    'izin'=>'bg-sky-50 text-sky-700 border-sky-200',
                                                    'sakit'=>'bg-orange-50 text-orange-700 border-orange-200',
                                                    'alpha'=>'bg-red-50 text-red-700 border-red-200',
                                                ];
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold border {{ $sc[$guru->status] ?? '' }}">
                                                {{ strtoupper($guru->status) }}
                                            </span>
                                        @else
                                            <span class="text-slate-300 text-[11px] font-semibold">Belum Absen</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(!$guru->status)
                                            <div class="flex justify-center gap-1">
                                                <button onclick="openConfirm({{ $guru->id }}, '{{ $guru->name }}', 'terlambat')" class="px-2 py-1 bg-amber-50 text-amber-600 border border-amber-200 rounded text-[10px] font-bold hover:bg-amber-500 hover:text-white transition">Terlambat</button>
                                                <button onclick="openConfirm({{ $guru->id }}, '{{ $guru->name }}', 'izin')" class="px-2 py-1 bg-sky-50 text-sky-600 border border-sky-200 rounded text-[10px] font-bold hover:bg-sky-500 hover:text-white transition">Izin</button>
                                                <button onclick="openConfirm({{ $guru->id }}, '{{ $guru->name }}', 'sakit')" class="px-2 py-1 bg-orange-50 text-orange-600 border border-orange-200 rounded text-[10px] font-bold hover:bg-orange-500 hover:text-white transition">Sakit</button>
                                                <button onclick="openConfirm({{ $guru->id }}, '{{ $guru->name }}', 'alpha')" class="px-2 py-1 bg-red-50 text-red-600 border border-red-200 rounded text-[10px] font-bold hover:bg-red-500 hover:text-white transition">Alpha</button>
                                            </div>
                                        @else
                                            <div class="text-center text-[10px] text-slate-300 italic">Terkunci</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden divide-y divide-slate-100">
                    @foreach($guruList as $guru)
                        <div class="p-4 hover:bg-slate-50 transition active:bg-slate-100">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 text-xs font-bold shrink-0">
                                        {{ strtoupper(substr($guru->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800 text-sm leading-tight">{{ $guru->name }}</h4>
                                        <p class="text-[10px] text-slate-400 font-mono mt-1">{{ $guru->jam_masuk ?? '--:--' }}</p>
                                    </div>
                                </div>
                                @if($guru->status)
                                    @php
                                        $scMobile = [
                                            'hadir'=>'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'terlambat'=>'bg-amber-50 text-amber-700 border-amber-200',
                                            'izin'=>'bg-sky-50 text-sky-700 border-sky-200',
                                            'sakit'=>'bg-orange-50 text-orange-700 border-orange-200',
                                            'alpha'=>'bg-red-50 text-red-700 border-red-200',
                                        ];
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider border {{ $scMobile[$guru->status] ?? 'bg-indigo-50 text-indigo-600 border-indigo-100' }}">{{ $guru->status }}</span>
                                @else
                                    <span class="text-slate-300 text-[9px] font-bold uppercase tracking-wider">Belum Absen</span>
                                @endif
                            </div>
                            
                            @if(!$guru->status)
                                <div class="grid grid-cols-4 gap-1 pt-1">
                                    <button onclick="openConfirm({{ $guru->id }}, '{{ $guru->name }}', 'terlambat')" class="py-2 bg-amber-50 text-amber-600 rounded-lg text-[9px] font-bold border border-amber-100">TERLAMBAT</button>
                                    <button onclick="openConfirm({{ $guru->id }}, '{{ $guru->name }}', 'izin')" class="py-2 bg-sky-50 text-sky-600 rounded-lg text-[9px] font-bold border border-sky-100">IZIN</button>
                                    <button onclick="openConfirm({{ $guru->id }}, '{{ $guru->name }}', 'sakit')" class="py-2 bg-orange-50 text-orange-600 rounded-lg text-[9px] font-bold border border-orange-100">SAKIT</button>
                                    <button onclick="openConfirm({{ $guru->id }}, '{{ $guru->name }}', 'alpha')" class="py-2 bg-red-50 text-red-600 rounded-lg text-[9px] font-bold border border-red-100">ALPHA</button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI --}}
<div id="confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 overflow-hidden border border-slate-200">
        <div id="modal-header" class="p-6 text-center">
            <div id="modal-icon" class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl"></div>
            <h3 class="text-lg font-bold text-slate-800">Konfirmasi Absensi</h3>
        </div>
        <div class="px-6 pb-6 text-center">
            <p class="text-sm text-slate-500">
                Anda akan mengubah status <span id="modal-guru" class="font-bold text-slate-800"></span> menjadi:
            </p>
            <div id="modal-status-badge" class="mt-3 inline-block px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest text-white"></div>
            <p id="modal-time-info" class="text-[11px] text-slate-400 mt-2 hidden">
                Jam masuk dicatat: <span id="modal-time" class="font-mono font-bold"></span>
            </p>
            <p class="text-[11px] text-slate-400 mt-4 border-t border-slate-100 pt-3">
                Aksi ini tidak dapat dibatalkan untuk hari ini.
            </p>
        </div>
        <div class="flex border-t border-slate-100">
            <button onclick="closeConfirm()" class="flex-1 py-3.5 text-sm font-semibold text-slate-500 hover:bg-slate-50 transition rounded-bl-2xl">
                Batal
            </button>
            <form id="confirm-form" action="{{ route('admin.qr-absensi.mark-manual') }}" method="POST" class="flex-1 border-l border-slate-100">
                @csrf
                <input type="hidden" name="guru_id" id="confirm-guru-id">
                <input type="hidden" name="status" id="confirm-status">
                <button type="submit" id="confirm-btn" class="w-full py-3.5 text-sm font-bold text-white transition rounded-br-2xl">
                    Ya, Konfirmasi
                </button>
            </form>
        </div>
    </div>
</div>

@if($expiresIn > 0)
<script>
    let timeLeft = {{ $expiresIn }};
    const totalInterval = {{ $interval * 60 }};
    const qrContainer = document.getElementById('qr-container');
    const timerBadge = document.getElementById('timer-badge');
    const timerProgress = document.getElementById('timer-progress');
    const qrStatusText = document.getElementById('qr-status-text');

    function updateTimer() {
        if (timeLeft <= 0) { refreshQR(); return; }
        const m = Math.floor(timeLeft / 60), s = timeLeft % 60;
        timerBadge.innerText = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
        const pct = (timeLeft / totalInterval) * 100;
        timerProgress.style.width = pct + '%';
        if (pct < 20) { timerProgress.className = timerProgress.className.replace('bg-indigo-500','bg-red-500'); }
        timeLeft--;
    }

    async function refreshQR() {
        try {
            timerBadge.innerText = "REFRESH...";
            qrContainer.style.opacity = '0.3';
            const res = await fetch('{{ route("admin.qr-absensi.refresh") }}');
            const data = await res.json();
            if (data.qrcode) {
                qrContainer.innerHTML = data.qrcode;
                qrContainer.style.opacity = '1';
                timeLeft = data.expiresIn;
                timerBadge.classList.remove('hidden');
                qrStatusText.innerText = data.message;
            } else { window.location.reload(); }
        } catch (e) { timerBadge.innerText = "ERROR"; }
    }

    setInterval(updateTimer, 1000);
    updateTimer();
</script>
@else
<script>setTimeout(() => { window.location.reload(); }, 60000);</script>
@endif

<script>
    const statusConfig = {
        terlambat: { color: 'bg-amber-500', icon: '⏰', label: 'Terlambat' },
        izin:      { color: 'bg-sky-500',   icon: '📝', label: 'Izin' },
        sakit:     { color: 'bg-orange-400', icon: '🤒', label: 'Sakit' },
        alpha:     { color: 'bg-red-500',    icon: '❌', label: 'Alpha' }
    };

    function openConfirm(guruId, guruName, status) {
        const c = statusConfig[status], modal = document.getElementById('confirm-modal');
        document.getElementById('confirm-guru-id').value = guruId;
        document.getElementById('confirm-status').value = status;
        document.getElementById('modal-guru').innerText = guruName;

        const badge = document.getElementById('modal-status-badge');
        badge.innerText = c.label;
        badge.className = 'mt-3 inline-block px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest text-white ' + c.color;

        const icon = document.getElementById('modal-icon');
        icon.innerText = c.icon;
        icon.className = 'w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl bg-slate-50';

        document.getElementById('confirm-btn').className = 'w-full py-3.5 text-sm font-bold text-white transition rounded-br-2xl ' + c.color;

        const ti = document.getElementById('modal-time-info');
        if (status === 'terlambat') { ti.classList.remove('hidden'); document.getElementById('modal-time').innerText = new Date().toLocaleTimeString('id-ID'); }
        else { ti.classList.add('hidden'); }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeConfirm() {
        const m = document.getElementById('confirm-modal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }

    document.getElementById('confirm-modal').addEventListener('click', function(e) { if (e.target === this) closeConfirm(); });

    const clockEl = document.getElementById('server-clock');
    if (clockEl) {
        function updateClock() { clockEl.innerText = new Date().toLocaleTimeString('id-ID'); }
        setInterval(updateClock, 1000);
        updateClock();
    }
</script>

</x-app-layout>

