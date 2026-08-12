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
    
    @if($pengaturan && $pengaturan->logo_sekolah)
        <link rel="icon" href="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" type="image/x-icon"/>
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;0,800;1,400&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"DM Sans"', 'sans-serif'],
                    },
                    colors: {
                        // Custom refined palette
                        base: {
                            50: '#F9FAFB', // Off-white background
                            900: '#111827', // Deep charcoal/black
                        },
                        accent: {
                            500: '#D97706', // Muted Orange/Gold for sharp contrast
                            600: '#B45309',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'DM Sans', sans-serif; 
            background-color: #F9FAFB; 
            color: #111827; 
            margin: 0;
            overflow-x: hidden;
        }

        /* Subtle, structural background instead of blobs */
        .bg-structural {
            background-image: 
                linear-gradient(to right, rgba(17, 24, 39, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(17, 24, 39, 0.03) 1px, transparent 1px);
            background-size: 64px 64px;
        }

        .modal-overlay {
            opacity: 0; visibility: hidden; transition: all 0.3s ease-in-out;
            backdrop-filter: blur(4px);
        }
        .modal-overlay.active {
            opacity: 1; visibility: visible;
        }
        .modal-content {
            transform: translateY(20px); opacity: 0; transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .modal-overlay.active .modal-content {
            transform: translateY(0); opacity: 1;
        }

        .hover-underline-animation {
            display: inline-block;
            position: relative;
        }
        .hover-underline-animation::after {
            content: '';
            position: absolute;
            width: 100%;
            transform: scaleX(0);
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: currentColor;
            transform-origin: bottom right;
            transition: transform 0.25s ease-out;
        }
        .hover-underline-animation:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col relative bg-base-50 bg-structural selection:bg-base-900 selection:text-white">

    <!-- Minimalist Navbar -->
    <nav class="w-full px-6 py-8 md:px-12 lg:px-16 flex justify-between items-start z-40">
        <div class="flex items-center gap-4">
            @if($pengaturan && $pengaturan->logo_sekolah)
                <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo" class="w-12 h-12 md:w-14 md:h-14 object-contain">
            @else
                <div class="w-12 h-12 md:w-14 md:h-14 bg-base-900 flex items-center justify-center text-white font-bold text-xl">M1</div>
            @endif
            <div class="flex flex-col">
                <span class="font-extrabold text-xl md:text-2xl tracking-tight leading-none text-base-900">Smart-M1</span>
                <span class="text-xs md:text-sm font-medium text-gray-500 mt-1 uppercase tracking-widest">{{ $namaSekolah }}</span>
            </div>
        </div>

        <button onclick="openFeaturesModal()" class="hidden md:block text-sm font-bold uppercase tracking-wider text-base-900 hover-underline-animation">
            Informasi Sistem
        </button>
    </nav>

    <!-- Main Content: Asymmetrical Layout -->
    <main class="flex-1 w-full max-w-[1600px] mx-auto px-6 md:px-12 lg:px-16 flex flex-col justify-center pb-20 z-10">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center mt-8 lg:mt-0">
            
            <!-- Left Column: Typography & CTAs -->
            <div class="lg:col-span-7 flex flex-col justify-center pr-0 lg:pr-8">
                
                <h1 class="text-5xl md:text-6xl lg:text-7xl xl:text-[5rem] font-extrabold text-base-900 tracking-tight leading-[1.05] mb-8">
                    Manajemen <br />
                    Akademik <br />
                    Terpadu.
                </h1>

                <p class="text-lg md:text-xl text-gray-600 max-w-2xl leading-relaxed mb-10">
                    Platform inti untuk tata kelola administrasi dan pemantauan perkembangan peserta didik secara komprehensif di lingkungan institusi.
                </p>

                <!-- Solid, Structural Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                    <a href="{{ url('/siswa/login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-base-900 text-white font-bold text-sm tracking-wider uppercase hover:bg-gray-800 transition-colors group">
                        Portal Siswa
                        <svg class="w-5 h-5 ml-3 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    
                    <a href="{{ url('/admin/login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-base-900 text-base-900 font-bold text-sm tracking-wider uppercase hover:bg-base-900 hover:text-white transition-colors">
                        Admin & Guru
                    </a>
                </div>
            </div>

            <!-- Right Column: Visual / Feature Highlights (Instead of a generic image) -->
            <div class="lg:col-span-5 w-full mt-12 lg:mt-0">
                <!-- A structural, card-based visual element -->
                <div class="bg-white border-2 border-base-900 p-8 md:p-10 relative">
                    <!-- Decorative accents -->
                    <div class="absolute top-0 right-0 w-16 h-16 border-l-2 border-b-2 border-base-900 bg-base-50 transform translate-x-2 -translate-y-2"></div>
                    <div class="absolute bottom-0 left-0 w-16 h-16 border-r-2 border-t-2 border-base-900 bg-base-50 transform -translate-x-2 translate-y-2"></div>
                    
                    <div class="relative z-10">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-accent-600 mb-6">Modul Inti</h3>
                        
                        <div class="space-y-8">
                            <div class="flex gap-4">
                                <div class="shrink-0 mt-1">
                                    <svg class="w-6 h-6 text-base-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-base-900 mb-1">E-Rapor & Evaluasi</h4>
                                    <p class="text-sm text-gray-600 leading-relaxed">Perekaman nilai secara berkala dengan sistem rekapitulasi otomatis.</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="shrink-0 mt-1">
                                    <svg class="w-6 h-6 text-base-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-base-900 mb-1">Pusat Data Mutasi</h4>
                                    <p class="text-sm text-gray-600 leading-relaxed">Pengelolaan riwayat status siswa, pendaftaran, dan mutasi keluar/masuk.</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="shrink-0 mt-1">
                                    <svg class="w-6 h-6 text-base-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-base-900 mb-1">Verifikasi Dokumen</h4>
                                    <p class="text-sm text-gray-600 leading-relaxed">Sistem penjaminan keaslian persuratan (dispensasi/panggilan) berbasis QRCode.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Minimalist Footer -->
    <footer class="w-full px-6 py-6 md:px-12 lg:px-16 flex flex-col md:flex-row justify-between items-center border-t-2 border-gray-200 mt-auto bg-base-50 z-10 gap-4 text-xs md:text-sm font-bold text-gray-500 uppercase tracking-wider">
        <div>&copy; {{ date('Y') }} {{ $namaSekolah }}.</div>
        <button onclick="openFeaturesModal()" class="md:hidden hover:text-base-900 transition-colors">Informasi Sistem</button>
    </footer>

    <!-- Structural Modal -->
    <div id="featuresModal" class="modal-overlay fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-6 bg-base-900/40">
        <!-- Close area -->
        <div class="absolute inset-0 cursor-pointer" onclick="closeFeaturesModal()"></div>
        
        <div class="modal-content relative w-full max-w-2xl bg-white border-2 border-base-900 p-0 flex flex-col shadow-2xl">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-6 md:p-8 border-b-2 border-base-900 bg-base-50">
                <h2 class="text-xl md:text-2xl font-extrabold text-base-900 tracking-tight">Informasi Sistem</h2>
                <button onclick="closeFeaturesModal()" class="text-base-900 hover:text-accent-600 transition-colors p-1">
                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 md:p-8 text-gray-700 leading-relaxed space-y-6">
                <p class="text-base md:text-lg">
                    <b>Smart-M1</b> dibangun secara spesifik untuk memfasilitasi kebutuhan administrasi dan operasional <b>{{ $namaSekolah }}</b>. Sistem memisahkan wewenang antara pengelola (Admin/Guru) dan pengguna akhir (Siswa/Wali Murid).
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-200">
                    <div>
                        <h4 class="font-bold text-base-900 mb-2 uppercase tracking-wider text-sm">Akses Administrator</h4>
                        <p class="text-sm">Diperuntukkan bagi staf Tata Usaha dan Tenaga Pendidik untuk manajemen data, input nilai, presensi, dan penerbitan dokumen resmi.</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-base-900 mb-2 uppercase tracking-wider text-sm">Akses Siswa</h4>
                        <p class="text-sm">Portal baca-saja (*read-only*) bagi peserta didik untuk memantau kemajuan belajar, riwayat mutasi, dan melihat E-Rapor.</p>
                    </div>
                </div>

                <div class="bg-gray-100 p-4 border-l-4 border-accent-600 text-sm font-medium">
                    Untuk bantuan teknis atau kendala login, silakan hubungi bagian Tata Usaha sekolah pada jam kerja.
                </div>
            </div>
        </div>
    </div>

    <script>
        const featuresModal = document.getElementById('featuresModal');
        
        function openFeaturesModal() { 
            featuresModal.classList.add('active'); 
            document.body.style.overflow = 'hidden'; // Prevent scrolling behind modal
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