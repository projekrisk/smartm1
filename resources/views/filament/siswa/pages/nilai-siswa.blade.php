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
            .theme-card { background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 8px 30px rgba(0,0,0,0.04); border: 1px solid #e2e8f0}
            .theme-text { color: #0f172a; }
            .theme-text-muted { color: #64748b; }
            .dark .theme-bg { background-color: #0f172a; }
            .dark .theme-card { background-color: #1e293b; border: 1px solid #334155; box-shadow: 0 8px 30px rgba(0,0,0,0.2); }
            .dark .theme-text { color: #f8fafc; }
            .dark .theme-text-muted { color: #94a3b8; }

            .theme-bg-smt { background-color: #fafafa; }
            .dark .theme-bg-smt { background-color: #253145; }
            .theme-bg-mpl { background-color: #ffffff; }
            .dark .theme-bg-mpl { background-color: #1e293b; }
            [x-cloak] { display: none !important; }
        </style>
    </div>

    <div class="android-app-container theme-bg">
        
        <div style="flex-shrink: 0; background: linear-gradient(135deg, #2563eb, #3730a3); padding: 40px 24px 60px 24px; color: white; position: relative; z-index: 10;">
            <a href="/siswa" style="position: absolute; top: 32px; left: 20px; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.9)'" onmouseup="this.style.transform='scale(1)'">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <div style="text-align: center; margin-top: 4px;">
                <p style="font-size: 10px; font-weight: 800; letter-spacing: 1px; color: #bfdbfe; text-transform: uppercase; margin-bottom: 8px;">Buku Nilai Siswa</p>
                <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0; line-height: 1.2; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Capaian Akademik</h1>
                <div style="display: inline-flex; align-items: center; gap: 6px; background-color: rgba(0,0,0,0.25); padding: 4px 14px; border-radius: 999px; font-size: 10px; font-weight: bold; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px); margin-top: 10px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Smt. {{ $taAktif ? $taAktif->semester : '-' }} &bull; {{ $totalNilaiMasuk }} Tugas
                </div>
            </div>
        </div>

        <div class="android-content theme-bg" 
             style="border-top-left-radius: 2.5rem; border-top-right-radius: 2.5rem; margin-top: -30px; padding: 32px 20px 40px 20px; position: relative; z-index: 20;">
            
            @if($nilaiGrouped->isEmpty())
                <div style="text-align: center; padding: 40px 20px;">
                    <div style="width: 64px; height: 64px; border-radius: 20px; background-color: rgba(245,158,11,0.1); color: #f59e0b; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;" class="dark:bg-slate-800">
                        <x-filament::icon icon="heroicon-o-academic-cap" style="width: 32px; height: 32px;" />
                    </div>
                    <h3 class="theme-text" style="font-weight: 900; font-size: 16px; margin: 0 0 8px 0;">Belum Ada Nilai Masuk</h3>
                    <p class="theme-text-muted" style="font-size: 12px; font-weight: 600; line-height: 1.5; margin: 0;">Guru belum memasukkan daftar nilai harian atau tugas Anda di semester aktif ini.</p>
                </div>
            @else

                <div x-data="{ activeMapel: null }" style="display: flex; flex-direction: column; gap: 16px;">
                    
                    @foreach($nilaiGrouped as $mapel => $nilais)
                        @php
                            $totalNilai = $nilais->sum('nilai');
                            $rataRata = $nilais->count() > 0 ? round($totalNilai / $nilais->count(), 1) : 0;
                            $rataColor = $rataRata >= 75 ? 'text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900/30' : 'text-red-600 bg-red-100 dark:text-red-400 dark:bg-red-900/30';
                        @endphp
                        
                        <div class="theme-card" style="border-radius: 20px; overflow: hidden;" class="dark:border-slate-800">
                            
                            <button @click="activeMapel = activeMapel === '{{ Str::slug($mapel) }}' ? null : '{{ Str::slug($mapel) }}'" 
                                    style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 16px; background-color: transparent; border: none; cursor: pointer;">
                                
                                <div style="display: flex; flex-direction: column; align-items: flex-start; text-align: left; gap: 4px;">
                                    <h3 style="font-size: 14px; font-weight: 800; line-height: 1.2;" class="theme-text pr-2">
                                        {{ $mapel }}
                                    </h3>
                                    <span style="font-size: 10px; font-weight: 700;" class="theme-text-muted">
                                        {{ $nilais->count() }} Data Penilaian
                                    </span>
                                </div>
                                
                                <div style="display: flex; align-items: center; gap: 12px; flex-shrink: 0;">
                                    <div class="{{ $rataColor }} font-bold text-[11px] px-2.5 py-1 rounded-full border border-current opacity-80">
                                        {{ $rataRata }}
                                    </div>
                                    
                                    <div class="text-gray-400" :class="{'rotate-180 text-blue-500': activeMapel === '{{ Str::slug($mapel) }}', 'transition-transform duration-200': true}">
                                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </button>

                            <div x-show="activeMapel === '{{ Str::slug($mapel) }}'" x-collapse x-cloak>
                                <div style="padding: 10px 16px 16px 16px;" class="theme-bg-smt">
                                    <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 4px;">
                                        
                                        @foreach($nilais as $n)
                                            <div style="border-radius: 12px; padding: 12px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 8px rgba(0,0,0,0.02);" class="theme-bg-mpl">
                                                
                                                <div style="flex: 1; padding-right: 12px;">
                                                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 4px;">
                                                        <span style="font-size: 9px; font-weight: 800; text-transform: uppercase; color: #f59e0b; background-color: rgba(245,158,11,0.1); padding: 2px 6px; border-radius: 4px;">
                                                            {{ $n->penilaian->jenis_nilai ?? '-' }}
                                                        </span>
                                                        <span style="font-size: 10px; font-weight: 600; color: #94a3b8;">
                                                            {{ \Carbon\Carbon::parse($n->penilaian->tanggal_penilaian)->format('d/m/y') }}
                                                        </span>
                                                    </div>
                                                    <h4 style="font-size: 13px; font-weight: 700; margin: 0; line-height: 1.3;" class="theme-text">
                                                        {{ $n->penilaian->materi ?? 'Materi tidak dijelaskan' }}
                                                    </h4>
                                                    @if($n->catatan_guru)
                                                        <p style="font-size: 11px; font-weight: 600; color: #64748b; margin: 4px 0 0 0; font-style: italic;">
                                                            "{{ $n->catatan_guru }}"
                                                        </p>
                                                    @endif
                                                </div>
                                                
                                                <div style="flex-shrink: 0; width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 900; background-color: {{ $n->nilai >= 75 ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)' }}; color: {{ $n->nilai >= 75 ? '#10b981' : '#ef4444' }}; border: 2px solid {{ $n->nilai >= 75 ? '#34d399' : '#f87171' }};">
                                                    {{ rtrim(rtrim(number_format($n->nilai, 2, '.', ''), '0'), '.') }}
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                    </div>
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