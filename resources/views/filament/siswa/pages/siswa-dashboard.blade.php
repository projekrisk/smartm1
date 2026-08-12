<x-filament-panels::page.simple>
    <div wire:ignore>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <style>
            :root {
                --ios-bg: #F2F2F7;
                --ios-card: #FFFFFF;
                --ios-text: #000000;
                --ios-text-secondary: #8A8A8E;
                --ios-blue: #007AFF;
                --ios-border: rgba(60, 60, 67, 0.29);
            }

            body { 
                font-family: -apple-system, BlinkMacSystemFont, 'Inter', sans-serif !important; 
                overflow: hidden !important; 
                background-color: #000000 !important; /* Latar luar layar */
                -webkit-font-smoothing: antialiased;
            }
            .fi-topbar, .fi-sidebar, .fi-header, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            
            html, body, .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page, section { 
                padding: 0 !important; margin: 0 !important; gap: 0 !important;
                height: 100vh !important; height: 100dvh !important; 
                max-width: 100% !important; width: 100% !important; 
                background-color: transparent !important; box-shadow: none !important; border: none !important;
            }

            /* Container Responsif bergaya iOS App */
            .ios-app-container {
                width: 100%; height: 100% !important;
                display: flex; flex-direction: column;
                background-color: var(--ios-bg);
                position: fixed; inset: 0;
                overflow: hidden;
            }

            @media (min-width: 640px) {
                .ios-app-container {
                    max-width: 414px; /* Ukuran standar iPhone Max */
                    left: 50%; right: auto;
                    transform: translateX(-50%);
                    box-shadow: 0 0 40px rgba(0, 0, 0, 0.2);
                }
            }

            .ios-content { 
                flex: 1; overflow-y: auto; overflow-x: hidden; 
                padding-bottom: calc(90px + env(safe-area-inset-bottom, 0px)); 
                scrollbar-width: none; 
            }
            .ios-content::-webkit-scrollbar { display: none; }

            /* Utility Classes iOS UI */
            .ios-squircle { 
                border-radius: 22%; /* Simulasi bentuk squircle Apple */
            }

            .ios-list-group {
                background: var(--ios-card);
                border-radius: 12px;
                overflow: hidden;
            }

            .ios-list-item {
                display: flex; gap: 16px; padding: 12px 16px;
                border-bottom: 0.5px solid rgba(60, 60, 67, 0.29); /* Hairline border */
            }
            .ios-list-item:last-child {
                border-bottom: none;
            }

            .menu-icon-wrap { 
                width: 60px; height: 60px; 
                display: flex; justify-content: center; align-items: center; 
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                transition: transform 0.1s ease;
            }
            
            .ios-app-icon:active .menu-icon-wrap { filter: brightness(0.8); transform: scale(0.95); }

            .nav-item { 
                display: flex; flex-direction: column; align-items: center; justify-content: center; 
                width: 100%; height: 100%; color: #999999; 
                text-decoration: none; padding-top: 4px;
            }
            .nav-item.active { color: var(--ios-blue); }
            
            /* Animasi masuk */
            .fade-in { animation: fadeIn 0.4s ease-out forwards; opacity: 0; }
            @keyframes fadeIn { to { opacity: 1; } }
            
            .delay-1 { animation-delay: 0.1s; }
            .delay-2 { animation-delay: 0.2s; }
        </style>
    </div>

    <div class="min-h-screen relative selection:bg-blue-200 selection:text-black" 
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
         
        <div class="ios-app-container z-10">
            
            <div class="ios-content">
                
                <!-- PWA Banner: Satu baris, tombol di kanan -->
                <div x-show="showPwaPrompt" style="display: none; background: rgba(255,255,255,0.85); backdrop-filter: blur(10px); border-bottom: 0.5px solid rgba(0,0,0,0.1); padding: 12px 16px; display: flex; justify-content: space-between; align-items: center;" x-transition>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="ios-squircle" style="width: 38px; height: 38px; background: #007AFF; color: #fff; display: flex; justify-content: center; align-items: center; font-weight: bold; font-size: 16px;">
                            M1
                        </div>
                        <div style="display: flex; flex-direction: column;">
                            <h4 style="font-size: 14px; font-weight: 600; color: #000; margin: 0; letter-spacing: -0.3px;">Smart-M1 App</h4>
                            <p style="font-size: 12px; font-weight: 400; color: #8A8A8E; margin: 0;">Akses lebih cepat & ringan</p>
                        </div>
                    </div>
                    <!-- Tombol di sebelah kanan -->
                    <button @click="installPwa()" style="background-color: #F2F2F7; color: #007AFF; font-weight: 600; font-size: 13px; padding: 6px 16px; border-radius: 16px; cursor: pointer; border: none;">
                        Pasang
                    </button>
                </div>

                @php
                    $hour = \Carbon\Carbon::now('Asia/Jakarta')->format('H');
                    if ($hour >= 5 && $hour < 11) $greeting = 'Selamat Pagi';
                    elseif ($hour >= 11 && $hour < 15) $greeting = 'Selamat Siang';
                    elseif ($hour >= 15 && $hour < 18) $greeting = 'Selamat Sore';
                    else $greeting = 'Selamat Malam';

                    $rawName = $siswa->nama_lengkap ?? Auth::user()->name ?? 'Siswa';
                    $properName = ucwords(strtolower($rawName));
                @endphp

                <div style="padding: 24px 20px 16px 20px;" class="fade-in">
                    <p style="font-size: 13px; font-weight: 500; color: var(--ios-text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">{{ $greeting }}</p>
                    <h1 style="font-size: 32px; font-weight: 700; color: var(--ios-text); letter-spacing: -1px; margin: 0 0 20px 0; line-height: 1.1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $properName }}
                    </h1>
                    
                    <div style="background: var(--ios-card); border-radius: 16px; padding: 12px 16px; display: flex; align-items: center; gap: 16px;">
                        <div style="width: 52px; height: 52px; border-radius: 50%; background-color: var(--ios-bg); display: flex; justify-content: center; align-items: center; overflow: hidden; flex-shrink: 0; border: 0.5px solid rgba(0,0,0,0.1);">
                            @if(isset($siswa->foto) && $siswa->foto && !str_ends_with($siswa->foto, '/'))
                                <img src="{{ url('/uploads/' . $siswa->foto) }}" alt="Foto Profile" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span style="color: var(--ios-text-secondary); font-weight: 500; font-size: 1.2rem;">{{ substr($properName, 0, 1) }}</span>
                            @endif
                        </div>
                        <div style="flex: 1;">
                            <h3 style="font-size: 16px; font-weight: 600; color: var(--ios-text); margin: 0 0 2px 0;">Kelas {{ $siswa->kelas->nama_kelas ?? 'Belum terdaftar' }}</h3>
                            <p style="font-size: 13px; font-weight: 400; color: var(--ios-text-secondary); margin: 0;">Siswa Aktif</p>
                        </div>
                    </div>
                </div>

                <div style="padding: 12px 20px 24px 20px;" class="fade-in delay-1">
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px 12px;">
                        
                        <a href="/siswa/jadwal" class="ios-app-icon" style="text-decoration: none; display: flex; flex-direction: column; align-items: center;">
                            <div class="menu-icon-wrap ios-squircle" style="background: linear-gradient(180deg, #FF5E3A 0%, #FF2A68 100%);">
                                <x-filament::icon icon="heroicon-s-calendar-days" style="width: 32px; height: 32px; color: white;" />
                            </div>
                            <span style="font-size: 11px; font-weight: 500; color: var(--ios-text); margin-top: 6px;">Jadwal</span>
                        </a>
                        
                        <a href="/siswa/rekap-absensi" class="ios-app-icon" style="text-decoration: none; display: flex; flex-direction: column; align-items: center;">
                            <div class="menu-icon-wrap ios-squircle" style="background: linear-gradient(180deg, #87FC70 0%, #0BD318 100%);">
                                <x-filament::icon icon="heroicon-s-document-check" style="width: 32px; height: 32px; color: white;" />
                            </div>
                            <span style="font-size: 11px; font-weight: 500; color: var(--ios-text); margin-top: 6px;">Absensi</span>
                        </a>
                        
                        <a href="/siswa/nilai" class="ios-app-icon" style="text-decoration: none; display: flex; flex-direction: column; align-items: center;">
                            <div class="menu-icon-wrap ios-squircle" style="background: linear-gradient(180deg, #FFCB00 0%, #FF9600 100%);">
                                <x-filament::icon icon="heroicon-s-academic-cap" style="width: 32px; height: 32px; color: white;" />
                            </div>
                            <span style="font-size: 11px; font-weight: 500; color: var(--ios-text); margin-top: 6px;">Nilai</span>
                        </a>
                        
                        <a href="/siswa/e-rapor" class="ios-app-icon" style="text-decoration: none; display: flex; flex-direction: column; align-items: center;">
                            <div class="menu-icon-wrap ios-squircle" style="background: linear-gradient(180deg, #5AC8FA 0%, #007AFF 100%);">
                                <x-filament::icon icon="heroicon-s-folder-open" style="width: 32px; height: 32px; color: white;" />
                            </div>
                            <span style="font-size: 11px; font-weight: 500; color: var(--ios-text); margin-top: 6px;">E-Rapor</span>
                        </a>

                        <a href="/siswa/prestasi" class="ios-app-icon" style="text-decoration: none; display: flex; flex-direction: column; align-items: center;">
                            <div class="menu-icon-wrap ios-squircle" style="background: linear-gradient(180deg, #FFD500 0%, #FFB200 100%);">
                                <x-filament::icon icon="heroicon-s-trophy" style="width: 32px; height: 32px; color: white;" />
                            </div>
                            <span style="font-size: 11px; font-weight: 500; color: var(--ios-text); margin-top: 6px;">Prestasi</span>
                        </a>

                        <a href="/siswa/catatan" class="ios-app-icon" style="text-decoration: none; display: flex; flex-direction: column; align-items: center;">
                            <div class="menu-icon-wrap ios-squircle" style="background: linear-gradient(180deg, #C644FC 0%, #5856D6 100%);">
                                <x-filament::icon icon="heroicon-s-clipboard-document-check" style="width: 32px; height: 32px; color: white;" />
                            </div>
                            <span style="font-size: 11px; font-weight: 500; color: var(--ios-text); margin-top: 6px;">Catatan</span>
                        </a>

                        <a href="/siswa/dokumen" class="ios-app-icon" style="text-decoration: none; display: flex; flex-direction: column; align-items: center;">
                            <div class="menu-icon-wrap ios-squircle" style="background: linear-gradient(180deg, #8E8E93 0%, #4A4A4A 100%);">
                                <x-filament::icon icon="heroicon-s-document-text" style="width: 32px; height: 32px; color: white;" />
                            </div>
                            <span style="font-size: 11px; font-weight: 500; color: var(--ios-text); margin-top: 6px;">Dokumen</span>
                        </a>

                        <a href="/siswa/pegawai" class="ios-app-icon" style="text-decoration: none; display: flex; flex-direction: column; align-items: center;">
                            <div class="menu-icon-wrap ios-squircle" style="background: linear-gradient(180deg, #55EFEF 0%, #00B4DB 100%);">
                                <x-filament::icon icon="heroicon-s-users" style="width: 32px; height: 32px; color: white;" />
                            </div>
                            <span style="font-size: 11px; font-weight: 500; color: var(--ios-text); margin-top: 6px;">Direktori</span>
                        </a>

                    </div>
                </div>

                @if(isset($siswa) && $siswa->is_sekretaris)
                    <div style="padding: 0 20px 24px 20px;" class="fade-in delay-2">
                        <div class="ios-list-group">
                            <a href="/siswa/absensi" class="ios-list-item" style="text-decoration: none; align-items: center;">
                                <div class="ios-squircle" style="width: 32px; height: 32px; background: #007AFF; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <x-filament::icon icon="heroicon-s-clipboard-document-list" style="width: 18px; height: 18px; color: white;" />
                                </div>
                                <div style="flex: 1;">
                                    <h4 style="font-weight: 500; font-size: 16px; margin: 0; color: var(--ios-text);">Jurnal & Absensi</h4>
                                </div>
                                <x-filament::icon icon="heroicon-m-chevron-right" style="width: 20px; height: 20px; color: #C7C7CC;" />
                            </a>
                        </div>
                    </div>
                @endif

                <div style="padding: 0 20px 24px 20px;" class="fade-in delay-2">
                    <h2 style="font-size: 22px; font-weight: 700; color: var(--ios-text); margin: 0 0 12px 10px; letter-spacing: -0.5px;">Informasi Terbaru</h2>
                    
                    <div class="ios-list-group">
                        @forelse($pengumuman as $info)
                            <div class="ios-list-item" style="align-items: flex-start; padding-top: 14px; padding-bottom: 14px;">
                                <div style="width: 12px; height: 12px; border-radius: 50%; background-color: #007AFF; margin-top: 4px; flex-shrink: 0;"></div>
                                <div style="flex: 1;">
                                    <h4 style="font-weight: 600; font-size: 16px; margin: 0 0 4px 0; color: var(--ios-text); letter-spacing: -0.2px;">{{ $info->judul }}</h4>
                                    <div style="font-size: 14px; font-weight: 400; line-height: 1.4; color: var(--ios-text-secondary); margin-bottom: 8px;">
                                        {!! strip_tags($info->isi, '<a><strong><b><i><em><br>') !!}
                                    </div>
                                    <span style="font-size: 12px; font-weight: 500; color: #C7C7CC;">{{ $info->created_at->isoFormat('D MMM YYYY') }}</span>
                                </div>
                            </div>
                        @empty
                            <div style="padding: 32px 20px; text-align: center;">
                                <p style="font-size: 15px; font-weight: 400; margin: 0; color: var(--ios-text-secondary);">Tidak ada informasi baru.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
            </div>

            <div style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-top: 0.5px solid rgba(0,0,0,0.1); position: absolute; bottom: 0; width: 100%; height: 83px; display: flex; justify-content: space-around; padding-top: 10px; padding-bottom: env(safe-area-inset-bottom, 20px); z-index: 50;">
                
                <a href="/siswa" class="nav-item active">
                    <x-filament::icon icon="heroicon-s-home" style="width: 26px; height: 26px; margin-bottom: 4px;" />
                    <span style="font-size: 10px; font-weight: 500;">Beranda</span>
                </a>
                
                <a href="/siswa/riwayat" class="nav-item">
                    <x-filament::icon icon="heroicon-s-clock" style="width: 26px; height: 26px; margin-bottom: 4px;" />
                    <span style="font-size: 10px; font-weight: 500;">Riwayat</span>
                </a>
                
                <a href="/siswa/kartu-pelajar" class="nav-item">
                    <x-filament::icon icon="heroicon-s-qr-code" style="width: 26px; height: 26px; margin-bottom: 4px;" />
                    <span style="font-size: 10px; font-weight: 500;">ID Card</span>
                </a>
                
                @php
                    $unreadPesan = 0;
                    if(isset($siswa)) {
                        $unreadPesan = \App\Models\PesanBantuan::where('siswa_id', $siswa->id)->where('is_read_siswa', false)->count();
                    }
                @endphp
                <a href="/siswa/pesan" class="nav-item">
                    <div style="position: relative;">
                        <x-filament::icon icon="heroicon-s-chat-bubble-2-text" style="width: 26px; height: 26px; margin-bottom: 4px;" />
                        @if($unreadPesan > 0)
                            <span style="position: absolute; top: -2px; right: -4px; width: 10px; height: 10px; background-color: #FF3B30; border: 2px solid white; border-radius: 50%;"></span>
                        @endif
                    </div>
                    <span style="font-size: 10px; font-weight: 500;">Bantuan</span>
                </a>
                
                <a @click="showLogoutSheet = true" class="nav-item" style="cursor: pointer;">
                    <x-filament::icon icon="heroicon-s-arrow-right-start-on-rectangle" style="width: 26px; height: 26px; margin-bottom: 4px;" />
                    <span style="font-size: 10px; font-weight: 500;">Keluar</span>
                </a>
            </div>

            <div x-show="showLogoutSheet" style="display: none; position: absolute; inset: 0; background-color: rgba(0,0,0,0.4); z-index: 99;" x-transition.opacity @click="showLogoutSheet = false"></div>
            
            <div x-show="showLogoutSheet" style="display: none; position: absolute; bottom: 0; left: 0; right: 0; z-index: 100; padding: 0 10px calc(10px + env(safe-area-inset-bottom, 0px));"
                 x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="transform translate-y-full" 
                 x-transition:enter-end="transform translate-y-0" 
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="transform translate-y-0" 
                 x-transition:leave-end="transform translate-y-full">
                
                <!-- Action Group -->
                <div style="background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); border-radius: 14px; overflow: hidden; margin-bottom: 8px;">
                    <div style="padding: 16px; border-bottom: 0.5px solid rgba(0,0,0,0.1); text-align: center;">
                        <span style="font-size: 13px; color: #8A8A8E; font-weight: 500;">Apakah Anda yakin ingin keluar dari aplikasi?</span>
                    </div>
                    <button type="button" wire:click="keluarAplikasi" wire:loading.attr="disabled" style="width: 100%; padding: 18px; color: #FF3B30; font-size: 20px; font-weight: 400; border: none; background: transparent; cursor: pointer;">
                        <span wire:loading.remove wire:target="keluarAplikasi">Keluar</span>
                        <span wire:loading wire:target="keluarAplikasi">Memproses...</span>
                    </button>
                </div>
                
                <!-- Cancel Button -->
                <button @click="showLogoutSheet = false" style="width: 100%; padding: 18px; background: #fff; border-radius: 14px; color: #007AFF; font-size: 20px; font-weight: 600; border: none; cursor: pointer;">
                    Batal
                </button>
            </div>

        </div>
    </div>
</x-filament-panels::page.simple>