<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Biodata & Portofolio - {{ $siswa->nama_lengkap }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* 🌟 Margin Kertas 1cm Semua Sisi */
        @page {
            size: A4 portrait;
            margin: 1cm;
        }
        
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        /* 🌟 Pengaturan Font Arial dan Ukuran 11pt Secara Global */
        body {
            font-family: Arial, Helvetica, sans-serif !important;
            font-size: 11pt !important;
            line-height: 1.5;
        }
        
        table, tr, td, th, p, span, div {
            font-size: 11pt;
        }

        /* Khusus pengecualian untuk Kop Surat dan Watermark */
        .kop-1 { font-size: 13pt !important; }
        .kop-2 { font-size: 15pt !important; }
        .watermark { font-size: 80pt !important; color: #f3f4f6 !important; }

        /* 🌟 FOOTER BERULANG DI SETIAP HALAMAN */
        .print-footer {
            margin-top: 20px;
            border-top: 2px solid #1f2937;
            padding-top: 10px;
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
                display: block !important; /* 🌟 Mencegah Halaman Pertama Kosong */
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important; /* Padding diatur oleh margin @page */
                box-shadow: none !important;
                min-height: auto !important;
                overflow: visible !important;
            }
            
            /* Menempelkan Footer di bawah setiap kertas */
            .print-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                margin-top: 0;
                background-color: white;
            }
            /* Memberi jarak agar konten tabel tidak tertutup footer */
            .footer-space {
                height: 70px;
            }
        }
    </style>
