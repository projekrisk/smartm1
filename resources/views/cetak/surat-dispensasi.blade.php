<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat Dispensasi</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; padding: 2cm 2.5cm; }
        .kop-surat { text-align: center; border-bottom: 3px solid black; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat h1 { margin: 0; font-size: 16pt; font-weight: bold; }
        .kop-surat p { margin: 0; font-size: 11pt; }
        .judul-surat { text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 0; font-size: 14pt;}
        .nomor-surat { text-align: center; margin-top: 0; margin-bottom: 30px; }
        .tabel-kegiatan { width: 100%; margin: 15px 0; }
        .tabel-kegiatan td { vertical-align: top; padding: 3px 0; }
        .tabel-kegiatan td:first-child { width: 150px; }
        .tabel-siswa { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .tabel-siswa th, .tabel-siswa td { border: 1px solid black; padding: 6px 10px; text-align: left; }
        .ttd-area { width: 300px; float: right; text-align: left; margin-top: 30px; }
        .page-break { page-break-before: always; }
        @media print { body { padding: 0; } }
    </style>
</head>
<body onload="window.print()">

    <!-- HALAMAN 1: SURAT PENGANTAR -->
    <div class="kop-surat">
        <h1>PEMERINTAH PROVINSI BANTEN</h1>
        <h1>DINAS PENDIDIKAN DAN KEBUDAYAAN</h1>
        <h1>SMA NEGERI 1 MALINGPING</h1>
        <p>Jalan Raya ... Malingping, Lebak - Banten</p>
    </div>

    <div class="judul-surat">SURAT DISPENSASI BELAJAR</div>
    <div class="nomor-surat">Nomor: {{ $surat->nomor_surat_lengkap }}</div>

    <p style="text-indent: 1cm; text-align: justify;">
        Dengan ini diberikan dispensasi belajar kepada peserta didik sebagaimana tercantum dalam Lampiran Surat Dispensasi Belajar untuk mengikuti kegiatan sebagai berikut:
    </p>

    <table class="tabel-kegiatan">
        <tr><td>Nama Kegiatan</td><td>:</td><td>{{ $surat->nama_kegiatan }}</td></tr>
        <tr><td>Penyelenggara</td><td>:</td><td>{{ $surat->penyelenggara }}</td></tr>
        <tr><td>Tempat</td><td>:</td><td>{{ $surat->tempat }}</td></tr>
        <tr><td>Hari/Tanggal</td><td>:</td>
            <td>
                @if($surat->tanggal_mulai->equalTo($surat->tanggal_selesai))
                    {{ \Carbon\Carbon::parse($surat->tanggal_mulai)->isoFormat('dddd, D MMMM Y') }}
                @else
                    {{ \Carbon\Carbon::parse($surat->tanggal_mulai)->isoFormat('D MMMM') }} s.d. {{ \Carbon\Carbon::parse($surat->tanggal_selesai)->isoFormat('D MMMM Y') }}
                @endif
            </td>
        </tr>
    </table>

    <p style="text-indent: 1cm; text-align: justify;">
        Selama mengikuti kegiatan tersebut, peserta didik diberikan dispensasi dari kegiatan pembelajaran di sekolah sesuai waktu pelaksanaan kegiatan. Peserta didik tetap berkewajiban mengikuti ketentuan yang berlaku di sekolah serta menyelesaikan tugas pembelajaran yang ditinggalkan sesuai arahan guru mata pelajaran.
    </p>
    
    <p style="text-indent: 1cm; text-align: justify;">
        Demikian surat dispensasi belajar ini dibuat untuk dipergunakan sebagaimana mestinya.
    </p>

    <div class="ttd-area">
        Malingping, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->isoFormat('D MMMM Y') }}<br>
        a.n. Kepala SMA Negeri 1 Malingping<br>
        Wakil Kepala Sekolah<br>
        Bidang Kesiswaan<br><br><br><br><br>
        
        <b><u>{{ $surat->penandatangan->nama ?? '..........................' }}</u></b><br>
        NIP. {{ $surat->penandatangan->nip ?? '..........................' }}
    </div>

    <div style="clear: both;"></div>

    <!-- HALAMAN 2: LAMPIRAN SISWA -->
    <div class="page-break"></div>

    <div class="kop-surat">
        <h1>LAMPIRAN SURAT DISPENSASI</h1>
    </div>
    <p>Nomor Surat : {{ $surat->nomor_surat_lengkap }}</p>
    <p>Kegiatan &nbsp;&nbsp;&nbsp;&nbsp;: {{ $surat->nama_kegiatan }}</p>

    <table class="tabel-siswa">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 25%;">NISN</th>
                <th style="width: 50%;">Nama Peserta Didik</th>
                <th style="width: 20%;">Kelas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($surat->siswa as $index => $s)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $s->nisn ?? '-' }}</td>
                <td>{{ $s->nama_lengkap }}</td>
                <td>{{ $s->kelas->nama_kelas ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>