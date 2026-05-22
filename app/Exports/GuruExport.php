<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GuruExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::where('role', 'guru')->get();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
        ];
    }

    public function map($guru): array
    {
        return [
            $guru->name,
            $guru->email,
        ];
    }
}
