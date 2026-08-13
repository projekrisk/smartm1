<x-filament-panels::page.simple>
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
                padding-bottom: calc(40px + env(safe-area-inset-bottom, 0px)); 
                scrollbar-width: none; 
            }
            .workspace-content::-webkit-scrollbar { display: none; }

            .touch-scale { transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
            .touch-scale:active { transform: scale(0.94); }

            .ambient-shadow { box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04); }
            
            [x-cloak] { display: none !important; }
        </style>
    </div>

    <div class="workspace-container selection:bg-zinc-900 selection:text-white">
        
        <div class="workspace-content">
            
            <div style="padding: 24px 20px 24px 20px; display: flex; align-items: center; gap: 16px; margin-top: env(safe-area-inset-top, 0px);">
                <a href="/siswa" class="touch-scale" style="width: 44px; height: 44px; border-radius: 50%; background: var(--ui-surface); border: 1px solid var(--ui-border); display: flex; align-items: center; justify-content: center; color: var(--ui-black); box-shadow: 0 2px 8px rgba(0,0,0,0.04); flex-shrink: 0; text-decoration: none;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                
                <div>
                    <h1 style="font-size: 20px; font-weight: 900; color: var(--ui-black); margin: 0; letter-spacing: -0.5px; line-height: 1.2;">Jadwal Pelajaran</h1>
                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 4px;">
                        <div style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--ui-black);"></div>
                        <p style="font-size: 12px; font-weight: 600; color: var(--ui-muted); margin: 0;">Kelas {{ $siswa->kelas->nama_kelas ?? 'Belum Diatur' }}</p>
                    </div>
                </div>
            </div>

            <div style="padding: 0 20px;">
                
                @if($jadwalGrouped->isEmpty())
                    <div class="ambient-shadow" style="text-align: center; padding: 48px 20px; background: var(--ui-surface); border-radius: 24px; border: 1px solid rgba(0,0,0,0.02);">
                        <div style="width: 56px; height: 56px; border-radius: 16px; background-color: var(--ui-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                            <x-filament::icon icon="heroicon-o-calendar-days" style="width: 28px; height: 28px; color: var(--ui-muted);" />
                        </div>
                        <h3 style="font-weight: 800; font-size: 15px; color: var(--ui-black); margin: 0 0 6px 0;">Belum Ada Jadwal</h3>
                        <p style="font-size: 12px; font-weight: 500; color: var(--ui-muted); line-height: 1.5; margin: 0;">Jadwal pelajaran untuk kelas Anda belum diterbitkan oleh Admin Tata Usaha.</p>
                    </div>
                @else
                    
                    @php
                        $hariIndo = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                        $hariIni = $hariIndo[date('N')];
                    @endphp

                    <div x-data="{ activeDay: '{{ $hariIni }}' }" style="display: flex; flex-direction: column; gap: 12px;">
                        
                        @foreach($jadwalGrouped as $hari => $jadwals)
                            
                            <div class="ambient-shadow" style="background: var(--ui-surface); border-radius: 20px; border: 1px solid rgba(0,0,0,0.02); overflow: hidden;">
                                
                                <button @click="activeDay = activeDay === '{{ $hari }}' ? null : '{{ $hari }}'" 
                                        style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; background: transparent; border: none; cursor: pointer; text-align: left;">
                                    
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <h3 style="font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin: 0; color: {{ $hari === $hariIni ? 'var(--ui-black)' : 'var(--ui-muted)' }};">
                                            Hari {{ $hari }}
                                        </h3>
                                        
                                        @if($hari === $hariIni)
                                            <span style="background-color: var(--ui-black); color: white; font-size: 9px; font-weight: 800; padding: 4px 10px; border-radius: 100px; text-transform: uppercase; letter-spacing: 0.5px;">Hari Ini</span>
                                        @endif
                                    </div>
                                    
                                    <div :class="{'rotate-180': activeDay === '{{ $hari }}', 'transition-transform duration-300': true}" style="color: var(--ui-muted);">
                                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </button>

                                <div x-show="activeDay === '{{ $hari }}'" x-collapse x-cloak>
                                    <div style="padding: 0 20px 20px 20px; display: flex; flex-direction: column;">
                                        
                                        @foreach($jadwals as $index => $item)
                                            <div style="display: flex; align-items: stretch; padding: 14px 0; border-top: 1px solid var(--ui-border);">
                                                
                                                <div style="width: 60px; flex-shrink: 0; display: flex; flex-direction: column; padding-right: 14px; border-right: 2px solid {{ $hari === $hariIni ? 'var(--ui-black)' : 'var(--ui-border)' }}; margin-right: 14px;">
                                                    <span style="font-size: 13px; font-weight: 800; color: var(--ui-black);">{{ date('H:i', strtotime($item->jam_mulai)) }}</span>
                                                    <span style="font-size: 11px; font-weight: 600; color: var(--ui-muted); margin-top: 2px;">{{ date('H:i', strtotime($item->jam_selesai)) }}</span>
                                                </div>

                                                <div style="flex: 1; display: flex; flex-direction: column; justify-content: center;">
                                                    <span style="font-size: 14px; font-weight: 800; color: var(--ui-black); line-height: 1.3;">{{ $item->mataPelajaran->nama_pelajaran ?? '-' }}</span>
                                                    
                                                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 6px;">
                                                        <x-filament::icon icon="heroicon-m-user" style="width: 12px; height: 12px; color: var(--ui-muted);" />
                                                        <span style="font-size: 11px; font-weight: 600; color: var(--ui-muted);">
                                                            {{ $item->guru->name ?? 'Belum ada guru' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        @endforeach

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