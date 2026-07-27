@php
    $pengaturan = null;
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) {
            $pengaturan = \App\Models\Pengaturan::first();
        }
    } catch (\Exception $e) {}
    
    $namaSekolah = $pengaturan->nama_sekolah ?? 'SMART-M1 SMAN 1 Malingping';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Portal Resmi | {{ $namaSekolah }}</title>
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
                        edu: {
                            50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 900: '#1e3a8a',
                        }
                    },
                    // Menambahkan animasi pelan untuk bola cahaya
                    animation: {
                        'blob': 'blob 10s infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Mengunci Layar 100% Tanpa Scroll */
        html, body { 
            margin: 0; padding: 0; height: 100%; height: 100dvh; 
            overflow: hidden; background-color: #f8fafc; color: #0f172a; 
        }

        /* Modal Transitions */
        .modal-overlay {
            opacity: 0; visibility: hidden; transition: all 0.3s ease;
        }
        .modal-overlay.active {
            opacity: 1; visibility: visible;
        }
        .modal-content {
            transform: translateY(20px) scale(0.95); opacity: 0; transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .modal-overlay.active .modal-content {
            transform: translateY(0) scale(1); opacity: 1;
        }
    </style>
</head>
<body class="antialiased flex flex-col relative w-full bg-slate-50 selection:bg-edu-500 selection:text-white">

    <!-- Elemen Latar Belakang Blur (Blobs) -->
    <div class="fixed top-[-10%] left-[-10%] w-[40rem] h-[40rem] bg-blue-600/10 rounded-full blur-[100px] animate-blob pointer-events-none z-0"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[40rem] h-[40rem] bg-emerald-600/10 rounded-full blur-[100px] animate-blob pointer-events-none z-0" style="animation-delay: 2s;"></div>

    <nav class="w-full px-6 py-4 md:px-8 md:py-6 flex justify-between items-center shrink-0 z-40 relative">
        <div class="flex items-center gap-3 group cursor-pointer">
            @if($pengaturan && $pengaturan->logo_sekolah)
                <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo" class="w-10 h-10 md:w-12 md:h-12 object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
            @else
                <div class="w-10 h-10 md:w-12 md:h-12 bg-slate-900 rounded-xl flex items-center justify-center shadow-md text-white font-bold text-xl group-hover:scale-105 transition-transform duration-300">M1</div>
            @endif
            <div class="flex flex-col">
                <span class="font-extrabold text-lg md:text-xl text-slate-900 leading-none tracking-tight">SmartM1</span>
                <span class="text-[10px] md:text-xs text-slate-500 font-bold tracking-widest mt-0.5 uppercase">{{ $namaSekolah }}</span>
            </div>
        </div>

        <button onclick="openFeaturesModal()" class="hidden md:flex items-center gap-2 px-5 py-2.5 rounded-full bg-white/80 backdrop-blur-md border border-slate-200 text-sm font-bold text-slate-600 hover:text-slate-900 hover:border-slate-300 hover:shadow-sm transition-all hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>Informasi Sistem</span>
        </button>
    </nav>

    <!-- MAIN CONTENT: BENTO GRID LAYOUT -->
    <main class="flex-1 w-full max-w-[1400px] mx-auto px-4 md:px-8 pb-4 md:pb-8 flex flex-col justify-center h-full relative z-10">
        
        <!-- Grid Container -->
        <div class="grid grid-cols-1 md:grid-cols-12 md:grid-rows-2 gap-4 md:gap-6 h-full max-h-[700px]">
            
            <!-- KOTAK 1: KOTAK UTAMA (HERO) -->
            <!-- Ditambahkan backdrop-blur dan efek hover agar kartu terasa "hidup" -->
            <div class="md:col-span-8 md:row-span-2 bg-white/70 backdrop-blur-xl rounded-[2rem] p-8 md:p-12 border border-slate-200/60 shadow-xl shadow-slate-200/20 flex flex-col justify-center relative overflow-hidden group hover:-translate-y-1 hover:shadow-2xl hover:shadow-slate-300/40 transition-all duration-500">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-slate-100/80 border border-slate-200/80 mb-6 backdrop-blur-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] font-extrabold tracking-widest text-slate-600 uppercase">SMARTM1</span>
                    </div>

                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold tracking-tight text-slate-900 mb-6 leading-[1.1]">
                        Portal Layanan Akademik,<br>
                        <span class="text-edu-600">Berbasis Digital.</span>
                    </h1>

                    <!-- Font size dinaikkan ke text-base md:text-xl agar normal dan jelas -->
                    <p class="text-base md:text-xl text-slate-600 max-w-xl mb-10 leading-relaxed font-medium">
                        Platform manajemen administrasi, akademik, dan pelayanan kesiswaan secara terintegrasi, transparan, dan akuntabel.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button onclick="openLoginModal()" class="group px-8 py-4 bg-slate-900 text-white font-bold rounded-xl shadow-lg shadow-slate-900/20 hover:bg-edu-600 hover:shadow-edu-600/30 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3 text-sm md:text-base">
                            Otorisasi Masuk
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                        <button onclick="openFeaturesModal()" class="md:hidden px-8 py-4 bg-white/80 backdrop-blur-md text-slate-700 border border-slate-200 font-bold rounded-xl hover:bg-slate-50 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center text-sm">
                            Pelajari Fitur
                        </button>
                    </div>
                </div>
            </div>

            <!-- KOTAK 2: INFO KEAMANAN -->
            <div class="hidden md:flex md:col-span-4 md:row-span-1 bg-slate-900/95 backdrop-blur-xl text-white rounded-[2rem] p-8 flex-col justify-between border border-slate-700 shadow-xl overflow-hidden relative group hover:-translate-y-1 hover:shadow-2xl hover:shadow-blue-900/20 transition-all duration-500 cursor-default">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 group-hover:-rotate-6 transition-transform duration-700">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div class="relative z-10">
                    <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center mb-5 backdrop-blur-sm border border-white/5 group-hover:bg-white/20 transition-colors">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-white">Infrastruktur Aman</h3>
                    <!-- Font size dinaikkan ke text-base -->
                    <p class="text-base text-slate-300 leading-relaxed font-medium">
                        Data privasi dijamin dengan enkripsi mutakhir dan pemisahan hak akses otoritas.
                    </p>
                </div>
            </div>

            <!-- KOTAK 3: INFO AKADEMIK -->
            <div class="hidden md:flex md:col-span-4 md:row-span-1 bg-edu-50/80 backdrop-blur-xl rounded-[2rem] p-8 flex-col justify-between border border-edu-100 shadow-lg group hover:-translate-y-1 hover:shadow-2xl hover:shadow-edu-500/10 transition-all duration-500 cursor-default">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center mb-5 text-edu-600 border border-edu-100 group-hover:bg-edu-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Basis Data Terpadu</h3>
                    <!-- Font size dinaikkan ke text-base -->
                    <p class="text-base text-slate-600 leading-relaxed font-medium">
                        Integrasi absensi harian & pelajaran, jurnal guru, nilai rapor, catatan kasus, dan manajemen mengajar secara digital.
                    </p>
                </div>
            </div>

        </div>
    </main>

    <footer class="relative z-10 w-full text-center py-4 text-[10px] md:text-xs text-slate-500 font-semibold shrink-0 bg-white/30 backdrop-blur-md border-t border-slate-200/50">
        &copy; {{ date('Y') }} {{ $namaSekolah }}. Sistem Manajemen Terpadu.
    </footer>

    <!-- LOGIN MODAL -->
    <div id="loginModal" class="modal-overlay fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeLoginModal()"></div>
        
        <div class="modal-content relative w-full max-w-sm">
            <button onclick="closeLoginModal()" class="absolute -top-12 right-0 p-2 text-white/70 hover:text-white transition-colors z-10 hover:rotate-90 duration-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="bg-white/95 backdrop-blur-xl rounded-[2rem] shadow-2xl overflow-hidden border border-slate-100/50">
                <div class="p-8 pb-6 border-b border-slate-100 text-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-edu-50 to-transparent opacity-50"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-slate-900 rounded-2xl flex items-center justify-center mx-auto mb-5 text-white shadow-lg">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Otorisasi Gerbang</h2>
                        <!-- Font size dinaikkan ke text-sm -->
                        <p class="text-sm text-slate-500 mt-2 font-medium">Pilih peran Anda untuk masuk ke dalam sistem.</p>
                    </div>
                </div>
                
                <div class="p-5 space-y-3 bg-slate-50/50">
                    <a href="{{ url('/admin/login') }}" class="flex items-center justify-between p-4 bg-white border border-slate-200 hover:border-slate-900 hover:shadow-md rounded-2xl transition-all duration-300 group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center group-hover:bg-slate-900 group-hover:text-white transition-colors duration-300">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <div class="text-left">
                                <!-- Font dinaikkan -->
                                <h3 class="text-base font-bold text-slate-900">Admin & Guru</h3>
                                <p class="text-xs text-slate-500 font-medium">Staf Pengajar & Tata Usaha</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-slate-300 group-hover:text-slate-900 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                    
                    <a href="{{ url('/siswa/login') }}" class="flex items-center justify-between p-4 bg-white border border-slate-200 hover:border-edu-600 hover:shadow-md rounded-2xl transition-all duration-300 group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-edu-50 text-edu-600 flex items-center justify-center group-hover:bg-edu-600 group-hover:text-white transition-colors duration-300">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                            </div>
                            <div class="text-left">
                                <!-- Font dinaikkan -->
                                <h3 class="text-base font-bold text-slate-900">Portal Siswa</h3>
                                <p class="text-xs text-slate-500 font-medium">Akses Nilai & E-Rapor</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-slate-300 group-hover:text-edu-600 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FEATURES MODAL (Tampil jika di klik di mobile) -->
    <div id="featuresModal" class="modal-overlay fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeFeaturesModal()"></div>
        <div class="modal-content relative w-full max-w-sm bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden border border-slate-100">
            <div class="flex justify-between items-center p-6 border-b border-slate-100 bg-slate-50/80">
                <h2 class="text-lg font-extrabold text-slate-900">Informasi Sistem</h2>
                <button onclick="closeFeaturesModal()" class="p-1.5 text-slate-400 hover:text-slate-900 hover:bg-slate-200 rounded-full transition-colors hover:rotate-90 duration-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto space-y-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-edu-50 text-edu-600 flex items-center justify-center border border-edu-100"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg></div>
                        <!-- Font dinaikkan -->
                        <h3 class="text-base font-bold text-slate-900">Data Terpusat</h3>
                    </div>
                    <!-- Font dinaikkan -->
                    <p class="text-sm text-slate-600 pl-13 font-medium">Satu sumber kebenaran untuk nilai, absensi, dan profil siswa.</p>
                </div>
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg></div>
                        <!-- Font dinaikkan -->
                        <h3 class="text-base font-bold text-slate-900">Infrastruktur Aman</h3>
                    </div>
                    <!-- Font dinaikkan -->
                    <p class="text-sm text-slate-600 pl-13 font-medium">Data privasi dijamin dengan enkripsi mutakhir dan pemisahan hak akses.</p>
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