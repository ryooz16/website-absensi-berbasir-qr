<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RekapMengajarExport implements WithMultipleSheets
{
    protected $rekapData;
    protected $startDate;
    protected $endDate;
    protected $guruName;
    protected $tahunAjaran;

    public function __construct($rekapData, $startDate, $endDate, $guruName, $tahunAjaran)
    {
        $this->rekapData = $rekapData;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->guruName = $guruName;
        $this->tahunAjaran = $tahunAjaran;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->rekapData as $subject) {
            foreach ($subject['classes'] as $cls) {
                $sheets[] = new \App\Exports\Sheet\RekapPerKelasSheet(
                    $subject['mata_pelajaran_id'],
                    $subject['nama_mapel'],
                    $cls,
                    $this->startDate,
                    $this->endDate,
                    $this->guruName,
                    $this->tahunAjaran
                );
            }
        }

        return $sheets;
    }
}
