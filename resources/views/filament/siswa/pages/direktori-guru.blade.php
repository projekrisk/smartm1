<x-filament-panels::page.simple>
    <div class="w-full h-full">
        
        <div wire:ignore>
            <style>
                .fi-topbar, .fi-sidebar, .fi-header, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
                html, body, .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page, section { 
                    padding: 0 !important; margin: 0 !important; gap: 0 !important; height: 100vh !important; height: 100dvh !important; 
                    max-width: 100% !important; width: 100% !important; overflow: hidden !important; 
                    background-color: #e2e8f0 !important; box-shadow: none !important; border: none !important;
                }
                .dark body, .dark .fi-layout, .dark .fi-simple-layout, .dark .fi-simple-main { background-color: #020617 !important; }
                .android-app-container {
                    width: 100%; max-width: 414px; margin: 0 auto; height: 100vh; height: 100dvh; position: relative; 
                    position: fixed; top: 0; bottom: 0; left: 0; right: 0;
                    display: flex; flex-direction: column; box-shadow: 0 0 40px rgba(0,0,0,0.15); overflow: hidden; 
                    font-family: 'Inter', system-ui, sans-serif; transition: background-color 0.3s ease;
                }
                .theme-bg { background-color: #f8fafc; }
                .dark .theme-bg { background-color: #0f172a; }
                .android-content { flex: 1; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; -ms-overflow-style: none; -webkit-overflow-scrolling: touch; }
                .android-content::-webkit-scrollbar { display: none; }
                
                /* Kustomisasi khusus tabel agar menyatu dengan background aplikasi */
                .fi-ta { background: transparent !important; box-shadow: none !important; border: none !important; }
                .fi-ta-content { background: transparent !important; border: none !important; }
                .fi-ta-header-toolbar { padding: 0 0 10px 0 !important; }
            </style>
        </div>

        <div class="android-app-container theme-bg">
            
            <!-- Header Halaman Berwarna Emerald/Hijau -->
            <div style="flex-shrink: 0; background: linear-gradient(135deg, #10b981, #047857); padding: 40px 24px 60px 24px; color: white; position: relative; z-index: 10; text-align: center;">
                <a href="/siswa" style="position: absolute; top: 32px; left: 20px; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.9)'" onmouseup="this.style.transform='scale(1)'">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                
                <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0; padding-top: 5px; line-height: 1.2; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Direktori Sekolah</h1>
                <p style="font-size: 11px; font-weight: 600; opacity: 0.9; margin-top: 6px; text-transform: uppercase; letter-spacing: 1px;">Daftar Pendidik & Staf</p>
            </div>

            <!-- Area Konten (Render Tabel Disini) -->
            <div class="android-content theme-bg" style="border-top-left-radius: 2.5rem; border-top-right-radius: 2.5rem; margin-top: -30px; padding: 24px 10px 40px 10px; position: relative; z-index: 20; box-shadow: 0 -10px 25px rgba(0,0,0,0.1);">
                
                {{ $this->table }}
                
                <div style="height: 40px;"></div>
            </div>
        </div>

    </div>
</x-filament-panels::page.simple>