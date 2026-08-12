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
    <meta name="keywords" content="Smart-M1, SMAN 1 Malingping, SmartM1 SMAN 1 Malingping, aplikasi digitalisasi sekolah, sistem administrasi sekolah, akademik siswa, e-rapor, absensi siswa, portal siswa Malingping">
    <meta name="author" content="{{ $namaSekolah }}">
    
    @if($pengaturan && $pengaturan->logo_sekolah)
        <link rel="icon" href="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" type="image/x-icon"/>
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f6ff', 100: '#e0edff', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f8fafc; 
            color: #0f172a; 
            margin: 0;
            overflow-x: hidden;
        }

        /* Profesional Grid Pattern Background */
        .bg-grid-pattern {
            background-size: 40px 40px;
            background-image: 
                linear-gradient(to right, rgba(15, 23, 42, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(15, 23, 42, 0.04) 1px, transparent 1px);
            background-position: center top;
        }

        /* Clean Modals */
        .modal-overlay {
            opacity: 0; visibility: hidden; transition: all 0.2s ease-in-out;
        }
        .modal-overlay.active {
            opacity: 1; visibility: visible;
        }
        .modal-content {
            transform: translateY(15px) scale(0.98); opacity: 0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .modal-overlay.active .modal-content {
            transform: translateY(0) scale(1); opacity: 1;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col relative bg-grid-pattern selection:bg-brand-600 selection:text-white">

    <!-- Navbar -->
    <nav class="w-full bg-white/90 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                <div class="flex items-center gap-3">
                    @if($pengaturan && $pengaturan->logo_sekolah)
                        <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo" class="w-10 h-10 md:w-12 md:h-12 object-contain">
                    @else
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-brand-900 rounded-lg flex items-center justify-center text-white font-bold text-lg">M1</div>
                    @endif
                    <div class="flex flex-col justify-center">
                        <span class="font-extrabold text-lg md:text-xl text-slate-900 leading-tight tracking-tight">SMART-M1</span>
                        <span class="text-[10px] md:text-xs text-slate-500 font-semibold tracking-wide uppercase">{{ $namaSekolah }}</span>
                    </div>
                </div>

                <div class="hidden md:flex items-center gap-4">
                    <button onclick="openFeaturesModal()" class="text-sm font-medium text-slate-600 hover:text-brand-600 transition-colors">
                        Informasi Sistem
                    </button>
                    <a href="mailto:info@sman1malingping.sch.id" class="text-sm font-medium text-slate-600 hover:text-brand-600 transition-colors">
                        Bantuan
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20 lg:py-24 flex flex-col justify-center relative z-10">
        
        <div class="text-center max-w-4xl mx-auto">
            
            <!-- Badge Sistem Aktif -->
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 mb-8 shadow-sm">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <span class="text-[11px] font-bold tracking-widest text-emerald-700 uppercase">SYSTEM AKTIF</span>
            </div>

            <!-- Headline -->
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.15] mb-6">
                Portal Sistem Informasi Manajemen<br>
                <span class="text-brand-600">{{ $namaSekolah }}</span>
            </h1>

            <p class="mt-4 text-base md:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">
                Infrastruktur digital terintegrasi untuk pengelolaan administrasi sekolah, akademik, dan layanan peserta didik secara terpusat, aman, dan efisien.
            </p>

            <!-- CTA Buttons -->
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ url('/siswa/login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 text-sm font-semibold text-white bg-brand-600 rounded-lg shadow-sm hover:bg-brand-700 hover:shadow transition-all border border-transparent">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    Portal Siswa
                </a>
                <a href="{{ url('/admin/login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 text-sm font-semibold text-slate-700 bg-white rounded-lg shadow-sm border border-slate-300 hover:bg-slate-50 hover:border-slate-400 transition-all">
                    <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Admin & Guru
                </a>
            </div>

            <!-- Fitur Cards (Professional look) -->
            <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 rounded-lg bg-brand-50 flex items-center justify-center border border-brand-100 mb-4">
                        <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                    </div>
                    <h3 class="text-slate-900 font-bold mb-2">Basis Data Terpusat</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Single source of truth untuk data siswa, mutasi, absensi, dan administrasi sekolah yang dikelola secara real-time.</p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center border border-emerald-100 mb-4">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-slate-900 font-bold mb-2">Keamanan Berlapis</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Infrastruktur terlindungi dengan otorisasi ketat. Sistem penjaminan keaslian dokumen menggunakan teknologi QRCode.</p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center border border-orange-100 mb-4">
                        <svg class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="text-slate-900 font-bold mb-2">Akses Cepat E-Rapor</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Kemudahan bagi wali murid dan siswa untuk memantau perkembangan akademik, nilai, dan rekapitulasi kehadiran.</p>
                </div>

            </div>
        </div>
    </main>

    <footer class="w-full text-center py-6 text-sm text-slate-500 font-medium shrink-0 bg-white border-t border-slate-200 z-10 mt-auto">
        &copy; {{ date('Y') }} Sistem Informasi Manajemen {{ $namaSekolah }}.
    </footer>

    <!-- Modal Informasi -->
    <div id="featuresModal" class="modal-overlay fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeFeaturesModal()"></div>
        <div class="modal-content relative w-full max-w-md bg-white rounded-2xl shadow-xl flex flex-col overflow-hidden border border-slate-200">
            <div class="flex justify-between items-center p-5 border-b border-slate-100 bg-slate-50">
                <h2 class="text-base font-bold text-slate-900">Tentang Smart-M1</h2>
                <button onclick="closeFeaturesModal()" class="text-slate-400 hover:text-slate-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 text-slate-600 text-sm leading-relaxed">
                <p class="mb-4">
                    <b>Smart-M1</b> adalah sistem manajemen informasi khusus yang dikembangkan untuk memfasilitasi kebutuhan administrasi dan operasional <b>{{ $namaSekolah }}</b>.
                </p>
                <p>
                    Sistem ini terbagi menjadi dua portal utama:
                </p>
                <ul class="list-disc pl-5 mt-2 space-y-1 mb-4">
                    <li><b>Portal Admin & Guru:</b> Mengelola data kepegawaian, siswa, pembuatan dokumen resmi, dan penginputan nilai.</li>
                    <li><b>Portal Siswa:</b> Akses khusus peserta didik untuk melihat e-Rapor, riwayat absensi, dan jadwal akademik.</li>
                </ul>
                <div class="p-3 bg-brand-50 border border-brand-100 rounded-lg text-brand-800 text-xs">
                    Jika Anda mengalami kendala saat login, silakan hubungi Staff Tata Usaha (TU) sekolah.
                </div>
            </div>
        </div>
    </div>

    <script>
        const featuresModal = document.getElementById('featuresModal');
        function openFeaturesModal() { featuresModal.classList.add('active'); }
        function closeFeaturesModal() { featuresModal.classList.remove('active'); }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && featuresModal.classList.contains('active')) {
                closeFeaturesModal();
            }
        });
    </script>
</body>
</html>