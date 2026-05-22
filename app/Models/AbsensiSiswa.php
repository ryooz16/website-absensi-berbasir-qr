<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiSiswa extends Model
{
    
    protected $fillable = ['siswa_kelas_id', 'guru_id', 'tahun_ajaran_id', 'kelas_id', 'mata_pelajaran_id', 'tanggal', 'status'];
     protected $table = 'absensi_siswa';

    protected $casts = [
        'tanggal' => 'date',
    ];
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function siswaKelas()
    {
        return $this->belongsTo(SiswaKelas::class, 'siswa_kelas_id');
    }
}
