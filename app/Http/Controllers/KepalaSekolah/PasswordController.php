<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('kepsek.password');
    }

    public function update(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'different:current_password'],
            'password_confirmation' => ['required', 'same:password'],
        ], [
            'password.different' => 'Password baru tidak boleh sama dengan password saat ini.',
            'password_confirmation.same' => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Password berhasil diubah!']);
        }

        return back()->with('success', 'Password berhasil diubah!');
    }
}
