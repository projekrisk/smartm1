<x-filament-panels::page>
    <style>
        /* CSS Reset & Simulator Layar HP (Disesuaikan untuk halaman dalam) */
        .fi-topbar, .fi-sidebar { display: none !important; }
        .fi-main { padding: 0 !important; margin: 0 !important; max-width: 100% !important; background: transparent !important; }
        body { margin: 0; overflow: hidden; background-color: #e2e8f0; }
        .dark body { background-color: #020617; }

        .android-app-container {
            width: 100%; max-width: 414px; margin: 0 auto;
            height: 100vh; height: 100dvh;
            position: relative; display: flex; flex-direction: column;
            box-shadow: 0 0 40px rgba(0,0,0,0.15); overflow: hidden;
            font-family: 'Inter', system-ui, sans-serif;
        }

        .theme-bg { background-color: #f8fafc; }
        .theme-card { background-color: #ffffff; border: 1px solid #f1f5f9; }
        .theme-text { color: #0f172a; }
        .theme-text-muted { color: #64748b; }
        
        .dark .theme-bg { background-color: #0f172a; }
        .dark .theme-card { background-color: #1e293b; border: 1px solid #334155; }
        .dark .theme-text { color: #f8fafc; }
        .dark .theme-text-muted { color: #94a3b8; }

        .android-content {
            flex: 1; overflow-y: auto; overflow-x: hidden; padding-bottom: 100px; scrollbar-width: none;
        }
        .android-content::-webkit-scrollbar { display: none; }
        
        /* Navbar State Aktif */
        .nav-item { color: #94a3b8; transition: color 0.2s; }
        .dark .nav-item { color: #64748b; }
        .nav-item.active { color: #2563eb; }
        
        /* Trik Tab Aktif Alpine.js */
        [x-cloak] { display: none !important; }

        /* Paksa hapus topbar dan header */
        .header, .fi-topbar { display: none !important; }
        .fi-page-header { display: none !important; }
        
        /* Perbaikan agar scrollbar hilang */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <div class="android-app-container theme-bg">
        <div class="android-content" x-data="{ activeTab: '{{ $hariIni === 'Minggu' ? 'Senin' : $hariIni }}' }">
            
            <!-- APP BAR KECIL (Khusus Halaman Dalam) -->
            <div style="background: linear-gradient(135deg, #2563eb, #3730a3); padding: 30px 20px 20px 20px; color: white; position: relative;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h1 style="font-size: 1.25rem; font-weight: 800; margin: 0;">Aktivitas Saya</h1>
                    <div style="font-size: 10px; background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 20px; font-weight: bold;">
                        {{ $siswa->kelas->nama_kelas ?? '-' }}
                    </div>
                </div>
            </div>

            <!-- TABS HARI (Scroll Horizontal) -->
            <div class="flex items-center gap-2 overflow-x-auto pb-4 pt-2 px-5 snap-x scrollbar-hide" style="scrollbar-width: none;">
                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                    <button @click="activeTab = '{{ $hari }}'" 
                            :class="activeTab === '{{ $hari }}' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700'"
                            class="px-5 py-2.5 rounded-full text-xs font-black uppercase transition-all duration-300 snap-start active:scale-95 whitespace-nowrap">
                        {{ $hari }}
                    </button>
                @endforeach
            </div>

            <!-- ISI KONTEN JADWAL -->
            <div style="padding: 20px;">
                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                    <div x-show="activeTab === '{{ $hari }}'" x-cloak>
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 16px;">
                            <h3 class="theme-text" style="font-size: 16px; font-weight: 900; margin: 0;">Jadwal Pelajaran</h3>
                            <span class="theme-text-muted" style="font-size: 11px; font-weight: bold;">{{ $jadwalSeminggu[$hari]->count() }} Sesi</span>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            @forelse($jadwalSeminggu[$hari] as $jadwal)
                                <div class="theme-card" style="border-radius: 16px; padding: 16px; display: flex; gap: 16px; align-items: center; position: relative; overflow: hidden;">
                                    
                                    <!-- Garis Warna Samping -->
                                    <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background-color: #3b82f6;"></div>
                                    
                                    <!-- Waktu Pelajaran -->
                                    <div style="text-align: center; width: 50px; flex-shrink: 0;">
                                        <p class="theme-text" style="font-size: 14px; font-weight: 900; margin: 0;">{{ date('H:i', strtotime($jadwal->jam_mulai)) }}</p>
                                        <p style="font-size: 10px; color: #94a3b8; font-weight: bold; margin: 2px 0 0 0;">s/d</p>
                                        <p class="theme-text-muted" style="font-size: 11px; font-weight: bold; margin: 2px 0 0 0;">{{ date('H:i', strtotime($jadwal->jam_selesai)) }}</p>
                                    </div>
                                    
                                    <!-- Detail Pelajaran -->
                                    <div style="flex: 1; border-left: 1px dashed #cbd5e1; padding-left: 16px;" class="dark:border-gray-700">
                                        <h4 class="theme-text" style="font-weight: 900; font-size: 14px; margin: 0 0 4px 0;">{{ $jadwal->mataPelajaran->nama_pelajaran ?? '-' }}</h4>
                                        <div style="display: flex; align-items: center; gap: 6px;">
                                            <x-filament::icon icon="heroicon-m-user" style="width: 12px; height: 12px; color: #94a3b8;" />
                                            <p style="font-size: 11px; color: #64748b; font-weight: 600; margin: 0;">{{ $jadwal->guru->name ?? 'Belum ada guru' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div style="text-align: center; padding: 40px 20px; background-color: rgba(0,0,0,0.02); border-radius: 16px; border: 1px dashed #cbd5e1;" class="dark:bg-gray-800 dark:border-gray-700">
                                    <x-filament::icon icon="heroicon-o-face-smile" style="width: 32px; height: 32px; margin: 0 auto 8px auto; color: #94a3b8;" />
                                    <p class="theme-text-muted" style="font-size: 12px; font-weight: bold; margin: 0;">Asyik! Tidak ada jadwal pelajaran hari ini.</p>
                                </div>
                            @endforelse
                        </div>

                    </div>
                @endforeach
            </div>
            
        </div>

        <!-- BOTTOM NAVIGATION BAR (Sama dengan Dasbor) -->
        <div style="background-color: rgba(255, 255, 255, 0.95); position: absolute; bottom: 0; width: 100%; height: 80px; border-top-left-radius: 24px; border-top-right-radius: 24px; box-shadow: 0 -4px 20px rgba(0,0,0,0.05); z-index: 50; display: flex; justify-content: space-around; align-items: center; padding: 0 8px 8px 8px; backdrop-filter: blur(10px);" class="dark:bg-gray-900/95 dark:shadow-none dark:border-t dark:border-gray-800">
            <!-- Nav: Home -->
            <a href="/siswa" class="nav-item" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; width: 100%;">
                <x-filament::icon icon="heroicon-s-home" style="width: 24px; height: 24px; margin-bottom: 4px;" />
                <span style="font-size: 10px; font-weight: bold;">Beranda</span>
            </a>
            
            <!-- Nav: Tugas (ACTIVE) -->
            <a href="/siswa/aktivitas-siswa" class="nav-item active" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; width: 100%;">
                <x-filament::icon icon="heroicon-s-clipboard-document-list" style="width: 24px; height: 24px; margin-bottom: 4px;" />
                <span style="font-size: 10px; font-weight: bold;">Aktivitas</span>
            </a>
            
            <!-- Nav: SCAN QR (TOMBOL BESAR TENGAH) -->
            <div style="position: relative; width: 100%; display: flex; justify-content: center;">
                <a href="#" style="position: absolute; top: -36px; width: 56px; height: 56px; background-color: #2563eb; color: white; border-radius: 50%; box-shadow: 0 8px 20px rgba(37,99,235,0.4); display: flex; align-items: center; justify-content: center; border: 4px solid white;" class="dark:border-gray-900">
                    <x-filament::icon icon="heroicon-s-qr-code" style="width: 24px; height: 24px;" />
                </a>
            </div>
            
            <!-- Nav: Pesan -->
            <a href="#" class="nav-item" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; width: 100%;">
                <div style="position: relative;">
                    <x-filament::icon icon="heroicon-s-chat-bubble-left-ellipsis" style="width: 24px; height: 24px; margin-bottom: 4px;" />
                </div>
                <span style="font-size: 10px; font-weight: bold;">Pesan</span>
            </a>
            
            <!-- Nav: Profil -->
            <a href="#" class="nav-item" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; width: 100%;">
                <x-filament::icon icon="heroicon-s-user" style="width: 24px; height: 24px; margin-bottom: 4px;" />
                <span style="font-size: 10px; font-weight: bold;">Profil</span>
            </a>
        </div>
    </div>
</x-filament-panels::page>