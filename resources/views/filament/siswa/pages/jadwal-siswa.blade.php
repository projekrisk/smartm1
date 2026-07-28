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
                display: flex; flex-direction: column; box-shadow: 0 0 40px rgba(0,0,0,0.15); overflow: hidden; 
                font-family: 'Inter', system-ui, sans-serif; transition: background-color 0.3s ease;
            }

            .theme-bg { background-color: #f8fafc; }
            .theme-card { background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 8px 30px rgba(0,0,0,0.04); }
            .theme-text { color: #0f172a; }
            .theme-text-muted { color: #64748b; }
            .theme-card-border { border-bottom: 1px solid #f1f5f9; }            
            .theme-card-collapse { background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04); }            

            .dark .theme-bg { background-color: #0f172a; }
            .dark .theme-card { background-color: #1e293b; border: 1px solid #334155; box-shadow: 0 8px 30px rgba(0,0,0,0.2); }
            .dark .theme-text { color: #f8fafc; }
            .dark .theme-text-muted { color: #94a3b8; }
            .dark .theme-card-border { border-bottom: 1px solid #334155; }
            .dark .theme-card-collapse { background-color: #1e293b }            
            
            .android-content { flex: 1; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; -ms-overflow-style: none; -webkit-overflow-scrolling: touch; }
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
                <p style="font-size: 10px; font-weight: 800; letter-spacing: 1px; color: #bfdbfe; text-transform: uppercase; margin-bottom: 8px;">Informasi Akademik</p>
                <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0; line-height: 1.2; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Jadwal Pelajaran</h1>
                <div style="display: inline-flex; align-items: center; gap: 6px; background-color: rgba(0,0,0,0.25); padding: 4px 14px; border-radius: 999px; font-size: 10px; font-weight: bold; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px); margin-top: 10px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Kelas {{ $siswa->kelas->nama_kelas ?? '-' }}
                </div>
            </div>
        </div>

        <div class="android-content theme-bg" 
             style="border-top-left-radius: 2.5rem; border-top-right-radius: 2.5rem; margin-top: -30px; padding: 32px 20px 40px 20px; position: relative; z-index: 20; box-shadow: 0 -10px 25px rgba(0,0,0,0.1);">
            
            @if($jadwalGrouped->isEmpty())
                <div style="text-align: center; padding: 40px 20px;">
                    <div style="width: 64px; height: 64px; border-radius: 20px; background-color: rgba(37,99,235,0.1); color: #2563eb; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;" class="dark:bg-slate-800">
                        <x-filament::icon icon="heroicon-o-calendar-days" style="width: 32px; height: 32px;" />
                    </div>
                    <h3 class="theme-text" style="font-weight: 900; font-size: 16px; margin: 0 0 8px 0;">Belum Ada Jadwal</h3>
                    <p class="theme-text-muted" style="font-size: 12px; font-weight: 600; line-height: 1.5; margin: 0;">Jadwal pelajaran untuk kelas Anda belum diterbitkan oleh Admin Tata Usaha.</p>
                </div>
            @else
                
                @php
                    $hariIndo = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                    $hariIni = $hariIndo[date('N')];
                @endphp

                <div x-data="{ activeDay: '{{ $hariIni }}' }" style="display: flex; flex-direction: column; gap: 16px;">
                    @foreach($jadwalGrouped as $hari => $jadwals)
                        
                        <div>
                            <button @click="activeDay = activeDay === '{{ $hari }}' ? null : '{{ $hari }}'" 
                                    style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-radius: 16px; border: none; cursor: pointer; transition: background-color 0.2s;"
                                    class="dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 theme-card-collapse">
                                
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <h3 style="font-size: 14px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px; color: {{ $hari === $hariIni ? '#2563eb' : '#64748b' }};" class="{{ $hari === $hariIni ? 'dark:text-blue-400' : 'dark:text-gray-300' }}">
                                        Hari {{ $hari }}
                                    </h3>
                                    @if($hari === $hariIni)
                                        <span style="background-color: #2563eb; color: white; font-size: 9px; font-weight: 800; padding: 2px 8px; border-radius: 999px; text-transform: uppercase;">Hari Ini</span>
                                    @endif
                                </div>
                                
                                <div class="text-gray-400" :class="{'rotate-180': activeDay === '{{ $hari }}', 'transition-transform duration-200': true}">
                                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </button>

                            <div x-show="activeDay === '{{ $hari }}'" x-collapse x-cloak>
                                <div class="theme-card" style="border-radius: 20px; overflow: hidden; display: flex; flex-direction: column; margin-top: 12px;">
                                    
                                    @foreach($jadwals as $index => $item)
                                        <div style="display: flex; align-items: stretch; padding: 14px 16px; {{ !$loop->last ? : '' }}" class="{{ !$loop->last ? 'dark:border-slate-700/50' : '' }} theme-card-border">
                                            
                                            <div style="width: 60px; flex-shrink: 0; display: flex; flex-direction: column; align-items: flex-end; padding-right: 12px; border-right: 2px solid {{ $hari === $hariIni ? '#3b82f6' : '#e2e8f0' }}; margin-right: 12px;" class="{{ $hari !== $hariIni ? 'dark:border-slate-700' : '' }}">
                                                <span style="font-size: 13px; font-weight: 800;" class="theme-text">{{ date('H:i', strtotime($item->jam_mulai)) }}</span>
                                                <span style="font-size: 13px; font-weight: 600; color: #94a3b8; margin-top: 2px;">{{ date('H:i', strtotime($item->jam_selesai)) }}</span>
                                            </div>

                                            <div style="flex: 1; display: flex; flex-direction: column; justify-content: center;">
                                                <span style="font-size: 14px; font-weight: 700; line-height: 1.3;" class="theme-text">{{ $item->mataPelajaran->nama_pelajaran ?? '-' }}</span>
                                                
                                                <div style="display: flex; align-items: center; gap: 4px; margin-top: 6px;">
                                                    <x-filament::icon icon="heroicon-m-user" style="width: 12px; height: 12px; color: #94a3b8;" />
                                                    <span style="font-size: 11px; font-weight: 600; color: #64748b;" class="dark:text-gray-400">
                                                        {{ $item->guru->name ?? 'Belum ada guru' }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>
                
                <div style="height: 30px;"></div>
            @endif
        </div>
    </div>
</x-filament-panels::page.simple>