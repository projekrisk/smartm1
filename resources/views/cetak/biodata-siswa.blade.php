<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Biodata & Portofolio - {{ $siswa->nama_lengkap }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4 portrait;
            margin: 1cm 1.5cm;
        }
        
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        /* Mode Cetak */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: white !important;
            }
            .cetak-kertas {
                width: 100% !important;
                margin: 0 !important;
                padding: 1cm 1.5cm; 
                box-shadow: none !important;
                min-height: auto !important;
            }
        }
    </style>
</head>
<body class="bg-gray-200 text-gray-900 font-serif">

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

    <div class="flex justify-center py-10 print:py-0 print:block">
        
        <div class="cetak-kertas bg-white shadow-2xl rounded w-[21cm] min-h-[29.7cm] p-[1cm] mx-auto relative flex flex-col overflow-hidden">
            
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0 overflow-hidden">
                <div class="text-[110px] font-bold text-gray-200 transform -rotate-45 tracking-widest text-center leading-none opacity-60">
                    DATA<br>RAHASIA
                </div>
            </div>

            <div class="relative z-10 flex-1 flex flex-col">
                
                <div class="border-b-4 border-gray-800 pb-3 mb-6 text-center avoid-break">
                    <h1 class="text-2xl font-bold uppercase tracking-wider font-sans">SMAN 1 MALINGPING</h1>
                    <p class="text-xs mt-1">Jl. Pendidikan No. 1, Kec. Pintar, Kota Cerdas, 12345</p>
                    <p class="text-xs">Telepon: (021) 888-9999 | Email: info@smart-m1.com</p>
                </div>

                <div class="text-center mb-6 avoid-break">
                    <h2 class="text-lg font-bold underline uppercase">Buku Induk - Profil & Portofolio Siswa</h2>
                    <p class="text-xs mt-1">Nomor Induk Siswa Nasional (NISN): <span class="font-bold">{{ $siswa->nisn ?? '-' }}</span></p>
                </div>

                <div class="mb-4 text-[13px] avoid-break">
                    <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase text-xs border border-gray-400">A. Keterangan Diri Siswa</h3>
                    <table class="w-full ml-1">
                        <tr><td class="py-0.5 w-1/3">1. Nama Lengkap</td><td class="w-2">:</td><td class="uppercase font-bold">{{ $siswa->nama_lengkap }}</td></tr>
                        <tr><td class="py-0.5">2. Jenis Kelamin</td><td>:</td><td>{{ $siswa->jenis_kelamin }}</td></tr>
                        <tr><td class="py-0.5">3. Tempat, Tgl Lahir</td><td>:</td><td>{{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->isoFormat('D MMMM Y') : '-' }}</td></tr>
                        <tr><td class="py-0.5">4. Agama</td><td>:</td><td>{{ $siswa->agama ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">5. NIK (Kependudukan)</td><td>:</td><td>{{ $siswa->nik ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">6. No. Kartu Keluarga</td><td>:</td><td>{{ $siswa->no_kk ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">7. No. Telepon / HP</td><td>:</td><td>{{ $siswa->telepon ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">8. Alamat Email</td><td>:</td><td>{{ $siswa->email ?? '-' }}</td></tr>
                    </table>
                </div>

                <div class="mb-4 text-[13px] avoid-break">
                    <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase text-xs border border-gray-400">B. Keterangan Tempat Tinggal</h3>
                    <table class="w-full ml-1">
                        <tr><td class="py-0.5 w-1/3 align-top">9. Alamat Lengkap</td><td class="w-2 align-top">:</td><td>{{ $siswa->alamat ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">10. RT / RW</td><td>:</td><td>{{ $siswa->rt ?? '-' }} / {{ $siswa->rw ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">11. Kelurahan / Desa</td><td>:</td><td>{{ $siswa->kelurahan ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">12. Kecamatan</td><td>:</td><td>{{ $siswa->kecamatan ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">13. Kabupaten / Kota</td><td>:</td><td>{{ $siswa->kabupaten ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">14. Koordinat (Lintang, Bujur)</td><td>:</td><td>{{ $siswa->lintang ?? '-' }}, {{ $siswa->bujur ?? '-' }}</td></tr>
                    </table>
                </div>

                <div class="mb-6 text-[13px] avoid-break">
                    <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase text-xs border border-gray-400">C. Keterangan Orang Tua / Wali</h3>
                    <table class="w-full ml-1">
                        <tr><td class="py-0.5 w-1/3">15. Nama Ayah Kandung</td><td class="w-2">:</td><td>{{ $siswa->nama_ayah ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">16. No. HP Ayah</td><td>:</td><td>{{ $siswa->telepon_ayah ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">17. Nama Ibu Kandung</td><td>:</td><td>{{ $siswa->nama_ibu ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">18. No. HP Ibu</td><td>:</td><td>{{ $siswa->telepon_ibu ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">19. Nama Wali</td><td>:</td><td>{{ $siswa->nama_wali ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">20. No. HP Wali</td><td>:</td><td>{{ $siswa->telepon_wali ?? '-' }}</td></tr>
                    </table>
                </div>

                <div class="mb-6 text-[13px] avoid-break">
                    <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase text-xs border border-gray-400">D. Keterangan Akademik Dasar</h3>
                    
                    <div class="flex items-start justify-between ml-1">
                        <div class="flex-1 pr-4">
                            <table class="w-full">
                                <tr><td class="py-0.5 w-2/5">21. NIS / NISN</td><td class="w-2">:</td><td class="py-0.5 font-bold">{{ $siswa->nis }} / {{ $siswa->nisn ?? '-' }}</td></tr>
                                <tr><td class="py-0.5">22. Kelas Saat Ini</td><td>:</td><td class="py-0.5 font-bold">{{ $siswa->kelas->nama_kelas ?? 'Belum ada kelas' }}</td></tr>
                                <tr><td class="py-0.5">23. Sekolah Asal</td><td>:</td><td class="py-0.5">{{ $siswa->sekolah_asal ?? '-' }}</td></tr>
                                <tr><td class="py-0.5">24. Status Masuk</td><td>:</td><td class="py-0.5">{{ $siswa->jalur_masuk ?? 'Siswa Baru' }}</td></tr>
                                <tr><td class="py-0.5">25. Tanggal Masuk</td><td>:</td><td class="py-0.5">{{ $siswa->tanggal_masuk ? \Carbon\Carbon::parse($siswa->tanggal_masuk)->isoFormat('D MMMM Y') : '-' }}</td></tr>
                                <tr><td class="py-0.5">26. Status Terkini</td><td>:</td><td class="py-0.5 font-bold uppercase {{ $siswa->status_siswa == 'Aktif' ? 'text-green-700' : 'text-red-700' }}">{{ $siswa->status_siswa }}</td></tr>
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
                </div>

                <div class="mb-6 text-[13px] avoid-break">
                    <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase text-xs border border-gray-400">E. Riwayat Kelas & Mutasi</h3>
                    <table class="w-full border-collapse border border-gray-400 text-center">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-400 p-1 w-12">No</th>
                                <th class="border border-gray-400 p-1">Tahun Ajaran</th>
                                <th class="border border-gray-400 p-1">Kelas</th>
                                <th class="border border-gray-400 p-1">Status/Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa->riwayatKelas as $index => $rw)
                                <tr>
                                    <td class="border border-gray-400 p-1">{{ $index + 1 }}</td>
                                    <td class="border border-gray-400 p-1">{{ $rw->tahunAjaran->nama_tahun ?? '-' }}</td>
                                    <td class="border border-gray-400 p-1">{{ $rw->kelas->nama_kelas ?? '-' }}</td>
                                    <td class="border border-gray-400 p-1">{{ $rw->status_riwayat }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="border border-gray-400 p-2 italic text-gray-500">Belum ada riwayat pergerakan kelas yang tercatat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mb-6 text-[13px] avoid-break">
                    <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase text-xs border border-gray-400">F. Catatan Siswa (Buku Kasus & Prestasi)</h3>
                    <table class="w-full border-collapse border border-gray-400 text-center">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-400 p-1 w-24">Tanggal</th>
                                <th class="border border-gray-400 p-1 w-20">Jenis</th>
                                <th class="border border-gray-400 p-1">Perihal / Keterangan</th>
                                <th class="border border-gray-400 p-1 w-32">Pencatat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa->catatan->sortByDesc('tanggal') as $catatan)
                                <tr>
                                    <td class="border border-gray-400 p-2 align-top">{{ \Carbon\Carbon::parse($catatan->tanggal)->format('d/m/Y') }}</td>
                                    <td class="border border-gray-400 p-2 align-top font-bold {{ $catatan->jenis_catatan == 'Positif' ? 'text-green-700' : ($catatan->jenis_catatan == 'Negatif' ? 'text-red-700' : 'text-blue-700') }}">{{ $catatan->jenis_catatan }}</td>
                                    <td class="border border-gray-400 p-2 text-left">
                                        <strong class="block mb-1">{{ $catatan->judul_catatan }}</strong>
                                        <span class="text-xs text-gray-700">{{ $catatan->isi_catatan }}</span>
                                    </td>
                                    <td class="border border-gray-400 p-2 align-top text-xs">{{ $catatan->pencatat->name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="border border-gray-400 p-2 italic text-gray-500">Siswa belum memiliki catatan pelanggaran maupun prestasi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mb-6 text-[13px] avoid-break">
                    <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase text-xs border border-gray-400">
                        G. Rekapitulasi Ketidakhadiran 
                        <span class="font-normal normal-case">(Tahun Ajaran: {{ $tahunAjaranAktif ? $tahunAjaranAktif->nama_tahun . ' ' . $tahunAjaranAktif->semester : 'Belum Diatur' }})</span>
                    </h3>
                    
                    @php
                        $absensiGrouped = $siswa->kehadiranHarian->groupBy(function($item) {
                            return \Carbon\Carbon::parse($item->rekapKehadiran->tanggal)->isoFormat('MMMM YYYY');
                        });
                    @endphp

                    @if($absensiGrouped->isEmpty())
                        <div class="border border-gray-400 p-3 text-center italic text-gray-600 font-semibold bg-gray-50">
                            Luar Biasa! Siswa ini tidak pernah abstain (Selalu Hadir) di semester aktif ini.
                        </div>
                    @else
                        <table class="w-full border-collapse border border-gray-400">
                            <tbody>
                                @foreach($absensiGrouped as $bulan => $items)
                                    <tr class="bg-gray-100 font-bold text-left">
                                        <td colspan="3" class="border border-gray-400 p-1 px-3 uppercase text-xs text-gray-800">{{ $bulan }}</td>
                                    </tr>
                                    <!-- Urutkan berdasar tanggal terkecil -->
                                    @foreach($items->sortBy(fn($i) => $i->rekapKehadiran->tanggal) as $absen)
                                        <tr>
                                            <td class="border border-gray-400 p-1 text-center w-32 text-xs">
                                                {{ \Carbon\Carbon::parse($absen->rekapKehadiran->tanggal)->isoFormat('D MMMM Y') }}
                                            </td>
                                            <td class="border border-gray-400 p-1 text-center font-bold w-24 uppercase text-xs {{ $absen->status == 'Alpa' ? 'text-red-600' : ($absen->status == 'Sakit' ? 'text-yellow-600' : 'text-blue-600') }}">
                                                {{ $absen->status }}
                                            </td>
                                            <td class="border border-gray-400 p-1 pl-3 text-xs">
                                                {{ $absen->keterangan ?? 'Tanpa Keterangan' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                <div class="mt-auto border-t-2 border-gray-800 pt-3 text-center text-xs text-gray-700 italic avoid-break">
                    Dicetak pada Smart-M1 SMAN 1 Malingping | Waktu Cetak: {{ now()->isoFormat('D MMMM Y - HH:mm') }} WIB
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