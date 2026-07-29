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
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

            body { overflow: hidden !important; background-color: #ffffff !important; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
            .fi-topbar, .fi-sidebar, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page { 
                padding: 0 !important; margin: 0 !important; max-width: 100% !important; 
                background-color: transparent !important; box-shadow: none !important; border: none !important;
            }

            #split-login .fi-fo-field-wrp label span { color: #334155 !important; font-weight: 700 !important; font-size: 13px !important; }
            #split-login .fi-fo-field-wrp p { color: #64748b !important; font-size: 11px !important; }
            #split-login .fi-fo-field-wrp-error-message { color: #ef4444 !important; font-size: 12px !important; }
            
            #split-login .fi-input-wrp {
                background-color: #f8fafc !important;
                border: 1px solid #e2e8f0 !important; 
                border-radius: 0.75rem !important;
                box-shadow: none !important;
                transition: all 0.2s ease !important;
            }
            
            #split-login .fi-input-wrp:focus-within {
                border-color: #0f172a !important;
                background-color: #ffffff !important;
                box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.05) !important;
            }
            
            #split-login .fi-input { 
                color: #0f172a !important; 
                padding: 14px 16px !important; 
                background: transparent !important;
                font-size: 14px !important;
                font-weight: 500 !important;
            }
            
            #split-login input[type="checkbox"],
            #split-login .fi-checkbox-input { 
                appearance: auto !important;
                -webkit-appearance: checkbox !important;
                width: 1.15rem !important;
                height: 1.15rem !important;
                border: 2px solid #cbd5e1 !important; 
                border-radius: 0.25rem !important; 
                background-color: #ffffff !important;
                cursor: pointer !important;
                display: inline-block !important;
                position: relative !important;
                z-index: 10 !important;
            }
            
            #split-login .fi-checkbox-input:checked {
                background-color: #0f172a !important;
                border-color: #0f172a !important;
            }
        </style>
    </div>

    <div id="split-login" class="fixed inset-0 z-[99999] bg-white flex w-full h-full">

        @if($errors->any())
            <div x-data="{ show: true }"
                 x-show="show"
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="-translate-y-5 opacity-0 md:translate-y-0 md:translate-x-10"
                 x-transition:enter-end="translate-y-0 opacity-100 md:translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 x-init="setTimeout(() => show = false, 5000)"
                 class="fixed top-4 left-4 right-4 md:left-auto md:right-6 md:top-6 z-[999999] flex w-auto md:w-full max-w-sm mx-auto md:mx-0 overflow-hidden bg-white rounded-2xl shadow-2xl border border-red-100 pointer-events-auto">
                <div class="flex items-center justify-center w-16 bg-red-500 shrink-0">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="px-4 py-3 flex-1 min-w-0">
                    <span class="text-sm font-extrabold text-red-600 uppercase tracking-wide">Login Gagal</span>
                    <p class="text-xs text-slate-500 mt-1 font-medium leading-relaxed truncate">{{ $errors->first() }}</p>
                </div>
                <button @click="show = false" class="absolute top-3 right-3 text-slate-400 hover:text-slate-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        <div class="hidden lg:flex lg:w-[45%] bg-slate-900 relative flex-col justify-between p-12 overflow-hidden border-r border-slate-800">
            <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-600/20 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-emerald-600/20 rounded-full blur-[100px]"></div>
            
            <div class="relative z-10">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-white/50 hover:text-white transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali
                </a>
            </div>

            <div class="relative z-10 max-w-md">
                <div class="w-16 h-16 bg-white/10 border border-white/20 rounded-2xl flex items-center justify-center p-2 mb-8 backdrop-blur-sm">
                    @if($pengaturan && $pengaturan->logo_sekolah)
                        <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo" class="w-full h-full object-contain">
                    @else
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                    @endif
                </div>
                <h1 class="text-4xl font-bold text-white mb-4 leading-tight">
                    Sistem Manajemen <br>Instansi Terpadu.
                </h1>
                <p class="text-slate-400 leading-relaxed">
                    Portal otorisasi khusus staf pengajar dan tata usaha. Harap jaga kerahasiaan kredensial akses Anda.
                </p>
            </div>
            
            <div class="relative z-10">
                <p class="text-xs text-slate-600">&copy; {{ date('Y') }} {{ $pengaturan->nama_sekolah ?? 'SMART-M1' }}</p>
            </div>
        </div>

        <div class="w-full lg:w-[55%] flex flex-col justify-center px-6 md:px-16 lg:px-24 bg-white relative">
            
            <a href="{{ url('/') }}" class="lg:hidden absolute top-6 left-6 inline-flex items-center gap-2 text-slate-400 hover:text-slate-900 transition-colors text-sm font-bold bg-slate-50 px-4 py-2 rounded-full border border-slate-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Utama
            </a>

            <div class="w-full max-w-md mx-auto">
                <div class="mb-10 text-left">
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Otorisasi Masuk</h2>
                    <p class="text-slate-500 mt-2 font-medium">Masukkan informasi akun staf Anda.</p>
                </div>

                <x-filament-panels::form wire:submit="authenticate" class="space-y-5">
                    
                    {{ $this->form }}

                    <div class="pt-4">
                        <button type="submit" wire:loading.attr="disabled" class="w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 shadow-lg shadow-slate-900/20 hover:shadow-slate-900/30 hover:-translate-y-0.5 transition-all">
                            
                            <span wire:loading.remove wire:target="authenticate" class="flex items-center gap-2 tracking-wide">
                                Masuk
                            </span>
                            
                            <span wire:loading wire:target="authenticate" class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </span>
                        </button>
                    </div>
                </x-filament-panels::form>
            </div>
            
        </div>
    </div>
</x-filament-panels::page.simple>