<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\SiswaKelas;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Facades\DB;

use App\Models\TahunAjaran;

class SiswaKelasImport implements OnEachRow, WithHeadingRow
{
    protected $kelasId;
    public $importedCount = 0;

    public function __construct($kelasId)
    {
        $this->kelasId = $kelasId;
    }

    public function onRow(Row $row)
    {
        $data = $row->toArray();
        
        // Mendukung berbagai kemungkinan penulisan header
        $nis = $data['nis'] ?? $data['NIS'] ?? null;
        $nama = $data['nama'] ?? $data['NAMA'] ?? $data['nama_siswa'] ?? null;

        if (empty($nis) || empty($nama)) {
            return;
        }

        DB::transaction(function () use ($nis, $nama) {
            // Ambil tahun ajaran yang sedang aktif
            $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
            
            if (!$tahunAktif) {
                // Fallback jika tidak ada yang aktif (seharusnya tidak terjadi)
                $tahunAktif = TahunAjaran::create(['nama_tahun' => date('Y') . '/' . (date('Y') + 1), 'status' => 'aktif']);
            }

            // 1. Simpan ke TABEL SISWA
            $siswa = Siswa::updateOrCreate(
                ['nis' => trim($nis)],
                ['nama' => trim($nama)]
            );

            // 2. Nonaktifkan status aktif siswa di kelas lain
            SiswaKelas::where('siswa_id', $siswa->id)
                      ->where('status', 'aktif')
                      ->update(['status' => 'nonaktif']);

            // 3. Masukkan ke kelas tujuan (tabel pivot)
            SiswaKelas::updateOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $this->kelasId,
                    'tahun_ajaran_id' => $tahunAktif->id,
                ],
                [
                    'status' => 'aktif'
                ]
            );

            $this->importedCount++;
        });
    }
}
