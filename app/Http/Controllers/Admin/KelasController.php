<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\SiswaKelas;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaKelasImport;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Models\TahunAjaran;

class KelasController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        // Natural Sorting: Urutkan berdasarkan angka di depan secara matematis
        $allKelas = Kelas::withCount(['siswa', 'historySiswa'])
            ->with('waliAktif.guru')
            ->orderByRaw('CAST(nama_kelas AS UNSIGNED) ASC')
            ->orderBy('nama_kelas', 'ASC')
            ->get();

        $kelasAktif = $allKelas->where('siswa_count', '>', 0);
        $kelasKosong = $allKelas->where('siswa_count', 0);
        
        $tahunAjarans = TahunAjaran::orderBy('nama_tahun', 'desc')->get();

        return view('admin.kelas.index', compact('kelasAktif', 'kelasKosong', 'tahunAktif', 'tahunAjarans'));
    }

    private function formatNamaKelas($nama)
    {
        // Hilangkan spasi berlebih
        $nama = trim($nama);
        
        // Pola 1: Jika input adalah '71' atau '82' (dua digit angka) -> jadi '7-1'
        if (preg_match('/^([7-9])([0-9])$/', $nama, $matches)) {
            return $matches[1] . '-' . $matches[2];
        }

        // Pola 2: Jika input adalah '7 1' atau '7  1' -> jadi '7-1'
        if (preg_match('/^([7-9])\s+([0-9])$/', $nama, $matches)) {
            return $matches[1] . '-' . $matches[2];
        }

        return $nama;
    }

    public function store(Request $request)
    {
        $nama_formatted = $this->formatNamaKelas($request->nama_kelas);
        
        // Simpan ke request agar validasi menggunakan nama yang sudah diformat
        $request->merge(['nama_kelas' => $nama_formatted]);

        $request->validate(['nama_kelas' => 'required|unique:kelas']);
        
        Kelas::create($request->all());
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas ' . $nama_formatted . ' berhasil ditambahkan');
    }

    public function show(Kelas $kela)
    {
        // Ambil siswa yang sedang aktif di kelas ini dan urutkan sesuai abjad
        $siswaAktif = $kela->siswa()->orderBy('nama')->get();

        // Ambil siswa yang BELUM punya kelas aktif (untuk form assign manual)
        $siswaTersedia = Siswa::whereDoesntHave('kelasAktif')->orderBy('nama')->get();

        return view('admin.kelas.show', compact('kela', 'siswaAktif', 'siswaTersedia'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $nama_formatted = $this->formatNamaKelas($request->nama_kelas);
        $request->merge(['nama_kelas' => $nama_formatted]);

        $request->validate(['nama_kelas' => 'required|unique:kelas,nama_kelas,' . $kela->id]);
        
        $kela->update($request->all());
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diupdate menjadi ' . $nama_formatted);
    }

    public function destroy(Kelas $kela)
    {
        // Proteksi: Cek apakah kelas ini punya history penempatan siswa
        if ($kela->historySiswa()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal! Kelas ' . $kela->nama_kelas . ' tidak bisa dihapus karena memiliki data historis siswa.');
        }

        $kela->delete();
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas ' . $kela->nama_kelas . ' berhasil dihapus');
    }

    /**
     * Assign banyak siswa sekaligus ke kelas
     */
    public function assignSiswa(Request $request, Kelas $kela)
    {
        $request->validate([
            'siswa_ids' => 'required|array',
            'siswa_ids.*' => 'exists:siswa,id'
        ]);

        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        foreach ($request->siswa_ids as $siswaId) {
            // Pastikan tidak double aktif (double check)
            SiswaKelas::where('siswa_id', $siswaId)->where('status', 'aktif')->update(['status' => 'nonaktif']);

            SiswaKelas::create([
                'siswa_id' => $siswaId,
                'kelas_id' => $kela->id,
                'tahun_ajaran_id' => $tahunAktif?->id,
                'status' => 'aktif'
            ]);
        }

        return back()->with('success', count($request->siswa_ids) . ' siswa berhasil dimasukkan ke kelas ' . $kela->nama_kelas);
    }

    public function removeSiswa(Request $request, Kelas $kela, Siswa $siswa)
    {
        $siswaKelas = SiswaKelas::where('siswa_id', $siswa->id)
                  ->where('kelas_id', $kela->id)
                  ->where('status', 'aktif')
                  ->first();

        if ($siswaKelas) {
            // Hapus histori absensi siswa ini di kelas tersebut
            \App\Models\AbsensiSiswa::where('siswa_kelas_id', $siswaKelas->id)->delete();
            
            // Hapus relasi siswa dengan kelas ini sepenuhnya
            $siswaKelas->delete();
        }

        return back()->with('success', 'Siswa berhasil dikeluarkan dari kelas dan histori absensinya di kelas ini telah dihapus.');
    }

    /**
     * Reset Tahun Ajaran: Semua yang aktif jadi nonaktif
     */
    public function resetTahunAjaran(Request $request)
    {
        $request->validate([
            'nama_tahun_baru' => 'required|string|max:20',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        DB::transaction(function() use ($request) {
            // 1. Nonaktifkan tahun ajaran yang sedang aktif
            TahunAjaran::where('status', 'aktif')->update(['status' => 'nonaktif']);

            // 2. Buat tahun ajaran baru dan set aktif
            TahunAjaran::create([
                'nama_tahun' => $request->nama_tahun_baru,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status' => 'aktif'
            ]);

            // 3. Nonaktifkan semua penempatan siswa (agar semua kelas jadi kosong)
            SiswaKelas::where('status', 'aktif')->update(['status' => 'nonaktif']);

            // 4. Nonaktifkan semua Wali Kelas (agar bisa di-assign ulang untuk periode baru)
            \App\Models\WaliKelas::where('status', 'aktif')->update(['status' => 'nonaktif']);
        });

        return redirect()->route('admin.kelas.index')->with('success', 'Berhasil! Tahun Ajaran ' . $request->nama_tahun_baru . ' telah dimulai. Semua kelas sekarang kosong.');
    }

    /**
     * Import Siswa via Excel
     */
    public function importSiswa(Request $request, Kelas $kela)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

        try {
            Excel::import(new SiswaKelasImport($kela->id), $request->file('file'));
            return back()->with('success', 'Data siswa berhasil diimport ke kelas ' . $kela->nama_kelas);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * Bulk Import Siswa ke Banyak Kelas Sekaligus
     */
    public function bulkImport(Request $request)
    {
        $imports = $request->file('imports');
        
        if (!$imports || !is_array($imports)) {
            return redirect()->back()->with('error', 'Tidak ada file yang dipilih untuk diimpor.');
        }

        $successCount = 0;
        $errorCount = 0;

        foreach ($imports as $kelasId => $file) {
            try {
                $kelas = Kelas::findOrFail($kelasId);
                Excel::import(new SiswaKelasImport($kelas->id), $file);
                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
            }
        }

        if ($errorCount > 0) {
            return redirect()->route('admin.kelas.index')
                             ->with('success', $successCount . ' kelas berhasil diimpor.')
                             ->with('error', $errorCount . ' kelas gagal diimpor. Periksa format file Anda.');
        }

        return redirect()->route('admin.kelas.index')
                         ->with('success', 'Berhasil! ' . $successCount . ' kelas telah diperbarui dengan data siswa baru.');
    }

    /**
     * Download Template Excel (XLSX)
     */
    public function downloadTemplate()
    {
        $data = [
            ['nis', 'nama'],
            ['12345', 'Nama Siswa Contoh'],
        ];

        return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromCollection {
            protected $data;
            public function __construct($data) { $this->data = $data; }
            public function collection() { return collect($this->data); }
        }, 'template_import_siswa.xlsx');
    }
}
