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
            
            <div>
                <h1 style="font-size: 20px; font-weight: 900; color: var(--ui-black); margin: 0; letter-spacing: -0.5px; line-height: 1.2;">E-Rapor Siswa</h1>
                <div style="display: flex; align-items: center; gap: 6px; margin-top: 4px;">
                    <div style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--ui-black);"></div>
                    <p style="font-size: 12px; font-weight: 600; color: var(--ui-muted); margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">Nilai Akhir Semester</p>
                </div>
            </div>
        </div>

        <div class="workspace-content">
            <div style="padding: 12px 20px 20px 20px;">
                
                @if($raporGrouped->isEmpty())
                    <div class="ambient-shadow" style="text-align: center; padding: 48px 20px; background: var(--ui-surface); border-radius: 24px; border: 1px solid rgba(0,0,0,0.02);">
                        <div style="width: 56px; height: 56px; border-radius: 16px; background-color: var(--ui-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                            <x-filament::icon icon="heroicon-o-folder-open" style="width: 28px; height: 28px; color: var(--ui-muted);" />
                        </div>
                        <h3 style="font-weight: 800; font-size: 15px; color: var(--ui-black); margin: 0 0 6px 0;">Belum Ada Rapor</h3>
                        <p style="font-size: 12px; font-weight: 500; color: var(--ui-muted); line-height: 1.5; margin: 0;">Nilai akhir semester Anda belum diterbitkan atau direkap oleh Tata Usaha.</p>
                    </div>
                @else
                    
                    <div style="margin-bottom: 24px;">
                        <a href="{{ url('/cetak/buku-rapor/' . $siswa->id) }}" target="_blank" class="touch-scale ambient-shadow" style="text-decoration: none; display: flex; align-items: center; justify-content: space-between; background: var(--ui-black); padding: 14px 20px; border-radius: 20px; color: white;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <div style="background-color: rgba(255,255,255,0.15); padding: 10px; border-radius: 12px;">
                                    <x-filament::icon icon="heroicon-s-document-arrow-down" style="width: 20px; height: 20px; color: white;" />
                                </div>
                                <div>
                                    <h4 style="font-weight: 800; font-size: 14px; margin: 0 0 2px 0;">Unduh Buku Rapor</h4>
                                    <p style="font-size: 11px; font-weight: 600; color: rgba(255,255,255,0.7); margin: 0;">Cetak Format PDF Resmi</p>
                                </div>
                            </div>
                            <x-filament::icon icon="heroicon-m-chevron-right" style="width: 18px; height: 18px; color: rgba(255,255,255,0.5);" />
                        </a>
                    </div>

                    @php
                        $semesterTerbaru = $raporGrouped->keys()->first();
                    @endphp

                    <div x-data="{ activeSmt: '{{ Str::slug($semesterTerbaru) }}' }" style="display: flex; flex-direction: column; gap: 12px;">
                        
                        @foreach($raporGrouped as $semester => $nilais)
                            @php
                                $totalNilai = $nilais->sum('nilai_akhir');
                                $rataRata = $nilais->count() > 0 ? round($totalNilai / $nilais->count(), 1) : 0;
                            @endphp
                            
                            <div class="ambient-shadow" style="background: var(--ui-surface); border-radius: 20px; border: 1px solid rgba(0,0,0,0.02); overflow: hidden;">
                                
                                <button @click="activeSmt = activeSmt === '{{ Str::slug($semester) }}' ? null : '{{ Str::slug($semester) }}'" 
                                        style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; background: transparent; border: none; cursor: pointer;">
                                    
                                    <div style="display: flex; flex-direction: column; align-items: flex-start; text-align: left; gap: 6px;">
                                        <h3 style="font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: var(--ui-black); margin: 0;">
                                            {{ $semester }}
                                        </h3>
                                        
                                        <div style="display: flex; align-items: center; gap: 6px;">
                                            <span style="font-size: 11px; font-weight: 600; color: var(--ui-muted);">Rata-rata:</span>
                                            <span style="font-size: 11px; font-weight: 800; padding: 2px 8px; border-radius: 6px; {{ $rataRata >= 75 ? 'background-color: #D1FAE5; color: #047857;' : 'background-color: #FEE2E2; color: #B91C1C;' }}">
                                                {{ $rataRata }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div :class="{'rotate-180': activeSmt === '{{ Str::slug($semester) }}', 'transition-transform duration-300': true}" style="color: var(--ui-muted);">
                                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </button>

                                <div x-show="activeSmt === '{{ Str::slug($semester) }}'" x-collapse x-cloak>
                                    <div style="padding: 0 16px 16px 16px; display: flex; flex-direction: column; gap: 8px;">
                                        
                                        @foreach($nilais->sortBy('mataPelajaran.nama_pelajaran') as $n)
                                            <div style="background-color: var(--ui-bg); border-radius: 16px; padding: 14px; display: flex; align-items: stretch; gap: 14px;">
                                                
                                                <div style="flex-shrink: 0; width: 44px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px; border-right: 1px solid var(--ui-border); padding-right: 14px;">
                                                    <span style="font-size: 18px; font-weight: 900; line-height: 1; {{ $n->nilai_akhir >= 75 ? 'color: #059669;' : 'color: #DC2626;' }}">
                                                        {{ $n->nilai_akhir }}
                                                    </span>
                                                    <span style="font-size: 9px; font-weight: 800; background-color: var(--ui-surface); color: var(--ui-muted); padding: 2px 6px; border-radius: 4px; border: 1px solid var(--ui-border);">
                                                        {{ $n->predikat ?? '-' }}
                                                    </span>
                                                </div>
                                                
                                                <div style="flex: 1; display: flex; flex-direction: column; justify-content: center; min-width: 0;">
                                                    <h4 style="font-size: 13px; font-weight: 800; color: var(--ui-black); margin: 0 0 4px 0; line-height: 1.3;">
                                                        {{ $n->mataPelajaran->nama_pelajaran ?? '-' }}
                                                    </h4>
                                                    <p style="font-size: 11px; font-weight: 500; color: var(--ui-muted); margin: 0; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                        {{ $n->deskripsi ?: 'Tidak ada deskripsi.' }}
                                                    </p>
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