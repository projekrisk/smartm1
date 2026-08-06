<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keaslian Surat Dispensasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full bg-white shadow-xl rounded-2xl p-6 text-center border-t-8 border-green-600">
        <!-- Ikon Sukses -->
        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl font-bold">
            ✓
        </div>

        <h1 class="text-xl font-bold text-gray-900 uppercase">Dokumen Resmi & Valid</h1>
        <p class="text-xs text-gray-500 mt-1">Sistem Penjaminan Keaslian Dokumen Elektronik</p>

        <div class="mt-6 text-left border-t border-gray-200 pt-4 space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Nomor Surat:</span>
                <span class="font-bold text-right">{{ $surat->nomor_surat_lengkap }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Nama Kegiatan:</span>
                <span class="font-semibold text-right">{{ $surat->nama_kegiatan }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Penyelenggara:</span>
                <span class="font-medium text-right">{{ $surat->penyelenggara }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Penandatangan:</span>
                <span class="font-semibold text-right">{{ $surat->penandatangan->nama ?? '-' }}</span>
            </div>
        </div>

        <!-- Daftar Siswa Terlampir -->
        <div class="mt-6 text-left border-t border-gray-200 pt-4">
            <h3 class="font-bold text-xs uppercase text-gray-500 mb-2">Peserta Didik yang Diberi Dispensasi:</h3>
            <ul class="text-xs space-y-1 bg-gray-50 p-3 rounded-lg max-h-40 overflow-y-auto">
                @foreach($surat->siswa as $s)
                    <li class="font-medium">• {{ $s->nama_lengkap }} <span class="text-gray-500">({{ $s->kelas->nama_kelas ?? '-' }})</span></li>
                @endforeach
            </ul>
        </div>

        <div class="mt-8 text-center text-[11px] text-gray-400">
            &copy; {{ date('Y') }} SMAN 1 Malingping. Dokumen ini sah dan diterbitkan secara elektronik melalui SMART-M1.
        </div>
    </div>

</body>
</html>