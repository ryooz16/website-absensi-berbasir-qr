<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class MapelTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return new Collection([
            [
                'nama_mapel' => 'Matematika',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'nama_mapel',
        ];
    }
}
