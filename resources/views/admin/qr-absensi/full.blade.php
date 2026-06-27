<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor QR Absensi - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('logo-tbg.png') }}">
</head>
<body class="bg-[radial-gradient(circle_at_top_right,_#1e293b,_#0f172a)] text-white min-h-screen m-0 flex items-center justify-center overflow-x-hidden font-sans">
    
    <!-- Background Decor -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[100px] -left-[100px] w-[400px] h-[400px] bg-gradient-to-br from-indigo-500/10 to-purple-500/10 rounded-full animate-[pulse_15s_infinite_alternate]"></div>
        <div class="absolute -bottom-[50px] -right-[50px] w-[300px] h-[300px] bg-gradient-to-br from-indigo-500/10 to-purple-500/10 rounded-full animate-[pulse_12s_infinite_alternate-reverse]"></div>
    </div>

    <!-- Navigation -->
    <a href="{{ route('admin.qr-absensi.index') }}" class="fixed top-4 left-4 lg:top-8 lg:left-8 z-50 flex items-center gap-1.5 lg:gap-2 bg-white/5 border border-white/10 text-slate-400 px-3 py-2 lg:px-6 lg:py-3 rounded-xl lg:rounded-2xl text-xs lg:text-sm font-semibold hover:bg-white/10 hover:text-white transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lg:w-5 lg:h-5"><path d="m15 18-6-6 6-6"/></svg>
        <span class="hidden sm:inline">Kembali ke Dashboard</span>
        <span class="sm:hidden">Kembali</span>
    </a>

    <button class="fixed bottom-4 right-4 lg:bottom-8 lg:right-8 z-50 bg-white/5 border border-white/10 text-white p-3 lg:p-4 rounded-xl lg:rounded-2xl hover:bg-white/10 hover:scale-110 transition-all" onclick="toggleFullScreen()" title="Toggle Fullscreen">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lg:w-6 lg:h-6"><path d="M8 3H5a2 2 0 0 0-2 2v3"/><path d="M21 8V5a2 2 0 0 0-2-2h-3"/><path d="M3 16v3a2 2 0 0 0 2 2h3"/><path d="M16 21h3a2 2 0 0 0 2-2v-3"/></svg>
    </button>

    <!-- MAIN CONTENT -->
    <div class="relative z-10 flex flex-col lg:flex-row items-center justify-center gap-6 lg:gap-16 xl:gap-20 w-full max-w-[1400px] px-4 sm:px-8 lg:px-16 py-16 lg:py-8">
        
        <!-- LEFT: TIME & DATE -->
        <div class="flex-1 flex flex-col items-center lg:items-start gap-3 lg:gap-8 order-1 lg:order-1">
            <div class="text-center lg:text-left">
                <div class="text-4xl sm:text-5xl md:text-6xl lg:text-[7rem] font-black leading-none tracking-[-0.05em] bg-gradient-to-b from-white to-slate-400 bg-clip-text text-transparent font-mono" id="clock">00:00:00</div>
                <div class="text-xs sm:text-sm md:text-lg lg:text-2xl text-slate-400 font-bold uppercase tracking-widest mt-2 lg:mt-4">{{ $today->timezone('Asia/Jakarta')->locale('id')->translatedFormat('l, d F Y') }}</div>
            </div>
            
            <div class="flex">
                <span class="text-[9px] sm:text-[10px] lg:text-xs font-bold text-slate-400 uppercase tracking-[0.15em] lg:tracking-[0.25em] border border-white/10 px-4 py-2 lg:px-6 lg:py-3 rounded-full bg-white/5 backdrop-blur-sm">
                    Batas: {{ $startTime }} — {{ $endTime }} WIB
                </span>
            </div>
        </div>

        <!-- MIDDLE: QR CODE -->
        <div class="relative bg-white/5 backdrop-blur-2xl border border-white/10 rounded-2xl sm:rounded-3xl lg:rounded-[3rem] p-6 sm:p-8 lg:p-14 text-center shadow-[0_40px_100px_-20px_rgba(0,0,0,0.6)] order-2 lg:order-2">
            @if($qrcode)
                <div class="absolute -top-3.5 lg:-top-5 left-1/2 -translate-x-1/2 px-5 py-2 lg:px-10 lg:py-3 rounded-full font-black text-[10px] lg:text-sm tracking-wider uppercase shadow-2xl z-20 whitespace-nowrap bg-gradient-to-br from-emerald-500 to-emerald-700 text-white" id="status-badge">
                    ● Sesi Aktif
                </div>
            @else
                <div class="absolute -top-3.5 lg:-top-5 left-1/2 -translate-x-1/2 px-5 py-2 lg:px-10 lg:py-3 rounded-full font-black text-[10px] lg:text-sm tracking-wider uppercase shadow-2xl z-20 whitespace-nowrap bg-gradient-to-br from-red-500 to-red-700 text-white" id="status-badge">
                    ● Sesi Nonaktif
                </div>
            @endif

            <div class="inline-block p-4 sm:p-6 lg:p-10 bg-white rounded-2xl sm:rounded-3xl lg:rounded-[2.5rem] shadow-[0_0_50px_rgba(99,102,241,0.4)]" id="qr-container">
                @if($qrcode)
                    <div class="w-[200px] h-[200px] sm:w-[260px] sm:h-[260px] md:w-[300px] md:h-[300px] lg:w-[400px] lg:h-[400px] flex items-center justify-center [&>svg]:w-full [&>svg]:h-full [&>img]:w-full [&>img]:h-full">
                        {!! $qrcode !!}
                    </div>
                @else
                    <div class="w-[200px] h-[200px] sm:w-[260px] sm:h-[260px] md:w-[300px] md:h-[300px] lg:w-[400px] lg:h-[400px] flex flex-col items-center justify-center text-slate-400">
                        <svg class="w-12 h-12 lg:w-20 lg:h-20 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"/></svg>
                        <p class="font-bold text-sm lg:text-lg text-slate-500 uppercase tracking-widest">{{ $message }}</p>
                    </div>
                @endif
            </div>

            @if($qrcode)
                <div class="w-full h-1.5 lg:h-2 bg-white/5 rounded-full mt-5 lg:mt-10 overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-1000 ease-linear" id="progress-bar" style="width: 100%"></div>
                </div>
                <p class="text-[8px] lg:text-[10px] uppercase tracking-[0.2em] lg:tracking-[0.3em] text-slate-500 font-black mt-3 lg:mt-4">
                    Refresh: {{ $interval }} Menit
                </p>
            @endif
        </div>

        <!-- RIGHT: STATISTICS -->
        <div class="flex flex-row lg:flex-col gap-3 lg:gap-6 order-3 lg:order-3 w-full lg:w-auto">
            <div class="bg-white/5 border border-white/5 rounded-2xl lg:rounded-[2rem] p-4 sm:p-5 lg:p-8 flex-1 lg:min-w-[320px] text-center lg:text-left">
                <span class="text-3xl sm:text-4xl lg:text-5xl font-black text-indigo-400 block leading-none mb-1 lg:mb-2" id="stat-total">{{ $totalGuru }}</span>
                <span class="text-[8px] sm:text-[9px] lg:text-[10px] font-bold text-slate-500 uppercase tracking-[0.15em] lg:tracking-[0.2em]">Total Guru</span>
            </div>
            <div class="bg-white/5 border border-white/5 rounded-2xl lg:rounded-[2rem] p-4 sm:p-5 lg:p-8 flex-1 lg:min-w-[320px] text-center lg:text-left">
                <span class="text-3xl sm:text-4xl lg:text-5xl font-black text-emerald-400 block leading-none mb-1 lg:mb-2" id="stat-present">{{ $sudahAbsensi }}</span>
                <span class="text-[8px] sm:text-[9px] lg:text-[10px] font-bold text-slate-500 uppercase tracking-[0.15em] lg:tracking-[0.2em]">Sudah Absensi</span>
            </div>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const time = now.toLocaleTimeString('id-ID', { timeZone: 'Asia/Jakarta', hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('clock').innerText = time;
        }
        setInterval(updateClock, 1000);
        updateClock();

        @if($expiresIn > 0)
            let timeLeft = {{ $expiresIn }};
            const totalInterval = {{ $interval * 60 }};
            const qrContainer = document.getElementById('qr-container');
            const progressBar = document.getElementById('progress-bar');
            const statusBadge = document.getElementById('status-badge');
            const statPresent = document.getElementById('stat-present');

            function updateTimer() {
                if (timeLeft <= 0) { refreshQR(); return; }
                const pct = (timeLeft / totalInterval) * 100;
                if (progressBar) progressBar.style.width = pct + '%';
                timeLeft--;
            }

            async function refreshQR() {
                try {
                    const res = await fetch('{{ route("admin.qr-absensi.refresh") }}?size=400');
                    const data = await res.json();
                    
                    if (data.qrcode) {
                        qrContainer.innerHTML = `<div class="w-[200px] h-[200px] sm:w-[260px] sm:h-[260px] md:w-[300px] md:h-[300px] lg:w-[400px] lg:h-[400px] flex items-center justify-center [&>svg]:w-full [&>svg]:h-full [&>img]:w-full [&>img]:h-full">${data.qrcode}</div>`;
                        timeLeft = data.expiresIn;
                        statusBadge.innerText = '● Sesi Aktif';
                        statusBadge.className = 'absolute -top-3.5 lg:-top-5 left-1/2 -translate-x-1/2 px-5 py-2 lg:px-10 lg:py-3 rounded-full font-black text-[10px] lg:text-sm tracking-wider uppercase shadow-2xl z-20 whitespace-nowrap bg-gradient-to-br from-emerald-500 to-emerald-700 text-white';
                        
                        updateStats();
                    } else {
                        window.location.reload();
                    }
                } catch (e) { console.error("Refresh failed", e); }
            }

            async function updateStats() {
                try {
                    const res = await fetch('{{ route("admin.qr-absensi.stats") }}');
                    const data = await res.json();
                    if (data.sudahAbsensi !== undefined) {
                        statPresent.innerText = data.sudahAbsensi;
                    }
                } catch (e) {}
            }

            setInterval(updateTimer, 1000);
            updateTimer();
            setInterval(updateStats, 3000);
        @else
            setTimeout(() => { window.location.reload(); }, 60000);
        @endif

        function toggleFullScreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }
    </script>
</body>
</html>
