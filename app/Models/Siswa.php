<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    
    protected $table = 'siswa';
    
    protected $fillable = ['nis', 'nama'];

    /**
     * Relasi ke Kelas yang sedang AKTIF
     */
    public function kelasAktif()
    {
        return $this->belongsToMany(Kelas::class, 'siswa_kelas', 'siswa_id', 'kelas_id')
                    ->using(SiswaKelas::class)
                    ->withPivot('status', 'tahun_ajaran_id')
                    ->wherePivot('status', 'aktif')
                    ->latest('siswa_kelas.id')
                    ->limit(1);
    }

    /**
     * Relasi ke Kelas terakhir (Apapun statusnya)
     */
    public function kelasTerakhir()
    {
        return $this->belongsToMany(Kelas::class, 'siswa_kelas', 'siswa_id', 'kelas_id')
                    ->using(SiswaKelas::class)
                    ->withPivot('status', 'tahun_ajaran_id')
                    ->latest('siswa_kelas.id')
                    ->limit(1);
    }

    /**
     * Relasi ke semua history kelas yang pernah diikuti
     */
    public function historyKelas()
    {
        return $this->belongsToMany(Kelas::class, 'siswa_kelas', 'siswa_id', 'kelas_id')
                    ->using(SiswaKelas::class)
                    ->withPivot('status', 'tahun_ajaran_id')
                    ->withTimestamps();
    }
}
