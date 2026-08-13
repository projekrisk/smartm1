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
                scrollbar-width: none; -ms-overflow-style: none; -webkit-overflow-scrolling: touch;
            }
            .workspace-content::-webkit-scrollbar { display: none; }

            .touch-scale { transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
            .touch-scale:active { transform: scale(0.96); }

            .ambient-shadow { box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04); }

            .status-btn { 
                padding: 14px; text-align: center; border-radius: 16px; border: 2px solid transparent; 
                font-weight: 800; font-size: 13px; transition: all 0.2s; 
                background-color: #F4F4F5; color: var(--ui-muted); 
            }
            .status-btn.active-hadir { background-color: #D1FAE5 !important; border-color: #10B981 !important; color: #047857 !important; }
            .status-btn.active-sakit { background-color: #FEF3C7 !important; border-color: #F59E0B !important; color: #B45309 !important; }
            .status-btn.active-izin { background-color: #E0E7FF !important; border-color: #6366F1 !important; color: #4338CA !important; }
            .status-btn.active-alpa { background-color: #FEE2E2 !important; border-color: #EF4444 !important; color: #B91C1C !important; }

            .badge-status { 
                min-width: 65px; text-align: center; padding: 4px 10px; border-radius: 8px; 
                font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; 
                display: inline-block; box-shadow: 0 2px 8px rgba(0,0,0,0.03); border: 1px solid transparent;
            }
            .badge-sakit { background-color: #FFFBEB; color: #D97706; border-color: #FEF3C7; }
            .badge-izin { background-color: #EEF2FF; color: #4F46E5; border-color: #E0E7FF; }
            .badge-alpa { background-color: #FEF2F2; color: #DC2626; border-color: #FEE2E2; }
            .badge-dispensasi { background-color: var(--ui-bg); color: var(--ui-muted); border-color: var(--ui-border); }
            
            [x-cloak] { display: none !important; }
        </style>
    </div>

    <div class="workspace-container selection:bg-zinc-900 selection:text-white" x-data="{ activeModal: null }">
        
        <form wire:submit="simpan" style="display: flex; flex-direction: column; height: 100%; width: 100%; position: relative;">
            
            <div style="padding: 24px 20px 16px 20px; display: flex; align-items: center; gap: 16px; margin-top: env(safe-area-inset-top, 0px); background: var(--ui-bg); flex-shrink: 0; z-index: 10;">
                <a href="/siswa" class="touch-scale" style="width: 44px; height: 44px; border-radius: 50%; background: var(--ui-surface); border: 1px solid var(--ui-border); display: flex; align-items: center; justify-content: center; color: var(--ui-black); box-shadow: 0 2px 8px rgba(0,0,0,0.04); flex-shrink: 0; text-decoration: none;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                
                <div style="flex: 1; min-width: 0;">
                    <h1 style="font-size: 20px; font-weight: 900; color: var(--ui-black); margin: 0 0 2px 0; letter-spacing: -0.5px; line-height: 1.2;">Absensi Kelas</h1>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <div style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--ui-black);"></div>
                        <p style="font-size: 11px; font-weight: 700; color: var(--ui-muted); margin: 0; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $namaKelas }} • {{ $tanggalIndo }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="workspace-content">
                <div style="padding: 8px 20px 40px 20px;">
                    
                    @if($isLocked)
                        <div class="ambient-shadow" style="background-color: #FFFBEB; color: #92400E; padding: 16px; border-radius: 20px; margin-bottom: 24px; font-size: 12px; font-weight: 600; display: flex; align-items: flex-start; gap: 12px; border: 1px solid #FEF3C7;">
                            <x-filament::icon icon="heroicon-s-lock-closed" style="width: 20px; height: 20px; color: #D97706; flex-shrink: 0; margin-top: 2px;" />
                            <span style="line-height: 1.5;">Data absensi untuk hari ini telah dikunci secara permanen oleh Admin Tata Usaha.</span>
                        </div>
                    @else
                        <div style="margin-bottom: 20px;">
                            <p style="font-size: 11px; font-weight: 800; color: var(--ui-muted); text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Pilih siswa yang tidak hadir</p>
                        </div>
                    @endif

                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach($absensi as $index => $item)
                            @php
                                $isDispensasi = $item['is_dispensasi'] ?? false;
                            @endphp
                            
                            <div wire:key="siswa-row-{{ $item['siswa_id'] }}" x-data="{ localStatus: '{{ $item['status'] }}' }">
                                
                                <div class="ambient-shadow touch-scale" 
                                     style="background: var(--ui-surface); border-radius: 20px; padding: 14px; border: 1px solid rgba(0,0,0,0.02); display: flex; align-items: center; justify-content: space-between; {{ $isDispensasi || $isLocked ? 'opacity: 0.7;' : 'cursor: pointer;' }}"
                                     @if(!$isLocked && !$isDispensasi) @click="activeModal = {{ $index }}" @endif>
                                    
                                    <div style="display: flex; align-items: center; gap: 14px; overflow: hidden; flex: 1; min-width: 0;">
                                        
                                        @if(!empty($item['foto']))
                                            <img src="{{ url('/uploads/' . $item['foto']) }}" 
                                                 style="flex-shrink: 0; width: 44px; height: 44px; border-radius: 12px; object-fit: cover; border: 1px solid var(--ui-border);" 
                                                 onerror="this.outerHTML='<div style=\'flex-shrink: 0; width: 44px; height: 44px; border-radius: 12px; background-color: var(--ui-bg); color: var(--ui-black); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 15px; border: 1px solid var(--ui-border);\'>{{ substr($item['nama'], 0, 1) }}</div>'">
                                        @else
                                            <div style="flex-shrink: 0; width: 44px; height: 44px; border-radius: 12px; background-color: var(--ui-bg); color: var(--ui-black); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 15px; border: 1px solid var(--ui-border);">
                                                {{ substr($item['nama'], 0, 1) }}
                                            </div>
                                        @endif

                                        <div style="display: flex; flex-direction: column; min-width: 0;">
                                            <span style="font-weight: 800; font-size: 14px; color: var(--ui-black); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; width: 100%;">{{ $item['nama'] }}</span>
                                            
                                            @if($isDispensasi)
                                                <span style="font-size: 11px; font-weight: 600; margin-top: 2px; color: #0284C7; display: flex; align-items: center; gap: 4px;">
                                                    <svg style="width: 12px; height: 12px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                                    {{ $item['keterangan'] }}
                                                </span>
                                            @else
                                                <span style="font-size: 11px; font-weight: 600; color: var(--ui-muted); margin-top: 2px;">NISN: {{ $item['nisn'] ?? $item['nis'] }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div style="flex-shrink: 0; margin-left: 12px; min-width: 65px; display: flex; justify-content: flex-end;">
                                        <div x-show="localStatus === 'Hadir'" x-cloak style="color: var(--ui-border);">
                                            <svg style="width: 26px; height: 26px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        </div>
                                        <div x-show="localStatus !== 'Hadir'" x-cloak class="badge-status" 
                                             style="display: flex; align-items: center; justify-content: center; gap: 4px;"
                                             :class="{ 'badge-sakit': localStatus === 'Sakit', 'badge-izin': localStatus === 'Izin', 'badge-alpa': localStatus === 'Alpa', 'badge-dispensasi': localStatus === 'Dispensasi' }">
                                            <span x-text="localStatus"></span>
                                        </div>
                                    </div>
                                </div>

                                <div x-show="activeModal === {{ $index }}" x-cloak 
                                     style="position: fixed; inset: 0; z-index: 99999; background-color: rgba(0,0,0,0.4); backdrop-filter: blur(4px);"
                                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                    
                                    <div @click.away="activeModal = null" 
                                         style="position: absolute; bottom: 0; left: 0; right: 0; background-color: var(--ui-surface); border-radius: 28px 28px 0 0; padding: 24px 24px calc(24px + env(safe-area-inset-bottom, 0px)) 24px; box-shadow: 0 -20px 40px rgba(0,0,0,0.1);"
                                         x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                                         x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                                         
                                         <div style="width: 40px; height: 5px; border-radius: 100px; background-color: var(--ui-border); margin: 0 auto 24px auto;"></div>

                                         <div style="text-align: center; margin-bottom: 24px;">
                                            @if(!empty($item['foto']))
                                                <img src="{{ url('/uploads/' . $item['foto']) }}" 
                                                     style="width: 56px; height: 56px; border-radius: 16px; object-fit: cover; display: block; margin: 0 auto 12px auto; border: 1px solid var(--ui-border);" 
                                                     onerror="this.outerHTML='<div style=\'width: 56px; height: 56px; border-radius: 16px; background-color: var(--ui-bg); color: var(--ui-black); display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800; margin: 0 auto 12px auto; border: 1px solid var(--ui-border);\'>{{ substr($item['nama'], 0, 1) }}</div>'">
                                            @else
                                                <div style="width: 56px; height: 56px; border-radius: 16px; background-color: var(--ui-bg); color: var(--ui-black); display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800; margin: 0 auto 12px auto; border: 1px solid var(--ui-border);">
                                                    {{ substr($item['nama'], 0, 1) }}
                                                </div>
                                            @endif

                                            <h3 style="font-weight: 900; font-size: 18px; color: var(--ui-black); line-height: 1.2; margin: 0;">{{ $item['nama'] }}</h3>
                                            <p style="font-size: 11px; font-weight: 700; color: var(--ui-muted); margin-top: 6px; letter-spacing: 0.5px; text-transform: uppercase;">Ubah Status Kehadiran</p>
                                         </div>

                                         <div style="display: flex; flex-direction: column; gap: 20px;">
                                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                                                <label style="cursor: pointer; display: block;">
                                                    <input type="radio" x-model="localStatus" wire:model="absensi.{{ $index }}.status" value="Hadir" style="display: none;">
                                                    <div class="status-btn" :class="localStatus === 'Hadir' ? 'active-hadir' : ''">Hadir</div>
                                                </label>
                                                <label style="cursor: pointer; display: block;">
                                                    <input type="radio" x-model="localStatus" wire:model="absensi.{{ $index }}.status" value="Sakit" style="display: none;">
                                                    <div class="status-btn" :class="localStatus === 'Sakit' ? 'active-sakit' : ''">Sakit</div>
                                                </label>
                                                <label style="cursor: pointer; display: block;">
                                                    <input type="radio" x-model="localStatus" wire:model="absensi.{{ $index }}.status" value="Izin" style="display: none;">
                                                    <div class="status-btn" :class="localStatus === 'Izin' ? 'active-izin' : ''">Izin</div>
                                                </label>
                                                <label style="cursor: pointer; display: block;">
                                                    <input type="radio" x-model="localStatus" wire:model="absensi.{{ $index }}.status" value="Alpa" style="display: none;">
                                                    <div class="status-btn" :class="localStatus === 'Alpa' ? 'active-alpa' : ''">Alpa</div>
                                                </label>
                                            </div>
                                            
                                            <div>
                                                <label style="display: block; font-size: 11px; font-weight: 700; color: var(--ui-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; margin-left: 4px;">Catatan (Opsional)</label>
                                                <input wire:model.defer="absensi.{{ $index }}.keterangan" type="text" 
                                                       style="width: 100%; border: 1px solid var(--ui-border); border-radius: 16px; padding: 14px 16px; font-size: 14px; font-weight: 600; outline: none; transition: all 0.2s; background: var(--ui-surface); color: var(--ui-black);" 
                                                       onfocus="this.style.borderColor='var(--ui-black)'; this.style.boxShadow='0 4px 12px rgba(24,24,27,0.08)';"
                                                       onblur="this.style.borderColor='var(--ui-border)'; this.style.boxShadow='none';"
                                                       placeholder="Cth: Surat dokter menyusul">
                                            </div>
                                         </div>

                                         <button @click="activeModal = null" type="button" style="margin-top: 24px; width: 100%; padding: 16px; background: var(--ui-black); color: white; border-radius: 16px; font-weight: 800; font-size: 13px; border: none; cursor: pointer; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.98)'" onmouseup="this.style.transform='scale(1)'">
                                             Selesai & Tutup
                                         </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if(!$isLocked)
                <div style="flex-shrink: 0; padding: 16px 20px calc(16px + env(safe-area-inset-bottom, 0px)) 20px; background: rgba(245,245,247,0.85); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-top: 1px solid rgba(0,0,0,0.05); z-index: 30;">
                    <button type="submit" wire:loading.attr="disabled" style="width: 100%; background: var(--ui-black); color: white; border-radius: 100px; padding: 16px; font-weight: 800; font-size: 13px; border: none; box-shadow: 0 4px 20px rgba(24,24,27,0.25); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.98)'" onmouseup="this.style.transform='scale(1)'" onmouseleave="this.style.transform='scale(1)'">
                        
                        <div wire:loading.remove wire:target="simpan" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%;">
                            SIMPAN ABSENSI
                        </div>
                        
                        <div wire:loading.flex wire:target="simpan" style="align-items: center; justify-content: center; gap: 8px; width: 100%;" x-cloak>
                            MENYIMPAN...
                        </div>
                    </button>
                    <style>@keyframes spin { 100% { transform: rotate(360deg); } }</style>
                </div>
            @endif

        </form>
    </div>
</x-filament-panels::page.simple>