<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat Dispensasi - {{ $surat->nomor_surat_lengkap }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4 portrait; margin: 2cm 2cm; }
        * { box-sizing: border-box; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; font-family: Arial, Helvetica, sans-serif !important; }
        body { font-size: 12pt; line-height: 1.4; margin: 0; padding: 0; color: black; background-color: #e5e7eb; }
        
        .cetak-kertas { width: 21cm; min-height: 29.7cm; padding: 1.5cm; margin: 0 auto; background-color: white; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border-radius: 8px; display: flex; flex-direction: column; }
        
        .tabel-kegiatan { width: 100%; margin: 5px 0 15px 0; }
        .tabel-kegiatan td { vertical-align: top; }
        .tabel-kegiatan td:first-child { width: 160px; }
        .tabel-siswa { width: 100%; border-collapse: collapse; margin-top: 0px; }
        .tabel-siswa th, .tabel-siswa td { border: 1px solid black; padding: 8px 10px; text-align: left; }
        
        .ttd-area { width: 300px; float: right; text-align: center; margin-top: 40px; margin-left: 360px;}
        .ttd-area b { font-size: 11pt; }
        
        .page-break { page-break-before: always; }
        .avoid-break { page-break-inside: avoid; }

        .footer-layar {
            margin-top: auto;
            padding-top: 20px;
            text-align: center;
            font-size: 10pt;
            font-style: italic;
            color: #555;
        }
        .footer-cetak-global { display: none; }

        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; padding: 0 !important; margin: 0 !important; }
            .cetak-kertas { width: 100% !important; max-width: 100% !important; min-height: auto !important; padding: 0 !important; margin: 0 !important; box-shadow: none !important; border-radius: 0 !important; display: block !important; }
            
            .footer-layar { display: none !important; }
            .footer-cetak-global {
                display: block !important;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                text-align: center;
                font-size: 10pt;
                font-style: italic;
                color: #555;
            }
        }
    </style>
