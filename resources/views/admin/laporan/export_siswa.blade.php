<table>
    <thead>
        <tr>
            <th colspan="6" style="font-weight: bold; text-align: center;">LAPORAN REKAPITULASI ABSENSI SISWA</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">Tahun Ajaran: {{ $tahunAjaran }}</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">Periode: {{ $tanggalAwal }} s/d {{ $tanggalAkhir }}</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">Kelas: {{ $kelas }} | Mata Pelajaran: {{ $mapel }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold; background-color: #cccccc; border: 1px solid #000000;">Nama Siswa</th>
            <th style="font-weight: bold; background-color: #cccccc; border: 1px solid #000000;">Kelas</th>
            <th style="font-weight: bold; background-color: #cccccc; border: 1px solid #000000;">Hadir</th>
            <th style="font-weight: bold; background-color: #cccccc; border: 1px solid #000000;">Sakit</th>
            <th style="font-weight: bold; background-color: #cccccc; border: 1px solid #000000;">Izin</th>
            <th style="font-weight: bold; background-color: #cccccc; border: 1px solid #000000;">Alpha</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $d)
    <tr>
        <td style="border: 1px solid #000000;">{{ $d->nama }}</td>
        <td style="border: 1px solid #000000;">{{ $d->kelas }}</td>
        <td style="border: 1px solid #000000;">{{ $d->hadir }}</td>
        <td style="border: 1px solid #000000;">{{ $d->sakit }}</td>
        <td style="border: 1px solid #000000;">{{ $d->izin }}</td>
        <td style="border: 1px solid #000000;">{{ $d->alpha }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

