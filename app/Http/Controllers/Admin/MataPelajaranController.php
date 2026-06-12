<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use App\Exports\MapelExport;
use App\Imports\MapelImport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $query = MataPelajaran::query();

        if ($request->search) {
            $query->where('nama_mapel', 'like', '%' . $request->search . '%');
        }

        $mapel = $query->orderBy('nama_mapel', 'asc')->get();
        return view('admin.mapel.index', compact('mapel'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|unique:mata_pelajaran',
        ]);

        MataPelajaran::create([
            'nama_mapel' => ucwords(strtolower(trim($request->nama_mapel)))
        ]);

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan');
    }



    public function update(Request $request, MataPelajaran $mapel)
    {
        $request->validate([
            'nama_mapel' => 'required|unique:mata_pelajaran,nama_mapel,' . $mapel->id,
        ]);

        $mapel->update([
            'nama_mapel' => ucwords(strtolower(trim($request->nama_mapel)))
        ]);

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil diupdate');
    }

    public function destroy(MataPelajaran $mapel)
    {
        $mapel->delete();

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil dihapus');
    }

    public function export()
    {
        return Excel::download(new MapelExport, 'data_mapel.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $import = new MapelImport;
        Excel::import($import, $request->file('file'));

        $message = "Berhasil: $import->successCount data diimpor.";
        if (count($import->failures) > 0) {
            $message .= " Gagal: " . count($import->failures) . " data (sudah ada).";
            return redirect()->route('admin.mapel.index')->with('success', $message)->with('import_failures', $import->failures);
        }

        return redirect()->route('admin.mapel.index')->with('success', $message);
    }

    public function downloadTemplate()
    {
        $response = new StreamedResponse(function() {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['nama_mapel']);
            fputcsv($handle, ['Matematika']);
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="template_mapel.csv"');

        return $response;
    }
}