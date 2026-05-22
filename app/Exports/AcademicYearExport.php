<?php

namespace App\Exports;

use App\Models\TahunAjaran;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use App\Exports\Sheet\ArsipSiswaSheet;
use App\Exports\Sheet\ArsipGuruSheet;

class AcademicYearExport implements WithMultipleSheets
{
    protected $tahun;

    public function __construct(TahunAjaran $tahun)
    {
        $this->tahun = $tahun;
    }

    public function sheets(): array
    {
        return [
            new ArsipSiswaSheet($this->tahun),
            new ArsipGuruSheet($this->tahun),
        ];
    }
}
