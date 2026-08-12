<x-filament-panels::page.simple>
    <div wire:ignore>
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Space Grotesk', sans-serif !important; overflow: hidden !important; background-color: #F9FAFB !important; color: #111827 !important; }
            .fi-topbar, .fi-sidebar, .fi-header, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            
            html, body, .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page, section { 
                padding: 0 !important; margin: 0 !important; gap: 0 !important;
                height: 100vh !important; height: 100dvh !important; 
                max-width: 100% !important; width: 100% !important; 
                background-color: transparent !important; box-shadow: none !important; border: none !important;
            }

            .bg-structural {
                background-image: 
                    linear-gradient(to right, rgba(17, 24, 39, 0.05) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(17, 24, 39, 0.05) 1px, transparent 1px);
                background-size: 32px 32px;
            }

            /* Container Responsif bergaya Neo-Brutalisme */
            .android-app-container {
                width: 100%; height: 100% !important;
                display: flex; flex-direction: column;
                background-color: #F9FAFB;
                position: fixed; inset: 0;
                overflow: hidden;
            }

            @media (min-width: 640px) {
                .android-app-container {
                    max-width: 460px;
                    left: 50%; right: auto;
                    transform: translateX(-50%);
                    border-left: 4px solid #111827;
                    border-right: 4px solid #111827;
                    box-shadow: 16px 0px 0px 0px rgba(17, 24, 39, 0.1);
                }
            }

            .android-content { flex: 1; overflow-y: auto; overflow-x: hidden; padding-bottom: calc(90px + env(safe-area-inset-bottom, 0px)); scrollbar-width: none; }
            .android-content::-webkit-scrollbar { display: none; }

            /* Utility Classes Brutalisme */
            .brutal-card { 
                background-color: #ffffff; 
                border: 3px solid #111827; 
                box-shadow: 5px 5px 0px 0px #111827; 
            }

            .brutal-btn { 
                border: 3px solid #111827; 
                box-shadow: 4px 4px 0px 0px #111827; 
                transition: all 0.1s ease-out; 
            }
            
            .brutal-btn:active { 
                transform: translate(4px, 4px); 
                box-shadow: 0px 0px 0px 0px transparent; 
            }

            .menu-item { display: flex; flex-direction: column; align-items: center; text-decoration: none; color: #111827; font-weight: 800; font-size: 11px; gap: 8px; letter-spacing: 0.5px; text-transform: uppercase;}
            
            .menu-icon-wrap { width: 56px; height: 56px; display: flex; justify-content: center; align-items: center; border: 3px solid #111827; box-shadow: 4px 4px 0px 0px #111827; transition: all 0.1s; background-color: #fff;}
            .menu-item:active .menu-icon-wrap { transform: translate(4px, 4px); box-shadow: 0px 0px 0px 0px transparent; }

            .nav-item { display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; height: 100%; color: #111827; transition: all 0.1s; text-decoration: none; border-right: 3px solid #111827; background-color: #ffffff; }
            .nav-item:last-child { border-right: none; }
            .nav-item:active { background-color: #e5e7eb; }
            
            a { text-decoration: underline; font-weight: bold; }
        </style>
    </div>

    <div class="bg-base-50 bg-structural min-h-screen relative selection:bg-base-900 selection:text-white" 
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
                
                <div x-show="showPwaPrompt" style="display: none; background-color: #fde047; border-bottom: 3px solid #111827; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center;" x-transition>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 36px; height: 36px; background-color: #111827; color: #fff; display: flex; justify-content: center; align-items: center; border-radius: 0;">
                            <x-filament::icon icon="heroicon-s-arrow-down-tray" style="width: 20px; height: 20px;" />
                        </div>
                        <div>
                            <h4 style="font-size: 13px; font-weight: 900; color: #111827; margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">Install Aplikasi</h4>
                            <p style="font-size: 11px; font-weight: 700; color: #374151; margin: 0;">Akses lebih cepat & ringan</p>
                        </div>
                    </div>
                    <button @click="installPwa()" class="brutal-btn" style="background-color: #fff; color: #111827; font-weight: 900; font-size: 11px; padding: 8px 16px; text-transform: uppercase; cursor: pointer;">
                        Install
                    </button>
                </div>

                <div style="background-color: #3b82f6; border-bottom: 4px solid #111827; padding: 28px 24px; position: relative; overflow: hidden;">
                    
                    <div style="position: absolute; right: -20px; top: -20px; opacity: 0.2; transform: rotate(15deg);">
                        <svg width="150" height="150" viewBox="0 0 24 24" fill="none" stroke="#111827" stroke-width="2" stroke-linecap="square" stroke-linejoin="miter"><polygon points="12 2 2 22 22 22"></polygon></svg>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; position: relative; z-index: 10;">
                        <div style="background-color: #111827; color: #fff; padding: 6px 12px; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; border: 2px solid #111827; box-shadow: 3px 3px 0px 0px #ffffff;">
                            SMART-M1 SMAN 1
                        </div>
                    </div>

                    @php
                        $hour = \Carbon\Carbon::now('Asia/Jakarta')->format('H');
                        if ($hour >= 5 && $hour < 11) $greeting = 'SELAMAT PAGI';
                        elseif ($hour >= 11 && $hour < 15) $greeting = 'SELAMAT SIANG';
                        elseif ($hour >= 15 && $hour < 18) $greeting = 'SELAMAT SORE';
                        else $greeting = 'SELAMAT MALAM';

                        $rawName = $siswa->nama_lengkap ?? Auth::user()->name ?? 'Siswa';
                        $properName = strtoupper($rawName);
                    @endphp

                    <div style="display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; position: relative; z-index: 10;">
                        <div style="flex: 1; min-width: 0;">
                            <p style="font-size: 13px; font-weight: 900; color: #111827; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em; text-shadow: 1px 1px 0px #fff;">{{ $greeting }}</p>
                            <h1 style="font-size: 24px; font-weight: 900; color: #ffffff; margin: 0 0 12px 0; line-height: 1.1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-transform: uppercase; text-shadow: 2px 2px 0px #111827;">
                                {{ $properName }}
                            </h1>
                            <div style="display: inline-block; background-color: #fde047; border: 2px solid #111827; padding: 6px 14px; font-size: 12px; font-weight: 900; box-shadow: 3px 3px 0px 0px #111827; text-transform: uppercase;">
                                KELAS {{ $siswa->kelas->nama_kelas ?? 'BELUM ADA' }}
                            </div>
                        </div>

                        <div style="width: 80px; height: 80px; background-color: #fff; border: 3px solid #111827; box-shadow: 5px 5px 0px 0px #111827; display: flex; justify-content: center; align-items: center; overflow: hidden; flex-shrink: 0; border-radius: 0;">
                            @if(isset($siswa->foto) && $siswa->foto && !str_ends_with($siswa->foto, '/'))
                                <img src="{{ url('/uploads/' . $siswa->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span style="color: #111827; font-weight: 900; font-size: 2.5rem;">{{ substr($properName, 0, 1) }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div style="padding: 24px 20px;">
                    <div class="brutal-card" style="padding: 24px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px 12px;">
                        
                        <a href="/siswa/jadwal" class="menu-item">
                            <div class="menu-icon-wrap" style="background-color: #bae6fd;">
                                <x-filament::icon icon="heroicon-s-calendar-days" style="width: 28px; height: 28px; color: #111827;" />
                            </div>
                            <span>JADWAL</span>
                        </a>
                        
                        <a href="/siswa/rekap-absensi" class="menu-item">
                            <div class="menu-icon-wrap" style="background-color: #86efac;">
                                <x-filament::icon icon="heroicon-s-document-check" style="width: 28px; height: 28px; color: #111827;" />
                            </div>
                            <span>ABSENSI</span>
                        </a>
                        
                        <a href="/siswa/nilai" class="menu-item">
                            <div class="menu-icon-wrap" style="background-color: #fde047;">
                                <x-filament::icon icon="heroicon-s-academic-cap" style="width: 28px; height: 28px; color: #111827;" />
                            </div>
                            <span>NILAI</span>
                        </a>
                        
                        <a href="/siswa/e-rapor" class="menu-item">
                            <div class="menu-icon-wrap" style="background-color: #d8b4fe;">
                                <x-filament::icon icon="heroicon-s-folder-open" style="width: 28px; height: 28px; color: #111827;" />
                            </div>
                            <span>E-RAPOR</span>
                        </a>

                        <a href="/siswa/prestasi" class="menu-item">
                            <div class="menu-icon-wrap" style="background-color: #f9a8d4;">
                                <x-filament::icon icon="heroicon-s-trophy" style="width: 28px; height: 28px; color: #111827;" />
                            </div>
                            <span>PRESTASI</span>
                        </a>

                        <a href="/siswa/catatan" class="menu-item">
                            <div class="menu-icon-wrap" style="background-color: #a5b4fc;">
                                <x-filament::icon icon="heroicon-s-clipboard-document-check" style="width: 28px; height: 28px; color: #111827;" />
                            </div>
                            <span>CATATAN</span>
                        </a>

                        <a href="/siswa/dokumen" class="menu-item">
                            <div class="menu-icon-wrap" style="background-color: #67e8f9;">
                                <x-filament::icon icon="heroicon-s-folder-arrow-down" style="width: 28px; height: 28px; color: #111827;" />
                            </div>
                            <span>DOKUMEN</span>
                        </a>

                        <a href="/siswa/pegawai" class="menu-item">
                            <div class="menu-icon-wrap" style="background-color: #fdba74;">
                                <x-filament::icon icon="heroicon-s-users" style="width: 28px; height: 28px; color: #111827;" />
                            </div>
                            <span>PEGAWAI</span>
                        </a>

                        <a href="/siswa/tentang" class="menu-item">
                            <div class="menu-icon-wrap" style="background-color: #bef264;">
                                <x-filament::icon icon="heroicon-s-star" style="width: 28px; height: 28px; color: #111827;" />
                            </div>
                            <span>TENTANG</span>
                        </a>

                        <a @click="showLogoutSheet = true" class="menu-item cursor-pointer">
                            <div class="menu-icon-wrap" style="background-color: #fca5a5;">
                                <x-filament::icon icon="heroicon-s-arrow-right-on-rectangle" style="width: 28px; height: 28px; color: #111827;" />
                            </div>
                            <span>KELUAR</span>
                        </a>

                    </div>
                </div>

                @if(isset($siswa) && $siswa->is_sekretaris)
                    <div style="padding: 0 20px; margin-bottom: 28px;">
                        <a href="/siswa/absensi" class="brutal-btn" style="text-decoration: none; display: flex; align-items: center; justify-content: space-between; background-color: #10b981; padding: 16px 20px; color: #111827;">
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div style="background-color: #fff; padding: 12px; border: 3px solid #111827;">
                                    <x-filament::icon icon="heroicon-s-users" style="width: 28px; height: 28px; color: #111827;" />
                                </div>
                                <div>
                                    <h4 style="font-weight: 900; font-size: 16px; margin: 0 0 4px 0; text-transform: uppercase;">Input Absensi Kelas</h4>
                                    <p style="font-size: 11px; font-weight: 800; margin: 0; background-color: #111827; color: #fff; display: inline-block; padding: 4px 8px; letter-spacing: 1px;">KHUSUS SEKRETARIS</p>
                                </div>
                            </div>
                            <div style="background-color: #111827; padding: 6px; display: flex; align-items: center; justify-content: center; border: 2px solid #fff;">
                                <x-filament::icon icon="heroicon-m-chevron-right" style="width: 20px; height: 20px; color: #fff;" />
                            </div>
                        </a>
                    </div>
                @endif

                <div style="padding: 0 20px 20px 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 20px; border-bottom: 4px solid #111827; padding-bottom: 12px;">
                        <h3 style="font-size: 18px; font-weight: 900; margin: 0; color: #111827; text-transform: uppercase; letter-spacing: 1px;">Papan Informasi</h3>
                        <span style="font-size: 11px; color: #111827; font-weight: 900; text-transform: uppercase; border: 2px solid #111827; padding: 4px 8px; background-color: #fff;">Lihat Semua</span>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 20px;">
                        @forelse($pengumuman as $info)
                            <div class="brutal-card" style="padding: 20px; display: flex; gap: 16px; background-color: #fff;">
                                <div style="width: 48px; height: 48px; background-color: #fde047; border: 3px solid #111827; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <x-filament::icon icon="heroicon-s-bell-alert" style="width: 24px; height: 24px; color: #111827;" />
                                </div>
                                <div>
                                    <h4 style="font-weight: 900; font-size: 16px; margin: 0 0 6px 0; color: #111827; text-transform: uppercase;">{{ $info->judul }}</h4>
                                    <div style="font-size: 13px; font-weight: 600; line-height: 1.6; color: #374151; margin-bottom: 12px;">
                                        {!! strip_tags($info->isi, '<a><strong><b><i><em><br>') !!}
                                    </div>
                                    <span style="background-color: #111827; color: #fff; padding: 4px 10px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">{{ $info->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="brutal-card" style="padding: 40px 20px; text-align: center; background-color: #f3f4f6;">
                                <x-filament::icon icon="heroicon-o-inbox" style="width: 48px; height: 48px; margin: 0 auto 16px auto; color: #111827;" />
                                <p style="font-size: 14px; font-weight: 900; margin: 0; color: #111827; text-transform: uppercase;">Belum ada pengumuman.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
            </div>

            <div style="background-color: #ffffff; border-top: 4px solid #111827; position: absolute; bottom: 0; width: 100%; height: 75px; display: flex; justify-content: space-between; align-items: stretch; z-index: 50; padding-bottom: env(safe-area-inset-bottom, 0px);">
                
                <a href="/siswa" class="nav-item">
                    <x-filament::icon icon="heroicon-s-home" style="width: 26px; height: 26px; margin-bottom: 4px; color: #111827;" />
                    <span style="font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Beranda</span>
                </a>
                
                <a href="/siswa/riwayat" class="nav-item">
                    <x-filament::icon icon="heroicon-s-clipboard-document-list" style="width: 26px; height: 26px; margin-bottom: 4px; color: #111827;" />
                    <span style="font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Riwayat</span>
                </a>
                
                <div style="position: relative; flex: 1; display: flex; justify-content: center; border-right: 3px solid #111827; background-color: #f3f4f6;">
                    <a href="/siswa/kartu-pelajar" class="brutal-btn" style="position: absolute; top: -30px; background-color: #3b82f6; width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                        <x-filament::icon icon="heroicon-s-qr-code" style="width: 32px; height: 32px; color: #111827;" />
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
                        <x-filament::icon icon="heroicon-s-chat-bubble-left-ellipsis" style="width: 26px; height: 26px; margin-bottom: 4px; color: #111827;" />
                        @if($unreadPesan > 0)
                            <span style="position: absolute; top: -4px; right: -6px; width: 14px; height: 14px; background-color: #ef4444; border: 3px solid #111827; border-radius: 0;"></span>
                        @endif
                    </div>
                    <span style="font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Pesan</span>
                </a>
                
                <a href="/siswa/profil" class="nav-item">
                    <x-filament::icon icon="heroicon-s-user" style="width: 26px; height: 26px; margin-bottom: 4px; color: #111827;" />
                    <span style="font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">Profil</span>
                </a>
            </div>

            <div x-show="showLogoutSheet" style="display: none; position: absolute; inset: 0; background-color: rgba(17,24,39,0.85); z-index: 99;" x-transition.opacity @click="showLogoutSheet = false"></div>
            
            <div x-show="showLogoutSheet" style="display: none; position: absolute; bottom: 0; left: 0; right: 0; background-color: #ffffff; border-top: 5px solid #111827; box-shadow: 0 -10px 0px 0px rgba(17,24,39,1); z-index: 100; padding: 40px 24px calc(24px + env(safe-area-inset-bottom, 0px));"
                 x-transition:enter="transition ease-out duration-200" 
                 x-transition:enter-start="transform translate-y-full" 
                 x-transition:enter-end="transform translate-y-0" 
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="transform translate-y-0" 
                 x-transition:leave-end="transform translate-y-full">
                
                <h3 style="font-size: 24px; font-weight: 900; text-align: center; margin: 0 0 12px 0; color: #111827; text-transform: uppercase; letter-spacing: 1px;">Akhiri Sesi?</h3>
                <p style="font-size: 14px; font-weight: 600; text-align: center; margin: 0 0 32px 0; color: #4b5563; line-height: 1.5;">Apakah Anda yakin ingin keluar dari portal ini?</p>
                
                <div style="display: flex; gap: 16px;">
                    <button @click="showLogoutSheet = false" class="brutal-btn" style="flex: 1; padding: 16px; background-color: #f3f4f6; color: #111827; font-weight: 900; font-size: 14px; text-transform: uppercase; cursor: pointer; letter-spacing: 1px;">BATAL</button>
                    
                    <button type="button" wire:click="keluarAplikasi" wire:loading.attr="disabled" class="brutal-btn" style="flex: 1; padding: 16px; background-color: #ef4444; color: #fff; font-weight: 900; font-size: 14px; text-transform: uppercase; cursor: pointer; letter-spacing: 1px;">
                        <span wire:loading.remove wire:target="keluarAplikasi">YA, KELUAR</span>
                        <span wire:loading wire:target="keluarAplikasi">PROSES...</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</x-filament-panels::page.simple>