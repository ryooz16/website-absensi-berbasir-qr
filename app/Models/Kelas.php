<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
    ];

    /**
     * Relasi ke Siswa (Hanya yang AKTIF di tahun ajaran ini)
     */
    public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'siswa_kelas', 'kelas_id', 'siswa_id')
                    ->withPivot('id', 'status')
                    ->wherePivot('status', 'aktif')
                    ->withTimestamps();
    }

    /**
     * Relasi ke history penempatan siswa (Termasuk yang sudah nonaktif/alumni)
     */
    public function historySiswa()
    {
        return $this->belongsToMany(Siswa::class, 'siswa_kelas', 'kelas_id', 'siswa_id')
                    ->withPivot('id', 'status')
                    ->withTimestamps();
    }

    /**
     * Accessor untuk jumlah siswa aktif
     */
    public function getJumlahSiswaAttribute()
    {
        return $this->siswa()->count();
    }

    /**
     * Relasi ke Wali Kelas
     */
    public function waliKelas()
    {
        return $this->hasMany(WaliKelas::class, 'kelas_id');
    }

    public function waliAktif()
    {
        return $this->hasOne(WaliKelas::class, 'kelas_id')->where('status', 'aktif');
    }
}