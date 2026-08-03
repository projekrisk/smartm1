<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Induk Rapor - {{ $siswa->nama_lengkap }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body, table, th, td, p, h1, h2, h3, span, div {
            font-family: Arial, Helvetica, sans-serif !important;
        }
        
        @page { 
            size: A4 portrait; 
            margin: 1.5cm; 
        }
        * { 
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important; 
        }
        .avoid-break { 
            page-break-inside: avoid; 
        }
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
            .cetak-kertas { 
                display: block !important; 
                width: 100% !important; 
                margin: 0 !important; 
                padding: 0 !important; 
                box-shadow: none !important; 
            }
        }
    </style>
</head>
<body class="bg-gray-200 text-gray-900 text-[13px]">
    
    <div class="no-print fixed top-5 left-5 z-50">
        <button onclick="window.close()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 transition">&larr; Tutup Halaman</button>
    </div>
    <div class="no-print fixed top-5 right-5 z-50">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500 transition font-bold">Cetak Rapor</button>
    </div>

    <div class="flex justify-center py-10 print:py-0 print:block">
        <div class="cetak-kertas bg-white shadow-2xl rounded w-[21cm] min-h-[29.7cm] p-[1.5cm] mx-auto">
            
            @php
                $pengaturan = null;
                try {
                    if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) {
                        $pengaturan = \App\Models\Pengaturan::first();
                    }
                } catch (\Exception $e) {}
            @endphp

            <div class="border-b-4 border-gray-800 pb-3 mb-6 flex items-center justify-between">
                <div class="w-24 h-24 flex-shrink-0 flex items-center justify-center">
                    @if($pengaturan && $pengaturan->logo_dinas)
                        <img src="{{ url('/uploads/' . $pengaturan->logo_dinas) }}" alt="Logo Dinas" class="max-w-full max-h-full object-contain">
                    @else
                        <div class="w-20 h-20 border-2 border-dashed border-gray-400 text-gray-400 flex items-center justify-center text-[10px] text-center">Logo<br>Dinas</div>
                    @endif
                </div>

                <div class="flex-1 text-center px-4">
                    <h1 class="text-xl font-bold uppercase tracking-wider">PEMERINTAH PROVINSI BANTEN</h1>
                    <h1 class="text-xl font-bold uppercase tracking-wider leading-tight">DINAS PENDIDIKAN DAN KEBUDAYAAN</h1>
                    <h1 class="text-2xl font-bold uppercase tracking-wider mt-1">{{ $pengaturan->nama_sekolah ?? 'NAMA SEKOLAH' }}</h1>
                    <p class="text-[11px] mt-1 font-bold">NPSN: 20601875 AKREDITASI: A (96)</p>
                    <p class="text-[11px]">Jl. Raya Bayah KM. 4 No. 39 Malingping – Lebak, 42391</p>
                    <p class="text-[11px]">Website: https://sman1malingping.sch.id – Email: sman1malingping@ymail.com</p>
                </div>

                <div class="w-24 h-24 flex-shrink-0 flex items-center justify-center">
                    @if($pengaturan && $pengaturan->logo_sekolah)
                        <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo Sekolah" class="max-w-full max-h-full object-contain">
                    @else
                        <div class="w-20 h-20 border-2 border-dashed border-gray-400 text-gray-400 flex items-center justify-center text-[10px] text-center">Logo<br>Sekolah</div>
                    @endif
                </div>
            </div>

            <div class="text-center mb-6">
                <h2 class="text-lg font-bold underline uppercase">Buku Induk Penilaian Siswa</h2>
            </div>

            <div class="flex items-start justify-between mb-8 avoid-break">
                <div class="flex-1 pr-4">
                    <table class="w-full text-[13px]">
                        <tr><td class="py-1.5 w-40 font-bold">Nama Lengkap</td><td class="w-2">:</td><td class="py-1.5 font-bold uppercase text-[15px]">{{ $siswa->nama_lengkap }}</td></tr>
                        <tr><td class="py-1.5 font-bold">Nomor Induk Siswa (NIS)</td><td>:</td><td class="py-1.5">{{ $siswa->nis ?? '-' }}</td></tr>
                        <tr><td class="py-1.5 font-bold">NISN</td><td>:</td><td class="py-1.5">{{ $siswa->nisn ?? '-' }}</td></tr>
                        <tr><td class="py-1.5 font-bold">Kelas Saat Ini</td><td>:</td><td class="py-1.5 font-bold">{{ $siswa->kelas->nama_kelas ?? '-' }}</td></tr>
                    </table>
                </div>
                
                <div class="w-[3cm] h-[4cm] border-2 border-gray-800 p-1 flex items-center justify-center overflow-hidden bg-white flex-shrink-0 relative z-20">
                    @if($siswa->foto)
                        <img src="{{ url('/uploads/' . $siswa->foto) }}" alt="Foto" class="w-full h-full object-cover">
                    @else
                        <span class="text-gray-400 text-xs text-center">Pas Foto<br>3 x 4</span>
                    @endif
                </div>
            </div>

            @php
                $semuaSemester = [];
                $mapelData = [];

                foreach($nilaisGrouped as $semesterName => $nilais) {
                    if(!in_array($semesterName, $semuaSemester)) {
                        $semuaSemester[] = $semesterName;
                    }
                    foreach($nilais as $n) {
                        $mapelName = $n->mataPelajaran->nama_pelajaran ?? 'Mapel Terhapus';
                        if(!isset($mapelData[$mapelName])) {
                            $mapelData[$mapelName] = [];
                        }
                        $mapelData[$mapelName][$semesterName] = $n->nilai_akhir;
                    }
                }
                ksort($mapelData);

                $sumSemester = array_fill_keys($semuaSemester, 0);
                $countSemester = array_fill_keys($semuaSemester, 0);
                $grandTotalSum = 0;
                $grandTotalCount = 0;
            @endphp

            <div class="mb-8 avoid-break">
                @if(count($semuaSemester) > 0)
                    <table class="w-full border-collapse border border-gray-400 text-center text-[12px]">
                        <thead>
                            <tr class="bg-gray-200">
                                <th rowspan="2" class="border border-gray-400 p-2 w-10">No</th>
                                <th rowspan="2" class="border border-gray-400 p-2 text-left">Mata Pelajaran</th>
                                <th colspan="{{ count($semuaSemester) }}" class="border border-gray-400 p-2">Nilai Semester</th>
                                <th rowspan="2" class="border border-gray-400 p-2 w-16 bg-gray-300">Rata-<br>rata</th>
                            </tr>
                            <tr class="bg-gray-100">
                                @foreach($semuaSemester as $sem)
                                    @php
                                        $parts = explode(' (', $sem);
                                        $tahun = $parts[0] ?? $sem;
                                        $smt = isset($parts[1]) ? str_replace(')', '', $parts[1]) : '';
                                    @endphp
                                    <th class="border border-gray-400 p-1 w-16">
                                        <div class="text-[10px] text-gray-600 font-normal">{{ $tahun }}</div>
                                        <div class="font-bold">{{ $smt }}</div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($mapelData as $mapel => $nilaiSmt)
                                @php
                                    $sumMapel = 0;
                                    $countMapel = 0;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-400 p-2">{{ $no++ }}</td>
                                    <td class="border border-gray-400 p-2 text-left font-bold">{{ $mapel }}</td>
                                    @foreach($semuaSemester as $sem)
                                        @php
                                            $val = $nilaiSmt[$sem] ?? null;
                                            
                                            if(is_numeric($val)) {
                                                $sumMapel += $val;
                                                $countMapel++;
                                                
                                                $sumSemester[$sem] += $val;
                                                $countSemester[$sem]++;
                                                
                                                $grandTotalSum += $val;
                                                $grandTotalCount++;
                                            }
                                        @endphp
                                        <td class="border border-gray-400 p-2 font-bold text-[14px]">
                                            {{ $val ?? '-' }}
                                        </td>
                                    @endforeach
                                    <td class="border border-gray-400 p-2 font-bold text-[14px] bg-gray-100 text-blue-800">
                                        {{ $countMapel > 0 ? round($sumMapel / $countMapel, 2) : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-200">
                                <td colspan="2" class="border border-gray-400 p-2 text-right font-bold uppercase">Rata-rata Nilai</td>
                                @foreach($semuaSemester as $sem)
                                    <td class="border border-gray-400 p-2 font-bold text-[14px] text-blue-800">
                                        {{ $countSemester[$sem] > 0 ? round($sumSemester[$sem] / $countSemester[$sem], 2) : '-' }}
                                    </td>
                                @endforeach
                                <td class="border border-gray-400 p-2 font-bold text-[14px] bg-gray-300 text-blue-900">
                                    {{ $grandTotalCount > 0 ? round($grandTotalSum / $grandTotalCount, 2) : '-' }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <div class="border border-gray-400 p-4 text-center italic text-gray-500">
                        Siswa belum memiliki riwayat nilai rapor.
                    </div>
                @endif
            </div>

            <div class="mt-12 flex justify-end text-[13px] avoid-break">
                <div class="text-center w-64">
                    <p>Malingping, ....................................</p>
                    <p class="mb-20">Kepala Sekolah</p>
                    <p class="font-bold underline uppercase">{{ $pengaturan->nama_kepala_sekolah ?? 'NAMA KEPALA SEKOLAH' }}</p>
                    <p>NIP. {{ $pengaturan->nip_kepala_sekolah ?? '....................................' }}</p>
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