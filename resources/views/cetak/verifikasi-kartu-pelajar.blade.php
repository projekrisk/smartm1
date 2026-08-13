<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Identitas Siswa</title>
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

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden pt-8">
        
        <!-- Header Identitas Valid -->
        <div class="bg-green-600 px-6 pt-16 pb-12 text-center text-white relative mt-12">
            
            <!-- Foto Siswa Melayang -->
            <div class="absolute -top-16 left-1/2 transform -translate-x-1/2">
                <div class="w-32 h-32 bg-white rounded-2xl p-1.5 shadow-2xl rotate-3 transition-transform hover:rotate-0">
                    <div class="w-full h-full rounded-xl overflow-hidden bg-gray-200 border border-gray-100 flex items-center justify-center">
                        @if($siswa->foto)
                            <img src="{{ url('/uploads/' . $siswa->foto) }}" alt="Foto Siswa" class="w-full h-full object-cover">
                        @else
                            <span class="text-xs font-bold text-gray-400">NO FOTO</span>
                        @endif
                    </div>
                </div>
                <!-- Badge Centang -->
                <div class="absolute -bottom-3 -right-3 w-10 h-10 bg-green-500 rounded-full border-4 border-white shadow-md flex items-center justify-center">
                    <i class="fas fa-check text-white text-lg"></i>
                </div>
            </div>

            <h1 class="text-2xl font-bold mb-1 tracking-wide mt-2">IDENTITAS VALID</h1>
            <p class="text-green-100 text-sm font-medium">Kartu Pelajar Terverifikasi</p>
        </div>

        <div class="p-6">
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-gray-900 uppercase">{{ $siswa->nama_lengkap }}</h2>
                <p class="text-gray-500 font-medium mt-1">NIS: {{ $siswa->nis ?? '-' }} <span class="mx-1">•</span> NISN: {{ $siswa->nisn ?? '-' }}</p>
            </div>

            <div class="space-y-4 text-sm text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-100">
                
                <div class="flex justify-between items-center pb-3 border-b border-gray-200/60">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Status Siswa</p>
                    @if(in_array($siswa->status_siswa, ['Aktif', 'Mutasi Masuk']))
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-green-100 text-green-700 uppercase">
                            {{ $siswa->status_siswa }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-red-100 text-red-700 uppercase">
                            {{ $siswa->status_siswa ?? 'Tidak Aktif' }}
                        </span>
                    @endif
                </div>

                <div class="flex justify-between items-center pb-3 border-b border-gray-200/60">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Kelas Saat Ini</p>
                    <p class="font-bold text-gray-900">{{ $siswa->kelas->nama_kelas ?? 'Belum ada kelas' }}</p>
                </div>

                <div class="flex justify-between items-center pb-3 border-b border-gray-200/60">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Jenis Kelamin</p>
                    <p class="font-bold text-gray-900">{{ $siswa->jenis_kelamin }}</p>
                </div>

                <div class="flex justify-between items-center">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Tempat, Tgl Lahir</p>
                    <p class="font-bold text-gray-900 text-right">
                        {{ $siswa->tempat_lahir ?? '-' }}<br>
                        <span class="font-medium text-gray-500">{{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->isoFormat('D MMMM Y') : '-' }}</span>
                    </p>
                </div>

            </div>

            <div class="mt-8 mb-2">
                <a href="{{ url('/') }}" class="w-full flex items-center justify-center gap-2 bg-gray-900 hover:bg-black text-white py-3 rounded-xl font-bold text-sm transition-colors shadow-lg shadow-gray-900/20">
                    Kembali ke Beranda
                </a>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-5 text-center border-t border-gray-100">
            <p class="text-xs text-gray-500">Sistem Informasi Manajemen<br><b class="text-gray-700">{{ $pengaturan->nama_sekolah ?? 'SMA Negeri 1 Malingping' }}</b></p>
        </div>

    </div>

</body>
</html>