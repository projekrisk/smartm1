<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat & Portofolio - {{ $siswa->nama_lengkap }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4 portrait;
            margin: 1cm;
        }
        
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        body {
            font-family: Arial, Helvetica, sans-serif !important;
            font-size: 11pt !important;
            line-height: 1.5;
        }
        
        table, tr, td, th, p, span, div {
            font-size: 11pt;
        }

        .kop-1 { font-size: 13pt !important; }
        .kop-2 { font-size: 15pt !important; }

        .watermark-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background-repeat: repeat;
        }

        .cetak-kertas {
            position: relative;
            padding-bottom: 2.5cm !important;
        }
        
        .print-footer {
            position: absolute;
            bottom: 1cm;
            left: 1cm;
            right: 1cm;
            border-top: 2px solid #1f2937;
            padding-top: 10px;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: white !important;
            }
            .cetak-kertas {
                display: block !important;
                position: static !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                min-height: auto !important;
            }
            
            .print-footer {
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
            }
            
            .footer-space {
                height: 70px;
            }
        }
    </style>
</head>
<body class="bg-gray-200 text-gray-900">

    <div class="no-print fixed top-5 left-5 z-50">
        <button onclick="window.close()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 transition">
            &larr; Tutup Halaman
        </button>
    </div>
    <div class="no-print fixed top-5 right-5 z-50">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500 transition font-bold">
            Cetak Dokumen
        </button>
    </div>

    @php
        $pengaturan = null;
        try { if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) $pengaturan = \App\Models\Pengaturan::first(); } catch (\Exception $e) {}

        $namaSekolah = strtoupper($pengaturan->nama_sekolah ?? 'SMA NEGERI 1 MALINGPING');
        $watermarkText = 'DATA ' . $namaSekolah;
        
        $svg = '
        <svg xmlns="http://www.w3.org/2000/svg" width="280" height="150">
            <text x="50%" y="50%" transform="rotate(-30 140 75)" text-anchor="middle" font-family="Arial, sans-serif" font-size="11" font-weight="bold" fill="rgba(150,150,150,0.18)">
                ' . $watermarkText . '
            </text>
        </svg>';
        
        $base64Svg = base64_encode($svg);
    @endphp

    <div class="flex justify-center py-10 print:py-0 print:block">
        
        <div class="cetak-kertas bg-white shadow-2xl w-[21cm] min-h-[29.7cm] p-[1cm] mx-auto block">
            
            <div class="watermark-bg" style="background-image: url('data:image/svg+xml;base64,{{ $base64Svg }}');"></div>

            <table style="width: 100%; position: relative; z-index: 10;">
                <tbody>
                    <tr>
                        <td>
                            
                            <div class="border-b-4 border-gray-800 pb-6 mb-6 flex items-center justify-between avoid-break">
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

                            <div class="text-center mb-6 avoid-break">
                                <h2 class="font-bold underline uppercase" style="font-size: 13pt;">Laporan Portofolio & Catatan Siswa</h2>
                            </div>

                            <div class="flex items-start gap-4 mb-6 avoid-break p-3 border border-gray-400 bg-gray-50 rounded">                                
                                <div class="flex-1">
                                    <table class="w-full">
                                        <tr><td class="py-1 w-32 font-bold">Nama Lengkap</td><td class="w-2">:</td><td class="py-1 uppercase font-bold">{{ $siswa->nama_lengkap }}</td></tr>
                                        <tr><td class="py-1 font-bold">NIS / NISN</td><td>:</td><td class="py-1">{{ $siswa->nis }} / {{ $siswa->nisn ?? '-' }}</td></tr>
                                        <tr><td class="py-1 font-bold">Kelas Saat Ini</td><td>:</td><td class="py-1 font-bold">{{ $siswa->kelas->nama_kelas ?? 'Belum ada kelas' }}</td></tr>
                                        <tr><td class="py-1 font-bold">Status</td><td>:</td><td class="py-1 uppercase">{{ $siswa->status_siswa }} ({{ $siswa->jalur_masuk ?? 'Siswa Baru' }})</td></tr>
                                    </table>
                                </div>      
                                <div class="w-[3.5cm] h-[4.5cm] border-2 border-gray-800 p-1 flex items-center justify-center overflow-hidden bg-white flex-shrink-0 relative z-20">
                                    @if($siswa->foto)
                                        <img src="{{ url('/uploads/' . $siswa->foto) }}" alt="Foto" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-gray-400 text-center text-[10pt]">Pas Foto<br>3 x 4</span>
                                    @endif
                                </div>                          
                            </div>

                            <div class="mb-6 avoid-break">
                                <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase border border-gray-400">A. Riwayat Kelas & Mutasi</h3>
                                <table class="w-full border-collapse border border-gray-400 text-center">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border border-gray-400 p-2 w-12">No</th>
                                            <th class="border border-gray-400 p-2">Tahun Ajaran</th>
                                            <th class="border border-gray-400 p-2">Kelas</th>
                                            <th class="border border-gray-400 p-2">Status Riwayat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($siswa->riwayatKelas as $index => $riwayat)
                                            <tr>
                                                <td class="border border-gray-400 p-2">{{ $index + 1 }}</td>
                                                <td class="border border-gray-400 p-2">{{ $riwayat->tahunAjaran->nama_tahun ?? '-' }}</td>
                                                <td class="border border-gray-400 p-2">{{ $riwayat->kelas->nama_kelas ?? '-' }}</td>
                                                <td class="border border-gray-400 p-2">{{ $riwayat->status_riwayat }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="border border-gray-400 p-3 italic text-gray-500">Belum ada riwayat kelas yang tercatat.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="avoid-break mb-6">
                                <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase border border-gray-400">B. Catatan Siswa (Buku Kasus)</h3>
                                
                                @if($siswa->catatan->count() > 0)
                                    <table class="w-full border-collapse border border-gray-400">
                                        <thead>
                                            <tr class="bg-gray-100 text-center">
                                                <th class="border border-gray-400 p-2 w-32">Tanggal</th>
                                                <th class="border border-gray-400 p-2 w-28">Jenis</th>
                                                <th class="border border-gray-400 p-2">Kasus / Judul Catatan</th>
                                                <th class="border border-gray-400 p-2 w-40">Pencatat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($siswa->catatan->sortByDesc('tanggal') as $catatan)
                                                <tr>
                                                    <td class="border border-gray-400 p-2 text-center align-top">{{ \Carbon\Carbon::parse($catatan->tanggal)->format('d/m/Y') }}</td>
                                                    <td class="border border-gray-400 p-2 text-center align-top font-bold 
                                                        {{ $catatan->jenis_catatan == 'Positif' ? 'text-green-700' : ($catatan->jenis_catatan == 'Negatif' ? 'text-red-700' : 'text-blue-700') }}">
                                                        {{ $catatan->jenis_catatan }}
                                                    </td>
                                                    <td class="border border-gray-400 p-2 align-top">
                                                        <strong class="block mb-1">{{ $catatan->judul_catatan }}</strong>
                                                        <span class="text-gray-700">{{ $catatan->isi_catatan }}</span>
                                                    </td>
                                                    <td class="border border-gray-400 p-2 text-center align-top">{{ $catatan->pencatat->name ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="border border-gray-400 p-4 text-center italic text-gray-500 bg-gray-50">
                                        Belum ada catatan atau buku kasus untuk siswa ini.
                                    </div>
                                @endif
                            </div>

                            <div class="avoid-break mb-6">
                                <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase border border-gray-400">C. Riwayat Prestasi Siswa</h3>
                                
                                @if(isset($siswa->prestasi) && $siswa->prestasi->count() > 0)
                                    <table class="w-full border-collapse border border-gray-400 text-center">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border border-gray-400 p-2 w-12">No</th>
                                                <th class="border border-gray-400 p-2 w-32">Tgl Perolehan</th>
                                                <th class="border border-gray-400 p-2">Nama Prestasi & Penyelenggara</th>
                                                <th class="border border-gray-400 p-2 w-48">Juara & Tingkat</th>
                                                <th class="border border-gray-400 p-2 w-28">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($siswa->prestasi->sortByDesc('tanggal_perolehan') as $index => $pres)
                                                <tr>
                                                    <td class="border border-gray-400 p-2 align-top">{{ $index + 1 }}</td>
                                                    <td class="border border-gray-400 p-2 align-top">{{ \Carbon\Carbon::parse($pres->tanggal_perolehan)->format('d/m/Y') }}</td>
                                                    <td class="border border-gray-400 p-2 text-left align-top">
                                                        <strong class="block mb-1 text-green-700">{{ $pres->nama_prestasi }}</strong>
                                                        <span class="text-gray-700 text-sm">Oleh: {{ $pres->penyelenggara ?? '-' }} <br>({{ $pres->jenis ?? '-' }} - {{ $pres->kategori ?? '-' }})</span>
                                                    </td>
                                                    <td class="border border-gray-400 p-2 align-top font-bold text-blue-700">
                                                        {{ $pres->juara }}<br>
                                                        <span class="text-sm font-normal text-gray-600">Tingkat {{ $pres->tingkat }}</span>
                                                    </td>
                                                    <td class="border border-gray-400 p-2 align-top">
                                                        <span class="uppercase font-bold text-[10px] {{ $pres->status == 'Disetujui' ? 'text-green-600' : ($pres->status == 'Ditolak' ? 'text-red-600' : 'text-yellow-600') }}">
                                                            {{ $pres->status }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="border border-gray-400 p-4 text-center italic text-gray-500 bg-gray-50">
                                        Siswa belum memiliki riwayat prestasi yang dicatat.
                                    </div>
                                @endif
                            </div>

                            <div class="mb-6 avoid-break">
                                <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase border border-gray-400">D. Riwayat Surat Panggilan Orang Tua</h3>
                                <table class="w-full border-collapse border border-gray-400 text-center">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border border-gray-400 p-2 w-12">No</th>
                                            <th class="border border-gray-400 p-2 w-48">Tgl Pemanggilan</th>
                                            <th class="border border-gray-400 p-2">Keperluan / Keterangan</th>
                                            <th class="border border-gray-400 p-2 w-32">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($siswa->suratPanggilan->sortBy('tanggal_surat') as $index => $sp)
                                            <tr>
                                                <td class="border border-gray-400 p-2 align-top">{{ $index + 1 }}</td>
                                                <td class="border border-gray-400 p-2 align-top whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($sp->tanggal_panggilan)->format('d/m/Y') }}<br>
                                                    <span class="text-gray-600">{{ date('H:i', strtotime($sp->waktu_panggilan)) }} WIB</span>
                                                </td>
                                                <td class="border border-gray-400 p-2 text-left">{{ $sp->alasan_panggilan }}</td>
                                                <td class="border border-gray-400 p-2 align-top font-bold uppercase {{ $sp->status == 'Selesai' ? 'text-green-700' : ($sp->status == 'Dibuat' ? 'text-yellow-600' : 'text-red-700') }}">{{ $sp->status }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="border border-gray-400 p-3 italic text-gray-500">Siswa belum memiliki riwayat panggilan orang tua.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <div class="footer-space hidden print:block"></div>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <div class="print-footer relative z-20">
                <div class="text-gray-700 text-[10pt]">
                    Dicetak pada Smart-M1 {{ $pengaturan->nama_sekolah ?? 'Sekolah' }} | Waktu Cetak: {{ now()->isoFormat('D MMMM Y - HH:mm') }} WIB
                </div>
            </div>

        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 800);
        }
    </script>
</body>
</html>