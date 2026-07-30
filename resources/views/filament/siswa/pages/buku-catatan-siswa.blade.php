<x-filament-panels::page.simple>
    <div wire:ignore>
        <style>
            .fi-topbar, .fi-sidebar, .fi-header, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            html, body, .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page, section { 
                padding: 0 !important; margin: 0 !important; gap: 0 !important; height: 100vh !important; height: 100dvh !important; 
                max-width: 100% !important; width: 100% !important; overflow: hidden !important; background-color: #e2e8f0 !important; box-shadow: none !important; border: none !important;
            }
            .dark body, .dark .fi-layout, .dark .fi-simple-layout, .dark .fi-simple-main { background-color: #020617 !important; }
            .android-app-container {
                width: 100%; max-width: 414px; margin: 0 auto; height: 100vh; height: 100dvh; position: relative; 
                display: flex; flex-direction: column; box-shadow: 0 0 40px rgba(0,0,0,0.15); overflow: hidden; font-family: 'Inter', system-ui, sans-serif; transition: background-color 0.3s ease;
            }
            .theme-bg { background-color: #f8fafc; }
            .theme-card { background-color: #ffffff; box-shadow: 0 8px 30px rgba(0,0,0,0.04); }
            .theme-text { color: #0f172a; }
            .theme-text-muted { color: #64748b; }
            .dark .theme-bg { background-color: #0f172a; }
            .dark .theme-card { background-color: #1e293b; box-shadow: 0 8px 30px rgba(0,0,0,0.2); }
            .dark .theme-text { color: #f8fafc; }
            .dark .theme-text-muted { color: #94a3b8; }
            .android-content { flex: 1; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; }
            .android-content::-webkit-scrollbar { display: none; }
            
            /* CSS UNTUK BARIS KIRI-KANAN */
            .data-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 6px 0; border-bottom: 1px dashed rgba(0,0,0,0.05); }
            .dark .data-row { border-bottom: 1px dashed rgba(255,255,255,0.05); }
            .data-row:last-child { border-bottom: none; }
            .data-label { font-size: 11px; font-weight: 600; width: 35%; flex-shrink: 0; }
            .data-val { font-size: 11px; font-weight: 800; text-align: right; width: 65%; line-height: 1.4; word-break: break-word; }
        </style>
    </div>

    <div class="android-app-container theme-bg">
        <div style="flex-shrink: 0; background: linear-gradient(135deg, #6366f1, #4338ca); padding: 40px 24px 60px 24px; color: white; position: relative; z-index: 10;">
            <a href="/siswa" style="position: absolute; top: 32px; left: 20px; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div style="text-align: center; margin-top: 4px;">
                <p style="font-size: 10px; font-weight: 800; letter-spacing: 1px; color: #e0e7ff; text-transform: uppercase; margin-bottom: 8px;">Kesiswaan</p>
                <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0; line-height: 1.2;">Buku Catatan</h1>
                <div style="display: inline-flex; align-items: center; gap: 6px; background-color: rgba(0,0,0,0.25); padding: 4px 14px; border-radius: 999px; font-size: 10px; font-weight: bold; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px); margin-top: 10px; text-transform: uppercase;">{{ $catatans->count() }} Data Terekam</div>
            </div>
        </div>

        <div class="android-content theme-bg" style="border-top-left-radius: 2.5rem; border-top-right-radius: 2.5rem; margin-top: -30px; padding: 24px 20px 100px 20px; position: relative; z-index: 20; box-shadow: 0 -10px 25px rgba(0,0,0,0.1);">
            
            <div x-data="{ activeAccordion: null }" style="display: flex; flex-direction: column; gap: 16px;">
                
                @forelse($catatans as $index => $item)
                    @php
                        // Menyesuaikan warna berdasarkan kategori catatan
                        $bg = 'rgba(100,116,139,0.1)'; $tc = '#64748b'; $icon = 'heroicon-s-information-circle';
                        if ($item->jenis_catatan == 'Positif') { $bg = 'rgba(16,185,129,0.1)'; $tc = '#10b981'; $icon = 'heroicon-s-star'; }
                        if ($item->jenis_catatan == 'Negatif') { $bg = 'rgba(239,68,68,0.1)'; $tc = '#ef4444'; $icon = 'heroicon-s-exclamation-triangle'; }
                        if ($item->jenis_catatan == 'Bimbingan') { $bg = 'rgba(14,165,233,0.1)'; $tc = '#0ea5e9'; $icon = 'heroicon-s-chat-bubble-bottom-center-text'; }
                        if ($item->jenis_catatan == 'Panggilan Ortu') { $bg = 'rgba(245,158,11,0.1)'; $tc = '#f59e0b'; $icon = 'heroicon-s-bell-alert'; }
                    @endphp
                    
                    <div class="theme-card" style="border-radius: 16px; overflow: hidden; border: 1px solid #f1f5f9;" class="dark:border-slate-800">
                        
                        <button @click="activeAccordion = activeAccordion === {{ $index }} ? null : {{ $index }}" type="button" style="width: 100%; text-align: left; padding: 16px; background: transparent; border: none; cursor: pointer; display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; outline: none;">
                            
                            <div style="flex-shrink: 0; width: 40px; height: 40px; border-radius: 12px; background-color: {{ $bg }}; color: {{ $tc }}; display: flex; items: center; justify-content: center; margin-top: 2px;">
                                <x-filament::icon icon="{{ $icon }}" style="width: 22px; height: 22px; margin: auto;" />
                            </div>

                            <div style="flex: 1; min-width: 0;">
                                <h4 class="theme-text" style="font-size: 14px; font-weight: 900; margin: 0 0 4px 0; line-height: 1.3;">{{ $item->judul_catatan }}</h4>
                                <span style="font-size: 11px; font-weight: 600; color: #64748b; display: block;" class="dark:text-slate-400">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMMM Y') }}
                                </span>
                            </div>

                            <div style="color: #94a3b8; transition: transform 0.3s; margin-top: 8px;" :style="activeAccordion === {{ $index }} ? 'transform: rotate(180deg);' : ''">
                                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </button>
                        
                        <div x-show="activeAccordion === {{ $index }}" x-collapse x-cloak>
                            <div style="padding: 0 16px 16px 16px;">
                                
                                <div style="border-top: 1px dashed rgba(0,0,0,0.1); padding-top: 12px;" class="dark:border-white/10">
                                    
                                    <div style="background-color: #f8fafc; padding: 12px; border-radius: 12px; margin-bottom: 12px; border: 1px solid #e2e8f0;" class="dark:bg-slate-800 dark:border-slate-700">
                                        <p class="theme-text" style="font-size: 12px; line-height: 1.5; margin: 0; font-weight: 600;">{{ $item->isi_catatan }}</p>
                                    </div>

                                    <div style="display: flex; flex-direction: column;">
                                        <div class="data-row">
                                            <span class="theme-text-muted data-label">Kategori</span>
                                            <span class="theme-text data-val" style="color: {{ $tc }};">{{ $item->jenis_catatan }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="theme-text-muted data-label">Dilaporkan Oleh</span>
                                            <span class="theme-text data-val">{{ $item->pencatat->name ?? '-' }}</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Jika ada Tindak Lanjut -->
                                    @if($item->status_tindak_lanjut === 'Sudah')
                                        <div style="margin-top: 12px; border-top: 1px dashed rgba(0,0,0,0.1); padding-top: 12px;" class="dark:border-white/10">
                                            <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                                                <x-filament::icon icon="heroicon-s-check-circle" style="width: 14px; height: 14px; color: #10b981;" />
                                                <span style="font-size: 10px; font-weight: 800; color: #10b981; text-transform: uppercase;">Sudah Ditindaklanjuti</span>
                                            </div>
                                            <p class="theme-text" style="font-size: 12px; line-height: 1.5; margin: 0; font-weight: 600;">{{ $item->tindak_lanjut }}</p>
                                            <div class="data-row" style="border: none; margin-top: 4px;">
                                                <span class="theme-text-muted data-label">Oleh</span>
                                                <span class="theme-text data-val">{{ $item->penindaklanjut->name ?? '-' }} ({{ \Carbon\Carbon::parse($item->tanggal_tindak_lanjut)->format('d/m/Y') }})</span>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 40px 20px;">
                        <div style="width: 64px; height: 64px; border-radius: 20px; background-color: rgba(99,102,241,0.1); color: #6366f1; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;" class="dark:bg-slate-800">
                            <x-filament::icon icon="heroicon-s-shield-check" style="width: 32px; height: 32px;" />
                        </div>
                        <h3 class="theme-text" style="font-weight: 900; font-size: 16px; margin: 0 0 8px 0;">Catatan Bersih</h3>
                        <p class="theme-text-muted" style="font-size: 12px; font-weight: 600; line-height: 1.5; margin: 0;">Anda belum memiliki riwayat catatan kasus maupun bimbingan di sistem.</p>
                    </div>
                @endforelse
            </div>
            
        </div>
    </div>
</x-filament-panels::page.simple>