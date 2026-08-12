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
            // Memaksa warna status bar di mobile agar senada dengan background aplikasi
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
                --ui-bg: #F5F5F7; /* Apple light gray */
                --ui-surface: #FFFFFF;
                --ui-black: #18181B; /* Zinc 900 */
                --ui-text: #27272A; /* Zinc 800 */
                --ui-muted: #71717A; /* Zinc 500 */
                --ui-border: #E4E4E7; /* Zinc 200 */
            }

            body { 
                font-family: 'DM Sans', sans-serif !important; 
                overflow: hidden !important; 
                background-color: var(--ui-bg) !important; 
                color: var(--ui-text) !important;
                -webkit-font-smoothing: antialiased;
                margin: 0; padding: 0;
            }

            /* Hide Filament default UI elements completely */
            .fi-topbar, .fi-sidebar, .fi-header, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            html, body, .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page, section { 
                padding: 0 !important; margin: 0 !important; gap: 0 !important;
                height: 100vh !important; height: 100dvh !important; 
                max-width: 100% !important; width: 100% !important; 
                background-color: transparent !important; box-shadow: none !important; border: none !important;
            }

            /* Main Mobile Workspace */
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

            /* Interactive Elements */
            .touch-scale { transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
            .touch-scale:active { transform: scale(0.96); }

            /* Custom Shadows */
            .ambient-shadow { box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04); }

            /* Badges for Categories */
            .badge-cat { 
                padding: 4px 10px; border-radius: 6px; font-size: 9px; font-weight: 800; 
                text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid transparent;
            }
            .badge-pelanggaran { background-color: #FEF2F2; color: #DC2626; border-color: #FEE2E2; }
            .badge-peringatan { background-color: #FFFBEB; color: #D97706; border-color: #FEF3C7; }
            .badge-prestasi { background-color: #EEF2FF; color: #4F46E5; border-color: #E0E7FF; }
            .badge-pembinaan { background-color: #F0FDF4; color: #059669; border-color: #D1FAE5; }
            .badge-default { background-color: var(--ui-surface); color: var(--ui-text); border-color: var(--ui-border); }
            
            [x-cloak] { display: none !important; }
        </style>
    </div>

    <div class="workspace-container selection:bg-zinc-900 selection:text-white">
        
        <!-- Minimalist Header -->
        <div style="padding: 24px 20px 16px 20px; display: flex; align-items: center; gap: 16px; margin-top: env(safe-area-inset-top, 0px); background: var(--ui-bg); flex-shrink: 0; z-index: 10; border-bottom: 1px solid rgba(0,0,0,0.02);">
            <a href="/siswa" class="touch-scale" style="width: 44px; height: 44px; border-radius: 50%; background: var(--ui-surface); border: 1px solid var(--ui-border); display: flex; align-items: center; justify-content: center; color: var(--ui-black); box-shadow: 0 2px 8px rgba(0,0,0,0.04); flex-shrink: 0; text-decoration: none;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <div>
                <h1 style="font-size: 20px; font-weight: 900; color: var(--ui-black); margin: 0; letter-spacing: -0.5px; line-height: 1.2;">Catatan Siswa</h1>
                <div style="display: flex; align-items: center; gap: 6px; margin-top: 4px;">
                    <div style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--ui-black);"></div>
                    <p style="font-size: 12px; font-weight: 600; color: var(--ui-muted); margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">{{ $catatans->count() }} Riwayat Tercatat</p>
                </div>
            </div>
        </div>

        <div class="workspace-content">
            <div style="padding: 12px 20px 20px 20px;">
                
                @if($catatans->isEmpty())
                    <div class="ambient-shadow" style="text-align: center; padding: 48px 20px; background: var(--ui-surface); border-radius: 24px; border: 1px solid rgba(0,0,0,0.02);">
                        <div style="width: 56px; height: 56px; border-radius: 16px; background-color: var(--ui-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                            <x-filament::icon icon="heroicon-o-bookmark" style="width: 28px; height: 28px; color: var(--ui-muted);" />
                        </div>
                        <h3 style="font-weight: 800; font-size: 15px; color: var(--ui-black); margin: 0 0 6px 0;">Belum Ada Catatan</h3>
                        <p style="font-size: 12px; font-weight: 500; color: var(--ui-muted); line-height: 1.5; margin: 0;">Tidak ada catatan khusus, pelanggaran, atau pembinaan yang direkam untuk Anda saat ini.</p>
                    </div>
                @else
                    
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        @foreach($catatans as $catatan)
                            @php
                                $kategori = strtolower($catatan->kategori ?? '');
                                $badgeClass = 'badge-default';
                                
                                if(str_contains($kategori, 'pelanggaran')) {
                                    $badgeClass = 'badge-pelanggaran';
                                } elseif(str_contains($kategori, 'peringatan')) {
                                    $badgeClass = 'badge-peringatan';
                                } elseif(str_contains($kategori, 'prestasi')) {
                                    $badgeClass = 'badge-prestasi';
                                } elseif(str_contains($kategori, 'pembinaan')) {
                                    $badgeClass = 'badge-pembinaan';
                                }
                            @endphp

                            <div class="ambient-shadow" style="background: var(--ui-surface); border-radius: 20px; padding: 16px; border: 1px solid rgba(0,0,0,0.02);">
                                
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                                    <span class="badge-cat {{ $badgeClass }}">
                                        {{ $catatan->kategori ?? 'Umum' }}
                                    </span>
                                    <span style="font-size: 10px; font-weight: 700; color: var(--ui-muted);">
                                        {{ \Carbon\Carbon::parse($catatan->tanggal)->format('d/m/Y') }}
                                    </span>
                                </div>
                                
                                <p style="font-size: 13px; font-weight: 600; color: var(--ui-black); margin: 0 0 16px 0; line-height: 1.5;">
                                    {{ $catatan->catatan }}
                                </p>
                                
                                <div style="display: flex; align-items: center; gap: 8px; border-top: 1px solid var(--ui-border); padding-top: 12px;">
                                    <div style="width: 24px; height: 24px; border-radius: 50%; background-color: var(--ui-bg); display: flex; align-items: center; justify-content: center;">
                                        <x-filament::icon icon="heroicon-s-user" style="width: 12px; height: 12px; color: var(--ui-muted);" />
                                    </div>
                                    <div style="display: flex; flex-direction: column;">
                                        <span style="font-size: 9px; font-weight: 800; color: var(--ui-muted); text-transform: uppercase; letter-spacing: 0.5px;">Dicatat Oleh</span>
                                        <span style="font-size: 11px; font-weight: 700; color: var(--ui-black);">{{ $catatan->guru->name ?? 'Administrator' }}</span>
                                    </div>
                                </div>
                                
                            </div>
                        @endforeach
                    </div>
                    
                @endif
                
            </div>
        </div>

    </div>
</x-filament-panels::page.simple>