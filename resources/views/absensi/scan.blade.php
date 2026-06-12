<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Absensi Guru - Scan QR</title>
    <link rel="icon" type="image/png" href="{{ asset('logo-tbg.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        .animate-pulse-soft { animation: pulse-soft 2s infinite; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4 font-sans">

    <div class="w-full max-w-sm">

        {{-- STATUS MESSAGES --}}

        @if(session('warning'))
            <div class="bg-yellow-500 text-white p-4 rounded-2xl shadow-lg mb-6 flex items-center gap-3">
                <span class="text-2xl">⚠️</span>
                <p class="font-bold text-sm">{{ session('warning') }}</p>
            </div>
        @endif


        {{-- MAIN CARD --}}
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

            <div class="bg-gray-800 p-6 text-center">
                <h1 class="text-white font-bold text-lg">Absensi Digital Guru</h1>
                <p class="text-gray-400 text-xs mt-1">{{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</p>
            </div>

            <div class="p-8">
                @if(!$valid)
                    <div class="text-center py-4">
                        <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">🚫</div>
                        <h2 class="text-xl font-bold text-gray-800">QR Kadaluarsa</h2>
                        <p class="text-gray-500 text-xs mt-2 px-4">QR yang Anda scan sudah tidak berlaku. Silakan scan ulang QR terbaru di layar Admin.</p>
                        <button onclick="window.location.reload()" class="mt-6 text-sm font-bold text-blue-600 hover:underline">Coba Lagi</button>
                    </div>
                @elseif($sudahAbsensi)
                    <div class="text-center py-4">
                        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">✨</div>
                        <h2 class="text-xl font-bold text-gray-800">Sudah Absen</h2>
                        <p class="text-gray-500 text-xs mt-2 px-4">Anda sudah mencatat kehadiran hari ini. Terima kasih!</p>
                    </div>
                @else
                    <div class="text-center">
                        <div class="text-4xl mb-4">👋</div>
                        <h2 class="text-lg font-bold text-gray-800">Selamat Datang,</h2>
                        <p class="text-blue-600 font-bold text-xl">{{ auth()->user()->name }}</p>

                        <div class="my-8 py-6 bg-blue-50 rounded-2xl border border-blue-100">
                            <p class="text-[10px] uppercase tracking-widest text-blue-400 font-bold mb-1">Jam Saat Ini</p>
                            <p id="clock" class="text-3xl font-mono font-bold text-blue-900 tracking-tighter">--:--:--</p>
                        </div>

                        <form action="{{ route('absensi.scan.process', $token) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-gray-900 text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-black transition-all active:scale-95 animate-pulse-soft">
                                KONFIRMASI HADIR
                            </button>
                        </form>
                        <p class="text-[10px] text-gray-400 mt-4 uppercase font-bold tracking-tight">Pastikan Anda berada di area sekolah</p>
                    </div>
                @endif
            </div>

        </div>

        <p class="text-center mt-8 text-[10px] text-gray-400 font-bold uppercase tracking-widest">Sistem Absensi Sekolah &copy; 2026</p>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock').innerText = `${h}:${m}:${s}`;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>

