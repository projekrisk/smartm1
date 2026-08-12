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
                --ui-accent: #0F172A; /* Slate 900 */
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
                padding-bottom: calc(100px + env(safe-area-inset-bottom, 0px)); 
                scrollbar-width: none; 
            }
            .workspace-content::-webkit-scrollbar { display: none; }

            /* Interactive Elements */
            .touch-scale { transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
            .touch-scale:active { transform: scale(0.96); }

            /* Custom Shadows */
            .ambient-shadow { box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04); }

            /* Data Rows for Accordion */
            .data-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid var(--ui-border); }
            .data-row:last-child { border-bottom: none; }
            .data-label { font-size: 12px; font-weight: 600; color: var(--ui-muted); width: 40%; flex-shrink: 0; }
            .data-val { font-size: 12px; font-weight: 800; color: var(--ui-black); text-align: right; width: 60%; line-height: 1.4; word-break: break-word; }

            /* Filament Form Adjustments within Workspace */
            .workspace-container .fi-fo-field-wrp label span { color: var(--ui-black) !important; font-weight: 800 !important; font-size: 12px !important; text-transform: uppercase; letter-spacing: 0.5px; }
            .workspace-container .fi-input-wrp { border-radius: 16px !important; background-color: var(--ui-surface) !important; border: 1px solid var(--ui-border) !important; box-shadow: none !important; transition: all 0.2s ease; overflow: hidden; }
            .workspace-container .fi-input-wrp:focus-within { border-color: var(--ui-black) !important; box-shadow: 0 4px 12px rgba(24,24,27,0.08) !important; }
            .workspace-container .fi-input { color: var(--ui-black) !important; padding: 14px 16px !important; font-weight: 600 !important; }
            
            /* Overriding Filament Layout for Forms */
            .workspace-container form .grid, 
            .workspace-container form .grid-cols-2,
            .workspace-container form .sm\:grid-cols-2,
            .workspace-container form .md\:grid-cols-2,
            .workspace-container form .lg\:grid-cols-2,
            .workspace-container form .fi-fo-component-ctn {
                display: flex !important;
                flex-direction: column !important;
                gap: 16px !important;
            }
            .workspace-container form > div,
            .workspace-container form .fi-fo-field-wrp {
                width: 100% !important;
                grid-column: span 1 / span 1 !important;
            }

            [x-cloak] { display: none !important; }
        </style>
    </div>

    <div class="workspace-container selection:bg-zinc-900 selection:text-white">
        
        <!-- Minimalist Header -->
        <div style="padding: 24px 20px 16px 20px; display: flex; align-items: center; gap: 16px; margin-top: env(safe-area-inset-top, 0px); background: var(--ui-bg); flex-shrink: 0; z-index: 10; border-bottom: 1px solid rgba(0,0,0,0.02);">
            @if(!$isCreatingNew)
                <a href="/siswa" class="touch-scale" style="width: 44px; height: 44px; border-radius: 50%; background: var(--ui-surface); border: 1px solid var(--ui-border); display: flex; align-items: center; justify-content: center; color: var(--ui-black); box-shadow: 0 2px 8px rgba(0,0,0,0.04); flex-shrink: 0; text-decoration: none;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                
                <div>
                    <h1 style="font-size: 20px; font-weight: 900; color: var(--ui-black); margin: 0; letter-spacing: -0.5px; line-height: 1.2;">PrestasiKu</h1>
                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 4px;">
                        <div style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--ui-black);"></div>
                        <p style="font-size: 12px; font-weight: 600; color: var(--ui-muted); margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">{{ $prestasis->count() }} Data Terdaftar</p>
                    </div>
                </div>
            @else
                <button wire:click="kembaliKeList" class="touch-scale" style="width: 44px; height: 44px; border-radius: 50%; background: var(--ui-surface); border: 1px solid var(--ui-border); display: flex; align-items: center; justify-content: center; color: var(--ui-black); box-shadow: 0 2px 8px rgba(0,0,0,0.04); flex-shrink: 0; border-style: solid; cursor: pointer;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                
                <div>
                    <h1 style="font-size: 20px; font-weight: 900; color: var(--ui-black); margin: 0; letter-spacing: -0.5px; line-height: 1.2;">Ajukan Prestasi</h1>
                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 4px;">
                        <div style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--ui-black);"></div>
                        <p style="font-size: 12px; font-weight: 600; color: var(--ui-muted); margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">Formulir Pengajuan</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="workspace-content">
            <div style="padding: 12px 20px 20px 20px;">
                
                @if(!$isCreatingNew)
                    
                    @if($prestasis->isEmpty())
                        <div class="ambient-shadow" style="text-align: center; padding: 48px 20px; background: var(--ui-surface); border-radius: 24px; border: 1px solid rgba(0,0,0,0.02);">
                            <div style="width: 56px; height: 56px; border-radius: 16px; background-color: var(--ui-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                                <x-filament::icon icon="heroicon-o-trophy" style="width: 28px; height: 28px; color: var(--ui-muted);" />
                            </div>
                            <h3 style="font-weight: 800; font-size: 15px; color: var(--ui-black); margin: 0 0 6px 0;">Belum Ada Prestasi</h3>
                            <p style="font-size: 12px; font-weight: 500; color: var(--ui-muted); line-height: 1.5; margin: 0;">Ajukan sertifikat lomba atau kejuaraan Anda untuk direkam ke dalam sistem.</p>
                        </div>
                    @else
                        
                        <div x-data="{ activeAccordion: null }" style="display: flex; flex-direction: column; gap: 12px;">
                            
                            @foreach($prestasis as $index => $item)
                                @php
                                    $bg = $item->status == 'Disetujui' ? '#D1FAE5' : ($item->status == 'Menunggu' ? '#FFFBEB' : '#FEE2E2');
                                    $tc = $item->status == 'Disetujui' ? '#047857' : ($item->status == 'Menunggu' ? '#D97706' : '#B91C1C');
                                    $bc = $item->status == 'Disetujui' ? '#A7F3D0' : ($item->status == 'Menunggu' ? '#FDE68A' : '#FECACA');
                                @endphp
                                
                                <div class="ambient-shadow" style="background: var(--ui-surface); border-radius: 20px; border: 1px solid rgba(0,0,0,0.02); overflow: hidden;">
                                    
                                    <button @click="activeAccordion = activeAccordion === {{ $index }} ? null : {{ $index }}" 
                                            style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; background: transparent; border: none; cursor: pointer; text-align: left;">
                                        
                                        <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 6px; min-width: 0; padding-right: 12px;">
                                            <h4 style="font-size: 14px; font-weight: 800; color: var(--ui-black); margin: 0; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 100%;">
                                                {{ $item->nama_prestasi }}
                                            </h4>
                                            <span style="font-size: 11px; font-weight: 600; color: var(--ui-muted);">
                                                {{ \Carbon\Carbon::parse($item->tanggal_perolehan)->isoFormat('D MMMM Y') }}
                                            </span>
                                        </div>
                                        
                                        <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                                            <span style="font-size: 9px; font-weight: 800; color: {{ $tc }}; background-color: {{ $bg }}; padding: 4px 10px; border-radius: 8px; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid {{ $bc }};">
                                                {{ $item->status }}
                                            </span>
                                            
                                            <div :class="{'rotate-180': activeAccordion === {{ $index }}, 'transition-transform duration-300': true}" style="color: var(--ui-muted);">
                                                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                    </button>

                                    <div x-show="activeAccordion === {{ $index }}" x-collapse x-cloak>
                                        <div style="padding: 0 20px 20px 20px; display: flex; flex-direction: column;">
                                            
                                            <div style="border-top: 1px solid var(--ui-border); padding-top: 12px; margin-top: 4px;">
                                                <div style="display: flex; flex-direction: column;">
                                                    <div class="data-row">
                                                        <span class="data-label">Peringkat / Juara</span>
                                                        <span class="data-val" style="display: flex; align-items: center; justify-content: flex-end; gap: 4px;">
                                                            <x-filament::icon icon="heroicon-s-star" style="width: 14px; height: 14px; color: #F59E0B;" />
                                                            {{ $item->juara }}
                                                        </span>
                                                    </div>
                                                    <div class="data-row">
                                                        <span class="data-label">Jenis Lomba</span>
                                                        <span class="data-val">{{ $item->jenis }}</span>
                                                    </div>
                                                    <div class="data-row">
                                                        <span class="data-label">Kategori</span>
                                                        <span class="data-val">{{ $item->kategori }}</span>
                                                    </div>
                                                    <div class="data-row">
                                                        <span class="data-label">Tingkat</span>
                                                        <span class="data-val">{{ $item->tingkat }}</span>
                                                    </div>
                                                    @if($item->penyelenggara)
                                                    <div class="data-row">
                                                        <span class="data-label">Penyelenggara</span>
                                                        <span class="data-val">{{ $item->penyelenggara }}</span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            @if($item->status == 'Ditolak' && $item->catatan_admin)
                                                <div style="background-color: #FEF2F2; border: 1px dashed #FCA5A5; padding: 12px; border-radius: 12px; margin-top: 12px;">
                                                    <div style="font-size: 10px; font-weight: 800; color: #DC2626; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Alasan Penolakan:</div>
                                                    <p style="font-size: 12px; color: #991B1B; margin: 0; font-weight: 600; line-height: 1.5;">{{ $item->catatan_admin }}</p>
                                                </div>
                                            @endif

                                            @if($item->bukti_file)
                                                <div style="margin-top: 16px;">
                                                    <a href="{{ url('/uploads/' . $item->bukti_file) }}" target="_blank" download class="touch-scale" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 12px; background-color: var(--ui-bg); color: var(--ui-black); border-radius: 12px; font-size: 12px; font-weight: 800; text-decoration: none; border: 1px solid var(--ui-border);">
                                                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"></path></svg>
                                                        Unduh Sertifikat / Bukti
                                                    </a>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                        </div>

                    @endif

                @else
                    
                    <form wire:submit="kirimPengajuan" style="display: flex; flex-direction: column; gap: 20px;">
                        {{ $this->form }}
                        
                        <div style="margin-top: 8px;">
                            <button type="submit" wire:loading.attr="disabled" class="touch-scale" style="width: 100%; background: var(--ui-black); color: white; border-radius: 100px; padding: 16px; font-weight: 800; font-size: 13px; border: none; box-shadow: 0 4px 20px rgba(24,24,27,0.25); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                
                                <div wire:loading.remove wire:target="kirimPengajuan">
                                    Kirim Pengajuan
                                </div>
                                
                                <div wire:loading.flex wire:target="kirimPengajuan" style="align-items: center; gap: 8px;" x-cloak>
                                    <svg style="animation: spin 1s linear infinite; height: 16px; width: 16px; color: white;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Mengunggah File...
                                </div>
                                
                            </button>
                            <style>@keyframes spin { 100% { transform: rotate(360deg); } }</style>
                        </div>
                    </form>
                    
                @endif
                
            </div>
        </div>

        @if(!$isCreatingNew)
            <!-- Floating Action Button (FAB) -->
            <div style="position: absolute; bottom: calc(24px + env(safe-area-inset-bottom, 0px)); left: 0; right: 0; padding: 0 20px; z-index: 50;">
                <button wire:click="buatPengajuanBaru" class="touch-scale" style="width: 100%; background: var(--ui-black); color: white; border-radius: 100px; padding: 16px; font-weight: 800; font-size: 13px; border: none; box-shadow: 0 4px 20px rgba(24,24,27,0.25); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Ajukan Prestasi Baru
                </button>
            </div>
        @endif

    </div>
</x-filament-panels::page.simple>