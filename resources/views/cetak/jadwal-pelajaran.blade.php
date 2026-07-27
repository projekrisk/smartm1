<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Jadwal Pelajaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body, table, th, td, p, h1, h2, h3, span, div { font-family: Arial, Helvetica, sans-serif !important; }
        @page { size: A4 portrait; margin: 1cm; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        .avoid-break { page-break-inside: avoid; }
        
        /* Pattern garis diagonal untuk kolom kosong */
        /* Pola garis diagonal untuk kolom kosong */
        .bg-pattern { 
            background-color: #ffffff; 
            background-image: linear-gradient(45deg, #f3f4f6 25%, transparent 25%, transparent 50%, #f3f4f6 50%, #f3f4f6 75%, transparent 75%, transparent);
            background-size: 10px 10px;
        }
        
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
            .cetak-kertas { width: 100% !important; margin: 0 !important; padding: 0 !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-gray-200 text-gray-900 text-[12px]">

    <div class="no-print fixed top-5 left-5 z-50 flex gap-2">
    <button onclick="window.close()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 transition">&larr; Tutup</button>
    <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500 transition font-bold">Cetak Jadwal</button>
</div>

<div class="flex flex-col items-center py-10 gap-10 print:py-0 print:block">
    
    @foreach($dataCetak as $judulLembar => $jadwalSeminggu)
    
        <div class="cetak-kertas bg-white shadow-2xl rounded p-[1cm] mx-auto w-[21cm] min-h-[29.7cm] print:mb-0 print:break-after-page">
            
                @php
                $pengaturan = null;
                try { if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) { $pengaturan = \App\Models\Pengaturan::first(); } } catch (\Exception $e) {}
                $hariUrut = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                
                $mapelStats = [];
                $totalMenitKeseluruhan = 0;
                foreach($jadwalSeminggu as $hari => $jadwals) {
                    foreach($jadwals as $j) {
                        $code = $j->mataPelajaran->kode_pelajaran ?? '-';
                        $nama = $j->mataPelajaran->nama_pelajaran ?? '-';
                        $menit = \Carbon\Carbon::parse($j->jam_mulai)->diffInMinutes(\Carbon\Carbon::parse($j->jam_selesai));
                        
                        if(!isset($mapelStats[$code])) {
                            $mapelStats[$code] = ['nama' => $nama, 'menit' => 0];
                        }
                        $mapelStats[$code]['menit'] += $menit;
                        $totalMenitKeseluruhan += $menit;
                    }
                }
            @endphp

            <!-- KOP SURAT RATA KIRI -->
            <div class="border-b-[1px] border-gray-800 pb-3 mb-4 flex items-center gap-4">
                <div class="w-20 h-20 flex-shrink-0 flex items-center justify-center">
                    @if($pengaturan && $pengaturan->logo_sekolah) 
                        <img src="{{ asset('uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo" class="max-w-full max-h-full object-contain">
                    @else
                        <div class="w-16 h-16 border border-gray-300 flex items-center justify-center text-[8px] text-gray-400">Logo</div>
                    @endif
                </div>
                <div class="text-left">
                    <h1 class="text-sm font-bold uppercase leading-tight">PEMERINTAH PROVINSI BANTEN</h1>
                    <h1 class="text-sm font-bold uppercase leading-tight">DINAS PENDIDIKAN DAN KEBUDAYAAN</h1>
                    <h1 class="text-lg font-bold uppercase mt-1">{{ $pengaturan->nama_sekolah ?? 'NAMA SEKOLAH' }}</h1>
                </div>
            </div>

            <div class="text-center mb-4">
                <h2 class="text-md font-bold uppercase underline">JADWAL PELAJARAN</h2>
                <p class="text-sm font-bold text-gray-700">{{ $judulLembar }}</p>
            </div>

            <!-- TABEL JADWAL UTAMA -->
            <div class="avoid-break mb-6">
                <table class="w-full border-collapse border border-gray-800 text-center">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-800 p-2 w-28 uppercase">JAM</th>
                            @foreach($hariUrut as $hari)
                                <th class="border border-gray-800 p-2 uppercase">{{ $hari }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $semuaJam = collect();
                            foreach($jadwalSeminggu as $hari => $jadwals) {
                                foreach($jadwals as $j) {
                                    $formatWaktu = date('H:i', strtotime($j->jam_mulai)) . ' - ' . date('H:i', strtotime($j->jam_selesai));
                                    if (!$semuaJam->contains('waktu', $formatWaktu)) {
                                        $semuaJam->push(['mulai' => $j->jam_mulai, 'waktu' => $formatWaktu]);
                                    }
                                }
                            }
                            $semuaJam = $semuaJam->sortBy('mulai')->values();
                        @endphp
                        @foreach($semuaJam as $waktu)
                            <tr>
                                <td class="border border-gray-800 p-2 font-bold bg-gray-50">{{ $waktu['waktu'] }}</td>
                                @foreach($hariUrut as $hari)
                                    @php
                                        $kelasBerlangsung = collect($jadwalSeminggu[$hari] ?? [])->filter(function($j) use ($waktu) {
                                            return (date('H:i', strtotime($j->jam_mulai)) . ' - ' . date('H:i', strtotime($j->jam_selesai))) === $waktu['waktu'];
                                        });
                                    @endphp
                                    <td class="border border-gray-800 p-1 align-middle {{ $kelasBerlangsung->isEmpty() ? 'bg-pattern' : '' }}">
                                        @foreach($kelasBerlangsung as $j)
                                            <strong class="text-[12px] font-bold text-gray-900 uppercase">
                                                {{ $j->mataPelajaran->kode_pelajaran ?? '-' }}
                                            </strong>
                                        @endforeach
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- TABEL KETERANGAN & TOTAL -->
            <div class="avoid-break mb-6">
                <strong class="text-[12px] uppercase block mb-1">Keterangan Mata Pelajaran:</strong>
                <table class="w-full border-collapse border border-gray-800 text-[12px]">
                    <tr class="bg-gray-100 text-center font-bold">
                        <td class="border border-gray-800 p-1 w-20">Kode</td>
                        <td class="border border-gray-800 p-1 text-left">Mata Pelajaran</td>
                        <td class="border border-gray-800 p-1 w-16">Jam</td>
                    </tr>
                    @foreach($mapelStats as $kode => $data)
                        <tr>
                            <td class="border border-gray-800 p-1 text-center font-bold">{{ $kode }}</td>
                            <td class="border border-gray-800 p-1">{{ $data['nama'] }}</td>
                            <td class="border border-gray-800 p-1 text-center">{{ round($data['menit']/60, 1) }}</td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-50 font-bold">
                        <td class="border border-gray-800 p-1 text-right" colspan="2">TOTAL KESELURUHAN</td>
                        <td class="border border-gray-800 p-1 text-center">{{ round($totalMenitKeseluruhan/60, 1) }}</td>
                    </tr>
                </table>
            </div>

            <!-- TANDA TANGAN -->
            <div class="mt-6 flex justify-end text-[12px] avoid-break">
                <div class="w-64 text-center">
                    Malingping, {{ now()->isoFormat('D MMMM Y') }}<br>
                    Kepala Sekolah
                    <br><br><br><br>
                    <strong class="uppercase underline">{{ $pengaturan->nama_kepala_sekolah ?? '__________________________' }}</strong><br>
                    NIP. {{ $pengaturan->nip_kepala_sekolah ?? '-' }}
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
    window.onload = function() {
        setTimeout(() => { window.print(); }, 800);
    }
</script>