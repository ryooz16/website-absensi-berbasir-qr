import os
import re

def process_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Insert use Carbon\Carbon;
    if 'use Carbon\\Carbon;' not in content:
        content = content.replace('use Illuminate\\Http\\Request;', 'use Illuminate\\Http\\Request;\nuse Carbon\\Carbon;')

    # Insert resolvePeriode method at the end of the class
    resolve_method = """
    private function resolvePeriode(Request $request)
    {
        $periode = $request->periode ?? 'hari_ini';
        $tanggalAwal = date('Y-m-d');
        $tanggalAkhir = date('Y-m-d');

        if ($periode === 'hari_ini') {
            $tanggalAwal = Carbon::today()->format('Y-m-d');
            $tanggalAkhir = Carbon::today()->format('Y-m-d');
        } elseif ($periode === 'minggu_ini') {
            $tanggalAwal = Carbon::now()->startOfWeek()->format('Y-m-d');
            $tanggalAkhir = Carbon::now()->endOfWeek()->format('Y-m-d');
        } elseif ($periode === 'bulan_ini') {
            $tanggalAwal = Carbon::now()->startOfMonth()->format('Y-m-d');
            $tanggalAkhir = Carbon::now()->endOfMonth()->format('Y-m-d');
        } elseif ($periode === 'tahun_ajaran') {
            $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
            if ($tahunAktif) {
                $tanggalAwal = $tahunAktif->tanggal_mulai ?? date('Y-m-01');
                $tanggalAkhir = $tahunAktif->tanggal_selesai ?? date('Y-m-t');
            }
        } elseif ($periode === 'kustom') {
            $tanggalAwal = $request->tanggal_awal ?? date('Y-m-d');
            $tanggalAkhir = $request->tanggal_akhir ?? date('Y-m-d');
        }

        return [$tanggalAwal, $tanggalAkhir, $periode];
    }
}"""
    # Replace the last closing brace with the method and the closing brace
    content = re.sub(r'\}\s*$', resolve_method, content)

    # Now replace the date fetching in the 4 methods
    
    # 1. siswaExport
    content = re.sub(
        r'public function siswaExport\(Request \$request\)\s*\{\s*\$tanggalAwal = \$request->tanggal_awal \?\? date\(\'Y-m-d\'\);\s*\$tanggalAkhir = \$request->tanggal_akhir \?\? date\(\'Y-m-d\'\);\s*\$kelasId = \$request->kelas_id;\s*\$mapelId = \$request->mata_pelajaran_id;\s*\$tahunAktif = TahunAjaran::where\(\'status\', \'aktif\'\)->first\(\);\s*\$tahunAjaranId = \$request->tahun_ajaran_id \?\? \$tahunAktif\?->id;\s*\$queryRaw = AbsensiSiswa::with\(\[\'siswaKelas\.siswa\', \'kelas\'\]\)\s*->whereBetween\(\'tanggal\', \[\$tanggalAwal, \$tanggalAkhir\]\)\s*->where\(\'tahun_ajaran_id\', \$tahunAjaranId\);',
        '''public function siswaExport(Request $request)
    {
        [$tanggalAwal, $tanggalAkhir, $periodeAktif] = $this->resolvePeriode($request);
        $kelasId = $request->kelas_id;
        $mapelId = $request->mata_pelajaran_id;

        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        $queryRaw = AbsensiSiswa::with(['siswaKelas.siswa', 'kelas'])
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
            
        if ($periodeAktif !== 'kustom' && $tahunAktif) {
            $queryRaw->where('tahun_ajaran_id', $tahunAktif->id);
        }''',
        content
    )

    # 2. guruExport
    content = re.sub(
        r'public function guruExport\(Request \$request\)\s*\{\s*\$tanggalAwal = \$request->tanggal_awal \?\? date\(\'Y-m-d\'\);\s*\$tanggalAkhir = \$request->tanggal_akhir \?\? date\(\'Y-m-d\'\);\s*\$tahunAktif = TahunAjaran::where\(\'status\', \'aktif\'\)->first\(\);\s*\$tahunAjaranId = \$request->tahun_ajaran_id \?\? \$tahunAktif\?->id;\s*\$queryRaw = AbsensiGuru::with\(\'guru\'\)\s*->whereBetween\(\'tanggal\', \[\$tanggalAwal, \$tanggalAkhir\]\)\s*->where\(\'tahun_ajaran_id\', \$tahunAjaranId\);',
        '''public function guruExport(Request $request)
    {
        [$tanggalAwal, $tanggalAkhir, $periodeAktif] = $this->resolvePeriode($request);

        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        $queryRaw = AbsensiGuru::with('guru')
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
            
        if ($periodeAktif !== 'kustom' && $tahunAktif) {
            $queryRaw->where('tahun_ajaran_id', $tahunAktif->id);
        }''',
        content
    )

    # 3. siswaIndex
    content = re.sub(
        r'public function siswaIndex\(Request \$request\)\s*\{\s*\$tanggalAwal = \$request->tanggal_awal \?\? date\(\'Y-m-d\'\);\s*\$tanggalAkhir = \$request->tanggal_akhir \?\? date\(\'Y-m-d\'\);\s*\$kelasId = \$request->kelas_id;\s*\$mapelId = \$request->mata_pelajaran_id;\s*\$tahunAktif = TahunAjaran::where\(\'status\', \'aktif\'\)->first\(\);\s*\$tahunAjaranId = \$request->tahun_ajaran_id \?\? \$tahunAktif\?->id;\s*// 1\. Base Query untuk rentang tanggal\s*\$queryRaw = AbsensiSiswa::with\(\[\'mataPelajaran\', \'guru\', \'siswaKelas\.siswa\', \'kelas\'\]\)\s*->whereBetween\(\'tanggal\', \[\$tanggalAwal, \$tanggalAkhir\]\)\s*->where\(\'tahun_ajaran_id\', \$tahunAjaranId\);',
        '''public function siswaIndex(Request $request)
    {
        [$tanggalAwal, $tanggalAkhir, $periodeAktif] = $this->resolvePeriode($request);
        $kelasId = $request->kelas_id;
        $mapelId = $request->mata_pelajaran_id;

        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        // 1. Base Query untuk rentang tanggal
        $queryRaw = AbsensiSiswa::with(['mataPelajaran', 'guru', 'siswaKelas.siswa', 'kelas'])
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
            
        if ($periodeAktif !== 'kustom' && $tahunAktif) {
            $queryRaw->where('tahun_ajaran_id', $tahunAktif->id);
        }''',
        content
    )

    # Also update the compact variables for siswaIndex
    content = re.sub(
        r'compact\(\s*\'tanggalAwal\', \'tanggalAkhir\', \'kelasId\', \'mapelId\', \'tahunAjaranId\',',
        '''compact(
            'tanggalAwal', 'tanggalAkhir', 'periodeAktif', 'kelasId', 'mapelId',''',
        content
    )

    # 4. guruIndex
    content = re.sub(
        r'public function guruIndex\(Request \$request\)\s*\{\s*\$tanggalAwal = \$request->tanggal_awal \?\? date\(\'Y-m-d\'\);\s*\$tanggalAkhir = \$request->tanggal_akhir \?\? date\(\'Y-m-d\'\);\s*\$tahunAktif = TahunAjaran::where\(\'status\', \'aktif\'\)->first\(\);\s*\$tahunAjaranId = \$request->tahun_ajaran_id \?\? \$tahunAktif\?->id;\s*\$queryRaw = AbsensiGuru::with\(\'guru\'\)\s*->whereBetween\(\'tanggal\', \[\$tanggalAwal, \$tanggalAkhir\]\)\s*->where\(\'tahun_ajaran_id\', \$tahunAjaranId\);',
        '''public function guruIndex(Request $request)
    {
        [$tanggalAwal, $tanggalAkhir, $periodeAktif] = $this->resolvePeriode($request);

        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        $queryRaw = AbsensiGuru::with('guru')
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
            
        if ($periodeAktif !== 'kustom' && $tahunAktif) {
            $queryRaw->where('tahun_ajaran_id', $tahunAktif->id);
        }''',
        content
    )

    # Also update the compact variables for guruIndex
    content = re.sub(
        r'compact\(\s*\'tanggalAwal\', \'tanggalAkhir\', \'tahunAjaranId\',',
        '''compact(
            'tanggalAwal', 'tanggalAkhir', 'periodeAktif',''',
        content
    )
    
    # Also fix tahunAjaran in exports
    content = re.sub(
        r"'tahunAjaran' => TahunAjaran::find\(\$tahunAjaranId\)\?->nama_tahun \?\? '-',",
        "'tahunAjaran' => $tahunAktif?->nama_tahun ?? '-',",
        content
    )
    
    # Also replace $tahunAjaranId in waliKelasList
    content = re.sub(
        r"->where\('tahun_ajaran_id', \$tahunAjaranId\)",
        "->where('tahun_ajaran_id', $tahunAktif?->id)",
        content
    )


    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

process_file('app/Http/Controllers/Admin/LaporanController.php')
process_file('app/Http/Controllers/KepalaSekolah/LaporanController.php')
print("Done")
