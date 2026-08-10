<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat Panggilan - {{ $surat->nomor_surat }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4 portrait; margin: 1.5cm; }
        * { box-sizing: border-box; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; font-family: Arial, Helvetica, sans-serif !important; }
        body { font-size: 11.5pt; line-height: 1.4 !important; margin: 0; padding: 0; color: black; background-color: #e5e7eb; }
        
        .cetak-kertas { width: 21cm; min-height: 29.7cm; padding: 1.5cm; margin: 0 auto; background-color: white; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border-radius: 8px; display: flex; flex-direction: column; }
        
        .tabel-data { width: 100%; margin: 0 0 10px 1cm; }
        .tabel-data td { vertical-align: top;}
        .tabel-data td:first-child { width: 180px; }

        .ttd-area { width: 300px; float: right; text-align: center; margin-top: 20px; margin-left: 380px; }
        .ttd-area b { font-size: 11pt; }

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
            .cetak-kertas { width: 100% !important; max-width: 100% !important; min-height: auto !important; padding: 0 !important; margin: 0 !important; box-shadow: none !important; border-radius: 0 !important; display: block !important;}
            
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
<body class="text-gray-900">

    <div class="no-print fixed top-5 left-5 z-50 flex gap-2">
        <button onclick="window.close()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 font-sans">&larr; Tutup Tab</button>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500 font-bold font-sans">Cetak PDF</button>
    </div>

    <div class="footer-cetak-global">
        Dokumen ini diterbitkan melalui Smart-M1 dan dapat diverifikasi keasliannya melalui QR Code.
    </div>

    @php
        $pengaturan = null;
        try { if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) $pengaturan = \App\Models\Pengaturan::first(); } catch (\Exception $e) {}
        
        $penandatanganAktif = null;
        $namaKelas = '-';
        
        // 🌟 TARIK DATA WALI KELAS SECARA PAKSA & LANGSUNG DARI DATABASE
        if ($surat->siswa_id) {
            $siswaInfo = \App\Models\Siswa::find($surat->siswa_id);
            if ($siswaInfo && $siswaInfo->kelas_id) {
                $kelasInfo = \App\Models\Kelas::find($siswaInfo->kelas_id);
                if ($kelasInfo) {
                    $namaKelas = $kelasInfo->nama_kelas;
                    
                    if ($kelasInfo->wali_kelas_id) {
                        $penandatanganAktif = \App\Models\Pegawai::find($kelasInfo->wali_kelas_id);
                    }
                }
            }
        }

        // Jika masih gagal, jadikan kolom surat->penandatangan_id sebagai cadangan terakhir
        if (!$penandatanganAktif && $surat->penandatangan_id) {
            $penandatanganAktif = \App\Models\Pegawai::find($surat->penandatangan_id);
        }

        $isKepalaSekolah = false;
        $isWakasek = false;
        
        if ($penandatanganAktif) {
            $jenis = strtolower((string) $penandatanganAktif->jenis_ptk);
            $tugas = strtolower(json_encode($penandatanganAktif->tugas_tambahan));
            
            if (str_contains($jenis, 'kepala sekolah') || str_contains($tugas, 'kepala sekolah')) { 
                $isKepalaSekolah = true; 
            } elseif (str_contains($tugas, 'kesiswaan')) {
                $isWakasek = true;
            }
        }

        $token = $surat->token ?? strtoupper(substr(md5($surat->nomor_surat . config('app.key')), 0, 6));
        $urlVerifikasi = url('/v/' . $token);
        $qrData = QrCode::format('png')->size(200)->margin(0)->errorCorrection('Q')->generate($urlVerifikasi);
        $qrCodeImage = 'data:image/png;base64,' . base64_encode($qrData);
    @endphp

    <div class="flex flex-col items-center py-10 print:py-0 print:block">
        <div class="cetak-kertas">
            
            <div class="border-b-4 border-gray-800 pb-6 mb-5 flex items-center justify-between">
                <div class="w-24 h-24 flex-shrink-0 flex items-center justify-center">
                    @if(isset($pengaturan) && $pengaturan->logo_dinas)
                        <img src="{{ url('/uploads/' . $pengaturan->logo_dinas) }}" alt="Logo" class="max-w-full max-h-full object-contain">
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
                        <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo" class="max-w-full max-h-full object-contain">
                    @endif
                </div>
            </div>

            <table style="font-size: 11pt; margin-bottom: 15px;">
                <tr><td style="width: 80px; vertical-align: top;">Nomor</td><td style="width: 15px; vertical-align: top;">:</td><td>{{ $surat->nomor_surat }}</td></tr>
                <tr><td style="vertical-align: top;">Lampiran</td><td style="vertical-align: top;">:</td><td>-</td></tr>
                <tr><td style="vertical-align: top;">Perihal</td><td style="vertical-align: top;">:</td><td><b>Panggilan Orang Tua / Wali Murid</b></td></tr>
            </table>

            <div style="margin-bottom: 20px;">
                Kepada Yth.<br>
                Bapak/Ibu Orang Tua / Wali Murid dari:<br>
                Siswa bernama <b>{{ $surat->siswa->nama_lengkap ?? '-' }}</b><br>
                Di Tempat
            </div>

            <p style="text-align: justify; margin-bottom: 10px;">Dengan hormat,</p>
            <p style="text-indent: 1cm; text-align: justify; margin-bottom: 10px;">
                Sehubungan dengan adanya hal penting yang perlu kami sampaikan terkait perkembangan dan evaluasi belajar putra/putri Bapak/Ibu di sekolah, maka dengan ini kami memanggil Orang Tua/Wali Murid dari siswa:
            </p>

            <table class="tabel-data">
                <tr><td>Nama</td><td>:</td><td class="font-bold uppercase">{{ $surat->siswa->nama_lengkap ?? '-' }}</td></tr>
                <tr><td>Tempat, Tanggal Lahir</td><td>:</td><td class="font-bold uppercase">{{ $surat->siswa->tempat_lahir ?? '-' }}, {{ $surat->siswa->tanggal_lahir ? \Carbon\Carbon::parse($surat->siswa->tanggal_lahir)->isoFormat('D MMMM Y') : '-' }}</td></tr>
                <tr><td>NIS / NISN</td><td>:</td><td class="font-bold">{{ $surat->siswa->nis ?? '-' }} / {{ $surat->siswa->nisn ?? '-' }}</td></tr>
                <tr><td>Kelas</td><td>:</td><td class="font-bold">{{ $namaKelas }}</td></tr>
            </table>

            <p style="text-indent: 1cm; text-align: justify; margin-bottom: 10px;">
                Untuk dapat hadir pada:
            </p>

            <table class="tabel-data">
                <tr><td>Hari/Tanggal</td><td>:</td><td class="font-bold">{{ \Carbon\Carbon::parse($surat->tanggal_panggilan)->isoFormat('dddd, D MMMM Y') }}</td></tr>
                <tr><td>Pukul</td><td>:</td><td class="font-bold">{{ date('H:i', strtotime($surat->waktu_panggilan)) }} WIB s.d Selesai</td></tr>
                <tr><td>Tempat</td><td>:</td><td class="font-bold">{{ $surat->tempat_pertemuan }}</td></tr>
                <tr><td>Keperluan</td><td>:</td><td class="font-bold">{{ $surat->alasan_panggilan }}</td></tr>
            </table>

            <p style="text-indent: 1cm; text-align: justify; margin-bottom: 10px;">
                Mengingat pentingnya pertemuan ini, kami sangat mengharapkan kehadiran Bapak/Ibu tepat pada waktunya. Kehadiran Bapak/Ibu merupakan bentuk kerja sama yang baik antara pihak sekolah dan orang tua dalam mendidik putra/putri kita.
            </p>
            <p style="text-indent: 1cm; text-align: justify;">Demikian surat panggilan ini kami sampaikan. Atas perhatian dan kerja samanya, kami ucapkan terima kasih.</p>

            <div class="ttd-area">
                Malingping, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->isoFormat('D MMMM Y') }}<br>
                
                @if($isKepalaSekolah)
                    Kepala {{ $pengaturan->nama_sekolah ?? 'SMA Negeri 1 Malingping' }}<br>
                @elseif($isWakasek)
                    a.n. Kepala Sekolah<br>Wakil Kepala Sekolah Bidang Kesiswaan<br>
                @else
                    Wali Kelas {{ $namaKelas }}<br>
                @endif

                <div style="position: relative; margin: auto; display: block; padding: 4px; width: 105px; height: 105px;">
                    <img src="{{ $qrCodeImage }}" alt="QR Code" style="width: 100%; height: 100%; object-fit: contain;">
                    @if(isset($pengaturan) && $pengaturan->logo_sekolah)
                        <div style="position: absolute; top: 50%; left: 50%; width: 34px; height: 34px; margin-top: -17px; margin-left: -17px; background: white; padding: 2px; border-radius: 8px; border:1px solid #d7cccc;">
                            <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                    @endif
                </div>

                <b><u>{{ $penandatanganAktif->nama ?? '........................................' }}</u></b><br>
                NIP. {{ $penandatanganAktif->nip ?? '-' }}
            </div>
            
            <div style="clear: both;"></div>

            <div class="footer-layar">
                Dokumen ini diterbitkan melalui Smart-M1 dan dapat diverifikasi keasliannya melalui QRCode.
            </div>

        </div>
    </div>
    <script> window.onload = function() { setTimeout(() => { window.print(); }, 800); } </script>
</body>
</html>