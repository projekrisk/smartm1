<x-filament-panels::page.simple>
    @php
        $pengaturan = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) {
                $pengaturan = \App\Models\Pengaturan::first();
            }
        } catch (\Exception $e) {}
    @endphp

    @if($pengaturan && $pengaturan->logo_sekolah)
        <link rel="icon" href="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" type="image/x-icon"/>
    @endif

    <div wire:ignore>
        <script>
            const metaThemeColor = document.createElement('meta');
            metaThemeColor.name = 'theme-color';
            metaThemeColor.content = '#F5F5F7';
            document.head.appendChild(metaThemeColor);
        </script>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;0,9..40,900&display=swap" rel="stylesheet">
        
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

            .workspace-content { 
                flex: 1; overflow-y: auto; overflow-x: hidden; 
                padding-bottom: calc(40px + env(safe-area-inset-bottom, 0px)); 
                scrollbar-width: none; 
            }
            .workspace-content::-webkit-scrollbar { display: none; }

            .touch-scale { transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
            .touch-scale:active { transform: scale(0.96); }

            .ambient-shadow { box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04); }
            
            [x-cloak] { display: none !important; }
        </style>
    </div>

    <div class="workspace-container selection:bg-zinc-900 selection:text-white">
        
        <div style="padding: 24px 20px 16px 20px; display: flex; align-items: center; gap: 16px; margin-top: env(safe-area-inset-top, 0px); background: var(--ui-bg); flex-shrink: 0; z-index: 10; border-bottom: 1px solid rgba(0,0,0,0.02);">
            <a href="/siswa" class="touch-scale" style="width: 44px; height: 44px; border-radius: 50%; background: var(--ui-surface); border: 1px solid var(--ui-border); display: flex; align-items: center; justify-content: center; color: var(--ui-black); box-shadow: 0 2px 8px rgba(0,0,0,0.04); flex-shrink: 0; text-decoration: none;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <div style="flex: 1;">
                <h1 style="font-size: 20px; font-weight: 900; color: var(--ui-black); margin: 0; letter-spacing: -0.5px; line-height: 1.2;">E-Dokumen</h1>
                <div style="display: flex; align-items: center; gap: 6px; margin-top: 4px;">
                    <div style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--ui-black);"></div>
                    <p style="font-size: 12px; font-weight: 600; color: var(--ui-muted); margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">{{ $dokumens->count() }} Arsip Tersedia</p>
                </div>
            </div>
        </div>

        <div class="workspace-content">
            <div style="padding: 12px 20px 24px 20px; display: flex; flex-direction: column; gap: 12px;">
                
                @if($dokumens->isEmpty())
                    <div style="text-align: center; padding: 48px 20px; border: 1px dashed var(--ui-border); border-radius: 24px; margin-top: 12px;">
                        <div style="width: 56px; height: 56px; border-radius: 50%; background-color: var(--ui-surface); color: var(--ui-muted); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
                            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        </div>
                        <h4 style="color: var(--ui-black); font-size: 14px; font-weight: 800; margin: 0 0 4px 0;">Belum Ada Arsip</h4>
                        <p style="color: var(--ui-muted); font-size: 12px; font-weight: 500; margin: 0;">Sekolah belum mengunggah dokumen atau surat edaran.</p>
                    </div>
                @else
                    @foreach($dokumens as $dokumen)
                        <div class="ambient-shadow" style="background: var(--ui-surface); border-radius: 20px; padding: 16px; border: 1px solid rgba(0,0,0,0.02);">
                            <div style="display: flex; gap: 14px;">
                                <div style="width: 40px; height: 40px; border-radius: 12px; background-color: var(--ui-bg); color: var(--ui-black); display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--ui-border);">
                                    @if($dokumen->jenis_sumber === 'File')
                                        <x-filament::icon icon="heroicon-s-document-arrow-down" style="width: 20px; height: 20px;" />
                                    @else
                                        <x-filament::icon icon="heroicon-s-link" style="width: 20px; height: 20px;" />
                                    @endif
                                </div>
                                
                                <div style="flex: 1; min-width: 0;">
                                    <h4 style="font-weight: 800; font-size: 14px; color: var(--ui-black); margin: 0 0 4px 0; line-height: 1.3;">{{ $dokumen->judul }}</h4>
                                    <p style="font-size: 12px; color: var(--ui-muted); margin: 0; line-height: 1.5; font-weight: 500;">{{ $dokumen->keterangan ?? 'Tidak ada keterangan.' }}</p>
                                </div>
                            </div>

                            <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid var(--ui-border); display: flex; align-items: center; justify-content: space-between;">
                                <span style="font-size: 10px; font-weight: 700; color: var(--ui-muted);">{{ $dokumen->created_at->isoFormat('D MMMM Y') }}</span>
                                
                                <a href="{{ $dokumen->jenis_sumber === 'File' ? url('/uploads/' . $dokumen->file_path) : $dokumen->url_link }}" target="_blank" class="touch-scale" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background-color: var(--ui-black); color: white; border-radius: 100px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; text-decoration: none;">
                                    @if($dokumen->jenis_sumber === 'File')
                                        Unduh File
                                    @else
                                        Buka Tautan
                                    @endif
                                    <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            
        </div>
    </div>
</x-filament-panels::page.simple>