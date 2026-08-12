<x-filament-panels::page.simple>
    <div wire:ignore>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Inter', sans-serif !important; overflow: hidden !important; background-color: #F9FAFB !important; }
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

            .dark .bg-structural {
                background-image: 
                    linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            }

            /* Container Responsif bergaya Neo-Brutalisme */
            .android-app-container {
                width: 100%; height: 100% !important;
                display: flex; flex-direction: column;
                background-color: #F9FAFB;
                position: fixed; inset: 0;
                overflow: hidden;
            }
            .dark .android-app-container { background-color: #111827; }

            @media (min-width: 640px) {
                .android-app-container {
                    max-width: 460px;
                    left: 50%; right: auto;
                    transform: translateX(-50%);
                    border-left: 4px solid #111827;
                    border-right: 4px solid #111827;
                    box-shadow: 16px 0px 0px 0px rgba(17, 24, 39, 0.1);
                }
                .dark .android-app-container {
                    border-color: #374151;
                    box-shadow: 16px 0px 0px 0px rgba(0, 0, 0, 0.4);
                }
            }

            .android-content { flex: 1; overflow-y: auto; overflow-x: hidden; padding-bottom: calc(90px + env(safe-area-inset-bottom, 0px)); scrollbar-width: none; }
            .android-content::-webkit-scrollbar { display: none; }

            /* Utility Classes Brutalisme */
            .brutal-card { 
                background-color: #ffffff; 
                border: 2px solid #111827; 
                box-shadow: 4px 4px 0px 0px #111827; 
            }
            .dark .brutal-card { 
                background-color: #1f2937; 
                border-color: #9ca3af; 
                box-shadow: 4px 4px 0px 0px #9ca3af; 
            }

            .brutal-btn { 
                border: 2px solid #111827; 
                box-shadow: 3px 3px 0px 0px #111827; 
                transition: all 0.1s ease-out; 
            }
            .dark .brutal-btn { border-color: #f9fafb; box-shadow: 3px 3px 0px 0px #f9fafb; }
            
            .brutal-btn:active { 
                transform: translate(3px, 3px); 
                box-shadow: 0px 0px 0px 0px transparent; 
            }

            .menu-item { display: flex; flex-direction: column; align-items: center; text-decoration: none; color: #111827; font-weight: 900; font-size: 11px; gap: 8px;}
            .dark .menu-item { color: #f9fafb; }
            
            .menu-icon-wrap { width: 52px; height: 52px; display: flex; justify-content: center; align-items: center; border: 2px solid #111827; box-shadow: 3px 3px 0px 0px #111827; transition: all 0.1s;}
            .dark .menu-icon-wrap { border-color: #111827; box-shadow: 3px 3px 0px 0px #111827; }
            .menu-item:active .menu-icon-wrap { transform: translate(3px, 3px); box-shadow: 0px 0px 0px 0px transparent; }

            .nav-item { display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; color: #4b5563; transition: all 0.1s; text-decoration: none; }
            .nav-item:active { transform: translateY(2px); color: #111827; }
            .dark .nav-item { color: #9ca3af; }
            .dark .nav-item:active { color: #f9fafb; }

            .icon-sun { display: none; }
            .icon-moon { display: block; }
            .dark .icon-sun { display: block; }
            .dark .icon-moon { display: none; }
            
            a { text-decoration: underline; font-weight: bold; }
        </style>
    </div>

    <div class="bg-base-50 bg-structural dark:bg-gray-900 min-h-screen relative selection:bg-base-900 selection:text-white" x-data="{ showLogoutSheet: false }">
        <div class="android-app-container z-10">
            
            <div class="android-content">            
                <!-- HEADER AREA -->
                <div style="background-color: #facc15; border-bottom: 4px solid #111827; padding: 24px 20px; position: relative;">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                        <div style="background-color: #111827; color: #fff; padding: 4px 10px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.05em; border: 1px solid #111827;">
                            SMART-M1 SMAN 1
                        </div>
                        
                        <button x-data="{
                                theme: localStorage.getItem('theme') || 'light',
                                toggle() {
                                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                                    localStorage.setItem('theme', this.theme);
                                    if (this.theme === 'dark') {
                                        document.documentElement.classList.add('dark');
                                    } else {
                                        document.documentElement.classList.remove('dark');
                                    }
                                    window.dispatchEvent(new CustomEvent('theme-changed', { detail: this.theme }));
                                }
                            }" 
                            @click="toggle()" 
                            class="brutal-btn"
                            type="button" 
                            style="background-color: #fff; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0;">
                            <x-filament::icon icon="heroicon-m-moon" class="icon-moon" style="width: 18px; height: 18px; color: #111827;" />
                            <x-filament::icon icon="heroicon-m-sun" class="icon-sun" style="width: 18px; height: 18px; color: #111827;" />
                        </button>
                    </div>

                    @php
                        $hour = \Carbon\Carbon::now('Asia/Jakarta')->format('H');
                        if ($hour >= 5 && $hour < 11) $greeting = 'Selamat pagi';
                        elseif ($hour >= 11 && $hour < 15) $greeting = 'Selamat siang';
                        elseif ($hour >= 15 && $hour < 18) $greeting = 'Selamat sore';
                        else $greeting = 'Selamat malam';

                        $rawName = $siswa->nama_lengkap ?? Auth::user()->name ?? 'Siswa';
                        $properName = ucwords(strtolower($rawName));
                    @endphp

                    <div style="display: flex; justify-content: space-between; align-items: flex-end; gap: 16px;">
                        <div style="flex: 1; min-width: 0;">
                            <p style="font-size: 12px; font-weight: 900; color: #111827; margin-bottom: 2px; text-transform: uppercase; letter-spacing: 0.05em;">{{ $greeting }}</p>
                            <h1 style="font-size: 20px; font-weight: 900; color: #111827; margin: 0 0 10px 0; line-height: 1.1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-transform: uppercase;">
                                {{ $properName }}
                            </h1>
                            <div style="display: inline-block; background-color: #fff; border: 2px solid #111827; padding: 4px 12px; font-size: 11px; font-weight: 900; box-shadow: 2px 2px 0px 0px #111827;">
                                KELAS {{ $siswa->kelas->nama_kelas ?? 'BELUM ADA' }}
                            </div>
                        </div>

                        <div style="width: 72px; height: 72px; background-color: #fff; border: 3px solid #111827; box-shadow: 4px 4px 0px 0px #111827; display: flex; justify-content: center; align-items: center; overflow: hidden; flex-shrink: 0;">
                            @if(isset($siswa->foto) && $siswa->foto && !str_ends_with($siswa->foto, '/'))
                                <img src="{{ url('/uploads/' . $siswa->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span style="color: #111827; font-weight: 900; font-size: 2rem;">{{ substr($properName, 0, 1) }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div style="padding: 24px 20px;">
                    <div class="brutal-card" style="padding: 24px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px 12px;">
                        
                        <a href="/siswa/jadwal" class="menu-item">
                            <div class="menu-icon-wrap" style="background-color: #bae6fd;">
                                <x-filament::icon icon="heroicon-s-calendar-days" style="width: 24px; height: 24px; color: #111827;" />
                            </div>
                            <span>JADWAL</span>
                        </a>
                        
                        <a href="/siswa/rekap-absensi" class="menu-item">
                            <div class="menu-icon-wrap" style="background-color: #86efac;">
                                <x-filament::icon icon="heroicon-s-document-check" style="width: 24px; height: 24px; color: #111827;" />
                            </div>
                            <span>ABSENSI</span>
                        </a>
                        
                        <a href="/siswa/nilai" class="menu-item">
                            <div class="menu-icon-wrap" style="background-color: #fde047;">
                                <x-filament::icon icon="heroicon-s-academic-cap" style="width: 24px; height: 24px; color: #111827;" />
                            </div>
                            <span>NILAI</span>
                        </a>
                        
                        <a href="/siswa/e-rapor" class="menu-item">
                            <div class="menu-icon-wrap" style="background-color: #d8b4fe;">
                                <x-filament::icon icon="heroicon-s-folder-open" style="width: 24px; height: 24px; color: #111827;" />
                            </div>
                            <span>E-RAPOR</span>
                        </a>

                        <a href="/siswa/prestasi" class="menu-item">
                            <div class="menu-icon-wrap" style="background-color: #f9a8d4;">
                                <x-filament::icon icon="heroicon-s-trophy" style="width: 24px; height: 24px; color: #111827;" />
                            </div>
                            <span>PRESTASI</span>
                        </a>

                        <a href="/siswa/catatan" class="menu-item">
                            <div class="menu-icon-wrap" style="background-color: #a5b4fc;">
                                <x-filament::icon icon="heroicon-s-clipboard-document-check" style="width: 24px; height: 24px; color: #111827;" />
                            </div>
                            <span>CATATAN</span>
                        </a>

                        <a href="/siswa/dokumen" class="menu-item">
                            <div class="menu-icon-wrap" style="background-color: #67e8f9;">
                                <x-filament::icon icon="heroicon-s-folder-arrow-down" style="width: 24px; height: 24px; color: #111827;" />
                            </div>
                            <span>DOKUMEN</span>
                        </a>

                        <a href="/siswa/pegawai" class="menu-item">
                            <div class="menu-icon-wrap" style="background-color: #fdba74;">
                                <x-filament::icon icon="heroicon-s-users" style="width: 24px; height: 24px; color: #111827;" />
                            </div>
                            <span>PEGAWAI</span>
                        </a>

                        <a href="/siswa/tentang" class="menu-item">
                            <div class="menu-icon-wrap" style="background-color: #bef264;">
                                <x-filament::icon icon="heroicon-s-star" style="width: 24px; height: 24px; color: #111827;" />
                            </div>
                            <span>TENTANG</span>
                        </a>

                        <a @click="showLogoutSheet = true" class="menu-item cursor-pointer">
                            <div class="menu-icon-wrap" style="background-color: #fca5a5;">
                                <x-filament::icon icon="heroicon-s-arrow-right-on-rectangle" style="width: 24px; height: 24px; color: #111827;" />
                            </div>
                            <span>KELUAR</span>
                        </a>

                    </div>
                </div>

                @if(isset($siswa) && $siswa->is_sekretaris)
                    <div style="padding: 0 20px; margin-bottom: 24px;">
                        <a href="/siswa/absensi" class="brutal-btn" style="text-decoration: none; display: flex; align-items: center; justify-content: space-between; background-color: #10b981; padding: 16px 20px; color: #111827;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <div style="background-color: #fff; padding: 10px; border: 2px solid #111827;">
                                    <x-filament::icon icon="heroicon-s-users" style="width: 24px; height: 24px; color: #111827;" />
                                </div>
                                <div>
                                    <h4 style="font-weight: 900; font-size: 14px; margin: 0 0 2px 0; text-transform: uppercase;">Input Absensi Kelas</h4>
                                    <p style="font-size: 10px; font-weight: 800; margin: 0; background-color: #111827; color: #fff; display: inline-block; padding: 2px 6px;">KHUSUS SEKRETARIS</p>
                                </div>
                            </div>
                            <div style="background-color: #111827; padding: 4px; display: flex; align-items: center; justify-content: center;">
                                <x-filament::icon icon="heroicon-m-chevron-right" style="width: 18px; height: 18px; color: #fff;" />
                            </div>
                        </a>
                    </div>
                @endif

                <div style="padding: 0 20px 20px 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 16px; border-bottom: 3px solid #111827; padding-bottom: 8px;" class="dark:border-gray-500">
                        <h3 style="font-size: 16px; font-weight: 900; margin: 0; color: #111827; text-transform: uppercase;" class="dark:text-white">Papan Informasi</h3>
                        <span style="font-size: 10px; color: #111827; font-weight: 900; text-transform: uppercase;" class="dark:text-gray-300">Lihat Semua</span>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        @forelse($pengumuman as $info)
                            <div class="brutal-card" style="padding: 16px; display: flex; gap: 16px;">
                                <div style="width: 40px; height: 40px; background-color: #fde047; border: 2px solid #111827; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                                    <x-filament::icon icon="heroicon-s-bell-alert" style="width: 20px; height: 20px; color: #111827;" />
                                </div>
                                <div>
                                    <h4 style="font-weight: 900; font-size: 14px; margin: 0 0 4px 0; color: #111827; text-transform: uppercase;" class="dark:text-white">{{ $info->judul }}</h4>
                                    <div style="font-size: 12px; font-weight: 600; line-height: 1.5; color: #374151; margin-bottom: 8px;" class="dark:text-gray-300">
                                        {!! strip_tags($info->isi, '<a><strong><b><i><em><br>') !!}
                                    </div>
                                    <span style="background-color: #111827; color: #fff; padding: 2px 6px; font-size: 9px; font-weight: 800; text-transform: uppercase;">{{ $info->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="brutal-card" style="padding: 32px 16px; text-align: center;">
                                <x-filament::icon icon="heroicon-o-inbox" style="width: 40px; height: 40px; margin: 0 auto 12px auto; color: #111827;" class="dark:text-gray-300" />
                                <p style="font-size: 13px; font-weight: 900; margin: 0; color: #111827; text-transform: uppercase;" class="dark:text-gray-300">Belum ada pengumuman.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
            </div>

            <div style="background-color: #ffffff; border-top: 4px solid #111827; position: absolute; bottom: 0; width: 100%; height: 75px; display: flex; justify-content: space-around; align-items: center; z-index: 50; padding-bottom: env(safe-area-inset-bottom, 0px);" class="dark:bg-gray-800 dark:border-gray-500">
                <a href="/siswa" class="nav-item" style="color: #111827;">
                    <x-filament::icon icon="heroicon-s-home" style="width: 24px; height: 24px; margin-bottom: 4px;" class="dark:text-white" />
                    <span style="font-size: 10px; font-weight: 900; text-transform: uppercase;" class="dark:text-white">Beranda</span>
                </a>
                
                <a href="/siswa/riwayat" class="nav-item">
                    <x-filament::icon icon="heroicon-s-clipboard-document-list" style="width: 24px; height: 24px; margin-bottom: 4px;" />
                    <span style="font-size: 10px; font-weight: 900; text-transform: uppercase;">Riwayat</span>
                </a>
                
                <div style="position: relative; width: 60px; display: flex; justify-content: center;">
                    <a href="/siswa/kartu-pelajar" class="brutal-btn" style="position: absolute; top: -35px; background-color: #3b82f6; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;">
                        <x-filament::icon icon="heroicon-s-qr-code" style="width: 28px; height: 28px; color: #111827;" />
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
                        <x-filament::icon icon="heroicon-s-chat-bubble-left-ellipsis" style="width: 24px; height: 24px; margin-bottom: 4px;" />
                        @if($unreadPesan > 0)
                            <span style="position: absolute; top: -2px; right: -4px; width: 12px; height: 12px; background-color: #ef4444; border: 2px solid #111827; border-radius: 0;"></span>
                        @endif
                    </div>
                    <span style="font-size: 10px; font-weight: 900; text-transform: uppercase;">Pesan</span>
                </a>
                
                <a href="/siswa/profil" class="nav-item">
                    <x-filament::icon icon="heroicon-s-user" style="width: 24px; height: 24px; margin-bottom: 4px;" />
                    <span style="font-size: 10px; font-weight: 900; text-transform: uppercase;">Profil</span>
                </a>
            </div>

            <div x-show="showLogoutSheet" style="display: none; position: absolute; inset: 0; background-color: rgba(17,24,39,0.8); z-index: 99;" x-transition.opacity @click="showLogoutSheet = false"></div>
            
            <div x-show="showLogoutSheet" style="display: none; position: absolute; bottom: 0; left: 0; right: 0; background-color: #ffffff; border-top: 4px solid #111827; box-shadow: 0 -8px 0px 0px rgba(17,24,39,1); z-index: 100; padding: 32px 24px calc(24px + env(safe-area-inset-bottom, 0px));" class="dark:bg-gray-800 dark:border-gray-500 dark:shadow-[0px_-8px_0px_0px_#9ca3af]"
                 x-transition:enter="transition ease-out duration-200" 
                 x-transition:enter-start="transform translate-y-full" 
                 x-transition:enter-end="transform translate-y-0" 
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="transform translate-y-0" 
                 x-transition:leave-end="transform translate-y-full">
                
                <h3 style="font-size: 20px; font-weight: 900; text-align: center; margin: 0 0 8px 0; color: #111827; text-transform: uppercase;" class="dark:text-white">Akhiri Sesi?</h3>
                <p style="font-size: 13px; font-weight: 600; text-align: center; margin: 0 0 28px 0; color: #4b5563; line-height: 1.5;" class="dark:text-gray-300">Apakah Anda yakin ingin keluar dari portal ini?</p>
                
                <div style="display: flex; gap: 16px;">
                    <button @click="showLogoutSheet = false" class="brutal-btn" style="flex: 1; padding: 14px; background-color: #f3f4f6; color: #111827; font-weight: 900; font-size: 13px; text-transform: uppercase; cursor: pointer;">BATAL</button>
                    
                    <button type="button" wire:click="keluarAplikasi" wire:loading.attr="disabled" class="brutal-btn" style="flex: 1; padding: 14px; background-color: #ef4444; color: #fff; font-weight: 900; font-size: 13px; text-transform: uppercase; cursor: pointer;">
                        <span wire:loading.remove wire:target="keluarAplikasi">YA, KELUAR</span>
                        <span wire:loading wire:target="keluarAplikasi">PROSES...</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</x-filament-panels::page.simple>