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
            
            /* Customizing Filament textarea/inputs for chat */
            .workspace-container .fi-fo-field-wrp label { display: none !important; }
            .workspace-container .fi-input-wrp { border-radius: 20px !important; background-color: var(--ui-bg) !important; border: 1px solid var(--ui-border) !important; box-shadow: none !important; transition: all 0.2s ease; overflow: hidden; }
            .workspace-container .fi-input-wrp:focus-within { border-color: var(--ui-black) !important; background-color: var(--ui-surface) !important; box-shadow: 0 4px 12px rgba(24,24,27,0.08) !important; }
            .workspace-container textarea { padding: 16px 20px !important; font-family: 'DM Sans', sans-serif !important; font-weight: 500 !important; font-size: 14px !important; color: var(--ui-black) !important; background: transparent !important; resize: none !important; }

            [x-cloak] { display: none !important; }
        </style>
    </div>

    <div class="workspace-container selection:bg-zinc-900 selection:text-white" x-data="{ showLogoutSheet: false }">
        
        <!-- Header -->
        <div style="padding: 24px 20px 16px 20px; display: flex; align-items: center; gap: 16px; margin-top: env(safe-area-inset-top, 0px); background: var(--ui-bg); flex-shrink: 0; z-index: 10; border-bottom: 1px solid rgba(0,0,0,0.02);">
            @if($activeSesiId === null && !$isCreatingNew)
                <div style="flex: 1;">
                    <h1 style="font-size: 20px; font-weight: 900; color: var(--ui-black); margin: 0; letter-spacing: -0.5px; line-height: 1.2;">Pusat Bantuan</h1>
                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 4px;">
                        <div style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--ui-black);"></div>
                        <p style="font-size: 12px; font-weight: 600; color: var(--ui-muted); margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">{{ $daftar_sesi->count() }} Percakapan</p>
                    </div>
                </div>
            @else
                <button wire:click="kembaliKeList" class="touch-scale" style="width: 44px; height: 44px; border-radius: 50%; background: var(--ui-surface); border: 1px solid var(--ui-border); display: flex; align-items: center; justify-content: center; color: var(--ui-black); box-shadow: 0 2px 8px rgba(0,0,0,0.04); flex-shrink: 0; border-style: solid; cursor: pointer;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                
                <div style="flex: 1;">
                    <h1 style="font-size: 20px; font-weight: 900; color: var(--ui-black); margin: 0; letter-spacing: -0.5px; line-height: 1.2;">Ruang Obrolan</h1>
                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 4px;">
                        <div style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--ui-black);"></div>
                        <p style="font-size: 12px; font-weight: 600; color: var(--ui-muted); margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">{{ $isCreatingNew ? 'Tiket Baru' : 'Tiket #' . $activeSesiId }}</p>
                    </div>
                </div>
            @endif
        </div>

        @if($activeSesiId === null && !$isCreatingNew)
            <!-- LIST PESAN -->
            <div class="workspace-content">
                <div style="padding: 12px 20px 120px 20px; display: flex; flex-direction: column; gap: 12px;">
                    
                    @if($daftar_sesi->isEmpty())
                        <div class="ambient-shadow" style="text-align: center; padding: 48px 20px; background: var(--ui-surface); border-radius: 24px; border: 1px solid rgba(0,0,0,0.02);">
                            <div style="width: 56px; height: 56px; border-radius: 16px; background-color: var(--ui-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                                <x-filament::icon icon="heroicon-o-chat-bubble-left-ellipsis" style="width: 28px; height: 28px; color: var(--ui-muted);" />
                            </div>
                            <h3 style="font-weight: 800; font-size: 15px; color: var(--ui-black); margin: 0 0 6px 0;">Kotak Masuk Kosong</h3>
                            <p style="font-size: 12px; font-weight: 500; color: var(--ui-muted); line-height: 1.5; margin: 0;">Anda belum memiliki riwayat percakapan atau tiket bantuan.</p>
                        </div>
                    @else
                        
                        @foreach($daftar_sesi as $sesi)
                            @php
                                $bg = $sesi->status === 'Selesai' ? '#D1FAE5' : ($sesi->status === 'Diproses' ? '#FFFBEB' : '#EEF2FF');
                                $tc = $sesi->status === 'Selesai' ? '#047857' : ($sesi->status === 'Diproses' ? '#D97706' : '#4F46E5');
                                $bc = $sesi->status === 'Selesai' ? '#A7F3D0' : ($sesi->status === 'Diproses' ? '#FDE68A' : '#C7D2FE');
                            @endphp
                            
                            <div wire:click="bukaSesi({{ $sesi->id }})" class="touch-scale ambient-shadow" style="background: var(--ui-surface); border-radius: 20px; padding: 16px; border: 1px solid rgba(0,0,0,0.02); cursor: pointer; position: relative;">
                                
                                @if(!$sesi->is_read_siswa)
                                    <div style="position: absolute; top: 16px; right: 16px; width: 10px; height: 10px; background-color: #EF4444; border-radius: 50%; box-shadow: 0 0 0 4px rgba(239,68,68,0.1);"></div>
                                @endif

                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-right: 16px;">
                                    <span style="font-size: 9px; font-weight: 800; color: {{ $tc }}; background-color: {{ $bg }}; padding: 4px 10px; border-radius: 8px; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid {{ $bc }};">
                                        {{ $sesi->status }}
                                    </span>
                                    <span style="font-size: 10px; font-weight: 700; color: var(--ui-muted);">{{ $sesi->updated_at->diffForHumans() }}</span>
                                </div>
                                
                                <h4 style="font-size: 14px; font-weight: 800; color: var(--ui-black); margin: 0 0 6px 0;">Tiket Bantuan #{{ $sesi->id }}</h4>
                                <p style="font-size: 12px; font-weight: 500; color: var(--ui-muted); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $sesi->details->last()->pesan ?? 'Belum ada pesan...' }}
                                </p>
                            </div>
                        @endforeach
                        
                    @endif
                </div>

                <!-- Floating Create Button -->
                <button wire:click="buatPesanBaru" class="touch-scale" style="position: absolute; bottom: calc(90px + env(safe-area-inset-bottom, 0px)); right: 24px; width: 60px; height: 60px; background: var(--ui-black); color: white; border-radius: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(24,24,27,0.3); border: none; cursor: pointer; z-index: 40;">
                    <svg style="width: 28px; height: 28px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                </button>
            </div>
            
            <!-- Bottom Navbar -->
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
                <a href="/siswa/pesan" style="display: flex; flex-direction: column; align-items: center; gap: 4px; text-decoration: none; color: var(--ui-black); flex: 1; position: relative;">
                    <div style="position: relative;">
                        <x-filament::icon icon="heroicon-s-chat-bubble-left-ellipsis" style="width: 24px; height: 24px;" />
                        @if($unreadPesan > 0)
                            <div style="position: absolute; top: -2px; right: -2px; width: 8px; height: 8px; background-color: #EF4444; border: 2px solid white; border-radius: 50%;"></div>
                        @endif
                    </div>
                    <span style="font-size: 10px; font-weight: 800;">Pesan</span>
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

            <!-- Logout Modal -->
            <div x-show="showLogoutSheet" x-cloak style="position: absolute; inset: 0; background-color: rgba(0,0,0,0.4); z-index: 99; backdrop-filter: blur(4px);" x-transition.opacity @click="showLogoutSheet = false"></div>
            
            <div x-show="showLogoutSheet" x-cloak style="position: absolute; bottom: 0; left: 0; right: 0; background-color: var(--ui-surface); border-top-left-radius: 28px; border-top-right-radius: 28px; z-index: 100; padding: 24px; padding-bottom: calc(24px + env(safe-area-inset-bottom, 0px)); box-shadow: 0 -20px 40px rgba(0,0,0,0.1);"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="transform translate-y-full" x-transition:enter-end="transform translate-y-0" 
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="transform translate-y-0" x-transition:leave-end="transform translate-y-full">
                
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
            
        @else
            <!-- RUANG OBROLAN -->
            <div class="workspace-content" 
                 x-data="{ scrollToBottom() { this.$el.scrollTop = this.$el.scrollHeight; } }"
                 x-init="scrollToBottom()"
                 x-on:livewire:morph-updated.camel="scrollToBottom()">
                
                <div style="padding: 12px 20px 24px 20px; display: flex; flex-direction: column; gap: 16px;">
                    
                    @if(count($pesan_list) === 0)
                        <div class="ambient-shadow" style="text-align: center; padding: 48px 20px; background: var(--ui-surface); border-radius: 24px; border: 1px solid rgba(0,0,0,0.02);">
                            <div style="width: 56px; height: 56px; border-radius: 16px; background-color: var(--ui-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                                <x-filament::icon icon="heroicon-o-chat-bubble-oval-left-ellipsis" style="width: 28px; height: 28px; color: var(--ui-muted);" />
                            </div>
                            <h3 style="font-weight: 800; font-size: 15px; color: var(--ui-black); margin: 0 0 6px 0;">Mulai Obrolan Baru</h3>
                            <p style="font-size: 12px; font-weight: 500; color: var(--ui-muted); line-height: 1.5; margin: 0;">Sebutkan secara detail data apa yang tidak sesuai dan perlu diperbarui oleh sistem.</p>
                        </div>
                    @else
                        
                        @foreach($pesan_list as $chat)
                            @if($chat->pengirim === 'Siswa')
                                <div style="align-self: flex-end; max-width: 85%; display: flex; flex-direction: column; align-items: flex-end;">
                                    <div class="ambient-shadow" style="background: var(--ui-black); color: white; padding: 14px 18px; border-radius: 24px; border-bottom-right-radius: 6px; font-size: 13px; font-weight: 500; line-height: 1.5;">
                                        {{ $chat->pesan }}
                                    </div>
                                    <div style="font-size: 10px; color: var(--ui-muted); text-align: right; margin-top: 6px; font-weight: 800; text-transform: uppercase;">
                                        Saya &bull; {{ $chat->created_at->format('H:i') }}
                                    </div>
                                </div>
                            @else
                                <div style="align-self: flex-start; max-width: 85%; display: flex; flex-direction: column; align-items: flex-start;">
                                    <div class="ambient-shadow" style="background: var(--ui-surface); border: 1px solid rgba(0,0,0,0.02); padding: 14px 18px; border-radius: 24px; border-bottom-left-radius: 6px; font-size: 13px; font-weight: 500; line-height: 1.5; color: var(--ui-black);">
                                        {{ $chat->pesan }}
                                    </div>
                                    <div style="font-size: 10px; color: var(--ui-muted); margin-top: 6px; font-weight: 800; text-transform: uppercase;">
                                        Admin ({{ $chat->user->name ?? '-' }}) &bull; {{ $chat->created_at->format('H:i') }}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        
                    @endif
                </div>
            </div>

            <!-- CHAT INPUT / FOOTER -->
            @if(isset($sesiAktif) && $sesiAktif->status === 'Selesai')
                <div style="flex-shrink: 0; padding: 16px 20px calc(16px + env(safe-area-inset-bottom, 0px)) 20px; background: rgba(245,245,247,0.9); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-top: 1px solid rgba(0,0,0,0.05); z-index: 30; text-align: center;">
                    <p style="font-size: 12px; font-weight: 700; color: var(--ui-muted); margin-bottom: 12px;">Sesi obrolan ini sudah diarsipkan.</p>
                    <button wire:click="kembaliKeList" class="touch-scale" type="button" style="width: 100%; background: var(--ui-black); color: white; border-radius: 100px; padding: 14px; font-weight: 800; font-size: 12px; border: none; cursor: pointer; text-transform: uppercase; letter-spacing: 0.5px;">
                        Kembali Ke Kotak Masuk
                    </button>
                </div>
            @else
                <div style="flex-shrink: 0; padding: 16px 20px calc(16px + env(safe-area-inset-bottom, 0px)) 20px; background: rgba(245,245,247,0.9); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-top: 1px solid rgba(0,0,0,0.05); z-index: 30;">
                    <form wire:submit="kirimPesan" style="display: flex; flex-direction: column; gap: 12px;">
                        {{ $this->form }}
                        <button type="submit" wire:loading.attr="disabled" class="touch-scale" style="width: 100%; background: var(--ui-black); color: white; border-radius: 100px; padding: 16px; font-weight: 800; font-size: 13px; border: none; box-shadow: 0 4px 20px rgba(24,24,27,0.25); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <div wire:loading.remove wire:target="kirimPesan">
                                Kirim Pesan
                            </div>
                            <div wire:loading.flex wire:target="kirimPesan" style="align-items: center; gap: 8px;" x-cloak>
                                <svg style="animation: spin 1s linear infinite; height: 16px; width: 16px; color: white;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Mengirim...
                            </div>
                        </button>
                        <style>@keyframes spin { 100% { transform: rotate(360deg); } }</style>
                    </form>
                </div>
            @endif
            
        @endif
    </div>
</x-filament-panels::page.simple>