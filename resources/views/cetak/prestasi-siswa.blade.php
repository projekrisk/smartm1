<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Prestasi Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4 landscape; margin: 1cm; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; font-family: Arial, Helvetica, sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
            .cetak-kertas { width: 100% !important; margin: 0 !important; padding: 0 !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-gray-200 text-black text-[10px]">
    <div class="no-print fixed top-5 left-5 z-50 flex gap-2">
        <button onclick="window.close()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 font-bold">&larr; Tutup Tab</button>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500 font-bold">Cetak Laporan</button>
    </div>

    <div class="flex justify-center py-10 print:py-0 print:block min-w-max">
        <div class="cetak-kertas bg-white shadow-2xl rounded p-[1cm] mx-auto min-w-[29.7cm] flex flex-col">
            
            <div class="border-b-4 border-gray-800 pb-2 mb-4 text-center">
                <h1 class="text-xl font-bold uppercase tracking-wider">{{ $pengaturan->nama_sekolah ?? 'SMAN 1 MALINGPING' }}</h1>
                <h2 class="text-[14px] font-bold uppercase tracking-wider mt-1">Daftar Rekapitulasi Prestasi Siswa</h2>
                <p class="text-[11px] mt-1 text-gray-700">Dicetak pada: {{ now()->isoFormat('D MMMM Y') }}</p>
            </div>

            <table class="w-full border-collapse border border-gray-600 mb-4 text-[10px]">
                <thead>
                    <tr class="bg-gray-200 text-center uppercase font-bold">
                        <th class="border border-gray-600 p-2 w-8">No</th>
                        <th class="border border-gray-600 p-2 w-24">NIS / NISN</th>
                        <th class="border border-gray-600 p-2 w-32">Nama Siswa</th>
                        <th class="border border-gray-600 p-2 w-16">Kelas</th>
                        <th class="border border-gray-600 p-2 text-left">Bidang Lomba (Prestasi)</th>
                        <th class="border border-gray-600 p-2 w-20">Peringkat</th>
                        <th class="border border-gray-600 p-2 w-20">Jenis</th>
                        <th class="border border-gray-600 p-2 w-20">Kategori</th>
                        <th class="border border-gray-600 p-2 w-24">Tingkat</th>
                        <th class="border border-gray-600 p-2 w-32">Penyelenggara</th>
                        <th class="border border-gray-600 p-2 w-20">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prestasis as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-600 p-2 text-center">{{ $index + 1 }}</td>
                            <td class="border border-gray-600 p-2 text-center">{{ $item->siswa->nis ?? '-' }}<br>{{ $item->siswa->nisn ?? '-' }}</td>
                            <td class="border border-gray-600 p-2 font-bold uppercase">{{ $item->siswa->nama_lengkap ?? '-' }}</td>
                            <td class="border border-gray-600 p-2 text-center">{{ $item->siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td class="border border-gray-600 p-2 font-semibold">{{ $item->nama_prestasi }}</td>
                            <td class="border border-gray-600 p-2 text-center text-yellow-700 font-bold">{{ $item->juara }}</td>
                            <td class="border border-gray-600 p-2 text-center">{{ $item->jenis }}</td>
                            <td class="border border-gray-600 p-2 text-center">{{ $item->kategori }}</td>
                            <td class="border border-gray-600 p-2 text-center">{{ $item->tingkat }}</td>
                            <td class="border border-gray-600 p-2 text-center">{{ $item->penyelenggara ?? '-' }}</td>
                            <td class="border border-gray-600 p-2 text-center whitespace-nowrap">{{ \Carbon\Carbon::parse($item->tanggal_perolehan)->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="11" class="border border-gray-600 p-6 text-center italic text-gray-500">Belum ada data prestasi yang disetujui.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <script>window.onload = function() { setTimeout(() => { window.print(); }, 800); }</script>
</body>
</html>