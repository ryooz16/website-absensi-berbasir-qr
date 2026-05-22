<?php

namespace App\Exports\Sheet;

use App\Models\User;
use App\Models\AbsensiGuru;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ArsipGuruSheet implements FromView, WithTitle, ShouldAutoSize
{
    protected $tahun;

    public function __construct($tahun)
    {
        $this->tahun = $tahun;
    }

    public function title(): string
    {
        return 'Rekap Absensi Guru';
    }

    public function view(): View
    {
        $gurus = User::where('role', 'guru')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function($guru) {
                $rekap = AbsensiGuru::where('guru_id', $guru->id)
                    ->where('tahun_ajaran_id', $this->tahun->id)
                    ->get();
                    
                return [
                    'nama' => $guru->name,
                    'hadir' => $rekap->where('status', 'hadir')->count(),
                    'terlambat' => $rekap->where('status', 'terlambat')->count(),
                    'sakit' => $rekap->where('status', 'sakit')->count(),
                    'izin' => $rekap->where('status', 'izin')->count(),
                    'alpha' => $rekap->where('status', 'alpha')->count(),
                ];
            });

        return view('exports.arsip-guru-sheet', [
            'tahun' => $this->tahun,
            'gurus' => $gurus
        ]);
    }
}
