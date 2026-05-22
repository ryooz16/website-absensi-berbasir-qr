<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiGuru extends Model
{
    protected $table = 'absensi_guru';

    protected $fillable = [
        'guru_id',
        'tahun_ajaran_id',
        'tanggal',
        'jam_masuk',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // RELASI GURU
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
