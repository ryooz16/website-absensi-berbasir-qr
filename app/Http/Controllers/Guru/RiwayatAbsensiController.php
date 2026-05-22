<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RiwayatAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user();
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        if (!$tahunAktif) {
            return redirect()->route('guru.dashboard')->with('error', 'Tahun ajaran aktif belum ditentukan.');
        }

        // Default: bulan ini
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        // Build date range from month/year
        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->toDateString();

        // Fetch attendance records
        $riwayat = AbsensiGuru::where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'desc')
            ->get();

        // Statistics
        $stats = [
            'hadir' => $riwayat->where('status', 'hadir')->count(),
            'terlambat' => $riwayat->where('status', 'terlambat')->count(),
            'izin' => $riwayat->where('status', 'izin')->count(),
            'sakit' => $riwayat->where('status', 'sakit')->count(),
            'alpha' => $riwayat->where('status', 'alpha')->count(),
            'total' => $riwayat->count(),
        ];

        // Check today's attendance
        $absensiHariIni = AbsensiGuru::where('guru_id', $guru->id)
            ->where('tanggal', Carbon::today()->toDateString())
            ->first();

        // Generate month options for filter
        $bulanList = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulanList[$i] = Carbon::createFromDate(null, $i, 1)->translatedFormat('F');
        }

        // Generate year options (current year ± 1)
        $tahunList = range(date('Y') - 1, date('Y') + 1);

        return view('guru.riwayat-absensi', compact(
            'guru',
            'tahunAktif',
            'riwayat',
            'stats',
            'absensiHariIni',
            'bulan',
            'tahun',
            'bulanList',
            'tahunList',
            'startDate',
            'endDate'
        ));
    }
}
