<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi - {{ $record->kelas->nama_kelas ?? 'Umum' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.5cm;
        }
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .avoid-break { page-break-inside: avoid; }
        
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
            .cetak-kertas {
                width: 100% !important; margin: 0 !important; padding: 0 !important; box-shadow: none !important;
            }
        }
    </style>
</head>
<body class="bg-gray-200 text-gray-900 font-sans text-xs">

    <div class="no-print fixed top-5 left-5 z-50 flex gap-2">
        <button onclick="window.close()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 transition">
            &larr; Tutup Tab
        </button>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500 transition font-bold">
            Cetak / Simpan PDF
        </button>
    </div>

    <div class="flex flex-col items-center py-10 gap-10 print:py-0 print:block min-w-max">
        
        @forelse($siswasGrouped as $namaKelasGroup => $siswas)
            
            <div class="cetak-kertas bg-white shadow-2xl rounded p-[1.5cm] mx-auto min-w-[29.7cm] print:mb-0 print:break-after-page">
                
                <div class="border-b-4 border-gray-800 pb-2 mb-4 text-center">
                    <h1 class="text-xl font-bold uppercase tracking-wider">SMAN 1 MALINGPING</h1>
                    <p class="text-xs">Laporan Rekapitulasi Kehadiran Siswa</p>
                    <p class="text-[10px] mt-1 text-gray-600">
                        Periode: {{ $startDate->isoFormat('D MMMM Y') }} s/d {{ $endDate->isoFormat('D MMMM Y') }} 
                        | Kelas: <strong>{{ $namaKelasGroup }}</strong>
                    </p>
                </div>

                <table class="w-full border-collapse border border-gray-600 mb-4">
                    <thead>
                        <tr class="bg-gray-200 text-center">
                            <th rowspan="2" class="border border-gray-600 p-1 w-8">No</th>
                            <th rowspan="2" class="border border-gray-600 p-1 w-48">Nama Siswa</th>
                            
                            @foreach($months as $m)
                                <!-- Ubah colspan menjadi 4 untuk memuat kolom Dispensasi -->
                                <th colspan="4" class="border border-gray-600 p-1">{{ $m['label'] }}</th>
                            @endforeach
                            
                            <!-- Ubah colspan TOTAL menjadi 4 -->
                            <th colspan="4" class="border border-gray-600 p-1 bg-yellow-100 font-bold">TOTAL</th>
                        </tr>
                        <tr class="bg-gray-100 text-center text-[10px]">
                            @foreach($months as $m)
                                <th class="border border-gray-600 p-1 text-yellow-700">S</th>
                                <th class="border border-gray-600 p-1 text-blue-700">I</th>
                                <th class="border border-gray-600 p-1 text-red-700">A</th>
                                <th class="border border-gray-600 p-1 text-gray-700">D</th>
                            @endforeach
                            <th class="border border-gray-600 p-1 bg-yellow-100">S</th>
                            <th class="border border-gray-600 p-1 bg-yellow-100">I</th>
                            <th class="border border-gray-600 p-1 bg-yellow-100">A</th>
                            <th class="border border-gray-600 p-1 bg-yellow-100">D</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswas as $index => $siswa)
                            <tr class="avoid-break hover:bg-gray-50">
                                <td class="border border-gray-600 p-1 text-center">{{ $index + 1 }}</td>
                                <td class="border border-gray-600 p-1 font-semibold uppercase">{{ $siswa->nama_lengkap }}</td>
                                
                                @foreach($months as $m)
                                    @php
                                        $s = $dataRekap[$siswa->id][$m['key']]['Sakit'] ?? 0;
                                        $i = $dataRekap[$siswa->id][$m['key']]['Izin'] ?? 0;
                                        $a = $dataRekap[$siswa->id][$m['key']]['Alpa'] ?? 0;
                                        $d = $dataRekap[$siswa->id][$m['key']]['Dispensasi'] ?? 0;
                                    @endphp
                                    <td class="border border-gray-600 p-1 text-center {{ $s > 0 ? 'font-bold' : 'text-gray-400' }}">{{ $s ?: '-' }}</td>
                                    <td class="border border-gray-600 p-1 text-center {{ $i > 0 ? 'font-bold' : 'text-gray-400' }}">{{ $i ?: '-' }}</td>
                                    <td class="border border-gray-600 p-1 text-center {{ $a > 0 ? 'font-bold text-red-600' : 'text-gray-400' }}">{{ $a ?: '-' }}</td>
                                    <td class="border border-gray-600 p-1 text-center {{ $d > 0 ? 'font-bold text-gray-700' : 'text-gray-400' }}">{{ $d ?: '-' }}</td>
                                @endforeach

                                @php
                                    $totS = $dataRekap[$siswa->id]['total']['Sakit'] ?? 0;
                                    $totI = $dataRekap[$siswa->id]['total']['Izin'] ?? 0;
                                    $totA = $dataRekap[$siswa->id]['total']['Alpa'] ?? 0;
                                    $totD = $dataRekap[$siswa->id]['total']['Dispensasi'] ?? 0;
                                @endphp
                                <td class="border border-gray-600 p-1 text-center bg-yellow-50 font-bold">{{ $totS ?: '-' }}</td>
                                <td class="border border-gray-600 p-1 text-center bg-yellow-50 font-bold">{{ $totI ?: '-' }}</td>
                                <td class="border border-gray-600 p-1 text-center bg-red-50 font-bold text-red-600">{{ $totA ?: '-' }}</td>
                                <td class="border border-gray-600 p-1 text-center bg-gray-50 font-bold text-gray-700">{{ $totD ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-2 flex justify-between text-[11px] avoid-break">
                    <div>
                        <strong>Keterangan:</strong><br>
                        S = Sakit<br>
                        I = Izin<br>
                        A = Alpa (Tanpa Keterangan)<br>
                        D = Dispensasi (Tugas Sekolah / Lainnya)
                    </div>
                    <div class="text-center">
                        Malingping, {{ now()->isoFormat('D MMMM Y') }}<br>
                        Mengetahui, Wali Kelas <strong>{{ $namaKelasGroup }}</strong>
                        <br><br><br><br>
                        
                        @php
                            $kelasObj = $siswas->first()->kelas ?? null;
                            $namaWali = '___________________________';
                            $nipWali = '-';
                            
                            if ($kelasObj && $kelasObj->wali_kelas_id) {
                                $userWali = \App\Models\User::find($kelasObj->wali_kelas_id);
                                if ($userWali) {
                                    $namaWali = $userWali->name;
                                }
                                
                                $pegawai = \App\Models\Pegawai::where('user_id', $kelasObj->wali_kelas_id)->first();
                                if ($pegawai && $pegawai->nip) {
                                    $nipWali = $pegawai->nip;
                                }
                            }
                        @endphp

                        @if($namaWali !== '___________________________')
                            <span class="font-bold underline uppercase">{{ $namaWali }}</span><br>
                            NIP. {{ $nipWali }}
                        @else
                            ___________________________<br>
                            NIP. -
                        @endif
                    </div>
                </div>

            </div>

        @empty
            <div class="cetak-kertas bg-white shadow-2xl rounded p-10 mx-auto text-center font-bold text-gray-500">
                Tidak ada data siswa ditemukan untuk kriteria tersebut.
            </div>
        @endforelse

    </div>

    <script>
        window.onload = function() {
            setTimeout(() => { window.print(); }, 800);
        }
    </script>
</body>
</html>