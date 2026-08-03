<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Nilai - {{ $penilaian->kelas->nama_kelas }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4 portrait; margin: 1.5cm; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; font-family: Arial, sans-serif; }
        .avoid-break { page-break-inside: avoid; }
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
            .cetak-kertas { width: 100% !important; margin: 0 !important; padding: 0 !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-gray-200 text-gray-900 text-xs">
    
    <div class="no-print fixed top-5 left-5 z-50">
        <button onclick="window.close()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700">&larr; Tutup Tab</button>
    </div>
    <div class="no-print fixed top-5 right-5 z-50">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500 font-bold">Cetak Rekap</button>
    </div>

    <div class="flex justify-center py-10 print:py-0 print:block">
        <div class="cetak-kertas bg-white shadow-2xl rounded w-[21cm] min-h-[29.7cm] p-[1.5cm] mx-auto flex flex-col">
            
            @php
                $pengaturan = null;
                try { if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) $pengaturan = \App\Models\Pengaturan::first(); } catch (\Exception $e) {}
                
                $namaGuru = '___________________________';
                $nipGuru = '-';
                
                $jadwal = \App\Models\JadwalPelajaran::where('mata_pelajaran_id', $penilaian->mata_pelajaran_id)
                    ->where('kelas_id', $penilaian->kelas_id)
                    ->first();

                if ($jadwal && $jadwal->guru_id) {
                    $namaGuru = $jadwal->guru->name ?? '___________________________';
                    $pegawai = \App\Models\Pegawai::where('user_id', $jadwal->guru_id)->first();
                    if ($pegawai && $pegawai->nip) {
                        $nipGuru = $pegawai->nip;
                    }
                }
            @endphp

            <div class="border-b-4 border-gray-800 pb-3 mb-6 text-center">
                <h1 class="text-xl font-bold uppercase tracking-wider">PEMERINTAH PROVINSI BANTEN</h1>
                <h1 class="text-2xl font-bold uppercase tracking-wider mt-1">{{ $pengaturan->nama_sekolah ?? 'SMART-M1 SMAN 1 MALINGPING' }}</h1>
                <p class="text-[11px] mt-1">Laporan Rekapitulasi Daftar Nilai Siswa</p>
            </div>

            <div class="flex justify-between items-start mb-6">
                <table class="w-2/3 text-[13px]">
                    <tr><td class="w-32 font-bold py-1">Mata Pelajaran</td><td class="w-2">:</td><td class="font-bold uppercase">{{ $penilaian->mataPelajaran->nama_pelajaran ?? '-' }}</td></tr>
                    <tr><td class="font-bold py-1">Kelas / Semester</td><td>:</td><td>{{ $penilaian->kelas->nama_kelas ?? '-' }} / {{ $penilaian->tahunAjaran->semester ?? '-' }}</td></tr>
                    <tr><td class="font-bold py-1">Tahun Ajaran</td><td>:</td><td>{{ $penilaian->tahunAjaran->nama_tahun ?? '-' }}</td></tr>
                </table>
                <table class="w-1/3 text-[13px] text-right">
                    <tr><td class="py-1">Jenis Penilaian :</td><td class="font-bold pl-2 uppercase">{{ $penilaian->jenis_nilai }}</td></tr>
                    <tr><td class="py-1">Tanggal :</td><td class="font-bold pl-2">{{ \Carbon\Carbon::parse($penilaian->tanggal_penilaian)->isoFormat('D MMMM Y') }}</td></tr>
                </table>
            </div>

            <div class="mb-4 bg-gray-100 border border-gray-400 p-2 text-center text-[13px]">
                <span class="font-bold">Materi / Topik:</span> {{ $penilaian->materi ?? '-' }}
            </div>

            <table class="w-full table-fixed border-collapse border border-gray-600 mb-6 text-sm">
                <thead>
                    <tr class="bg-gray-200 text-center font-bold">
                        <th class="border border-gray-600 p-2 w-[8%]">No</th>
                        <th class="border border-gray-600 p-2 text-left w-[32%]">Nama Siswa</th>
                        <th class="border border-gray-600 p-2 w-[15%]">NISN</th>
                        <th class="border border-gray-600 p-2 w-[15%]">Nilai</th>
                        <th class="border border-gray-600 p-2 text-left w-[30%]">Catatan Guru</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $no = 1; 
                        $totalNilai = 0;
                        $jumlahSiswa = 0;
                        $bukuNilais = $penilaian->bukuNilai->sortBy('siswa.nama_lengkap');
                    @endphp
                    
                    @foreach($bukuNilais as $item)
                        @php
                            if(is_numeric($item->nilai)) {
                                $totalNilai += $item->nilai;
                                $jumlahSiswa++;
                            }
                        @endphp
                        <tr class="avoid-break hover:bg-gray-50">
                            <td class="border border-gray-600 p-2 text-center">{{ $no++ }}</td>
                            <td class="border border-gray-600 p-2 font-bold uppercase truncate">{{ $item->siswa->nama_lengkap ?? '-' }}</td>
                            <td class="border border-gray-600 p-2 text-center">{{ $item->siswa->nisn ?? '-' }}</td>
                            <td class="border border-gray-600 p-2 text-center font-bold text-base {{ $item->nilai < 75 ? 'text-red-600' : '' }}">{{ $item->nilai ?? '-' }}</td>
                            <td class="border border-gray-600 p-2 text-xs text-gray-700 break-words">{{ $item->catatan_guru ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100 font-bold">
                        <td colspan="3" class="border border-gray-600 p-2 text-right uppercase">Rata-rata Kelas :</td>
                        <td class="border border-gray-600 p-2 text-center text-base bg-yellow-100">{{ $jumlahSiswa > 0 ? round($totalNilai / $jumlahSiswa, 2) : '-' }}</td>
                        <td class="border border-gray-600 p-2"></td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-auto pt-6 flex justify-end avoid-break">
                <div class="text-center w-72">
                    <p>Malingping, {{ now()->isoFormat('D MMMM Y') }}</p>
                    <p class="mb-20">Guru Mata Pelajaran,</p>
                    
                    @if($namaGuru !== '___________________________')
                        <p class="font-bold underline uppercase">{{ $namaGuru }}</p>
                        <p>NIP. {{ $nipGuru }}</p>
                    @else
                        <p class="font-bold underline uppercase">___________________________</p>
                        <p>NIP. - </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    <script>
        window.onload = function() { setTimeout(() => { window.print(); }, 800); }
    </script>
</body>
</html>