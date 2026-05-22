<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use App\Models\AbsensiSiswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKelasExport;

class LaporanKelasController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user();
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        $waliKelas = $guru->waliKelas()->where('tahun_ajaran_id', $tahunAktif?->id)->first();

        if (!$waliKelas) {
            abort(403, 'Anda bukan Wali Kelas pada tahun ajaran aktif ini.');
        }

        $kelas = $waliKelas->kelas;

        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate = $request->end_date ?? date('Y-m-t');

        $siswa = $kelas->siswa()
            ->orderBy('nama')
            ->get();

        $presensi = AbsensiSiswa::where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        $rekap = [];
        foreach ($siswa as $s) {
            $rekap[$s->pivot->id] = [
                'hadir' => 0,
                'sakit' => 0,
                'izin' => 0,
                'alpha' => 0,
            ];
        }

        foreach ($presensi as $ab) {
            if (isset($rekap[$ab->siswa_kelas_id][$ab->status])) {
                $rekap[$ab->siswa_kelas_id][$ab->status]++;
            }
        }

        return view('guru.laporan-kelas', compact('kelas', 'siswa', 'rekap', 'startDate', 'endDate', 'tahunAktif'));
    }

    public function export(Request $request)
    {
        $guru = auth()->user();
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        $waliKelas = $guru->waliKelas()->where('tahun_ajaran_id', $tahunAktif?->id)->first();

        if (!$waliKelas) abort(403);

        $kelas = $waliKelas->kelas;
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate = $request->end_date ?? date('Y-m-t');

        $siswa = $kelas->siswa()->orderBy('nama')->get();
        $presensi = AbsensiSiswa::where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        $rekap = [];
        foreach ($siswa as $s) {
            $rekap[$s->pivot->id] = ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0];
        }

        foreach ($presensi as $ab) {
            if (isset($rekap[$ab->siswa_kelas_id][$ab->status])) {
                $rekap[$ab->siswa_kelas_id][$ab->status]++;
            }
        }

        $fileName = 'laporan-presensi-kelas-' . str_replace(' ', '-', strtolower($kelas->nama_kelas)) . '.xlsx';

        return Excel::download(new LaporanKelasExport([
            'kelas' => $kelas,
            'guru' => $guru,
            'siswa' => $siswa,
            'rekap' => $rekap,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'tahunAjaran' => $tahunAktif->nama_tahun
        ]), $fileName);
    }
}
