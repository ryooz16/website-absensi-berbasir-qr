<?php

namespace App\Http\Controllers\WakilKepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $siswaAktif = Siswa::has('kelasAktif')->count();
        $totalGuru = User::where('role', 'guru')->count();
        $totalKelas = Kelas::count();
        $totalMapel = MataPelajaran::count();
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        $guruHadirHariIni = \App\Models\AbsensiGuru::whereDate('tanggal', today())
                                ->where('status', 'hadir')
                                ->count();
                                
        $siswaHadirHariIni = \App\Models\AbsensiSiswa::whereDate('tanggal', today())
                                ->where('status', 'hadir')
                                ->distinct('siswa_kelas_id')
                                ->count('siswa_kelas_id');

        return view('wakepsek.dashboard', compact(
            'totalSiswa',
            'siswaAktif',
            'totalGuru',
            'totalKelas',
            'totalMapel',
            'tahunAktif',
            'guruHadirHariIni',
            'siswaHadirHariIni'
        ));
    }
    
    public function getChartData(Request $request)
    {
        $filter = $request->get('filter', 'today');

        $query = \App\Models\AbsensiSiswa::query();

        if ($filter == 'today') {
            $query->whereDate('tanggal', today());
        } elseif ($filter == 'week') {
            $query->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter == 'month') {
            $query->whereMonth('tanggal', now()->month)
                  ->whereYear('tanggal', now()->year);
        } elseif ($filter == 'year') {
            $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
            if ($tahunAktif) {
                $mulai = $tahunAktif->tanggal_mulai ?? now()->startOfYear();
                $selesai = $tahunAktif->tanggal_selesai ?? now()->endOfYear();
                $query->whereBetween('tanggal', [$mulai, $selesai]);
            }
        }

        $stats = $query->selectRaw("kelas_id, count(*) as total, sum(case when status = 'hadir' then 1 else 0 end) as hadir")
                       ->groupBy('kelas_id')
                       ->get()
                       ->keyBy('kelas_id');

        $kelasAktif = Kelas::whereHas('siswa')->orderBy('nama_kelas')->get();

        $labels = [];
        $data = [];

        foreach ($kelasAktif as $kelas) {
            $labels[] = $kelas->nama_kelas;
            if ($stats->has($kelas->id)) {
                $stat = $stats->get($kelas->id);
                $percentage = $stat->total > 0 ? round(($stat->hadir / $stat->total) * 100, 1) : 0;
                $data[] = $percentage;
            } else {
                $data[] = 0;
            }
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
}
