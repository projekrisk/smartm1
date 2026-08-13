<x-filament-panels::page.simple>
    @php
        $pengaturan = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) {
                $pengaturan = \App\Models\Pengaturan::first();
            }
        } catch (\Exception $e) {}
    @endphp

    <div wire:ignore>
        <script>
            // Memaksa warna status bar di mobile agar senada dengan background aplikasi
            const metaThemeColor = document.createElement('meta');
            metaThemeColor.name = 'theme-color';
            metaThemeColor.content = '#F5F5F7';
            document.head.appendChild(metaThemeColor);
        </script>
        
        <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;0,9..40,900&display=swap" rel="stylesheet">
        
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['DM Sans', 'sans-serif'],
                        },
                        colors: {
                            uibg: '#F5F5F7',
                            uisurface: '#FFFFFF',
                            uiblack: '#18181B',
                            uitext: '#27272A',
                            uimuted: '#71717A',
                            uiborder: '#E4E4E7',
                        }
                    }
                }
            }
        </script>
        <style>
            :root {
                /* Memaksa elemen native HP (Dropdown, Keyboard) menggunakan Light Mode */
                color-scheme: light !important; 
                --ui-bg: #F5F5F7;
                --ui-surface: #FFFFFF;
                --ui-black: #18181B;
                --ui-text: #27272A;
                --ui-muted: #71717A;
                --ui-border: #E4E4E7;
            }

            body { 
                font-family: 'DM Sans', sans-serif !important; 
                overflow: hidden !important; 
                background-color: var(--ui-bg) !important; 
                color: var(--ui-text) !important;
                -webkit-font-smoothing: antialiased;
                margin: 0; padding: 0;
            }

            /* Hide Filament default UI elements */
            .fi-topbar, .fi-sidebar, .fi-header, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            html, body, .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page, section { 
                padding: 0 !important; margin: 0 !important; gap: 0 !important;
                height: 100vh !important; height: 100dvh !important; 
                max-width: 100% !important; width: 100% !important; 
                background-color: transparent !important; box-shadow: none !important; border: none !important;
            }

            /* Menghapus gap bawaan section form */
            section.grid.auto-cols-fr.gap-y-6, .fi-simple-main-ctn, .fi-main-ctn { 
                gap: 0 !important; padding: 0 !important; margin: 0 !important; 
            }

            /* Main Mobile Workspace */
            .workspace-container {
                width: 100%; max-width: 414px; margin: 0 auto;
                position: fixed; top: 0; bottom: 0; left: 0; right: 0;
                display: flex; flex-direction: column;
                background-color: var(--ui-bg);
                overflow: hidden;
            }

            /* Desktop boundaries */
            @media (min-width: 640px) {
                .workspace-container {
                    left: 50%; right: auto; transform: translateX(-50%);
                    border-left: 1px solid var(--ui-border);
                    border-right: 1px solid var(--ui-border);
                    box-shadow: 0 0 50px rgba(0,0,0,0.05);
                }
            }

            /* Scrollable Area */
            .workspace-content { 
                flex: 1; overflow-y: auto; overflow-x: hidden; 
                scrollbar-width: none; 
                padding-bottom: calc(100px + env(safe-area-inset-bottom, 0px));
            }
            .workspace-content::-webkit-scrollbar { display: none; }

            /* Touch Interactions */
            .touch-scale { transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
            .touch-scale:active { transform: scale(0.96); }

            /* Override Form Filament agar Text Terlihat (Warna Hitam) */
            .review-form-wrapper .fi-fo-field-wrp label span,
            .review-form-wrapper fieldset legend span { color: var(--ui-black) !important; font-weight: 800 !important; font-size: 13px !important; letter-spacing: 0.02em; text-transform: uppercase; }
            .review-form-wrapper .fi-fo-field-wrp p { color: var(--ui-muted) !important; font-size: 12px !important; font-weight: 500 !important; margin-top: 4px !important;}
            .review-form-wrapper .fi-fo-field-wrp-error-message { color: #EF4444 !important; font-size: 12px !important; font-weight: 700 !important; }
            
            /* Warna Teks pada Opsi Radio / Select / Bintang (Dalam Wrapper) */
            .review-form-wrapper .fi-fo-radio-option-label span,
            .review-form-wrapper .fi-fo-radio-option-label { color: var(--ui-black) !important; font-weight: 600 !important; }
            
            .review-form-wrapper .fi-input-wrp {
                background-color: var(--ui-bg) !important;
                border: 1px solid var(--ui-border) !important; 
                border-radius: 16px !important; 
                box-shadow: none !important;
                transition: all 0.2s ease !important;
                overflow: hidden;
            }
            
            .review-form-wrapper .fi-input-wrp:focus-within {
                border-color: var(--ui-black) !important;
                background-color: var(--ui-surface) !important;
                box-shadow: 0 4px 12px rgba(24, 24, 27, 0.08) !important;
            }
            
            .review-form-wrapper .fi-input, 
            .review-form-wrapper textarea, 
            .review-form-wrapper select { 
                color: var(--ui-black) !important; 
                padding: 16px !important; 
                background: transparent !important;
                font-size: 15px !important;
                font-weight: 600 !important;
            }

            /* --- MENCEGAH BACKGROUND HITAM PADA DROPDOWN & SELECT --- */
            
            /* 1. Paksa Native Select Option Putih */
            select option {
                background-color: #FFFFFF !important;
                color: #18181B !important;
            }

            /* 2. Paksa Filament Custom Dropdown Putih */
            .fi-select-popover, 
            .fi-popover,
            .fi-popover-content,
            .fi-dropdown-panel,
            .fi-select-list,
            .choices__list--dropdown,
            div[role="listbox"],
            ul[role="listbox"] {
                background-color: #FFFFFF !important;
                border: 1px solid #E4E4E7 !important;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
                border-radius: 12px !important;
            }
            
            .fi-select-option, 
            .fi-dropdown-list-item,
            .choices__item--choice,
            [role="option"] {
                background-color: #FFFFFF !important;
                color: #18181B !important;
                border-radius: 8px !important;
                margin: 2px !important;
                transition: background-color 0.2s ease;
            }
            
            .fi-select-option-label,
            .fi-dropdown-list-item-label,
            .choices__item--choice,
            [role="option"] span {
                color: #18181B !important;
                font-weight: 700 !important;
            }

            .fi-select-option:hover, 
            .fi-select-option:focus,
            .fi-select-option[aria-selected="true"],
            .choices__item--selectable.is-highlighted,
            [role="option"]:hover,
            [role="option"][aria-selected="true"] {
                background-color: #F5F5F7 !important;
                color: #18181B !important;
            }

            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            [x-cloak] { display: none !important; }
        </style>
    </div>

    <div class="workspace-container selection:bg-zinc-900 selection:text-white" x-data="{ reviewModal: false, formBottomSheet: false, reviewNama: '', reviewKelas: '', reviewPesan: '', reviewRating: 5, reviewWaktu: '', reviewFoto: '' }">
        
        <!-- Header -->
        <div style="padding: 24px 20px 16px 20px; display: flex; align-items: center; gap: 16px; margin-top: env(safe-area-inset-top, 0px); background: var(--ui-bg); flex-shrink: 0; z-index: 10; border-bottom: 1px solid rgba(0,0,0,0.02);">
            <a href="/siswa" class="touch-scale" style="width: 44px; height: 44px; border-radius: 50%; background: var(--ui-surface); border: 1px solid var(--ui-border); display: flex; align-items: center; justify-content: center; color: var(--ui-black); box-shadow: 0 2px 8px rgba(0,0,0,0.04); flex-shrink: 0; text-decoration: none;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <div>
                <h1 style="font-size: 20px; font-weight: 900; color: var(--ui-black); margin: 0; letter-spacing: -0.5px; line-height: 1.2;">Tentang Aplikasi</h1>
                <div style="display: flex; align-items: center; gap: 6px; margin-top: 4px;">
                    <div style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--ui-black);"></div>
                    <p style="font-size: 12px; font-weight: 600; color: var(--ui-muted); margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">Portal Siswa</p>
                </div>
            </div>
        </div>

        <div class="workspace-content">
            <div style="padding: 24px 20px;">
                
                <!-- Info Aplikasi Card -->
                <div style="text-align: center; margin-bottom: 32px; background: var(--ui-surface); border-radius: 32px; padding: 32px 20px; border: 1px solid var(--ui-border); box-shadow: 0 10px 40px rgba(0,0,0,0.03);">
                    @if($pengaturan && $pengaturan->logo_sekolah)
                        <div style="width: 80px; height: 80px; border-radius: 24px; background: var(--ui-bg); padding: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; border: 1px solid var(--ui-border); box-shadow: 0 8px 20px rgba(0,0,0,0.05);">
                            <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                    @else
                        <div style="width: 80px; height: 80px; border-radius: 24px; background: var(--ui-black); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; box-shadow: 0 8px 20px rgba(24,24,27,0.2);">
                            <x-filament::icon icon="heroicon-s-academic-cap" style="width: 44px; height: 44px; color: white;" />
                        </div>
                    @endif
                    <h2 style="font-size: 20px; font-weight: 900; color: var(--ui-black); margin: 0;">SMART-M1 Student</h2>
                    <p style="font-size: 12px; font-weight: 700; color: var(--ui-muted); margin-top: 4px;">Versi 2.1.0 (Build 2026)</p>
                    <p style="font-size: 12px; margin-top: 16px; line-height: 1.6; color: var(--ui-text); font-weight: 500;">Platform manajemen edukasi terpadu untuk mendukung transparansi nilai, absensi, dan prestasi peserta didik.</p>
                </div>

                <!-- Testimoni Section -->
                <div>
                    <h3 style="font-size: 14px; font-weight: 900; color: var(--ui-black); margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px;">
                        <x-filament::icon icon="heroicon-s-chat-bubble-left-right" style="width: 18px; height: 18px; color: var(--ui-muted);" />
                        Ulasan Siswa ({{ $totalTestimoni }})
                    </h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @forelse($semuaTestimoni as $testi)
                            <div style="border-radius: 24px; padding: 20px; background: var(--ui-surface); border: 1px solid var(--ui-border); box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                                
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                                    <div style="display: flex; gap: 12px; align-items: center;">
                                        
                                        <div style="width: 40px; height: 40px; border-radius: 12px; background-color: var(--ui-bg); color: var(--ui-black); display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 15px; overflow: hidden; border: 1px solid var(--ui-border);">
                                            @if($testi->siswa->foto)
                                                <img src="{{ url('/uploads/' . $testi->siswa->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.outerHTML='{{ substr($testi->siswa->nama_lengkap ?? 'S', 0, 1) }}'">
                                            @else
                                                {{ substr($testi->siswa->nama_lengkap ?? 'S', 0, 1) }}
                                            @endif
                                        </div>
                                        
                                        <div>
                                            <h4 style="font-size: 13px; font-weight: 900; color: var(--ui-black); margin: 0; line-height: 1.2;">{{ $testi->siswa->nama_lengkap ?? 'Siswa' }}</h4>
                                            <span style="font-size: 10px; font-weight: 600; color: var(--ui-muted);">{{ $testi->siswa->kelas->nama_kelas ?? 'Tanpa Kelas' }} &bull; {{ $testi->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    
                                    <div style="display: flex; color: #F59E0B; gap: 2px;">
                                        @for($i = 0; $i < $testi->rating; $i++)
                                            <x-filament::icon icon="heroicon-s-star" style="width: 12px; height: 12px;" />
                                        @endfor
                                    </div>
                                </div>

                                <div>
                                    <p class="line-clamp-2" style="font-size: 13px; line-height: 1.6; margin: 0; font-weight: 500; color: var(--ui-text);">
                                        {{ $testi->pesan }}
                                    </p>
                                    
                                    @if(strlen($testi->pesan) > 85)
                                        <button class="touch-scale" @click="
                                            reviewNama = {{ json_encode($testi->siswa->nama_lengkap ?? 'Siswa') }};
                                            reviewKelas = {{ json_encode($testi->siswa->kelas->nama_kelas ?? 'Tanpa Kelas') }};
                                            reviewWaktu = '{{ $testi->created_at->diffForHumans() }}';
                                            reviewRating = {{ $testi->rating }};
                                            reviewPesan = {{ json_encode($testi->pesan) }};
                                            reviewFoto = '{{ $testi->siswa->foto ? url('/uploads/' . $testi->siswa->foto) : '' }}';
                                            reviewModal = true;
                                        " style="color: var(--ui-black); font-size: 11px; font-weight: 800; background: transparent; border: none; padding: 0; margin-top: 6px; cursor: pointer; text-decoration: underline; text-underline-offset: 2px;">
                                            Baca Selengkapnya
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 32px 24px; border: 2px dashed var(--ui-border); border-radius: 24px; background: var(--ui-surface);">
                                <p style="font-size: 12px; font-weight: 600; color: var(--ui-muted); margin: 0;">Belum ada ulasan dari siswa lain. Jadilah yang pertama!</p>
                            </div>
                        @endforelse
                    </div>

                    @if($totalTestimoni > count($semuaTestimoni))
                        <div style="text-align: center; margin-top: 24px;">
                            <button class="touch-scale" wire:click="loadMore" wire:loading.attr="disabled" style="background-color: var(--ui-surface); color: var(--ui-black); font-size: 12px; font-weight: 800; border: 1px solid var(--ui-border); box-shadow: 0 2px 10px rgba(0,0,0,0.02); border-radius: 100px; padding: 12px 24px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                                <span wire:loading.remove wire:target="loadMore">Muat Lebih Banyak ({{ count($semuaTestimoni) }} / {{ $totalTestimoni }})</span>
                                <span wire:loading.flex wire:target="loadMore" style="align-items: center; gap: 6px;">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Memuat Data...
                                </span>
                            </button>
                        </div>
                    @endif
                </div>

            </div>
        </div>
        
        <!-- FAB Add Review -->
        <button class="touch-scale" @click="formBottomSheet = true" style="position: absolute; bottom: 32px; right: 24px; width: 60px; height: 60px; background: var(--ui-black); color: white; border-radius: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(24,24,27,0.3); border: 2px solid var(--ui-surface); cursor: pointer; z-index: 50;">
            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
            </svg>
        </button>

        <!-- Bottom Sheet Formulir Ulasan -->
        <div x-show="formBottomSheet" x-cloak 
             style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; z-index: 99999; background-color: rgba(0,0,0,0.4); backdrop-filter: blur(4px);"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
             
            <div @click.away="formBottomSheet = false" 
                 style="position: absolute; bottom: 0; left: 0; right: 0; width: 100%; border-radius: 40px 40px 0 0; padding: 24px 24px calc(24px + env(safe-area-inset-bottom, 0px)) 24px; box-shadow: 0 -15px 40px rgba(0,0,0,0.1); display: flex; flex-direction: column; max-height: 90vh; background-color: var(--ui-surface); overflow-y: auto;"
                 x-transition:enter="transition ease-out duration-400 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                
                <div style="flex-shrink: 0; width: 48px; height: 6px; border-radius: 999px; background-color: var(--ui-border); margin: 0 auto 24px auto;"></div>

                @if($sudahMenilai)
                    <div style="text-align: center; padding-top: 10px; padding-bottom: 10px;">
                        <div style="width: 64px; height: 64px; border-radius: 50%; background-color: #F0FDF4; color: #10B981; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; border: 1px solid #D1FAE5;">
                            <x-filament::icon icon="heroicon-s-heart" style="width: 32px; height: 32px;" />
                        </div>
                        <h3 style="font-size: 20px; font-weight: 900; color: var(--ui-black); margin-bottom: 8px;">Ulasan Terkirim!</h3>
                        <p style="font-size: 13px; font-weight: 500; color: var(--ui-muted); line-height: 1.5; margin-bottom: 32px;">Kritik dan saran Anda sangat berharga bagi pengembangan sistem sekolah kita.</p>
                        
                        <div style="display: flex; gap: 12px;">
                            <button class="touch-scale" @click="formBottomSheet = false" style="flex: 1; background: var(--ui-bg); color: var(--ui-black); border: 1px solid var(--ui-border); border-radius: 100px; padding: 14px; font-weight: 800; font-size: 13px; cursor: pointer; text-transform: uppercase; letter-spacing: 0.5px;">
                                Tutup
                            </button>
                            <button class="touch-scale" wire:click="tulisLagi" style="flex: 1; background: var(--ui-black); color: white; border: none; border-radius: 100px; padding: 14px; font-weight: 800; font-size: 13px; cursor: pointer; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 15px rgba(24,24,27,0.2);">
                                Tulis Lagi
                            </button>
                        </div>
                    </div>
                @else
                    <div style="text-align: center; margin-bottom: 24px;">
                        <h2 style="font-size: 18px; font-weight: 900; color: var(--ui-black);">Berikan Penilaian</h2>
                        <p style="font-size: 12px; font-weight: 600; color: var(--ui-muted); mt-1">Bagikan pengalaman Anda menggunakan aplikasi ini.</p>
                    </div>

                    <form wire:submit="kirimTestimoni" class="review-form-wrapper" style="display: flex; flex-direction: column; gap: 20px;">
                        <div style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                            {{ $this->form }}
                        </div>
                        <div class="pt-2 pb-6">
                            <button type="submit" wire:loading.attr="disabled" class="touch-scale" style="width: 100%; background: var(--ui-black); color: white; border-radius: 100px; padding: 16px; font-weight: 800; font-size: 13px; border: none; box-shadow: 0 4px 20px rgba(24,24,27,0.25); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                
                                <span wire:loading.remove wire:target="kirimTestimoni">KIRIM ULASAN</span>
                                
                                <span wire:loading.flex wire:target="kirimTestimoni" style="align-items: center; gap: 8px;">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    MENYIMPAN...
                                </span>
                                
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <!-- Modal Detail Review -->
        <div x-show="reviewModal" x-cloak 
             style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 20px; background-color: rgba(0,0,0,0.5); backdrop-filter: blur(4px);"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
             
            <div @click.away="reviewModal = false" style="width: 100%; max-width: 340px; border-radius: 32px; padding: 24px; position: relative; display: flex; flex-direction: column; max-height: 80vh; background-color: var(--ui-surface); box-shadow: 0 20px 50px rgba(0,0,0,0.1); border: 1px solid var(--ui-border);"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95 translate-y-4" x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100 translate-y-0" x-transition:leave-end="opacity-0 transform scale-95 translate-y-4">
                
                <button @click="reviewModal = false" class="touch-scale" style="position: absolute; top: 16px; right: 16px; background: var(--ui-bg); border: 1px solid var(--ui-border); border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; color: var(--ui-muted); cursor: pointer;">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div style="display: flex; gap: 14px; align-items: center; margin-bottom: 20px; padding-right: 24px;">
                    <template x-if="reviewFoto">
                        <img :src="reviewFoto" x-on:error="reviewFoto = null" style="width: 48px; height: 48px; border-radius: 16px; object-fit: cover; border: 1px solid var(--ui-border);">
                    </template>
                    <template x-if="!reviewFoto">
                        <div style="width: 48px; height: 48px; border-radius: 16px; background-color: var(--ui-bg); color: var(--ui-black); display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 18px; border: 1px solid var(--ui-border);">
                            <span x-text="reviewNama.substring(0,1)"></span>
                        </div>
                    </template>

                    <div>
                        <h4 style="font-size: 15px; font-weight: 900; color: var(--ui-black); margin: 0; line-height: 1.2;" x-text="reviewNama"></h4>
                        <span style="font-size: 11px; font-weight: 600; color: var(--ui-muted);" x-text="reviewKelas + ' • ' + reviewWaktu"></span>
                    </div>
                </div>

                <div style="display: flex; color: #F59E0B; gap: 4px; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--ui-border);">
                    <template x-for="i in reviewRating">
                        <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </template>
                </div>

                <div style="flex: 1; overflow-y: auto; scrollbar-width: none; margin-bottom: 10px;">
                    <p style="font-size: 14px; line-height: 1.7; margin: 0; font-weight: 500; color: var(--ui-text); white-space: pre-wrap;" x-text="reviewPesan"></p>
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page.simple>