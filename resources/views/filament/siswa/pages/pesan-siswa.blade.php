<x-filament-panels::page.simple>
    <div wire:ignore>
        <style>
            .fi-topbar, .fi-sidebar, .fi-header, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            html, body, .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page, section { 
                padding: 0 !important; margin: 0 !important; gap: 0 !important; height: 100vh !important; height: 100dvh !important; 
                max-width: 100% !important; width: 100% !important; overflow: hidden !important; 
                background-color: #e2e8f0 !important; box-shadow: none !important; border: none !important;
            }
            .dark body, .dark .fi-layout, .dark .fi-simple-layout, .dark .fi-simple-main { background-color: #020617 !important; }
            
            .android-app-container {
                width: 100%; max-width: 414px; margin: 0 auto; height: 100vh; height: 100dvh; position: relative; 
                position: fixed; top: 0; bottom: 0; left: 0; right: 0;
                display: flex; flex-direction: column; box-shadow: 0 0 40px rgba(0,0,0,0.15); overflow: hidden; 
                font-family: 'Inter', system-ui, sans-serif; transition: background-color 0.3s ease;
            }

            .theme-bg { background-color: #f8fafc; }
            .theme-card { background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 8px 30px rgba(0,0,0,0.04)}
            .dark .theme-card { background-color: #1e293b; border: 1px solid #334155; box-shadow: 0 8px 30px rgba(0,0,0,0.2); }
            .theme-text { color: #0f172a; }
            .theme-text-muted { color: #64748b; }
            .dark .theme-bg { background-color: #0f172a; }
            .dark .theme-text { color: #f8fafc; }
            .dark .theme-text-muted { color: #94a3b8; }
            
            .android-content { flex: 1; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; -ms-overflow-style: none; -webkit-overflow-scrolling: touch; }
            .android-content::-webkit-scrollbar { display: none; }

            .android-app-container .fi-fo-field-wrp label { display: none !important; }
            .android-app-container .fi-input-wrp { border-radius: 16px !important; background-color: #f1f5f9 !important; border: 2px solid transparent !important; box-shadow: none !important; transition: all 0.2s ease; overflow: hidden; }
            .dark .android-app-container .fi-input-wrp { background-color: #0f172a !important; border: 1px solid #334155 !important; }
            .android-app-container .fi-input-wrp:focus-within { border-color: #2563eb !important; background-color: #ffffff !important; box-shadow: 0 4px 20px rgba(37, 99, 235, 0.1) !important; }
            .dark .android-app-container .fi-input-wrp:focus-within { background-color: #020617 !important; border-color: #3b82f6 !important; }
            .android-app-container textarea { padding: 16px 20px !important; font-weight: 600 !important; font-size: 13px !important; color: #0f172a !important; background: transparent !important; resize: none !important; }
            .dark .android-app-container textarea { color: #f8fafc !important; }
        </style>
    </div>

    <div class="android-app-container theme-bg">
        
        <div style="flex-shrink: 0; background: linear-gradient(135deg, #2563eb, #3730a3); padding: 40px 24px 60px 24px; color: white; position: relative; z-index: 10;">
            @if($activeSesiId === null && !$isCreatingNew)
                <a href="/siswa" style="position: absolute; top: 32px; left: 20px; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.9)'" onmouseup="this.style.transform='scale(1)'">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                
                <div style="text-align: center; margin-top: 4px;">
                    <p style="font-size: 10px; font-weight: 800; letter-spacing: 1px; color: #bfdbfe; text-transform: uppercase; margin-bottom: 8px;">Pusat Bantuan</p>
                    <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0; line-height: 1.2;">Kotak Masuk</h1>
                    <div style="display: inline-flex; align-items: center; gap: 6px; background-color: rgba(0,0,0,0.25); padding: 4px 14px; border-radius: 999px; font-size: 10px; font-weight: bold; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px); margin-top: 10px; text-transform: uppercase;">
                        {{ $daftar_sesi->count() }} Percakapan
                    </div>
                </div>
            @else
                <button wire:click="kembaliKeList" style="cursor: pointer; position: absolute; top: 32px; left: 20px; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); transition: transform 0.2s; border: none;" onmousedown="this.style.transform='scale(0.9)'" onmouseup="this.style.transform='scale(1)'">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                
                <div style="text-align: center; margin-top: 4px;">
                    <p style="font-size: 10px; font-weight: 800; letter-spacing: 1px; color: #bfdbfe; text-transform: uppercase; margin-bottom: 8px;">Helpdesk</p>
                    <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0; line-height: 1.2;">Ruang Obrolan</h1>
                    <div style="display: inline-flex; align-items: center; gap: 6px; background-color: rgba(0,0,0,0.25); padding: 4px 14px; border-radius: 999px; font-size: 10px; font-weight: bold; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px); margin-top: 10px; text-transform: uppercase;">
                        {{ $isCreatingNew ? 'Tiket Baru' : 'Tiket #' . $activeSesiId }}
                    </div>
                </div>
            @endif
        </div>

        @if($activeSesiId === null && !$isCreatingNew)
            <div class="android-content theme-bg" style="border-top-left-radius: 2.5rem; border-top-right-radius: 2.5rem; margin-top: -30px; padding: 24px 20px 100px 20px; position: relative; z-index: 20; box-shadow: 0 -10px 25px rgba(0,0,0,0.1);">
                <div style="display: flex; flex-direction: column; gap: 14px;">
                    @forelse($daftar_sesi as $sesi)
                        <div wire:click="bukaSesi({{ $sesi->id }})" class="theme-card" style="border-radius: 20px; padding: 16px; cursor: pointer; position: relative; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.98)'" onmouseup="this.style.transform='scale(1)'">
                            @if(!$sesi->is_read_siswa)
                                <div style="position: absolute; top: 16px; right: 16px; width: 10px; height: 10px; background-color: #ef4444; border-radius: 50%; box-shadow: 0 0 0 4px rgba(239,68,68,0.1);"></div>
                            @endif

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; padding-right: 16px;">
                                <span style="font-size: 11px; font-weight: 800; color: {{ $sesi->status === 'Selesai' ? '#10b981' : ($sesi->status === 'Diproses' ? '#f59e0b' : '#2563eb') }}; background-color: {{ $sesi->status === 'Selesai' ? 'rgba(16,185,129,0.1)' : ($sesi->status === 'Diproses' ? 'rgba(245,158,11,0.1)' : 'rgba(37,99,235,0.1)') }}; padding: 4px 10px; border-radius: 8px; text-transform: uppercase;">
                                    {{ $sesi->status }}
                                </span>
                                <span class="theme-text-muted" style="font-size: 10px; font-weight: 700;">{{ $sesi->updated_at->diffForHumans() }}</span>
                            </div>
                            
                            <h4 class="theme-text" style="font-size: 13px; font-weight: 800; margin: 0 0 4px 0;">Tiket Bantuan #{{ $sesi->id }}</h4>
                            <p class="theme-text-muted" style="font-size: 12px; font-weight: 600; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $sesi->details->last()->pesan ?? 'Belum ada pesan...' }}
                            </p>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 40px 20px;">
                            <div style="width: 64px; height: 64px; border-radius: 20px; background-color: rgba(37,99,235,0.1); color: #2563eb; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                                <x-filament::icon icon="heroicon-s-inbox" style="width: 32px; height: 32px;" />
                            </div>
                            <h3 class="theme-text" style="font-weight: 900; font-size: 16px; margin: 0 0 8px 0;">Kotak Masuk Kosong</h3>
                            <p class="theme-text-muted" style="font-size: 12px; font-weight: 600; line-height: 1.5; margin: 0;">Anda belum memiliki riwayat tiket bantuan.</p>
                        </div>
                    @endforelse
                </div>

                <button wire:click="buatPesanBaru" style="position: absolute; bottom: 32px; right: 24px; width: 60px; height: 60px; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; border-radius: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 25px rgba(37,99,235,0.4); border: none; cursor: pointer; z-index: 50; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.9)'" onmouseup="this.style.transform='scale(1)'">
                    <svg style="width: 28px; height: 28px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                </button>
            </div>
            
        @else
            <div class="android-content theme-bg" 
                 x-data="{ scrollToBottom() { this.$el.scrollTop = this.$el.scrollHeight; } }"
                 x-init="scrollToBottom()"
                 x-on:livewire:morph-updated.camel="scrollToBottom()"
                 style="border-top-left-radius: 2.5rem; border-top-right-radius: 2.5rem; margin-top: -30px; padding: 24px 20px 24px 20px; position: relative; z-index: 20; box-shadow: 0 -10px 25px rgba(0,0,0,0.1);">
                
                <div style="display: flex; flex-direction: column; gap: 16px; padding-bottom: 20px;">
                    @forelse($pesan_list as $chat)
                        @if($chat->pengirim === 'Siswa')
                            <div style="align-self: flex-end; max-width: 85%; display: flex; flex-direction: column; align-items: flex-end;">
                                <div style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; padding: 12px 16px; border-radius: 20px; border-bottom-right-radius: 4px; font-size: 13px; font-weight: 600; line-height: 1.5; box-shadow: 0 4px 15px rgba(37,99,235,0.25);">
                                    {{ $chat->pesan }}
                                </div>
                                <div style="font-size: 10px; color: #94a3b8; text-align: right; margin-top: 6px; font-weight: 800; text-transform: uppercase;">
                                    Saya &bull; {{ $chat->created_at->format('H:i') }}
                                </div>
                            </div>
                        @else
                            <div style="align-self: flex-start; max-width: 85%; display: flex; flex-direction: column; align-items: flex-start;">
                                <div class="theme-card" style="padding: 12px 16px; border-radius: 20px; border-bottom-left-radius: 4px; font-size: 13px; font-weight: 600; line-height: 1.5;">
                                    <span class="theme-text">{{ $chat->pesan }}</span>
                                </div>
                                <div style="font-size: 10px; color: #94a3b8; margin-top: 6px; font-weight: 800; text-transform: uppercase;">
                                    Admin ({{ $chat->user->name ?? '-' }}) &bull; {{ $chat->created_at->format('H:i') }}
                                </div>
                            </div>
                        @endif
                    @empty
                        <div style="text-align: center; padding: 40px 20px;">
                            <div style="width: 64px; height: 64px; border-radius: 20px; background-color: rgba(37,99,235,0.1); color: #2563eb; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                                <x-filament::icon icon="heroicon-s-chat-bubble-oval-left-ellipsis" style="width: 32px; height: 32px;" />
                            </div>
                            <h3 class="theme-text" style="font-weight: 900; font-size: 16px; margin: 0 0 8px 0;">Mulai Obrolan Baru</h3>
                            <p class="theme-text-muted" style="font-size: 12px; font-weight: 600; line-height: 1.5; margin: 0;">Sebutkan detail data apa yang tidak sesuai dan ingin diperbarui.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            @if(isset($sesiAktif) && $sesiAktif->status === 'Selesai')
                <div class="theme-card" style="flex-shrink: 0; border-radius: 2.5rem 2.5rem 0 0; margin-top: -30px; padding: 32px 24px 40px 24px; position: relative; z-index: 30; box-shadow: 0 -15px 40px rgba(0,0,0,0.1); text-align: center;">
                    <p class="theme-text-muted" style="font-size: 13px; font-weight: 700; margin-bottom: 16px;">Sesi obrolan ini sudah diarsipkan.</p>
                    <button wire:click="kembaliKeList" type="button" style="width: 100%; background: #0f172a; color: white; border-radius: 16px; padding: 16px; font-weight: 900; font-size: 14px; border: none; cursor: pointer; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.96)'" onmouseup="this.style.transform='scale(1)'">
                        KEMBALI KE KOTAK MASUK
                    </button>
                </div>
            @else
                <div class="theme-card" style="flex-shrink: 0; border-radius: 2.5rem 2.5rem 0 0; margin-top: -30px; padding: 24px 24px 32px 24px; position: relative; z-index: 30; box-shadow: 0 -15px 40px rgba(0,0,0,0.1);">
                    <div style="width: 48px; height: 6px; border-radius: 999px; background-color: #cbd5e1; margin: 0 auto 20px auto;"></div>
                    <form wire:submit="kirimPesan" style="display: flex; flex-direction: column; gap: 16px;">
                        {{ $this->form }}
                        <button type="submit" wire:loading.attr="disabled" style="width: 100%; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; border-radius: 16px; padding: 16px; font-weight: 900; font-size: 14px; border: none; box-shadow: 0 8px 25px rgba(37,99,235,0.3); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.96)'" onmouseup="this.style.transform='scale(1)'">
                            <div wire:loading.remove wire:target="kirimPesan" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%;">
                                KIRIM PESAN
                            </div>
                            <div wire:loading.flex wire:target="kirimPesan" style="align-items: center; justify-content: center; gap: 8px; width: 100%;">
                                <svg style="animation: spin 1s linear infinite; height: 20px; width: 20px; color: white;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                        </button>
                    </form>
                </div>
            @endif
        @endif
    </div>
</x-filament-panels::page.simple>