</head>
<body class="bg-gray-200 text-gray-900">

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

    @php
        $pengaturan = null;
        try { if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) $pengaturan = \App\Models\Pengaturan::first(); } catch (\Exception $e) {}
    @endphp

    <div class="flex justify-center py-10 print:py-0 print:block">
        
        <div class="cetak-kertas bg-white shadow-2xl w-[21cm] p-[1cm] mx-auto relative block">
            
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
                <div class="watermark font-bold transform -rotate-45 tracking-widest text-center leading-none opacity-50">
                    DATA<br>RAHASIA
                </div>
            </div>

            <!-- 🌟 WADAH TABEL AGAR FOOTER BISA BEKERJA SEMPURNA -->
            <table style="width: 100%; position: relative; z-index: 10;">
                <tbody>
                    <tr>
                        <td>
                            
                            <!-- KOP SEKOLAH -->
                            <div class="border-b-4 border-gray-800 pb-3 mb-6 flex items-center justify-between avoid-break">
                                <div class="w-24 h-24 flex-shrink-0 flex items-center justify-center">
                                    @if(isset($pengaturan) && $pengaturan->logo_dinas)
                                        <img src="{{ url('/uploads/' . $pengaturan->logo_dinas) }}" alt="Logo Dinas" class="max-w-full max-h-full object-contain">
                                    @endif
                                </div>
                                <div class="flex-1 text-center px-4">
                                    <h1 class="kop-1 font-bold uppercase tracking-wider mb-1">PEMERINTAH PROVINSI BANTEN</h1>
                                    <h1 class="kop-1 font-bold uppercase tracking-wider leading-tight mb-2">DINAS PENDIDIKAN DAN KEBUDAYAAN</h1>
                                    <h1 class="kop-2 font-bold uppercase tracking-wider mt-1">{{ $pengaturan->nama_sekolah ?? 'SMA NEGERI 1 MALINGPING' }}</h1>
                                    <p class="mt-1">Jalan Raya Binuangeun Km. 02 Malingping Lebak - Banten 42391</p>
                                </div>
                                <div class="w-24 h-24 flex-shrink-0 flex items-center justify-center">
                                    @if(isset($pengaturan) && $pengaturan->logo_sekolah)
                                        <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo Sekolah" class="max-w-full max-h-full object-contain">
                                    @endif
                                </div>
                            </div>

                            <div class="text-center mb-6 avoid-break">
                                <h2 class="font-bold underline uppercase" style="font-size: 13pt;">Buku Induk - Profil & Portofolio Siswa</h2>
                                <p class="mt-1">Nomor Induk Siswa Nasional (NISN): <span class="font-bold">{{ $siswa->nisn ?? '-' }}</span></p>
                            </div>

                            <div class="mb-4 avoid-break">
                                <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase border border-gray-400">A. Keterangan Diri Siswa</h3>
                                <table class="w-full ml-1">
                                    <tr><td class="py-1 w-1/3">1. Nama Lengkap</td><td class="w-4">:</td><td class="uppercase font-bold">{{ $siswa->nama_lengkap }}</td></tr>
                                    <tr><td class="py-1">2. Jenis Kelamin</td><td>:</td><td>{{ $siswa->jenis_kelamin }}</td></tr>
                                    <tr><td class="py-1">3. Tempat, Tgl Lahir</td><td>:</td><td>{{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->isoFormat('D MMMM Y') : '-' }}</td></tr>
                                    <tr><td class="py-1">4. Agama</td><td>:</td><td>{{ $siswa->agama ?? '-' }}</td></tr>
                                    <tr><td class="py-1">5. NIK (Kependudukan)</td><td>:</td><td>{{ $siswa->nik ?? '-' }}</td></tr>
                                    <tr><td class="py-1">6. No. Kartu Keluarga</td><td>:</td><td>{{ $siswa->no_kk ?? '-' }}</td></tr>
                                    <tr><td class="py-1">7. No. Telepon / HP</td><td>:</td><td>{{ $siswa->telepon ?? '-' }}</td></tr>
                                    <tr><td class="py-1">8. Alamat Email</td><td>:</td><td>{{ $siswa->email ?? '-' }}</td></tr>
                                </table>
                            </div>

                            <div class="mb-4 avoid-break">
                                <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase border border-gray-400">B. Keterangan Tempat Tinggal</h3>
                                <table class="w-full ml-1">
                                    <tr><td class="py-1 w-1/3 align-top">9. Alamat Lengkap</td><td class="w-4 align-top">:</td><td>{{ $siswa->alamat ?? '-' }}</td></tr>
                                    <tr><td class="py-1">10. RT / RW</td><td>:</td><td>{{ $siswa->rt ?? '-' }} / {{ $siswa->rw ?? '-' }}</td></tr>
                                    <tr><td class="py-1">11. Kelurahan / Desa</td><td>:</td><td>{{ $siswa->kelurahan ?? '-' }}</td></tr>
                                    <tr><td class="py-1">12. Kecamatan</td><td>:</td><td>{{ $siswa->kecamatan ?? '-' }}</td></tr>
                                    <tr><td class="py-1">13. Kabupaten / Kota</td><td>:</td><td>{{ $siswa->kabupaten ?? '-' }}</td></tr>
                                    <tr><td class="py-1">14. Koordinat (Lintang, Bujur)</td><td>:</td><td>{{ $siswa->lintang ?? '-' }}, {{ $siswa->bujur ?? '-' }}</td></tr>
                                </table>
                            </div>

                            <div class="mb-6 avoid-break">
                                <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase border border-gray-400">C. Keterangan Orang Tua / Wali</h3>
                                <table class="w-full ml-1">
                                    <tr><td class="py-1 w-1/3">15. Nama Ayah Kandung</td><td class="w-4">:</td><td>{{ $siswa->nama_ayah ?? '-' }}</td></tr>
                                    <tr><td class="py-1">16. No. HP Ayah</td><td>:</td><td>{{ $siswa->telepon_ayah ?? '-' }}</td></tr>
                                    <tr><td class="py-1">17. Nama Ibu Kandung</td><td>:</td><td>{{ $siswa->nama_ibu ?? '-' }}</td></tr>
                                    <tr><td class="py-1">18. No. HP Ibu</td><td>:</td><td>{{ $siswa->telepon_ibu ?? '-' }}</td></tr>
                                    <tr><td class="py-1">19. Nama Wali</td><td>:</td><td>{{ $siswa->nama_wali ?? '-' }}</td></tr>
                                    <tr><td class="py-1">20. No. HP Wali</td><td>:</td><td>{{ $siswa->telepon_wali ?? '-' }}</td></tr>
                                </table>
                            </div>

                            <div class="mb-6 avoid-break">
                                <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase border border-gray-400">D. Keterangan Akademik Dasar</h3>
                                
                                <div class="flex items-start justify-between ml-1">
                                    <div class="flex-1 pr-4">
                                        <table class="w-full">
                                            <tr><td class="py-1 w-2/5">21. NIS / NISN</td><td class="w-4">:</td><td class="py-1 font-bold">{{ $siswa->nis }} / {{ $siswa->nisn ?? '-' }}</td></tr>
                                            <tr><td class="py-1">22. Kelas Saat Ini</td><td>:</td><td class="py-1 font-bold">{{ $siswa->kelas->nama_kelas ?? 'Belum ada kelas' }}</td></tr>
                                            <tr><td class="py-1">23. Sekolah Asal</td><td>:</td><td class="py-1">{{ $siswa->sekolah_asal ?? '-' }}</td></tr>
                                            <tr><td class="py-1">24. Status Masuk</td><td>:</td><td class="py-1">{{ $siswa->jalur_masuk ?? 'Siswa Baru' }}</td></tr>
                                            <tr><td class="py-1">25. Tanggal Masuk</td><td>:</td><td class="py-1">{{ $siswa->tanggal_masuk ? \Carbon\Carbon::parse($siswa->tanggal_masuk)->isoFormat('D MMMM Y') : '-' }}</td></tr>
                                            <tr><td class="py-1">26. Status Terkini</td><td>:</td><td class="py-1 font-bold uppercase {{ $siswa->status_siswa == 'Aktif' ? 'text-green-700' : 'text-red-700' }}">{{ $siswa->status_siswa }}</td></tr>
                                        </table>
                                    </div>
                                    
                                    <div class="w-[3.5cm] h-[4.5cm] border-2 border-gray-800 p-1 flex items-center justify-center overflow-hidden bg-white flex-shrink-0 relative z-20">
                                        @if($siswa->foto)
                                            <img src="{{ url('/uploads/' . $siswa->foto) }}" alt="Foto" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-gray-400 text-center text-[10pt]">Pas Foto<br>3 x 4</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mb-6 avoid-break">
                                <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase border border-gray-400">E. Riwayat Kelas & Mutasi</h3>
                                <table class="w-full border-collapse border border-gray-400 text-center">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border border-gray-400 p-2 w-12">No</th>
                                            <th class="border border-gray-400 p-2">Tahun Ajaran</th>
                                            <th class="border border-gray-400 p-2">Kelas</th>
                                            <th class="border border-gray-400 p-2">Status / Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($siswa->riwayatKelas as $index => $rw)
                                            <tr>
                                                <td class="border border-gray-400 p-2">{{ $index + 1 }}</td>
                                                <td class="border border-gray-400 p-2">{{ $rw->tahunAjaran->nama_tahun ?? '-' }}</td>
                                                <td class="border border-gray-400 p-2">{{ $rw->kelas->nama_kelas ?? '-' }}</td>
                                                <td class="border border-gray-400 p-2">{{ $rw->status_riwayat }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="border border-gray-400 p-3 italic text-gray-500">Belum ada riwayat pergerakan kelas yang tercatat.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mb-6 avoid-break">
                                <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase border border-gray-400">F. Catatan Siswa (Buku Kasus & Prestasi)</h3>
                                <table class="w-full border-collapse border border-gray-400 text-center">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border border-gray-400 p-2 w-32">Tanggal</th>
                                            <th class="border border-gray-400 p-2 w-28">Jenis</th>
                                            <th class="border border-gray-400 p-2">Perihal / Keterangan</th>
                                            <th class="border border-gray-400 p-2 w-40">Pencatat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($siswa->catatan->sortByDesc('tanggal') as $catatan)
                                            <tr>
                                                <td class="border border-gray-400 p-2 align-top">{{ \Carbon\Carbon::parse($catatan->tanggal)->format('d/m/Y') }}</td>
                                                <td class="border border-gray-400 p-2 align-top font-bold {{ $catatan->jenis_catatan == 'Positif' ? 'text-green-700' : ($catatan->jenis_catatan == 'Negatif' ? 'text-red-700' : 'text-blue-700') }}">{{ $catatan->jenis_catatan }}</td>
                                                <td class="border border-gray-400 p-2 text-left">
                                                    <strong class="block mb-1">{{ $catatan->judul_catatan }}</strong>
                                                    <span class="text-gray-700">{{ $catatan->isi_catatan }}</span>
                                                </td>
                                                <td class="border border-gray-400 p-2 align-top">{{ $catatan->pencatat->name ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="border border-gray-400 p-3 italic text-gray-500">Siswa belum memiliki catatan pelanggaran maupun prestasi.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mb-6 avoid-break">
                                <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase border border-gray-400">G. Riwayat Surat Panggilan Orang Tua</h3>
                                <table class="w-full border-collapse border border-gray-400 text-center">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border border-gray-400 p-2 w-12">No</th>
                                            <th class="border border-gray-400 p-2 w-48">Tgl Pemanggilan</th>
                                            <th class="border border-gray-400 p-2">Keperluan / Keterangan</th>
                                            <th class="border border-gray-400 p-2 w-32">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($siswa->suratPanggilan->sortBy('tanggal_surat') as $index => $sp)
                                            <tr>
                                                <td class="border border-gray-400 p-2 align-top">{{ $index + 1 }}</td>
                                                <td class="border border-gray-400 p-2 align-top whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($sp->tanggal_panggilan)->format('d/m/Y') }}<br>
                                                    <span class="text-gray-600">{{ date('H:i', strtotime($sp->waktu_panggilan)) }} WIB</span>
                                                </td>
                                                <td class="border border-gray-400 p-2 text-left">{{ $sp->alasan_panggilan }}</td>
                                                <td class="border border-gray-400 p-2 align-top font-bold uppercase {{ $sp->status == 'Selesai' ? 'text-green-700' : ($sp->status == 'Dibuat' ? 'text-yellow-600' : 'text-red-700') }}">{{ $sp->status }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="border border-gray-400 p-3 italic text-gray-500">Siswa belum memiliki riwayat panggilan orang tua.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mb-6 avoid-break">
                                <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase border border-gray-400">
                                    H. Rekapitulasi Ketidakhadiran 
                                    <span class="font-normal normal-case">(Tahun Ajaran: {{ $tahunAjaranAktif ? $tahunAjaranAktif->nama_tahun . ' ' . $tahunAjaranAktif->semester : 'Belum Diatur' }})</span>
                                </h3>
                                
                                @php
                                    $absensiGrouped = $siswa->kehadiranHarian->groupBy(function($item) {
                                        return \Carbon\Carbon::parse($item->rekapKehadiran->tanggal)->isoFormat('MMMM YYYY');
                                    });
                                @endphp

                                @if($absensiGrouped->isEmpty())
                                    <div class="border border-gray-400 p-4 text-center italic text-gray-600 font-semibold bg-gray-50">
                                        Luar Biasa! Siswa ini tidak pernah abstain (Selalu Hadir) di semester aktif ini.
                                    </div>
                                @else
                                    <table class="w-full border-collapse border border-gray-400">
                                        <tbody>
                                            @foreach($absensiGrouped as $bulan => $items)
                                                <tr class="bg-gray-100 font-bold text-left">
                                                    <td colspan="3" class="border border-gray-400 p-2 px-3 uppercase text-gray-800">{{ $bulan }}</td>
                                                </tr>
                                                @foreach($items->sortBy(fn($i) => $i->rekapKehadiran->tanggal) as $absen)
                                                    <tr>
                                                        <td class="border border-gray-400 p-2 text-center w-40">
                                                            {{ \Carbon\Carbon::parse($absen->rekapKehadiran->tanggal)->isoFormat('D MMMM Y') }}
                                                        </td>
                                                        <td class="border border-gray-400 p-2 text-center font-bold w-32 uppercase {{ $absen->status == 'Alpa' ? 'text-red-600' : ($absen->status == 'Sakit' ? 'text-yellow-600' : 'text-blue-600') }}">
                                                            {{ $absen->status }}
                                                        </td>
                                                        <td class="border border-gray-400 p-2 pl-3">
                                                            {{ $absen->keterangan ?? 'Tanpa Keterangan' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>

                        </td>
                    </tr>
                </tbody>
                <!-- 🌟 TFOOT INI MEMBERIKAN JARAK AGAR KONTEN TIDAK TERTUTUP OLEH FOOTER BAWAH -->
                <tfoot>
                    <tr>
                        <td>
                            <div class="footer-space hidden print:block"></div>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <!-- 🌟 FOOTER YANG AKAN BERULANG DI SETIAP HALAMAN BAWAH KETIKA DICETAK -->
            <div class="print-footer relative z-20">
                <div class="flex justify-between font-bold mb-1 text-[10pt]">
                    <span>Biodata {{ $siswa->nama_lengkap }}</span>
                    <span>Halaman ... dari ...</span> <!-- Chrome secara otomatis akan mengisi footer jika fiturnya diaktifkan, atau kita sediakan area tulis manual -->
                </div>
                <div class="text-center text-gray-700 italic text-[10pt]">
                    Dicetak pada Sistem Informasi {{ $pengaturan->nama_sekolah ?? 'Sekolah' }} | Waktu Cetak: {{ now()->isoFormat('D MMMM Y - HH:mm') }} WIB
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