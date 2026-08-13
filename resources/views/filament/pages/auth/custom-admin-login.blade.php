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
                background-color: #F5F5F7 !important; 
                color: #27272A !important; 
                margin: 0; padding: 0;
                overflow: hidden !important; 
                -webkit-font-smoothing: antialiased;
            }
            
            .touch-scale { transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
            .touch-scale:active { transform: scale(0.96); }

            .fi-topbar, .fi-sidebar, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page { 
                padding: 0 !important; margin: 0 !important; max-width: 100% !important; 
                background-color: transparent !important; box-shadow: none !important; border: none !important;
            }

            /* Override Label & Text Filament */
            #single-login .fi-fo-field-wrp label span { color: #18181B !important; font-weight: 700 !important; font-size: 13px !important; letter-spacing: 0.02em; }
            #single-login .fi-fo-field-wrp p { color: #71717A !important; font-size: 13px !important; font-weight: 500 !important;}
            #single-login .fi-fo-field-wrp-error-message { color: #EF4444 !important; font-size: 12px !important; font-weight: 600 !important; }
            
            /* Override Input Form Filament */
            #single-login .fi-input-wrp {
                background-color: #F9FAFB !important;
                border: 1px solid #E4E4E7 !important; 
                border-radius: 12px !important; 
                box-shadow: none !important;
                transition: all 0.2s ease !important;
                overflow: hidden;
            }
            
            #single-login .fi-input-wrp:focus-within {
                border-color: #18181B !important;
                background-color: #FFFFFF !important;
                box-shadow: 0 0 0 4px rgba(24, 24, 27, 0.1) !important;
            }
            
            #single-login .fi-input { 
                color: #18181B !important; 
                padding: 12px 16px !important; 
                background: transparent !important;
                font-size: 15px !important;
                font-weight: 500 !important;
            }
            
            /* Override Checkbox Filament */
            #single-login input[type="checkbox"],
            #single-login .fi-checkbox-input { 
                appearance: none !important;
                -webkit-appearance: none !important;
                width: 1.25rem !important;
                height: 1.25rem !important;
                border: 1.5px solid #D4D4D8 !important; 
                border-radius: 6px !important; 
                background-color: #ffffff !important;
                cursor: pointer !important;
                display: inline-block !important;
                position: relative !important;
                outline: none !important;
                box-shadow: none !important;
                transition: all 0.2s ease !important;
            }
            
            #single-login .fi-checkbox-input:checked {
                background-color: #18181B !important;
                border-color: #18181B !important;
                background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e") !important;
            }
            
            #single-login .fi-checkbox-input:focus {
                box-shadow: 0 0 0 4px rgba(24, 24, 27, 0.1) !important;
            }
        </style>
    </div>

    <div id="single-login" class="min-h-screen w-full flex flex-col items-center justify-center p-4 sm:p-8 relative bg-uibg selection:bg-zinc-900 selection:text-white">
        
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
                 class="fixed top-6 left-1/2 -translate-x-1/2 z-[999999] flex w-full max-w-sm mx-auto overflow-hidden bg-white border border-red-100 shadow-[0_10px_40px_rgba(239,68,68,0.15)] pointer-events-auto rounded-2xl">
                
                <div class="flex items-center justify-center w-12 bg-red-50 shrink-0 text-red-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                
                <div class="px-4 py-3.5 flex-1 min-w-0">
                    <span class="text-[11px] font-bold text-red-600 uppercase tracking-widest">Akses Ditolak</span>
                    <p class="text-[13px] text-gray-700 mt-0.5 font-medium leading-tight truncate">{{ $errors->first() }}</p>
                </div>
                
                <button @click="show = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition-colors p-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        <div class="relative z-10 w-full max-w-[440px]">
            
            <div class="bg-uisurface border border-uiborder rounded-[32px] p-8 md:p-10 shadow-[0_20px_60px_rgba(0,0,0,0.05)] overflow-hidden">
                
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-gray-50 rounded-full blur-2xl opacity-60"></div>
                
                <div class="relative z-10">
                    
                    <div class="flex flex-col items-center sm:items-start text-center sm:text-left mb-8 pb-6 border-b border-uiborder">
                        @if($pengaturan && $pengaturan->logo_sekolah)
                            <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo" class="w-14 h-14 object-contain mb-5 drop-shadow-sm">
                        @else
                            <div class="w-14 h-14 bg-uiblack flex items-center justify-center text-white font-black text-xl mb-5 rounded-full shadow-md">M1</div>
                        @endif
                        
                        <h1 class="text-2xl font-black text-uiblack tracking-tight leading-tight">
                            Selamat Datang
                        </h1>
                        <p class="text-[14px] text-uimuted mt-2 font-medium">
                            Masuk ke portal Admin & Guru
                        </p>
                    </div>

                    <div>
                        <x-filament-panels::form wire:submit="authenticate" class="space-y-6">
                            
                            {{ $this->form }}

                            <div class="pt-4">
                                <button type="submit" wire:loading.attr="disabled" class="touch-scale w-full flex justify-center items-center py-4 px-6 rounded-[100px] text-[14px] font-bold text-white bg-uiblack uppercase tracking-wide transition-all shadow-[0_8px_25px_rgba(24,24,27,0.2)] hover:bg-black focus:outline-none focus:ring-4 focus:ring-zinc-200 disabled:opacity-70 disabled:cursor-not-allowed group">
                                    
                                    <span wire:loading.remove wire:target="authenticate" class="flex items-center gap-2">
                                        Masuk Sekarang
                                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    </span>
                                    
                                    <span wire:loading wire:target="authenticate" class="flex items-center gap-3">
                                        Memverifikasi...
                                    </span>
                                </button>
                            </div>
                        </x-filament-panels::form>
                    </div>
                    
                    <div class="mt-8 pt-6 flex justify-center border-t border-uiborder/60">
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-[12px] font-bold uppercase tracking-wider text-uimuted hover:text-uiblack transition-colors">
                            Beranda
                        </a>
                    </div>

                </div>
            </div>

        </div>

        <div class="mt-8 text-center text-[11px] font-bold text-uimuted uppercase tracking-widest">
            Smart-M1 &copy; {{ date('Y') }}
        </div>

    </div>
</x-filament-panels::page.simple>