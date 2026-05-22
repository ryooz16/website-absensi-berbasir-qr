<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $siswaAktif = Siswa::has('kelasAktif')->count();
        $totalGuru = User::where('role', 'guru')->count();
        $totalKelas = Kelas::count();
        $totalMapel = MataPelajaran::count();
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'siswaAktif',
            'totalGuru',
            'totalKelas',
            'totalMapel',
            'tahunAktif'
        ));
    }
}
