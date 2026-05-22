<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\AbsensiSiswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class AbsensiSiswaController extends Controller
{
    public function index(Request $request)
    {
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $mapel = MataPelajaran::orderBy('nama_mapel')->get();

        $selectedKelas = $request->kelas_id ? Kelas::find($request->kelas_id) : null;
        $selectedMapel = $request->mapel_id ? MataPelajaran::find($request->mapel_id) : null;
        $tanggal = $request->tanggal ?? date('Y-m-d');

        $siswa = [];
        $absensi = [];

        if ($selectedKelas && $tahunAktif) {
            $siswa = $selectedKelas->siswa()
                ->orderBy('nama')
                ->get();
                
            if ($selectedMapel) {
                $absensiData = AbsensiSiswa::where('kelas_id', $selectedKelas->id)
                    ->where('mata_pelajaran_id', $selectedMapel->id)
                    ->where('tanggal', $tanggal)
                    ->get()
                    ->keyBy('siswa_kelas_id');
                    
                $absensi = $absensiData;
            }
        }

        return view('guru.absensi-siswa', compact('kelas', 'mapel', 'selectedKelas', 'selectedMapel', 'tanggal', 'siswa', 'absensi', 'tahunAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required',
            'mata_pelajaran_id' => 'required',
            'tanggal' => 'required|date',
            'status' => 'required|array',
        ]);

        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        foreach ($request->status as $siswa_kelas_id => $status) {
            AbsensiSiswa::updateOrCreate(
                [
                    'siswa_kelas_id' => $siswa_kelas_id,
                    'kelas_id' => $request->kelas_id,
                    'mata_pelajaran_id' => $request->mata_pelajaran_id,
                    'tanggal' => $request->tanggal,
                ],
                [
                    'guru_id' => auth()->id(),
                    'tahun_ajaran_id' => $tahunAktif?->id,
                    'status' => $status
                ]
            );
        }

        return redirect()->back()->with('success', 'Berhasil menyimpan absensi siswa.');
    }
}
