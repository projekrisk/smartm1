<x-filament-panels::page.simple>
    <div class="w-full h-full">
        
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
                    display: flex; flex-direction: column; box-shadow: 0 0 40px rgba(0,0,0,0.15); overflow: hidden; 
                    font-family: 'Inter', system-ui, sans-serif; transition: background-color 0.3s ease;
                }
                .theme-bg { background-color: #f8fafc; }
                .dark .theme-bg { background-color: #0f172a; }
                .android-content { flex: 1; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; -ms-overflow-style: none; -webkit-overflow-scrolling: touch; }
                .android-content::-webkit-scrollbar { display: none; }
                
                /* Menyembunyikan scrollbar untuk tab horizontal */
                .hide-scroll::-webkit-scrollbar { display: none; }
                .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
                
                /* Animasi List */
                .list-item { animation: fadeIn 0.3s ease-out forwards; opacity: 0; }
                @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
            </style>
        </div>

        <div class="android-app-container theme-bg">
            
            <!-- Header Hijau Khas Aplikasi -->
            <div style="flex-shrink: 0; background: linear-gradient(135deg, #10b981, #047857); padding: 40px 24px 20px 24px; color: white; position: relative; z-index: 10;">
                <div style="display: flex; align-items: center; justify-content: center; position: relative; margin-bottom: 20px;">
                    <a href="/siswa" style="position: absolute; left: 0; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.9)'" onmouseup="this.style.transform='scale(1)'">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <div>
                        <h1 style="font-size: 1.3rem; font-weight: 900; margin: 0; text-align: center; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Direktori Guru</h1>
                    </div>
                </div>

                <!-- Input Pencarian Bulat -->
                <div style="position: relative; margin-top: 10px;">
                    <div style="position: absolute; inset-y: 0; left: 0; padding-left: 14px; display: flex; align-items: center; pointer-events: none;">
                        <svg style="width: 18px; height: 18px; color: #a7f3d0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau tugas utama..." 
                           style="width: 100%; background-color: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; font-size: 13px; border-radius: 999px; padding: 12px 16px 12px 40px; outline: none; backdrop-filter: blur(4px);">
                </div>
            </div>

            <!-- Tab Kategori Interaktif (Scrollable) -->
            <div style="background-color: white; border-bottom: 1px solid #f1f5f9; padding: 12px 16px; position: relative; z-index: 5;" class="dark:bg-slate-900 dark:border-slate-800">
                <div class="hide-scroll" style="display: flex; gap: 8px; overflow-x: auto; white-space: nowrap; padding-bottom: 2px;">
                    @php
                        $tabs = ['Semua' => 'Semua', 'Kepala Sekolah' => 'Kepsek', 'Guru' => 'Guru', 'Tenaga Kependidikan' => 'Staf / TU'];
                    @endphp
                    
                    @foreach($tabs as $key => $label)
                        <button wire:click="setKategori('{{ $key }}')" 
                                style="border: none; padding: 6px 14px; border-radius: 999px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s;
                                {{ $kategori === $key ? 'background-color: #10b981; color: white; box-shadow: 0 4px 10px rgba(16,185,129,0.3);' : 'background-color: #f1f5f9; color: #64748b;' }}"
                                class="dark:{{ $kategori !== $key ? 'bg-slate-800 text-slate-400' : '' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Daftar List Pegawai -->
            <div class="android-content theme-bg" style="padding: 16px 16px 40px 16px;">
                
                @forelse($pegawais as $index => $pegawai)
                    @php
                        // Logika Cek Foto
                        $avatarUrl = asset('images/default-avatar.png');
                        if ($pegawai->foto && \Illuminate\Support\Facades\Storage::disk('publik_upload')->exists($pegawai->foto)) {
                            $avatarUrl = \Illuminate\Support\Facades\Storage::disk('publik_upload')->url($pegawai->foto);
                        }

                        // Penentuan Warna Badge
                        $badgeBg = '#f1f5f9'; $badgeText = '#64748b';
                        if ($pegawai->jenis_ptk === 'Kepala Sekolah') { $badgeBg = '#fee2e2'; $badgeText = '#ef4444'; }
                        elseif ($pegawai->jenis_ptk === 'Guru') { $badgeBg = '#e0f2fe'; $badgeText = '#0ea5e9'; }
                        elseif ($pegawai->jenis_ptk === 'Tenaga Kependidikan') { $badgeBg = '#dcfce3'; $badgeText = '#10b981'; }
                    @endphp
                    
                    <div class="list-item" style="animation-delay: {{ $index * 0.05 }}s; background-color: white; border-radius: 16px; padding: 12px; margin-bottom: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 12px; border: 1px solid #f8fafc;" class="dark:bg-slate-800 dark:border-slate-700">
                        
                        <!-- Foto Profil (Bulat) -->
                        <div style="width: 48px; height: 48px; flex-shrink: 0; border-radius: 50%; overflow: hidden; background-color: #f1f5f9; border: 2px solid #f8fafc;" class="dark:border-slate-700">
                            <img src="{{ $avatarUrl }}" alt="{{ $pegawai->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>

                        <!-- Info Pegawai -->
                        <div style="flex: 1; min-width: 0;">
                            <h3 style="font-size: 13px; font-weight: 800; color: #0f172a; margin: 0 0 2px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" class="dark:text-white">
                                {{ $pegawai->nama }}
                            </h3>
                            <p style="font-size: 11px; font-weight: 600; color: #64748b; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" class="dark:text-slate-400">
                                {{ $pegawai->tugas_utama ?? 'Belum diatur' }}
                            </p>
                        </div>

                        <!-- Badge Jenis PTK -->
                        <div style="flex-shrink: 0; background-color: {{ $badgeBg }}; color: {{ $badgeText }}; font-size: 9px; font-weight: 800; padding: 4px 8px; border-radius: 6px; text-transform: uppercase;">
                            {{ $pegawai->jenis_ptk === 'Tenaga Kependidikan' ? 'STAF/TU' : ($pegawai->jenis_ptk === 'Kepala Sekolah' ? 'KEPSEK' : 'GURU') }}
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 40px 20px; color: #64748b;">
                        <svg style="width: 48px; height: 48px; margin: 0 auto 10px auto; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <h4 style="font-size: 14px; font-weight: 700; margin: 0 0 4px 0;">Tidak ada data</h4>
                        <p style="font-size: 12px; margin: 0;">Coba gunakan kata kunci pencarian lain.</p>
                    </div>
                @endforelse

                <!-- Penahan Jarak Bawah -->
                <div style="height: 20px;"></div>
            </div>
        </div>

    </div>
</x-filament-panels::page.simple>