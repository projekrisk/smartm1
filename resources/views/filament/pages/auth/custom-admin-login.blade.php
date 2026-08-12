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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['"Inter"', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
        <style>
            body { 
                overflow: hidden !important; 
                background-color: #f8fafc !important; 
                margin: 0; padding: 0; 
                font-family: 'Inter', sans-serif !important; 
            }
            
            /* Dot Pattern Background */
            .bg-dot-pattern {
                background-image: radial-gradient(circle, #cbd5e1 1px, transparent 1px);
                background-size: 24px 24px;
            }

            /* Hide Default Filament Elements */
            .fi-topbar, .fi-sidebar, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page { 
                padding: 0 !important; margin: 0 !important; max-width: 100% !important; 
                background-color: transparent !important; box-shadow: none !important; border: none !important;
            }

            #single-login .fi-fo-field-wrp label span { color: #0f172a !important; font-weight: 600 !important; font-size: 13px !important; }
            #single-login .fi-fo-field-wrp p { color: #64748b !important; font-size: 12px !important; }
            #single-login .fi-fo-field-wrp-error-message { color: #ef4444 !important; font-size: 12px !important; font-weight: 500 !important; }
            
            /* Sharp borders for inputs matching the homepage */
            #single-login .fi-input-wrp {
                background-color: #ffffff !important;
                border: 1px solid #cbd5e1 !important; 
                border-radius: 0.375rem !important; /* Straighter, less rounded edges */
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
                transition: all 0.2s ease !important;
            }
            
            #single-login .fi-input-wrp:focus-within {
                border-color: #0f172a !important;
                box-shadow: 0 0 0 1px #0f172a !important; /* Sharp solid focus ring */
            }
            
            #single-login .fi-input { 
                color: #0f172a !important; 
                padding: 10px 14px !important; 
                background: transparent !important;
                font-size: 14px !important;
            }
            
            #single-login input[type="checkbox"],
            #single-login .fi-checkbox-input { 
                appearance: auto !important;
                -webkit-appearance: checkbox !important;
                width: 1.15rem !important;
                height: 1.15rem !important;
                border: 1px solid #94a3b8 !important; 
                border-radius: 0.25rem !important; 
                background-color: #ffffff !important;
                cursor: pointer !important;
                display: inline-block !important;
                position: relative !important;
                z-index: 10 !important;
            }
            
            #single-login .fi-checkbox-input:checked {
                background-color: #0f172a !important;
                border-color: #0f172a !important;
            }
        </style>
    </div>

    <div id="single-login" class="min-h-screen w-full flex flex-col items-center justify-center p-4 sm:p-8 relative bg-slate-50 selection:bg-slate-900 selection:text-white">
        
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-dot-pattern opacity-60 z-0 pointer-events-none"></div>

        @if($errors->any())
            <div x-data="{ show: true }"
                 x-show="show"
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="-translate-y-5 opacity-0 scale-95"
                 x-transition:enter-end="translate-y-0 opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 x-init="setTimeout(() => show = false, 5000)"
                 class="fixed top-6 left-1/2 -translate-x-1/2 z-[999999] flex w-full max-w-sm mx-auto overflow-hidden bg-white shadow-xl border border-slate-200 pointer-events-auto">
                <div class="flex items-center justify-center w-12 bg-red-600 shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="px-4 py-3 flex-1 min-w-0">
                    <span class="text-[11px] font-bold text-red-600 uppercase tracking-wider">Akses Ditolak</span>
                    <p class="text-sm text-slate-700 mt-0.5 font-medium leading-tight truncate">{{ $errors->first() }}</p>
                </div>
                <button @click="show = false" class="absolute top-3 right-3 text-slate-400 hover:text-slate-900 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        <div class="relative z-10 w-full max-w-[420px] bg-white border border-slate-200 shadow-xl shadow-slate-200/50 p-8 sm:p-10 flex flex-col">
            
            <div class="flex flex-col items-center text-center mb-8">
                <!-- Logo -->
                <div class="w-14 h-14 bg-white border border-slate-200 flex items-center justify-center p-2 mb-5 shadow-sm">
                    @if($pengaturan && $pengaturan->logo_sekolah)
                        <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo" class="w-full h-full object-contain">
                    @else
                        <span class="text-slate-900 font-bold text-xl">M1</span>
                    @endif
                </div>
                
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight mb-2">
                    Otorisasi Sistem
                </h1>
                <p class="text-sm text-slate-500">
                    Masukkan kredensial Anda untuk mengakses panel.
                </p>
            </div>

            <div>
                <x-filament-panels::form wire:submit="authenticate" class="space-y-6">
                    
                    {{ $this->form }}

                    <div class="pt-2">
                        <!-- Solid, sharp button matching the homepage styling -->
                        <button type="submit" wire:loading.attr="disabled" class="w-full flex justify-center items-center py-3 px-4 border border-transparent text-sm font-semibold text-white bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-colors duration-200">
                            
                            <span wire:loading.remove wire:target="authenticate" class="flex items-center gap-2">
                                Autentikasi Masuk
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </span>
                            
                            <span wire:loading wire:target="authenticate" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Memverifikasi...
                            </span>
                        </button>
                    </div>
                </x-filament-panels::form>
            </div>
            
            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 text-slate-500 hover:text-slate-900 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali ke Beranda
                </a>
            </div>

        </div>

    </div>
</x-filament-panels::page.simple>