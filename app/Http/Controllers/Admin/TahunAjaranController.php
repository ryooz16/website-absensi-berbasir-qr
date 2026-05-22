<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use App\Models\SiswaKelas;
use App\Models\WaliKelas;
use App\Models\AbsensiSiswa;
use App\Models\AbsensiGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjarans = TahunAjaran::orderBy('nama_tahun', 'desc')->get();
        return view('admin.tahun-ajaran.index', compact('tahunAjarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tahun' => 'required|unique:tahun_ajarans,nama_tahun',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        TahunAjaran::create([
            'nama_tahun' => $request->nama_tahun,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => 'nonaktif' 
        ]);

        return redirect()->back()->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $request->validate([
            'nama_tahun' => 'required|unique:tahun_ajarans,nama_tahun,' . $tahunAjaran->id,
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $tahunAjaran->update([
            'nama_tahun' => $request->nama_tahun,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return redirect()->back()->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function setAktif(TahunAjaran $tahunAjaran)
    {
        DB::transaction(function() use ($tahunAjaran) {
            // Nonaktifkan semua
            TahunAjaran::where('status', 'aktif')->update(['status' => 'nonaktif']);
            
            // Aktifkan yang dipilih
            $tahunAjaran->update(['status' => 'aktif']);
        });

        return redirect()->back()->with('success', 'Tahun ajaran ' . $tahunAjaran->nama_tahun . ' sekarang aktif.');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        if ($tahunAjaran->status === 'aktif') {
            return redirect()->back()->with('error', 'Gagal! Tahun ajaran yang sedang aktif tidak boleh dihapus.');
        }

        DB::transaction(function() use ($tahunAjaran) {
            // Hapus semua data terkait tahun ini (Cascade)
            AbsensiSiswa::where('tahun_ajaran_id', $tahunAjaran->id)->delete();
            AbsensiGuru::where('tahun_ajaran_id', $tahunAjaran->id)->delete();
            SiswaKelas::where('tahun_ajaran_id', $tahunAjaran->id)->delete();
            WaliKelas::where('tahun_ajaran_id', $tahunAjaran->id)->delete();
            
            // Hapus tahun ajaran itu sendiri
            $tahunAjaran->delete();
        });

        return redirect()->back()->with('success', 'Data tahun ajaran berhasil dibersihkan secara permanen.');
    }

    // Fitur Ekspor Arsip (Multi-Sheet)
    public function exportArchive(TahunAjaran $tahunAjaran)
    {
        // Jika arsip statis sudah dibekukan, download file tersebut
        if ($tahunAjaran->path_arsip && Storage::disk('local')->exists($tahunAjaran->path_arsip)) {
            $downloadName = 'ARSIP_' . str_replace('/', '-', $tahunAjaran->nama_tahun) . '.xlsx';
            return Storage::disk('local')->download($tahunAjaran->path_arsip, $downloadName);
        }

        // Jika belum dibekukan, generate secara dinamis
        $fileName = 'ARSIP_' . str_replace('/', '-', $tahunAjaran->nama_tahun) . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\AcademicYearExport($tahunAjaran), $fileName);
    }

    // Fitur Bekukan Arsip (Statis)
    public function freezeArchive(TahunAjaran $tahunAjaran)
    {
        if ($tahunAjaran->status === 'aktif') {
            return redirect()->back()->with('error', 'Gagal! Tahun ajaran aktif tidak dapat dibekukan.');
        }

        // Cek jika sudah dibekukan sebelumnya
        if ($tahunAjaran->path_arsip && Storage::disk('local')->exists($tahunAjaran->path_arsip)) {
            return redirect()->back()->with('error', 'Arsip tahun ajaran ini sudah dibekukan sebelumnya.');
        }

        try {
            $folder = 'archives';
            if (!Storage::disk('local')->exists($folder)) {
                Storage::disk('local')->makeDirectory($folder);
            }

            $fileName = $folder . '/ARSIP_' . str_replace('/', '-', $tahunAjaran->nama_tahun) . '_' . time() . '.xlsx';
            
            // Simpan file excel ke storage local
            \Maatwebsite\Excel\Facades\Excel::store(new \App\Exports\AcademicYearExport($tahunAjaran), $fileName, 'local');

            // Simpan path ke database
            $tahunAjaran->update([
                'path_arsip' => $fileName
            ]);

            return redirect()->back()->with('success', 'Berhasil membekukan arsip secara statis untuk tahun ' . $tahunAjaran->nama_tahun);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membekukan arsip: ' . $e->getMessage());
        }
    }
}
