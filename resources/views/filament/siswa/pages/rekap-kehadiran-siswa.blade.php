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

    <div class="workspace-container selection:bg-zinc-900 selection:text-white">
        
        <div style="padding: 24px 20px 16px 20px; display: flex; align-items: center; gap: 16px; margin-top: env(safe-area-inset-top, 0px); background: var(--ui-bg); flex-shrink: 0; z-index: 10; border-bottom: 1px solid rgba(0,0,0,0.02);">
            <a href="/siswa" class="touch-scale" style="width: 44px; height: 44px; border-radius: 50%; background: var(--ui-surface); border: 1px solid var(--ui-border); display: flex; align-items: center; justify-content: center; color: var(--ui-black); box-shadow: 0 2px 8px rgba(0,0,0,0.04); flex-shrink: 0; text-decoration: none;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <div>
                <h1 style="font-size: 20px; font-weight: 900; color: var(--ui-black); margin: 0; letter-spacing: -0.5px; line-height: 1.2;">Info Kehadiran</h1>
                <div style="display: flex; align-items: center; gap: 6px; margin-top: 4px;">
                    <div style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--ui-black);"></div>
                    <p style="font-size: 12px; font-weight: 600; color: var(--ui-muted); margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">{{ $bulanTahun }}</p>
                </div>
            </div>
        </div>

        <div class="workspace-content">
            <div style="padding: 12px 20px 24px 20px;">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 24px;">
                    <div class="ambient-shadow" style="background: var(--ui-surface); border-radius: 24px; padding: 20px 16px; text-align: center; display: flex; flex-direction: column; justify-content: center; border: 1px solid rgba(0,0,0,0.02);">
                        <p style="font-size: 10px; font-weight: 800; color: var(--ui-muted); text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Semester Aktif</p>
                        <h2 style="font-size: 32px; font-weight: 900; color: var(--ui-black); margin: 6px 0; line-height: 1;">{{ $absenSemester }}</h2>
                        <p style="font-size: 10px; font-weight: 700; color: var(--ui-muted); margin: 0;">Hari Tdk Hadir</p>
                    </div>
                    <div class="ambient-shadow" style="background: var(--ui-surface); border-radius: 24px; padding: 20px 16px; text-align: center; display: flex; flex-direction: column; justify-content: center; border: 1px solid rgba(0,0,0,0.02); position: relative; overflow: hidden;">
                        
                        <div style="position: absolute; top: -15px; right: -15px; width: 50px; height: 50px; background: rgba(239, 68, 68, 0.1); border-radius: 50%; filter: blur(12px);"></div>

                        <p style="font-size: 10px; font-weight: 800; color: var(--ui-muted); text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Bulan Ini</p>
                        <h2 style="font-size: 32px; font-weight: 900; color: #EF4444; margin: 6px 0; line-height: 1;">{{ $absenBulan }}</h2>
                        <p style="font-size: 10px; font-weight: 700; color: var(--ui-muted); margin: 0;">Hari Tdk Hadir</p>
                    </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; padding: 0 4px;">
                    <h3 style="font-size: 14px; font-weight: 800; color: var(--ui-black); margin: 0;">Riwayat Bulan Ini</h3>
                    <span style="font-size: 10px; font-weight: 700; color: var(--ui-muted); text-transform: uppercase;">{{ count($listAbsen) }} Catatan</span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @if(count($listAbsen) > 0)
                        @foreach($listAbsen as $absen)
                            <div class="ambient-shadow" style="background: var(--ui-surface); border-radius: 20px; padding: 16px; display: flex; align-items: center; justify-content: space-between; border: 1px solid rgba(0,0,0,0.02);">
                                
                                <div style="display: flex; align-items: center; gap: 14px; overflow: hidden; flex: 1;">
                                    <div style="flex-shrink: 0; width: 40px; height: 40px; border-radius: 12px; background-color: var(--ui-bg); display: flex; align-items: center; justify-content: center; color: var(--ui-muted); border: 1px solid var(--ui-border);">
                                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    
                                    <div style="display: flex; flex-direction: column; min-width: 0;">
                                        <span style="font-weight: 800; font-size: 13px; color: var(--ui-black);">{{ \Carbon\Carbon::parse($absen->rekapKehadiran->tanggal)->isoFormat('dddd, D MMMM Y') }}</span>
                                        <span style="font-size: 11px; font-weight: 600; color: var(--ui-muted); margin-top: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $absen->keterangan ?? 'Tanpa Keterangan Tambahan' }}</span>
                                    </div>
                                </div>

                                <div style="flex-shrink: 0; margin-left: 12px;">
                                    <div class="badge-status @if($absen->status == 'Sakit') badge-sakit @elseif($absen->status == 'Izin') badge-izin @elseif($absen->status == 'Dispensasi') badge-dispensasi @else badge-alpa @endif">
                                        {{ $absen->status }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="ambient-shadow" style="background: var(--ui-surface); border-radius: 20px; padding: 32px 16px; text-align: center; border: 1px dashed var(--ui-border);">
                            <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #D1FAE5; color: #10B981; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                                <svg style="width: 28px; height: 28px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 style="font-size: 14px; font-weight: 900; color: var(--ui-black); margin: 0 0 4px 0;">Luar Biasa!</h3>
                            <p style="font-size: 11px; font-weight: 600; color: var(--ui-muted); line-height: 1.5; margin: 0;">Anda selalu hadir penuh di bulan ini.<br>Pertahankan semangat belajarnya!</p>
                        </div>
                    @endif
                </div>
                
            </div>
        </div>

    </div>
</x-filament-panels::page.simple>