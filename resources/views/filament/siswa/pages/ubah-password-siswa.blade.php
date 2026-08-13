<x-filament-panels::page>
    @php
        $pengaturan = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) {
                $pengaturan = \App\Models\Pengaturan::first();
            }
        } catch (\Exception $e) {}
    @endphp

    <div wire:ignore>
        <script>
            // Memaksa warna status bar di mobile agar senada dengan background aplikasi
            const metaThemeColor = document.createElement('meta');
            metaThemeColor.name = 'theme-color';
            metaThemeColor.content = '#F5F5F7';
            document.head.appendChild(metaThemeColor);
        </script>
        
        <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
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
                font-family: 'DM Sans', sans-serif !important; 
                background-color: var(--uibg) !important; 
                color: var(--uitext) !important; 
                margin: 0; padding: 0;
                overflow-x: hidden !important; 
                -webkit-font-smoothing: antialiased;
            }

            /* Sembunyikan Header/Sidebar bawaan Filament sepenuhnya */
            .fi-topbar, .fi-sidebar, .fi-header, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            html, body, .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page, section { 
                padding: 0 !important; margin: 0 !important; gap: 0 !important;
                background-color: transparent !important; box-shadow: none !important; border: none !important;
            }

            section.grid.auto-cols-fr.gap-y-6, .fi-simple-main-ctn, .fi-main-ctn { 
                gap: 0 !important; 
                padding: 0 !important; 
                margin: 0 !important; 
            }

            /* Container Utama - Menggunakan flow CSS normal agar bisa scroll alami */
            .workspace-container {
                width: 100%; max-width: 414px; margin: 0 auto;
                min-height: 100vh; min-height: 100dvh;
                display: flex; flex-direction: column;
                background-color: var(--uibg);
            }
            @media (min-width: 640px) {
                .workspace-container {
                    border-left: 1px solid var(--uiborder);
                    border-right: 1px solid var(--uiborder);
                    box-shadow: 0 0 50px rgba(0,0,0,0.05);
                }
            }

            .touch-scale { transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
            .touch-scale:active { transform: scale(0.96); }

            /* Penyesuaian Form Input Filament agar sama dengan Login */
            #security-overlay .fi-fo-field-wrp label span { color: var(--uiblack) !important; font-weight: 800 !important; font-size: 13px !important; letter-spacing: 0.02em; text-transform: uppercase; }
            #security-overlay .fi-fo-field-wrp p { color: var(--uimuted) !important; font-size: 12px !important; font-weight: 500 !important; margin-top: 4px !important;}
            #security-overlay .fi-fo-field-wrp-error-message { color: #EF4444 !important; font-size: 12px !important; font-weight: 700 !important; }
            
            #security-overlay .fi-input-wrp {
                background-color: var(--uibg) !important;
                border: 1px solid var(--uiborder) !important; 
                border-radius: 16px !important; 
                box-shadow: none !important;
                transition: all 0.2s ease !important;
                overflow: hidden;
            }
            
            #security-overlay .fi-input-wrp:focus-within {
                border-color: var(--uiblack) !important;
                background-color: var(--uisurface) !important;
                box-shadow: 0 4px 12px rgba(24, 24, 27, 0.08) !important;
            }
            
            #security-overlay .fi-input { 
                color: var(--uiblack) !important; 
                padding: 16px !important; 
                background: transparent !important;
                font-size: 15px !important;
                font-weight: 700 !important;
            }

            [x-cloak] { display: none !important; }
        </style>
    </div>

    <!-- Halaman Utama -->
    <div id="security-overlay" class="min-h-screen w-full flex flex-col selection:bg-zinc-900 selection:text-white bg-uibg">
        
        <div class="workspace-container">
            
            <!-- Area Atas (Branding) -->
            <div class="flex-1 flex flex-col items-center justify-center p-6 relative min-h-[35vh]">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-white rounded-full blur-3xl opacity-50 pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-[20px] bg-uiblack flex items-center justify-center text-white mb-4 shadow-[0_8px_20px_rgba(24,24,27,0.2)]">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    
                    <h1 class="text-[24px] font-black text-uiblack tracking-tight leading-tight mb-1">
                        Keamanan Akun
                    </h1>
                    <p class="text-[12px] font-semibold text-uimuted uppercase tracking-widest">
                        Portal Siswa
                    </p>
                </div>
            </div>

            <!-- Area Bawah (Bottom Sheet CSS Murni Anti-Hang) -->
            <div class="bg-uisurface rounded-t-[40px] px-6 pt-6 pb-12 border-t border-uiborder shadow-[0_-20px_60px_rgba(0,0,0,0.05)] relative flex-shrink-0" style="animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;">
                <style>@keyframes slideUp { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }</style>
                
                <!-- Handle Indicator -->
                <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-6"></div>

                <div class="text-center mb-6">
                    <p class="text-[13px] font-medium text-uimuted leading-snug">
                        Sandi Anda tidak aman. Demi perlindungan data nilai, silakan atur kata sandi rahasia baru.
                    </p>
                </div>

                <div>
                    <!-- Form Utama Livewire -->
                    <form wire:submit="simpan" class="space-y-5">
                        
                        {{ $this->form }}

                        <div class="pt-4">
                            <button type="submit" wire:loading.attr="disabled" class="touch-scale w-full flex justify-center items-center py-4 px-6 rounded-[100px] text-[14px] font-bold text-white bg-uiblack uppercase tracking-wide transition-all shadow-[0_8px_25px_rgba(24,24,27,0.2)] disabled:opacity-70 disabled:cursor-not-allowed group hover:bg-black">
                                
                                <span wire:loading.remove wire:target="simpan" class="flex items-center gap-2">
                                    Simpan & Masuk
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </span>
                                
                                <span wire:loading wire:target="simpan" class="flex items-center gap-3">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </form>
                    
                    <!-- Alternatif: Lewati & Batal -->
                    <div class="mt-4 flex flex-col items-center gap-2">
                        
                        <!-- Tombol Lewati: Menghilangkan paksaan ganti password untuk sesi ini -->
                        <button type="button" wire:click="lewati" class="touch-scale text-[12px] font-bold uppercase tracking-wider text-uimuted hover:text-uiblack transition-colors py-2 border-none bg-transparent cursor-pointer">
                            Lewati Untuk Saat Ini &rarr;
                        </button>
                        
                        <a href="/siswa/profil" class="touch-scale inline-flex items-center gap-2 text-[12px] font-bold uppercase tracking-wider text-uimuted hover:text-uiblack transition-colors py-2">
                            &larr; Batal & Kembali
                        </a>
                    </div>

                </div>
                
            </div>

        </div>
    </div>
</x-filament-panels::page>