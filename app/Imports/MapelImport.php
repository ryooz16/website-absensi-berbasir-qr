<?php

namespace App\Imports;

use App\Models\MataPelajaran;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MapelImport implements ToCollection, WithHeadingRow
{
    public $successCount = 0;
    public $failures = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nama = $row['nama_mapel'] ?? $row['nama_mata_pelajaran'] ?? null;

            if (!$nama) {
                $this->failures[] = "Nama mata pelajaran kosong.";
                continue;
            }

            // Cek duplikasi
            if (MataPelajaran::where('nama_mapel', $nama)->exists()) {
                $this->failures[] = "$nama: Mata pelajaran sudah ada.";
                continue;
            }

            MataPelajaran::create([
                'nama_mapel' => $nama,
            ]);

            $this->successCount++;
        }
    }
}
