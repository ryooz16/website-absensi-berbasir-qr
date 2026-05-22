<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\Kelas;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with(['kelasAktif', 'kelasTerakhir', 'historyKelas']);

        // Filter Pencarian (Nama / NIS)
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Kelas
        if ($request->kelas_id) {
            $query->whereHas('kelasAktif', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        // Filter Status
        if ($request->status) {
            if ($request->status === 'aktif') {
                $query->has('kelasAktif');
            } elseif ($request->status === 'non-aktif') {
                $query->doesntHave('kelasAktif');
            }
        }

        // Sorting: Siswa Aktif dulu (Urut Kelas), lalu Non-Aktif
        $query->leftJoin('siswa_kelas', function($join) {
                  $join->on('siswa.id', '=', 'siswa_kelas.siswa_id')
                       ->where('siswa_kelas.status', '=', 'aktif');
              })
              ->leftJoin('kelas', 'siswa_kelas.kelas_id', '=', 'kelas.id')
              ->select('siswa.*')
              ->orderByRaw('CASE WHEN kelas.id IS NULL THEN 1 ELSE 0 END') // 0 = Aktif, 1 = Non-Aktif
              ->orderByRaw('CAST(kelas.nama_kelas AS UNSIGNED) ASC')      // Urutan kelas 7, 8, 9
              ->orderBy('kelas.nama_kelas', 'asc')                       // Sub-urutan (7A, 7B, dst)
              ->orderBy('siswa.nama', 'asc');                            // Urutan nama

        $siswa = $query->get();
        
        // Data untuk Stats & Dropdown Filter
        $totalSiswa = Siswa::count();
        $siswaAktif = Siswa::has('kelasAktif')->count();
        $siswaNonAktif = $totalSiswa - $siswaAktif;
        $allKelas = Kelas::orderByRaw('CAST(nama_kelas AS UNSIGNED) ASC')->orderBy('nama_kelas', 'asc')->get();

        // Hitung statistik pembersihan siswa lulus
        $graduatedStudents = Siswa::whereDoesntHave('kelasAktif')
            ->whereHas('kelasTerakhir', function ($q) {
                $q->where('nama_kelas', 'like', '9%');
            })
            ->get();

        $graduatedCount = $graduatedStudents->count();
        $eligibleCount = 0;
        $unfrozenYearsNeeded = collect();

        if ($graduatedCount > 0) {
            $studentIds = $graduatedStudents->pluck('id');
            
            // Ambil data tahun ajaran dari penempatan kelas
            $siswaYears = \App\Models\SiswaKelas::whereIn('siswa_id', $studentIds)
                ->with('tahunAjaran')
                ->get()
                ->groupBy('siswa_id');

            foreach ($graduatedStudents as $student) {
                $enrollments = $siswaYears->get($student->id) ?? collect();
                $years = $enrollments->map(fn($e) => $e->tahunAjaran)->filter()->unique('id');

                $unfrozenForStudent = $years->filter(fn($ta) => is_null($ta->path_arsip));

                if ($unfrozenForStudent->isEmpty()) {
                    $eligibleCount++;
                } else {
                    foreach ($unfrozenForStudent as $ta) {
                        $unfrozenYearsNeeded->push($ta->nama_tahun);
                    }
                }
            }
        }

        $unfrozenYearsNeeded = $unfrozenYearsNeeded->unique()->values();

        return view('admin.siswa.index', compact(
            'siswa', 'totalSiswa', 'siswaAktif', 'siswaNonAktif', 'allKelas',
            'graduatedCount', 'eligibleCount', 'unfrozenYearsNeeded'
        ));
    }



    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:siswa',
            'nama' => 'required'
        ]);

        Siswa::create($request->all());

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan');
    }



    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nis' => 'required|unique:siswa,nis,' . $siswa->id,
            'nama' => 'required'
        ]);

        $siswa->update($request->all());

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil diperbarui');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil dihapus');
    }

    public function cleanGraduated(Request $request)
    {
        $graduatedStudents = Siswa::whereDoesntHave('kelasAktif')
            ->whereHas('kelasTerakhir', function ($q) {
                $q->where('nama_kelas', 'like', '9%');
            })
            ->get();

        $deletedCount = 0;
        $skippedCount = 0;

        if ($graduatedStudents->count() > 0) {
            $studentIds = $graduatedStudents->pluck('id');

            // Ambil semua data tahun ajaran keaktifan dari semua siswa lulus ini
            $siswaYears = \App\Models\SiswaKelas::whereIn('siswa_id', $studentIds)
                ->with('tahunAjaran')
                ->get()
                ->groupBy('siswa_id');

            $toDeleteIds = [];

            foreach ($graduatedStudents as $student) {
                $enrollments = $siswaYears->get($student->id) ?? collect();
                $years = $enrollments->map(fn($e) => $e->tahunAjaran)->filter()->unique('id');

                $unfrozenForStudent = $years->filter(fn($ta) => is_null($ta->path_arsip));

                if ($unfrozenForStudent->isEmpty()) {
                    $toDeleteIds[] = $student->id;
                } else {
                    $skippedCount++;
                }
            }

            if (count($toDeleteIds) > 0) {
                \Illuminate\Support\Facades\DB::transaction(function () use ($toDeleteIds, &$deletedCount) {
                    $deletedCount = Siswa::whereIn('id', $toDeleteIds)->delete();
                });
            }
        }

        if ($deletedCount > 0) {
            $message = 'Berhasil membersihkan ' . $deletedCount . ' data siswa lulus beserta riwayat absensinya.';
            if ($skippedCount > 0) {
                $message .= ' (' . $skippedCount . ' siswa dilewati karena arsip tahun ajaran lamanya belum dibekukan).';
            }
            return redirect()->route('admin.siswa.index')->with('success', $message);
        }

        return redirect()->route('admin.siswa.index')->with('error', 'Tidak ada data siswa lulus yang dapat dibersihkan. Pastikan Anda sudah membekukan arsip tahun ajaran lamanya.');
    }
}