<x-filament-panels::page.simple>
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
                overflow: hidden !important; 
                background-color: var(--ui-bg) !important; 
                color: var(--ui-text) !important;
                -webkit-font-smoothing: antialiased;
                margin: 0; padding: 0;
            }

            .fi-topbar, .fi-sidebar, .fi-header, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            html, body, .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page, section { 
                padding: 0 !important; margin: 0 !important; gap: 0 !important;
                height: 100vh !important; height: 100dvh !important; 
                max-width: 100% !important; width: 100% !important; 
                background-color: transparent !important; box-shadow: none !important; border: none !important;
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
                    border-left: 1px solid var(--ui-border);
                    border-right: 1px solid var(--ui-border);
                    box-shadow: 0 0 50px rgba(0,0,0,0.05);
                }
            }

            .touch-scale { transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
            .touch-scale:active { transform: scale(0.96); }

            #student-login .fi-fo-field-wrp label span { color: var(--ui-black) !important; font-weight: 800 !important; font-size: 13px !important; letter-spacing: 0.02em; text-transform: uppercase; }
            #student-login .fi-fo-field-wrp p { color: var(--ui-muted) !important; font-size: 12px !important; font-weight: 500 !important; margin-top: 4px !important;}
            #student-login .fi-fo-field-wrp-error-message { color: #EF4444 !important; font-size: 12px !important; font-weight: 700 !important; }
            
            #student-login .fi-input-wrp {
                background-color: var(--ui-bg) !important;
                border: 1px solid var(--ui-border) !important; 
                border-radius: 16px !important; 
                box-shadow: none !important;
                transition: all 0.2s ease !important;
                overflow: hidden;
            }
            
            #student-login .fi-input-wrp:focus-within {
                border-color: var(--ui-black) !important;
                background-color: var(--ui-surface) !important;
                box-shadow: 0 4px 12px rgba(24, 24, 27, 0.08) !important;
            }
            
            #student-login .fi-input { 
                color: var(--ui-black) !important; 
                padding: 16px !important; 
                background: transparent !important;
                font-size: 15px !important;
                font-weight: 700 !important;
            }
            
            #student-login input[type="checkbox"],
            #student-login .fi-checkbox-input { 
                appearance: none !important;
                -webkit-appearance: none !important;
                width: 1.25rem !important;
                height: 1.25rem !important;
                border: 1.5px solid var(--ui-muted) !important; 
                border-radius: 6px !important; 
                background-color: var(--ui-surface) !important;
                cursor: pointer !important;
                display: inline-block !important;
                position: relative !important;
                outline: none !important;
                box-shadow: none !important;
                transition: all 0.2s ease !important;
            }
            
            #student-login .fi-checkbox-input:checked {
                background-color: var(--ui-black) !important;
                border-color: var(--ui-black) !important;
                background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e") !important;
            }
            
            #student-login .fi-checkbox-input:focus {
                box-shadow: 0 0 0 4px rgba(24, 24, 27, 0.1) !important;
            }
            
            [x-cloak] { display: none !important; }
        </style>
    </div>

    <div class="workspace-container selection:bg-zinc-900 selection:text-white" id="student-login">
        
        @if($errors->any())
            <div x-data="{ show: true }"
                 x-show="show"
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="-translate-y-5 opacity-0"
                 x-transition:enter-end="translate-y-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 x-init="setTimeout(() => show = false, 5000)"
                 x-cloak
                 class="fixed top-4 left-4 right-4 sm:left-1/2 sm:-translate-x-1/2 sm:w-full sm:max-w-sm z-[999999] flex overflow-hidden bg-white border border-red-100 shadow-[0_10px_40px_rgba(239,68,68,0.15)] pointer-events-auto rounded-2xl">
                
                <div class="flex items-center justify-center w-12 bg-red-50 shrink-0 text-red-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                
                <div class="px-4 py-3.5 flex-1 min-w-0">
                    <span class="text-[11px] font-bold text-red-600 uppercase tracking-widest">Akses Ditolak</span>
                    <p class="text-[13px] text-gray-700 mt-0.5 font-medium leading-tight truncate">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        <div class="flex-1 flex flex-col items-center justify-center p-6 relative">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-white rounded-full blur-3xl opacity-50 pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col items-center text-center">
                @if($pengaturan && $pengaturan->logo_sekolah)
                    <div class="w-20 h-20 bg-uisurface rounded-[24px] p-3 mb-6 shadow-[0_8px_30px_rgba(0,0,0,0.06)] border border-uiborder">
                        <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                @else
                    <div class="w-20 h-20 bg-uiblack rounded-[24px] flex items-center justify-center text-white font-black text-3xl mb-6 shadow-md">M1</div>
                @endif
                
                <h1 class="text-[28px] font-black text-uiblack tracking-tight leading-tight mb-2">
                    Portal Siswa
                </h1>
                <p class="text-[14px] font-semibold text-uimuted uppercase tracking-widest">
                    {{ $pengaturan->nama_sekolah ?? 'SMA Negeri 1 Malingping' }}
                </p>
            </div>
        </div>

        <div x-data="{ showSheet: false }" 
             x-init="setTimeout(() => showSheet = true, 100)"
             class="bg-uisurface rounded-t-[40px] px-6 pt-6 pb-8 border-t border-uiborder shadow-[0_-20px_60px_rgba(0,0,0,0.05)] relative flex-shrink-0"
             x-show="showSheet"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="transform translate-y-full"
             x-transition:enter-end="transform translate-y-0"
             x-cloak>
            
            <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-8"></div>

            <div class="text-center mb-6">
                <h2 class="text-[18px] font-black text-uiblack">Masuk ke Akun</h2>
                <p class="text-[12px] font-bold text-uimuted mt-1">Masukkan kredensial Anda untuk melanjutkan.</p>
            </div>

            <div>
                <x-filament-panels::form wire:submit="authenticate" class="space-y-5">
                    
                    {{ $this->form }}            

                    <div class="pt-4">
                        <button type="submit" wire:loading.attr="disabled" class="touch-scale w-full flex justify-center items-center py-4 px-6 rounded-[100px] text-[14px] font-bold text-white bg-uiblack uppercase tracking-wide transition-all shadow-[0_8px_25px_rgba(24,24,27,0.2)] disabled:opacity-70 disabled:cursor-not-allowed group">
                            
                            <span wire:loading.remove wire:target="authenticate" class="flex items-center gap-2">
                                Masuk Sekarang
                            </span>
                            
                            <span wire:loading wire:target="authenticate" class="flex items-center gap-3" x-cloak>
                                Memverifikasi...
                            </span>
                        </button>
                    </div>
                </x-filament-panels::form>
            </div>
            
            <div class="mt-6 flex justify-center">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-[12px] font-bold uppercase tracking-wider text-uimuted hover:text-uiblack transition-colors py-2">
                    Beranda
                </a>
            </div>

        </div>

    </div>
</x-filament-panels::page.simple>