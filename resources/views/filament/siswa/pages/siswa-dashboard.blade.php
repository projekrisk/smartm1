<x-filament-panels::page.simple>
    <div wire:ignore>
        <style>
            .fi-topbar, .fi-sidebar, .fi-header, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            
            html, body, .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page, section { 
                padding: 0 !important; margin: 0 !important; gap: 0 !important;
                height: 100vh !important; height: 100dvh !important; 
                max-width: 100% !important; width: 100% !important; 
                overflow: hidden !important; 
                background-color: #e2e8f0 !important; box-shadow: none !important; border: none !important;
            }
            .dark body, .dark .fi-layout, .dark .fi-simple-layout, .dark .fi-simple-main { background-color: #020617 !important; }

            .android-app-container {
                width: 100%; max-width: 414px; margin: 0 auto; 
                position: fixed; top: 0; bottom: 0; left: 0; right: 0;
                height: 100% !important; 
                max-width: 100% !important; width: 100% !important; overflow: hidden !important;
                display: flex; flex-direction: column;
                box-shadow: 0 0 40px rgba(0,0,0,0.15); overflow: hidden; 
                font-family: 'Inter', system-ui, sans-serif; transition: background-color 0.3s ease;
            }

            a{
                color: #2563eb;
                text-decoration: underline;
            }

            .theme-bg { background-color: #f8fafc; }
            .theme-card { background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 8px 30px rgba(0,0,0,0.04); }
            .theme-text { color: #0f172a; }
            .theme-text-muted { color: #64748b; }
            .theme-nav { background-color: rgba(255, 255, 255, 0.95); border-top: 1px solid #f1f5f9; }
            .theme-nav-item { color: #94a3b8; }
            .theme-nav-item:hover { color: #0f172a; }
            
            .dark .theme-bg { background-color: #0f172a; }
            .dark .theme-card { background-color: #1e293b; border: 1px solid #334155; box-shadow: 0 8px 30px rgba(0,0,0,0.2); }
            .dark .theme-text { color: #f8fafc; }
            .dark .theme-text-muted { color: #94a3b8; }
            .dark .theme-nav { background-color: rgba(15, 23, 42, 0.95); border-top: 1px solid #334155; }
            .dark .theme-nav-item { color: #64748b; }
            .dark .theme-nav-item:hover { color: #f8fafc; }

            /* scroll */
            .android-content { flex: 1; overflow-y: auto; overflow-x: hidden; padding-bottom: calc(100px + env(safe-area-inset-bottom, 0px)); scrollbar-width: none; -ms-overflow-style: none; -webkit-overflow-scrolling: touch; }
            .android-content::-webkit-scrollbar { display: none; }

            .icon-sun { display: none; }
            .icon-moon { display: block; }
            .dark .icon-sun { display: block; }
            .dark .icon-moon { display: none; }
        </style>
    </div>

    <div class="android-app-container theme-bg">
        <div class="android-content">            
            <div style="background: linear-gradient(135deg, #2563eb, #3730a3); padding: 24px 24px 48px 24px; border-bottom-left-radius: 2rem; border-bottom-right-radius: 2rem; color: white; position: relative; box-shadow: 0 10px 30px rgba(37,99,235,0.3);">
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                    <p style="font-size: 11px; font-weight: 800; letter-spacing: 1px; margin: 0; color: #bfdbfe; text-transform: uppercase;">
                        SmartM1- SMAN 1 Malingping
                    </p>
                    
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
                        type="button" 
                        style="background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;">
                        <x-filament::icon icon="heroicon-m-moon" class="icon-moon" style="width: 18px; height: 18px; color: white;" />
                        <x-filament::icon icon="heroicon-m-sun" class="icon-sun" style="width: 18px; height: 18px; color: #fbbf24;" />
                    </button>
                </div>

                <div style="width: 72px; height: 72px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.4); background-color: #f1f5f9; overflow: hidden; margin-bottom: 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                    @if(isset($siswa->foto) && $siswa->foto)
                        <img src="{{ url('/uploads/' . $siswa->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="color: #2563eb; font-weight: 900; font-size: 1.75rem;">{{ substr($siswa->nama_lengkap ?? 'S', 0, 1) }}</span>
                    @endif
                </div>

                <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0 0 8px 0; line-height: 1.2; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    {{ $siswa->nama_lengkap ?? Auth::user()->name }}
                </h1>
                <div style="display: inline-flex; align-items: center; gap: 6px; background-color: rgba(0,0,0,0.25); padding: 4px 14px; border-radius: 999px; font-size: 11px; font-weight: bold; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px);">
                    <div style="width: 6px; height: 6px; border-radius: 50%; background-color: #4ade80; box-shadow: 0 0 8px #4ade80;"></div>
                    {{ $siswa->kelas->nama_kelas ?? 'Belum ada kelas' }}
                </div>
            </div>

            <div style="padding: 0 20px; margin-top: -30px; position: relative; z-index: 10;">
                <div class="theme-card" style="border-radius: 24px; padding: 20px;">
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px 8px;">
                        
                        <a href="/siswa/jadwal" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; cursor: pointer; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'">
                            <div style="width: 46px; height: 46px; border-radius: 14px; background-color: rgba(37, 99, 235, 0.1); color: #2563eb; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                                <x-filament::icon icon="heroicon-s-calendar-days" style="width: 22px; height: 22px;" />
                            </div>
                            <span class="theme-text" style="font-size: 11px; font-weight: bold;">Jadwal</span>
                        </a>
                        
                        <a href="/siswa/rekap-absensi" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; cursor: pointer; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'">
                            <div style="width: 46px; height: 46px; border-radius: 14px; background-color: rgba(16, 185, 129, 0.1); color: #10b981; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                                <x-filament::icon icon="heroicon-s-document-check" style="width: 22px; height: 22px;" />
                            </div>
                            <span class="theme-text" style="font-size: 11px; font-weight: bold;">Absensi</span>
                        </a>
                        
                        <a href="/siswa/nilai" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; cursor: pointer; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'">
                            <div style="width: 46px; height: 46px; border-radius: 14px; background-color: rgba(245, 158, 11, 0.1); color: #f59e0b; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                                <x-filament::icon icon="heroicon-s-academic-cap" style="width: 22px; height: 22px;" />
                            </div>
                            <span class="theme-text" style="font-size: 11px; font-weight: bold;">Nilai</span>
                        </a>
                        
                        <a href="/siswa/e-rapor" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; cursor: pointer; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'">
                            <div style="width: 46px; height: 46px; border-radius: 14px; background-color: rgba(147, 51, 234, 0.1); color: #9333ea; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                                <x-filament::icon icon="heroicon-s-folder-open" style="width: 22px; height: 22px;" />
                            </div>
                            <span class="theme-text" style="font-size: 11px; font-weight: bold;">E-Rapor</span>
                        </a>

                        <a href="/siswa/prestasi" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; cursor: pointer; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'">
                            <div style="width: 46px; height: 46px; border-radius: 14px; background-color: rgba(234, 179, 8, 0.1); color: #eab308; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                                <x-filament::icon icon="heroicon-s-trophy" style="width: 22px; height: 22px;" />
                            </div>
                            <span class="theme-text" style="font-size: 11px; font-weight: bold;">Prestasi</span>
                        </a>

                        <a href="/siswa/dokumen" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; cursor: pointer; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'">
                            <div style="width: 46px; height: 46px; border-radius: 14px; background-color: rgba(6, 182, 212, 0.1); color: #06b6d4; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                                <x-filament::icon icon="heroicon-s-folder-arrow-down" style="width: 22px; height: 22px;" />
                            </div>
                            <span class="theme-text" style="font-size: 11px; font-weight: bold;">Dokumen</span>
                        </a>

                        <a href="/siswa/tentang" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; cursor: pointer; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'">
                            <div style="width: 46px; height: 46px; border-radius: 14px; background-color: rgba(236, 72, 153, 0.1); color: #ec4899; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                                <x-filament::icon icon="heroicon-s-star" style="width: 22px; height: 22px;" />
                            </div>
                            <span class="theme-text" style="font-size: 11px; font-weight: bold;">Tentang</span>
                        </a>
                    </div>
                </div>
            </div>

            @if(isset($siswa) && $siswa->is_sekretaris)
                <div style="padding: 0 20px; margin-top: 16px;">
                    <a href="/siswa/absensi" style="text-decoration: none; display: flex; align-items: center; justify-content: space-between; background: linear-gradient(135deg, #10b981, #047857); padding: 16px 20px; border-radius: 20px; color: white; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3); transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.98)'" onmouseup="this.style.transform='scale(1)'">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="background-color: rgba(255,255,255,0.2); padding: 10px; border-radius: 12px; backdrop-filter: blur(4px);">
                                <x-filament::icon icon="heroicon-s-users" style="width: 24px; height: 24px; color: white;" />
                            </div>
                            <div>
                                <h4 style="font-weight: 900; font-size: 14px; margin: 0 0 2px 0;">Input Absensi Kelas</h4>
                                <p style="font-size: 10px; font-weight: 600; opacity: 0.9; margin: 0;">Khusus Sekretaris</p>
                            </div>
                        </div>
                        <div style="background-color: rgba(255,255,255,0.2); border-radius: 50%; padding: 4px; display: flex; align-items: center; justify-content: center;">
                            <x-filament::icon icon="heroicon-m-chevron-right" style="width: 18px; height: 18px; color: white;" />
                        </div>
                    </a>
                </div>
            @endif

            <div style="padding: 24px 20px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 16px;">
                    <h3 class="theme-text" style="font-size: 16px; font-weight: 900; margin: 0;">Papan Informasi</h3>
                    <span style="font-size: 11px; color: #2563eb; font-weight: bold; text-transform: uppercase;">Lihat Semua</span>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @forelse($pengumuman as $info)
                        <div class="theme-card" style="border-radius: 20px; padding: 16px; display: flex; gap: 16px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background-color: rgba(37,99,235,0.1); color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                                <x-filament::icon icon="heroicon-s-bell-alert" style="width: 20px; height: 20px;" />
                            </div>
                            <div>
                                <h4 class="theme-text" style="font-weight: bold; font-size: 14px; margin: 0 0 4px 0;">{{ $info->judul }}</h4>
                                <div class="theme-text-muted content-pengumuman" style="font-size: 13px; line-height: 1.5;">
                                    {!! strip_tags($info->isi, '<a><strong><b><i><em><br>') !!}
                                </div>
                                <p style="font-size: 10px; color: #9694b8; font-weight: bold; margin-top: 8px; text-transform: uppercase;">{{ $info->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="theme-card" style="border-radius: 20px; padding: 32px 16px; text-align: center;">
                            <x-filament::icon icon="heroicon-o-inbox" style="width: 40px; height: 40px; margin: 0 auto 12px auto; color: #cbd5e1;" />
                            <p class="theme-text-muted" style="font-size: 13px; font-weight: bold; margin: 0;">Belum ada pengumuman.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
        </div>

        <div class="theme-nav" style="position: absolute; bottom: 0; width: 100%; height: calc(80px + env(safe-area-inset-bottom, 0px)); border-top-left-radius: 24px; border-top-right-radius: 24px; box-shadow: 0 -4px 20px rgba(0,0,0,0.05); z-index: 50; display: flex; justify-content: space-around; align-items: center; padding: 0 8px calc(8px + env(safe-area-inset-bottom, 0px)) 8px; backdrop-filter: blur(10px);">
            <a href="/siswa" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; width: 100%; color: #2563eb;">
                <x-filament::icon icon="heroicon-s-home" style="width: 24px; height: 24px; margin-bottom: 4px;" />
                <span style="font-size: 10px; font-weight: bold;">Beranda</span>
            </a>
            
            <a href="/siswa/riwayat" class="theme-nav-item" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; width: 100%; transition: color 0.2s;">
                <x-filament::icon icon="heroicon-s-clipboard-document-list" style="width: 24px; height: 24px; margin-bottom: 4px;" />
                <span style="font-size: 10px; font-weight: bold;">Riwayat</span>
            </a>
            
            <div style="position: relative; width: 100%; display: flex; justify-content: center;">
                <a href="/siswa/kartu-pelajar" style="position: absolute; top: -36px; width: 56px; height: 56px; background-color: #2563eb; color: white; border-radius: 50%; box-shadow: 0 8px 20px rgba(37,99,235,0.4); display: flex; align-items: center; justify-content: center; border: 4px solid var(--tw-ring-color, white); transition: transform 0.1s;" class="dark:border-slate-900" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'">
                    <x-filament::icon icon="heroicon-s-qr-code" style="width: 24px; height: 24px;" />
                </a>
            </div>
            
            @php
                $unreadPesan = 0;
                if(isset($siswa)) {
                    $unreadPesan = \App\Models\PesanBantuan::where('siswa_id', $siswa->id)->where('is_read_siswa', false)->count();
                }
            @endphp
            <a href="/siswa/pesan" class="theme-nav-item" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; width: 100%; transition: color 0.2s;">
                <div style="position: relative;">
                    <x-filament::icon icon="heroicon-s-chat-bubble-left-ellipsis" style="width: 24px; height: 24px; margin-bottom: 4px;" />
                    @if($unreadPesan > 0)
                        <span style="position: absolute; top: 0; right: -2px; width: 8px; height: 8px; background-color: #ef4444; border: 2px solid white; border-radius: 50%;"></span>
                    @endif
                </div>
                <span style="font-size: 10px; font-weight: bold;">Pesan</span>
            </a>
            
            <a href="/siswa/profil" class="theme-nav-item" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; width: 100%; transition: color 0.2s;">
                <x-filament::icon icon="heroicon-s-user" style="width: 24px; height: 24px; margin-bottom: 4px;" />
                <span style="font-size: 10px; font-weight: bold;">Profil</span>
            </a>
        </div>

    </div>
</x-filament-panels::page.simple>