<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat Dispensasi - {{ $surat->nomor_surat_lengkap }}</title>
    <style>
        /* PENGATURAN KERTAS A4 & FONT ARIAL */
        @page {
            size: A4 portrait;
            margin: 20mm 20mm 20mm 20mm;
        }
        
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            font-size: 11pt; 
            line-height: 1.5; 
            margin: 0;
            padding: 0;
            color: black;
        }

        /* WADAH UTAMA */
        .page-container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
        }

        /* PENGATURAN KOP SURAT */
        .kop-table {
            width: 100%;
            margin-bottom: 2px;
        }
        .kop-table td {
            vertical-align: middle;
            text-align: center;
        }
        .logo-kiri, .logo-kanan {
            width: 85px; /* Sesuaikan ukuran logo jika perlu */
            height: auto;
        }
        .teks-kop {
            line-height: 1.2;
        }
        /* Garis Ganda Kop Surat */
        .garis-ganda {
            border-bottom: 4px solid black;
            padding-bottom: 2px;
            margin-bottom: 2px;
        }
        .garis-ganda-bawah {
            border-bottom: 1px solid black;
            margin-bottom: 20px;
        }

        /* KONTEN SURAT */
        .judul-surat { text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 0; font-size: 14pt;}
        .nomor-surat { text-align: center; margin-top: 0; margin-bottom: 25px; }
        
        .tabel-kegiatan { width: 100%; margin: 15px 0; }
        .tabel-kegiatan td { vertical-align: top; padding: 4px 0; }
        .tabel-kegiatan td:first-child { width: 160px; }

        .tabel-siswa { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .tabel-siswa th, .tabel-siswa td { border: 1px solid black; padding: 8px 10px; text-align: left; }
        
        /* AREA TANDA TANGAN */
        .ttd-area { width: 320px; float: right; text-align: left; margin-top: 30px; line-height: 1.3; }
        .ttd-area b { font-size: 12pt; }
        
        .page-break { page-break-before: always; }
        
        @media print {
            body { padding: 0; background-color: white; }
        }
    </style>
</head>
<body onload="window.print()">

    @php
        // LOGIKA PENGECEKAN JABATAN PENANDATANGAN
        $isKepalaSekolah = false;
        if ($surat->penandatangan) {
            $jenis = strtolower((string) $surat->penandatangan->jenis_ptk);
            $tugas = strtolower(json_encode($surat->penandatangan->tugas_tambahan));
            
            if (str_contains($jenis, 'kepala sekolah') || str_contains($tugas, 'kepala sekolah')) {
                $isKepalaSekolah = true;
            }
        }
    @endphp

    <div class="page-container">
        <!-- HALAMAN 1: SURAT PENGANTAR -->
        
        <!-- KOP SURAT BERLOGO -->
        <table class="kop-table">
            <tr>
                <td style="width: 15%;">
                    <!-- LOGO KIRI (BANTEN). Pastikan nama file/path logo Anda benar di folder public -->
                    <img src="{{ url('/images/logo-banten.png') }}" class="logo-kiri" alt="Logo Banten" onerror="this.style.display='none'">
                </td>
                <td style="width: 70%;" class="teks-kop">
                    <span style="font-size: 14pt;">PEMERINTAH PROVINSI BANTEN</span><br>
                    <span style="font-size: 14pt;">DINAS PENDIDIKAN DAN KEBUDAYAAN</span><br>
                    <strong style="font-size: 18pt; font-family: Arial, sans-serif;">SMA NEGERI 1 MALINGPING</strong><br>
                    <span style="font-size: 10pt;">Jalan Raya Binuangeun Km. 02 Malingping Lebak - Banten 42391</span><br>
                    <span style="font-size: 10pt;">Email: sman1mlp@yahoo.co.id Website: sman1malingping.sch.id</span>
                </td>
                <td style="width: 15%;">
                    <!-- LOGO KANAN (SEKOLAH). Pastikan nama file/path logo Anda benar di folder public -->
                    <img src="{{ url('/images/logo-sekolah.png') }}" class="logo-kanan" alt="Logo Sekolah" onerror="this.style.display='none'">
                </td>
            </tr>
        </table>
        <!-- GARIS GANDA KOP SURAT -->
        <div class="garis-ganda"><div class="garis-ganda-bawah"></div></div>

        <!-- ISI SURAT -->
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

        <!-- BAGIAN TANDA TANGAN (DINAMIS) -->
        <div class="ttd-area">
            Malingping, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->isoFormat('D MMMM Y') }}<br>
            
            @if($isKepalaSekolah)
                <!-- TTD Kepala Sekolah -->
                Mengetahui,<br>
                Kepala SMA Negeri 1 Malingping<br><br><br><br><br>
            @else
                <!-- TTD Wakasek / Lainnya -->
                a.n. Kepala SMA Negeri 1 Malingping<br>
                Wakil Kepala Sekolah<br>
                Bidang Kesiswaan<br><br><br><br><br>
            @endif
            
            <b><u>{{ $surat->penandatangan->nama ?? '........................................' }}</u></b><br>
            NIP. {{ $surat->penandatangan->nip ?? '........................................' }}
        </div>

        <div style="clear: both;"></div>

        <!-- HALAMAN 2: LAMPIRAN SISWA -->
        <div class="page-break"></div>

        <div style="margin-top: 10mm;">
            <h3 style="text-align: left; margin-bottom: 2px;">LAMPIRAN SURAT DISPENSASI</h3>
            <table style="width: 100%;">
                <tr>
                    <td style="width: 120px;">Nomor Surat</td>
                    <td style="width: 15px;">:</td>
                    <td>{{ $surat->nomor_surat_lengkap }}</td>
                </tr>
                <tr>
                    <td>Kegiatan</td>
                    <td>:</td>
                    <td>{{ $surat->nama_kegiatan }}</td>
                </tr>
            </table>

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
        </div>
    </div>
</body>
</html>