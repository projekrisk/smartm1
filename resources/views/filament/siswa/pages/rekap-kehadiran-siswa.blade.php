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
        <style>
            .fi-topbar, .fi-sidebar, .fi-header, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            html, body, .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page, section { 
                position: fixed; top: 0; bottom: 0; left: 0; right: 0;
                height: 100% !important; 
                max-width: 100% !important; width: 100% !important; 
                overflow: hidden !important; 
                background-color: #e2e8f0 !important; box-shadow: none !important; border: none !important;
            }
            .dark body, .dark .fi-layout, .dark .fi-simple-layout, .dark .fi-simple-main { background-color: #020617 !important; }
            .android-app-container {
                width: 100%; max-width: 414px; margin: 0 auto; height: 100vh; height: 100dvh;
                position: fixed; top: 0; bottom: 0; left: 0; right: 0;
                box-shadow: 0 0 40px rgba(0,0,0,0.15); overflow: hidden; font-family: 'Inter', system-ui, sans-serif;
            }
            .theme-bg { background-color: #f8fafc; }
            .theme-card { background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 8px 30px rgba(0,0,0,0.04); }
            .theme-text { color: #0f172a; }
            .theme-text-muted { color: #64748b; }
            .dark .theme-bg { background-color: #0f172a; }
            .dark .theme-card { background-color: #1e293b; border: 1px solid #334155; box-shadow: 0 8px 30px rgba(0,0,0,0.2); }
            .dark .theme-text { color: #f8fafc; }
            .dark .theme-text-muted { color: #94a3b8; }
            .android-content { flex: 1; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; }
            .android-content::-webkit-scrollbar { display: none; }
            
            .badge-status { min-width: 65px; text-align: center; padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); display: inline-block; }
            .badge-sakit { background-color: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
            .badge-izin { background-color: #e0e7ff; color: #4f46e5; border: 1px solid #c7d2fe; }
            .badge-alpa { background-color: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
            .dark .badge-sakit { background-color: rgba(217, 119, 6, 0.2); color: #fcd34d; border-color: rgba(253, 230, 138, 0.2); }
            .dark .badge-izin { background-color: rgba(79, 70, 229, 0.2); color: #a5b4fc; border-color: rgba(199, 210, 254, 0.2); }
            .dark .badge-alpa { background-color: rgba(220, 38, 38, 0.2); color: #fca5a5; border-color: rgba(254, 202, 202, 0.2); }
        </style>
    </div>

    <div class="android-app-container theme-bg">
        
        <div style="flex-shrink: 0; background: linear-gradient(135deg, #2563eb, #3730a3); padding: 40px 24px 60px 24px; color: white; position: relative; z-index: 10;">
            <a href="/siswa" style="position: absolute; top: 32px; left: 20px; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.9)'" onmouseup="this.style.transform='scale(1)'">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <div style="text-align: center; margin-top: 4px;">
                <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0; line-height: 1.2; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Info Kehadiran</h1>
                <div style="display: inline-flex; align-items: center; gap: 6px; background-color: rgba(0,0,0,0.25); padding: 4px 14px; border-radius: 999px; font-size: 10px; font-weight: bold; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px); margin-top: 10px; text-transform: uppercase; letter-spacing: 0.5px;">
                    {{ $bulanTahun }}
                </div>
            </div>
        </div>

        <div class="android-content theme-bg" style="border-top-left-radius: 2.5rem; border-top-right-radius: 2.5rem; margin-top: -30px; padding: 32px 20px 24px 20px; position: relative; z-index: 20; box-shadow: 0 -10px 25px rgba(0,0,0,0.1);">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 24px;">
                <div class="theme-card" style="border-radius: 24px; padding: 20px 16px; text-align: center; display: flex; flex-direction: column; justify-content: center;">
                    <p class="theme-text-muted" style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">Semester Aktif</p>
                    <h2 class="theme-text" style="font-size: 2rem; font-weight: 900; margin: 6px 0; line-height: 1;">{{ $absenSemester }}</h2>
                    <p class="theme-text-muted" style="font-size: 9px; font-weight: 700;">Hari Tdk Hadir</p>
                </div>
                <div class="theme-card" style="border-radius: 24px; padding: 20px 16px; text-align: center; display: flex; flex-direction: column; justify-content: center; position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -10px; right: -10px; width: 40px; height: 40px; background: rgba(239, 68, 68, 0.1); border-radius: 50%; filter: blur(10px);"></div>
                    
                    <p class="theme-text-muted" style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">Bulan Ini</p>
                    <h2 style="font-size: 2rem; font-weight: 900; margin: 6px 0; line-height: 1; color: #ef4444;">{{ $absenBulan }}</h2>
                    <p class="theme-text-muted" style="font-size: 9px; font-weight: 700;">Hari Tdk Hadir</p>
                </div>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; padding: 0 4px;">
                <h3 class="theme-text" style="font-size: 14px; font-weight: 900; m-0">Riwayat Bulan Ini</h3>
                <span class="theme-text-muted" style="font-size: 10px; font-weight: 700; text-transform: uppercase;">{{ $listAbsen->count() }} Catatan</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                @forelse($listAbsen as $absen)
                    <div class="theme-card" style="border-radius: 20px; padding: 16px; display: flex; align-items: center; justify-content: space-between;">
                        
                        <div style="display: flex; align-items: center; gap: 14px; overflow: hidden; flex: 1;">
                            <div style="flex-shrink: 0; width: 40px; height: 40px; border-radius: 12px; background-color: rgba(100, 116, 139, 0.1); display: flex; align-items: center; justify-content: center;" class="text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            
                            <div style="display: flex; flex-direction: column; min-width: 0;">
                                <span class="theme-text" style="font-weight: 800; font-size: 13px;">{{ \Carbon\Carbon::parse($absen->rekapKehadiran->tanggal)->isoFormat('dddd, D MMMM Y') }}</span>
                                <span class="theme-text-muted" style="font-size: 11px; font-weight: 600; margin-top: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $absen->keterangan ?? 'Tanpa Keterangan Tambahan' }}</span>
                            </div>
                        </div>

                        <div style="flex-shrink: 0; margin-left: 12px;">
                            <div class="badge-status @if($absen->status == 'Sakit') badge-sakit @elseif($absen->status == 'Izin') badge-izin @else badge-alpa @endif">
                                {{ $absen->status }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="theme-card" style="border-radius: 20px; padding: 32px 16px; text-align: center; border: 2px dashed #e2e8f0;" class="dark:border-slate-700">
                        <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #d1fae5; color: #10b981; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;" class="dark:bg-emerald-900/30 dark:text-emerald-400">
                            <svg style="width: 28px; height: 28px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="theme-text" style="font-size: 14px; font-weight: 900; margin-bottom: 4px;">Luar Biasa!</h3>
                        <p class="theme-text-muted" style="font-size: 11px; font-weight: 600; line-height: 1.5;">Anda selalu hadir penuh di bulan ini.<br>Pertahankan semangat belajarnya!</p>
                    </div>
                @endforelse
            </div>
            
            <div style="padding-bottom: 24px;"></div>
        </div>

    </div>
</x-filament-panels::page.simple>