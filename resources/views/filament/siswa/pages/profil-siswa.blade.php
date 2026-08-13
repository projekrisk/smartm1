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
                --ui-accent: #0F172A;
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
                padding-bottom: calc(100px + env(safe-area-inset-bottom, 0px)); 
                scrollbar-width: none; 
            }
            .workspace-content::-webkit-scrollbar { display: none; }

            .touch-scale { transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
            .touch-scale:active { transform: scale(0.96); }

            .data-row { 
                display: flex; justify-content: space-between; align-items: flex-start; 
                padding: 14px 0; border-bottom: 1px solid var(--ui-border); 
            }
            .data-row:last-child { border-bottom: none; padding-bottom: 0; }
            .data-label { font-size: 13px; font-weight: 500; color: var(--ui-muted); width: 40%; flex-shrink: 0; }
            .data-val { font-size: 13px; font-weight: 700; color: var(--ui-black); text-align: right; width: 60%; line-height: 1.4; word-break: break-word; }

            .ambient-shadow { box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04); }
            
            [x-cloak] { display: none !important; }
        </style>
    </div>

    <div class="min-h-screen relative selection:bg-zinc-900 selection:text-white" 
         x-data="{ showLogoutSheet: false, showFotoModal: false }">
         
        <div class="workspace-container">

            <div class="workspace-content">
                
                @php
                    $pengaturan = null;
                    try {
                        if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) {
                            $pengaturan = \App\Models\Pengaturan::first();
                        }
                    } catch (\Exception $e) {}

                    $rawName = $siswa->nama_lengkap ?? Auth::user()->name ?? 'Siswa';
                    $properName = ucwords(strtolower($rawName));
                @endphp

                <div style="padding: 32px 20px 24px 20px; display: flex; flex-direction: column; align-items: center; text-align: center;">
                    
                    <div style="width: 90px; height: 90px; border-radius: 50%; background-color: var(--ui-surface); border: 4px solid var(--ui-surface); overflow: hidden; margin-bottom: 16px; box-shadow: 0 8px 30px rgba(0,0,0,0.08); position: relative;">
                        @if(isset($siswa->foto) && $siswa->foto && !str_ends_with($siswa->foto, '/'))
                            <img src="{{ url('/uploads/' . $siswa->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #18181B, #3F3F46); color: white; font-size: 32px; font-weight: 800;">
                                {{ substr($properName, 0, 1) }}
                            </div>
                        @endif
                        
                        <div @click="showFotoModal = true" class="touch-scale" style="position: absolute; bottom: 0; right: 0; width: 28px; height: 28px; background: var(--ui-black); border-radius: 50%; border: 2px solid var(--ui-surface); display: flex; align-items: center; justify-content: center; cursor: pointer;">
                            <x-filament::icon icon="heroicon-s-camera" style="width: 14px; height: 14px; color: white;" />
                        </div>
                    </div>

                    <h1 style="font-size: 22px; font-weight: 900; color: var(--ui-black); margin: 0 0 6px 0; letter-spacing: -0.5px;">{{ $properName }}</h1>
                    
                    <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                        <span style="background: white; border: 1px solid var(--ui-border); padding: 4px 12px; border-radius: 100px; font-size: 11px; font-weight: 700; color: var(--ui-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                            NISN: <span style="color: var(--ui-black);">{{ $siswa->nisn ?? '-' }}</span>
                        </span>
                        <span style="background: white; border: 1px solid var(--ui-border); padding: 4px 12px; border-radius: 100px; font-size: 11px; font-weight: 700; color: var(--ui-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                            KELAS: <span style="color: var(--ui-black);">{{ $siswa->kelas->nama_kelas ?? '-' }}</span>
                        </span>
                    </div>
                </div>

                <div style="padding: 0 20px 24px 20px; display: flex; flex-direction: column; gap: 16px;">
                    
                    <div class="ambient-shadow" style="background: var(--ui-surface); border-radius: 24px; padding: 6px; border: 1px solid rgba(0,0,0,0.02);">
                        <button @click="showFotoModal = true" class="touch-scale" style="width: 100%; text-decoration: none; display: flex; align-items: center; justify-content: space-between; padding: 14px; background: transparent; border: none; cursor: pointer;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <div style="width: 36px; height: 36px; border-radius: 12px; background: #F4F4F5; display: flex; align-items: center; justify-content: center;">
                                    <x-filament::icon icon="heroicon-s-photo" style="width: 20px; height: 20px; color: var(--ui-black);" />
                                </div>
                                <div style="text-align: left;">
                                    <h4 style="font-weight: 800; font-size: 14px; color: var(--ui-black); margin: 0 0 2px 0;">Ganti Foto Profil</h4>
                                    <p style="font-size: 11px; font-weight: 600; color: var(--ui-muted); margin: 0;">Perbarui foto avatar Anda</p>
                                </div>
                            </div>
                            <x-filament::icon icon="heroicon-m-chevron-right" style="width: 18px; height: 18px; color: var(--ui-muted);" />
                        </button>
                        
                        <div style="height: 1px; background: var(--ui-border); margin: 0 14px;"></div>

                        <a href="/siswa/ubah-password" class="touch-scale" style="width: 100%; text-decoration: none; display: flex; align-items: center; justify-content: space-between; padding: 14px; background: transparent; border: none; cursor: pointer;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <div style="width: 36px; height: 36px; border-radius: 12px; background: #F4F4F5; display: flex; align-items: center; justify-content: center;">
                                    <x-filament::icon icon="heroicon-s-shield-check" style="width: 20px; height: 20px; color: var(--ui-black);" />
                                </div>
                                <div style="text-align: left;">
                                    <h4 style="font-weight: 800; font-size: 14px; color: var(--ui-black); margin: 0 0 2px 0;">Keamanan Akun</h4>
                                    <p style="font-size: 11px; font-weight: 600; color: var(--ui-muted); margin: 0;">Ubah kata sandi rahasia</p>
                                </div>
                            </div>
                            <x-filament::icon icon="heroicon-m-chevron-right" style="width: 18px; height: 18px; color: var(--ui-muted);" />
                        </a>
                    </div>

                    <div class="ambient-shadow" style="background: var(--ui-surface); border-radius: 24px; padding: 20px; border: 1px solid rgba(0,0,0,0.02);">
                        <h3 style="font-size: 14px; font-weight: 800; color: var(--ui-black); margin: 0 0 12px 0; padding-bottom: 12px; border-bottom: 1px solid var(--ui-border); display: flex; align-items: center; gap: 8px;">
                            <x-filament::icon icon="heroicon-s-identification" style="width: 18px; height: 18px; color: var(--ui-muted);" />
                            Identitas Diri
                        </h3>
                        
                        <div style="display: flex; flex-direction: column;">
                            <div class="data-row"><span class="data-label">NIS Lokal</span><span class="data-val">{{ $siswa->nis ?? '-' }}</span></div>
                            <div class="data-row"><span class="data-label">NIK (KTP)</span><span class="data-val">{{ $siswa->nik ?? '-' }}</span></div>
                            <div class="data-row"><span class="data-label">No. KK</span><span class="data-val">{{ $siswa->no_kk ?? '-' }}</span></div>
                            <div class="data-row"><span class="data-label">Jenis Kelamin</span><span class="data-val">{{ $siswa->jenis_kelamin ?? '-' }}</span></div>
                            <div class="data-row"><span class="data-label">Tempat Lahir</span><span class="data-val">{{ $siswa->tempat_lahir ?? '-' }}</span></div>
                            <div class="data-row"><span class="data-label">Tanggal Lahir</span><span class="data-val">{{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->isoFormat('D MMMM Y') : '-' }}</span></div>
                            <div class="data-row"><span class="data-label">Agama</span><span class="data-val">{{ $siswa->agama ?? '-' }}</span></div>
                        </div>
                    </div>

                    <div class="ambient-shadow" style="background: var(--ui-surface); border-radius: 24px; padding: 20px; border: 1px solid rgba(0,0,0,0.02);">
                        <h3 style="font-size: 14px; font-weight: 800; color: var(--ui-black); margin: 0 0 12px 0; padding-bottom: 12px; border-bottom: 1px solid var(--ui-border); display: flex; align-items: center; gap: 8px;">
                            <x-filament::icon icon="heroicon-s-map-pin" style="width: 18px; height: 18px; color: var(--ui-muted);" />
                            Kontak & Alamat
                        </h3>
                        
                        <div style="display: flex; flex-direction: column;">
                            <div class="data-row"><span class="data-label">No. Telepon / HP</span><span class="data-val">{{ $siswa->telepon ?? '-' }}</span></div>
                            <div class="data-row"><span class="data-label">Email Aktif</span><span class="data-val">{{ $siswa->email ?? '-' }}</span></div>
                            <div class="data-row" style="align-items: flex-start;">
                                <span class="data-label">Alamat Lengkap</span>
                                <span class="data-val" style="font-weight: 600; font-size: 12px;">
                                    {{ $siswa->alamat ?? '-' }}<br>
                                    RT {{ $siswa->rt ?? '-' }} / RW {{ $siswa->rw ?? '-' }}, Kel. {{ $siswa->kelurahan ?? '-' }}<br>
                                    Kec. {{ $siswa->kecamatan ?? '-' }}, {{ $siswa->kabupaten ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="ambient-shadow" style="background: var(--ui-surface); border-radius: 24px; padding: 20px; border: 1px solid rgba(0,0,0,0.02);">
                        <h3 style="font-size: 14px; font-weight: 800; color: var(--ui-black); margin: 0 0 12px 0; padding-bottom: 12px; border-bottom: 1px solid var(--ui-border); display: flex; align-items: center; gap: 8px;">
                            <x-filament::icon icon="heroicon-s-users" style="width: 18px; height: 18px; color: var(--ui-muted);" />
                            Orang Tua / Wali
                        </h3>
                        
                        <div style="display: flex; flex-direction: column;">
                            <div class="data-row"><span class="data-label">Nama Ayah</span><span class="data-val">{{ $siswa->nama_ayah ?? '-' }}</span></div>
                            <div class="data-row"><span class="data-label">No. HP Ayah</span><span class="data-val">{{ $siswa->telepon_ayah ?? '-' }}</span></div>
                            <div class="data-row" style="border-top: 1px solid var(--ui-border); padding-top: 14px; margin-top: 4px;"><span class="data-label">Nama Ibu</span><span class="data-val">{{ $siswa->nama_ibu ?? '-' }}</span></div>
                            <div class="data-row"><span class="data-label">No. HP Ibu</span><span class="data-val">{{ $siswa->telepon_ibu ?? '-' }}</span></div>
                            <div class="data-row" style="border-top: 1px solid var(--ui-border); padding-top: 14px; margin-top: 4px;"><span class="data-label">Nama Wali</span><span class="data-val">{{ $siswa->nama_wali ?? '-' }}</span></div>
                            <div class="data-row"><span class="data-label">No. HP Wali</span><span class="data-val">{{ $siswa->telepon_wali ?? '-' }}</span></div>
                        </div>
                    </div>

                    <a href="/siswa/pesan" class="touch-scale ambient-shadow" style="text-decoration: none; display: flex; flex-direction: row; align-items: center; gap: 16px; background-color: #FFFBEB; border: 1px solid #FEF3C7; padding: 20px; border-radius: 24px; margin-top: 8px;">
                        <div style="background-color: #F59E0B; color: white; width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 10px rgba(245, 158, 11, 0.2);">
                            <x-filament::icon icon="heroicon-s-lifebuoy" style="width: 24px; height: 24px;" />
                        </div>
                        <div>
                            <h4 style="color: #D97706; font-size: 14px; font-weight: 800; margin: 0 0 4px 0;">Butuh Perbaikan Data?</h4>
                            <p style="color: #B45309; font-size: 12px; font-weight: 600; line-height: 1.4; margin: 0;">Hubungi Tata Usaha jika data tidak sesuai dengan dokumen resmi.</p>
                        </div>
                    </a>

                </div>
            </div>

            <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(255,255,255,0.85); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-top: 1px solid rgba(0,0,0,0.05); display: flex; justify-content: space-around; padding: 12px 8px calc(12px + env(safe-area-inset-bottom, 0px)) 8px; z-index: 50;">
                
                <a href="/siswa" style="display: flex; flex-direction: column; align-items: center; gap: 4px; text-decoration: none; color: var(--ui-muted); flex: 1; transition: color 0.2s;">
                    <x-filament::icon icon="heroicon-o-home" style="width: 24px; height: 24px;" />
                    <span style="font-size: 10px; font-weight: 600;">Beranda</span>
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
                
                <a href="/siswa/profil" style="display: flex; flex-direction: column; align-items: center; gap: 4px; text-decoration: none; color: var(--ui-black); flex: 1;">
                    <x-filament::icon icon="heroicon-s-user" style="width: 24px; height: 24px;" />
                    <span style="font-size: 10px; font-weight: 800;">Profil</span>
                </a>
                
                <button @click="showLogoutSheet = true" style="display: flex; flex-direction: column; align-items: center; gap: 4px; border: none; background: transparent; color: var(--ui-muted); flex: 1; cursor: pointer; transition: color 0.2s;">
                    <x-filament::icon icon="heroicon-o-arrow-right-on-rectangle" style="width: 24px; height: 24px;" />
                    <span style="font-size: 10px; font-weight: 600;">Keluar</span>
                </button>
                
            </div>

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

            <div x-show="showFotoModal" x-cloak 
                 style="position: fixed; inset: 0; z-index: 101; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(4px);"
                 x-transition.opacity @click="showFotoModal = false"></div>
                 
            <div x-show="showFotoModal" x-cloak
                 style="position: absolute; bottom: 0; left: 0; right: 0; background-color: var(--ui-surface); border-radius: 28px 28px 0 0; padding: 24px; padding-bottom: calc(24px + env(safe-area-inset-bottom, 0px)); z-index: 102; box-shadow: 0 -20px 40px rgba(0,0,0,0.15);"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="transform translate-y-full" x-transition:enter-end="transform translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="transform translate-y-0" x-transition:leave-end="transform translate-y-full">
                
                <div style="width: 40px; height: 5px; border-radius: 100px; background-color: var(--ui-border); margin: 0 auto 24px auto;"></div>

                <h3 style="font-size: 16px; font-weight: 800; color: var(--ui-black); margin: 0 0 20px 0; text-align: center;">Perbarui Foto Profil</h3>
                
                <form wire:submit="simpanFoto" style="display: flex; flex-direction: column; gap: 20px;">
                    <div style="width: 100%; display: flex; justify-content: center; [&_.fi-fo-field-wrp]:mx-auto [&_.fi-fo-file-upload]:mx-auto">
                        {{ $this->fotoForm }}
                    </div>
                    
                    <button type="submit" wire:loading.attr="disabled" @click="setTimeout(() => showFotoModal = false, 800)" style="width: 100%; background: var(--ui-black); color: white; border-radius: 100px; padding: 14px; font-weight: 800; font-size: 13px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <span wire:loading.remove wire:target="simpanFoto">Simpan Foto Baru</span>
                        <span wire:loading wire:target="simpanFoto">Mengunggah...</span>
                    </button>
                    
                    <button type="button" @click="showFotoModal = false" style="width: 100%; background: transparent; color: var(--ui-muted); font-weight: 700; font-size: 13px; border: none; cursor: pointer; padding: 4px;">
                        Batal & Tutup
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-filament-panels::page.simple>