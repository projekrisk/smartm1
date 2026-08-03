<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Jurnal Mengajar - {{ $guru->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4 landscape; margin: 1.5cm; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; font-family: Arial, sans-serif; }
        .avoid-break { page-break-inside: avoid; }
        
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
            .cetak-kertas { width: 100% !important; margin: 0 !important; padding: 0 !important; box-shadow: none !important; }
        }
        
        /* Pewarnaan Status */
        .status-s { color: #b45309; font-weight: bold; }
        .status-i { color: #1d4ed8; font-weight: bold; }
        .status-a { color: #b91c1c; font-weight: bold; }
        .status-t { color: #7e22ce; font-weight: bold; }
    </style>
</head>
<body class="bg-gray-200 text-gray-900 text-[11px]">
    
    <div class="no-print fixed top-5 left-5 z-50 flex gap-2">
        <button onclick="window.close()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700">&larr; Tutup Tab</button>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500 font-bold">Cetak / Simpan PDF</button>
    </div>

    <div class="flex justify-center py-10 print:py-0 print:block min-w-max">
        <div class="cetak-kertas bg-white shadow-2xl rounded p-[1.5cm] mx-auto min-w-[29.7cm] flex flex-col">
            
            @php
                $pengaturan = null;
                try { if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) $pengaturan = \App\Models\Pengaturan::first(); } catch (\Exception $e) {}
            @endphp

            <div class="border-b-4 border-gray-800 pb-3 mb-6 text-center">
                <h1 class="text-xl font-bold uppercase tracking-wider">REKAPITULASI JURNAL MENGAJAR GURU</h1>
                <h1 class="text-2xl font-bold uppercase tracking-wider mt-1">{{ $pengaturan->nama_sekolah ?? 'SMART-M1 SMAN 1 MALINGPING' }}</h1>
            </div>

            <div class="flex justify-between items-start mb-6">
                <table class="w-1/2 text-[12px]">
                    <tr><td class="w-24 font-bold py-1">Nama Guru</td><td class="w-2">:</td><td class="font-bold uppercase">{{ $guru->name }}</td></tr>
                    @php
                        $nipGuru = '-';
                        $pegawai = \App\Models\Pegawai::where('user_id', $guru->id)->first();
                        if ($pegawai && $pegawai->nip) $nipGuru = $pegawai->nip;
                    @endphp
                    <tr><td class="font-bold py-1">NIP</td><td>:</td><td>{{ $nipGuru }}</td></tr>
                </table>
                <table class="w-60 text-[12px]">
                    <tr><td class="w-24 font-bold py-1">Tahun Ajaran</td><td class="w-2">:</td><td class="font-bold uppercase">{{ $tahunAktif ? $tahunAktif->nama_tahun : 'Belum Diatur' }}</td></tr>
                    <tr><td class="font-bold py-1">Semester</td><td>:</td><td class="font-bold uppercase">{{ $tahunAktif ? $tahunAktif->semester : '-' }}</td></tr>
                </table>
            </div>

            <table class="w-full border-collapse border border-gray-600 mb-6 text-[11px]">
                <thead>
                    <tr class="bg-gray-200 text-center font-bold uppercase">
                        <th rowspan="2" class="border border-gray-600 p-1 w-8">No</th>
                        <th rowspan="2" class="border border-gray-600 p-1 w-20">Tanggal</th>
                        <th rowspan="2" class="border border-gray-600 p-1 w-20">Waktu</th>
                        <th rowspan="2" class="border border-gray-600 p-1 w-16">Kelas</th>
                        <th rowspan="2" class="border border-gray-600 p-1 w-36">Pelajaran</th>
                        <th rowspan="2" class="border border-gray-600 p-1">Materi Pembahasan</th>
                        <th colspan="3" class="border border-gray-600 p-1 bg-yellow-100">Status Kehadiran</th>
                    </tr>
                    <tr class="bg-gray-100 text-center font-bold uppercase text-[10px]">
                        <th class="border border-gray-600 p-1 w-20">NIS / NISN</th>
                        <th class="border border-gray-600 p-1 w-40">Nama Siswa</th>
                        <th class="border border-gray-600 p-1 w-14">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    
                    @forelse($jurnals as $j)
                        @php
                            $absenSiswa = clone $j->kehadiranPelajaran; 
                            $tidakHadir = $absenSiswa->where('status', '!=', 'Hadir')->sortBy('siswa.nama_lengkap')->values();
                            
                            $rowspan = max(1, $tidakHadir->count());
                        @endphp
                        
                        <tr class="avoid-break hover:bg-gray-50">
                            <td rowspan="{{ $rowspan }}" class="border border-gray-600 p-2 text-center align-top">{{ $no++ }}</td>
                            <td rowspan="{{ $rowspan }}" class="border border-gray-600 p-2 text-center align-top whitespace-nowrap">{{ \Carbon\Carbon::parse($j->tanggal)->isoFormat('DD MMM YYYY') }}</td>
                            <td rowspan="{{ $rowspan }}" class="border border-gray-600 p-2 text-center align-top whitespace-nowrap">{{ date('H:i', strtotime($j->jam_mulai)) }} - {{ date('H:i', strtotime($j->jam_selesai)) }}</td>
                            <td rowspan="{{ $rowspan }}" class="border border-gray-600 p-2 text-center align-top font-bold">{{ $j->kelas->nama_kelas ?? '-' }}</td>
                            <td rowspan="{{ $rowspan }}" class="border border-gray-600 p-2 text-left align-top font-semibold">{{ $j->mataPelajaran->nama_pelajaran ?? '-' }}</td>
                            <td rowspan="{{ $rowspan }}" class="border border-gray-600 p-2 align-top">
                                <div class="font-bold text-[12px] mb-1">{{ $j->materi_pembahasan ?? '-' }}</div>
                                @if($j->catatan_kejadian)
                                    <div class="text-[10px] text-gray-700 italic border-l-2 border-gray-400 pl-2">Catatan: {{ $j->catatan_kejadian }}</div>
                                @endif
                            </td>
                            
                            @if($tidakHadir->count() > 0)
                                @php 
                                    $absen1 = $tidakHadir[0]; 
                                    $warna = '';
                                    if($absen1->status == 'Sakit') $warna = 'status-s';
                                    if($absen1->status == 'Izin') $warna = 'status-i';
                                    if($absen1->status == 'Alpa') $warna = 'status-a';
                                    if($absen1->status == 'Terlambat') $warna = 'status-t';
                                @endphp
                                <td class="border border-gray-600 p-1 text-center align-top">{{ $absen1->siswa->nis ?? '-' }}</td>
                                <td class="border border-gray-600 p-1 align-top uppercase text-[10px] font-bold">
                                    {{ $absen1->siswa->nama_lengkap ?? '-' }}
                                    @if($absen1->keterangan)
                                        <div class="font-normal italic text-gray-600 mt-0.5">"{{ $absen1->keterangan }}"</div>
                                    @endif
                                </td>
                                <td class="border border-gray-600 p-1 text-center align-top uppercase font-bold {{ $warna }}">{{ $absen1->status }}</td>
                            @else
                                <td colspan="3" class="border border-gray-600 p-2 text-center align-middle text-green-700 font-bold bg-green-50">✓ Seluruh Siswa Hadir</td>
                            @endif
                        </tr>
                        
                        @if($tidakHadir->count() > 1)
                            @for($i = 1; $i < $tidakHadir->count(); $i++)
                                @php 
                                    $absenN = $tidakHadir[$i]; 
                                    $warnaN = '';
                                    if($absenN->status == 'Sakit') $warnaN = 'status-s';
                                    if($absenN->status == 'Izin') $warnaN = 'status-i';
                                    if($absenN->status == 'Alpa') $warnaN = 'status-a';
                                    if($absenN->status == 'Terlambat') $warnaN = 'status-t';
                                @endphp
                                <tr class="avoid-break hover:bg-gray-50">
                                    <td class="border border-gray-600 p-1 text-center align-top">{{ $absenN->siswa->nis ?? '-' }}</td>
                                    <td class="border border-gray-600 p-1 align-top uppercase text-[10px] font-bold">
                                        {{ $absenN->siswa->nama_lengkap ?? '-' }}
                                        @if($absenN->keterangan)
                                            <div class="font-normal italic text-gray-600 mt-0.5">"{{ $absenN->keterangan }}"</div>
                                        @endif
                                    </td>
                                    <td class="border border-gray-600 p-1 text-center align-top uppercase font-bold {{ $warnaN }}">{{ $absenN->status }}</td>
                                </tr>
                            @endfor
                        @endif

                    @empty
                        <tr>
                            <td colspan="9" class="border border-gray-600 p-6 text-center italic text-gray-500">Belum ada jurnal mengajar pada tahun ajaran ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @php
                $rekapAbsenSiswa = [];
                foreach($jurnals as $j) {
                    if($j->kehadiranPelajaran) {
                        foreach($j->kehadiranPelajaran as $absen) {
                            if ($absen->status !== 'Hadir') {
                                $sId = $absen->siswa_id;
                                if (!isset($rekapAbsenSiswa[$sId])) {
                                    $rekapAbsenSiswa[$sId] = [
                                        'nis' => $absen->siswa->nis ?? '-',
                                        'nama' => $absen->siswa->nama_lengkap ?? '-',
                                        'kelas' => $j->kelas->nama_kelas ?? '-',
                                        'Sakit' => 0,
                                        'Izin' => 0,
                                        'Alpa' => 0,
                                        'Terlambat' => 0,
                                        'Total' => 0
                                    ];
                                }
                                if(isset($rekapAbsenSiswa[$sId][$absen->status])) {
                                    $rekapAbsenSiswa[$sId][$absen->status]++;
                                    $rekapAbsenSiswa[$sId]['Total']++;
                                }
                            }
                        }
                    }
                }
                
                $topAbsen = collect($rekapAbsenSiswa)->sortByDesc('Total')->values();
            @endphp
            
            @if($topAbsen->count() > 0)
                <div class="mt-6 mb-4 avoid-break">
                    <h3 class="font-bold text-[12px] uppercase mb-2 border-l-4 border-gray-800 pl-2">Peringkat Ketidakhadiran Siswa Terbanyak (Sesi Bpk/Ibu {{ $guru->name }})</h3>
                    <table class="w-full border-collapse border border-gray-600 text-[11px]">
                        <thead>
                            <tr class="bg-gray-100 text-center font-bold uppercase">
                                <th class="border border-gray-600 p-1 w-8">No</th>
                                <th class="border border-gray-600 p-1 w-20">NIS</th>
                                <th class="border border-gray-600 p-1 text-left">Nama Lengkap Siswa</th>
                                <th class="border border-gray-600 p-1 w-24">Kelas</th>
                                <th class="border border-gray-600 p-1 w-10 text-yellow-700" title="Sakit">S</th>
                                <th class="border border-gray-600 p-1 w-10 text-blue-700" title="Izin">I</th>
                                <th class="border border-gray-600 p-1 w-10 text-red-700" title="Alpa">A</th>
                                <th class="border border-gray-600 p-1 w-10 text-purple-700" title="Terlambat">T</th>
                                <th class="border border-gray-600 p-1 w-12 bg-gray-200">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topAbsen as $idx => $siswa)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-600 p-1 text-center">{{ $idx + 1 }}</td>
                                    <td class="border border-gray-600 p-1 text-center">{{ $siswa['nis'] }}</td>
                                    <td class="border border-gray-600 p-1 font-bold uppercase">{{ $siswa['nama'] }}</td>
                                    <td class="border border-gray-600 p-1 text-center">{{ $siswa['kelas'] }}</td>
                                    <td class="border border-gray-600 p-1 text-center {{ $siswa['Sakit'] > 0 ? 'font-bold' : 'text-gray-400' }}">{{ $siswa['Sakit'] ?: '-' }}</td>
                                    <td class="border border-gray-600 p-1 text-center {{ $siswa['Izin'] > 0 ? 'font-bold' : 'text-gray-400' }}">{{ $siswa['Izin'] ?: '-' }}</td>
                                    <td class="border border-gray-600 p-1 text-center {{ $siswa['Alpa'] > 0 ? 'font-bold text-red-600' : 'text-gray-400' }}">{{ $siswa['Alpa'] ?: '-' }}</td>
                                    <td class="border border-gray-600 p-1 text-center {{ $siswa['Terlambat'] > 0 ? 'font-bold' : 'text-gray-400' }}">{{ $siswa['Terlambat'] ?: '-' }}</td>
                                    <td class="border border-gray-600 p-1 text-center font-bold bg-yellow-50">{{ $siswa['Total'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="mt-4 flex justify-between text-[11px] avoid-break">
                <div>
                    <strong>Keterangan Status:</strong><br>
                    S = Sakit &nbsp; | &nbsp; I = Izin &nbsp; | &nbsp; A = Alpa &nbsp; | &nbsp; T = Terlambat
                </div>
                <div class="text-center w-64 pr-10">
                    Malingping, {{ now()->isoFormat('D MMMM Y') }}<br>
                    Guru Pengampu,
                    <br><br><br><br>
                    <p class="font-bold underline uppercase">{{ $guru->name }}</p>
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