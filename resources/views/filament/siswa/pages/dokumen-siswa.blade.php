<x-filament-panels::page.simple>
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
                height: 100% !important;
                display: flex; flex-direction: column; box-shadow: 0 0 40px rgba(0,0,0,0.15); overflow: hidden; 
                font-family: 'Inter', system-ui, sans-serif; transition: background-color 0.3s ease; padding-bottom: env(safe-area-inset-bottom);
            }
            .theme-bg { background-color: #f8fafc; }
            .theme-card { background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 8px 30px rgba(0,0,0,0.04); }
            .theme-text { color: #0f172a; }
            .theme-text-muted { color: #64748b; }
            .dark .theme-bg { background-color: #0f172a; }
            .dark .theme-card { background-color: #1e293b; border: 1px solid #334155; box-shadow: 0 8px 30px rgba(0,0,0,0.2); }
            .dark .theme-text { color: #f8fafc; }
            .dark .theme-text-muted { color: #94a3b8; }
            .android-content { flex: 1; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; }
            .android-content::-webkit-scrollbar { display: none; }
        </style>
    </div>

    <div class="android-app-container theme-bg">
        <div style="flex-shrink: 0; background: linear-gradient(135deg, #2563eb, #3730a3); padding: 40px 24px 60px 24px; color: white; position: relative; z-index: 10;">
            <a href="/siswa" style="position: absolute; top: 32px; left: 20px; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <div style="text-align: center; margin-top: 4px;">
                <p style="font-size: 10px; font-weight: 800; letter-spacing: 1px; color: #cffafe; text-transform: uppercase; margin-bottom: 8px;">Informasi Publik</p>
                <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0; line-height: 1.2;">E-Dokumen</h1>
                <div style="display: inline-flex; align-items: center; gap: 6px; background-color: rgba(0,0,0,0.25); padding: 4px 14px; border-radius: 999px; font-size: 10px; font-weight: bold; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px); margin-top: 10px; text-transform: uppercase;">{{ $dokumens->count() }} Arsip</div>
            </div>
        </div>

        <div class="android-content theme-bg" style="border-top-left-radius: 2.5rem; border-top-right-radius: 2.5rem; margin-top: -30px; padding: 32px 20px 40px 20px; position: relative; z-index: 20; box-shadow: 0 -10px 25px rgba(0,0,0,0.1);">
            
            <div style="display: flex; flex-direction: column; gap: 16px;">
                @forelse($dokumens as $dokumen)
                    <div class="theme-card" style="border-radius: 20px; padding: 20px; position: relative;">
                        <div style="display: flex; gap: 16px;">
                            <div style="width: 48px; height: 48px; border-radius: 14px; background-color: rgba(6, 182, 212, 0.1); color: #06b6d4; display: flex; align-items: center; justify-content: center; flex-shrink: 0;" class="dark:bg-cyan-900/30 dark:text-cyan-400">
                                @if($dokumen->jenis_sumber === 'File')
                                    <x-filament::icon icon="heroicon-s-document-arrow-down" style="width: 24px; height: 24px;" />
                                @else
                                    <x-filament::icon icon="heroicon-s-link" style="width: 24px; height: 24px;" />
                                @endif
                            </div>
                            
                            <div style="flex: 1; min-width: 0;">
                                <h4 class="theme-text" style="font-weight: 900; font-size: 14px; margin: 0 0 4px 0; line-height: 1.3;">{{ $dokumen->judul }}</h4>
                                <p class="theme-text-muted" style="font-size: 12px; margin: 0; line-height: 1.5;">{{ $dokumen->keterangan ?? 'Tidak ada keterangan.' }}</p>
                                <span style="display: block; margin-top: 8px; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">{{ $dokumen->created_at->isoFormat('D MMMM Y') }}</span>
                            </div>
                        </div>

                        <div style="margin-top: 16px; border-top: 1px dashed rgba(0,0,0,0.1);" class="dark:border-white/10">
                            <a href="{{ $dokumen->jenis_sumber === 'File' ? url('/uploads/' . $dokumen->file_path) : $dokumen->url_link }}" target="_blank" style="display: flex; align-items: center; justify-content: center; width: 100%; padding: 12px; background-color: #06b6d4; color: white; border-radius: 12px; font-size: 12px; font-weight: 900; text-decoration: none; transition: transform 0.1s; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);" class="active:scale-[0.98]">
                                {{ $dokumen->jenis_sumber === 'File' ? 'UNDUH DOKUMEN' : 'BUKA TAUTAN' }}
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 40px 20px;">
                        <div style="width: 64px; height: 64px; border-radius: 20px; background-color: rgba(6, 182, 212, 0.1); color: #06b6d4; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;" class="dark:bg-slate-800">
                            <x-filament::icon icon="heroicon-s-folder-open" style="width: 32px; height: 32px;" />
                        </div>
                        <h3 class="theme-text" style="font-weight: 900; font-size: 16px; margin: 0 0 8px 0;">Belum Ada Arsip</h3>
                        <p class="theme-text-muted" style="font-size: 12px; font-weight: 600; line-height: 1.5; margin: 0;">Sekolah belum mengunggah dokumen edaran atau modul.</p>
                    </div>
                @endforelse
            </div>
            
        </div>
    </div>
</x-filament-panels::page.simple>