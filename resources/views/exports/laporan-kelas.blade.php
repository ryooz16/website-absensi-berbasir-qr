<table>
    <thead>
        <tr>
            <th colspan="6" style="font-weight: bold; text-align: center;">LAPORAN ABSENSI KELAS - {{ strtoupper($kelas->nama_kelas) }}</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">Tahun Ajaran: {{ $tahunAjaran }}</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">Periode: {{ $startDate }} s/d {{ $endDate }}</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">Wali Kelas: {{ $guru->name }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold; background-color: #f3f4f6;">No</th>
            <th style="font-weight: bold; background-color: #f3f4f6;">NIS</th>
            <th style="font-weight: bold; background-color: #f3f4f6;">Nama Siswa</th>
            <th style="font-weight: bold; background-color: #f3f4f6; text-align: center;">Hadir</th>
            <th style="font-weight: bold; background-color: #f3f4f6; text-align: center;">Sakit</th>
            <th style="font-weight: bold; background-color: #f3f4f6; text-align: center;">Izin</th>
            <th style="font-weight: bold; background-color: #f3f4f6; text-align: center;">Alpha</th>
        </tr>
    </thead>
    <tbody>
        @foreach($siswa as $index => $s)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>'{{ $s->nis }}</td>
                <td>{{ $s->nama }}</td>
                <td style="text-align: center;">{{ $rekap[$s->pivot->id]['hadir'] ?? 0 }}</td>
                <td style="text-align: center;">{{ $rekap[$s->pivot->id]['sakit'] ?? 0 }}</td>
                <td style="text-align: center;">{{ $rekap[$s->pivot->id]['izin'] ?? 0 }}</td>
                <td style="text-align: center;">{{ $rekap[$s->pivot->id]['alpha'] ?? 0 }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

