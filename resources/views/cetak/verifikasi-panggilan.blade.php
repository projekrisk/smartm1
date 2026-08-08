<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Surat Panggilan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full bg-white shadow-xl rounded-2xl p-6 border-t-8 border-blue-600 relative overflow-hidden">
        <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-3 text-3xl font-bold">
            ✓
        </div>

        <div class="text-center">
            <h1 class="text-xl font-bold text-gray-900 uppercase">Dokumen Resmi & Valid</h1>
            <p class="text-xs text-gray-500 mt-1">Sistem Penjaminan Keaslian Dokumen Elektronik</p>
        </div>

        <div class="mt-6 border-t border-gray-200 pt-5 space-y-4">
            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                <span class="block text-[11px] text-gray-500 uppercase tracking-wider font-bold mb-1">Nomor Surat</span>
                <span class="block font-bold text-gray-900 text-sm">{{ $surat->nomor_surat }}</span>
            </div>

            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                <span class="block text-[11px] text-gray-500 uppercase tracking-wider font-bold mb-1">Ditujukan Kepada Wali Murid Dari:</span>
                <span class="block font-bold text-gray-900 text-sm uppercase">{{ $surat->siswa->nama_lengkap ?? '-' }}</span>
                <span class="block text-gray-500 text-xs mt-0.5">Kelas {{ $surat->siswa->kelas->nama_kelas ?? '-' }}</span>
            </div>

            <div class="bg-blue-50 p-3 rounded-lg border border-blue-100">
                <span class="block text-[11px] text-blue-500 uppercase tracking-wider font-bold mb-1">Jadwal Kehadiran</span>
                <span class="block font-bold text-blue-900 text-sm leading-snug">
                    {{ \Carbon\Carbon::parse($surat->tanggal_panggilan)->isoFormat('dddd, D MMMM Y') }} <br> 
                    Pukul {{ date('H:i', strtotime($surat->waktu_panggilan)) }} WIB
                </span>
                <span class="block text-blue-700 text-xs mt-1 font-semibold">Tempat: {{ $surat->tempat_pertemuan }}</span>
            </div>
        </div>

        <div class="mt-8 mb-2">
            <a href="{{ url('/') }}" class="w-full flex items-center justify-center gap-2 bg-gray-800 hover:bg-gray-900 text-white py-2.5 rounded-lg font-semibold text-sm transition-colors shadow-md">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>