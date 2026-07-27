<x-filament-panels::page>
    <style>
        /* 1. Mencegah Scroll dan Membunuh Layout Bawaan Admin/Filament */
        body { overflow: hidden !important; }
        .fi-topbar, .fi-sidebar, .fi-header { display: none !important; }
        .fi-main { padding: 0 !important; margin: 0 !important; max-width: 100% !important; background: transparent !important; }
        
        /* 2. OVERLAY LAYAR PENUH: Mengubur sisa-sisa desain Filament */
        #security-overlay {
            position: fixed; inset: 0; z-index: 99999;
            background-color: #e2e8f0;
            display: flex; align-items: center; justify-content: center;
        }
        .dark #security-overlay { background-color: #020617; }

        /* 3. KONTAINER APLIKASI (SAMA PERSIS DENGAN LOGIN & DASBOR) */
        .android-app-container {
            width: 100%; max-width: 414px; height: 100vh; height: 100dvh;
            background-color: #f8fafc;
            display: flex; flex-direction: column; position: relative;
            box-shadow: 0 0 40px rgba(0,0,0,0.15); overflow: hidden;
            font-family: 'Inter', system-ui, sans-serif;
        }
        .dark .android-app-container { background-color: #0f172a; }

        .android-content { flex: 1; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; }
        .android-content::-webkit-scrollbar { display: none; }

        /* 4. MENGUBAH FORM FILAMENT AGAR TERLIHAT SEPERTI APLIKASI */
        .android-app-container .fi-fo-field-wrp label span { color: #1e293b !important; font-weight: 800 !important; font-size: 11px !important; text-transform: uppercase; letter-spacing: 0.5px; }
        .dark .android-app-container .fi-fo-field-wrp label span { color: #94a3b8 !important; }
        
        .android-app-container .fi-input-wrp { border-radius: 16px !important; background-color: #f1f5f9 !important; border: 2px solid transparent !important; box-shadow: none !important; transition: all 0.2s ease; overflow: hidden; }
        .dark .android-app-container .fi-input-wrp { background-color: #1e293b !important; }
        
        .android-app-container .fi-input-wrp:focus-within { border-color: #f59e0b !important; background-color: #ffffff !important; box-shadow: 0 4px 20px rgba(245, 158, 11, 0.1) !important; }
        .dark .android-app-container .fi-input-wrp:focus-within { background-color: #0f172a !important; border-color: #f59e0b !important; }
        
        .android-app-container .fi-input { padding: 16px 20px !important; font-weight: 700 !important; font-size: 14px !important; color: #0f172a !important; }
        .dark .android-app-container .fi-input { color: #f8fafc !important; }
        
        /* Memperbaiki warna teks helper ("Minimal 6 Karakter") */
        .android-app-container .fi-fo-field-wrp p { color: #64748b !important; font-size: 11px !important; font-weight: 600; }
    </style>

    <div id="security-overlay">
        <div class="android-app-container">
            <div class="android-content bg-white dark:bg-gray-800" style="display: flex; flex-direction: column;">
                
                <div style="background: linear-gradient(135deg, #f59e0b, #ea580c); flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 24px 80px 24px; color: white; position: relative; z-index: 10; min-height: 45vh;">
                    
                    <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                        <!-- Ikon Perisai (Shield) -->
                        <div style="width: 80px; height: 80px; border-radius: 24px; border: 3px solid rgba(255,255,255,0.4); background-color: rgba(255,255,255,0.2); backdrop-filter: blur(4px); overflow: hidden; margin-bottom: 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 25px rgba(0,0,0,0.2); padding: 8px;">
                            <x-filament::icon icon="heroicon-s-shield-exclamation" style="width: 44px; height: 44px; color: white;" />
                        </div>

                        <h1 style="font-size: 1.75rem; font-weight: 900; margin: 0 0 6px 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            Keamanan Akun
                        </h1>
                        <div style="display: inline-flex; align-items: center; gap: 6px; background-color: rgba(0,0,0,0.25); padding: 4px 14px; border-radius: 999px; font-size: 11px; font-weight: bold; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px);">
                            Sandi Default Terdeteksi
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800" style="border-radius: 2.5rem 2.5rem 0 0; padding: 32px 32px 48px 32px; margin-top: -40px; position: relative; z-index: 20; box-shadow: 0 -15px 40px rgba(0,0,0,0.15); display: flex; flex-direction: column;">
                    
                    <!-- Garis Indikator Swipe -->
                    <div style="width: 48px; height: 6px; border-radius: 999px; background-color: #cbd5e1; margin: 0 auto 24px auto;" class="dark:bg-gray-700"></div>

                    <p style="text-align: center; font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 24px; line-height: 1.5;" class="dark:text-gray-400">
                        Demi keamanan data nilai dan absensi Anda, silakan buat kata sandi rahasia baru.
                    </p>

                    <form wire:submit="simpan" style="display: flex; flex-direction: column; gap: 24px;">
                        
                        {{ $this->form }}

                        <div style="margin-top: 8px;">
                            <button type="submit" wire:loading.attr="disabled" style="width: 100%; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; border-radius: 16px; padding: 16px; font-size: 14px; font-weight: 800; border: none; box-shadow: 0 8px 20px rgba(37,99,235,0.3); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: transform 0.1s;">
                                
                                <span wire:loading.remove wire:target="simpan" class="flex items-center gap-2">
                                    SIMPAN & MASUK
                                </span>
                                
                                <span wire:loading wire:target="simpan" class="flex items-center gap-2">
                                    <svg style="animation: spin 1s linear infinite; height: 20px; width: 20px; color: white;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </span>
                                
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-filament-panels::page>