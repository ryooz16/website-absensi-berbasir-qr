<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AbsensiSiswa;
use App\Models\AbsensiGuru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiSiswaExport;
use App\Exports\AbsensiGuruExport;
use App\Models\TahunAjaran;

class LaporanController extends Controller
{
    // === EXPORT EXCEL ===
    public function siswaExport(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal ?? date('Y-m-d');
        $tanggalAkhir = $request->tanggal_akhir ?? date('Y-m-d');
        $kelasId = $request->kelas_id;
        $mapelId = $request->mata_pelajaran_id;

        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        $tahunAjaranId = $request->tahun_ajaran_id ?? $tahunAktif?->id;

        $queryRaw = AbsensiSiswa::with(['siswaKelas.siswa', 'kelas'])
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->where('tahun_ajaran_id', $tahunAjaranId);

        if ($kelasId) {
            $queryRaw->where('kelas_id', $kelasId);
        }
        if ($mapelId) {
            $queryRaw->where('mata_pelajaran_id', $mapelId);
        }

        $presensiRaw = $queryRaw->get();

        $rekapSiswa = [];
        foreach ($presensiRaw as $item) {
            $siswaId = $item->siswaKelas->siswa_id ?? null;
            if (!$siswaId) continue;

            if (!isset($rekapSiswa[$siswaId])) {
                $rekapSiswa[$siswaId] = [
                    'nama' => $item->siswaKelas->siswa->nama ?? 'Siswa Terhapus',
                    'kelas' => $item->kelas->nama_kelas ?? '-',
                    'kelas_id' => $item->kelas_id,
                    'hadir' => 0,
                    'sakit' => 0,
                    'izin' => 0,
                    'alpha' => 0,
                ];
            }
            $rekapSiswa[$siswaId][$item->status]++;
        }

        usort($rekapSiswa, function($a, $b) {
            $classComparison = strnatcmp($a['kelas'], $b['kelas']);
            if ($classComparison === 0) {
                return strcasecmp($a['nama'], $b['nama']);
            }
            return $classComparison;
        });

        $waliKelasList = \App\Models\WaliKelas::with('guru')
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get()
            ->keyBy('kelas_id');

        $groupedData = collect($rekapSiswa)->groupBy('kelas');
        $dataToExport = [];
        foreach ($groupedData as $kelasName => $students) {
            $kelasIdFromFirstStudent = $students->first()['kelas_id'] ?? null;
            $wali = $kelasIdFromFirstStudent ? $waliKelasList->get($kelasIdFromFirstStudent) : null;
            
            $dataToExport[] = [
                'kelas' => $kelasName,
                'wali_kelas' => $wali ? ($wali->guru->name ?? 'Guru Terhapus') : 'Belum Ditentukan',
                'students' => $students->map(function($item) { return (object)$item; })->all()
            ];
        }

        $exportData = [
            'groupedData' => collect($dataToExport),
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'tahunAjaran' => TahunAjaran::find($tahunAjaranId)?->nama_tahun ?? '-',
            'kelas' => $kelasId ? Kelas::find($kelasId)?->nama_kelas : 'Semua Kelas',
            'mapel' => $mapelId ? MataPelajaran::find($mapelId)?->nama_mapel : 'Semua Mata Pelajaran',
        ];

        return Excel::download(new AbsensiSiswaExport($exportData), 'laporan-rekap-presensi-siswa.xlsx');
    }

