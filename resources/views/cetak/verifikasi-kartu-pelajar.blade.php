<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Identitas Siswa</title>
    
    <meta name="theme-color" content="#F5F5F7">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;0,9..40,900&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'sans-serif'],
                    },
                    colors: {
                        uibg: '#F5F5F7',
                        uisurface: '#FFFFFF',
                        uiblack: '#18181B',
                        uitext: '#27272A',
                        uimuted: '#71717A',
                        uiborder: '#E4E4E7',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-uibg text-uitext font-sans min-h-screen flex flex-col items-center justify-center p-4 sm:p-8 antialiased selection:bg-zinc-900 selection:text-white">

    @php
        if (!isset($pengaturan)) {
            try { $pengaturan = \Illuminate\Support\Facades\Schema::hasTable('pengaturan') ? \App\Models\Pengaturan::first() : null; } catch (\Exception $e) { $pengaturan = null; }
        }
        
        $isAktif = in_array($siswa->status_siswa, ['Aktif', 'Mutasi Masuk']);
    @endphp

    <!-- Container Lebar Desktop (max-w-3xl) -->
    <div class="w-full max-w-3xl bg-uisurface rounded-[32px] shadow-[0_20px_60px_rgba(0,0,0,0.05)] border border-uiborder overflow-hidden flex flex-col">
        
        <!-- Top Bar Klasik -->
        <div class="px-6 sm:px-8 py-5 sm:py-6 border-b border-uiborder bg-gray-50/50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 {{ $isAktif ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                    @if($isAktif)
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    @else
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    @endif
                </div>
                <div>
                    <h1 class="text-xl font-black text-uiblack leading-tight tracking-tight">
                        Sistem Validasi Dokumen
                    </h1>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <div class="w-1.5 h-1.5 rounded-full {{ $isAktif ? 'bg-emerald-500' : 'bg-red-500' }} animate-pulse"></div>
                        <p class="text-[12px] font-bold {{ $isAktif ? 'text-emerald-600' : 'text-red-600' }} uppercase tracking-wider">
                            {{ $isAktif ? 'Identitas Terverifikasi' : 'Data Siswa Tidak Aktif' }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Logo / Nama Sekolah -->
            <div class="text-left sm:text-right w-full sm:w-auto">
                <p class="text-[11px] font-bold text-uimuted uppercase tracking-wider">Pangkalan Data</p>
                <p class="text-[14px] font-black text-uiblack">{{ $pengaturan->nama_sekolah ?? 'SMA Negeri 1 Malingping' }}</p>
            </div>
        </div>

        <!-- Area Konten (Dibagi Kolom) -->
        <div class="p-6 sm:p-10 flex flex-col gap-8">
            
            <!-- Headline Nama Siswa -->
            <div class="pb-6 border-b border-uiborder">
                <!-- NAMA SISWA DENGAN PROPER CASE & FONT 22pt -->
                <h2 class="text-[22px] sm:text-[22pt] font-black text-uiblack tracking-tight leading-tight break-words">
                    {{ ucwords(strtolower($siswa->nama_lengkap)) }}
                </h2>
                
                <div class="mt-4 flex flex-wrap items-center gap-2 sm:gap-3">
                    <span class="inline-flex items-center px-3 sm:px-4 py-1.5 rounded-full text-[12px] sm:text-[13px] font-bold uppercase tracking-wide bg-gray-100 text-gray-700">
                        NIS: {{ $siswa->nis ?? '-' }}
                    </span>
                    <span class="inline-flex items-center px-3 sm:px-4 py-1.5 rounded-full text-[12px] sm:text-[13px] font-bold uppercase tracking-wide {{ $isAktif ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20' : 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20' }}">
                        Status: {{ $siswa->status_siswa ?? 'Tidak Aktif' }}
                    </span>
                </div>
            </div>

            <!-- Grid Detail Data (2 Kolom di Layar Lebar) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                
                <div class="flex flex-col">
                    <span class="text-[12px] font-bold text-uimuted uppercase tracking-wider mb-1">Nomor Induk Siswa Nasional (NISN)</span>
                    <span class="text-[15px] sm:text-[16px] font-black text-uiblack leading-snug">{{ $siswa->nisn ?? '-' }}</span>
                </div>
                
                <div class="flex flex-col">
                    <span class="text-[12px] font-bold text-uimuted uppercase tracking-wider mb-1">Kelas Saat Ini</span>
                    <span class="text-[15px] sm:text-[16px] font-black text-uiblack leading-snug">{{ $siswa->kelas->nama_kelas ?? '-' }}</span>
                </div>

                <div class="flex flex-col">
                    <span class="text-[12px] font-bold text-uimuted uppercase tracking-wider mb-1">Jenis Kelamin</span>
                    <span class="text-[15px] sm:text-[16px] font-black text-uiblack leading-snug">{{ $siswa->jenis_kelamin }}</span>
                </div>

                <div class="flex flex-col">
                    <span class="text-[12px] font-bold text-uimuted uppercase tracking-wider mb-1">Tempat, Tanggal Lahir</span>
                    <span class="text-[15px] sm:text-[16px] font-black text-uiblack leading-snug block">
                        {{ $siswa->tempat_lahir ?? '-' }}, 
                        <span class="font-semibold text-gray-600">{{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->isoFormat('D MMMM Y') : '-' }}</span>
                    </span>
                </div>

            </div>

        </div>

        <!-- Area Bawah / Tombol -->
        <div class="px-6 pb-6 sm:px-10 sm:pb-8 pt-0 flex justify-center sm:justify-start">
            <a href="{{ url('/') }}" class="w-full sm:w-auto bg-uiblack hover:bg-black transition-colors text-white rounded-[100px] py-3.5 px-8 font-bold text-[14px] uppercase tracking-wide shadow-[0_8px_25px_rgba(24,24,27,0.2)] text-center">
                Kembali ke Beranda
            </a>
        </div>

    </div>

</body>
</html>