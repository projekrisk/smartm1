<x-filament-panels::page.simple>
    <div class="w-full h-full" x-data="{ showFotoModal: false }">
        
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
                .dark .theme-bg { background-color: #0f172a; }
                .dark .theme-card { background-color: #1e293b; border: 1px solid #334155; box-shadow: 0 8px 30px rgba(0,0,0,0.2); }
                .dark .theme-text { color: #f8fafc; }
                .dark .theme-text-muted { color: #94a3b8; }
                .android-content { flex: 1; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; -ms-overflow-style: none; -webkit-overflow-scrolling: touch; }
                .android-content::-webkit-scrollbar { display: none; }
                
                .data-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 12px 0; border-bottom: 1px dashed rgba(0,0,0,0.05); }
                .dark .data-row { border-bottom: 1px dashed rgba(255,255,255,0.05); }
                .data-row:last-child { border-bottom: none; padding-bottom: 0; }
                .data-label { font-size: 11px; font-weight: 600; width: 40%; flex-shrink: 0; padding-top: 2px; }
                .data-val { font-size: 12px; font-weight: 800; text-align: right; width: 60%; line-height: 1.4; word-break: break-word; }
                
                [x-cloak] { display: none !important; }
            </style>
        </div>

        <div class="android-app-container theme-bg">
            
            <div style="flex-shrink: 0; background: linear-gradient(135deg, #2563eb, #3730a3); padding: 40px 24px 60px 24px; color: white; position: relative; z-index: 10;">
                <a href="/siswa" style="position: absolute; top: 32px; left: 20px; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.9)'" onmouseup="this.style.transform='scale(1)'">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                
                <div style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.4); background-color: #f1f5f9; overflow: hidden; margin: 0 auto 12px auto; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                    @if($siswa?->foto)
                        <img src="{{ url('/uploads/' . $siswa?->foto) }}" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="color: #2563eb; font-weight: 900; font-size: 2rem;">{{ substr($siswa?->nama_lengkap ?? 'S', 0, 1) }}</span>
                    @endif
                </div>

                <div style="text-align: center;">
                    <p style="font-size: 10px; font-weight: 800; letter-spacing: 1px; color: #bfdbfe; text-transform: uppercase; margin-bottom: 4px;">Profil Siswa</p>
                    <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0; line-height: 1.2; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">{{ $siswa?->nama_lengkap ?? '-' }}</h1>
                    <div style="display: inline-flex; align-items: center; gap: 6px; background-color: rgba(0,0,0,0.25); padding: 4px 14px; border-radius: 999px; font-size: 10px; font-weight: bold; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px); margin-top: 10px; text-transform: uppercase; letter-spacing: 0.5px;">
                        NISN: {{ $siswa?->nisn ?? '-' }} &bull; KELAS: {{ $siswa?->kelas?->nama_kelas ?? '-' }}
                    </div>
                </div>
            </div>

            <div class="android-content theme-bg" style="border-top-left-radius: 2.5rem; border-top-right-radius: 2.5rem; margin-top: -30px; padding: 24px 20px 40px 20px; position: relative; z-index: 20; box-shadow: 0 -10px 25px rgba(0,0,0,0.1);">
                
                <button @click="showFotoModal = true" type="button" style="width: 100%; text-decoration: none; display: flex; align-items: center; justify-content: space-between; background: linear-gradient(135deg, #ec4899, #be185d); padding: 14px 20px; border-radius: 20px; color: white; box-shadow: 0 8px 20px rgba(236, 72, 153, 0.2); margin-bottom: 16px; border: none; cursor: pointer; transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.98)'" onmouseup="this.style.transform='scale(1)'">
                    <div style="display: flex; align-items: center; gap: 14px; text-align: left;">
                        <div style="background-color: rgba(255,255,255,0.1); padding: 8px; border-radius: 12px; backdrop-filter: blur(4px);">
                            <x-filament::icon icon="heroicon-s-camera" style="width: 20px; height: 20px; color: white;" />
                        </div>
                        <div>
                            <h4 style="font-weight: 900; font-size: 13px; margin: 0 0 2px 0;">Ganti Foto Profil</h4>
                            <p style="font-size: 10px; font-weight: 600; opacity: 0.9; margin: 0;">Perbarui foto avatar Anda</p>
                        </div>
                    </div>
                    <div style="background-color: rgba(255,255,255,0.1); border-radius: 50%; padding: 4px; display: flex; align-items: center; justify-content: center;">
                        <x-filament::icon icon="heroicon-m-chevron-right" style="width: 16px; height: 16px; color: white;" />
                    </div>
                </button>

                <a href="/siswa/ubah-password" style="text-decoration: none; display: flex; align-items: center; justify-content: space-between; background: linear-gradient(135deg, #0f172a, #334155); padding: 14px 20px; border-radius: 20px; color: white; box-shadow: 0 8px 20px rgba(15, 23, 42, 0.2); margin-bottom: 24px; transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.98)'" onmouseup="this.style.transform='scale(1)'">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="background-color: rgba(255,255,255,0.1); padding: 8px; border-radius: 12px; backdrop-filter: blur(4px);">
                            <x-filament::icon icon="heroicon-s-key" style="width: 20px; height: 20px; color: white;" />
                        </div>
                        <div>
                            <h4 style="font-weight: 900; font-size: 13px; margin: 0 0 2px 0;">Keamanan Akun</h4>
                            <p style="font-size: 10px; font-weight: 600; opacity: 0.8; margin: 0;">Ubah Password Login Anda</p>
                        </div>
                    </div>
                    <div style="background-color: rgba(255,255,255,0.1); border-radius: 50%; padding: 4px; display: flex; align-items: center; justify-content: center;">
                        <x-filament::icon icon="heroicon-m-chevron-right" style="width: 16px; height: 16px; color: white;" />
                    </div>
                </a>

                <div class="theme-card" style="border-radius: 20px; padding: 20px; margin-bottom: 16px;">
                    <h3 class="theme-text" style="font-size: 13px; font-weight: 900; margin: 0 0 8px 0; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px;" class="dark:border-slate-700">
                        <x-filament::icon icon="heroicon-s-identification" style="width: 18px; height: 18px; color: #2563eb;" />
                        Identitas Diri
                    </h3>
                    
                    <div style="display: flex; flex-direction: column;">
                        <div class="data-row">
                            <span class="theme-text-muted data-label">NIS Lokal</span>
                            <span class="theme-text data-val">{{ $siswa?->nis ?? '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="theme-text-muted data-label">NIK (No. KTP)</span>
                            <span class="theme-text data-val">{{ $siswa?->nik ?? '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="theme-text-muted data-label">No. Kartu Keluarga</span>
                            <span class="theme-text data-val">{{ $siswa?->no_kk ?? '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="theme-text-muted data-label">Jenis Kelamin</span>
                            <span class="theme-text data-val">{{ $siswa?->jenis_kelamin ?? '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="theme-text-muted data-label">Tempat Lahir</span>
                            <span class="theme-text data-val">{{ $siswa?->tempat_lahir ?? '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="theme-text-muted data-label">Tanggal Lahir</span>
                            <span class="theme-text data-val">{{ $siswa?->tanggal_lahir ? \Carbon\Carbon::parse($siswa?->tanggal_lahir)->isoFormat('D MMMM Y') : '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="theme-text-muted data-label">Agama</span>
                            <span class="theme-text data-val">{{ $siswa?->agama ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="theme-card" style="border-radius: 20px; padding: 20px; margin-bottom: 16px;">
                    <h3 class="theme-text" style="font-size: 13px; font-weight: 900; margin: 0 0 8px 0; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px;" class="dark:border-slate-700">
                        <x-filament::icon icon="heroicon-s-map-pin" style="width: 18px; height: 18px; color: #10b981;" />
                        Kontak & Alamat
                    </h3>
                    
                    <div style="display: flex; flex-direction: column;">
                        <div class="data-row">
                            <span class="theme-text-muted data-label">No. Telepon / HP</span>
                            <span class="theme-text data-val">{{ $siswa?->telepon ?? '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="theme-text-muted data-label">Email Aktif</span>
                            <span class="theme-text data-val">{{ $siswa?->email ?? '-' }}</span>
                        </div>
                        <div class="data-row" style="align-items: flex-start;">
                            <span class="theme-text-muted data-label">Alamat Lengkap</span>
                            <span class="theme-text data-val">
                                {{ $siswa?->alamat ?? '-' }}<br>
                                RT {{ $siswa?->rt ?? '-' }} / RW {{ $siswa?->rw ?? '-' }}, Kel. {{ $siswa?->kelurahan ?? '-' }}<br>
                                Kec. {{ $siswa?->kecamatan ?? '-' }}, {{ $siswa?->kabupaten ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="theme-card" style="border-radius: 20px; padding: 20px; margin-bottom: 16px;">
                    <h3 class="theme-text" style="font-size: 13px; font-weight: 900; margin: 0 0 8px 0; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px;" class="dark:border-slate-700">
                        <x-filament::icon icon="heroicon-s-users" style="width: 18px; height: 18px; color: #f59e0b;" />
                        Orang Tua / Wali
                    </h3>
                    
                    <div style="display: flex; flex-direction: column;">
                        <div class="data-row">
                            <span class="theme-text-muted data-label">Nama Ayah</span>
                            <span class="theme-text data-val">{{ $siswa?->nama_ayah ?? '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="theme-text-muted data-label">No. HP Ayah</span>
                            <span class="theme-text data-val">{{ $siswa?->telepon_ayah ?? '-' }}</span>
                        </div>
                        <div class="data-row" style="border-top: 2px solid #f1f5f9; padding-top: 12px; margin-top: 4px;" class="dark:border-slate-700">
                            <span class="theme-text-muted data-label">Nama Ibu</span>
                            <span class="theme-text data-val">{{ $siswa?->nama_ibu ?? '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="theme-text-muted data-label">No. HP Ibu</span>
                            <span class="theme-text data-val">{{ $siswa?->telepon_ibu ?? '-' }}</span>
                        </div>
                        <div class="data-row" style="border-top: 2px solid #f1f5f9; padding-top: 12px; margin-top: 4px;" class="dark:border-slate-700">
                            <span class="theme-text-muted data-label">Nama Wali</span>
                            <span class="theme-text data-val">{{ $siswa?->nama_wali ?? '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="theme-text-muted data-label">No. HP Wali</span>
                            <span class="theme-text data-val">{{ $siswa?->telepon_wali ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <a href="/siswa/pesan" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; text-align: center; background-color: rgba(245, 158, 11, 0.1); border: 1px dashed #f59e0b; padding: 20px; border-radius: 20px; margin-top: 32px; transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.98)'" onmouseup="this.style.transform='scale(1)'">
                    <div style="background-color: #f59e0b; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3);">
                        <x-filament::icon icon="heroicon-s-chat-bubble-left-ellipsis" style="width: 20px; height: 20px;" />
                    </div>
                    <h4 style="color: #d97706; font-size: 13px; font-weight: 900; margin: 0 0 6px 0;">Butuh Perbaikan Data?</h4>
                    <p style="color: #b45309; font-size: 11px; font-weight: 600; line-height: 1.5; margin: 0;">Jika data Anda tidak sesuai dengan dokumen resmi, <strong>silakan hubungi kami di sini.</strong></p>
                </a>

                <div style="height: 20px;"></div>
            </div>
        </div>

        <div x-show="showFotoModal" x-cloak 
             style="position: fixed; top: 0; bottom: 0; left: 50%; transform: translateX(-50%); width: 100%; max-width: 414px; z-index: 999999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px);"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            
            <div @click.away="showFotoModal = false" class="theme-card" 
                 style="position: absolute; bottom: 0; left: 0; right: 0; width: 100%; border-radius: 2.5rem 2.5rem 0 0; padding: 24px 24px 40px 24px; box-shadow: 0 -15px 40px rgba(0,0,0,0.3); display: flex; flex-direction: column;"
                 x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                
                <div style="width: 48px; height: 6px; border-radius: 999px; background-color: #cbd5e1; margin: 0 auto 24px auto;" class="dark:bg-slate-600"></div>

                <h3 class="theme-text" style="font-size: 15px; font-weight: 900; margin: 0 0 16px 0; text-align: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px;" class="dark:border-slate-700">
                    Perbarui Foto Profil
                </h3>
                
                <form wire:submit="simpanFoto" style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="width: 100%; display: flex; justify-content: center; [&_.fi-fo-field-wrp]:mx-auto [&_.fi-fo-file-upload]:mx-auto">
                        {{ $this->fotoForm }}
                    </div>
                    
                    <button type="submit" wire:loading.attr="disabled" @click="setTimeout(() => showFotoModal = false, 800)" style="width: 100%; background: #ec4899; color: white; border-radius: 14px; padding: 14px; font-weight: 800; font-size: 12px; border: none; cursor: pointer; transition: transform 0.1s; box-shadow: 0 4px 15px rgba(236,72,153,0.3);" class="dark:bg-pink-600 active:scale-[0.98]">
                        <span wire:loading.remove wire:target="simpanFoto">SIMPAN FOTO BARU</span>
                        <span wire:loading wire:target="simpanFoto">MENGUNGGAH...</span>
                    </button>
                    
                    <button type="button" @click="showFotoModal = false" style="width: 100%; background: transparent; color: #64748b; font-weight: 800; font-size: 12px; border: none; cursor: pointer; margin-top: -8px;">
                        Batal & Tutup
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-filament-panels::page.simple>