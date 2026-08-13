@php
    $pengaturan = null;
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) {
            $pengaturan = \App\Models\Pengaturan::first();
        }
    } catch (\Exception $e) {}
    
    $namaSekolah = $pengaturan->nama_sekolah ?? 'SMAN 1 Malingping';
    $appTitle = "Smart-M1 - " . $namaSekolah . " | Sistem Informasi Manajemen";
    $metaDescription = "Smart-M1 " . $namaSekolah . " adalah platform terpadu digitalisasi sistem administrasi dan akademik siswa. Akses nilai, e-rapor, absensi harian, dan portofolio prestasi sekolah secara real-time.";
    
    $ogImageUrl = url('images/og-image.jpg');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>{{ $appTitle }}</title>
    <meta name="title" content="{{ $appTitle }}">
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="Smart-M1, {{ $namaSekolah }}, sistem administrasi sekolah, akademik siswa, e-rapor, absensi siswa, portal siswa">
    <meta name="author" content="{{ $namaSekolah }}">
    <meta name="theme-color" content="#F5F5F7">
    
    @if($pengaturan && $pengaturan->logo_sekolah)
        <link rel="icon" href="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" type="image/x-icon"/>
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;0,9..40,900&display=swap" rel="stylesheet">
    
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
        body { 
            -webkit-font-smoothing: antialiased; 
            overflow-x: hidden;
        }

        .touch-scale { transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
        .touch-scale:active { transform: scale(0.96); }

        .modal-overlay {
            opacity: 0; visibility: hidden; transition: all 0.3s ease-in-out;
            backdrop-filter: blur(4px);
        }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-content {
            transform: translateY(20px) scale(0.98); opacity: 0; transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .modal-overlay.active .modal-content {
            transform: translateY(0) scale(1); opacity: 1;
        }
    </style>
</head>
<body class="bg-uibg text-uitext font-sans min-h-screen flex flex-col selection:bg-zinc-900 selection:text-white">

    <!-- Navbar -->
    <nav class="w-full px-6 py-6 md:px-12 lg:px-16 flex justify-between items-center z-40 bg-uibg/80 backdrop-blur-md sticky top-0 border-b border-uiborder/50">
        <div class="flex items-center gap-4">
            @if($pengaturan && $pengaturan->logo_sekolah)
                <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo" class="w-11 h-11 md:w-12 md:h-12 object-contain drop-shadow-sm">
            @else
                <div class="w-11 h-11 md:w-12 md:h-12 bg-uiblack rounded-full flex items-center justify-center text-white font-black text-xl shadow-md">M1</div>
            @endif
            <div class="flex flex-col">
                <span class="font-black text-[20px] tracking-tight leading-tight text-uiblack">Smart-M1</span>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-[11px] font-bold text-uimuted uppercase tracking-wider">{{ $namaSekolah }}</span>
                </div>
            </div>
        </div>

        <button onclick="openFeaturesModal()" class="hidden md:flex items-center gap-2 px-5 py-2.5 rounded-full bg-white border border-uiborder text-[12px] font-bold uppercase tracking-wider text-uiblack hover:bg-gray-50 transition-colors shadow-sm">
            <svg class="w-4 h-4 text-uimuted" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Info Sistem
        </button>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 w-full max-w-[1440px] mx-auto px-6 md:px-12 lg:px-16 flex flex-col justify-center py-12 lg:py-0 z-10">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <!-- Hero Text (Kiri) -->
            <div class="lg:col-span-7 flex flex-col justify-center">

                <h1 class="text-[40px] md:text-[56px] lg:text-[72px] font-black text-uiblack tracking-tight leading-[1.05] mb-6">
                    Portal Smart-M1 <br class="hidden md:block" />
                    {{ $namaSekolah }}
                </h1>

                <p class="text-[16px] md:text-[18px] text-uimuted max-w-2xl leading-relaxed mb-10 font-medium">
                    Platform digital yang memfasilitasi manajemen administrasi akademik dan non-akademik peserta didik secara <span class="text-uiblack font-bold">aman, efisien, dan real-time.</span>
                </p>

                <!-- Tombol Akses -->
                <div class="flex flex-col sm:flex-row gap-4 mb-10">
                    <a href="{{ url('/siswa/login') }}" class="touch-scale flex items-center justify-center px-8 py-4 bg-uiblack text-white rounded-[100px] font-bold text-[14px] uppercase tracking-wide transition-all hover:bg-black shadow-[0_8px_25px_rgba(24,24,27,0.25)]">
                        Portal Siswa
                        <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    
                    <a href="{{ url('/admin/login') }}" class="touch-scale flex items-center justify-center px-8 py-4 bg-white text-uiblack border border-uiborder rounded-[100px] font-bold text-[14px] uppercase tracking-wide transition-colors hover:bg-gray-50 shadow-sm">
                        Admin & Guru
                    </a>
                </div>

                <!-- Platform Support -->
                <div class="flex flex-col gap-3">
                    <span class="text-[11px] font-bold tracking-widest text-uimuted uppercase">Aksesibilitas Perangkat:</span>
                    <div class="flex flex-wrap items-center gap-2">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-uiborder rounded-md text-[11px] font-bold uppercase tracking-wider text-uimuted">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> Desktop
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-uiborder rounded-md text-[11px] font-bold uppercase tracking-wider text-uimuted">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> Smartphone
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-uiborder rounded-md text-[11px] font-bold uppercase tracking-wider text-uimuted">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg> Web App
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Card (Kanan) -->
            <div class="lg:col-span-5 w-full mt-8 lg:mt-0">
                <div class="bg-uisurface rounded-[32px] p-8 md:p-10 border border-uiborder shadow-[0_20px_60px_rgba(0,0,0,0.05)] relative overflow-hidden">
                    
                    <h3 class="text-[12px] font-bold uppercase tracking-widest text-emerald-600 mb-8 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        Modul Inti Sistem
                    </h3>
                    
                    <div class="space-y-8">
                        
                        <div class="flex gap-4 items-start">
                            <div class="shrink-0 w-12 h-12 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center text-uiblack">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <div>
                                <h4 class="text-[16px] font-black text-uiblack mb-1 leading-snug">E-Rapor & Akademik</h4>
                                <p class="text-[13px] text-uimuted leading-relaxed font-medium">Perekaman nilai terintegrasi, rekapitulasi absensi harian, dan portofolio prestasi peserta didik.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 items-start">
                            <div class="shrink-0 w-12 h-12 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center text-uiblack">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </div>
                            <div>
                                <h4 class="text-[16px] font-black text-uiblack mb-1 leading-snug">Manajemen Mutasi</h4>
                                <p class="text-[13px] text-uimuted leading-relaxed font-medium">Pengelolaan terpusat riwayat status siswa, pendaftaran, transisi kelas, hingga data mutasi.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 items-start">
                            <div class="shrink-0 w-12 h-12 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center text-uiblack">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            </div>
                            <div>
                                <h4 class="text-[16px] font-black text-uiblack mb-1 leading-snug">Verifikasi QR Code</h4>
                                <p class="text-[13px] text-uimuted leading-relaxed font-medium">Sistem penjaminan keaslian persuratan dan Kartu Pelajar digital melalui pemindaian kode pintar.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full px-6 py-8 md:px-12 lg:px-16 flex flex-col sm:flex-row justify-between items-center bg-uibg z-10 gap-4 text-[12px] font-bold text-uimuted uppercase tracking-wider">
        <div>&copy; {{ date('Y') }} {{ $namaSekolah }}. All Rights Reserved.</div>
        <button onclick="openFeaturesModal()" class="md:hidden text-uiblack hover:text-black transition-colors underline decoration-2 underline-offset-4">Informasi Sistem</button>
    </footer>

    <!-- Modal Informasi Sistem -->
    <div id="featuresModal" class="modal-overlay fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-6 bg-uiblack/40">
        <div class="absolute inset-0 cursor-pointer" onclick="closeFeaturesModal()"></div>
        
        <div class="modal-content relative w-full max-w-2xl bg-uisurface rounded-[32px] p-0 flex flex-col shadow-2xl border border-uiborder overflow-hidden">
            
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-6 md:p-8 border-b border-uiborder bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-uiblack text-white flex items-center justify-center font-black text-sm">M1</div>
                    <h2 class="text-xl md:text-2xl font-black text-uiblack tracking-tight">Informasi Sistem</h2>
                </div>
                <button onclick="closeFeaturesModal()" class="w-10 h-10 rounded-full bg-white border border-uiborder flex items-center justify-center text-uiblack hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 md:p-8 text-uitext leading-relaxed space-y-8">
                <p class="text-[15px] font-medium text-uimuted leading-relaxed">
                    <b class="text-uiblack">Smart-M1</b> dirancang khusus untuk memfasilitasi kebutuhan administrasi akademik dan operasional secara terpadu di lingkungan <b class="text-uiblack">{{ $namaSekolah }}</b>.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-uiborder">
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700 mb-3">
                            Administrator & Guru
                        </span>
                        <p class="text-[14px] font-medium text-uimuted">Pengelolaan basis data peserta didik, manajemen evaluasi akademik, rekapitulasi presensi harian kelas, serta otentikasi dokumen resmi sekolah.</p>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 mb-3">
                            Akses Peserta Didik
                        </span>
                        <p class="text-[14px] font-medium text-uimuted">Portal mandiri untuk memantau perkembangan belajar, histori catatan akademik, validasi Kartu Pelajar digital, dan pencetakan dokumen layanan administrasi.</p>
                    </div>
                </div>

                <div class="bg-gray-50/80 p-5 rounded-2xl border border-gray-100 text-[13px] font-bold text-uimuted flex items-start gap-3 mt-4">
                    <svg class="w-5 h-5 text-gray-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p>Sistem dilindungi dengan enkripsi berlapis. Untuk kendala lupa kata sandi atau permasalahan akses sistem, silakan menghubungi bagian Tata Usaha sekolah.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const featuresModal = document.getElementById('featuresModal');
        
        function openFeaturesModal() { 
            featuresModal.classList.add('active'); 
            document.body.style.overflow = 'hidden';
        }
        
        function closeFeaturesModal() { 
            featuresModal.classList.remove('active'); 
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && featuresModal.classList.contains('active')) {
                closeFeaturesModal();
            }
        });
    </script>
</body>
</html>