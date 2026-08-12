<x-filament-panels::page.simple>
    <div wire:ignore>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        
        <style>
            body { 
                font-family: 'Inter', sans-serif !important; 
                overflow: hidden !important; 
                background-color: #f1f5f9 !important; /* Slate 100 */
                color: #0f172a !important; /* Slate 900 */
                -webkit-font-smoothing: antialiased;
            }
            .fi-topbar, .fi-sidebar, .fi-header, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            
            html, body, .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page, section { 
                padding: 0 !important; margin: 0 !important; gap: 0 !important;
                height: 100vh !important; height: 100dvh !important; 
                max-width: 100% !important; width: 100% !important; 
                background-color: transparent !important; box-shadow: none !important; border: none !important;
            }

            /* Container Responsif bergaya Modern App */
            .android-app-container {
                width: 100%; height: 100% !important;
                display: flex; flex-direction: column;
                background-color: #f8fafc; /* Slate 50 */
                position: fixed; inset: 0;
                overflow: hidden;
            }

            @media (min-width: 640px) {
                .android-app-container {
                    max-width: 420px;
                    left: 50%; right: auto;
                    transform: translateX(-50%);
                    box-shadow: 0 0 40px rgba(15, 23, 42, 0.1);
                    border-left: 1px solid #e2e8f0;
                    border-right: 1px solid #e2e8f0;
                }
            }

            .android-content { 
                flex: 1; overflow-y: auto; overflow-x: hidden; 
                padding-bottom: calc(90px + env(safe-area-inset-bottom, 0px)); 
                scrollbar-width: none; 
            }
            .android-content::-webkit-scrollbar { display: none; }

            /* Utility Classes Modern UI */
            .modern-card { 
                background-color: #ffffff; 
                border-radius: 20px;
                box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
                border: 1px solid #f1f5f9;
            }

            .menu-item { 
                display: flex; flex-direction: column; align-items: center; 
                text-decoration: none; color: #334155; 
                font-weight: 600; font-size: 11px; gap: 10px; 
                transition: transform 0.2s ease, color 0.2s ease;
            }
            
            .menu-icon-wrap { 
                width: 52px; height: 52px; 
                display: flex; justify-content: center; align-items: center; 
                border-radius: 16px;
                background-color: #f8fafc; /* Sangat soft abu-abu */
                color: #3b82f6; /* Aksen ikon biru profesional */
                transition: all 0.2s ease;
            }
            
            .menu-item:active { transform: scale(0.95); }
            .menu-item:active .menu-icon-wrap { background-color: #eff6ff; }

            .nav-item { 
                display: flex; flex-direction: column; align-items: center; justify-content: center; 
                width: 100%; height: 100%; color: #64748b; 
                transition: all 0.2s ease; text-decoration: none; 
            }
            .nav-item:active, .nav-item.active { color: #1e40af; }
            .nav-item:active .nav-icon, .nav-item.active .nav-icon { transform: scale(1.1); color: #1e40af; }
            .nav-icon { transition: all 0.2s ease; }
            
            /* Animasi masuk */
            .fade-in-up { animation: fadeInUp 0.5s ease-out forwards; opacity: 0; transform: translateY(10px); }
            @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
            
            .delay-1 { animation-delay: 0.1s; }
            .delay-2 { animation-delay: 0.2s; }
            .delay-3 { animation-delay: 0.3s; }
        </style>
    </div>

    <div class="min-h-screen relative selection:bg-blue-900 selection:text-white" 
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
         
        <div class="android-app-container z-10">
            
            <div class="android-content">
                
                <div x-show="showPwaPrompt" style="display: none; background-color: #eff6ff; border-bottom: 1px solid #bfdbfe; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center;" x-transition>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 36px; height: 36px; background-color: #3b82f6; color: #fff; display: flex; justify-content: center; align-items: center; border-radius: 10px; box-shadow: 0 2px 8px rgba(59,130,246,0.3);">
                            <x-filament::icon icon="heroicon-s-arrow-down-tray" style="width: 18px; height: 18px;" />
                        </div>
                        <div>
                            <h4 style="font-size: 13px; font-weight: 700; color: #1e3a8a; margin: 0;">Pasang Aplikasi</h4>
                            <p style="font-size: 11px; font-weight: 500; color: #3b82f6; margin: 0;">Akses lebih cepat & ringan</p>
                        </div>
                    </div>
                    <button @click="installPwa()" style="background-color: #1e40af; color: #ffffff; font-weight: 600; font-size: 12px; padding: 8px 16px; border-radius: 8px; cursor: pointer; border: none;">
                        Pasang
                    </button>
                </div>

                <div style="background: linear-gradient(145deg, #0f172a 0%, #1e3a8a 100%); padding: 32px 24px 40px 24px; border-bottom-left-radius: 32px; border-bottom-right-radius: 32px; position: relative; box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15);">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="background-color: rgba(255,255,255,0.1); padding: 6px 12px; border-radius: 20px; backdrop-filter: blur(8px);">
                                <span style="font-size: 11px; font-weight: 600; color: #e2e8f0; letter-spacing: 0.5px;">SMART-M1 PORTAL</span>
                            </div>
                        </div>
                        <x-filament::icon icon="heroicon-o-bell" style="width: 22px; height: 22px; color: rgba(255,255,255,0.7);" />
                    </div>

                    @php
                        $hour = \Carbon\Carbon::now('Asia/Jakarta')->format('H');
                        if ($hour >= 5 && $hour < 11) $greeting = 'Selamat pagi';
                        elseif ($hour >= 11 && $hour < 15) $greeting = 'Selamat siang';
                        elseif ($hour >= 15 && $hour < 18) $greeting = 'Selamat sore';
                        else $greeting = 'Selamat malam';

                        $rawName = $siswa->nama_lengkap ?? Auth::user()->name ?? 'Peserta Didik';
                        $properName = ucwords(strtolower($rawName));
                    @endphp

                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px;">
                        <div style="flex: 1; min-width: 0;">
                            <p style="font-size: 13px; font-weight: 500; color: #94a3b8; margin-bottom: 4px;">{{ $greeting }},</p>
                            <h1 style="font-size: 20px; font-weight: 700; color: #ffffff; margin: 0 0 10px 0; line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $properName }}
                            </h1>
                            <div style="display: inline-flex; align-items: center; gap: 6px; background-color: rgba(255,255,255,0.15); padding: 4px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; color: #f8fafc;">
                                <div style="width: 6px; height: 6px; border-radius: 50%; background-color: #34d399;"></div>
                                Kelas {{ $siswa->kelas->nama_kelas ?? 'Belum ada data' }}
                            </div>
                        </div>

                        <div style="width: 64px; height: 64px; background-color: #f1f5f9; border: 2px solid #ffffff; display: flex; justify-content: center; align-items: center; overflow: hidden; flex-shrink: 0; border-radius: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                            @if(isset($siswa->foto) && $siswa->foto && !str_ends_with($siswa->foto, '/'))
                                <img src="{{ url('/uploads/' . $siswa->foto) }}" alt="Foto Profile" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span style="color: #1e3a8a; font-weight: 700; font-size: 1.5rem;">{{ substr($properName, 0, 1) }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div style="padding: 0 20px; margin-top: -24px; position: relative; z-index: 20;" class="fade-in-up">
                    <div class="modern-card" style="padding: 24px 20px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px 12px;">
                        
                        <a href="/siswa/jadwal" class="menu-item">
                            <div class="menu-icon-wrap">
                                <x-filament::icon icon="heroicon-s-calendar-days" style="width: 24px; height: 24px;" />
                            </div>
                            <span>Jadwal</span>
                        </a>
                        
                        <a href="/siswa/rekap-absensi" class="menu-item">
                            <div class="menu-icon-wrap">
                                <x-filament::icon icon="heroicon-s-document-check" style="width: 24px; height: 24px;" />
                            </div>
                            <span>Absensi</span>
                        </a>
                        
                        <a href="/siswa/nilai" class="menu-item">
                            <div class="menu-icon-wrap">
                                <x-filament::icon icon="heroicon-s-academic-cap" style="width: 24px; height: 24px;" />
                            </div>
                            <span>Nilai</span>
                        </a>
                        
                        <a href="/siswa/e-rapor" class="menu-item">
                            <div class="menu-icon-wrap">
                                <x-filament::icon icon="heroicon-s-folder-open" style="width: 24px; height: 24px;" />
                            </div>
                            <span>E-Rapor</span>
                        </a>

                        <a href="/siswa/prestasi" class="menu-item">
                            <div class="menu-icon-wrap">
                                <x-filament::icon icon="heroicon-s-trophy" style="width: 24px; height: 24px;" />
                            </div>
                            <span>Prestasi</span>
                        </a>

                        <a href="/siswa/catatan" class="menu-item">
                            <div class="menu-icon-wrap">
                                <x-filament::icon icon="heroicon-s-clipboard-document-check" style="width: 24px; height: 24px;" />
                            </div>
                            <span>Catatan</span>
                        </a>

                        <a href="/siswa/dokumen" class="menu-item">
                            <div class="menu-icon-wrap">
                                <x-filament::icon icon="heroicon-s-document-text" style="width: 24px; height: 24px;" />
                            </div>
                            <span>Dokumen</span>
                        </a>

                        <a href="/siswa/pegawai" class="menu-item">
                            <div class="menu-icon-wrap">
                                <x-filament::icon icon="heroicon-s-users" style="width: 24px; height: 24px;" />
                            </div>
                            <span>Direktori</span>
                        </a>

                    </div>
                </div>

                @if(isset($siswa) && $siswa->is_sekretaris)
                    <div style="padding: 0 20px; margin-top: 20px;" class="fade-in-up delay-1">
                        <a href="/siswa/absensi" style="text-decoration: none; display: flex; align-items: center; justify-content: space-between; background-color: #1e3a8a; padding: 16px 20px; border-radius: 20px; box-shadow: 0 8px 15px rgba(30, 58, 138, 0.15);">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <div style="background-color: rgba(255,255,255,0.15); padding: 10px; border-radius: 14px;">
                                    <x-filament::icon icon="heroicon-s-clipboard-document-list" style="width: 24px; height: 24px; color: #ffffff;" />
                                </div>
                                <div>
                                    <h4 style="font-weight: 700; font-size: 14px; margin: 0 0 2px 0; color: #ffffff;">Jurnal & Absensi Kelas</h4>
                                    <p style="font-size: 11px; font-weight: 500; margin: 0; color: #93c5fd;">Tugas Khusus Sekretaris</p>
                                </div>
                            </div>
                            <div style="background-color: rgba(255,255,255,0.1); border-radius: 50%; padding: 6px;">
                                <x-filament::icon icon="heroicon-m-chevron-right" style="width: 18px; height: 18px; color: #ffffff;" />
                            </div>
                        </a>
                    </div>
                @endif

                <div style="padding: 24px 20px;" class="fade-in-up delay-2">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <h3 style="font-size: 16px; font-weight: 700; margin: 0; color: #0f172a;">Papan Informasi</h3>
                        <a href="#" style="font-size: 12px; color: #3b82f6; font-weight: 600; text-decoration: none;">Lihat Semua</a>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @forelse($pengumuman as $info)
                            <div class="modern-card" style="padding: 16px; display: flex; gap: 16px; align-items: flex-start;">
                                <div style="width: 44px; height: 44px; background-color: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #64748b;">
                                    <x-filament::icon icon="heroicon-o-megaphone" style="width: 22px; height: 22px;" />
                                </div>
                                <div>
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                                        <h4 style="font-weight: 700; font-size: 14px; margin: 0; color: #1e293b;">{{ $info->judul }}</h4>
                                    </div>
                                    <div style="font-size: 13px; font-weight: 400; line-height: 1.5; color: #64748b; margin-bottom: 8px;">
                                        {!! strip_tags($info->isi, '<a><strong><b><i><em><br>') !!}
                                    </div>
                                    <span style="font-size: 11px; font-weight: 500; color: #94a3b8;">{{ $info->created_at->isoFormat('D MMM YYYY') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="modern-card" style="padding: 32px 20px; text-align: center;">
                                <x-filament::icon icon="heroicon-o-inbox" style="width: 40px; height: 40px; margin: 0 auto 12px auto; color: #cbd5e1;" />
                                <p style="font-size: 13px; font-weight: 500; margin: 0; color: #64748b;">Belum ada informasi terbaru.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
            </div>

            <div style="background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); border-top: 1px solid #f1f5f9; position: absolute; bottom: 0; width: 100%; height: 75px; display: flex; justify-content: space-around; align-items: center; z-index: 50; padding-bottom: env(safe-area-inset-bottom, 0px); box-shadow: 0 -4px 20px rgba(0,0,0,0.03);">
                
                <a href="/siswa" class="nav-item active">
                    <x-filament::icon icon="heroicon-s-home" style="width: 24px; height: 24px; margin-bottom: 4px;" class="nav-icon" />
                    <span style="font-size: 10px; font-weight: 600;">Beranda</span>
                </a>
                
                <a href="/siswa/riwayat" class="nav-item">
                    <x-filament::icon icon="heroicon-o-clock" style="width: 24px; height: 24px; margin-bottom: 4px;" class="nav-icon" />
                    <span style="font-size: 10px; font-weight: 500;">Riwayat</span>
                </a>
                
                <!-- Center Floating QR Button -->
                <div style="position: relative; width: 60px; display: flex; justify-content: center;">
                    <a href="/siswa/kartu-pelajar" style="position: absolute; top: -45px; background-color: #1e3a8a; width: 56px; height: 56px; border-radius: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; box-shadow: 0 8px 20px rgba(30, 58, 138, 0.3); border: 4px solid #ffffff; transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.9)'" onmouseup="this.style.transform='scale(1)'">
                        <x-filament::icon icon="heroicon-s-qr-code" style="width: 24px; height: 24px; color: #ffffff;" />
                    </a>
                </div>
                
                @php
                    $unreadPesan = 0;
                    if(isset($siswa)) {
                        $unreadPesan = \App\Models\PesanBantuan::where('siswa_id', $siswa->id)->where('is_read_siswa', false)->count();
                    }
                @endphp
                <a href="/siswa/pesan" class="nav-item">
                    <div style="position: relative;">
                        <x-filament::icon icon="heroicon-o-chat-bubble-left-ellipsis" style="width: 24px; height: 24px; margin-bottom: 4px;" class="nav-icon" />
                        @if($unreadPesan > 0)
                            <span style="position: absolute; top: 0px; right: -2px; width: 8px; height: 8px; background-color: #ef4444; border: 2px solid white; border-radius: 50%;"></span>
                        @endif
                    </div>
                    <span style="font-size: 10px; font-weight: 500;">Bantuan</span>
                </a>
                
                <a @click="showLogoutSheet = true" class="nav-item" style="cursor: pointer;">
                    <x-filament::icon icon="heroicon-o-arrow-right-start-on-rectangle" style="width: 24px; height: 24px; margin-bottom: 4px;" class="nav-icon" />
                    <span style="font-size: 10px; font-weight: 500;">Keluar</span>
                </a>
            </div>

            <div x-show="showLogoutSheet" style="display: none; position: absolute; inset: 0; background-color: rgba(15,23,42,0.4); backdrop-filter: blur(4px); z-index: 99;" x-transition.opacity @click="showLogoutSheet = false"></div>
            
            <div x-show="showLogoutSheet" style="display: none; position: absolute; bottom: 0; left: 0; right: 0; background-color: #ffffff; border-top-left-radius: 28px; border-top-right-radius: 28px; z-index: 100; padding: 32px 24px calc(24px + env(safe-area-inset-bottom, 0px)); box-shadow: 0 -10px 40px rgba(0,0,0,0.1);"
                 x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="transform translate-y-full" 
                 x-transition:enter-end="transform translate-y-0" 
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="transform translate-y-0" 
                 x-transition:leave-end="transform translate-y-full">
                
                <div style="width: 40px; height: 5px; background-color: #e2e8f0; border-radius: 10px; margin: 0 auto 24px auto;"></div>
                
                <h3 style="font-size: 20px; font-weight: 700; text-align: center; margin: 0 0 8px 0; color: #0f172a;">Akhiri Sesi</h3>
                <p style="font-size: 14px; font-weight: 400; text-align: center; margin: 0 0 32px 0; color: #64748b; line-height: 1.5;">Apakah Anda yakin ingin keluar dari portal siswa?</p>
                
                <div style="display: flex; gap: 12px; flex-direction: column;">
                    <button type="button" wire:click="keluarAplikasi" wire:loading.attr="disabled" style="width: 100%; padding: 14px; background-color: #ef4444; color: #fff; font-weight: 600; font-size: 14px; border-radius: 16px; border: none; cursor: pointer;">
                        <span wire:loading.remove wire:target="keluarAplikasi">Ya, Keluar Sekarang</span>
                        <span wire:loading wire:target="keluarAplikasi">Memproses...</span>
                    </button>
                    
                    <button @click="showLogoutSheet = false" style="width: 100%; padding: 14px; background-color: #f1f5f9; color: #334155; font-weight: 600; font-size: 14px; border-radius: 16px; border: none; cursor: pointer;">
                        Batal
                    </button>
                </div>
            </div>

        </div>
    </div>
</x-filament-panels::page.simple>