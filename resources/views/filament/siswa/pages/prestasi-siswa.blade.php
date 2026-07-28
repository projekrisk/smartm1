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
            .theme-card { background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 8px 30px rgba(0,0,0,0.04)}
            .dark .theme-card { background-color: #1e293b; border: 1px solid #334155; box-shadow: 0 8px 30px rgba(0,0,0,0.2); }
            
            .theme-text { color: #0f172a; }
            .theme-text-muted { color: #64748b; }
            .dark .theme-bg { background-color: #0f172a; }
            .dark .theme-text { color: #f8fafc; }
            .dark .theme-text-muted { color: #94a3b8; }
            .android-content { flex: 1; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; }
            .android-content::-webkit-scrollbar { display: none; }
            .android-app-container .fi-fo-field-wrp label span { color: #1e293b !important; font-weight: 800 !important; font-size: 11px !important; text-transform: uppercase; letter-spacing: 0.5px; }
            .dark .android-app-container .fi-fo-field-wrp label span { color: #94a3b8 !important; }
            .android-app-container .fi-input-wrp { border-radius: 12px !important; background-color: #ffffff !important; border: 1px solid #cbd5e1 !important; box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important; transition: all 0.2s ease; overflow: hidden; }
            .dark .android-app-container .fi-input-wrp { background-color: #0f172a !important; border: 1px solid #334155 !important; }
            .android-app-container .fi-input-wrp:focus-within { border-color: #2563eb !important; box-shadow: 0 0 0 1px #2563eb !important; }
            .dark .android-app-container .fi-input-wrp:focus-within { border-color: #3b82f6 !important; box-shadow: 0 0 0 1px #3b82f6 !important; }
            
            .data-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 6px 0; border-bottom: 1px dashed rgba(0,0,0,0.05); }
            .dark .data-row { border-bottom: 1px dashed rgba(255,255,255,0.05); }
            .data-row:last-child { border-bottom: none; }
            .data-label { font-size: 11px; font-weight: 600; width: 40%; flex-shrink: 0; }
            .data-val { font-size: 11px; font-weight: 800; text-align: right; width: 60%; line-height: 1.4; word-break: break-word; }

            .android-app-container form .grid, 
            .android-app-container form .grid-cols-2,
            .android-app-container form .sm\:grid-cols-2,
            .android-app-container form .md\:grid-cols-2,
            .android-app-container form .lg\:grid-cols-2,
            .android-app-container form .fi-fo-component-ctn {
                display: flex !important;
                flex-direction: column !important;
                gap: 16px !important;
            }
            .android-app-container form > div,
            .android-app-container form .fi-fo-field-wrp {
                width: 100% !important;
                grid-column: span 1 / span 1 !important;
            }
        </style>
    </div>

    <div class="android-app-container theme-bg">
        <div style="flex-shrink: 0; background: linear-gradient(135deg, #2563eb, #3730a3); padding: 40px 24px 60px 24px; color: white; position: relative; z-index: 10;">
            @if(!$isCreatingNew)
                <a href="/siswa" style="position: absolute; top: 32px; left: 20px; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div style="text-align: center; margin-top: 4px;">
                    <p style="font-size: 10px; font-weight: 800; letter-spacing: 1px; color: #bfdbfe; text-transform: uppercase; margin-bottom: 8px;">Portofolio</p>
                    <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0; line-height: 1.2;">PrestasiKu</h1>
                    <div style="display: inline-flex; align-items: center; gap: 6px; background-color: rgba(0,0,0,0.25); padding: 4px 14px; border-radius: 999px; font-size: 10px; font-weight: bold; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px); margin-top: 10px; text-transform: uppercase;">{{ $prestasis->count() }} Terdaftar</div>
                </div>
            @else
                <button wire:click="kembaliKeList" style="position: absolute; top: 32px; left: 20px; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); border: none; cursor: pointer;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <div style="text-align: center; margin-top: 4px;">
                    <p style="font-size: 10px; font-weight: 800; letter-spacing: 1px; color: #bfdbfe; text-transform: uppercase; margin-bottom: 8px;">Formulir</p>
                    <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0; line-height: 1.2;">Ajukan Prestasi</h1>
                </div>
            @endif
        </div>

        @if(!$isCreatingNew)
            <div class="android-content theme-bg" style="border-top-left-radius: 2.5rem; border-top-right-radius: 2.5rem; margin-top: -30px; padding: 24px 20px 100px 20px; position: relative; z-index: 20; box-shadow: 0 -10px 25px rgba(0,0,0,0.1);">
                
                <div x-data="{ activeAccordion: null }" style="display: flex; flex-direction: column; gap: 16px;">
                    
                    @forelse($prestasis as $index => $item)
                        @php
                            $bg = $item->status == 'Disetujui' ? 'rgba(16,185,129,0.1)' : ($item->status == 'Menunggu' ? 'rgba(245,158,11,0.1)' : 'rgba(239,68,68,0.1)');
                            $tc = $item->status == 'Disetujui' ? '#10b981' : ($item->status == 'Menunggu' ? '#f59e0b' : '#ef4444');
                        @endphp
                        
                        <div class="theme-card" style="border-radius: 16px; overflow: hidden;" class="dark:border-slate-800">
                            
                            <button @click="activeAccordion = activeAccordion === {{ $index }} ? null : {{ $index }}" type="button" style="width: 100%; text-align: left; padding: 16px; background: transparent; border: none; cursor: pointer; display: flex; justify-content: space-between; align-items: center; gap: 12px; outline: none;">
                                <div style="flex: 1; min-width: 0;">
                                    <h4 class="theme-text" style="font-size: 14px; font-weight: 900; margin: 0 0 4px 0; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item->nama_prestasi }}</h4>
                                    <span style="font-size: 11px; font-weight: 600; color: #64748b;" class="dark:text-slate-400">
                                        {{ \Carbon\Carbon::parse($item->tanggal_perolehan)->isoFormat('D MMMM Y') }}
                                    </span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                                    <span style="font-size: 10px; font-weight: 800; color: {{ $tc }}; background-color: {{ $bg }}; padding: 4px 10px; border-radius: 999px; text-transform: uppercase;">{{ $item->status }}</span>
                                    <div style="color: #94a3b8; transition: transform 0.3s;" :style="activeAccordion === {{ $index }} ? 'transform: rotate(180deg);' : ''">
                                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </button>
                            
                            <div x-show="activeAccordion === {{ $index }}" x-collapse x-cloak>
                                <div style="padding: 0 16px 16px 16px;">
                                    
                                    <div style="border-top: 1px dashed rgba(0,0,0,0.1); padding-top: 12px;" class="dark:border-white/10">
                                        <div style="display: flex; flex-direction: column;">
                                            <div class="data-row">
                                                <span class="theme-text-muted data-label">Peringkat / Juara</span>
                                                <span class="theme-text data-val" style="color: #d97706;">
                                                    <x-filament::icon icon="heroicon-s-star" style="width: 12px; height: 12px; display: inline-block; vertical-align: sub; margin-right: 2px;" />
                                                    {{ $item->juara }}
                                                </span>
                                            </div>
                                            <div class="data-row">
                                                <span class="theme-text-muted data-label">Jenis Lomba</span>
                                                <span class="theme-text data-val">{{ $item->jenis }}</span>
                                            </div>
                                            <div class="data-row">
                                                <span class="theme-text-muted data-label">Kategori</span>
                                                <span class="theme-text data-val">{{ $item->kategori }}</span>
                                            </div>
                                            <div class="data-row">
                                                <span class="theme-text-muted data-label">Tingkat</span>
                                                <span class="theme-text data-val">{{ $item->tingkat }}</span>
                                            </div>
                                            @if($item->penyelenggara)
                                            <div class="data-row">
                                                <span class="theme-text-muted data-label">Penyelenggara</span>
                                                <span class="theme-text data-val">{{ $item->penyelenggara }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        @if($item->status == 'Ditolak' && $item->catatan_admin)
                                            <div style="background-color: #fef2f2; border: 1px dashed #fca5a5; padding: 10px; border-radius: 10px; margin-top: 8px;" class="dark:bg-red-900/20 dark:border-red-800">
                                                <div style="font-size: 10px; font-weight: 800; color: #dc2626; margin-bottom: 4px; text-transform: uppercase;">Alasan Penolakan:</div>
                                                <p style="font-size: 11px; color: #991b1b; margin: 0; font-weight: 600; line-height: 1.4;" class="dark:text-red-400">{{ $item->catatan_admin }}</p>
                                            </div>
                                        @endif

                                        @if($item->bukti_file)
                                            <div style="margin-top: 12px; padding-top: 12px; border-top: 1px dashed rgba(0,0,0,0.05);" class="dark:border-white/10">
                                                <a href="{{ url('/uploads/' . $item->bukti_file) }}" target="_blank" download style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 10px; background-color: rgba(37,99,235,0.1); color: #2563eb; border-radius: 10px; font-size: 12px; font-weight: 800; text-decoration: none; transition: transform 0.1s;" class="dark:bg-slate-800 dark:text-blue-400" onmousedown="this.style.transform='scale(0.98)'" onmouseup="this.style.transform='scale(1)'">
                                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"></path></svg>
                                                    LIHAT / DOWNLOAD BUKTI
                                                </a>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 40px 20px;">
                            <div style="width: 64px; height: 64px; border-radius: 20px; background-color: rgba(37,99,235,0.1); color: #2563eb; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;" class="dark:bg-slate-800">
                                <x-filament::icon icon="heroicon-s-trophy" style="width: 32px; height: 32px;" />
                            </div>
                            <h3 class="theme-text" style="font-weight: 900; font-size: 16px; margin: 0 0 8px 0;">Belum Ada Prestasi</h3>
                            <p class="theme-text-muted" style="font-size: 12px; font-weight: 600; line-height: 1.5; margin: 0;">Ajukan sertifikat prestasi lomba atau kejuaraan Anda di sini.</p>
                        </div>
                    @endforelse
                </div>

                <button wire:click="buatPengajuanBaru" style="position: fixed; bottom: 32px; left: 50%; transform: translateX(-50%); max-width: 350px; width: 90%; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; border-radius: 20px; padding: 16px; font-weight: 900; font-size: 14px; border: none; box-shadow: 0 10px 25px rgba(37,99,235,0.4); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; z-index: 50;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    AJUKAN PRESTASI BARU
                </button>
            </div>

        @else
            <div class="android-content theme-bg" style="border-top-left-radius: 2.5rem; border-top-right-radius: 2.5rem; margin-top: -30px; padding: 24px 20px 40px 20px; position: relative; z-index: 20; box-shadow: 0 -10px 25px rgba(0,0,0,0.1);">
                <form wire:submit="kirimPengajuan" style="display: flex; flex-direction: column; gap: 16px;">
                    {{ $this->form }}
                    <button type="submit" wire:loading.attr="disabled" style="margin-top: 16px; width: 100%; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; border-radius: 16px; padding: 16px; font-weight: 900; font-size: 14px; border: none; box-shadow: 0 8px 25px rgba(37,99,235,0.3); cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        <span wire:loading.remove wire:target="kirimPengajuan">KIRIM PENGAJUAN</span>
                        <span wire:loading wire:target="kirimPengajuan">MENGUNGGAH...</span>
                    </button>
                </form>
            </div>
        @endif
    </div>
</x-filament-panels::page.simple>