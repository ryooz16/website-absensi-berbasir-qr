<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuruImport implements ToCollection, WithHeadingRow
{
    public $successCount = 0;
    public $failures = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $name = $row['nama'] ?? $row['name'] ?? null;
            $email = $row['email'] ?? null;
            $password = $row['password'] ?? 'guru12345';

            if (!$name || !$email) {
                $this->failures[] = "Data tidak lengkap pada baris dengan nama: " . ($name ?? 'N/A');
                continue;
            }

            // Cek duplikasi
            if (User::where('email', $email)->exists()) {
                $this->failures[] = "$email: Email sudah terdaftar.";
                continue;
            }

            User::create([
                'name' => ucwords(strtolower(trim($name))),
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'guru',
            ]);

            $this->successCount++;
        }
    }
}
