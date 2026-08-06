<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat Dispensasi - {{ $surat->nomor_surat_lengkap }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* PENGATURAN KERTAS A4 & FONT ARIAL */
        @page {
            size: A4 portrait;
            margin: 2cm;
        }
        
        * { box-sizing: border-box; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; font-family: Arial, Helvetica, sans-serif !important; }
        
        body { 
            font-size: 11pt; 
            line-height: 1.5; 
            margin: 0;
            padding: 0;
            color: black;
            background-color: #e5e7eb;
        }

        /* WADAH UTAMA KERTAS */
        .cetak-kertas {
            width: 21cm;
            min-height: 29.7cm;
            padding:2cm;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
        }

        /* KONTEN SURAT */
        .judul-surat { text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 0; font-size: 16pt;}
        .nomor-surat { text-align: center; margin-top: 0; margin-bottom: 25px; }
        
        .tabel-kegiatan { width: 100%; margin: 15px 0; }
        .tabel-kegiatan td { vertical-align: top; padding: 4px 0; }
        .tabel-kegiatan td:first-child { width: 160px; }

        .tabel-siswa { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .tabel-siswa th, .tabel-siswa td { border: 1px solid black; padding: 8px 10px; text-align: left; }
        
        /* AREA TANDA TANGAN */
        .ttd-area { width: 320px; float: right; text-align: left; margin-top: 30px; line-height: 1.3; margin-left: 420px; }
        .ttd-area b { font-size: 12pt; }
        
        .page-break { page-break-before: always; }
        .avoid-break { page-break-inside: avoid; }
        
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; padding: 0 !important; margin: 0 !important; }
            .cetak-kertas { 
                width: 100% !important;
                max-width: 100% !important;
                min-height: auto !important;
                padding: 0 !important;
                margin: 0 !important; 
                box-shadow: none !important; 
                border-radius: 0 !important;
            }
        }
    </style>
