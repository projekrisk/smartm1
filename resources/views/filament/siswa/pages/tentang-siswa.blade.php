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
                position: fixed; top: 0; bottom: 0; left: 0; right: 0;
                height: 100% !important;                 
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
            
            .android-app-container .fi-fo-field-wrp label span { color: #1e293b !important; font-weight: 800 !important; font-size: 11px !important; text-transform: uppercase; letter-spacing: 0.5px; }
            .dark .android-app-container .fi-fo-field-wrp label span { color: #94a3b8 !important; }
            .android-app-container .fi-input-wrp { border-radius: 12px !important; background-color: #ffffff !important; border: 1px solid #cbd5e1 !important; box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important; transition: all 0.2s ease; overflow: hidden; }
            .dark .android-app-container .fi-input-wrp { background-color: #0f172a !important; border: 1px solid #334155 !important; }
            .android-app-container .fi-input-wrp:focus-within { border-color: #4858ec !important; box-shadow: 0 0 0 1px #4861ec !important; }
            .dark .android-app-container .fi-input-wrp:focus-within { border-color: #727ff4 !important; box-shadow: 0 0 0 1px #7274f4 !important; }
            
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>
    </div>

    <div class="android-app-container theme-bg" x-data="{ reviewModal: false, formBottomSheet: false, reviewNama: '', reviewKelas: '', reviewPesan: '', reviewRating: 5, reviewWaktu: '', reviewFoto: '' }">
        
        <div style="flex-shrink: 0; background: linear-gradient(135deg, #2563eb, #3730a3); padding: 40px 24px 60px 24px; color: white; position: relative; z-index: 10;">
            <a href="/siswa" style="position: absolute; top: 32px; left: 20px; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <div style="text-align: center; margin-top: 4px;">
                <p style="font-size: 10px; font-weight: 800; letter-spacing: 1px; color: #bfdbfe; text-transform: uppercase; margin-bottom: 8px;">Informasi Sistem</p>
                <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0; line-height: 1.2;">Tentang Aplikasi</h1>
            </div>
        </div>

        <div class="android-content theme-bg" style="border-top-left-radius: 2.5rem; border-top-right-radius: 2.5rem; margin-top: -30px; padding: 32px 20px 100px 20px; position: relative; z-index: 20; box-shadow: 0 -10px 25px rgba(0,0,0,0.1);">
            
            <div style="text-align: center; margin-bottom: 32px;">
                <div style="width: 80px; height: 80px; border-radius: 24px; background: linear-gradient(135deg, #2563eb, #3730a3); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; box-shadow: 0 8px 20px rgb(57 39 219 / 30%);">
                    <x-filament::icon icon="heroicon-s-academic-cap" style="width: 44px; height: 44px; color: white;" />
                </div>
                <h2 class="theme-text" style="font-size: 20px; font-weight: 900; margin: 0;">SMART-M1 Student</h2>
                <p class="theme-text-muted" style="font-size: 12px; font-weight: 700; margin-top: 4px;">Versi 2.1.0 (Build 2026)</p>
                <p class="theme-text-muted" style="font-size: 11px; margin-top: 12px; line-height: 1.5; padding: 0 20px;">Platform manajemen edukasi terpadu untuk mendukung transparansi nilai, absensi, dan prestasi siswa.</p>
            </div>

            <div style="width: 48px; height: 6px; border-radius: 999px; background-color: #cbd5e1; margin: 0 auto 24px auto;" class="dark:bg-slate-700"></div>

            <div style="margin-top: 24px;">
                <h3 class="theme-text" style="font-size: 14px; font-weight: 900; margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px;">
                    <x-filament::icon icon="heroicon-s-chat-bubble-left-right" style="width: 18px; height: 18px; color: #2563eb;" />
                    Apa Kata Mereka? ({{ $totalTestimoni }})
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @forelse($semuaTestimoni as $testi)
                        <div class="theme-card" style="border-radius: 16px; padding: 16px; position: relative; border-left: 4px solid #728af4;">
                            
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    
                                    <div style="width: 36px; height: 36px; border-radius: 10px; background-color: rgba(236,72,153,0.1); color: #272adb; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 14px; overflow: hidden;" class="dark:bg-pink-900/30 dark:text-pink-400 border border-pink-100 dark:border-pink-900/50">
                                        @if($testi->siswa->foto)
                                            <img src="{{ url('/uploads/' . $testi->siswa->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            {{ substr($testi->siswa->nama_lengkap ?? 'S', 0, 1) }}
                                        @endif
                                    </div>
                                    
                                    <div>
                                        <h4 class="theme-text" style="font-size: 12px; font-weight: 900; margin: 0; line-height: 1.2;">{{ $testi->siswa->nama_lengkap ?? 'Siswa' }}</h4>
                                        <span class="theme-text-muted" style="font-size: 10px; font-weight: 600;">{{ $testi->siswa->kelas->nama_kelas ?? 'Tanpa Kelas' }} &bull; {{ $testi->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                
                                <div style="display: flex; color: #f59e0b; gap: 2px;">
                                    @for($i = 0; $i < $testi->rating; $i++)
                                        <x-filament::icon icon="heroicon-s-star" style="width: 12px; height: 12px;" />
                                    @endfor
                                </div>
                            </div>

                            <div>
                                <p class="theme-text line-clamp-2" style="font-size: 12px; line-height: 1.5; margin: 0; font-weight: 600; opacity: 0.9;">
                                    {{ $testi->pesan }}
                                </p>
                                
                                @if(strlen($testi->pesan) > 75)
                                    <button @click="
                                        reviewNama = {{ json_encode($testi->siswa->nama_lengkap ?? 'Siswa') }};
                                        reviewKelas = {{ json_encode($testi->siswa->kelas->nama_kelas ?? 'Tanpa Kelas') }};
                                        reviewWaktu = '{{ $testi->created_at->diffForHumans() }}';
                                        reviewRating = {{ $testi->rating }};
                                        reviewPesan = {{ json_encode($testi->pesan) }};
                                        reviewFoto = '{{ $testi->siswa->foto ? url('/uploads/' . $testi->siswa->foto) : '' }}';
                                        reviewModal = true;
                                    " style="color: #db2777; font-size: 10px; font-weight: 900; background: transparent; border: none; padding: 0; margin-top: 4px; cursor: pointer;">
                                        Baca Selengkapnya
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 24px; border: 2px dashed #cbd5e1; border-radius: 16px;" class="dark:border-slate-700">
                            <p class="theme-text-muted" style="font-size: 11px; font-weight: 600; margin: 0;">Belum ada ulasan dari siswa lain. Jadilah yang pertama!</p>
                        </div>
                    @endforelse
                </div>

                @if($totalTestimoni > count($semuaTestimoni))
                    <div style="text-align: center; margin-top: 20px;">
                        <button wire:click="loadMore" wire:loading.attr="disabled" style="background-color: #f1f5f9; color: #475569; font-size: 11px; font-weight: 800; border: none; border-radius: 999px; padding: 8px 24px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: transform 0.1s;" class="dark:bg-slate-800 dark:text-slate-300">
                            <span wire:loading.remove wire:target="loadMore">Muat Lebih Banyak ({{ count($semuaTestimoni) }} / {{ $totalTestimoni }})</span>
                            <span wire:loading wire:target="loadMore">
                                Memuat Data...
                            </span>
                        </button>
                    </div>
                @endif
            </div>

        </div>
        
        <button @click="formBottomSheet = true" style="position: absolute; bottom: 32px; right: 24px; width: 60px; height: 60px; background: linear-gradient(135deg, #2563eb, #3730a3); color: white; border-radius: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 25px rgb(39 41 219 / 40%); border: none; cursor: pointer; z-index: 50; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.9)'" onmouseup="this.style.transform='scale(1)'">
            <svg style="width: 28px; height: 28px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
            </svg>
        </button>

        <div x-show="formBottomSheet" x-cloak 
             style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; z-index: 99999; background-color: rgba(15,23,42,0.75); backdrop-filter: blur(4px);"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
             
            <div @click.away="formBottomSheet = false" class="theme-card" 
                 style="position: absolute; bottom: 0; left: 0; right: 0; width: 100%; border-radius: 2.5rem 2.5rem 0 0; padding: 24px 24px 40px 24px; box-shadow: 0 -15px 40px rgba(0,0,0,0.3); display: flex; flex-direction: column; max-height: 85vh; overflow-y: auto;"
                 x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                
                <div style="flex-shrink: 0; width: 48px; height: 6px; border-radius: 999px; background-color: #cbd5e1; margin: 0 auto 24px auto;" class="dark:bg-slate-600"></div>

                @if($sudahMenilai)
                    <div style="text-align: center; padding-top: 10px;">
                        <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #d1fae5; color: #10b981; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;" class="dark:bg-emerald-900/30 dark:text-emerald-400">
                            <x-filament::icon icon="heroicon-s-heart" style="width: 28px; height: 28px;" />
                        </div>
                        <h3 class="theme-text" style="font-size: 18px; font-weight: 900; margin-bottom: 8px;">Ulasan Terkirim!</h3>
                        <p class="theme-text-muted" style="font-size: 13px; font-weight: 600; line-height: 1.5; margin-bottom: 24px;">Kritik dan saran Anda sangat berharga bagi pengembangan sistem sekolah kita.</p>
                        
                        <button wire:click="tulisLagi" style="width: 100%; background: transparent; color: #10b981; border: 2px solid #10b981; border-radius: 16px; padding: 14px; font-weight: 900; font-size: 13px; cursor: pointer; text-transform: uppercase;">
                            Tulis Ulasan Lainnya
                        </button>
                    </div>
                @else
                    <h3 class="theme-text" style="font-size: 18px; font-weight: 900; margin-bottom: 20px; text-align: center;">Berikan Penilaian Anda</h3>
                    <form wire:submit="kirimTestimoni" style="display: flex; flex-direction: column; gap: 16px;">
                        <div style="display: flex; flex-direction: column; gap: 16px; width: 100%; [&_.fi-fo-component-ctn]:flex [&_.fi-fo-component-ctn]:flex-col [&_.fi-fo-component-ctn]:gap-4">
                            {{ $this->form }}
                        </div>
                        <button type="submit" wire:loading.attr="disabled" style="margin-top: 8px; width: 100%; background: linear-gradient(135deg, rgb(42, 39, 219), #19179d); color: white; border-radius: 16px; padding: 16px; font-weight: 900; font-size: 14px; border: none; box-shadow: 0 8px 25px rgba(219,39,119,0.3); cursor: pointer; display: flex; align-items: center; justify-content: center;">
                            <span wire:loading.remove wire:target="kirimTestimoni">KIRIM ULASAN</span>
                            <span wire:loading wire:target="kirimTestimoni">MENYIMPAN...</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div x-show="reviewModal" x-cloak 
             style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 20px; background-color: rgba(15,23,42,0.75); backdrop-filter: blur(4px);"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
             
            <div @click.away="reviewModal = false" class="theme-card" style="width: 100%; max-width: 340px; border-radius: 24px; padding: 24px; position: relative; display: flex; flex-direction: column; max-height: 80vh; place-self: center;"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95 translate-y-4" x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100 translate-y-0" x-transition:leave-end="opacity-0 transform scale-95 translate-y-4">
                
                <button @click="reviewModal = false" style="position: absolute; top: 16px; right: 16px; background: transparent; border: none; color: #94a3b8; cursor: pointer; padding: 4px;">
                    <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div style="display: flex; gap: 12px; align-items: center; margin-bottom: 20px; padding-right: 24px;">
                    <template x-if="reviewFoto">
                        <img :src="reviewFoto" style="width: 48px; height: 48px; border-radius: 14px; object-fit: cover; border: 1px solid #d2cffb;" class="dark:border-pink-900/50">
                    </template>
                    <template x-if="!reviewFoto">
                        <div style="width: 48px; height: 48px; border-radius: 14px; background-color: rgba(236,72,153,0.1); color: #4227db; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 20px;" class="dark:bg-pink-900/30 dark:text-pink-400">
                            <span x-text="reviewNama.substring(0,1)"></span>
                        </div>
                    </template>

                    <div>
                        <h4 class="theme-text" style="font-size: 15px; font-weight: 900; margin: 0; line-height: 1.2;" x-text="reviewNama"></h4>
                        <span class="theme-text-muted" style="font-size: 11px; font-weight: 600;" x-text="reviewKelas + ' • ' + reviewWaktu"></span>
                    </div>
                </div>

                <div style="display: flex; color: #f59e0b; gap: 4px; margin-bottom: 16px;">
                    <template x-for="i in reviewRating">
                        <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </template>
                </div>

                <div style="flex: 1; overflow-y: auto; padding-right: 4px; margin-bottom: 10px;">
                    <p class="theme-text" style="font-size: 13px; line-height: 1.6; margin: 0; font-weight: 600; opacity: 0.9; white-space: pre-wrap;" x-text="reviewPesan"></p>
                </div>
                
                <div style="margin-top: 14px; text-align: center;">
                    <button @click="reviewModal = false" style="background-color: #f1f5f9; color: #475569; font-size: 12px; font-weight: 800; border: none; border-radius: 12px; padding: 10px 24px; cursor: pointer; width: 100%;" class="dark:bg-slate-700 dark:text-white">Tutup Ulasan</button>
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page.simple>