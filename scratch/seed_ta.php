<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TahunAjaran;
use App\Models\SiswaKelas;
use App\Models\AbsensiSiswa;
use App\Models\AbsensiGuru;

$ta = TahunAjaran::where('status', 'aktif')->first();
SiswaKelas::query()->whereNull('tahun_ajaran_id')->update(['tahun_ajaran_id' => $ta->id]);
AbsensiSiswa::query()->whereNull('tahun_ajaran_id')->update(['tahun_ajaran_id' => $ta->id]);
AbsensiGuru::query()->whereNull('tahun_ajaran_id')->update(['tahun_ajaran_id' => $ta->id]);

echo "Success: Existing data linked to " . $ta->nama_tahun;
