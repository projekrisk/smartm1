<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat & Catatan - {{ $siswa->nama_lengkap }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4 portrait;
            margin: 2cm;
        }
        
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: white !important;
            }
            .cetak-kertas {
                display: block !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important; 
                box-shadow: none !important;
                min-height: auto !important;
            }
        }
    </style>
</head>
<body class="bg-gray-200 text-gray-900 font-serif text-[13px]">

    <div class="no-print fixed top-5 left-5 z-50">
        <button onclick="window.close()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 transition">
            &larr; Tutup Halaman
        </button>
    </div>
    <div class="no-print fixed top-5 right-5 z-50">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500 transition">
            Cetak Dokumen
        </button>
    </div>

    <div class="flex justify-center py-10 print:py-0 print:block">
        
        <div class="cetak-kertas bg-white shadow-2xl rounded w-[21cm] min-h-[29.7cm] p-[2cm] mx-auto">
            
            <table class="w-full" style="height: 100%; min-height: 25.7cm;">
                <tbody class="table-row-group">
                    <tr>
                        <td class="align-top pb-10">
                            
                            <div class="border-b-4 border-gray-800 pb-3 mb-6 text-center font-sans">
                                <h1 class="text-2xl font-bold uppercase tracking-wider">SMAN 1 MALINGPING</h1>
                                <p class="text-xs mt-1">Jl. Pendidikan No. 1, Kec. Pintar, Kota Cerdas, 12345</p>
                                <p class="text-xs">Telepon: (021) 888-9999 | Email: info@smart-m1.com</p>
                            </div>

                            <div class="text-center mb-6 font-sans">
                                <h2 class="text-lg font-bold underline uppercase">Laporan Riwayat & Catatan Siswa</h2>
                            </div>

                            <div class="flex items-start gap-4 mb-6 avoid-break p-3 border border-gray-400 bg-gray-50 rounded">
                                <div class="flex-1">
                                    <table class="w-full">
                                        <tr><td class="py-1 w-32 text-gray-600 font-semibold">Nama Lengkap</td><td class="w-2">:</td><td class="py-1 font-bold uppercase text-sm">{{ $siswa->nama_lengkap }}</td></tr>
                                        <tr><td class="py-1 text-gray-600 font-semibold">NIS / NISN</td><td>:</td><td class="py-1">{{ $siswa->nis }} / {{ $siswa->nisn ?? '-' }}</td></tr>
                                        <tr><td class="py-1 text-gray-600 font-semibold">Kelas Saat Ini</td><td>:</td><td class="py-1 font-bold">{{ $siswa->kelas->nama_kelas ?? 'Belum ada kelas' }}</td></tr>
                                        <tr><td class="py-1 text-gray-600 font-semibold">Status</td><td>:</td><td class="py-1 uppercase">{{ $siswa->status_siswa }} ({{ $siswa->jalur_masuk ?? 'Siswa Baru' }})</td></tr>
                                    </table>
                                </div>
                                <div class="w-[3cm] h-[4cm] border-2 border-gray-800 p-1 bg-white flex-shrink-0 flex items-center justify-center overflow-hidden">
                                    @if($siswa->foto)
                                        <img src="{{ url('/uploads/' . $siswa->foto) }}" alt="Foto" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-gray-400 text-xs text-center font-sans">Pas Foto<br>3 x 4</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-6 avoid-break font-sans">
                                <h3 class="font-bold text-sm bg-gray-800 text-white px-3 py-1 mb-2 uppercase border border-gray-800">A. Riwayat Kelas & Mutasi</h3>
                                <table class="w-full border-collapse border border-gray-400 text-center">
                                    <thead>
                                        <tr class="bg-gray-200">
                                            <th class="border border-gray-400 p-1 w-12">No</th>
                                            <th class="border border-gray-400 p-1">Tahun Ajaran</th>
                                            <th class="border border-gray-400 p-1">Kelas</th>
                                            <th class="border border-gray-400 p-1">Status Riwayat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($siswa->riwayatKelas as $index => $riwayat)
                                            <tr>
                                                <td class="border border-gray-400 p-1">{{ $index + 1 }}</td>
                                                <td class="border border-gray-400 p-1">{{ $riwayat->tahunAjaran->nama_tahun ?? '-' }}</td>
                                                <td class="border border-gray-400 p-1">{{ $riwayat->kelas->nama_kelas ?? '-' }}</td>
                                                <td class="border border-gray-400 p-1">{{ $riwayat->status_riwayat }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="border border-gray-400 p-3 italic text-gray-500 font-serif">Belum ada riwayat kelas yang tercatat.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="avoid-break mb-8 font-sans">
                                <h3 class="font-bold text-sm bg-gray-800 text-white px-3 py-1 mb-2 uppercase border border-gray-800">B. Buku Kasus & Prestasi (Catatan Siswa)</h3>
                                
                                @if($siswa->catatan->count() > 0)
                                    <table class="w-full border-collapse border border-gray-400">
                                        <thead>
                                            <tr class="bg-gray-200 text-center">
                                                <th class="border border-gray-400 p-1 w-24">Tanggal</th>
                                                <th class="border border-gray-400 p-1 w-20">Jenis</th>
                                                <th class="border border-gray-400 p-1">Kasus / Judul Catatan</th>
                                                <th class="border border-gray-400 p-1 w-32">Pencatat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($siswa->catatan->sortByDesc('tanggal') as $catatan)
                                                <tr>
                                                    <td class="border border-gray-400 p-2 text-center align-top whitespace-nowrap">{{ \Carbon\Carbon::parse($catatan->tanggal)->format('d M Y') }}</td>
                                                    <td class="border border-gray-400 p-2 text-center align-top font-semibold 
                                                        {{ $catatan->jenis_catatan == 'Positif' ? 'text-green-700' : ($catatan->jenis_catatan == 'Negatif' ? 'text-red-700' : 'text-blue-700') }}">
                                                        {{ $catatan->jenis_catatan }}
                                                    </td>
                                                    <td class="border border-gray-400 p-2 align-top">
                                                        <strong class="block mb-1">{{ $catatan->judul_catatan }}</strong>
                                                        <span class="text-gray-700 font-serif">{{ $catatan->isi_catatan }}</span>
                                                    </td>
                                                    <td class="border border-gray-400 p-2 text-center align-top text-xs">{{ $catatan->pencatat->name ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="border border-gray-400 p-3 text-center italic text-gray-500 font-serif">
                                        Belum ada catatan pelanggaran atau prestasi untuk siswa ini.
                                    </div>
                                @endif
                            </div>

                        </td>
                    </tr>
                </tbody>

                <tfoot class="table-footer-group">
                    <tr>
                        <td class="align-bottom">
                            <div class="border-t border-gray-400 pt-2 mt-4 text-center text-xs text-gray-500 italic font-sans">
                                Dicetak pada Smart-M1 SMAN 1 Malingping | Waktu Cetak: {{ now()->isoFormat('D MMMM Y - HH:mm') }} WIB
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>