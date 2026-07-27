<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Biodata Pegawai - {{ $pegawai->nama }}</title>
    <!-- Menggunakan Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Pengaturan wajib untuk kertas cetak A4 dengan margin 2cm */
        @page {
            size: A4 portrait;
            margin: 1cm 1.5cm;
        }
        
        /* Memastikan background berwarna ikut tercetak (termasuk watermark) */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Hindari elemen terpotong saat ganti halaman */
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

    <!-- Tombol Navigasi (Sembunyi saat cetak) -->
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

    <!-- Wrapper pelindung -->
    <div class="flex justify-center py-10 print:py-0 print:block">
        
        <!-- Kertas A4 -->
        <div class="cetak-kertas bg-white shadow-2xl rounded w-[21cm] min-h-[29.7cm] p-[1cm] mx-auto relative flex flex-col overflow-hidden">
            
            @php
                // Menarik data pengaturan untuk logo dan nama sekolah
                $pengaturan = null;
                try {
                    if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) {
                        $pengaturan = \App\Models\Pengaturan::first();
                    }
                } catch (\Exception $e) {}
            @endphp

            <!-- KONTEN UTAMA -->
            <div class="relative z-10 flex-1 flex flex-col">
                
                <div class="border-b-4 border-gray-800 pb-3 mb-6 text-center avoid-break">
                    <h1 class="text-2xl font-bold uppercase tracking-wider font-sans">{{ $pengaturan->nama_sekolah ?? 'SMAN 1 MALINGPING' }}</h1>
                    <p class="text-xs mt-1">Sistem Informasi Kepegawaian (SIMPEG)</p>
                </div>

                <!-- JUDUL DOKUMEN -->
                <div class="text-center mb-6 avoid-break">
                    <h2 class="text-lg font-bold underline uppercase">Biodata Pegawai</h2>
                    <p class="text-xs mt-1">Nomor Induk Kependudukan (NIK): <span class="font-bold">{{ $pegawai->nik ?? '-' }}</span></p>
                </div>

                <div class="mb-4 text-[13px] avoid-break">
                    <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase text-xs border border-gray-400">A. Identitas Pribadi</h3>
                    <div class="flex items-start justify-between ml-1">
                        <div class="flex-1 pr-4">
                            <table class="w-full">
                                <tr><td class="py-0.5 w-1/3">1. Nama Lengkap</td><td class="w-2">:</td><td class="uppercase font-bold">{{ $pegawai->nama }}</td></tr>
                                <tr><td class="py-0.5">2. Jenis Kelamin</td><td>:</td><td>{{ $pegawai->jenis_kelamin }}</td></tr>
                                <tr><td class="py-0.5">3. Tempat, Tgl Lahir</td><td>:</td><td>{{ $pegawai->tempat_lahir ?? '-' }}, {{ $pegawai->tanggal_lahir ? \Carbon\Carbon::parse($pegawai->tanggal_lahir)->isoFormat('D MMMM Y') : '-' }}</td></tr>
                                <tr><td class="py-0.5">4. NIK (No. KTP)</td><td>:</td><td>{{ $pegawai->nik }}</td></tr>
                                <tr><td class="py-0.5">5. No. Telepon / HP</td><td>:</td><td>{{ $pegawai->telepon ?? '-' }}</td></tr>
                                <tr><td class="py-0.5">6. Alamat Email</td><td>:</td><td>{{ $pegawai->email ?? '-' }}</td></tr>
                            </table>
                        </div>
                        <!-- Foto Pegawai -->
                        <div class="w-[3cm] h-[4cm] border-2 border-gray-800 p-1 flex items-center justify-center overflow-hidden bg-white flex-shrink-0 relative z-20">
                            @if($pegawai->foto)
                                <img src="{{ url('/uploads/' . $pegawai->foto) }}" alt="Foto" class="w-full h-full object-cover">
                            @else
                                <span class="text-gray-400 text-xs text-center">Pas Foto<br>3 x 4</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- DATA KEPEGAWAIAN -->
                <div class="mb-4 text-[13px] avoid-break">
                    <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase text-xs border border-gray-400">B. Data Kepegawaian</h3>
                    <table class="w-full ml-1">
                        <tr><td class="py-0.5 w-1/3 align-top">7. Status Kepegawaian</td><td class="w-2 align-top">:</td><td class="font-bold uppercase">{{ $pegawai->status_kepegawaian }}</td></tr>
                        <tr><td class="py-0.5">8. Tugas Utama</td><td>:</td><td>{{ $pegawai->tugas_utama ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">9. NIP</td><td>:</td><td>{{ $pegawai->nip ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">10. NUPTK</td><td>:</td><td>{{ $pegawai->nuptk ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">11. Pangkat / Gol. Ruang</td><td>:</td><td>{{ $pegawai->pangkat_golongan ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">12. Jabatan</td><td>:</td><td>{{ $pegawai->jabatan ?? '-' }}</td></tr>
                        <tr>
                            <td class="py-0.5 align-top">13. Status Tugas Saat Ini</td>
                            <td class="align-top">:</td>
                            <td>
                                @php
                                    $tugas = $pegawai->daftar_tugas_tambahan;
                                @endphp
                                {{ empty($tugas) ? 'Tidak ada tugas tambahan.' : implode(', ', (array) $tugas) }}
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- RIWAYAT PENGANGKATAN -->
                <div class="mb-4 text-[13px] avoid-break">
                    <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase text-xs border border-gray-400">C. Riwayat Pengangkatan & Masa Kerja</h3>
                    <table class="w-full ml-1">
                        <tr><td class="py-0.5 w-1/3">14. TMT CPNS / Honorer Awal</td><td class="w-2">:</td><td>{{ $pegawai->tmt_cpns_honorer ? \Carbon\Carbon::parse($pegawai->tmt_cpns_honorer)->isoFormat('D MMMM Y') : '-' }}</td></tr>
                        <tr><td class="py-0.5">15. TMT PNS / PPPK</td><td>:</td><td>{{ $pegawai->tmt_pns_pppk ? \Carbon\Carbon::parse($pegawai->tmt_pns_pppk)->isoFormat('D MMMM Y') : '-' }}</td></tr>
                        <tr><td class="py-0.5">16. TMT Golongan Terakhir</td><td>:</td><td>{{ $pegawai->tmt_golongan_terakhir ? \Carbon\Carbon::parse($pegawai->tmt_golongan_terakhir)->isoFormat('D MMMM Y') : '-' }}</td></tr>
                        <tr>
                            <td class="py-0.5 font-bold">17. Kalkulasi Masa Kerja</td>
                            <td>:</td>
                            <td class="font-bold">
                                Masa Kerja Golongan: {{ intval($pegawai->masa_kerja_golongan) }} Tahun <br>
                                Masa Kerja Keseluruhan: {{ intval($pegawai->masa_kerja_keseluruhan) }} Tahun
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- PENDIDIKAN TERAKHIR -->
                <div class="mb-6 text-[13px] avoid-break">
                    <h3 class="font-bold bg-gray-200 px-2 py-1 mb-2 uppercase text-xs border border-gray-400">D. Pendidikan Terakhir</h3>
                    <table class="w-full ml-1">
                        <tr><td class="py-0.5 w-1/3">18. Tingkat Ijazah</td><td class="w-2">:</td><td class="font-bold">{{ $pegawai->pendidikan_ijazah ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">19. Tahun Lulus</td><td>:</td><td>{{ $pegawai->pendidikan_tahun ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">20. Fakultas / Jurusan</td><td>:</td><td>{{ $pegawai->pendidikan_jurusan ?? '-' }}</td></tr>
                    </table>
                </div>

                <div class="mt-auto border-t-2 border-gray-800 pt-3 text-center text-xs text-gray-700 italic avoid-break">
                    Dicetak dari Sistem Informasi Manajemen Sekolah | Waktu Cetak: {{ now()->isoFormat('D MMMM Y - HH:mm') }} WIB
                </div>

            </div>
        </div>
    </div>

    <!-- Script Print Otomatis -->
    <script>
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 800);
        }
    </script>
</body>
</html>