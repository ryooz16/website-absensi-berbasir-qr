<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AbsensiGuru;
use App\Models\User;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrAbsensiController extends Controller
{

    public function fullView()
    {
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();

        $interval = (int) Setting::get('qr_interval', 5);
        $startTime = Setting::get('qr_start_time', '06:00');
        $endTime = Setting::get('qr_end_time', '08:00');
        $isActive = (bool) Setting::get('qr_active', true);

        $start = Carbon::parse($today . ' ' . $startTime);
        $end = Carbon::parse($today . ' ' . $endTime);
        $isWithinTime = $now->between($start, $end);

        $qrData = $this->generateTimedQrData($today, $interval, $isActive, $isWithinTime, $startTime, $endTime, 400);

        // Statistik singkat untuk monitor
        $sudahAbsensi = AbsensiGuru::where('tanggal', $today)->count();
        $totalGuru = User::where('role', 'guru')->count();

        return view('admin.qr-absensi.full', array_merge($qrData, [
            'sudahAbsensi' => $sudahAbsensi,
            'totalGuru' => $totalGuru,
            'today' => Carbon::today(),
            'interval' => $interval,
            'startTime' => $startTime,
            'endTime' => $endTime,
        ]));
    }

    public function getStats()
    {
        $today = Carbon::today()->toDateString();
        $sudahAbsensi = AbsensiGuru::where('tanggal', $today)->count();

        return response()->json([
            'sudahAbsensi' => $sudahAbsensi,
        ]);
    }

    public function index()
    {
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();

        // Ambil Konfigurasi
        $interval = (int) Setting::get('qr_interval', 5);
        $startTime = Setting::get('qr_start_time', '06:00');
        $endTime = Setting::get('qr_end_time', '08:00');
        $isActive = (bool) Setting::get('qr_active', true);

        // Cek apakah sekarang dalam jendela waktu operasional
        $start = Carbon::parse($today . ' ' . $startTime);
        $end = Carbon::parse($today . ' ' . $endTime);
        $isWithinTime = $now->between($start, $end);

        // Generate QR Data
        $qrData = $this->generateTimedQrData($today, $interval, $isActive, $isWithinTime, $startTime, $endTime, 200);

        // Daftar Guru
        $guruList = User::where('role', 'guru')
            ->leftJoin('absensi_guru', function($join) use ($today) {
                $join->on('users.id', '=', 'absensi_guru.guru_id')
                     ->where('absensi_guru.tanggal', '=', $today);
            })
            ->select('users.id', 'users.name', 'absensi_guru.jam_masuk', 'absensi_guru.status', 'absensi_guru.id as absensi_id')
            ->orderBy('users.name', 'asc')
            ->get();

        // Statistik
        $totalGuru = User::where('role', 'guru')->count();
        $sudahAbsensi = AbsensiGuru::where('tanggal', $today)->count();
        $terlambat = AbsensiGuru::where('tanggal', $today)->where('status', 'terlambat')->count();

        return view('admin.qr-absensi.index', array_merge($qrData, [
            'guruList' => $guruList,
            'totalGuru' => $totalGuru,
            'sudahAbsensi' => $sudahAbsensi,
            'terlambat' => $terlambat,
            'interval' => $interval,
            'isActive' => $isActive,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'isWithinTime' => $isWithinTime,
            'today' => Carbon::today()
        ]));
    }

    public function refreshQr(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();

        $interval = (int) Setting::get('qr_interval', 5);
        $startTime = Setting::get('qr_start_time', '06:00');
        $endTime = Setting::get('qr_end_time', '08:00');
        $isActive = (bool) Setting::get('qr_active', true);

        $start = Carbon::parse($today . ' ' . $startTime);
        $end = Carbon::parse($today . ' ' . $endTime);
        $isWithinTime = $now->between($start, $end);

        // Ukuran menyesuaikan darimana request berasal (default 200)
        $size = $request->input('size', 200);

        $data = $this->generateTimedQrData($today, $interval, $isActive, $isWithinTime, $startTime, $endTime, $size);

        return response()->json($data);
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'qr_interval' => 'required|integer|min:1|max:60',
            'qr_start_time' => 'required|date_format:H:i',
            'qr_end_time' => 'required|date_format:H:i|after:qr_start_time',
        ]);

        Setting::set('qr_interval', $request->qr_interval);
        Setting::set('qr_start_time', $request->qr_start_time);
        Setting::set('qr_end_time', $request->qr_end_time);

        return back()->with('success', 'Konfigurasi operasional berhasil diperbarui.');
    }

    public function toggleStatus(Request $request)
    {
        $current = (bool) Setting::get('qr_active', true);
        Setting::set('qr_active', $current ? '0' : '1');

        $status = !$current ? 'diaktifkan' : 'dimatikan';
        return back()->with('success', "Sesi QR berhasil $status.");
    }

    public function markManual(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:users,id',
            'status' => 'required|in:terlambat,izin,sakit,alpha'
        ]);

        $today = Carbon::today()->toDateString();
        $exists = AbsensiGuru::where('guru_id', $request->guru_id)->where('tanggal', $today)->exists();

        if ($exists) {
            return back()->with('error', 'Guru tersebut sudah memiliki data absensi hari ini.');
        }

        $tahunAktif = \App\Models\TahunAjaran::where('status', 'aktif')->first();

        AbsensiGuru::create([
            'guru_id' => $request->guru_id,
            'tahun_ajaran_id' => $tahunAktif?->id,
            'tanggal' => $today,
            'status' => $request->status,
            'jam_masuk' => $request->status === 'terlambat' ? Carbon::now()->format('H:i:s') : null
        ]);

        return back()->with('success', 'Status absensi berhasil diperbarui.');
    }

    public function scanPage(string $token)
    {
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();

        $interval = (int) Setting::get('qr_interval', 5);
        $startTime = Setting::get('qr_start_time', '06:00');
        $endTime = Setting::get('qr_end_time', '08:00');
        $isActive = (bool) Setting::get('qr_active', true);

        // Cek apakah masih dalam jam operasional
        $start = Carbon::parse($today . ' ' . $startTime);
        $end = Carbon::parse($today . ' ' . $endTime);
        $isWithinTime = $now->between($start, $end);

        if (!$isActive || !$isWithinTime) {
            return view('absensi.scan', [
                'valid' => false,
                'error_type' => !$isActive ? 'MANUAL_OFF' : 'OUT_OF_TIME',
                'today' => $today
            ]);
        }

        $currentToken = $this->generateToken($today, $interval, 0);
        
        $valid = hash_equals($currentToken, $token);
        
        // Grace period hanya berlaku jika interval <= 3 menit
        // DINONAKTIFKAN SEMENTARA:
        // if ($interval <= 30) {
        //     $prevToken = $this->generateToken($today, $interval, -1);
        //     if (hash_equals($prevToken, $token)) {
        //         $valid = true;
        //     }
        // }

        $user = auth()->user();
        $sudahAbsensi = false;
        if ($valid && $user) {
            $sudahAbsensi = AbsensiGuru::where('guru_id', $user->id)->where('tanggal', $today)->exists();
            if ($sudahAbsensi) {
                return redirect()->route('guru.riwayat-absensi')->with('warning', 'Anda sudah mencatat kehadiran hari ini.');
            }
        }

        return view('absensi.scan', compact('valid', 'token', 'sudahAbsensi', 'today'));
    }

    public function scanProcess(Request $request, string $token)
    {
        $today = Carbon::today()->toDateString();
        $interval = (int) Setting::get('qr_interval', 5);

        $currentToken = $this->generateToken($today, $interval, 0);
        
        $isValid = hash_equals($currentToken, $token);
        
        // Grace period hanya berlaku jika interval <= 3 menit
        // DINONAKTIFKAN SEMENTARA:
        // if ($interval <= 3) {
        //     $prevToken = $this->generateToken($today, $interval, -1);
        //     if (hash_equals($prevToken, $token)) {
        //         $isValid = true;
        //     }
        // }

        if (!$isValid) {
            return back()->with('error', 'QR Code sudah kadaluarsa.');
        }

        $user = auth()->user();
        $sudah = AbsensiGuru::where('guru_id', $user->id)->where('tanggal', $today)->exists();
        if ($sudah) return redirect()->route('guru.riwayat-absensi')->with('warning', 'Anda sudah absensi.');

        $jamMasuk = Carbon::now();
        $batasHadir = Setting::get('qr_end_time', '08:00');
        $batas = Carbon::parse($today . ' ' . $batasHadir);
        $status = $jamMasuk->lte($batas) ? 'hadir' : 'terlambat';

        $tahunAktif = \App\Models\TahunAjaran::where('status', 'aktif')->first();

        AbsensiGuru::create([
            'guru_id' => $user->id,
            'tahun_ajaran_id' => $tahunAktif?->id,
            'tanggal' => $today,
            'jam_masuk' => $jamMasuk->format('H:i:s'),
            'status' => $status,
        ]);

        return redirect()->route('guru.riwayat-absensi')->with('success', "Absensi berhasil dicatat sebagai " . ucfirst($status));
    }

    private function generateTimedQrData($tanggal, $interval, $isActive, $isWithinTime, $startTime, $endTime, $size = 200)
    {
        if (!$isActive || !$isWithinTime) {
            return [
                'qrcode' => null,
                'token' => null,
                'scanUrl' => null,
                'expiresIn' => 0,
                'message' => !$isActive ? 'Sesi Dimatikan Manual' : 'Di Luar Jam Operasional',
                'subMessage' => !$isActive ? 'Admin menonaktifkan presensi' : "Hanya aktif: $startTime - $endTime"
            ];
        }

        $token = $this->generateToken($tanggal, $interval);
        $scanUrl = route('absensi.scan', ['token' => $token]);
        $qrcode = QrCode::size($size)->margin(1)->generate($scanUrl)->toHtml();

        $now = time();
        $secondsInInterval = $interval * 60;
        $nextExpiryTimestamp = (floor($now / $secondsInInterval) + 1) * $secondsInInterval;
        $expiresInSeconds = $nextExpiryTimestamp - $now;

        return [
            'qrcode' => $qrcode,
            'token' => $token,
            'scanUrl' => $scanUrl,
            'expiresIn' => $expiresInSeconds,
            'message' => 'Sesi Aktif',
            'subMessage' => 'QR berganti otomatis'
        ];
    }

    private function generateToken(string $tanggal, int $interval, int $offset = 0): string
    {
        $secondsInInterval = $interval * 60;
        $timeSlot = floor(time() / $secondsInInterval) + $offset;
        return hash_hmac('sha256', 'presensi-v2-' . $tanggal . $timeSlot, config('app.key'));
    }
}
