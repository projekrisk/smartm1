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
                padding: 0 !important; margin: 0 !important; gap: 0 !important; height: 100vh !important; height: 100dvh !important; 
                max-width: 100% !important; width: 100% !important; overflow: hidden !important; 
                background-color: #e2e8f0 !important; box-shadow: none !important; border: none !important;
            }
            .dark body, .dark .fi-layout, .dark .fi-simple-layout, .dark .fi-simple-main { background-color: #020617 !important; }

            .android-app-container {
                width: 100%; max-width: 414px; margin: 0 auto; 
                position: fixed; top: 0; bottom: 0; left: 0; right: 0;
                height: 100% !important;
                box-shadow: 0 0 40px rgba(0,0,0,0.15); overflow: hidden; 
                font-family: 'Inter', system-ui, sans-serif; transition: background-color 0.3s ease;
            }

            .theme-bg { background-color: #f8fafc; }
            .theme-card { background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 8px 30px rgba(0,0,0,0.04); padding:10px; border-radius: 20px; transition: all 0.2s ease; }
            .theme-text { color: #0f172a; }
            .theme-text-muted { color: #64748b; }
            
            .dark .theme-bg { background-color: #0f172a; }
            .dark .theme-card { background-color: #1e293b; border: 1px solid #334155; box-shadow: 0 8px 30px rgba(0,0,0,0.2); }
            .dark .theme-text { color: #f8fafc; }
            .dark .theme-text-muted { color: #94a3b8; }

            .android-content { flex: 1; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; -ms-overflow-style: none; -webkit-overflow-scrolling: touch; }
            .android-content::-webkit-scrollbar { display: none; }
            [x-cloak] { display: none !important; }

            .status-btn { padding: 12px; text-align: center; border-radius: 12px; border: 2px solid transparent; font-weight: 900; font-size: 13px; transition: all 0.2s; background-color: #f1f5f9; color: #64748b; }
            .dark .status-btn { background-color: #334155; color: #94a3b8; }
            
            .status-btn.active-hadir { background-color: #d1fae5 !important; border-color: #10b981 !important; color: #047857 !important; box-shadow: 0 4px 15px rgba(16,185,129,0.2); transform: scale(1.02); }
            .dark .status-btn.active-hadir { background-color: rgba(16, 185, 129, 0.2) !important; color: #34d399 !important; }

            .status-btn.active-sakit { background-color: #fef3c7 !important; border-color: #f59e0b !important; color: #b45309 !important; box-shadow: 0 4px 15px rgba(245,158,11,0.2); transform: scale(1.02); }
            .dark .status-btn.active-sakit { background-color: rgba(245, 158, 11, 0.2) !important; color: #fbbf24 !important; }

            .status-btn.active-izin { background-color: #e0e7ff !important; border-color: #6366f1 !important; color: #4338ca !important; box-shadow: 0 4px 15px rgba(99,102,241,0.2); transform: scale(1.02); }
            .dark .status-btn.active-izin { background-color: rgba(99, 102, 241, 0.2) !important; color: #818cf8 !important; }

            .status-btn.active-alpa { background-color: #fee2e2 !important; border-color: #ef4444 !important; color: #b91c1c !important; box-shadow: 0 4px 15px rgba(239,68,68,0.2); transform: scale(1.02); }
            .dark .status-btn.active-alpa { background-color: rgba(239, 68, 68, 0.2) !important; color: #f87171 !important; }

            .badge-status { min-width: 65px; text-align: center; padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); display: inline-block; }
            .badge-sakit { background-color: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
            .badge-izin { background-color: #e0e7ff; color: #4f46e5; border: 1px solid #c7d2fe; }
            .badge-alpa { background-color: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
            
            /* Warna Khusus Dispensasi */
            .badge-dispensasi { background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
            
            .dark .badge-sakit { background-color: rgba(217, 119, 6, 0.2); color: #fcd34d; border-color: rgba(253, 230, 138, 0.2); }
            .dark .badge-izin { background-color: rgba(79, 70, 229, 0.2); color: #a5b4fc; border-color: rgba(199, 210, 254, 0.2); }
            .dark .badge-alpa { background-color: rgba(220, 38, 38, 0.2); color: #fca5a5; border-color: rgba(254, 202, 202, 0.2); }
            .dark .badge-dispensasi { background-color: rgba(71, 85, 105, 0.3); color: #cbd5e1; border-color: rgba(100, 116, 139, 0.4); }
        </style>
    </div>

    <div class="android-app-container theme-bg" x-data="{ activeModal: null }">
        
        <form wire:submit="simpan" style="display: flex; flex-direction: column; height: 100%; width: 100%; position: relative; overflow: hidden;">
            
            <div style="flex-shrink: 0; background: linear-gradient(135deg, #2563eb, #3730a3); padding: 40px 24px 60px 24px; color: white; position: relative; z-index: 10;">
                <a href="/siswa" style="position: absolute; top: 32px; left: 20px; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.9)'" onmouseup="this.style.transform='scale(1)'">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                
                <div style="text-align: center; margin-top: 4px;">
                    <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0; line-height: 1.2; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Absensi Kelas</h1>
                    <div style="display: inline-flex; align-items: center; gap: 6px; background-color: rgba(0,0,0,0.25); padding: 4px 14px; border-radius: 999px; font-size: 10px; font-weight: bold; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px); margin-top: 10px; text-transform: uppercase; letter-spacing: 0.5px;">
                        {{ $namaKelas }} • {{ $tanggalIndo }}
                    </div>
                </div>
            </div>

            <div class="android-content theme-bg" style="border-top-left-radius: 2.5rem; border-top-right-radius: 2.5rem; margin-top: -40px; padding: 32px 20px 24px 20px; position: relative; z-index: 50; box-shadow: 0 -10px 25px rgba(0,0,0,0.1);">
                
                @if($isLocked)
                    <div style="background-color: #fef3c7; color: #92400e; padding: 14px 16px; border-radius: 16px; margin-bottom: 24px; font-size: 11px; font-weight: bold; display: flex; align-items: center; gap: 10px; border: 1px solid #fde68a; box-shadow: 0 4px 10px rgba(245, 158, 11, 0.1);">
                        <x-filament::icon icon="heroicon-s-lock-closed" style="width: 24px; height: 24px; color: #d97706;" />
                        Data absensi telah dikunci secara permanen oleh Tata Usaha.
                    </div>
                @endif

                <div style="text-align: center; margin-bottom: 20px;">
                    <p style="font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Pilih siswa yang tidak hadir</p>
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach($absensi as $index => $item)
                        @php
                            // Ambil penanda apakah dia sedang dispensasi
                            $isDispensasi = $item['is_dispensasi'] ?? false;
                        @endphp
                        
                        <div wire:key="siswa-row-{{ $item['siswa_id'] }}" x-data="{ localStatus: '{{ $item['status'] }}' }">
                            
                            <!-- 🌟 LOGIKA: Hapus 'cursor-pointer' dan cegah @click jika siswa sedang Dispensasi -->
                            <div class="theme-card rounded-[20px] p-[16px] {{ $isDispensasi || $isLocked ? 'opacity-80' : 'cursor-pointer' }} transition-transform duration-200 flex items-center justify-between" 
                                 @if(!$isLocked && !$isDispensasi) @click="activeModal = {{ $index }}" @endif
                                 :style="activeModal === {{ $index }} ? 'transform: scale(0.96); opacity: 0.8;' : ''">
                                
                                <div style="display: flex; align-items: center; gap: 14px; overflow: hidden; flex: 1; min-width: 0;">
                                    <div style="flex-shrink: 0; width: 46px; height: 46px; border-radius: 14px; background-color: rgba(37,99,235,0.1); color: #2563eb; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 16px;" class="dark:bg-slate-700 dark:text-blue-400">
                                        {{ substr($item['nama'], 0, 1) }}
                                    </div>
                                    <div style="display: flex; flex-direction: column; min-width: 0;">
                                        <span class="theme-text" style="font-weight: 800; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; width: 100%;">{{ $item['nama'] }}</span>
                                        
                                        @if($isDispensasi)
                                            <!-- Tampilkan informasi gembok Keterangan Surat -->
                                            <span style="font-size: 10px; font-weight: 700; margin-top: 2px; color: #0ea5e9;" class="dark:text-sky-400">
                                                <svg style="width: 10px; height: 10px; display: inline-block; vertical-align: text-top; margin-right: 2px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                                Terkunci: {{ $item['keterangan'] }}
                                            </span>
                                        @else
                                            <span class="theme-text-muted" style="font-size: 10px; font-weight: 700; margin-top: 2px;">NISN: {{ $item['nisn'] ?? $item['nis'] }}</span>
                                        @endif
                                        
                                    </div>
                                </div>

                                <div style="flex-shrink: 0; margin-left: 12px; min-width: 65px; display: flex; justify-content: flex-end;">
                                    <div x-show="localStatus === 'Hadir'" x-cloak style="color: #cbd5e1;" class="dark:text-slate-600">
                                        <svg style="width: 24px; height: 24px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <!-- Menambahkan badge-dispensasi -->
                                    <div x-show="localStatus !== 'Hadir'" x-cloak class="badge-status" 
                                         style="display: flex; align-items: center; justify-content: center; gap: 4px;"
                                         :class="{ 'badge-sakit': localStatus === 'Sakit', 'badge-izin': localStatus === 'Izin', 'badge-alpa': localStatus === 'Alpa', 'badge-dispensasi': localStatus === 'Dispensasi' }">
                                        <span x-text="localStatus"></span>
                                        
                                        @if($isDispensasi)
                                            <!-- Ikon gembok di dalam badge Dispensasi -->
                                            <svg style="width: 12px; height: 12px; opacity: 0.7;" fill="currentColor" viewBox="0 0 20 20"><path d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"></path></svg>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Pilihan Status (Tidak akan terbuka jika Dispensasi) -->
                            <div x-show="activeModal === {{ $index }}" x-cloak 
                                 style="position: fixed; top: 0; bottom: 0; left: 50%; transform: translateX(-50%); width: 100%; max-width: 414px; z-index: 999999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px);"
                                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                
                                <div @click.away="activeModal = null" 
                                     class="theme-card" 
                                     style="position: absolute; bottom: 0; left: 0; right: 0; width: 100%; border-radius: 2.5rem 2.5rem 0 0; padding: 24px 24px 40px 24px; box-shadow: 0 -15px 40px rgba(0,0,0,0.3);"
                                     x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                                     x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                                     
                                     <div style="width: 48px; height: 6px; border-radius: 999px; background-color: #cbd5e1; margin: 0 auto 24px auto;" class="dark:bg-slate-600"></div>

                                     <div style="text-align: center; margin-bottom: 24px;">
                                        <div style="width: 56px; height: 56px; border-radius: 16px; background-color: rgba(37,99,235,0.1); color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 900; margin: 0 auto 12px auto;" class="dark:bg-slate-700 dark:text-blue-400">
                                            {{ substr($item['nama'], 0, 1) }}
                                        </div>
                                        <h3 class="theme-text" style="font-weight: 900; font-size: 1.125rem; text-transform: uppercase; line-height: 1.2; margin: 0;">{{ $item['nama'] }}</h3>
                                        <p class="theme-text-muted" style="font-size: 10px; font-weight: 800; margin-top: 6px; letter-spacing: 0.5px;">PILIH STATUS KEHADIRAN</p>
                                    </div>

                                    <div style="display: flex; flex-direction: column; gap: 20px;">
                                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                                            <label style="cursor: pointer; display: block;">
                                                <input type="radio" x-model="localStatus" wire:model="absensi.{{ $index }}.status" value="Hadir" style="display: none;">
                                                <div class="status-btn" :class="localStatus === 'Hadir' ? 'active-hadir' : ''">Hadir</div>
                                            </label>
                                            <label style="cursor: pointer; display: block;">
                                                <input type="radio" x-model="localStatus" wire:model="absensi.{{ $index }}.status" value="Sakit" style="display: none;">
                                                <div class="status-btn" :class="localStatus === 'Sakit' ? 'active-sakit' : ''">Sakit</div>
                                            </label>
                                            <label style="cursor: pointer; display: block;">
                                                <input type="radio" x-model="localStatus" wire:model="absensi.{{ $index }}.status" value="Izin" style="display: none;">
                                                <div class="status-btn" :class="localStatus === 'Izin' ? 'active-izin' : ''">Izin</div>
                                            </label>
                                            <label style="cursor: pointer; display: block;">
                                                <input type="radio" x-model="localStatus" wire:model="absensi.{{ $index }}.status" value="Alpa" style="display: none;">
                                                <div class="status-btn" :class="localStatus === 'Alpa' ? 'active-alpa' : ''">Alpa</div>
                                            </label>
                                        </div>
                                        
                                        <div>
                                            <label class="theme-text-muted" style="display: block; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; margin-left: 4px;">Keterangan Tambahan</label>
                                            <input wire:model.defer="absensi.{{ $index }}.keterangan" type="text" 
                                                   style="width: 100%; border: 2px solid #f1f5f9; border-radius: 12px; padding: 14px 16px; font-size: 13px; font-weight: 700; outline: none; transition: border-color 0.2s;" 
                                                   class="theme-bg theme-text dark:border-slate-700 dark:focus:border-blue-500 focus:border-blue-500" 
                                                   placeholder="Opsional (Cth: Surat dokter)">
                                        </div>
                                    </div>

                                    <button @click="activeModal = null" type="button" style="margin-top: 24px; width: 100%; padding: 16px; background: #0f172a; color: white; border-radius: 14px; font-weight: 800; font-size: 13px; border: none; cursor: pointer; transition: transform 0.1s;" class="dark:bg-blue-600 active:scale-[0.98]">
                                        Selesai & Tutup Pilihan
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if(!$isLocked)
                <div style="flex-shrink: 0; padding: 16px 20px 24px 20px; position: relative; z-index: 30;" class="dark:bg-slate-900 dark:border-slate-800">
                    <button type="submit" wire:loading.attr="disabled" style="width: 100%; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; border-radius: 16px; padding: 16px; font-weight: 900; font-size: 14px; border: none; box-shadow: 0 8px 25px rgba(37,99,235,0.3); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.96)'" onmouseup="this.style.transform='scale(1)'" onmouseleave="this.style.transform='scale(1)'">
                        
                        <div wire:loading.remove wire:target="simpan" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%;">
                            SIMPAN ABSENSI
                        </div>
                        
                        <div wire:loading.flex wire:target="simpan" style="align-items: center; justify-content: center; gap: 8px; width: 100%;" x-cloak>
                            MENYIMPAN DATA...
                        </div>
                    </button>
                    <style>@keyframes spin { 100% { transform: rotate(360deg); } }</style>
                </div>
            @endif

        </form>
    </div>
</x-filament-panels::page.simple>