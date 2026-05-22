<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapMengajarExport;

class RekapAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        if (!$tahunAktif) {
            return redirect()->route('guru.dashboard')->with('error', 'Tahun ajaran aktif belum ditentukan.');
        }

        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate = $request->end_date ?? date('Y-m-t');

        // Get unique class + subject combinations
        $rawRekap = AbsensiSiswa::where('guru_id', auth()->id())
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->select('kelas_id', 'mata_pelajaran_id')
            ->distinct()
            ->with(['kelas', 'mataPelajaran'])
            ->get();

        // Group by Subject
        $rekap = $rawRekap->groupBy('mata_pelajaran_id')->map(function ($items) use ($tahunAktif, $startDate, $endDate) {
            $first = $items->first();
            return [
                'mata_pelajaran_id' => $first->mata_pelajaran_id,
                'nama_mapel' => $first->mataPelajaran->nama_mapel,
                'classes' => $items->map(function ($item) use ($tahunAktif, $startDate, $endDate) {
                    return [
                        'kelas_id' => $item->kelas_id,
                        'nama_kelas' => $item->kelas->nama_kelas,
                        'total_pertemuan' => AbsensiSiswa::where('guru_id', auth()->id())
                            ->where('tahun_ajaran_id', $tahunAktif->id)
                            ->where('kelas_id', $item->kelas_id)
                            ->where('mata_pelajaran_id', $item->mata_pelajaran_id)
                            ->whereBetween('tanggal', [$startDate, $endDate])
                            ->distinct('tanggal')
                            ->count('tanggal')
                    ];
                })
            ];
        });

        return view('guru.rekap-absensi.index', compact('rekap', 'tahunAktif', 'startDate', 'endDate'));
    }

    public function show(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate = $request->end_date ?? date('Y-m-t');
        
        $siswaRekap = AbsensiSiswa::where('guru_id', auth()->id())
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->where('kelas_id', $request->kelas_id)
            ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->with(['siswaKelas.siswa'])
            ->get()
            ->groupBy('siswa_kelas_id')
            ->map(function($items) {
                $first = $items->first();
                return [
                    'nama' => $first->siswaKelas->siswa->nama ?? 'Unknown',
                    'nis' => $first->siswaKelas->siswa->nis ?? '-',
                    'hadir' => $items->where('status', 'hadir')->count(),
                    'sakit' => $items->where('status', 'sakit')->count(),
                    'izin' => $items->where('status', 'izin')->count(),
                    'alpha' => $items->where('status', 'alpha')->count(),
                    'total' => $items->count()
                ];
            })->values();

        return response()->json([
            'success' => true,
            'data' => $siswaRekap
        ]);
    }

    public function export(Request $request)
    {
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        if (!$tahunAktif) return abort(404);

        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate = $request->end_date ?? date('Y-m-t');

        $rawRekap = AbsensiSiswa::where('guru_id', auth()->id())
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->select('kelas_id', 'mata_pelajaran_id')
            ->distinct()
            ->with(['kelas', 'mataPelajaran'])
            ->get();

        $rekapData = $rawRekap->groupBy('mata_pelajaran_id')->map(function ($items) use ($tahunAktif, $startDate, $endDate) {
            $first = $items->first();
            return [
                'mata_pelajaran_id' => $first->mata_pelajaran_id,
                'nama_mapel' => $first->mataPelajaran->nama_mapel,
                'classes' => $items->map(function ($item) use ($tahunAktif, $startDate, $endDate) {
                    return [
                        'kelas_id' => $item->kelas_id,
                        'nama_kelas' => $item->kelas->nama_kelas,
                        'total_pertemuan' => AbsensiSiswa::where('guru_id', auth()->id())
                            ->where('tahun_ajaran_id', $tahunAktif->id)
                            ->where('kelas_id', $item->kelas_id)
                            ->where('mata_pelajaran_id', $item->mata_pelajaran_id)
                            ->whereBetween('tanggal', [$startDate, $endDate])
                            ->distinct('tanggal')
                            ->count('tanggal')
                    ];
                })
            ];
        });

        $fileName = 'laporan-mengajar-' . str_replace(' ', '-', strtolower(auth()->user()->name)) . '.xlsx';

        return Excel::download(new RekapMengajarExport(
            $rekapData,
            $startDate,
            $endDate,
            auth()->user()->name,
            $tahunAktif->nama_tahun
        ), $fileName);
    }
}
