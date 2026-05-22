<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WaliKelas;
use App\Models\User;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class WaliKelasController extends Controller
{
    public function index(Request $request)
    {
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        // BACKFILL: Jika ada data lama yang belum punya tahun_ajaran_id, beri tahun aktif sekarang
        if ($tahunAktif) {
            WaliKelas::whereNull('tahun_ajaran_id')->update(['tahun_ajaran_id' => $tahunAktif->id]);
        }

        // 1. Daftar Wali Kelas (Tabel Utama) dengan Pencarian (Hanya yang AKTIF)
        $query = WaliKelas::join('kelas', 'wali_kelas.kelas_id', '=', 'kelas.id')
            ->join('users', 'wali_kelas.guru_id', '=', 'users.id')
            ->where('wali_kelas.status', 'aktif')
            ->select('wali_kelas.*');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('users.name', 'like', '%' . $request->search . '%')
                  ->orWhere('kelas.nama_kelas', 'like', '%' . $request->search . '%');
            });
        }

        $wali = $query->orderByRaw('CAST(kelas.nama_kelas AS UNSIGNED) ASC')
            ->orderBy('kelas.nama_kelas', 'ASC')
            ->with(['guru', 'kelas', 'tahunAjaran'])
            ->get();

        // 2. Guru yang BELUM punya kelas (Tersedia)
        $guruTersedia = User::where('role', 'guru')
            ->whereDoesntHave('waliKelas', function($q) {
                $q->where('status', 'aktif');
            })
            ->orderBy('name', 'asc')
            ->get();
        
        // 3. Kelas yang BELUM punya wali (Tersedia)
        $kelasTersedia = Kelas::withCount('siswa')
            ->having('siswa_count', '>', 0)
            ->whereDoesntHave('waliAktif')
            ->orderByRaw('CAST(nama_kelas AS UNSIGNED) ASC')
            ->orderBy('nama_kelas', 'ASC')
            ->get();

        return view('admin.walikelas.index', compact('wali', 'guruTersedia', 'kelasTersedia'));
    }



    public function update(Request $request, WaliKelas $walikelas)
    {
        $request->validate([
            'guru_id' => 'required',
            'kelas_id' => 'required',
            'status' => 'required',
        ]);

        // Jika status diubah ke aktif, lakukan validasi ganda
        if ($request->status == 'aktif') {
            // 1. Cek apakah Guru sudah jadi wali aktif di kelas lain
            $existingGuru = WaliKelas::where('guru_id', $request->guru_id)
                ->where('status', 'aktif')
                ->where('id', '!=', $walikelas->id)
                ->first();

            if ($existingGuru) {
                return redirect()->back()
                    ->withInput()
                    ->with('error_edit', 'Gagal! ' . $existingGuru->guru->name . ' sudah menjadi Wali Kelas aktif di kelas ' . $existingGuru->kelas->nama_kelas);
            }

            // 2. Cek apakah Kelas sudah punya wali aktif lain
            $existingKelas = WaliKelas::where('kelas_id', $request->kelas_id)
                ->where('status', 'aktif')
                ->where('id', '!=', $walikelas->id)
                ->first();

            if ($existingKelas) {
                return redirect()->back()
                    ->withInput()
                    ->with('error_edit', 'Gagal! Kelas ' . $existingKelas->kelas->nama_kelas . ' sudah memiliki Wali Kelas aktif yaitu ' . $existingKelas->guru->name);
            }
        }

        $walikelas->update([
            'guru_id' => $request->guru_id,
            'kelas_id' => $request->kelas_id,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.walikelas.index')
            ->with('success', 'Data wali kelas berhasil diupdate');
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required',
            'kelas_id' => 'required',
        ]);

        // 1. Cek apakah guru ini sudah jadi wali aktif di kelas mana pun
        $existingWali = WaliKelas::where('guru_id', $request->guru_id)
            ->where('status', 'aktif')
            ->first();

        if ($existingWali) {
            return redirect()->back()
                ->withInput()
                ->with('error_tambah', 'Gagal! ' . $existingWali->guru->name . ' sudah menjadi Wali Kelas aktif di kelas ' . $existingWali->kelas->nama_kelas);
        }

        // 2. Cek apakah kelas ini sudah punya wali aktif
        $existingKelas = WaliKelas::where('kelas_id', $request->kelas_id)
            ->where('status', 'aktif')
            ->first();

        if ($existingKelas) {
            return redirect()->back()
                ->withInput()
                ->with('error_tambah', 'Gagal! Kelas ' . $existingKelas->kelas->nama_kelas . ' sudah memiliki Wali Kelas aktif yaitu ' . $existingKelas->guru->name);
        }

        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        WaliKelas::create([
            'guru_id' => $request->guru_id,
            'kelas_id' => $request->kelas_id,
            'tahun_ajaran_id' => $tahunAktif->id ?? null,
            'status' => 'aktif'
        ]);

        return redirect()->back()->with('success', 'Wali kelas berhasil ditambahkan');
    }

    public function destroy(WaliKelas $walikelas)
    {
        $walikelas->delete();

        return redirect()->back()->with('success', 'Wali kelas dihapus');
    }
}
