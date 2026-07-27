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
            
            .android-content { flex: 1; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; -ms-overflow-style: none; -webkit-overflow-scrolling: touch; }
            .android-content::-webkit-scrollbar { display: none; }
            [x-cloak] { display: none !important; }
        </style>
    </div>

    <div class="android-app-container theme-bg">
        
        <!-- HEADER -->
        <div style="flex-shrink: 0; background: linear-gradient(135deg, #2563eb, #3730a3); padding: 40px 24px 60px 24px; color: white; position: relative; z-index: 10;">
            <a href="/siswa" style="position: absolute; top: 32px; left: 20px; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.9)'" onmouseup="this.style.transform='scale(1)'">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <div style="text-align: center; margin-top: 4px;">
                <p style="font-size: 10px; font-weight: 800; letter-spacing: 1px; color: #bfdbfe; text-transform: uppercase; margin-bottom: 8px;">Akademik</p>
                <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0; line-height: 1.2; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">E-Rapor Siswa</h1>
                <div style="display: inline-flex; align-items: center; gap: 6px; background-color: rgba(0,0,0,0.25); padding: 4px 14px; border-radius: 999px; font-size: 10px; font-weight: bold; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px); margin-top: 10px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Nilai Akhir Semester
                </div>
            </div>
        </div>

        <!-- KONTEN -->
        <div class="android-content theme-bg" 
             style="border-top-left-radius: 2.5rem; border-top-right-radius: 2.5rem; margin-top: -30px; padding: 24px 20px 40px 20px; position: relative; z-index: 20; box-shadow: 0 -10px 25px rgba(0,0,0,0.1);">
            
            @if($raporGrouped->isEmpty())
                <!-- TAMPILAN KOSONG -->
                <div style="text-align: center; padding: 40px 20px;">
                    <div style="width: 64px; height: 64px; border-radius: 20px; background-color: rgba(147,51,234,0.1); color: #9333ea; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;" class="dark:bg-slate-800">
                        <x-filament::icon icon="heroicon-o-folder-open" style="width: 32px; height: 32px;" />
                    </div>
                    <h3 class="theme-text" style="font-weight: 900; font-size: 16px; margin: 0 0 8px 0;">Belum Ada Rapor</h3>
                    <p class="theme-text-muted" style="font-size: 12px; font-weight: 600; line-height: 1.5; margin: 0;">Nilai akhir semester Anda belum diterbitkan atau direkap oleh Admin.</p>
                </div>
            @else
                
                <!-- TOMBOL CETAK BUKU RAPOR -->
                <div style="margin-bottom: 24px;">
                    <a href="{{ url('/cetak/buku-rapor/' . $siswa->id) }}" target="_blank" style="text-decoration: none; display: flex; align-items: center; justify-content: space-between; background: linear-gradient(135deg, #0f172a, #334155); padding: 14px 20px; border-radius: 20px; color: white; box-shadow: 0 8px 20px rgba(15, 23, 42, 0.2); transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.98)'" onmouseup="this.style.transform='scale(1)'">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="background-color: rgba(255,255,255,0.1); padding: 10px; border-radius: 12px; backdrop-filter: blur(4px);">
                                <x-filament::icon icon="heroicon-s-document-arrow-down" style="width: 22px; height: 22px; color: white;" />
                            </div>
                            <div>
                                <h4 style="font-weight: 900; font-size: 13px; margin: 0 0 2px 0;">Unduh Buku Rapor</h4>
                                <p style="font-size: 10px; font-weight: 600; opacity: 0.8; margin: 0;">Cetak PDF Resmi</p>
                            </div>
                        </div>
                        <div style="background-color: rgba(255,255,255,0.1); border-radius: 50%; padding: 4px; display: flex; align-items: center; justify-content: center;">
                            <x-filament::icon icon="heroicon-m-chevron-right" style="width: 16px; height: 16px; color: white;" />
                        </div>
                    </a>
                </div>

                @php
                    // Ambil nama semester terbaru agar terbuka otomatis
                    $semesterTerbaru = $raporGrouped->keys()->first();
                @endphp

                <!-- STATE ALPINE.JS: Mengelola status buka-tutup per semester -->
                <div x-data="{ activeSmt: '{{ Str::slug($semesterTerbaru) }}' }" style="display: flex; flex-direction: column; gap: 16px;">
                    
                    @foreach($raporGrouped as $semester => $nilais)
                        @php
                            // Hitung Rata-rata Semester ini
                            $totalNilai = $nilais->sum('nilai_akhir');
                            $rataRata = $nilais->count() > 0 ? round($totalNilai / $nilais->count(), 1) : 0;
                            $rataColor = $rataRata >= 75 ? 'text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900/30 border-green-200 dark:border-green-800' : 'text-red-600 bg-red-100 dark:text-red-400 dark:bg-red-900/30 border-red-200 dark:border-red-800';
                        @endphp
                        
                        <!-- BLOK PER SEMESTER (ACCORDION ITEM) -->
                        <div class="theme-card" style="border-radius: 20px; overflow: hidden;" class="dark:border-slate-800">
                            
                            <button @click="activeSmt = activeSmt === '{{ Str::slug($semester) }}' ? null : '{{ Str::slug($semester) }}'" 
                                    style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 16px; background-color: transparent; border: none; cursor: pointer;"
                                    class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                
                                <div style="display: flex; flex-direction: column; align-items: flex-start; text-align: left; gap: 4px;">
                                    <h3 style="font-size: 13px; font-weight: 900; line-height: 1.2; text-transform: uppercase;" class="theme-text pr-2">
                                        {{ $semester }}
                                    </h3>
                                    <span style="font-size: 10px; font-weight: 700;" class="theme-text-muted">
                                        Rata-rata: <span class="font-bold {{ $rataRata >= 75 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">{{ $rataRata }}</span>
                                    </span>
                                </div>
                                
                                <div class="text-gray-400" :class="{'rotate-180 text-blue-500': activeSmt === '{{ Str::slug($semester) }}', 'transition-transform duration-200': true}">
                                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </button>

                            <!-- Area Detail Nilai Tersembunyi -->
                            <div x-show="activeSmt === '{{ Str::slug($semester) }}'" x-collapse x-cloak>
                                <div style="padding: 10px 16px 16px 16px;" class="theme-bg-smt">
                                    <div style="display: flex; flex-direction: column; gap: 6px; margin-top: 4px;">
                                        
                                        @foreach($nilais->sortBy('mataPelajaran.nama_pelajaran') as $n)
                                            <div style="border-radius: 12px; padding: 12px; display: flex; align-items: stretch; gap: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);" class="theme-bg-mpl">
                                                
                                                <!-- Blok Nilai Kiri -->
                                                <div style="flex-shrink: 0; width: 46px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px; border-right: 1px dashed #e2e8f0; padding-right: 12px;" class="dark:border-slate-700">
                                                    <span style="font-size: 18px; font-weight: 900; line-height: 1;" class="{{ $n->nilai_akhir >= 75 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                        {{ $n->nilai_akhir }}
                                                    </span>
                                                    <span style="font-size: 10px; font-weight: 800; background-color: #f1f5f9; color: #475569; padding: 2px 8px; border-radius: 4px; border: 1px solid #e2e8f0;" class="dark:bg-slate-700 dark:text-gray-300 dark:border-slate-600">
                                                        {{ $n->predikat ?? '-' }}
                                                    </span>
                                                </div>
                                                
                                                <!-- Blok Keterangan Kanan -->
                                                <div style="flex: 1; display: flex; flex-direction: column; justify-content: center;">
                                                    <h4 style="font-size: 13px; font-weight: 800; margin: 0 0 4px 0; line-height: 1.2;" class="theme-text">
                                                        {{ $n->mataPelajaran->nama_pelajaran ?? '-' }}
                                                    </h4>
                                                    <p style="font-size: 10px; font-weight: 600; color: #64748b; margin: 0; line-height: 1.4;" class="dark:text-gray-400">
                                                        {{ $n->deskripsi ?: 'Tidak ada deskripsi kompetensi.' }}
                                                    </p>
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