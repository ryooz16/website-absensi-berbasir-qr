<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $guru = auth()->user();
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        $waliKelas = $guru->waliKelas()->where('tahun_ajaran_id', $tahunAktif?->id)->first();
        $kelasWali = $waliKelas ? $waliKelas->kelas : null;

        return view('guru.dashboard', compact('guru', 'tahunAktif', 'kelasWali'));
    }
}
