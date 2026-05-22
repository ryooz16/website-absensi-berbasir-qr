<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaliKelas extends Model
{
    use HasFactory;

    protected $table = 'wali_kelas';

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'tahun_ajaran_id',
        'status',
    ];

    // RELASI GURU
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    // RELASI KELAS
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // RELASI TAHUN AJARAN
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}