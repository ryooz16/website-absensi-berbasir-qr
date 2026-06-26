<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['admin', 'kepala_sekolah', 'wakil_kepala_sekolah']);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $admins = $query->latest()->get();
        $totalAdmin = $admins->count();
        $hasKepsek = User::where('role', 'kepala_sekolah')->exists();
        $hasWakepsek = User::where('role', 'wakil_kepala_sekolah')->exists();

        return view('admin.admins.index', compact('admins', 'totalAdmin', 'hasKepsek', 'hasWakepsek'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,kepala_sekolah,wakil_kepala_sekolah',
        ]);

        if ($request->role === 'kepala_sekolah') {
            $hasKepsek = User::where('role', 'kepala_sekolah')->exists();
            if ($hasKepsek) {
                return redirect()->back()->with('error', 'Gagal! Akun Kepala Sekolah hanya boleh ada 1.')->withInput();
            }
        }

        if ($request->role === 'wakil_kepala_sekolah') {
            $hasWakepsek = User::where('role', 'wakil_kepala_sekolah')->exists();
            if ($hasWakepsek) {
                return redirect()->back()->with('error', 'Gagal! Akun Wakil Kepala Sekolah hanya boleh ada 1.')->withInput();
            }
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Akun baru berhasil ditambahkan.');
    }

    public function update(Request $request, User $manajemen_admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $manajemen_admin->id,
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->password) {
            $request->validate(['password' => 'min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $manajemen_admin->update($data);

        return redirect()->route('admin.admins.index')->with('success', 'Data akun berhasil diperbarui.');
    }

    public function destroy(User $manajemen_admin)
    {
        if ($manajemen_admin->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()->route('admin.admins.index')->with('error', 'Gagal! Admin terakhir tidak boleh dihapus untuk mencegah sistem terkunci.');
            }
        } elseif ($manajemen_admin->role === 'kepala_sekolah') {
            $kepsekCount = User::where('role', 'kepala_sekolah')->count();
            if ($kepsekCount <= 1) {
                return redirect()->route('admin.admins.index')->with('error', 'Gagal! Kepala Sekolah terakhir tidak boleh dihapus.');
            }
        } elseif ($manajemen_admin->role === 'wakil_kepala_sekolah') {
            $wakepsekCount = User::where('role', 'wakil_kepala_sekolah')->count();
            if ($wakepsekCount <= 1) {
                return redirect()->route('admin.admins.index')->with('error', 'Gagal! Wakil Kepala Sekolah terakhir tidak boleh dihapus.');
            }
        }

        $manajemen_admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Akun berhasil dihapus.');
    }
}
