<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SiswaKelas extends Pivot
{
    protected $table = 'siswa_kelas';

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
