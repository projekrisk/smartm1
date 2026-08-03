<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Mengajar - {{ $jurnal->kelas->nama_kelas ?? '-' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4 portrait; margin: 1cm 1.5cm; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; font-family: Arial, sans-serif; }
        .avoid-break { page-break-inside: avoid; }
        
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
            .cetak-kertas { width: 100% !important; margin: 0 !important; padding: 0 !important; box-shadow: none !important; }
        }

        .status-hadir { color: #15803d; }
        .status-sakit { color: #b45309; }
        .status-izin { color: #1d4ed8; }
        .status-alpa { color: #b91c1c; }
        .status-terlambat { color: #7e22ce; }
    </style>
</head>
<body class="bg-gray-200 text-gray-900 text-[12px]">
    
    <div class="no-print fixed top-5 left-5 z-50 flex gap-2">
        <button onclick="window.close()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700">&larr; Tutup Tab</button>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500 font-bold">Cetak Laporan</button>
    </div>

    <div class="flex justify-center py-10 print:py-0 print:block">
        <div class="cetak-kertas bg-white shadow-2xl rounded w-[21cm] min-h-[29.7cm] p-[1.5cm] mx-auto flex flex-col">
            
            @php
                $pengaturan = null;
                try { if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) $pengaturan = \App\Models\Pengaturan::first(); } catch (\Exception $e) {}
            @endphp

            <div class="border-b-4 border-gray-800 pb-3 mb-6 text-center avoid-break">
                <h1 class="text-xl font-bold uppercase tracking-wider">Laporan Pelaksanaan Pembelajaran & Absensi</h1>
                <h1 class="text-2xl font-bold uppercase tracking-wider mt-1">{{ $pengaturan->nama_sekolah ?? 'SMART-M1 SMAN 1 MALINGPING' }}</h1>
            </div>

            <div class="flex justify-between items-start mb-6 avoid-break">
                <table class="w-1/2 text-[13px]">
                    <tr><td class="w-32 font-bold py-1">Mata Pelajaran</td><td class="w-2">:</td><td class="font-bold uppercase">{{ $jurnal->mataPelajaran->nama_pelajaran ?? '-' }}</td></tr>
                    <tr><td class="font-bold py-1">Kelas</td><td>:</td><td class="font-bold">{{ $jurnal->kelas->nama_kelas ?? '-' }}</td></tr>
                    <tr><td class="font-bold py-1">Guru Pengajar</td><td>:</td><td>{{ $jurnal->guru->name ?? '-' }}</td></tr>
                </table>
                <table class="w-1/2 text-[13px]">
                    <tr><td class="w-24 font-bold py-1">Tahun Ajaran</td><td class="w-2">:</td><td class="font-bold">{{ $jurnal->tahunAjaran->nama_tahun ?? '-' }} ({{ $jurnal->tahunAjaran->semester ?? '-' }})</td></tr>
                    <tr><td class="font-bold py-1">Tanggal</td><td>:</td><td>{{ \Carbon\Carbon::parse($jurnal->tanggal)->isoFormat('dddd, D MMMM Y') }}</td></tr>
                    <tr><td class="font-bold py-1">Waktu</td><td>:</td><td>{{ date('H:i', strtotime($jurnal->jam_mulai)) }} s/d {{ date('H:i', strtotime($jurnal->jam_selesai)) }}</td></tr>
                </table>
            </div>

            <div class="mb-6 border border-gray-400 p-3 bg-gray-50 avoid-break">
                <div class="font-bold mb-1 uppercase text-[11px] text-gray-600">Materi / Topik Pembahasan:</div>
                <div class="text-[13px]">{{ $jurnal->materi_pembahasan ?? '-' }}</div>
                
                @if($jurnal->catatan_kejadian)
                    <div class="font-bold mb-1 mt-3 uppercase text-[11px] text-gray-600">Catatan Kejadian:</div>
                    <div class="text-[13px] italic">{{ $jurnal->catatan_kejadian }}</div>
                @endif
            </div>

            <table class="w-full border-collapse border border-gray-600 mb-6 text-sm">
                <thead>
                    <tr class="bg-gray-200 text-center font-bold text-[11px] uppercase tracking-wider">
                        <th class="border border-gray-600 p-2 w-10">No</th>
                        <th class="border border-gray-600 p-2 w-32">NIS / NISN</th>
                        <th class="border border-gray-600 p-2 text-left">Nama Lengkap Siswa</th>
                        <th class="border border-gray-600 p-2 w-24">Status</th>
                        <th class="border border-gray-600 p-2 text-left w-1/3">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $no = 1; 
                        $daftarHadir = $jurnal->kehadiranPelajaran;
                    @endphp
                    
                    @forelse($daftarHadir as $item)
                        @php
                            $statusClass = '';
                            if($item->status == 'Hadir') $statusClass = 'status-hadir';
                            elseif($item->status == 'Sakit') $statusClass = 'status-sakit';
                            elseif($item->status == 'Izin') $statusClass = 'status-izin';
                            elseif($item->status == 'Alpa') $statusClass = 'status-alpa';
                            elseif($item->status == 'Terlambat') $statusClass = 'status-terlambat';
                        @endphp
                        <tr class="avoid-break hover:bg-gray-50">
                            <td class="border border-gray-600 p-2 text-center">{{ $no++ }}</td>
                            <td class="border border-gray-600 p-2 text-center">{{ $item->siswa->nis ?? '-' }} / {{ $item->siswa->nisn ?? '-' }}</td>
                            <td class="border border-gray-600 p-2 font-semibold uppercase">{{ $item->siswa->nama_lengkap ?? '-' }}</td>
                            <td class="border border-gray-600 p-2 text-center font-bold {{ $statusClass }}">{{ $item->status ?? '-' }}</td>
                            <td class="border border-gray-600 p-2 text-[11px]">{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="border border-gray-600 p-4 text-center italic text-gray-500">Daftar absensi siswa belum tersimpan atau kelas kosong.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-auto pt-6 flex justify-end avoid-break">
                <div class="text-center w-64">
                    <p>Malingping, {{ \Carbon\Carbon::parse($jurnal->tanggal)->isoFormat('D MMMM Y') }}</p>
                    <p class="mb-20">Guru Mata Pelajaran,</p>
                    
                    @php
                        $nipGuru = '-';
                        if ($jurnal->guru_id) {
                            $pegawai = \App\Models\Pegawai::where('user_id', $jurnal->guru_id)->first();
                            if ($pegawai && $pegawai->nip) {
                                $nipGuru = $pegawai->nip;
                            }
                        }
                    @endphp

                    <p class="font-bold underline uppercase">{{ $jurnal->guru->name ?? '___________________________' }}</p>
                    <p>NIP. {{ $nipGuru }}</p>
                </div>
            </div>
            
        </div>
    </div>

    <script>
        window.onload = function() { 
            setTimeout(() => { window.print(); }, 800); 
        }
    </script>
</body>
</html>