<?php

namespace App\Exports;

use App\Models\MataPelajaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MapelExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return MataPelajaran::select('nama_mapel')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Mata Pelajaran',
        ];
    }
}
