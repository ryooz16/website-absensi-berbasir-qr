<?php

namespace App\Http\Controllers\WakilKepalaSekolah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
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
        [$tanggalAwal, $tanggalAkhir, $periodeAktif] = $this->resolvePeriode($request);
        $kelasId = $request->kelas_id;
        $mapelId = $request->mata_pelajaran_id;
        $search = $request->search;

        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        $queryRaw = AbsensiSiswa::with(['siswaKelas.siswa', 'kelas'])
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
            
        if ($search) {
            $queryRaw->whereHas('siswaKelas.siswa', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }
            
        if ($periodeAktif !== 'kustom' && $tahunAktif) {
            $queryRaw->where('tahun_ajaran_id', $tahunAktif->id);
        }

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
            ->where('tahun_ajaran_id', $tahunAktif?->id)
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
            'tahunAjaran' => $tahunAktif?->nama_tahun ?? '-',
            'kelas' => $kelasId ? Kelas::find($kelasId)?->nama_kelas : 'Semua Kelas',
            'mapel' => $mapelId ? MataPelajaran::find($mapelId)?->nama_mapel : 'Semua Mata Pelajaran',
        ];

        return Excel::download(new AbsensiSiswaExport($exportData), 'laporan-rekap-presensi-siswa.xlsx');
    }

    public function guruExport(Request $request)
    {
        [$tanggalAwal, $tanggalAkhir, $periodeAktif] = $this->resolvePeriode($request);
        $search = $request->search;

        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        $queryRaw = AbsensiGuru::with('guru')
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
            
        if ($search) {
            $queryRaw->whereHas('guru', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
            
        if ($periodeAktif !== 'kustom' && $tahunAktif) {
            $queryRaw->where('tahun_ajaran_id', $tahunAktif->id);
        }

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
            'tahunAjaran' => $tahunAktif?->nama_tahun ?? '-',
        ];

        return Excel::download(new AbsensiGuruExport($exportData), 'laporan-rekap-presensi-guru.xlsx');
    }
    // === LAPORAN SISWA ===
    public function siswaIndex(Request $request)
    {
        [$tanggalAwal, $tanggalAkhir, $periodeAktif] = $this->resolvePeriode($request);
        $kelasId = $request->kelas_id;
        $mapelId = $request->mata_pelajaran_id;
        $search = $request->search;

        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        // 1. Base Query untuk rentang tanggal
        $queryRaw = AbsensiSiswa::with(['mataPelajaran', 'guru', 'siswaKelas.siswa', 'kelas'])
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
            
        if ($search) {
            $queryRaw->whereHas('siswaKelas.siswa', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }
            
        if ($periodeAktif !== 'kustom' && $tahunAktif) {
            $queryRaw->where('tahun_ajaran_id', $tahunAktif->id);
        }

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

        return view('wakepsek.laporan.siswa', compact(
            'tanggalAwal', 'tanggalAkhir', 'periodeAktif', 'search', 'kelasId', 'mapelId',
            'kpiStats', 'rekapSiswa',
            'semuaKelas', 'semuaMapel', 'semuaTahunAjaran', 'tahunAktif'
        ));
    }

    // === LAPORAN GURU ===
    public function guruIndex(Request $request)
    {
        [$tanggalAwal, $tanggalAkhir, $periodeAktif] = $this->resolvePeriode($request);
        $search = $request->search;

        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        $queryRaw = AbsensiGuru::with('guru')
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
            
        if ($search) {
            $queryRaw->whereHas('guru', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
            
        if ($periodeAktif !== 'kustom' && $tahunAktif) {
            $queryRaw->where('tahun_ajaran_id', $tahunAktif->id);
        }

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

        return view('wakepsek.laporan.guru', compact(
            'tanggalAwal', 'tanggalAkhir', 'periodeAktif', 'search',
            'kpiStats', 'rekapGuru',
            'semuaTahunAjaran', 'tahunAktif'
        ));
    }

    private function resolvePeriode(Request $request)
    {
        $periode = $request->periode ?? 'hari_ini';
        $tanggalAwal = date('Y-m-d');
        $tanggalAkhir = date('Y-m-d');

        if ($periode === 'hari_ini') {
            $tanggalAwal = Carbon::today()->format('Y-m-d');
            $tanggalAkhir = Carbon::today()->format('Y-m-d');
        } elseif ($periode === 'minggu_ini') {
            $tanggalAwal = Carbon::now()->startOfWeek()->format('Y-m-d');
            $tanggalAkhir = Carbon::now()->endOfWeek()->format('Y-m-d');
        } elseif ($periode === 'bulan_ini') {
            $tanggalAwal = Carbon::now()->startOfMonth()->format('Y-m-d');
            $tanggalAkhir = Carbon::now()->endOfMonth()->format('Y-m-d');
        } elseif ($periode === 'tahun_ajaran') {
            $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
            if ($tahunAktif) {
                $tanggalAwal = $tahunAktif->tanggal_mulai ?? date('Y-m-01');
                $tanggalAkhir = $tahunAktif->tanggal_selesai ?? date('Y-m-t');
            }
        } elseif ($periode === 'kustom') {
            $tanggalAwal = $request->tanggal_awal ?? date('Y-m-d');
            $tanggalAkhir = $request->tanggal_akhir ?? date('Y-m-d');
        }

        return [$tanggalAwal, $tanggalAkhir, $periode];
    }
}
