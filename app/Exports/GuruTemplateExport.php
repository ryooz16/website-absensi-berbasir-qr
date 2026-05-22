<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class GuruTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return new Collection([
            [
                'Nama' => 'Guru Contoh',
                'Email' => 'guru@example.com',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
        ];
    }
}
