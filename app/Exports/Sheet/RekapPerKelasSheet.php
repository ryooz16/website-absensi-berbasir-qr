<?php

namespace App\Exports\Sheet;

use App\Models\AbsensiSiswa;
use App\Models\TahunAjaran;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RekapPerKelasSheet implements FromView, WithTitle, ShouldAutoSize
{
    protected $mapelId;
    protected $namaMapel;
    protected $clsData;
    protected $startDate;
    protected $endDate;
    protected $guruName;
    protected $tahunAjaran;

    public function __construct($mapelId, $namaMapel, $clsData, $startDate, $endDate, $guruName, $tahunAjaran)
    {
        $this->mapelId = $mapelId;
        $this->namaMapel = $namaMapel;
        $this->clsData = $clsData;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->guruName = $guruName;
        $this->tahunAjaran = $tahunAjaran;
    }

    public function title(): string
    {
        // Sheet title limit is 31 chars
        $title = $this->clsData['nama_kelas'] . '-' . $this->namaMapel;
        return substr($title, 0, 31);
    }

    public function view(): View
    {
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        
        $siswaRekap = AbsensiSiswa::where('guru_id', auth()->id())
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->where('kelas_id', $this->clsData['kelas_id'])
            ->where('mata_pelajaran_id', $this->mapelId)
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
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

        return view('exports.rekap-mengajar-sheet', [
            'namaMapel' => $this->namaMapel,
            'namaKelas' => $this->clsData['nama_kelas'],
            'totalPertemuan' => $this->clsData['total_pertemuan'],
            'siswaRekap' => $siswaRekap,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'guruName' => $this->guruName,
            'tahunAjaran' => $this->tahunAjaran
        ]);
    }
}
