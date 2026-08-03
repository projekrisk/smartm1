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
                height: 100% !important; 
                display: flex; flex-direction: column; box-shadow: 0 0 40px rgba(0,0,0,0.15); overflow: hidden; 
                font-family: 'Inter', system-ui, sans-serif; transition: background-color 0.3s ease;
            }
            .theme-bg { background-color: #f8fafc; }
            .theme-card { background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 8px 30px rgba(0,0,0,0.04); }
            .theme-text { color: #0f172a; }
            .theme-text-muted { color: #64748b; }
            .theme-bg-tab { background-color: #f4f4f5; margin-bottom: 16px; }
            .theme-menu-tab { background-color:  #ffffff; }

            .dark .theme-bg-tab { background-color: #131c31; }
            .dark .theme-menu-tab { background-color:  #1d2a4b; }

            .dark .theme-bg { background-color: #0f172a; }
            .dark .theme-card { background-color: #1e293b; border: 1px solid #334155; box-shadow: 0 8px 30px rgba(0,0,0,0.2); }
            .dark .theme-text { color: #f8fafc; }
            .dark .theme-text-muted { color: #94a3b8; }
            .android-content { flex: 1; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; }
            .android-content::-webkit-scrollbar { display: none; }
            [x-cloak] { display: none !important; }
        </style>
    </div>

    <div class="android-app-container theme-bg">
        <div style="flex-shrink: 0; background: linear-gradient(135deg, #2563eb, #3730a3); padding: 40px 24px 60px 24px; color: white; position: relative; z-index: 10;">
            <a href="/siswa" style="position: absolute; top: 32px; left: 20px; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.9)'" onmouseup="this.style.transform='scale(1)'">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <div style="text-align: center; margin-top: 4px;">
                <p style="font-size: 10px; font-weight: 800; letter-spacing: 1px; color: #bfdbfe; text-transform: uppercase; margin-bottom: 8px;">Kesiswaan</p>
                <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0; line-height: 1.2; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Riwayat & Catatan</h1>
            </div>
        </div>

        <div class="android-content theme-bg" x-data="{ tab: 'catatan' }" wire:ignore.self style="border-top-left-radius: 2.5rem; border-top-right-radius: 2.5rem; margin-top: -30px; padding: 24px 20px 40px 20px; position: relative; z-index: 20; box-shadow: 0 -10px 25px rgba(0,0,0,0.1);">
            
            <div class="flex theme-bg-tab p-1 rounded-2xl mb-6 shadow-inner rounded-xl">
                <button @click="tab = 'catatan'" 
                        :class="tab === 'catatan' ? 'theme-menu-tab text-blue-600 dark:text-blue-400 shadow-sm font-extrabold' : 'text-gray-500 dark:text-gray-400 font-semibold hover:text-gray-700'" 
                        class="flex-1 py-3 px-4 rounded-xl text-xs transition-all duration-200 focus:outline-none">
                    Catatan Kasus
                </button>
                <button @click="tab = 'panggilan'" 
                        :class="tab === 'panggilan' ? 'theme-menu-tab text-red-600 dark:text-red-400 shadow-sm font-extrabold' : 'text-gray-500 dark:text-gray-400 font-semibold hover:text-gray-700'" 
                        class="flex-1 py-3 px-4 rounded-xl text-xs transition-all duration-200 focus:outline-none">
                    Surat Panggilan
                </button>
            </div>

            <div x-show="tab === 'catatan'" x-cloak style="display: flex; flex-direction: column; gap: 16px;">
                @forelse($catatans as $catatan)
                    @php
                        $warnaB = $catatan->jenis_catatan === 'Positif' ? 'rgba(16,185,129,0.1)' : ($catatan->jenis_catatan === 'Negatif' ? 'rgba(239,68,68,0.1)' : 'rgba(37,99,235,0.1)');
                        $warnaT = $catatan->jenis_catatan === 'Positif' ? '#10b981' : ($catatan->jenis_catatan === 'Negatif' ? '#ef4444' : '#2563eb');
                    @endphp
                    <div class="theme-card" style="margin-bottom;14px; border-radius: 20px; padding: 16px; border-left: 4px solid {{ $warnaT }};">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="font-size: 10px; font-weight: 800; background-color: {{ $warnaB }}; color: {{ $warnaT }}; padding: 4px 8px; border-radius: 6px; text-transform: uppercase;">{{ $catatan->jenis_catatan }}</span>
                            <span style="font-size: 11px; font-weight: 700; color: #94a3b8;">{{ \Carbon\Carbon::parse($catatan->tanggal)->format('d M Y') }}</span>
                        </div>
                        <h3 class="theme-text" style="font-size: 14px; font-weight: 800; margin: 0 0 6px 0; line-height: 1.3;">{{ $catatan->judul_catatan }}</h3>
                        <p class="theme-text-muted" style="font-size: 12px; line-height: 1.5; margin: 0 0 12px 0;">"{{ $catatan->isi_catatan }}"</p>
                        
                        <div style="display: flex; align-items: center; gap: 6px; border-top: 1px dashed #e2e8f0; padding-top: 12px;" class="dark:border-slate-700">
                            <x-filament::icon icon="heroicon-m-user" style="width: 14px; height: 14px; color: #94a3b8;" />
                            <span style="font-size: 10px; font-weight: 600; color: #64748b;" class="dark:text-gray-400">Dicatat oleh: {{ $catatan->pencatat->name ?? '-' }}</span>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 40px 20px;">
                        <div style="width: 64px; height: 64px; border-radius: 20px; background-color: rgba(16,185,129,0.1); color: #10b981; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;" class="dark:bg-slate-800">
                            <x-filament::icon icon="heroicon-o-shield-check" style="width: 32px; height: 32px;" />
                        </div>
                        <h3 class="theme-text" style="font-weight: 900; font-size: 16px; margin: 0 0 8px 0;">Buku Catatan Bersih</h3>
                        <p class="theme-text-muted" style="font-size: 12px; font-weight: 600; line-height: 1.5; margin: 0;">Anda belum memiliki catatan pelanggaran atau catatan khusus lainnya.</p>
                    </div>
                @endforelse
            </div>

            <div x-show="tab === 'panggilan'" x-cloak style="display: flex; flex-direction: column; gap: 16px;">
                @forelse($panggilans as $sp)
                    <div class="theme-card" style="border-radius: 20px; padding: 16px; border: 1px solid {{ $sp->status === 'Dibuat' ? '#fecaca' : '#e2e8f0' }};" class="dark:border-slate-700">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <span class="theme-text" style="font-size: 11px; font-weight: 800;">No. {{ $sp->nomor_surat }}</span>
                            <span style="font-size: 10px; font-weight: 800; padding: 4px 8px; border-radius: 6px; text-transform: uppercase; 
                                {{ $sp->status === 'Selesai' ? 'background-color: rgba(16,185,129,0.1); color: #10b981;' : 'background-color: rgba(239,68,68,0.1); color: #ef4444;' }}">
                                {{ $sp->status }}
                            </span>
                        </div>
                        
                        <div style="background-color: rgba(0,0,0,0.03); border-radius: 12px; padding: 12px; margin-bottom: 12px;" class="dark:bg-slate-800">
                            <h4 style="font-size: 12px; font-weight: 800; color: #ef4444; margin: 0 0 4px 0;">Jadwal Pertemuan Orang Tua:</h4>
                            <p class="theme-text" style="font-size: 13px; font-weight: 800; margin: 0;">{{ \Carbon\Carbon::parse($sp->tanggal_panggilan)->format('d F Y') }} - Pukul {{ date('H:i', strtotime($sp->waktu_panggilan)) }}</p>
                            <p class="theme-text-muted" style="font-size: 11px; font-weight: 600; margin: 4px 0 0 0;">Tempat: {{ $sp->tempat_pertemuan }}</p>
                        </div>
                        
                        <h4 class="theme-text" style="font-size: 11px; font-weight: 800; margin: 0 0 4px 0;">Perihal Pemanggilan:</h4>
                        <p class="theme-text-muted" style="font-size: 12px; line-height: 1.5; margin: 0;">{{ $sp->alasan_panggilan }}</p>
                    </div>
                @empty
                    <div style="text-align: center; padding: 40px 20px;">
                        <x-filament::icon icon="heroicon-o-envelope-open" style="width: 40px; height: 40px; margin: 0 auto 12px auto; color: #cbd5e1;" />
                        <p class="theme-text-muted" style="font-size: 13px; font-weight: bold; margin: 0;">Tidak ada surat panggilan orang tua.</p>
                    </div>
                @endforelse
            </div>
            
            <div style="height: 30px;"></div>
        </div>
    </div>
</x-filament-panels::page.simple>