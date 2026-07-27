<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi Harian - {{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y') }}</title>
    <!-- Tailwind untuk struktur luar di layar monitor -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        * { box-sizing: border-box; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        body, table, th, td, p, h1, h2, h3, span, div {
            font-family: Arial, Helvetica, sans-serif !important;
        }
        
        /* Mengatur Margin Fisik Printer */
        @page { size: A4 portrait; margin: 1cm 1.5cm; }
        
        .avoid-break { page-break-inside: avoid; }
        
        /* Gaya Tabel Kaku (Anti-Gagal) */
        .tabel-cetak { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .tabel-cetak th, .tabel-cetak td { border: 1px solid #000; padding: 6px 8px; font-size: 12px; }
        .tabel-cetak th { background-color: #f3f4f6; font-weight: bold; text-align: center; text-transform: uppercase; }
        
        /* DESAIN KERTAS DI LAYAR MONITOR */
        .cetak-kertas {
            width: 21cm;
            min-height: 29.7cm;
            padding: 1.5cm;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border-radius: 8px;
        }
        
        /* KUNCI PERBAIKAN: MERESET DESAIN SAAT MASUK MESIN PRINTER */
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; margin: 0 !important; padding: 0 !important; }
            
            .cetak-kertas { 
                width: 100% !important; /* Paksa memenuhi lebar kertas */
                max-width: 100% !important;
                min-height: auto !important; /* Hapus paksaan tinggi agar tidak muncul lembar kosong */
                padding: 0 !important; /* Hapus padding karena sudah diwakilkan oleh margin @page */
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
        
        <!-- Hapus class p-[1.5cm] dll bawaan Tailwind di div ini, kita kontrol via css native agar aman di Printer -->
        <div class="cetak-kertas">
            
            <!-- ============================================== -->
            <!-- KOP SURAT RESMI (Kiri Dinas, Kanan Sekolah)    -->
            <!-- ============================================== -->
            <div class="border-b-4 border-gray-800 pb-3 mb-6 flex items-center justify-between">
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
                    <h1 class="text-xl font-bold uppercase tracking-wider">PEMERINTAH PROVINSI BANTEN</h1>
                    <h1 class="text-xl font-bold uppercase tracking-wider leading-tight">DINAS PENDIDIKAN DAN KEBUDAYAAN</h1>
                    <h1 class="text-2xl font-bold uppercase tracking-wider mt-1">{{ $pengaturan->nama_sekolah ?? 'SMAN 1 MALINGPING' }}</h1>
                    <p class="text-[11px] mt-1 font-bold">NPSN: 20601875 AKREDITASI: A (96)</p>
                    <p class="text-[11px]">Jl. Raya Bayah KM. 4 No. 39 Malingping – Lebak, 42391</p>
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

            <!-- JUDUL LAPORAN -->
            <div class="text-center mb-6">
                <h2 class="text-lg font-bold underline uppercase">Rekapitulasi Siswa Tidak Hadir</h2>
                <p class="text-[12px] mt-1">Tanggal: <strong>{{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}</strong></p>
            </div>

            <!-- ISI KONTEN / TABEL -->
            @if($groupedAndSorted->isEmpty())
                <div style="border: 1px solid #9ca3af; padding: 40px; text-align: center; font-style: italic; font-weight: bold; background-color: #f9fafb; font-size: 14px;">
                    Nihil. Tidak ada data siswa yang absen (Sakit, Izin, Alpa) pada hari ini.
                </div>
            @else
                <table class="tabel-cetak">
                    <thead>
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th style="width: 120px;">Kelas</th>
                            <th style="text-align: left;">Nama Lengkap Siswa</th>
                            <th style="width: 80px;">Status</th>
                            <th style="text-align: left; width: 30%;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $globalNo = 1; @endphp
                        @foreach($groupedAndSorted as $kelas => $siswas)
                            @foreach($siswas as $index => $item)
                                <tr class="avoid-break">
                                    <td style="text-align: center;">{{ $globalNo++ }}</td>
                                    
                                    <!-- Merge (Gabungkan) Kolom Kelas agar rapi dan tidak berulang -->
                                    @if($index === 0)
                                        <td rowspan="{{ count($siswas) }}" style="text-align: center; font-weight: bold; background-color: #f9fafb; vertical-align: middle;">
                                            {{ $kelas }}
                                        </td>
                                    @endif
                                    
                                    <td style="text-transform: uppercase; font-weight: 600;">{{ $item->siswa->nama_lengkap }}</td>
                                    <td style="text-align: center; font-weight: bold;">{{ $item->status }}</td>
                                    <td style="font-size: 11px;">{{ $item->keterangan ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @endif

            <!-- TANDA TANGAN -->
            <div style="display: flex; justify-content: flex-end; margin-top: 40px; font-size: 12px; page-break-inside: avoid;">
                <div style="text-align: center; width: 250px;">
                    Malingping, {{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y') }}<br>
                    Petugas Piket / Tata Usaha
                    <br><br><br><br>
                    <strong style="text-decoration: underline;">______________________________</strong>
                </div>
            </div>

        </div>
    </div>

    <!-- Script menunda print 1 detik agar logo dan CSS Native dirender penuh -->
    <script>
        window.onload = function() { 
            setTimeout(() => { window.print(); }, 1000); 
        }
    </script>
</body>
</html>