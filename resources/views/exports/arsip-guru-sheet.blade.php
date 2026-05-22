<table>
    <thead>
        <tr>
            <th colspan="8" style="font-weight: bold; text-align: center; font-size: 14pt;">ARSIP ABSENSI GURU</th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center; font-weight: bold;">TAHUN AJARAN: {{ $tahun->nama_tahun }}</th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center; font-weight: bold;">PERIODE: {{ $tahun->tanggal_mulai->translatedFormat('d F Y') }} s/d {{ $tahun->tanggal_selesai->translatedFormat('d F Y') }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f3f4f6;">No</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f3f4f6;">Nama Guru</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f3f4f6;">Hadir</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f3f4f6;">Terlambat</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f3f4f6;">Sakit</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f3f4f6;">Izin</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f3f4f6;">Alpha</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f3f4f6;">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($gurus as $index => $g)
            @php $total = $g['hadir'] + $g['terlambat'] + $g['sakit'] + $g['izin'] + $g['alpha']; @endphp
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000;">{{ $g['nama'] }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $g['hadir'] }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $g['terlambat'] }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $g['sakit'] }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $g['izin'] }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $g['alpha'] }}</td>
                <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $total }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

