<x-filament-panels::page.simple>
    <div wire:ignore>
        <script>
            // Memaksa warna status bar (baterai/sinyal) di mobile agar senada dengan background aplikasi
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

            /* Desktop boundaries */
            @media (min-width: 640px) {
                .workspace-container {
                    left: 50%; right: auto; transform: translateX(-50%);
                    border-left: 1px solid var(--ui-border);
                    border-right: 1px solid var(--ui-border);
                    box-shadow: 0 0 50px rgba(0,0,0,0.05);
                }
            }

            /* Scrollable Area */
            .workspace-content { 
                flex: 1; overflow-y: auto; overflow-x: hidden; 
                padding-bottom: calc(90px + env(safe-area-inset-bottom, 0px)); 
                scrollbar-width: none; 
            }
            .workspace-content::-webkit-scrollbar { display: none; }

            /* Digital Pass Element */
            .digital-pass {
                background: linear-gradient(135deg, #18181B 0%, #27272A 100%);
                border-radius: 24px;
                padding: 20px;
                color: white;
                position: relative;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(24, 24, 27, 0.15);
            }
            .digital-pass::before {
                content: ''; position: absolute; top: -50px; right: -20px;
                width: 150px; height: 150px; border-radius: 50%;
                background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
                pointer-events: none;
            }

            /* Touch Interactions */
            .touch-scale { transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
            .touch-scale:active { transform: scale(0.92); }

            /* Custom Shadows */
            .ambient-shadow { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); }
        </style>
    </div>

    <div class="min-h-screen relative selection:bg-zinc-900 selection:text-white" 
         x-data="{ 
            showLogoutSheet: false,
            showPwaPrompt: false,
            deferredPrompt: null,
            initPwa() {
                window.addEventListener('beforeinstallprompt', (e) => {
                    e.preventDefault();
                    this.deferredPrompt = e;
                    this.showPwaPrompt = true;
                });
            },
            async installPwa() {
                if (this.deferredPrompt !== null) {
                    this.deferredPrompt.prompt();
                    const { outcome } = await this.deferredPrompt.userChoice;
                    if (outcome === 'accepted') {
                        this.showPwaPrompt = false;
                    }
                    this.deferredPrompt = null;
                }
            }
         }"
         x-init="initPwa()">
         
        <div class="workspace-container">
            
            <!-- iOS-style PWA Prompt -->
            <div x-show="showPwaPrompt" style="display: none; position: absolute; top: 16px; left: 16px; right: 16px; z-index: 100;" x-transition>
                <div style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border-radius: 100px; padding: 8px 8px 8px 16px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 24px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05);">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <x-filament::icon icon="heroicon-s-arrow-down-tray" style="width: 16px; height: 16px; color: var(--ui-black);" />
                        <span style="font-size: 12px; font-weight: 700; color: var(--ui-black);">Pasang Aplikasi</span>
                    </div>
                    <button @click="installPwa()" style="background: var(--ui-black); color: white; border: none; padding: 6px 14px; border-radius: 100px; font-size: 11px; font-weight: 800; cursor: pointer; text-transform: uppercase; letter-spacing: 0.5px;">Install</button>
                </div>
            </div>

            <div class="workspace-content">
                
                @php
                    $hour = \Carbon\Carbon::now('Asia/Jakarta')->format('H');
                    if ($hour >= 5 && $hour < 11) $greeting = 'Selamat Pagi';
                    elseif ($hour >= 11 && $hour < 15) $greeting = 'Selamat Siang';
                    elseif ($hour >= 15 && $hour < 18) $greeting = 'Selamat Sore';
                    else $greeting = 'Selamat Malam';

                    $rawName = $siswa->nama_lengkap ?? Auth::user()->name ?? 'Siswa';
                    $properName = ucwords(strtolower($rawName));
                    $firstName = explode(' ', trim($properName))[0];
                @endphp

                <div style="padding: 24px 20px 16px 20px; display: flex; flex-direction: column; gap: 20px;">
                    
                    <!-- Clean Header -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: env(safe-area-inset-top, 0px);">
                        <div>
                            <p style="font-size: 13px; font-weight: 600; color: var(--ui-muted); margin: 0 0 2px 0;">{{ $greeting }}</p>
                            <h1 style="font-size: 22px; font-weight: 800; color: var(--ui-black); margin: 0; letter-spacing: -0.5px;">{{ $firstName }}</h1>
                        </div>
                        <a href="/siswa/profil" class="touch-scale" style="width: 44px; height: 44px; border-radius: 50%; background: var(--ui-surface); border: 1px solid var(--ui-border); overflow: hidden; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                            @if(isset($siswa->foto) && $siswa->foto && !str_ends_with($siswa->foto, '/'))
                                <img src="{{ url('/uploads/' . $siswa->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span style="font-size: 16px; font-weight: 800; color: var(--ui-black);">{{ substr($firstName, 0, 1) }}</span>
                            @endif
                        </a>
                    </div>

                    <!-- Digital Identity Pass (Highly Efficient UX) -->
                    <div class="digital-pass">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                <span style="font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.6); font-weight: 700;">ID Peserta Didik</span>
                                <span style="font-size: 16px; font-weight: 700; letter-spacing: 1px;">{{ $siswa->nisn ?? '0000000000' }}</span>
                            </div>
                            <x-filament::icon icon="heroicon-s-academic-cap" style="width: 24px; height: 24px; color: rgba(255,255,255,0.8);" />
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                            <div>
                                <span style="font-size: 10px; color: rgba(255,255,255,0.6); font-weight: 600; display: block; margin-bottom: 2px;">Kelas</span>
                                <span style="font-size: 14px; font-weight: 800;">{{ $siswa->kelas->nama_kelas ?? 'Belum Diatur' }}</span>
                            </div>
                            
                            <a href="/siswa/kartu-pelajar" class="touch-scale" style="background: white; color: var(--ui-black); padding: 8px 12px; border-radius: 100px; font-size: 11px; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                                <x-filament::icon icon="heroicon-s-qr-code" style="width: 14px; height: 14px;" />
                                Kartu
                            </a>
                        </div>
                    </div>
                </div>

                <!-- The Individual Bento Cards Grid -->
                <div style="padding: 0 20px;">
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
                        
                        <a href="/siswa/jadwal" class="touch-scale ambient-shadow" style="background: var(--ui-surface); border-radius: 20px; padding: 14px 8px; display: flex; flex-direction: column; align-items: center; gap: 10px; text-decoration: none; border: 1px solid rgba(0,0,0,0.02);">
                            <x-filament::icon icon="heroicon-o-calendar-days" style="width: 26px; height: 26px; color: var(--ui-black); stroke-width: 1.5;" />
                            <span style="font-size: 11px; font-weight: 700; color: var(--ui-text);">Jadwal</span>
                        </a>
                        
                        <a href="/siswa/rekap-absensi" class="touch-scale ambient-shadow" style="background: var(--ui-surface); border-radius: 20px; padding: 14px 8px; display: flex; flex-direction: column; align-items: center; gap: 10px; text-decoration: none; border: 1px solid rgba(0,0,0,0.02);">
                            <x-filament::icon icon="heroicon-o-check-circle" style="width: 26px; height: 26px; color: var(--ui-black); stroke-width: 1.5;" />
                            <span style="font-size: 11px; font-weight: 700; color: var(--ui-text);">Absensi</span>
                        </a>
                        
                        <a href="/siswa/nilai" class="touch-scale ambient-shadow" style="background: var(--ui-surface); border-radius: 20px; padding: 14px 8px; display: flex; flex-direction: column; align-items: center; gap: 10px; text-decoration: none; border: 1px solid rgba(0,0,0,0.02);">
                            <x-filament::icon icon="heroicon-o-chart-bar" style="width: 26px; height: 26px; color: var(--ui-black); stroke-width: 1.5;" />
                            <span style="font-size: 11px; font-weight: 700; color: var(--ui-text);">Nilai</span>
                        </a>
                        
                        <a href="/siswa/e-rapor" class="touch-scale ambient-shadow" style="background: var(--ui-surface); border-radius: 20px; padding: 14px 8px; display: flex; flex-direction: column; align-items: center; gap: 10px; text-decoration: none; border: 1px solid rgba(0,0,0,0.02);">
                            <x-filament::icon icon="heroicon-o-document-text" style="width: 26px; height: 26px; color: var(--ui-black); stroke-width: 1.5;" />
                            <span style="font-size: 11px; font-weight: 700; color: var(--ui-text);">Rapor</span>
                        </a>
                        
                        <a href="/siswa/prestasi" class="touch-scale ambient-shadow" style="background: var(--ui-surface); border-radius: 20px; padding: 14px 8px; display: flex; flex-direction: column; align-items: center; gap: 10px; text-decoration: none; border: 1px solid rgba(0,0,0,0.02);">
                            <x-filament::icon icon="heroicon-o-trophy" style="width: 26px; height: 26px; color: var(--ui-black); stroke-width: 1.5;" />
                            <span style="font-size: 11px; font-weight: 700; color: var(--ui-text);">Prestasi</span>
                        </a>

                        <a href="/siswa/catatan" class="touch-scale ambient-shadow" style="background: var(--ui-surface); border-radius: 20px; padding: 14px 8px; display: flex; flex-direction: column; align-items: center; gap: 10px; text-decoration: none; border: 1px solid rgba(0,0,0,0.02);">
                            <x-filament::icon icon="heroicon-o-bookmark" style="width: 26px; height: 26px; color: var(--ui-black); stroke-width: 1.5;" />
                            <span style="font-size: 11px; font-weight: 700; color: var(--ui-text);">Catatan</span>
                        </a>
                        
                        <a href="/siswa/dokumen" class="touch-scale ambient-shadow" style="background: var(--ui-surface); border-radius: 20px; padding: 14px 8px; display: flex; flex-direction: column; align-items: center; gap: 10px; text-decoration: none; border: 1px solid rgba(0,0,0,0.02);">
                            <x-filament::icon icon="heroicon-o-folder-open" style="width: 26px; height: 26px; color: var(--ui-black); stroke-width: 1.5;" />
                            <span style="font-size: 11px; font-weight: 700; color: var(--ui-text);">Dokumen</span>
                        </a>

                        <a href="/siswa/pegawai" class="touch-scale ambient-shadow" style="background: var(--ui-surface); border-radius: 20px; padding: 14px 8px; display: flex; flex-direction: column; align-items: center; gap: 10px; text-decoration: none; border: 1px solid rgba(0,0,0,0.02);">
                            <x-filament::icon icon="heroicon-o-users" style="width: 26px; height: 26px; color: var(--ui-black); stroke-width: 1.5;" />
                            <span style="font-size: 11px; font-weight: 700; color: var(--ui-text);">Direktori</span>
                        </a>
                        
                    </div>
                </div>

                @if(isset($siswa) && $siswa->is_sekretaris)
                    <div style="padding: 16px 20px 0 20px;">
                        <a href="/siswa/absensi" class="touch-scale ambient-shadow" style="display: flex; align-items: center; justify-content: space-between; background: var(--ui-surface); padding: 16px; border-radius: 20px; text-decoration: none; border: 1px solid var(--ui-border);">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <div style="background: var(--ui-bg); padding: 10px; border-radius: 12px;">
                                    <x-filament::icon icon="heroicon-o-clipboard-document-check" style="width: 22px; height: 22px; color: var(--ui-black);" />
                                </div>
                                <div>
                                    <h4 style="font-weight: 800; font-size: 13px; color: var(--ui-black); margin: 0 0 2px 0;">Input Absensi Kelas</h4>
                                    <p style="font-size: 11px; font-weight: 600; color: var(--ui-muted); margin: 0;">Akses Khusus Sekretaris</p>
                                </div>
                            </div>
                            <x-filament::icon icon="heroicon-m-chevron-right" style="width: 18px; height: 18px; color: var(--ui-muted);" />
                        </a>
                    </div>
                @endif

                <!-- Clean Announcements -->
                <div style="padding: 24px 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 16px;">
                        <h3 style="font-size: 15px; font-weight: 800; color: var(--ui-black); margin: 0;">Informasi Terbaru</h3>
                        <a href="/siswa/pengumuman" style="font-size: 12px; font-weight: 700; color: var(--ui-muted); text-decoration: none;">Lihat Semua</a>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @forelse($pengumuman as $info)
                            <div class="ambient-shadow" style="background: var(--ui-surface); border-radius: 20px; padding: 16px; display: flex; gap: 16px; border: 1px solid rgba(0,0,0,0.02);">
                                <div style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--ui-black); flex-shrink: 0; margin-top: 6px;"></div>
                                <div style="flex: 1; min-width: 0;">
                                    <h4 style="font-weight: 800; font-size: 13px; color: var(--ui-black); margin: 0 0 6px 0; line-height: 1.4;">{{ $info->judul }}</h4>
                                    <div style="font-size: 12px; color: var(--ui-muted); line-height: 1.5; font-weight: 500; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {!! strip_tags($info->isi, '<a><strong><b><i><em>') !!}
                                    </div>
                                    <p style="font-size: 10px; color: var(--ui-muted); font-weight: 700; margin-top: 10px; margin-bottom: 0;">{{ $info->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <div style="background: transparent; border-radius: 20px; border: 1px dashed var(--ui-border); padding: 32px 16px; text-align: center;">
                                <p style="font-size: 12px; font-weight: 600; color: var(--ui-muted); margin: 0;">Belum ada informasi terbaru.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
            </div>

            <!-- Glassmorphism Bottom Nav (Efficient UX) -->
            <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(255,255,255,0.85); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-top: 1px solid rgba(0,0,0,0.05); display: flex; justify-content: space-around; padding: 12px 8px calc(12px + env(safe-area-inset-bottom, 0px)) 8px; z-index: 50;">
                
                <a href="/siswa" style="display: flex; flex-direction: column; align-items: center; gap: 4px; text-decoration: none; color: var(--ui-black); flex: 1;">
                    <x-filament::icon icon="heroicon-s-home" style="width: 24px; height: 24px;" />
                    <span style="font-size: 10px; font-weight: 800;">Beranda</span>
                </a>
                
                <a href="/siswa/riwayat" style="display: flex; flex-direction: column; align-items: center; gap: 4px; text-decoration: none; color: var(--ui-muted); flex: 1; transition: color 0.2s;">
                    <x-filament::icon icon="heroicon-o-clock" style="width: 24px; height: 24px;" />
                    <span style="font-size: 10px; font-weight: 600;">Riwayat</span>
                </a>
                
                @php
                    $unreadPesan = 0;
                    if(isset($siswa)) {
                        $unreadPesan = \App\Models\PesanBantuan::where('siswa_id', $siswa->id)->where('is_read_siswa', false)->count();
                    }
                @endphp
                <a href="/siswa/pesan" style="display: flex; flex-direction: column; align-items: center; gap: 4px; text-decoration: none; color: var(--ui-muted); flex: 1; position: relative; transition: color 0.2s;">
                    <div style="position: relative;">
                        <x-filament::icon icon="heroicon-o-chat-bubble-left-ellipsis" style="width: 24px; height: 24px;" />
                        @if($unreadPesan > 0)
                            <div style="position: absolute; top: -2px; right: -2px; width: 8px; height: 8px; background-color: #EF4444; border: 2px solid white; border-radius: 50%;"></div>
                        @endif
                    </div>
                    <span style="font-size: 10px; font-weight: 600;">Pesan</span>
                </a>
                
                <a href="/siswa/profil" style="display: flex; flex-direction: column; align-items: center; gap: 4px; text-decoration: none; color: var(--ui-muted); flex: 1; transition: color 0.2s;">
                    <x-filament::icon icon="heroicon-o-user" style="width: 24px; height: 24px;" />
                    <span style="font-size: 10px; font-weight: 600;">Profil</span>
                </a>
                
                <button @click="showLogoutSheet = true" style="display: flex; flex-direction: column; align-items: center; gap: 4px; border: none; background: transparent; color: var(--ui-muted); flex: 1; cursor: pointer; transition: color 0.2s;">
                    <x-filament::icon icon="heroicon-o-arrow-right-on-rectangle" style="width: 24px; height: 24px;" />
                    <span style="font-size: 10px; font-weight: 600;">Keluar</span>
                </button>
                
            </div>

            <!-- Sleek Logout Sheet -->
            <div x-show="showLogoutSheet" style="display: none; position: absolute; inset: 0; background-color: rgba(0,0,0,0.4); z-index: 99; backdrop-filter: blur(4px);" x-transition.opacity @click="showLogoutSheet = false"></div>
            
            <div x-show="showLogoutSheet" style="display: none; position: absolute; bottom: 0; left: 0; right: 0; background-color: var(--ui-surface); border-top-left-radius: 28px; border-top-right-radius: 28px; z-index: 100; padding: 24px; padding-bottom: calc(24px + env(safe-area-inset-bottom, 0px)); box-shadow: 0 -20px 40px rgba(0,0,0,0.1);"
                 x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="transform translate-y-full" 
                 x-transition:enter-end="transform translate-y-0" 
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="transform translate-y-0" 
                 x-transition:leave-end="transform translate-y-full">
                
                <div style="width: 40px; height: 5px; border-radius: 100px; background-color: var(--ui-border); margin: 0 auto 24px auto;"></div>
                
                <h3 style="font-size: 18px; font-weight: 800; color: var(--ui-black); text-align: center; margin: 0 0 8px 0;">Konfirmasi Keluar</h3>
                <p style="font-size: 13px; font-weight: 500; color: var(--ui-muted); text-align: center; margin: 0 0 28px 0; line-height: 1.5;">Anda harus masuk kembali menggunakan NISN untuk mengakses portal ini.</p>
                
                <div style="display: flex; gap: 12px;">
                    <button @click="showLogoutSheet = false" style="flex: 1; padding: 14px; border-radius: 100px; background-color: var(--ui-bg); color: var(--ui-black); font-weight: 800; font-size: 13px; border: none; cursor: pointer;">Batal</button>
                    
                    <button type="button" wire:click="keluarAplikasi" wire:loading.attr="disabled" style="flex: 1; padding: 14px; border-radius: 100px; background-color: var(--ui-black); color: white; font-weight: 800; font-size: 13px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <span wire:loading.remove wire:target="keluarAplikasi">Ya, Keluar</span>
                        <span wire:loading wire:target="keluarAplikasi">
                            <svg style="animation: spin 1s linear infinite; height: 16px; width: 16px; color: white;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</x-filament-panels::page.simple>