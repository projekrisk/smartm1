<x-filament-panels::page.simple>
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
            
            .hide-scroll::-webkit-scrollbar { display: none; }
            .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
            
            .list-item { animation: fadeIn 0.3s ease-out forwards; opacity: 0; }
            @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

            .search-input::placeholder { color: #A1A1AA; opacity: 1; }
            .search-input:focus { border-color: var(--ui-black) !important; outline: none; box-shadow: 0 4px 12px rgba(24,24,27,0.08) !important; }
            
            /* Custom Scrollbar for Horizontal Nav */
            .nav-scroll { overflow-x: auto; white-space: nowrap; padding-bottom: 4px; display: flex; gap: 8px; }
            .nav-scroll::-webkit-scrollbar { height: 0; display: none; }

            [x-cloak] { display: none !important; }
        </style>
    </div>

    <div class="workspace-container selection:bg-zinc-900 selection:text-white">
        
        <!-- Header & Search Area (Fixed/Sticky behavior visually) -->
        <div style="padding: 24px 20px 16px 20px; display: flex; flex-direction: column; gap: 16px; margin-top: env(safe-area-inset-top, 0px); background: var(--ui-bg); flex-shrink: 0; z-index: 10;">
            
            <div style="display: flex; align-items: center; gap: 16px;">
                <a href="/siswa" class="touch-scale" style="width: 44px; height: 44px; border-radius: 50%; background: var(--ui-surface); border: 1px solid var(--ui-border); display: flex; align-items: center; justify-content: center; color: var(--ui-black); box-shadow: 0 2px 8px rgba(0,0,0,0.04); flex-shrink: 0; text-decoration: none;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                
                <div style="flex: 1;">
                    <h1 style="font-size: 20px; font-weight: 900; color: var(--ui-black); margin: 0; letter-spacing: -0.5px; line-height: 1.2;">Direktori</h1>
                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 4px;">
                        <div style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--ui-black);"></div>
                        <p style="font-size: 12px; font-weight: 600; color: var(--ui-muted); margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">Staf & Pengajar</p>
                    </div>
                </div>
            </div>

            <div style="position: relative;">
                <div style="position: absolute; inset-y: 0; left: 0; padding-left: 16px; display: flex; align-items: center; pointer-events: none; top: 17px; ">
                    <svg style="width: 18px; height: 18px; color: var(--ui-muted);" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="search-input ambient-shadow" placeholder="Cari nama atau tugas utama..." 
                       style="width: 100%; background-color: var(--ui-surface); border: 1px solid var(--ui-border); color: var(--ui-black); font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: 13px; border-radius: 16px; padding: 14px 16px 14px 44px; transition: all 0.2s;">
            </div>

            <!-- Filter Pills -->
            <div class="nav-scroll hide-scroll" style="margin-top: 4px;">
                @php
                    $tabs = ['Semua' => 'Semua', 'Kepala Sekolah' => 'Kepsek', 'Guru' => 'Guru', 'Tenaga Kependidikan' => 'Staf / TU'];
                @endphp
                
                @foreach($tabs as $key =>$label)
                    <button wire:click="setKategori('{{ $key }}')" 
                            style="border: 1px solid {{ $kategori ===$key ? 'var(--ui-black)' : 'var(--ui-border)' }}; 
                                   padding: 8px 16px; border-radius: 100px; font-family: 'DM Sans', sans-serif; font-size: 11px; font-weight: 800; cursor: pointer; transition: all 0.2s; 
                                   background-color: {{ $kategori ===$key ? 'var(--ui-black)' : 'var(--ui-surface)' }}; 
                                   color: {{ $kategori ===$key ? 'white' : 'var(--ui-muted)' }};
                                   text-transform: uppercase; letter-spacing: 0.5px;">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

        </div>

        <div class="workspace-content">
            <div style="padding: 4px 20px 24px 20px; display: flex; flex-direction: column; gap: 12px;">
                
                @forelse($pegawais as $index =>$pegawai)
                    @php
                        $avatarUrl = asset('images/default-avatar.png');
                        
                        if (!empty($pegawai->foto) &&$pegawai->foto !== '[]' && $pegawai->foto !== 'foto-pegawai/' && !str_ends_with($pegawai->foto, '/')) {
                            if (\Illuminate\Support\Facades\Storage::disk('publik_upload')->exists($pegawai->foto)) {
                                $avatarUrl = \Illuminate\Support\Facades\Storage::disk('publik_upload')->url($pegawai->foto);
                            }
                        }

                        $badgeBg = 'var(--ui-bg)'; $badgeText = 'var(--ui-muted)';$badgeBorder = 'var(--ui-border)';
                        
                        if ($pegawai->jenis_ptk === 'Kepala Sekolah') {$badgeBg = '#FEF2F2'; $badgeText = '#DC2626'; $badgeBorder = '#FCA5A5';
                        }
                        elseif ($pegawai->jenis_ptk === 'Guru') {$badgeBg = '#EEF2FF'; $badgeText = '#4F46E5'; $badgeBorder = '#C7D2FE';
                        }
                        elseif ($pegawai->jenis_ptk === 'Tenaga Kependidikan') {$badgeBg = '#F0FDF4'; $badgeText = '#059669'; $badgeBorder = '#A7F3D0';
                        }
                    @endphp
                    
                    <div class="list-item ambient-shadow" style="animation-delay: {{ $index * 0.05 }}s; background: var(--ui-surface); border: 1px solid rgba(0,0,0,0.02); border-radius: 20px; padding: 14px; display: flex; align-items: center; gap: 14px;">
                        
                        <div style="width: 48px; height: 48px; flex-shrink: 0; border-radius: 50%; overflow: hidden; background-color: var(--ui-bg); border: 1px solid var(--ui-border);">
                            <img src="{{ $avatarUrl }}" alt="{{ $pegawai->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>

                        <div style="flex: 1; min-width: 0; display: flex; flex-direction: column; justify-content: center;">
                            <h3 style="font-size: 14px; font-weight: 800; color: var(--ui-black); margin: 0 0 4px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.2;">
                                {{ $pegawai->nama }}
                            </h3>
                            <p style="font-size: 11px; font-weight: 600; color: var(--ui-muted); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $pegawai->tugas_utama ?? 'Belum ada tugas spesifik' }}
                            </p>
                        </div>

                        <div style="flex-shrink: 0; background-color: {{ $badgeBg }}; color: {{ $badgeText }}; border: 1px solid {{$badgeBorder }}; font-size: 9px; font-weight: 800; padding: 4px 8px; border-radius: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                            {{ $pegawai->jenis_ptk === 'Tenaga Kependidikan' ? 'STAF/TU' : ($pegawai->jenis_ptk === 'Kepala Sekolah' ? 'KEPSEK' : 'GURU') }}
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 48px 20px; border: 1px dashed var(--ui-border); border-radius: 24px; margin-top: 12px;">
                        <div style="width: 56px; height: 56px; border-radius: 50%; background-color: var(--ui-surface); color: var(--ui-muted); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
                            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <h4 style="color: var(--ui-black); font-size: 14px; font-weight: 800; margin: 0 0 4px 0;">Tidak ada hasil</h4>
                        <p style="color: var(--ui-muted); font-size: 12px; font-weight: 500; margin: 0;">Pencarian Anda tidak cocok dengan pegawai mana pun.</p>
                    </div>
                @endforelse

            </div>
        </div>

    </div>
</x-filament-panels::page.simple>