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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['"Inter"', 'sans-serif'],
                        },
                        colors: {
                            base: {
                                50: '#F9FAFB',
                                900: '#111827',
                            }
                        }
                    }
                }
            }
        </script>
        <style>
            body { 
                font-family: 'Inter', sans-serif !important; 
                background-color: #F9FAFB !important; 
                color: #111827 !important; 
                margin: 0; padding: 0;
                overflow: hidden !important; 
            }
            
            .bg-structural {
                background-image: 
                    linear-gradient(to right, rgba(17, 24, 39, 0.04) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(17, 24, 39, 0.04) 1px, transparent 1px);
                background-size: 64px 64px;
            }

            .fi-topbar, .fi-sidebar, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page { 
                padding: 0 !important; margin: 0 !important; max-width: 100% !important; 
                background-color: transparent !important; box-shadow: none !important; border: none !important;
            }

            /* Menghapus gap dan padding bawaan section Filament agar menempel ke atas */
            section.grid.auto-cols-fr.gap-y-6, .fi-simple-main-ctn { 
                gap: 0 !important; 
                padding: 0 !important; 
                margin: 0 !important; 
            }

            #single-login .fi-fo-field-wrp label span { color: #111827 !important; font-weight: 800 !important; font-size: 13px !important; text-transform: uppercase; letter-spacing: 0.05em; }
            #single-login .fi-fo-field-wrp p { color: #4B5563 !important; font-size: 12px !important; font-weight: 500 !important;}
            #single-login .fi-fo-field-wrp-error-message { color: #DC2626 !important; font-size: 12px !important; font-weight: 700 !important; }
            
            #single-login .fi-input-wrp {
                background-color: #ffffff !important;
                border: 2px solid #111827 !important; 
                border-radius: 0px !important; 
                box-shadow: none !important;
                transition: all 0.2s ease-out !important;
            }
            
            #single-login .fi-input-wrp:focus-within {
                box-shadow: 4px 4px 0px 0px #111827 !important; 
                transform: translate(-2px, -2px) !important;
            }
            
            #single-login .fi-input { 
                color: #111827 !important; 
                padding: 12px 14px !important; 
                background: transparent !important;
                font-size: 15px !important;
                font-weight: 500 !important;
            }
            
            #single-login input[type="checkbox"],
            #single-login .fi-checkbox-input { 
                appearance: none !important;
                -webkit-appearance: none !important;
                width: 1.25rem !important;
                height: 1.25rem !important;
                border: 2px solid #111827 !important; 
                border-radius: 0px !important; 
                background-color: #ffffff !important;
                cursor: pointer !important;
                display: inline-block !important;
                position: relative !important;
                outline: none !important;
                box-shadow: none !important;
            }
            
            #single-login .fi-checkbox-input:checked {
                background-color: #111827 !important;
                background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e") !important;
            }
        </style>
    </div>

    <div id="single-login" class="min-h-screen w-full flex flex-col items-center justify-center p-4 sm:p-8 relative bg-base-50 bg-structural selection:bg-base-900 selection:text-white">
        
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
                 class="fixed top-6 left-1/2 -translate-x-1/2 z-[999999] flex w-full max-w-sm mx-auto overflow-hidden bg-white border-2 border-base-900 shadow-[4px_4px_0px_0px_#111827] pointer-events-auto">
                <div class="flex items-center justify-center w-12 bg-base-900 shrink-0 text-white font-bold">
                    !
                </div>
                <div class="px-4 py-3 flex-1 min-w-0">
                    <span class="text-[12px] font-extrabold text-base-900 uppercase tracking-widest">Akses Ditolak</span>
                    <p class="text-sm text-gray-700 mt-0.5 font-bold leading-tight truncate">{{ $errors->first() }}</p>
                </div>
                <button @click="show = false" class="absolute top-3 right-3 text-base-900 hover:text-red-600 transition-colors font-bold p-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        <div class="relative z-10 w-full max-w-[460px]">
            
            <div class="bg-white border-2 border-base-900 p-8 md:p-12 relative shadow-[8px_8px_0px_0px_#111827]">
                
                <div class="absolute top-0 right-0 w-12 h-12 border-l-2 border-b-2 border-base-900 bg-base-50 transform translate-x-2 -translate-y-2"></div>
                <div class="absolute bottom-0 left-0 w-12 h-12 border-r-2 border-t-2 border-base-900 bg-base-50 transform -translate-x-2 translate-y-2"></div>
                
                <div class="relative z-10">
                    
                    <div class="flex flex-col items-start mb-8 border-b-2 border-base-900 pb-6">
                        @if($pengaturan && $pengaturan->logo_sekolah)
                            <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo" class="w-16 h-16 object-contain mb-5">
                        @else
                            <div class="w-14 h-14 bg-base-900 flex items-center justify-center text-white font-black text-2xl mb-5">M1</div>
                        @endif
                        
                        <h1 class="text-3xl font-black text-base-900 tracking-tight uppercase leading-[1.1]">
                            Portal <br/> Siswa
                        </h1>
                        <p class="text-sm text-gray-600 mt-3 font-semibold">
                            Masuk ke Akun Anda.
                        </p>
                    </div>

                    <div>
                        <x-filament-panels::form wire:submit="authenticate" class="space-y-6">
                            
                            {{ $this->form }}

                            <div class="pt-4">
                                <button type="submit" wire:loading.attr="disabled" class="w-full flex justify-center items-center py-4 px-6 border-2 border-base-900 text-sm font-black text-white bg-base-900 uppercase tracking-widest hover:bg-white hover:text-base-900 focus:outline-none transition-colors duration-200 group">
                                    
                                    <span wire:loading.remove wire:target="authenticate" class="flex items-center gap-3">
                                        MASUK SEKARANG
                                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    </span>
                                    
                                    <span wire:loading wire:target="authenticate" class="flex items-center gap-3">
                                        <svg class="animate-spin h-5 w-5 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        MEMVERIFIKASI...
                                    </span>
                                </button>
                            </div>
                        </x-filament-panels::form>
                    </div>
                    
                     <div class="mt-8 pt-6 border-t-2 border-dashed border-gray-300">
                        <div class="text-center text-[11px] text-gray-500 font-bold mb-4 uppercase tracking-wider">
                            Sandi awal Anda adalah <strong class="text-base-900">NISN</strong>
                        </div>
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-base-900 hover:text-accent-600 transition-colors hover:underline decoration-2 underline-offset-4">
                            &larr; Kembali ke Beranda
                        </a>
                    </div>

                </div>
            </div>

        </div>

    </div>
</x-filament-panels::page.simple>