</head>
<body class="text-gray-900">

    <div class="no-print fixed top-5 left-5 z-50 flex gap-2">
        <button onclick="window.close()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 font-sans">&larr; Tutup Tab</button>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500 font-bold font-sans">Cetak PDF</button>
    </div>

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

        // PENGATURAN LOGO
        $pengaturan = null;
        try { if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) $pengaturan = \App\Models\Pengaturan::first(); } catch (\Exception $e) {}
    @endphp

    <div class="flex flex-col items-center py-10 print:py-0 print:block">
        
        <!-- HALAMAN 1: SURAT PENGANTAR -->
        <div class="cetak-kertas">
            
            <!-- KOP SURAT BERLOGO (Mirip Rekap Harian) -->
            <div class="border-b-4 border-gray-800 pb-3 mb-6 flex items-center justify-between avoid-break">
                <!-- Logo Dinas (Kiri) -->
                <div class="w-24 h-24 flex-shrink-0 flex items-center justify-center">
                    @if(isset($pengaturan) && $pengaturan->logo_dinas)
                        <img src="{{ url('/uploads/' . $pengaturan->logo_dinas) }}" alt="Logo Dinas" class="max-w-full max-h-full object-contain">
                    @else
                        <div class="w-20 h-20 border-2 border-dashed border-gray-400 text-gray-400 flex items-center justify-center text-[10px] text-center">Logo<br>Dinas</div>
                    @endif
                </div>

                <!-- Teks Kop Tengah -->
                <div class="flex-1 text-center px-4">
                    <h1 class="text-[14pt] font-bold uppercase">PEMERINTAH PROVINSI BANTEN</h1>
                    <h1 class="text-[14pt] font-bold uppercase">DINAS PENDIDIKAN DAN KEBUDAYAAN</h1>
                    <h1 class="text-[22pt] font-bold uppercase">{{ $pengaturan->nama_sekolah ?? 'SMA NEGERI 1 MALINGPING' }}</h1>
                    <p>NPSN: 20601875 AKREDITASI: A (96)</p>
                    <p>Jl. Raya Bayah KM. 4 No. 39 Malingping – Lebak, 42391</p>
                    <p>Website: https://sman1malingping.sch.id – Email: sman1malingping@ymail.com</p>
                </div>

                <!-- Logo Sekolah (Kanan) -->
                <div class="w-24 h-24 flex-shrink-0 flex items-center justify-center">
                    @if(isset($pengaturan) && $pengaturan->logo_sekolah)
                        <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo Sekolah" class="max-w-full max-h-full object-contain">
                    @else
                        <div class="w-20 h-20 border-2 border-dashed border-gray-400 text-gray-400 flex items-center justify-center text-[10px] text-center">Logo<br>Sekolah</div>
                    @endif
                </div>
            </div>

            <!-- ISI SURAT -->
            <div class="judul-surat uppercase">SURAT DISPENSASI BELAJAR</div>
            <div class="nomor-surat">Nomor: {{ $surat->nomor_surat_lengkap }}</div>

            <p style="text-indent: 1cm; text-align: justify; margin-bottom: 10px;">
                Dengan ini diberikan dispensasi belajar kepada peserta didik sebagaimana tercantum dalam Lampiran Surat Dispensasi Belajar untuk mengikuti kegiatan sebagai berikut:
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

            <p style="text-indent: 1cm; text-align: justify; margin-bottom: 10px;">
                Selama mengikuti kegiatan tersebut, peserta didik diberikan dispensasi dari kegiatan pembelajaran di sekolah sesuai waktu pelaksanaan kegiatan. Peserta didik tetap berkewajiban mengikuti ketentuan yang berlaku di sekolah serta menyelesaikan tugas pembelajaran yang ditinggalkan sesuai arahan guru mata pelajaran.
            </p>
            
            <p style="text-indent: 1cm; text-align: justify;">
                Demikian surat dispensasi belajar ini dibuat untuk dipergunakan sebagaimana mestinya.
            </p>

            <!-- BAGIAN TANDA TANGAN -->
            <div class="ttd-area avoid-break" style="float: right; width: 280px; text-align: center; margin-top: 20px;">
                Malingping, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->isoFormat('D MMMM Y') }}<br>
                
                @if($isKepalaSekolah)
                    Kepala {{ $pengaturan->nama_sekolah ?? 'SMA Negeri 1 Malingping' }}<br>
                @else
                    a.n. Kepala Sekolah<br>
                    Wakil Kepala Sekolah Bidang Kesiswaan<br>
                @endif

                @php
                    // URL Tujuan saat QR Code di-scan
                    $urlVerifikasi = route('verifikasi.dispensasi', $surat->id);
                    
                    // Ambil path logo sekolah dari pengaturan
                    $logoPath = ($pengaturan && $pengaturan->logo_sekolah) 
                        ? public_path('uploads/' . $pengaturan->logo_sekolah) 
                        : null;
                @endphp

                <!-- Kotak QR Code -->
                <div style="margin: 10px auto; display: inline-block; padding: 5px; background: white; border: 1px solid #ccc;">
                    @if($logoPath && file_exists($logoPath))
                        {!! QrCode::format('svg')->size(90)->errorCorrection('H')->merge($logoPath, 0.28, true)->generate($urlVerifikasi) !!}
                    @else
                        {!! QrCode::format('svg')->size(90)->errorCorrection('H')->generate($urlVerifikasi) !!}
                    @endif
                </div>

                <div style="font-size: 8pt; color: #555; margin-bottom: 5px;">Scan untuk verifikasi keaslian surat</div>

                <b style="font-size: 11pt;"><u>{{ $surat->penandatangan->nama ?? '........................................' }}</u></b><br>
                NIP. {{ $surat->penandatangan->nip ?? '........................................' }}
            </div>

            <div style="clear: both;"></div>

        </div>

        <!-- HALAMAN 2: LAMPIRAN SISWA -->
        <div class="cetak-kertas page-break print:mt-0 mt-10">
            
            <h3 style="text-align: left; margin-bottom: 15px; text-decoration: underline; font-size: 12pt;">LAMPIRAN SURAT DISPENSASI</h3>
            
            <table style="width: 100%; margin-bottom: 20px;">
                <tr>
                    <td style="width: 120px;">Nomor Surat</td>
                    <td style="width: 15px;">:</td>
                    <td class="font-bold">{{ $surat->nomor_surat_lengkap }}</td>
                </tr>
                <tr>
                    <td>Kegiatan</td>
                    <td>:</td>
                    <td class="font-bold">{{ $surat->nama_kegiatan }}</td>
                </tr>
            </table>

            <table class="tabel-siswa">
                <thead>
                    <tr style="background-color: #f3f4f6;">
                        <th style="width: 5%; text-align: center;">No</th>
                        <th style="width: 25%;">NISN / NIS</th>
                        <th style="width: 50%;">Nama Peserta Didik</th>
                        <th style="width: 20%; text-align: center;">Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($surat->siswa->sortBy('nama_lengkap') as $index => $s)
                    <tr class="avoid-break">
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $s->nisn ?? '-' }} / {{ $s->nis ?? '-' }}</td>
                        <td class="uppercase font-semibold">{{ $s->nama_lengkap }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $s->kelas->nama_kelas ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <script>
        window.onload = function() { 
            setTimeout(() => { window.print(); }, 800); 
        }
    </script>
</body>
</html>