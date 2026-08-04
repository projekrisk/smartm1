<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    @php
        // Hitung total kolom dinamis (setiap sumatif sekarang memakan 2 kolom: Nilai & Status)
        $totalKolom = 3; // Nomor, NISN, Nama
        foreach($grupMapel as $penilaians) {
            $totalKolom += (count($penilaians) * 2);
        }
    @endphp

    <table>
        <thead>
            <!-- ===================== KOP JUDUL EXCEL ===================== -->
            <tr>
                <th colspan="{{ $totalKolom }}" style="text-align: center; font-weight: bold; font-size: 16px;">
                    {{ strtoupper($namaSekolah ?? 'SEKOLAH') }}
                </th>
            </tr>
            <tr>
                <th colspan="{{ $totalKolom }}" style="text-align: center; font-weight: bold; font-size: 14px;">
                    REKAP NILAI SISWA KELAS {{ strtoupper($kelas->nama_kelas) }}
                </th>
            </tr>
            <tr>
                <th colspan="{{ $totalKolom }}" style="text-align: center; font-weight: bold; font-size: 14px;">
                    TAHUN AJARAN {{ strtoupper($tahunAjaranNama ?? '-') }}
                </th>
            </tr>
            <tr>
                <th colspan="{{ $totalKolom }}"></th> <!-- Baris Kosong -->
            </tr>
            <!-- =========================================================== -->

            <!-- HEADER TABEL INTI (3 TINGKAT) -->
            <tr>
                <th rowspan="3" style="font-weight: bold; text-align: center; background-color: #f8fafc; border: 1px solid #000000; vertical-align: middle;">No</th>
                <th rowspan="3" style="font-weight: bold; text-align: center; background-color: #f8fafc; border: 1px solid #000000; vertical-align: middle;">NISN</th>
                <th rowspan="3" style="font-weight: bold; text-align: center; background-color: #f8fafc; width: 250px; border: 1px solid #000000; vertical-align: middle;">Nama Lengkap Siswa</th>
                
                <!-- TINGKAT 1: Nama Mata Pelajaran -->
                @foreach($grupMapel as $namaMapel => $penilaians)
                    <th colspan="{{ count($penilaians) * 2 }}" style="font-weight: bold; text-align: center; background-color: #cbd5e1; border: 1px solid #000000;">
                        {{ $namaMapel }}
                    </th>
                @endforeach
            </tr>
            <tr>
                <!-- TINGKAT 2: Jenis Penilaian (S1, S2, dsb) -->
                @foreach($grupMapel as $namaMapel => $penilaians)
                    @foreach($penilaians as $p)
                        <th colspan="2" style="font-weight: bold; text-align: center; background-color: #e2e8f0; border: 1px solid #000000;">
                            {{ $p->jenis_nilai }}
                        </th>
                    @endforeach
                @endforeach
            </tr>
            <tr>
                <!-- TINGKAT 3: Label Nilai & Status -->
                @foreach($grupMapel as $namaMapel => $penilaians)
                    @foreach($penilaians as $p)
                        <th style="font-weight: bold; text-align: center; background-color: #f1f5f9; border: 1px solid #000000;">Nilai</th>
                        <th style="font-weight: bold; text-align: center; background-color: #f1f5f9; border: 1px solid #000000;">Sts</th>
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
                            @php 
                                $nilai = $data['nilai'][$p->id]; 
                                $status = '-';
                                $warnaStatus = '';

                                // Logika penentuan R dan T (KKM = 75)
                                if(!is_null($nilai) && is_numeric($nilai)){
                                    if($nilai < 75){
                                        $status = 'R';
                                        $warnaStatus = 'color: #ff0000; font-weight: bold;'; // Merah
                                    } else {
                                        $status = 'T';
                                        $warnaStatus = 'color: #16a34a; font-weight: bold;'; // Hijau
                                    }
                                }
                            @endphp
                            
                            @if(is_null($nilai))
                                <!-- Jika Kosong -->
                                <td style="text-align: center; background-color: #ffcccc; color: #ff0000; font-weight: bold; border: 1px solid #000000;">X</td>
                                <td style="text-align: center; background-color: #ffcccc; color: #ff0000; font-weight: bold; border: 1px solid #000000;">X</td>
                            @else
                                <!-- Jika Ada Nilai -->
                                <td style="text-align: center; border: 1px solid #000000;">{{ $nilai }}</td>
                                <td style="text-align: center; border: 1px solid #000000; {{ $warnaStatus }}">{{ $status }}</td>
                            @endif
                        @endforeach
                    @endforeach
                </tr>
            @endforeach
            
            <!-- BARIS KOSONG PEMISAH -->
            <tr>
                <td colspan="{{ $totalKolom }}"></td>
            </tr>
            <tr>
                <td colspan="{{ $totalKolom }}"></td>
            </tr>

            <!-- TABEL KETERANGAN DI BAWAH -->
            <tr>
                <td colspan="2"></td>
                <td style="font-weight: bold; text-decoration: underline;">KETERANGAN STATUS:</td>
                <td colspan="{{ $totalKolom - 3 }}"></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td style="font-weight: bold; color: #16a34a;">T = Tercapai (Nilai &ge; 75)</td>
                <td colspan="{{ $totalKolom - 3 }}"></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td style="font-weight: bold; color: #ff0000;">R = Remedial (Nilai &lt; 75)</td>
                <td colspan="{{ $totalKolom - 3 }}"></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td style="font-weight: bold; color: #ff0000;">X = Belum Ada Nilai / Kosong</td>
                <td colspan="{{ $totalKolom - 3 }}"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>