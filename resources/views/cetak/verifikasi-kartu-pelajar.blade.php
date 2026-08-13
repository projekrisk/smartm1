<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Validasi Identitas Siswa</title>
    
    <meta name="theme-color" content="#F5F5F7">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;0,9..40,900&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

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

    <style>
        body { -webkit-font-smoothing: antialiased; }
        .touch-scale { transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
        .touch-scale:active { transform: scale(0.96); }
        /* Sembunyikan scrollbar untuk kesan aplikasi asli */
        ::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-uibg text-uitext font-sans m-0 p-0 overflow-x-hidden selection:bg-zinc-900 selection:text-white">

    @php
        if (!isset($pengaturan)) {
            try { $pengaturan = \Illuminate\Support\Facades\Schema::hasTable('pengaturan') ? \App\Models\Pengaturan::first() : null; } catch (\Exception $e) { $pengaturan = null; }
        }
        
        $isAktif = in_array($siswa->status_siswa, ['Aktif', 'Mutasi Masuk']);
    @endphp

    <!-- Mobile Workspace Container (Tengah di Desktop, Full di Mobile) -->
    <div class="w-full max-w-[414px] mx-auto min-h-screen bg-uibg sm:border-x border-uiborder relative flex flex-col shadow-[0_0_50px_rgba(0,0,0,0.03)] pb-8">
        
        <!-- Header Topbar -->
        <div class="pt-10 pb-4 px-6 flex items-center gap-4 bg-uibg border-b border-black/5 shrink-0 sticky top-0 z-20">
            <div class="w-11 h-11 rounded-full flex items-center justify-center shrink-0 {{ $isAktif ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }} shadow-sm">
                @if($isAktif)
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                @else
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                @endif
            </div>
            
            <div>
                <h1 class="text-[20px] font-black text-uiblack leading-tight tracking-tight">
                    {{ $isAktif ? 'Identitas Valid' : 'Tidak Aktif' }}
                </h1>
                <div class="flex items-center gap-1.5 mt-1">
                    <div class="w-1.5 h-1.5 rounded-full {{ $isAktif ? 'bg-emerald-500' : 'bg-red-500' }} animate-pulse"></div>
                    <p class="text-[11px] font-bold text-uimuted uppercase tracking-wider">Sistem Terverifikasi</p>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto pt-6 px-5 flex flex-col gap-5">
            
            <!-- Card 1: Profil Utama -->
            <div class="bg-uisurface rounded-[24px] p-6 text-center border border-uiborder shadow-[0_10px_40px_rgba(0,0,0,0.03)] relative">
                
                <div class="w-24 h-24 mx-auto rounded-[20px] bg-uibg border-4 border-uisurface shadow-md overflow-hidden mb-4 relative flex items-center justify-center">
                    @if($siswa->foto)
                        <img src="{{ url('/uploads/' . $siswa->foto) }}" alt="Foto Siswa" class="w-full h-full object-cover">
                    @else
                        <span class="text-[10px] font-black text-uimuted">NO FOTO</span>
                    @endif
                </div>

                <h2 class="text-[19px] font-black text-uiblack leading-tight">{{ $siswa->nama_lengkap }}</h2>
                <p class="text-[13px] font-bold text-uimuted mt-1 tracking-wide">NIS: {{ $siswa->nis ?? '-' }}</p>

                <div class="mt-5 inline-flex items-center gap-2 px-4 py-2 rounded-full text-[12px] font-bold uppercase tracking-wide {{ $isAktif ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20' : 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20' }}">
                    {{ $siswa->status_siswa ?? 'Tidak Aktif' }}
                </div>
            </div>

            <!-- Card 2: Detail Akademik -->
            <div class="bg-uisurface rounded-[24px] px-5 border border-uiborder shadow-[0_10px_40px_rgba(0,0,0,0.03)]">
                
                <div class="py-4 border-b border-uiborder flex items-center justify-between">
                    <span class="text-[12px] font-bold text-uimuted uppercase tracking-wider">NISN</span>
                    <span class="text-[14px] font-black text-uiblack">{{ $siswa->nisn ?? '-' }}</span>
                </div>
                
                <div class="py-4 border-b border-uiborder flex items-center justify-between">
                    <span class="text-[12px] font-bold text-uimuted uppercase tracking-wider">Kelas</span>
                    <span class="text-[14px] font-black text-uiblack">{{ $siswa->kelas->nama_kelas ?? '-' }}</span>
                </div>

                <div class="py-4 border-b border-uiborder flex items-center justify-between">
                    <span class="text-[12px] font-bold text-uimuted uppercase tracking-wider">Gender</span>
                    <span class="text-[14px] font-black text-uiblack">{{ $siswa->jenis_kelamin }}</span>
                </div>

                <div class="py-4 flex items-center justify-between">
                    <span class="text-[12px] font-bold text-uimuted uppercase tracking-wider">Kelahiran</span>
                    <div class="text-right leading-tight">
                        <span class="text-[14px] font-black text-uiblack block">{{ $siswa->tempat_lahir ?? '-' }}</span>
                        <span class="text-[12px] font-semibold text-uimuted">{{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->isoFormat('D MMM Y') : '-' }}</span>
                    </div>
                </div>

            </div>

            <!-- Action Button -->
            <div class="mt-4 mb-6">
                <a href="{{ url('/') }}" class="touch-scale w-full bg-uiblack text-white rounded-[100px] py-4 px-6 font-black text-[13px] uppercase tracking-wide flex items-center justify-center shadow-[0_8px_25px_rgba(24,24,27,0.25)] relative overflow-hidden group">
                    Kembali ke Beranda
                </a>
            </div>

            <!-- Footer Text -->
            <div class="text-center pb-6">
                <p class="text-[11px] font-semibold text-uimuted">
                    Sistem Informasi Manajemen<br>
                    <span class="text-uiblack font-bold">{{ $pengaturan->nama_sekolah ?? 'SMA Negeri 1 Malingping' }}</span>
                </p>
            </div>

        </div>
    </div>

</body>
</html>