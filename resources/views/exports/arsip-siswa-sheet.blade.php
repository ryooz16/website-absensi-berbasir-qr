<table>
    <thead>
        <tr>
            <th colspan="7" style="font-weight: bold; text-align: center; font-size: 14pt;">ARSIP ABSENSI SISWA</th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center; font-weight: bold;">TAHUN AJARAN: {{ $tahun->nama_tahun }}</th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center; font-weight: bold;">PERIODE: {{ $tahun->tanggal_mulai->translatedFormat('d F Y') }} s/d {{ $tahun->tanggal_selesai->translatedFormat('d F Y') }}</th>
        </tr>
        <tr></tr>
    </thead>
    <tbody>
        @foreach($dataKelas as $kelas)
            <tr>
                <td colspan="7" style="font-weight: bold; background-color: #e2e8f0;">KELAS: {{ $kelas['nama_kelas'] }}</td>
            </tr>
            <tr>
                <td colspan="7" style="font-weight: bold; background-color: #f1f5f9;">Wali Kelas: {{ $kelas['wali_kelas'] }}</td>
            </tr>
            <tr>
                <th style="font-weight: bold; border: 1px solid #000; text-align: center;">No</th>
                <th style="font-weight: bold; border: 1px solid #000;">NIS</th>
                <th style="font-weight: bold; border: 1px solid #000;">Nama Siswa</th>
                <th style="font-weight: bold; border: 1px solid #000; text-align: center;">Hadir</th>
                <th style="font-weight: bold; border: 1px solid #000; text-align: center;">Sakit</th>
                <th style="font-weight: bold; border: 1px solid #000; text-align: center;">Izin</th>
                <th style="font-weight: bold; border: 1px solid #000; text-align: center;">Alpha</th>
            </tr>
            @foreach($kelas['siswas'] as $index => $s)
                <tr>
                    <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid #000;">'{{ $s['nis'] }}</td>
                    <td style="border: 1px solid #000;">{{ $s['nama'] }}</td>
                    <td style="border: 1px solid #000; text-align: center;">{{ $s['hadir'] }}</td>
                    <td style="border: 1px solid #000; text-align: center;">{{ $s['sakit'] }}</td>
                    <td style="border: 1px solid #000; text-align: center;">{{ $s['izin'] }}</td>
                    <td style="border: 1px solid #000; text-align: center;">{{ $s['alpha'] }}</td>
                </tr>
            @endforeach
            <tr></tr>
            <tr></tr>
        @endforeach
    </tbody>
</table>

