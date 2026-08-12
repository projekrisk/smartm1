@php
    $pengaturan = null;
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) {
            $pengaturan = \App\Models\Pengaturan::first();
        }
    } catch (\Exception $e) {}
    
    $namaSekolah = $pengaturan->nama_sekolah ?? 'SMAN 1 Malingping';
    $appTitle = "Smart-M1 - " . $namaSekolah . " | Sistem Administrasi & Akademik Siswa";
    $metaDescription = "Portal Sistem Informasi Manajemen Terpadu untuk " . $namaSekolah . ". Akses absensi, e-rapor, dan layanan administrasi secara real-time.";
    
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
    
    @if($pengaturan && $pengaturan->logo_sekolah)
        <link rel="icon" href="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" type="image/x-icon"/>
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            800: '#1e293b',
                            900: '#0f172a',
                        },
                        accent: {
                            500: '#3b82f6', // Sharp professional blue for minimal highlights
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { 
            background-color: #ffffff; 
            color: #0f172a; 
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Subtle grid background for structural feel */
        .bg-grid {
            background-image: 
                linear-gradient(to right, rgba(15, 23, 42, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(15, 23, 42, 0.04) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        /* Modal Transitions */
        .modal-overlay { opacity: 0; visibility: hidden; transition: all 0.2s ease-out; }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-content { transform: scale(0.98) translateY(10px); opacity: 0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .modal-overlay.active .modal-content { transform: scale(1) translateY(0); opacity: 1; }
    </style>
</head>
<body class="antialiased flex flex-col relative w-full selection:bg-brand-900 selection:text-white bg-grid">

    <!-- Top Navigation -->
    <nav class="w-full px-6 py-5 md:px-12 flex justify-between items-center shrink-0 z-40 border-b border-brand-100 bg-white/90 backdrop-blur-sm">
        <div class="flex items-center gap-3">
            @if($pengaturan && $pengaturan->logo_sekolah)
                <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo" class="w-10 h-10 md:w-11 md:h-11 object-contain">
            @else
                <div class="w-10 h-10 bg-brand-900 rounded-lg flex items-center justify-center text-white font-bold text-lg">M1</div>
            @endif
            <div class="flex flex-col">
                <span class="font-extrabold text-lg md:text-xl text-brand-900 tracking-tight leading-none uppercase">SMART-M1</span>
                <span class="text-[11px] text-slate-500 font-medium tracking-wide mt-1">{{ $namaSekolah }}</span>
            </div>
        </div>

        <button onclick="openFeaturesModal()" class="hidden md:flex items-center gap-2 px-4 py-2 rounded-md border border-brand-200 text-sm font-semibold text-brand-800 hover:bg-brand-50 transition-colors">
            Informasi Portal
        </button>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-1 w-full max-w-7xl mx-auto px-6 md:px-12 py-10 md:py-16 flex flex-col justify-center relative z-10">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
            
            <div class="flex flex-col pr-0 lg:pr-12">
                
                <h1 class="text-4xl md:text-5xl lg:text-[4rem] font-extrabold tracking-tight text-brand-900 leading-[1.05] mb-6">
                    Manajemen <br />
                    Akademik <br />
                    Terpadu.
                </h1>

                <p class="text-base md:text-lg text-slate-600 max-w-lg mb-10 leading-relaxed">
                    Sistem informasi resmi untuk pengelolaan administrasi, rekam jejak akademik, dan layanan operasional secara terpusat.
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button onclick="openLoginModal()" class="inline-flex items-center justify-center px-8 py-3.5 bg-brand-900 text-white font-bold text-sm tracking-wide rounded-lg hover:bg-brand-800 transition-colors shadow-sm">
                        Otorisasi Masuk
                    </button>
                    <button onclick="openFeaturesModal()" class="md:hidden inline-flex items-center justify-center px-8 py-3.5 bg-white text-brand-900 border border-brand-200 font-bold text-sm tracking-wide rounded-lg hover:bg-brand-50 transition-colors shadow-sm">
                        Informasi Portal
                    </button>
                </div>
            </div>

            <div class="relative w-full h-full min-h-[400px] flex items-center justify-center lg:justify-end">
                <!-- A clean, structural card layout representing the system -->
                <div class="w-full max-w-md bg-white border border-brand-200 rounded-2xl shadow-xl shadow-brand-900/5 p-8 relative overflow-hidden">
                    
                    <div class="absolute top-0 left-0 w-full h-1 bg-brand-900"></div>

                    <h3 class="text-sm font-bold uppercase tracking-widest text-slate-400 mb-8 border-b border-brand-100 pb-4">Modul Layanan</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded bg-brand-50 flex items-center justify-center shrink-0 border border-brand-100 mt-0.5">
                                <svg class="w-4 h-4 text-brand-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-brand-900 mb-1">E-Rapor & Evaluasi</h4>
                                <p class="text-sm text-slate-500 leading-relaxed">Sistem perekaman nilai dan rekapitulasi capaian akademik berkelanjutan.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded bg-brand-50 flex items-center justify-center shrink-0 border border-brand-100 mt-0.5">
                                <svg class="w-4 h-4 text-brand-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-brand-900 mb-1">Manajemen Mutasi</h4>
                                <p class="text-sm text-slate-500 leading-relaxed">Pengelolaan data induk dan riwayat pergerakan peserta didik.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded bg-brand-50 flex items-center justify-center shrink-0 border border-brand-100 mt-0.5">
                                <svg class="w-4 h-4 text-brand-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-brand-900 mb-1">Verifikasi Digital</h4>
                                <p class="text-sm text-slate-500 leading-relaxed">Validasi keabsahan dokumen persuratan melalui pemindaian QR Code.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="w-full text-center py-6 text-xs text-slate-400 font-medium shrink-0 border-t border-brand-100 bg-white">
        &copy; {{ date('Y') }} {{ $namaSekolah }}. Hak Cipta Dilindungi.
    </footer>

    <!-- Login Modal -->
    <div id="loginModal" class="modal-overlay fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-brand-900/40 backdrop-blur-sm" onclick="closeLoginModal()"></div>
        
        <div class="modal-content relative w-full max-w-sm bg-white rounded-2xl shadow-2xl border border-brand-100 overflow-hidden">
            <div class="p-6 border-b border-brand-100 bg-brand-50 flex justify-between items-center">
                <h2 class="text-lg font-bold text-brand-900">Otorisasi Akses</h2>
                <button onclick="closeLoginModal()" class="text-slate-400 hover:text-brand-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="p-6 space-y-3">
                <a href="{{ url('/admin/login') }}" class="flex items-center justify-between p-4 bg-white border border-brand-200 hover:border-brand-900 rounded-xl transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-brand-50 border border-brand-100 text-brand-900 flex items-center justify-center group-hover:bg-brand-900 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </div>
                        <div class="text-left">
                            <h3 class="text-sm font-bold text-brand-900">Staf & Pengajar</h3>
                            <p class="text-xs text-slate-500">Panel Manajemen</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-brand-900 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
                
                <a href="{{ url('/siswa/login') }}" class="flex items-center justify-between p-4 bg-white border border-brand-200 hover:border-brand-900 rounded-xl transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-brand-50 border border-brand-100 text-brand-900 flex items-center justify-center group-hover:bg-brand-900 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                        </div>
                        <div class="text-left">
                            <h3 class="text-sm font-bold text-brand-900">Peserta Didik</h3>
                            <p class="text-xs text-slate-500">Akses Akademik</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-brand-900 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Info Modal -->
    <div id="featuresModal" class="modal-overlay fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-brand-900/40 backdrop-blur-sm" onclick="closeFeaturesModal()"></div>
        <div class="modal-content relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-brand-100 overflow-hidden">
            <div class="p-6 border-b border-brand-100 bg-brand-50 flex justify-between items-center">
                <h2 class="text-lg font-bold text-brand-900">Tentang Sistem</h2>
                <button onclick="closeFeaturesModal()" class="text-slate-400 hover:text-brand-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6">
                <p class="text-sm text-slate-600 leading-relaxed mb-4">
                    <b>Smart-M1</b> dirancang khusus untuk memfasilitasi kebutuhan operasional {{ $namaSekolah }}. Platform ini memastikan sinkronisasi data yang ketat antara tenaga pendidik dan tata usaha.
                </p>
                <div class="bg-brand-50 border border-brand-100 rounded-lg p-4 text-xs text-slate-500">
                    Sistem memisahkan kredensial masuk secara tegas. Pastikan Anda memilih portal otorisasi yang tepat sesuai peran. Hubungi administrator IT sekolah jika mengalami kendala akses.
                </div>
            </div>
        </div>
    </div>

    <script>
        const loginModal = document.getElementById('loginModal');
        const featuresModal = document.getElementById('featuresModal');
        
        function openLoginModal() { loginModal.classList.add('active'); }
        function closeLoginModal() { loginModal.classList.remove('active'); }
        function openFeaturesModal() { featuresModal.classList.add('active'); }
        function closeFeaturesModal() { featuresModal.classList.remove('active'); }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (loginModal.classList.contains('active')) closeLoginModal();
                if (featuresModal.classList.contains('active')) closeFeaturesModal();
            }
        });
    </script>
</body>
</html>