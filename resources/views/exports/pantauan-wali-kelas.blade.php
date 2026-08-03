<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    @php
        // Hitung total kolom dinamis untuk keperluan merge (colspan) judul
        $totalKolom = 3; // Nomor, NISN, Nama
        foreach($grupMapel as $penilaians) {
            $totalKolom += count($penilaians);
        }
    @endphp

    <table>
        <thead>
            <!-- ===================== KOP JUDUL EXCEL ===================== -->
            <tr>
                <th colspan="{{ $totalKolom }}" style="text-align: center; font-weight: bold; font-size: 16px;">
                    {{ strtoupper($namaSekolah) }}
                </th>
            </tr>
            <tr>
                <th colspan="{{ $totalKolom }}" style="text-align: center; font-weight: bold; font-size: 14px;">
                    REKAP NILAI SISWA KELAS {{ strtoupper($kelas->nama_kelas) }}
                </th>
            </tr>
            <tr>
                <th colspan="{{ $totalKolom }}" style="text-align: center; font-weight: bold; font-size: 14px;">
                    TAHUN AJARAN {{ strtoupper($tahunAjaranNama) }}
                </th>
            </tr>
            <tr>
                <th colspan="{{ $totalKolom }}"></th> <!-- Baris Kosong sebagai pemisah -->
            </tr>
            <!-- =========================================================== -->

            <!-- HEADER TABEL INTI -->
            <tr>
                <th rowspan="2" style="font-weight: bold; text-align: center; background-color: #f8fafc; border: 1px solid #000000;">No</th>
                <th rowspan="2" style="font-weight: bold; text-align: center; background-color: #f8fafc; border: 1px solid #000000;">NISN</th>
                <th rowspan="2" style="font-weight: bold; text-align: center; background-color: #f8fafc; width: 250px; border: 1px solid #000000;">Nama Lengkap Siswa</th>
                
                @foreach($grupMapel as $namaMapel => $penilaians)
                    <th colspan="{{ count($penilaians) }}" style="font-weight: bold; text-align: center; background-color: #cbd5e1; border: 1px solid #000000;">
                        {{ $namaMapel }}
                    </th>
                @endforeach
            </tr>
            <tr>
                @foreach($grupMapel as $namaMapel => $penilaians)
                    @foreach($penilaians as $p)
                        <th style="font-weight: bold; text-align: center; background-color: #e2e8f0; border: 1px solid #000000;">
                            {{ $p->jenis_nilai }}
                        </th>
                    @endforeach
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rekap as $index => $data)
                <tr>
                    <td style="text-align: center; border: 1px solid #000000;">{{ $index + 1 }}</td>
                    <td style="text-align: center; border: 1px solid #000000; mso-number-format:'\@';">{{ $data['siswa']->nisn ?? '-' }}</td>
                    <td style="border: 1px solid #000000;">{{ $data['siswa']->nama_lengkap }}</td>
                    
                    @foreach($grupMapel as $namaMapel => $penilaians)
                        @foreach($penilaians as $p)
                            @php $nilai = $data['nilai'][$p->id]; @endphp
                            
                            @if(is_null($nilai))
                                <!-- Kotak Merah Jika Kosong -->
                                <td style="text-align: center; background-color: #ffcccc; color: #ff0000; font-weight: bold; border: 1px solid #000000;">X</td>
                            @else
                                <td style="text-align: center; border: 1px solid #000000;">{{ $nilai }}</td>
                            @endif
                        @endforeach
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>