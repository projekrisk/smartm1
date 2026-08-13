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
            // Memaksa warna status bar (baterai/sinyal) di mobile agar senada dengan background aplikasi
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
            :root {
                --ui-bg: #F5F5F7;
                --ui-surface: #FFFFFF;
                --ui-black: #18181B;
                --ui-text: #27272A;
                --ui-muted: #71717A;
                --ui-border: #E4E4E7;
            }

            body { 
                font-family: 'DM Sans', sans-serif !important; 
                overflow: hidden !important; 
                background-color: var(--ui-bg) !important; 
                color: var(--ui-text) !important;
                -webkit-font-smoothing: antialiased;
                margin: 0; padding: 0;
            }

            /* Hide Filament default UI elements completely */
            .fi-topbar, .fi-sidebar, .fi-header, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            html, body, .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page, section { 
                padding: 0 !important; margin: 0 !important; gap: 0 !important;
                height: 100vh !important; height: 100dvh !important; 
                max-width: 100% !important; width: 100% !important; 
                background-color: transparent !important; box-shadow: none !important; border: none !important;
            }

            /* Menghapus gap dan padding bawaan section Filament */
            section.grid.auto-cols-fr.gap-y-6, .fi-simple-main-ctn, .fi-main-ctn { 
                gap: 0 !important; 
                padding: 0 !important; 
                margin: 0 !important; 
            }

            /* Main Mobile Workspace */
            .workspace-container {
                width: 100%; max-width: 414px; margin: 0 auto;
                position: fixed; top: 0; bottom: 0; left: 0; right: 0;
                display: flex; flex-direction: column;
                background-color: var(--ui-bg);
                overflow: hidden;
            }

            /* Desktop boundaries */
            @media (min-width: 640px) {
                .workspace-container {
                    left: 50%; right: auto; transform: translateX(-50%);
                    border-left: 1px solid var(--ui-border);
                    border-right: 1px solid var(--ui-border);
                    box-shadow: 0 0 50px rgba(0,0,0,0.05);
                }
            }

            /* Touch Interactions */
            .touch-scale { transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
            .touch-scale:active { transform: scale(0.96); }

            /* Override Label & Text Filament (SAMA PERSIS DENGAN LOGIN SISWA) */
            #security-overlay .fi-fo-field-wrp label span { color: var(--ui-black) !important; font-weight: 800 !important; font-size: 13px !important; letter-spacing: 0.02em; }
            #security-overlay .fi-fo-field-wrp p { color: var(--ui-muted) !important; font-size: 12px !important; font-weight: 500 !important; margin-top: 4px !important;}
            #security-overlay .fi-fo-field-wrp-error-message { color: #EF4444 !important; font-size: 12px !important; font-weight: 700 !important; }
            
            /* Override Input Form Filament (SAMA PERSIS DENGAN LOGIN SISWA) */
            #security-overlay .fi-input-wrp {
                background-color: var(--ui-bg) !important;
                border: 1px solid var(--ui-border) !important; 
                border-radius: 16px !important; 
                box-shadow: none !important;
                transition: all 0.2s ease !important;
                overflow: hidden;
                position: relative; /* Penting untuk tombol mata */
            }
            
            #security-overlay .fi-input-wrp:focus-within {
                border-color: var(--ui-black) !important;
                background-color: var(--ui-surface) !important;
                box-shadow: 0 0 0 4px rgba(24, 24, 27, 0.1) !important;
            }
            
            #security-overlay .fi-input { 
                color: var(--ui-black) !important; 
                padding: 16px !important; 
                padding-right: 48px !important; /* Ruang untuk ikon mata */
                background: transparent !important;
                font-size: 15px !important;
                font-weight: 600 !important;
            }

            /* Custom Scrollbar untuk Bottom Sheet agar bisa digulir kalau konten panjang */
            .sheet-scroll { overflow-y: auto; scrollbar-width: none; }
            .sheet-scroll::-webkit-scrollbar { display: none; }
            
            [x-cloak] { display: none !important; }
        </style>
    </div>

    <!-- Gunakan div utama yang fixed dan mengambil seluruh layar (SAMA PERSIS DENGAN LOGIN SISWA) -->
    <div id="security-overlay" class="fixed inset-0 z-[99999] min-h-screen w-full flex flex-col selection:bg-zinc-900 selection:text-white bg-uibg">
        
        <div class="workspace-container">
            
            <!-- Area Atas (Branding) -->
            <!-- Flex-1 agar dia mendorong area bawah turun ke dasar -->
            <div class="flex-1 flex flex-col items-center justify-center p-6 relative">
                <!-- Hiasan Glow Belakang Ikon -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-40 h-40 bg-white rounded-full blur-3xl opacity-50 pointer-events-none"></div>
                
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

            <!-- Area Bawah (Bottom Sheet) -->
            <!-- Flex-shrink-0 agar sheet tidak menyusut, melainkan menempel padat di bawah -->
            <div x-data="{ showSheet: false }" 
                 x-init="setTimeout(() => showSheet = true, 100)"
                 class="sheet-scroll bg-uisurface rounded-t-[40px] px-6 pt-6 pb-10 border-t border-uiborder shadow-[0_-20px_60px_rgba(0,0,0,0.05)] relative flex-shrink-0"
                 x-show="showSheet"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="transform translate-y-full"
                 x-transition:enter-end="transform translate-y-0"
                 x-cloak>
                
                <!-- Handle Indicator -->
                <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-6"></div>

                <div class="text-center mb-6">
                    <p class="text-[13px] font-medium text-uimuted leading-snug">
                        Sandi Anda tidak aman. Demi perlindungan data nilai, silakan atur kata sandi rahasia baru.
                    </p>
                </div>

                <div>
                    <!-- Form Utama -->
                    <form wire:submit="simpan" class="space-y-5">
                        
                        {{ $this->form }}

                        <div class="pt-4 pb-2">
                            <button type="submit" wire:loading.attr="disabled" class="touch-scale w-full flex justify-center items-center py-4 px-6 rounded-[100px] text-[14px] font-bold text-white bg-uiblack uppercase tracking-wide transition-all shadow-[0_8px_25px_rgba(24,24,27,0.2)] disabled:opacity-70 disabled:cursor-not-allowed group hover:bg-black">
                                
                                <span wire:loading.remove wire:target="simpan" class="flex items-center gap-2">
                                    Simpan & Masuk
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </span>
                                
                                <span wire:loading wire:target="simpan" class="flex items-center gap-3">
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </form>

                    <!-- Link Kembali -->
                    <div class="mt-4 flex justify-center pb-2">
                        <a href="/siswa/profil" class="inline-flex items-center gap-2 text-[12px] font-bold uppercase tracking-wider text-uimuted hover:text-uiblack transition-colors py-2">
                            &larr; Batal & Kembali
                        </a>
                    </div>
                </div>
                
            </div>

        </div>

    </div>

    <!-- Script Tombol Mata (Show/Hide Password) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const attachEyeIcons = () => {
                // Cari semua input bertipe password (atau yang sudah kita tandai)
                const passwordInputs = document.querySelectorAll('input[type="password"], input[data-is-password="true"]');
                
                passwordInputs.forEach(input => {
                    // Jika tombol mata sudah terpasang, lewati
                    if (input.parentNode.querySelector('.toggle-password-btn')) return;
                    
                    // Tandai
                    input.setAttribute('data-is-password', 'true');
                    
                    // Pastikan wrapper induknya memiliki position: relative
                    const wrapper = input.closest('.fi-input-wrp');
                    if (wrapper) {
                        wrapper.style.position = 'relative';
                        
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'toggle-password-btn absolute right-4 top-1/2 transform -translate-y-1/2 text-uimuted hover:text-uiblack focus:outline-none p-1 transition-colors bg-transparent border-none';
                        btn.style.zIndex = '10'; // Pastikan bisa diklik
                        
                        const iconShow = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`;
                        const iconHide = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>`;
                        
                        btn.innerHTML = input.type === 'password' ? iconShow : iconHide;
                        
                        btn.addEventListener('click', (e) => {
                            e.preventDefault();
                            if (input.type === 'password') {
                                input.type = 'text';
                                btn.innerHTML = iconHide;
                                btn.classList.add('text-uiblack');
                            } else {
                                input.type = 'password';
                                btn.innerHTML = iconShow;
                                btn.classList.remove('text-uiblack');
                            }
                        });
                        
                        wrapper.appendChild(btn);
                    }
                });
            };

            // Pasang pertama kali
            attachEyeIcons();
            
            // Observer untuk Livewire Re-render
            const observer = new MutationObserver((mutations) => {
                attachEyeIcons();
            });
            observer.observe(document.body, { childList: true, subtree: true });
        });
    </script>
</x-filament-panels::page>