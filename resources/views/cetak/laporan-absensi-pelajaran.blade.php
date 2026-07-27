<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi Pelajaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Menggunakan kertas A4 memanjang (Landscape) agar muat banyak kolom bulan */
        @page {
            size: A4 landscape;
            margin: 1.5cm;
        }
        
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        /* Mencegah baris tabel terpotong di tengah halaman */
        .avoid-break { page-break-inside: avoid; }
        
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
            /* Mereset tampilan khusus cetak agar memenuhi kertas */
            .cetak-kertas {
                width: 100% !important; 
                margin: 0 !important; 
                padding: 0 !important; 
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body class="bg-gray-200 text-gray-900 font-sans text-xs">

    <!-- Tombol Navigasi (Otomatis hilang saat dicetak) -->
    <div class="no-print fixed top-5 left-5 z-50 flex gap-2">
        <button onclick="window.close()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 transition">
            &larr; Tutup Tab
        </button>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500 transition font-bold">
            Cetak / Simpan PDF
        </button>
    </div>

    <!-- Wrapper pelindung -->
    <div class="flex flex-col items-center py-10 gap-10 print:py-0 print:block min-w-max">
        
        <!-- LOOPING BERDASARKAN KELAS (Pisah Kertas Otomatis per Kelas) -->
        @forelse($siswasGrouped as $namaKelasGroup => $siswas)
            
            <div class="cetak-kertas bg-white shadow-2xl rounded p-[1.5cm] mx-auto min-w-[29.7cm] print:mb-0 print:break-after-page">
                
                <!-- KOP LAPORAN -->
                <div class="border-b-4 border-gray-800 pb-2 mb-4 text-center">
                    <h1 class="text-xl font-bold uppercase tracking-wider">SMAN 1 MALINGPING</h1>
                    <p class="text-[13px] font-bold">REKAPITULASI KEHADIRAN PER MATA PELAJARAN</p>
                    <p class="text-[11px] mt-1 text-gray-700">
                        Mata Pelajaran: <strong class="uppercase">{{ $mataPelajaran->nama_pelajaran ?? '-' }}</strong> 
                        | Kelas: <strong>{{ $namaKelasGroup }}</strong>
                    </p>
                    <p class="text-[10px] text-gray-500">
                        Periode Penilaian: {{ $startDate->isoFormat('D MMMM Y') }} s/d {{ $endDate->isoFormat('D MMMM Y') }} 
                    </p>
                </div>

                <!-- TABEL REKAPITULASI -->
                <table class="w-full border-collapse border border-gray-600 mb-4">
                    <thead>
                        <tr class="bg-gray-200 text-center">
                            <th rowspan="2" class="border border-gray-600 p-1 w-8">No</th>
                            <th rowspan="2" class="border border-gray-600 p-1 w-48">Nama Siswa</th>
                            
                            <!-- Header Bulan Dinamis berdasarkan rentang waktu yang dipilih -->
                            @foreach($months as $m)
                                <th colspan="4" class="border border-gray-600 p-1">{{ $m['label'] }}</th>
                            @endforeach
                            
                            <!-- Header Kolom Total -->
                            <th colspan="4" class="border border-gray-600 p-1 bg-yellow-100 font-bold">TOTAL KESELURUHAN</th>
                        </tr>
                        <tr class="bg-gray-100 text-center text-[10px]">
                            @foreach($months as $m)
                                <th class="border border-gray-600 p-1 text-yellow-700" title="Sakit">S</th>
                                <th class="border border-gray-600 p-1 text-blue-700" title="Izin">I</th>
                                <th class="border border-gray-600 p-1 text-red-700" title="Alpa">A</th>
                                <th class="border border-gray-600 p-1 text-purple-700" title="Terlambat">T</th>
                            @endforeach
                            <!-- Total Headers -->
                            <th class="border border-gray-600 p-1 bg-yellow-100" title="Total Sakit">S</th>
                            <th class="border border-gray-600 p-1 bg-yellow-100" title="Total Izin">I</th>
                            <th class="border border-gray-600 p-1 bg-yellow-100" title="Total Alpa">A</th>
                            <th class="border border-gray-600 p-1 bg-yellow-100" title="Total Terlambat">T</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswas as $index => $siswa)
                            <tr class="avoid-break hover:bg-gray-50">
                                <td class="border border-gray-600 p-1 text-center">{{ $index + 1 }}</td>
                                <td class="border border-gray-600 p-1 font-semibold uppercase">{{ $siswa->nama_lengkap }}</td>
                                
                                <!-- Looping Data Rekapitulasi Per Bulan -->
                                @foreach($months as $m)
                                    @php
                                        $s = $dataRekap[$siswa->id][$m['key']]['Sakit'] ?? 0;
                                        $i = $dataRekap[$siswa->id][$m['key']]['Izin'] ?? 0;
                                        $a = $dataRekap[$siswa->id][$m['key']]['Alpa'] ?? 0;
                                        $t = $dataRekap[$siswa->id][$m['key']]['Terlambat'] ?? 0;
                                    @endphp
                                    <td class="border border-gray-600 p-1 text-center {{ $s > 0 ? 'font-bold' : 'text-gray-400' }}">{{ $s ?: '-' }}</td>
                                    <td class="border border-gray-600 p-1 text-center {{ $i > 0 ? 'font-bold' : 'text-gray-400' }}">{{ $i ?: '-' }}</td>
                                    <td class="border border-gray-600 p-1 text-center {{ $a > 0 ? 'font-bold text-red-600' : 'text-gray-400' }}">{{ $a ?: '-' }}</td>
                                    <td class="border border-gray-600 p-1 text-center {{ $t > 0 ? 'font-bold text-purple-600' : 'text-gray-400' }}">{{ $t ?: '-' }}</td>
                                @endforeach

                                <!-- Looping Data Total Keseluruhan Siswa -->
                                @php
                                    $totS = $dataRekap[$siswa->id]['total']['Sakit'] ?? 0;
                                    $totI = $dataRekap[$siswa->id]['total']['Izin'] ?? 0;
                                    $totA = $dataRekap[$siswa->id]['total']['Alpa'] ?? 0;
                                    $totT = $dataRekap[$siswa->id]['total']['Terlambat'] ?? 0;
                                @endphp
                                <td class="border border-gray-600 p-1 text-center bg-yellow-50 font-bold">{{ $totS ?: '-' }}</td>
                                <td class="border border-gray-600 p-1 text-center bg-yellow-50 font-bold">{{ $totI ?: '-' }}</td>
                                <td class="border border-gray-600 p-1 text-center bg-red-50 font-bold text-red-600">{{ $totA ?: '-' }}</td>
                                <td class="border border-gray-600 p-1 text-center bg-purple-50 font-bold text-purple-600">{{ $totT ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- BAGIAN TANDA TANGAN (Dinamic Sesuai Guru Jurnal) -->
                <div class="mt-4 flex justify-between text-[11px] avoid-break">
                    <div>
                        <strong>Keterangan Status:</strong><br>
                        <span class="inline-block w-4">S</span> = Sakit<br>
                        <span class="inline-block w-4">I</span> = Izin<br>
                        <span class="inline-block w-4">A</span> = Alpa (Tanpa Keterangan)<br>
                        <span class="inline-block w-4">T</span> = Terlambat
                    </div>
                    <div class="text-center pr-10">
                        Malingping, {{ now()->isoFormat('D MMMM Y') }}<br>
                        Guru Mata Pelajaran,
                        <br><br><br><br>
                        
                        @if($guruMapel)
                            <span class="font-bold underline uppercase">{{ $guruMapel->name }}</span><br>
                            NIP. {{ $pegawaiMapel->nip ?? '-' }}
                        @else
                            ___________________________<br>
                            NIP. -
                        @endif
                    </div>
                </div>

            </div>

        @empty
            <div class="cetak-kertas bg-white shadow-2xl rounded p-10 mx-auto text-center font-bold text-gray-500">
                Tidak ada data absensi pelajaran yang ditemukan pada rentang waktu yang dipilih.
            </div>
        @endforelse

    </div>

    <!-- Script Print Otomatis -->
    <script>
        window.onload = function() {
            setTimeout(() => { window.print(); }, 800);
        }
    </script>
</body>
</html>