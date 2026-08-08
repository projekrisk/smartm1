<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keaslian Surat Dispensasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full bg-white shadow-xl rounded-2xl p-6 border-t-8 border-green-600 relative overflow-hidden">
        
        <!-- Ikon Sukses -->
        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3 text-3xl font-bold">
            ✓
        </div>

        <div class="text-center">
            <h1 class="text-xl font-bold text-gray-900 uppercase">Dokumen Resmi & Valid</h1>
            <p class="text-xs text-gray-500 mt-1">Sistem Penjaminan Keaslian Dokumen Elektronik</p>
        </div>

        <!-- 🌟 LAYOUT BARU: Atas-Bawah -->
        <div class="mt-6 border-t border-gray-200 pt-5 space-y-4">
            
            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                <span class="block text-[11px] text-gray-500 uppercase tracking-wider font-bold mb-1">Nomor Surat</span>
                <span class="block font-bold text-gray-900 text-sm">{{ $surat->nomor_surat_lengkap }}</span>
            </div>

            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                <span class="block text-[11px] text-gray-500 uppercase tracking-wider font-bold mb-1">Perihal / Nama Kegiatan</span>
                <span class="block font-semibold text-gray-900 text-sm leading-snug">{{ $surat->nama_kegiatan }}</span>
            </div>

            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                <span class="block text-[11px] text-gray-500 uppercase tracking-wider font-bold mb-1">Penyelenggara</span>
                <span class="block font-medium text-gray-900 text-sm">{{ $surat->penyelenggara }}</span>
            </div>

            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                <span class="block text-[11px] text-gray-500 uppercase tracking-wider font-bold mb-1">Ditandatangani Secara Elektronik Oleh:</span>
                <span class="block font-bold text-blue-700 text-sm uppercase">{{ $surat->penandatangan->nama ?? '-' }}</span>
            </div>
        </div>

        <!-- Daftar Siswa -->
        <div class="mt-5 border-t border-gray-200 pt-5">
            <span class="block text-[11px] text-gray-500 uppercase tracking-wider font-bold mb-2">Peserta Didik yang Diberi Dispensasi ({{ $surat->siswa->count() }} Orang):</span>
            <ul class="text-xs space-y-1.5 bg-gray-50 p-3 rounded-lg border border-gray-100 max-h-40 overflow-y-auto">
                @foreach($surat->siswa->sortBy('nama_lengkap') as $s)
                    <li class="font-semibold flex justify-between border-b border-gray-200 pb-1 last:border-0 last:pb-0">
                        <span>• {{ $s->nama_lengkap }}</span> 
                        <span class="text-gray-500 font-normal">Kelas {{ $s->kelas->nama_kelas ?? '-' }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- 🌟 TOMBOL HOME -->
        <div class="mt-8 mb-2">
            <a href="{{ url('/') }}" class="w-full flex items-center justify-center gap-2 bg-gray-800 hover:bg-gray-900 text-white py-2.5 rounded-lg font-semibold text-sm transition-colors shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                Kembali ke Beranda
            </a>
        </div>

        <div class="mt-4 text-center text-[10px] text-gray-400 leading-tight">
            &copy; {{ date('Y') }} {{ $surat->pengaturan->nama_sekolah ?? 'SMAN 1 Malingping' }}. <br>Dokumen ini sah dan diterbitkan secara elektronik.
        </div>
    </div>

</body>
</html>