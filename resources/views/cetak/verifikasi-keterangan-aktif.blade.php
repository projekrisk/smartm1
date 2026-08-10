<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Dokumen Resmi - Smart-M1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        
        <!-- Header / Status Valid -->
        <div class="bg-green-600 px-6 py-8 text-center text-white">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-500 rounded-full mb-4 shadow-lg border-4 border-green-300">
                <i class="fas fa-check text-4xl"></i>
            </div>
            <h1 class="text-2xl font-bold mb-1">DOKUMEN VALID</h1>
            <p class="text-green-100 text-sm">Surat Keterangan Aktif Sekolah</p>
        </div>

        <!-- Detail Data -->
        <div class="p-6">
            <div class="space-y-4 text-sm text-gray-700">
                
                <div class="pb-3 border-b border-gray-100">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Nomor Surat</p>
                    <p class="font-bold text-gray-900 text-base">{{ $surat->nomor_surat }}</p>
                </div>

                <div class="pb-3 border-b border-gray-100">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Tanggal Diterbitkan</p>
                    <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($surat->tanggal_surat)->isoFormat('D MMMM Y') }}</p>
                </div>

                <div class="pb-3 border-b border-gray-100">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Data Siswa</p>
                    <p class="font-bold text-gray-900 uppercase">{{ $surat->siswa->nama_lengkap }}</p>
                    <p>NISN: {{ $surat->siswa->nisn ?? '-' }}</p>
                    <p>Kelas: {{ $surat->siswa->kelas->nama_kelas ?? '-' }}</p>
                </div>

                <div class="pb-3 border-b border-gray-100">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Tahun Ajaran</p>
                    <p class="font-bold text-gray-900">{{ $surat->tahun_ajaran }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Penandatangan Terverifikasi</p>
                    <p class="font-bold text-gray-900">{{ $surat->penandatangan->nama }}</p>
                    <p>NIP. {{ $surat->penandatangan->nip ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 text-center border-t border-gray-100">
            <p class="text-xs text-gray-500">Sistem Informasi Manajemen<br><b>{{ $pengaturan->nama_sekolah ?? 'SMA Negeri 1 Malingping' }}</b></p>
        </div>

    </div>

</body>
</html>