    public function guruExport(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal ?? date('Y-m-d');
        $tanggalAkhir = $request->tanggal_akhir ?? date('Y-m-d');

        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        $tahunAjaranId = $request->tahun_ajaran_id ?? $tahunAktif?->id;

        $queryRaw = AbsensiGuru::with('guru')
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->where('tahun_ajaran_id', $tahunAjaranId);

        $presensiRaw = $queryRaw->get();

        $rekapGuru = [];
        foreach ($presensiRaw as $item) {
            $guruId = $item->guru_id;
            if (!$guruId) continue;

            if (!isset($rekapGuru[$guruId])) {
                $rekapGuru[$guruId] = [
                    'nama' => $item->guru->name ?? 'Guru Terhapus',
                    'hadir' => 0,
                    'terlambat' => 0,
                    'sakit' => 0,
                    'izin' => 0,
                    'alpha' => 0,
                ];
            }
            $rekapGuru[$guruId][$item->status]++;
        }

        usort($rekapGuru, function($a, $b) {
            return strcasecmp($a['nama'], $b['nama']);
        });

        $data = collect($rekapGuru)->map(function ($item) {
            return (object) $item;
        });

        $exportData = [
            'data' => $data,
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'tahunAjaran' => TahunAjaran::find($tahunAjaranId)?->nama_tahun ?? '-',
        ];

        return Excel::download(new AbsensiGuruExport($exportData), 'laporan-rekap-presensi-guru.xlsx');
    }
    // === LAPORAN SISWA ===
    public function siswaIndex(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal ?? date('Y-m-d');
        $tanggalAkhir = $request->tanggal_akhir ?? date('Y-m-d');
        $kelasId = $request->kelas_id;
        $mapelId = $request->mata_pelajaran_id;

        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        $tahunAjaranId = $request->tahun_ajaran_id ?? $tahunAktif?->id;

        // 1. Base Query untuk rentang tanggal
        $queryRaw = AbsensiSiswa::with(['mataPelajaran', 'guru', 'siswaKelas.siswa', 'kelas'])
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->where('tahun_ajaran_id', $tahunAjaranId);

        if ($kelasId) {
            $queryRaw->where('kelas_id', $kelasId);
        }
        if ($mapelId) {
            $queryRaw->where('mata_pelajaran_id', $mapelId);
        }

        $presensiRaw = $queryRaw->get();

        // 2. Hitung Statistik Global (KPI)
        $kpiStats = [
            'hadir' => $presensiRaw->where('status', 'hadir')->count(),
            'sakit' => $presensiRaw->where('status', 'sakit')->count(),
            'izin' => $presensiRaw->where('status', 'izin')->count(),
            'alpha' => $presensiRaw->where('status', 'alpha')->count(),
        ];
        $kpiStats['total'] = array_sum($kpiStats);
        $kpiStats['persentase_hadir'] = $kpiStats['total'] > 0 ? round(($kpiStats['hadir'] / $kpiStats['total']) * 100, 1) : 0;

        // 3. Rekapitulasi per Siswa
        $rekapSiswa = [];
        foreach ($presensiRaw as $item) {
            $siswaId = $item->siswaKelas->siswa_id ?? null;
            if (!$siswaId) continue; // Jika siswa sudah terhapus tapi data presensi ada

            if (!isset($rekapSiswa[$siswaId])) {
                $rekapSiswa[$siswaId] = [
                    'nama' => $item->siswaKelas->siswa->nama ?? 'Siswa Terhapus',
                    'kelas' => $item->kelas->nama_kelas ?? '-',
                    'hadir' => 0,
                    'sakit' => 0,
                    'izin' => 0,
                    'alpha' => 0,
                ];
            }
            $rekapSiswa[$siswaId][$item->status]++;
        }

        // Urutkan rekap berdasarkan kelas, lalu nama siswa
        usort($rekapSiswa, function($a, $b) {
            $classComparison = strnatcmp($a['kelas'], $b['kelas']);
            if ($classComparison === 0) {
                return strcasecmp($a['nama'], $b['nama']);
            }
            return $classComparison;
        });

        // Data untuk Filter Dropdown
        $semuaKelas = Kelas::orderBy('nama_kelas')->get();
        $semuaMapel = MataPelajaran::orderBy('nama_mapel')->get();
        $semuaTahunAjaran = TahunAjaran::orderByDesc('nama_tahun')->get();

        return view('admin.laporan.siswa', compact(
            'tanggalAwal', 'tanggalAkhir', 'kelasId', 'mapelId', 'tahunAjaranId',
            'kpiStats', 'rekapSiswa',
            'semuaKelas', 'semuaMapel', 'semuaTahunAjaran', 'tahunAktif'
        ));
    }

    // === LAPORAN GURU ===
    public function guruIndex(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal ?? date('Y-m-d');
        $tanggalAkhir = $request->tanggal_akhir ?? date('Y-m-d');

        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        $tahunAjaranId = $request->tahun_ajaran_id ?? $tahunAktif?->id;

        $queryRaw = AbsensiGuru::with('guru')
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->where('tahun_ajaran_id', $tahunAjaranId);

        $presensiRaw = $queryRaw->get();

        // Hitung Statistik Global (KPI)
        $kpiStats = [
            'hadir' => $presensiRaw->where('status', 'hadir')->count(),
            'terlambat' => $presensiRaw->where('status', 'terlambat')->count(),
            'sakit' => $presensiRaw->where('status', 'sakit')->count(),
            'izin' => $presensiRaw->where('status', 'izin')->count(),
            'alpha' => $presensiRaw->where('status', 'alpha')->count(),
        ];
        $kpiStats['total'] = array_sum($kpiStats);
        $kpiStats['persentase_hadir'] = $kpiStats['total'] > 0 ? round((($kpiStats['hadir'] + $kpiStats['terlambat']) / $kpiStats['total']) * 100, 1) : 0;

        // Rekapitulasi per Guru
        $rekapGuru = [];
        foreach ($presensiRaw as $item) {
            $guruId = $item->guru_id;
            if (!$guruId) continue;

            if (!isset($rekapGuru[$guruId])) {
                $rekapGuru[$guruId] = [
                    'nama' => $item->guru->name ?? 'Guru Terhapus',
                    'hadir' => 0,
                    'terlambat' => 0,
                    'sakit' => 0,
                    'izin' => 0,
                    'alpha' => 0,
                ];
            }
            $rekapGuru[$guruId][$item->status]++;
        }

        // Urutkan rekap berdasarkan nama guru
        usort($rekapGuru, function($a, $b) {
            return strcasecmp($a['nama'], $b['nama']);
        });

        // Data untuk Filter Dropdown
        $semuaTahunAjaran = TahunAjaran::orderByDesc('nama_tahun')->get();

        return view('admin.laporan.guru', compact(
            'tanggalAwal', 'tanggalAkhir', 'tahunAjaranId',
            'kpiStats', 'rekapGuru',
            'semuaTahunAjaran', 'tahunAktif'
        ));
    }
}