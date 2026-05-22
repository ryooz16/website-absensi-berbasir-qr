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
        $query = User::where('role', 'admin');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $admins = $query->latest()->get();
        $totalAdmin = $admins->count();

        return view('admin.admins.index', compact('admins', 'totalAdmin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin baru berhasil ditambahkan.');
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

        return redirect()->route('admin.admins.index')->with('success', 'Data admin berhasil diperbarui.');
    }

    public function destroy(User $manajemen_admin)
    {
        // Proteksi: Jangan biarkan admin terakhir dihapus
        $adminCount = User::where('role', 'admin')->count();

        if ($adminCount <= 1) {
            return redirect()->route('admin.admins.index')->with('error', 'Gagal! Admin terakhir tidak boleh dihapus untuk mencegah sistem terkunci.');
        }

        $manajemen_admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil dihapus.');
    }
}
