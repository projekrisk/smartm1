<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Surat - Smart-M1</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 antialiased font-sans">

    @php
        if (!isset($pengaturan)) {
            try { 
                $pengaturan = \Illuminate\Support\Facades\Schema::hasTable('pengaturan') ? \App\Models\Pengaturan::first() : null; 
            } catch (\Exception $e) { 
                $pengaturan = null; 
            }
        }
    @endphp

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        
        <div class="bg-green-600 px-6 py-8 text-center text-white">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-500 rounded-full mb-4 shadow-lg border-4 border-green-300">
                <i class="fas fa-check text-4xl"></i>
            </div>
            <h1 class="text-2xl font-bold mb-1 tracking-tight">DOKUMEN VALID</h1>
            <p class="text-green-100 text-sm font-medium">Surat Dispensasi Belajar</p>
        </div>

        <div class="p-6">
            <div class="space-y-4 text-sm text-gray-700">
                
                <div class="pb-3 border-b border-gray-100">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Nomor Surat</p>
                    <p class="font-bold text-gray-900 text-base">{{ $surat->nomor_surat_lengkap }}</p>
                </div>

                <div class="pb-3 border-b border-gray-100">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Tanggal Diterbitkan</p>
                    <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($surat->tanggal_surat)->isoFormat('D MMMM Y') }}</p>
                </div>

                <div class="pb-3 border-b border-gray-100">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Kegiatan / Perihal</p>
                    <p class="font-bold text-gray-900">{{ $surat->nama_kegiatan }}</p>
                    <p class="text-gray-600 mt-0.5">Penyelenggara: {{ $surat->penyelenggara }}</p>
                    <p class="text-gray-600 mt-0.5">Waktu: 
                        @if(\Carbon\Carbon::parse($surat->tanggal_mulai)->isSameDay(\Carbon\Carbon::parse($surat->tanggal_selesai)))
                            {{ \Carbon\Carbon::parse($surat->tanggal_mulai)->isoFormat('D MMMM Y') }}
                        @else
                            {{ \Carbon\Carbon::parse($surat->tanggal_mulai)->isoFormat('D MMMM') }} - {{ \Carbon\Carbon::parse($surat->tanggal_selesai)->isoFormat('D MMMM Y') }}
                        @endif
                    </p>
                </div>

                <div class="pb-3 border-b border-gray-100">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-2">Peserta Didik ({{ $surat->siswa->count() }} Siswa)</p>
                    <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 max-h-40 overflow-y-auto">
                        <ul class="text-xs space-y-2">
                            @foreach($surat->siswa->sortBy('nama_lengkap') as $s)
                                <li class="font-semibold flex justify-between border-b border-gray-200 pb-2 last:border-0 last:pb-0">
                                    <span class="text-gray-800 uppercase pr-2">{{ $s->nama_lengkap }}</span> 
                                    <span class="text-gray-500 font-medium whitespace-nowrap">Kelas {{ $s->kelas->nama_kelas ?? '-' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Penandatangan Terverifikasi</p>
                    <p class="font-bold text-gray-900">{{ $surat->penandatangan->nama ?? '-' }}</p>
                    <p class="text-gray-600 mt-0.5">NIP. {{ $surat->penandatangan->nip ?? '-' }}</p>
                </div>
            </div>

            <div class="mt-8 mb-2">
                <a href="{{ url('/') }}" class="w-full flex items-center justify-center gap-2 bg-gray-900 hover:bg-black text-white py-2.5 rounded-lg font-semibold text-sm transition-colors shadow-md">
                    Kembali ke Beranda
                </a>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-4 text-center border-t border-gray-100">
            <p class="text-xs text-gray-500">Smart-M1 - Sistem Informasi Manajemen<br><b class="font-semibold">{{ $pengaturan->nama_sekolah ?? 'SMA Negeri 1 Malingping' }}</b></p>
        </div>
    </div>

</body>
</html>