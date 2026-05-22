<?php

namespace App\Exports\Sheet;

use App\Models\Kelas;
use App\Models\SiswaKelas;
use App\Models\AbsensiSiswa;
use App\Models\WaliKelas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ArsipSiswaSheet implements FromView, WithTitle, ShouldAutoSize
{
    protected $tahun;

    public function __construct($tahun)
    {
        $this->tahun = $tahun;
    }

    public function title(): string
    {
        return 'Rekap Absensi Siswa';
    }

    public function view(): View
    {
        // Ambil semua kelas yang ada di tahun ajaran tersebut
        $kelasIds = SiswaKelas::where('tahun_ajaran_id', $this->tahun->id)
            ->distinct()
            ->pluck('kelas_id');
            
        $dataKelas = Kelas::whereIn('id', $kelasIds)
            ->orderByRaw('CAST(nama_kelas AS UNSIGNED) ASC')
            ->orderBy('nama_kelas', 'asc')
            ->get()
            ->map(function($kelas) {
                // Wali Kelas
                $wali = WaliKelas::where('kelas_id', $kelas->id)
                    ->where('tahun_ajaran_id', $this->tahun->id)
                    ->with('guru')
                    ->first();
                
                // Data Siswa & Rekap
                $siswas = SiswaKelas::where('kelas_id', $kelas->id)
                    ->where('tahun_ajaran_id', $this->tahun->id)
                    ->with('siswa')
                    ->get()
                    ->map(function($sk) use ($kelas) {
                        $rekap = AbsensiSiswa::where('siswa_kelas_id', $sk->id)
                            ->where('tahun_ajaran_id', $this->tahun->id)
                            ->get();
                            
                        return [
                            'nis' => $sk->siswa->nis ?? '-',
                            'nama' => $sk->siswa->nama ?? 'Unknown',
                            'hadir' => $rekap->where('status', 'hadir')->count(),
                            'sakit' => $rekap->where('status', 'sakit')->count(),
                            'izin' => $rekap->where('status', 'izin')->count(),
                            'alpha' => $rekap->where('status', 'alpha')->count(),
                        ];
                    });

                return [
                    'nama_kelas' => $kelas->nama_kelas,
                    'wali_kelas' => $wali->guru->name ?? 'Belum Diatur',
                    'siswas' => $siswas
                ];
            });

        return view('exports.arsip-siswa-sheet', [
            'tahun' => $this->tahun,
            'dataKelas' => $dataKelas
        ]);
    }
}
