import re

def process_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # 1. Add search to siswaIndex
    content = re.sub(
        r'(\$mapelId = \$request->mata_pelajaran_id;)',
        r'\1\n        $search = $request->search;',
        content
    )
    
    content = re.sub(
        r'(// 1\. Base Query untuk rentang tanggal\n\s*\$queryRaw = AbsensiSiswa::with\(\[\'mataPelajaran\', \'guru\', \'siswaKelas\.siswa\', \'kelas\'\]\)\n\s*->whereBetween\(\'tanggal\', \[\$tanggalAwal, \$tanggalAkhir\]\);)',
        r'\1\n            \n        if ($search) {\n            $queryRaw->whereHas(\'siswaKelas.siswa\', function ($q) use ($search) {\n                $q->where(\'nama\', \'like\', "%{$search}%");\n            });\n        }',
        content
    )

    content = re.sub(
        r'compact\(\n\s*\'tanggalAwal\', \'tanggalAkhir\', \'periodeAktif\', \'kelasId\', \'mapelId\',',
        r"compact(\n            'tanggalAwal', 'tanggalAkhir', 'periodeAktif', 'kelasId', 'mapelId', 'search',",
        content
    )

    # 2. Add search to siswaExport
    content = re.sub(
        r'(public function siswaExport\(Request \$request\)\n\s*\{\n\s*\[\$tanggalAwal, \$tanggalAkhir, \$periodeAktif\] = \$this->resolvePeriode\(\$request\);\n\s*\$kelasId = \$request->kelas_id;\n\s*\$mapelId = \$request->mata_pelajaran_id;)',
        r'\1\n        $search = $request->search;',
        content
    )
    
    content = re.sub(
        r'(\$queryRaw = AbsensiSiswa::with\(\[\'siswaKelas\.siswa\', \'kelas\'\]\)\n\s*->whereBetween\(\'tanggal\', \[\$tanggalAwal, \$tanggalAkhir\]\);)',
        r'\1\n            \n        if ($search) {\n            $queryRaw->whereHas(\'siswaKelas.siswa\', function ($q) use ($search) {\n                $q->where(\'nama\', \'like\', "%{$search}%");\n            });\n        }',
        content
    )

    # 3. Add search to guruIndex
    content = re.sub(
        r'(public function guruIndex\(Request \$request\)\n\s*\{\n\s*\[\$tanggalAwal, \$tanggalAkhir, \$periodeAktif\] = \$this->resolvePeriode\(\$request\);)',
        r'\1\n        $search = $request->search;',
        content
    )
    
    content = re.sub(
        r'(\$queryRaw = AbsensiGuru::with\(\'guru\'\)\n\s*->whereBetween\(\'tanggal\', \[\$tanggalAwal, \$tanggalAkhir\]\);)',
        r'\1\n            \n        if ($search) {\n            $queryRaw->whereHas(\'guru\', function ($q) use ($search) {\n                $q->where(\'name\', \'like\', "%{$search}%");\n            });\n        }',
        content
    )

    content = re.sub(
        r'compact\(\n\s*\'tanggalAwal\', \'tanggalAkhir\', \'periodeAktif\',',
        r"compact(\n            'tanggalAwal', 'tanggalAkhir', 'periodeAktif', 'search',",
        content
    )

    # 4. Add search to guruExport
    content = re.sub(
        r'(public function guruExport\(Request \$request\)\n\s*\{\n\s*\[\$tanggalAwal, \$tanggalAkhir, \$periodeAktif\] = \$this->resolvePeriode\(\$request\);)',
        r'\1\n        $search = $request->search;',
        content
    )
    
    content = re.sub(
        r'(\$queryRaw = AbsensiGuru::with\(\'guru\'\)\n\s*->whereBetween\(\'tanggal\', \[\$tanggalAwal, \$tanggalAkhir\]\);)',
        r'\1\n            \n        if ($search) {\n            $queryRaw->whereHas(\'guru\', function ($q) use ($search) {\n                $q->where(\'name\', \'like\', "%{$search}%");\n            });\n        }',
        content
    )

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

process_file('app/Http/Controllers/Admin/LaporanController.php')
process_file('app/Http/Controllers/KepalaSekolah/LaporanController.php')
print("Done")
