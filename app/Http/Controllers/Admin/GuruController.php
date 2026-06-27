<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Exports\GuruExport;
use App\Imports\GuruImport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'guru');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $guru = $query->orderBy('name', 'asc')->get();
        return view('admin.guru.index', compact('guru'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => ucwords(strtolower(trim($request->name))),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru'
        ]);

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil ditambahkan');
    }



    public function update(Request $request, User $guru)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $guru->id,
        ]);

        $data = [
            'name' => ucwords(strtolower(trim($request->name))),
            'email' => $request->email,
        ];

        if ($request->has('reset_password')) {
            $data['password'] = Hash::make('guru12345');
        }

        $guru->update($data);

        $msg = 'Guru berhasil diupdate';
        if ($request->has('reset_password')) {
            $msg .= ' dan password telah direset ke default (guru12345)';
        }

        return redirect()->route('admin.guru.index')->with('success', $msg);
    }

    public function destroy(User $guru)
    {
        $guru->delete();
        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil dihapus');
    }

    public function export()
    {
        return Excel::download(new GuruExport, 'data_guru.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $import = new GuruImport;
        try {
            Excel::import($import, $request->file('file'));
        } catch (\Exception $e) {
            return redirect()->route('admin.guru.index')->with('error', $e->getMessage());
        }

        $message = "Berhasil: $import->successCount data diimpor.";
        if (count($import->failures) > 0) {
            $message .= " Gagal: " . count($import->failures) . " data (sudah terdaftar atau tidak lengkap).";
            return redirect()->route('admin.guru.index')->with('success', $message)->with('import_failures', $import->failures);
        }

        return redirect()->route('admin.guru.index')->with('success', $message);
    }

    public function downloadTemplate()
    {
        return Excel::download(new \App\Exports\GuruTemplateExport, 'template_guru.xlsx');
    }
}