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
                background-color: var(--ui-bg) !important; 
                color: var(--ui-text) !important; 
                margin: 0; padding: 0;
                overflow: hidden !important; 
                -webkit-font-smoothing: antialiased;
            }

            .fi-topbar, .fi-sidebar, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page { 
                padding: 0 !important; margin: 0 !important; max-width: 100% !important; 
                background-color: transparent !important; box-shadow: none !important; border: none !important;
            }

            section.grid.auto-cols-fr.gap-y-6, .fi-simple-main-ctn, .fi-main-ctn { 
                gap: 0 !important; 
                padding: 0 !important; 
                margin: 0 !important; 
            }

            .workspace-container {
                width: 100%; max-width: 414px; margin: 0 auto;
                position: fixed; top: 0; bottom: 0; left: 0; right: 0;
                display: flex; flex-direction: column;
                background-color: var(--ui-bg);
                overflow: hidden;
            }
            @media (min-width: 640px) {
                .workspace-container {
                    left: 50%; right: auto; transform: translateX(-50%);
                    border-left: 1px solid var(--ui-border); border-right: 1px solid var(--ui-border);
                    box-shadow: 0 0 50px rgba(0,0,0,0.05);
                }
            }

            .touch-scale { transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
            .touch-scale:active { transform: scale(0.96); }

            #security-overlay .fi-fo-field-wrp label span { color: var(--ui-black) !important; font-weight: 800 !important; font-size: 13px !important; letter-spacing: 0.02em; text-transform: uppercase; }
            #security-overlay .fi-fo-field-wrp p { color: var(--ui-muted) !important; font-size: 12px !important; font-weight: 500 !important; margin-top: 4px !important;}
            #security-overlay .fi-fo-field-wrp-error-message { color: #EF4444 !important; font-size: 12px !important; font-weight: 700 !important; }
            
            #security-overlay .fi-input-wrp {
                background-color: var(--ui-bg) !important;
                border: 1px solid var(--ui-border) !important; 
                border-radius: 16px !important; 
                box-shadow: none !important;
                transition: all 0.2s ease !important;
                overflow: hidden;
            }
            
            #security-overlay .fi-input-wrp:focus-within {
                border-color: var(--ui-black) !important;
                background-color: var(--ui-surface) !important;
                box-shadow: 0 4px 12px rgba(24, 24, 27, 0.08) !important;
            }
            
            #security-overlay .fi-input { 
                color: var(--ui-black) !important; 
                padding: 16px !important; 
                background: transparent !important;
                font-size: 15px !important;
                font-weight: 700 !important;
            }

            .sheet-scroll { overflow-y: auto; scrollbar-width: none; }
            .sheet-scroll::-webkit-scrollbar { display: none; }
            
            [x-cloak] { display: none !important; }
        </style>
    </div>

    <div id="security-overlay" class="fixed inset-0 z-[99999] min-h-screen w-full flex flex-col selection:bg-zinc-900 selection:text-white bg-uibg">
        
        <div class="workspace-container">
            
            <div class="flex-1 flex flex-col items-center justify-center p-6 relative">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-white rounded-full blur-3xl opacity-50 pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-[20px] bg-uiblack flex items-center justify-center text-white mb-5 shadow-[0_8px_20px_rgba(24,24,27,0.2)]">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    
                    <h1 class="text-[28px] font-black text-uiblack tracking-tight leading-tight mb-2">
                        Keamanan Akun
                    </h1>
                    <p class="text-[14px] font-semibold text-uimuted uppercase tracking-widest">
                        Portal Siswa
                    </p>
                </div>
            </div>

            <div class="sheet-scroll bg-uisurface rounded-t-[40px] px-6 pt-6 pb-8 border-t border-uiborder shadow-[0_-20px_60px_rgba(0,0,0,0.05)] relative flex-shrink-0">
                
                <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-8"></div>

                <div class="text-center mb-6">
                    <h2 class="text-[18px] font-black text-uiblack">Atur Kata Sandi Baru</h2>
                    <p class="text-[12px] font-bold text-uimuted mt-1">Demi perlindungan data nilai, atur kata sandi rahasia.</p>
                </div>

                <div>
                    <form wire:submit="simpan" class="space-y-5">
                        
                        {{ $this->form }}

                        <div class="pt-4 flex flex-col gap-3">
                            
                            <button type="submit" wire:loading.attr="disabled" wire:target="simpan" class="touch-scale w-full flex justify-center items-center py-4 px-6 rounded-[100px] text-[14px] font-bold text-white bg-uiblack uppercase tracking-wide transition-all shadow-[0_8px_25px_rgba(24,24,27,0.2)] disabled:opacity-70 disabled:cursor-not-allowed group hover:bg-black">
                                <span wire:loading.remove wire:target="simpan" class="flex items-center gap-2">
                                    Simpan & Masuk
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </span>
                                
                                <span wire:loading wire:target="simpan" class="flex items-center gap-3" x-cloak>
                                    Memproses...
                                </span>
                            </button>

                            <button type="button" wire:click="lewati" wire:loading.attr="disabled" wire:target="lewati" class="touch-scale w-full flex justify-center items-center py-3.5 px-6 rounded-[100px] text-[13px] font-bold text-uiblack bg-gray-100 uppercase tracking-wide transition-colors hover:bg-gray-200 disabled:opacity-70">
                                <span wire:loading.remove wire:target="lewati">
                                    Lewati Untuk Saat Ini
                                </span>
                                <span wire:loading wire:target="lewati" class="flex items-center gap-2 text-uimuted" x-cloak>
                                    Melewati...
                                </span>
                            </button>

                        </div>
                    </form>
                </div>
                
                <div class="mt-6 flex justify-center">
                    <a href="{{ url('/siswa/profil') }}" class="inline-flex items-center gap-2 text-[12px] font-bold uppercase tracking-wider text-uimuted hover:text-uiblack transition-colors py-2">
                        Batal & Kembali
                    </a>
                </div>
                
            </div>

        </div>

    </div>
</x-filament-panels::page>