</head>
<body class="text-gray-900" style="font-size: 11pt; ">

    <div class="no-print fixed top-5 left-5 z-50 flex gap-2">
        <button onclick="window.close()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 font-sans">&larr; Tutup Tab</button>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500 font-bold font-sans">Cetak PDF</button>
    </div>

    <div class="footer-cetak-global">
        Dokumen ini diterbitkan melalui Smart-M1 dan dapat diverifikasi keasliannya melalui QR Code.
    </div>

    @php
        $isKepalaSekolah = false;
        if ($surat->penandatangan) {
            $jenis = strtolower((string) $surat->penandatangan->jenis_ptk);
            $tugas = strtolower(json_encode($surat->penandatangan->tugas_tambahan));
            if (str_contains($jenis, 'kepala sekolah') || str_contains($tugas, 'kepala sekolah')) { $isKepalaSekolah = true; }
        }

        $pengaturan = null;
        try { if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) $pengaturan = \App\Models\Pengaturan::first(); } catch (\Exception $e) {}

        $token = $surat->token ?? strtoupper(substr(md5($surat->nomor_surat_lengkap . config('app.key')), 0, 6));
        $urlVerifikasi = url('/v/' . $token);
        $qrData = QrCode::format('png')->size(200)->margin(0)->errorCorrection('Q')->generate($urlVerifikasi);
        $qrCodeImage = 'data:image/png;base64,' . base64_encode($qrData);
    @endphp

    <div class="flex flex-col items-center py-10 print:py-0 print:block">
        
        <div class="cetak-kertas">
            
            <div class="border-b-4 border-gray-800 pb-6 mb-5 flex items-center justify-between avoid-break">
                <div class="w-24 h-24 flex-shrink-0 flex items-center justify-center">
                    @if(isset($pengaturan) && $pengaturan->logo_dinas)
                        <img src="{{ url('/uploads/' . $pengaturan->logo_dinas) }}" alt="Logo Dinas" class="max-w-full max-h-full object-contain">
                    @endif
                </div>
                <div class="flex-1 text-center px-4" style="line-height: 1.3;">
                    <h1 class="text-[14pt] font-bold uppercase">PEMERINTAH PROVINSI BANTEN</h1>
                    <h1 class="text-[14pt] font-bold uppercase">DINAS PENDIDIKAN DAN KEBUDAYAAN</h1>
                    <h1 class="text-[22pt] font-bold uppercase">{{ $pengaturan->nama_sekolah ?? 'SMA NEGERI 1 MALINGPING' }}</h1>
                    <p>NPSN: 20601875 AKREDITASI: A (96)</p>
                    <p>Jl. Raya Bayah KM. 4 No. 39 Malingping – Lebak, 42391</p>
                    <p style="position: absolute; justify-self: center;">Website: <u>https://sman1malingping.sch.id</u> – Email: <u>info@sman1malingping.sch.id</u></p>
                </div>
                <div class="w-24 h-24 flex-shrink-0 flex items-center justify-center">
                    @if(isset($pengaturan) && $pengaturan->logo_sekolah)
                        <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo Sekolah" class="max-w-full max-h-full object-contain">
                    @endif
                </div>
            </div>

            <table style="margin-bottom: 15px;">
                <tr><td style="width: 80px; vertical-align: top;">Nomor</td><td style="width: 15px; vertical-align: top;">:</td><td>{{ $surat->nomor_surat_lengkap }}</td></tr>
                <tr><td style="vertical-align: top;">Lampiran</td><td style="vertical-align: top;">:</td><td>1 (satu) Berkas</td></tr>
                <tr><td style="vertical-align: top;">Perihal</td><td style="vertical-align: top;">:</td><td><b>Dispensasi Belajar</b></td></tr>
            </table>

            <div style="margin-bottom: 20px;">
                Kepada Yth.<br>
                Bapak/Ibu Guru {{ $pengaturan->nama_sekolah ?? 'SMA Negeri 1 Malingping' }}<br>
                Di Tempat
            </div>

            <p style="text-align: justify; margin-bottom: 10px;">Dengan hormat,</p>
            <p style="text-align: justify; margin-bottom: 10px;">
                Bersama surat ini, kami memberikan dispensasi belajar kepada peserta didik sebagaimana tercantum dalam Lampiran untuk mengikuti kegiatan sebagai berikut:
            </p>

            <table class="tabel-kegiatan avoid-break">
                <tr><td>Nama Kegiatan</td><td>:</td><td class="font-bold">{{ $surat->nama_kegiatan }}</td></tr>
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

            <p style="text-align: justify; margin-bottom: 10px;">
                Selama mengikuti kegiatan tersebut, peserta didik diberikan dispensasi dari kegiatan pembelajaran di sekolah sesuai waktu pelaksanaan kegiatan. Peserta didik tetap berkewajiban mengikuti ketentuan yang berlaku di sekolah serta menyelesaikan tugas pembelajaran yang ditinggalkan sesuai arahan guru mata pelajaran.
            </p>
            <p style="text-align: justify;">Demikian surat dispensasi belajar ini dibuat untuk dipergunakan sebagaimana mestinya.</p>

            <div class="ttd-area avoid-break">
                Malingping, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->isoFormat('D MMMM Y') }}<br>
                @if($isKepalaSekolah)
                    Kepala {{ $pengaturan->nama_sekolah ?? 'SMA Negeri 1 Malingping' }}<br>
                @else
                    a.n. Kepala Sekolah<br>Wakil Kepala Sekolah Bidang Kesiswaan<br>
                @endif

                <div style="position: relative; margin: auto; display: block; padding: 4px; width: 105px; height: 105px;">
                    <img src="{{ $qrCodeImage }}" alt="QR Code" style="width: 100%; height: 100%; object-fit: contain;">
                    
                    @if(isset($pengaturan) && $pengaturan->logo_sekolah)
                        <div style="position: absolute; top: 50%; left: 50%; width: 34px; height: 34px; margin-top: -17px; margin-left: -17px; background: white; padding: 2px; border-radius: 8px; border:1px solid #d7cccc;">
                            <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                    @endif
                </div>

                <b><u>{{ $surat->penandatangan->nama ?? '........................................' }}</u></b><br>
                NIP. {{ $surat->penandatangan->nip ?? '-' }}
            </div>
            
            <div style="clear: both;"></div>

            <div class="footer-layar">
                Dokumen ini diterbitkan melalui Smart-M1 dan dapat diverifikasi keasliannya melalui QR Code.
            </div>
        </div>

        <div class="cetak-kertas page-break print:mt-0 mt-10">
            
            <h3 style="text-align: left; margin-bottom: 10px; text-decoration: underline; font-size: 14pt;">LAMPIRAN SURAT DISPENSASI</h3>
            
            <table style="width: 100%; margin-bottom: 20px;">
                <tr><td style="width: 120px;">Nomor Surat</td><td style="width: 15px;">:</td><td class="font-bold">{{ $surat->nomor_surat_lengkap }}</td></tr>
                <tr><td>Kegiatan</td><td>:</td><td class="font-bold">{{ $surat->nama_kegiatan }}</td></tr>
            </table>

            <table class="tabel-siswa">
                <thead>
                    <tr style="background-color: #f3f4f6;">
                        <th style="width: 5%; text-align: center;">No</th>
                        <th style="width: 20%; text-align: center;">NISN</th>
                        <th style="width: 55%; text-align: center;">Nama Peserta Didik</th>
                        <th style="width: 20%; text-align: center;">Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($surat->siswa->sortBy(fn($s) => ($s->kelas->nama_kelas ?? 'ZZZ') . ' - ' . $s->nama_lengkap, SORT_NATURAL | SORT_FLAG_CASE)->values() as $index => $s)
                    <tr class="avoid-break">
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="text-align: center;">{{ $s->nisn ?? '-' }}</td>
                        <td style="text-align: left;" class="uppercase font-semibold">{{ $s->nama_lengkap }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $s->kelas->nama_kelas ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="ttd-area avoid-break" style="margin-top: 40px;">
                Malingping, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->isoFormat('D MMMM Y') }}<br>
                @if($isKepalaSekolah)
                    Kepala {{ $pengaturan->nama_sekolah ?? 'SMA Negeri 1 Malingping' }}<br>
                @else
                    a.n. Kepala Sekolah<br>Wakil Kepala Sekolah Bidang Kesiswaan<br>
                @endif

                <div style="position: relative; margin: auto; display: block; padding: 4px; width: 105px; height: 105px;">
                    <img src="{{ $qrCodeImage }}" alt="QR Code" style="width: 100%; height: 100%; object-fit: contain;">
                    
                    @if(isset($pengaturan) && $pengaturan->logo_sekolah)
                        <div style="position: absolute; top: 50%; left: 50%; width: 34px; height: 34px; margin-top: -17px; margin-left: -17px; background: white; padding: 2px; border-radius: 8px; border:1px solid #d7cccc;">
                            <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                    @endif
                </div>

                <b><u>{{ $surat->penandatangan->nama ?? '........................................' }}</u></b><br>
                NIP. {{ $surat->penandatangan->nip ?? '-' }}
            </div>
            
            <div style="clear: both;"></div>

            <div class="footer-layar">
                Dokumen ini diterbitkan melalui Smart-M1 dan dapat diverifikasi keasliannya melalui QRCode.
            </div>

        </div>

    </div>

    <script>
        window.onload = function() { 
            setTimeout(() => { window.print(); }, 800); 
        }
    </script>
</body>
</html>