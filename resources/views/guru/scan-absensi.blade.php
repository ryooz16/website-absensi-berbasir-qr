<x-app-layout>
    <x-slot name="header">Scan QR Absensi</x-slot>
    <div class="p-6 lg:p-8 max-w-3xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Scan Absensi</h1>
            <p class="text-sm text-slate-400 mt-1">Arahkan kamera ke QR Code yang ditampilkan Admin untuk melakukan absensi.</p>
        </div>


        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6 text-center">
            <div id="reader" class="mx-auto w-full max-w-sm rounded-xl overflow-hidden mb-4 border border-slate-200"></div>
            
            <p class="text-sm text-slate-500 font-medium" id="scan-status">Kamera sedang diinisiasi...</p>

            <form id="scan-form" action="" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </div>

    <!-- html5-qrcode -->
    @vite('resources/js/qr-scanner.js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const html5QrCode = new Html5Qrcode("reader");
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };

            html5QrCode.start({ facingMode: "environment" }, config, (decodedText, decodedResult) => {
                if (decodedText.includes('/presensi/scan/')) {
                    document.getElementById('scan-status').innerHTML = '<span class="text-emerald-600 font-bold">QR Terbaca! Memproses...</span>';
                    html5QrCode.stop().then(() => {
                        const form = document.getElementById('scan-form');
                        form.action = decodedText; 
                        form.submit();
                    });
                } else {
                    document.getElementById('scan-status').innerHTML = '<span class="text-red-500">QR Code tidak valid untuk absensi sistem ini.</span>';
                }
            }, (errorMessage) => {
                // continue reading
            }).catch((err) => {
                document.getElementById('scan-status').innerHTML = '<span class="text-red-500">Gagal mengakses kamera. Pastikan izin kamera diberikan.</span>';
            });
        });
    </script>
</x-app-layout>

