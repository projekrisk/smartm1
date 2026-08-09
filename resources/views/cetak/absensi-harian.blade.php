<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Harian - {{ $nama_kelas }} - {{ \Carbon\Carbon::parse($rekap->tanggal)->isoFormat('D MMMM Y') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        * { box-sizing: border-box; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        body, table, th, td, p, h1, h2, h3, span, div {
            font-family: Arial, Helvetica, sans-serif !important;
        }
        
        @page { size: A4 portrait; margin: 1cm 1.5cm; }
        
        .avoid-break { page-break-inside: avoid; }
        
        .info-table { width: 100%; margin-bottom: 15px; font-size: 13px; }
        .info-table td { padding: 4px 5px; vertical-align: top; }
        
        .tabel-cetak { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .tabel-cetak th, .tabel-cetak td { border: 1px solid #000; padding: 6px 8px; font-size: 12px; }
        .tabel-cetak th { background-color: #f3f4f6; font-weight: bold; text-align: center; text-transform: uppercase; }
        
        .cetak-kertas {
            width: 21cm;
            min-height: 29.7cm;
            padding: 1.5cm;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border-radius: 8px;
        }
        
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; margin: 0 !important; padding: 0 !important; }
            
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
<body class="bg-gray-200 text-gray-900 text-xs">

    <div class="no-print fixed top-5 left-5 z-50 flex gap-2">
        <button onclick="window.close()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 font-sans">&larr; Tutup Tab</button>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500 font-bold font-sans">Cetak PDF</button>
    </div>

    <div class="flex flex-col items-center py-10 print:py-0 print:block">
        <div class="cetak-kertas">
            
            <div class="border-b-4 border-gray-800 pb-3 mb-6 flex items-center justify-between">
                <div class="w-24 h-24 flex-shrink-0 flex items-center justify-center">
                    @php
                        $pengaturan = null;
                        try { if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) $pengaturan = \App\Models\Pengaturan::first(); } catch (\Exception $e) {}
                    @endphp
                    
                    @if(isset($pengaturan) && $pengaturan->logo_dinas)
                        <img src="{{ url('/uploads/' . $pengaturan->logo_dinas) }}" alt="Logo Dinas" class="max-w-full max-h-full object-contain">
                    @else
                        <div class="w-20 h-20 border-2 border-dashed border-gray-400 text-gray-400 flex items-center justify-center text-[10px] text-center">Logo<br>Dinas</div>
                    @endif
                </div>

                <div class="flex-1 text-center px-4">
                    <h1 class="text-xl font-bold uppercase tracking-wider">PEMERINTAH PROVINSI BANTEN</h1>
                    <h1 class="text-xl font-bold uppercase tracking-wider leading-tight">DINAS PENDIDIKAN DAN KEBUDAYAAN</h1>
                    <h1 class="text-2xl font-bold uppercase tracking-wider mt-1">{{ $pengaturan->nama_sekolah ?? 'SMAN 1 MALINGPING' }}</h1>
                    <p class="text-[11px] mt-1 font-bold">NPSN: 20601875 AKREDITASI: A (96)</p>
                    <p class="text-[11px]">Jl. Raya Bayah KM. 4 No. 39 Malingping – Lebak, 42391</p>
                </div>

                <div class="w-24 h-24 flex-shrink-0 flex items-center justify-center">
                    @if(isset($pengaturan) && $pengaturan->logo_sekolah)
                        <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo Sekolah" class="max-w-full max-h-full object-contain">
                    @else
                        <div class="w-20 h-20 border-2 border-dashed border-gray-400 text-gray-400 flex items-center justify-center text-[10px] text-center">Logo<br>Sekolah</div>
                    @endif
                </div>
            </div>

            <div class="text-center mb-6">
                <h2 class="text-lg font-bold underline uppercase">Laporan Kehadiran Harian Siswa</h2>
                <p class="text-[12px] mt-1">Aplikasi Manajemen Akademik Terpadu</p>
            </div>

            <table class="info-table">
                <tr>
                    <td style="width: 100px;"><strong>Kelas</strong></td>
                    <td style="width: 10px;">:</td>
                    <td style="font-weight: bold;">{{ $nama_kelas }}</td>
                    
                    <td style="width: 120px;"><strong>Tanggal</strong></td>
                    <td style="width: 10px;">:</td>
                    <td>{{ \Carbon\Carbon::parse($rekap->tanggal)->isoFormat('dddd, D MMMM YYYY') }}</td>
                </tr>
                <tr>
                    <td><strong>Status Validasi</strong></td>
                    <td>:</td>
                    <td>{{ $rekap->is_valid ? 'Tervalidasi' : 'Belum Divalidasi' }}</td>
                    
                    <td><strong>Divalidasi Oleh</strong></td>
                    <td>:</td>
                    <td>{{ $rekap->validator->name ?? '-' }}</td>
                </tr>
            </table>

            <table class="tabel-cetak">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th style="width: 120px;">NIS / NISN</th>
                        <th style="text-align: left;">Nama Lengkap Siswa</th>
                        <th style="width: 120px;">Status Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekap->kehadiranHarian as $index => $hadir)
                        <tr class="avoid-break">
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td style="text-align: center;">{{ $hadir->siswa->nis ?? '-' }} / {{ $hadir->siswa->nisn ?? '-' }}</td>
                            <td style="text-transform: uppercase; font-weight: 600;">{{ $hadir->siswa->nama_lengkap ?? 'Siswa Terhapus' }}</td>
                            <td style="text-align: center;">
                                @if($hadir->status === 'Hadir')
                                    <span>Hadir</span>
                                @elseif($hadir->status === 'Sakit')
                                    <span style="font-weight: bold;">Sakit</span>
                                @elseif($hadir->status === 'Izin')
                                    <span style="font-weight: bold;">Izin</span>
                                @elseif($hadir->status === 'Alpa')
                                    <span style="font-weight: bold; color: red;">Alpa</span>
                                @elseif($hadir->status === 'Dispensasi')
                                    <span style="font-weight: bold; color: #4b5563;">Dispensasi</span>
                                @else
                                    <span>{{ $hadir->status ?? '-' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 20px; font-style: italic;">
                                Belum ada data kehadiran untuk kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @php
                $totalHadir = $rekap->kehadiranHarian->where('status', 'Hadir')->count();
                $totalSakit = $rekap->kehadiranHarian->where('status', 'Sakit')->count();
                $totalIzin  = $rekap->kehadiranHarian->where('status', 'Izin')->count();
                $totalAlpa  = $rekap->kehadiranHarian->where('status', 'Alpa')->count();
                $totalDispensasi = $rekap->kehadiranHarian->where('status', 'Dispensasi')->count();
                $totalSiswa = $rekap->kehadiranHarian->count();
            @endphp

            <div class="avoid-break" style="margin-top: 20px;">
                <div style="border: 1px solid #000; padding: 10px; width: 250px; float: left; font-size: 13px;">
                    <strong>Ringkasan Kehadiran:</strong><br>
                    <table style="width: 100%; margin-top: 5px;">
                        <tr><td>Total Siswa</td><td>: <strong>{{ $totalSiswa }}</strong> Orang</td></tr>
                        <tr><td>Hadir</td><td>: <strong>{{ $totalHadir }}</strong> Orang</td></tr>
                        <tr><td>Sakit</td><td>: <strong>{{ $totalSakit }}</strong> Orang</td></tr>
                        <tr><td>Izin</td><td>: <strong>{{ $totalIzin }}</strong> Orang</td></tr>
                        <tr><td>Alpa</td><td>: <strong style="color: red;">{{ $totalAlpa }}</strong> Orang</td></tr>
                        <tr><td>Dispensasi</td><td>: <strong>{{ $totalDispensasi }}</strong> Orang</td></tr>
                    </table>
                </div>

                <div style="float: right; text-align: center; width: 300px; font-size: 13px;">
                    Malingping, {{ \Carbon\Carbon::parse($rekap->tanggal)->isoFormat('D MMMM YYYY') }}<br>
                    Petugas Validasi,
                    <br><br><br><br>
                    <strong style="text-decoration: underline;">
                        {{ $rekap->validator->name ?? '_____________________________' }}
                    </strong>
                </div>
                <div style="clear: both;"></div>
            </div>

        </div>
    </div>

    <script>
        window.onload = function() { 
            setTimeout(() => { window.print(); }, 1000); 
        }
    </script>
</body>
</html>