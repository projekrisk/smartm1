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
        <script>
            const metaThemeColor = document.createElement('meta');
            metaThemeColor.name = 'theme-color';
            metaThemeColor.content = '#F5F5F7';
            document.head.appendChild(metaThemeColor);
        </script>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;0,9..40,900&display=swap" rel="stylesheet">
        
        <style>
            :root {
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

            .fi-topbar, .fi-sidebar, .fi-header, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            html, body, .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page, section { 
                padding: 0 !important; margin: 0 !important; gap: 0 !important;
                height: 100vh !important; height: 100dvh !important; 
                max-width: 100% !important; width: 100% !important; 
                background-color: transparent !important; box-shadow: none !important; border: none !important;
            }

            .workspace-container {
                width: 100%; max-width: 414px; margin: 0 auto;
                position: fixed; top: 0; bottom: 0; left: 0; right: 0;
                display: flex; flex-direction: column;
                background-color: var(--ui-bg);
                overflow: hidden;
            }

            @media (min-width: 640px) {
                .workspace-container {
                    left: 50%; right: auto; transform: translateX(-50%);
                    border-left: 1px solid var(--ui-border);
                    border-right: 1px solid var(--ui-border);
                    box-shadow: 0 0 50px rgba(0,0,0,0.05);
                }
            }

            .workspace-content { 
                flex: 1; overflow-y: auto; overflow-x: hidden; 
                padding-bottom: calc(40px + env(safe-area-inset-bottom, 0px)); 
                scrollbar-width: none; 
                display: flex; flex-direction: column; align-items: center;
            }
            .workspace-content::-webkit-scrollbar { display: none; }

            .touch-scale { transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
            .touch-scale:active { transform: scale(0.96); }

            * { box-sizing: border-box; }
            
            #print-card-wrapper {
                position: absolute;
                top: -9999px;
                left: -9999px;
                z-index: -100;
                opacity: 0;
                pointer-events: none;
            }

            @keyframes pulse-card {
                0%, 100% { opacity: 1; }
                50% { opacity: .5; }
            }
            .animate-pulse-card {
                animation: pulse-card 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }
        </style>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    </div>

    <div class="workspace-container selection:bg-zinc-900 selection:text-white">
        
        <div style="padding: 24px 20px 16px 20px; display: flex; align-items: center; gap: 16px; margin-top: env(safe-area-inset-top, 0px); background: var(--ui-bg); flex-shrink: 0; z-index: 10; border-bottom: 1px solid rgba(0,0,0,0.02); width: 100%;">
            <a href="/siswa" class="touch-scale" style="width: 44px; height: 44px; border-radius: 50%; background: var(--ui-surface); border: 1px solid var(--ui-border); display: flex; align-items: center; justify-content: center; color: var(--ui-black); box-shadow: 0 2px 8px rgba(0,0,0,0.04); flex-shrink: 0; text-decoration: none;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <div>
                <h1 style="font-size: 20px; font-weight: 900; color: var(--ui-black); margin: 0; letter-spacing: -0.5px; line-height: 1.2;">Kartu Pelajar</h1>
                <div style="display: flex; align-items: center; gap: 6px; margin-top: 4px;">
                    <div style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--ui-black);"></div>
                    <p style="font-size: 12px; font-weight: 600; color: var(--ui-muted); margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">Identitas Digital</p>
                </div>
            </div>
        </div>

        <div class="workspace-content">
            <div style="width: 100%; padding: 24px 20px; display: flex; flex-direction: column; align-items: center;">
    
                @php
                    if (empty($siswa->token_validasi)) {
                        do {
                            $newToken = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8));
                        } while (\App\Models\Siswa::where('token_validasi', $newToken)->exists());
                        
                        \Illuminate\Support\Facades\DB::table('siswa')
                            ->where('id', $siswa->id)
                            ->update(['token_validasi' => $newToken]);
                            
                        $siswa->token_validasi = $newToken;
                    }
                    
                    $qrRawData = url('/v/' . $siswa->token_validasi);
                    $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrRawData);
                    
                    $tanggalLahir = $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d-m-Y') : '-';
                    $namaSekolah = $pengaturan->nama_sekolah ?? 'SMAN 1 MALINGPING';
                    $namaKepsek = $pengaturan->nama_kepala_sekolah ?? 'NAMA KEPALA SEKOLAH';
                    $nipKepsek = $pengaturan->nip_kepala_sekolah ?? '-';
                @endphp

                <div id="loading-preview" class="animate-pulse-card" style="width: 100%; max-width: 324px; aspect-ratio: 324/514; background-color: #E4E4E7; border-radius: 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; margin-bottom: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05);">
                    <svg style="width: 32px; height: 32px; color: var(--ui-muted); animation: spin 1s linear infinite;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span style="font-size: 11px; font-weight: 800; color: var(--ui-muted); margin-top: 12px; font-family: 'DM Sans', sans-serif; text-transform: uppercase; letter-spacing: 1px;">Merender Kartu...</span>
                </div>
                
                <img id="card-preview-image" src="" alt="Preview Kartu" style="display: none; width: 100%; max-width: 324px; height: auto; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.15); margin-bottom: 24px; border: 1px solid rgba(0,0,0,0.05);">

                <button id="download-btn" class="touch-scale" style="width: 100%; max-width: 324px; background: var(--ui-black); color: white; border-radius: 100px; padding: 16px; font-weight: 800; font-size: 13px; border: none; box-shadow: 0 4px 20px rgba(24,24,27,0.25); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: opacity 0.3s; opacity: 0.5; font-family: 'DM Sans', sans-serif; text-transform: uppercase; letter-spacing: 0.5px;" disabled>
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"></path></svg>
                    Simpan Ke Galeri
                </button>

            </div>

            <div id="print-card-wrapper">
                <div id="canvas-target" style="width: 324px; height: 514px; background-color: #FFFFFF; border-radius: 20px; overflow: hidden; position: relative; font-family: Arial, Helvetica, sans-serif;">
                    
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(145deg, #18181B 0%, #27272A 100%); z-index: 1;"></div>
                    
                    <div style="position: absolute; top: 150px; right: -50px; width: 280px; height: 280px; opacity: 0.03; z-index: 2; pointer-events: none;">
                        @if($pengaturan && $pengaturan->logo_sekolah)
                            <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" crossorigin="anonymous" style="width: 100%; height: 100%; object-fit: contain; filter: grayscale(100%) contrast(200%);">
                        @endif
                    </div>

                    <div style="position: absolute; top: -50px; right: -30px; width: 150px; height: 150px; border-radius: 50%; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%); z-index: 3;"></div>

                    <div style="position: absolute; top: 0; left: 0; width: 100%; padding: 24px 20px; display: flex; align-items: center; z-index: 10;">
                        <div style="width: 42px; height: 42px; background: rgba(255,255,255,0.1); border-radius: 12px; padding: 6px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                            @if($pengaturan && $pengaturan->logo_sekolah)
                                <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" crossorigin="anonymous" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                            @endif
                        </div>
                        <div style="margin-left: 14px; color: white;">
                            <div style="font-size: 10px; font-weight: bold; letter-spacing: 2px; color: rgba(255,255,255,0.6); margin-bottom: 2px;">IDENTITAS SISWA</div>
                            <div style="font-size: 13px; font-weight: 900; line-height: 1.2; text-transform: uppercase;">{{ $namaSekolah }}</div>
                        </div>
                    </div>

                    <div style="position: absolute; top: 90px; left: 24px; display: flex; align-items: center; gap: 14px; z-index: 10;">
                        <div style="width: 72px; height: 72px; border-radius: 16px; background-color: #3F3F46; border: 2px solid rgba(255,255,255,0.2); overflow: hidden; display: flex; justify-content: center; align-items: center; flex-shrink: 0;">
                            @if($siswa->foto)
                                <img src="{{ url('/uploads/' . $siswa->foto) }}" crossorigin="anonymous" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="font-size: 10px; font-weight: bold; color: rgba(255,255,255,0.5);">NO FOTO</div>
                            @endif
                        </div>
                        <div style="color: white; flex: 1;">
                            <div style="font-size: 16px; font-weight: 900; line-height: 1.2; margin-bottom: 4px;">{{ ucwords(strtolower($siswa->nama_lengkap)) }}</div>
                            <div style="font-size: 10px; font-weight: 600; letter-spacing: 0.5px; color: rgba(255,255,255,0.8);">
                                {{ $siswa->nis ?? '-' }} / {{ $siswa->nisn ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div style="position: absolute; top: 190px; left: 24px; right: 24px; z-index: 10; display: flex; flex-direction: column; gap: 12px;">
                        
                        <div>
                            <div style="font-size: 9px; font-weight: bold; color: rgba(255,255,255,0.5); text-transform: uppercase; margin-bottom: 2px;">Tempat, Tgl Lahir</div>
                            <div style="font-size: 12px; font-weight: bold; color: #FFFFFF;">{{ $siswa->tempat_lahir ?? '-' }}, {{ $tanggalLahir }}</div>
                        </div>
                        
                        <div style="display: flex; gap: 12px;">
                            <div style="flex: 1;">
                                <div style="font-size: 9px; font-weight: bold; color: rgba(255,255,255,0.5); text-transform: uppercase; margin-bottom: 2px;">Kelas</div>
                                <div style="font-size: 12px; font-weight: bold; color: #FFFFFF;">{{ $siswa->kelas->nama_kelas ?? '-' }}</div>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-size: 9px; font-weight: bold; color: rgba(255,255,255,0.5); text-transform: uppercase; margin-bottom: 2px;">Jenis Kelamin</div>
                                <div style="font-size: 12px; font-weight: bold; color: #FFFFFF;">{{ $siswa->jenis_kelamin === 'Laki-laki' ? 'Laki-laki' : ($siswa->jenis_kelamin === 'Perempuan' ? 'Perempuan' : '-') }}</div>
                            </div>
                        </div>

                        <div>
                            <div style="font-size: 9px; font-weight: bold; color: rgba(255,255,255,0.5); text-transform: uppercase; margin-bottom: 2px;">Alamat Lengkap</div>
                            <div style="font-size: 11px; font-weight: bold; color: rgba(255,255,255,0.9); line-height: 1.4;">
                                {{ $siswa->alamat ?? '-' }}<br>
                                RT {{ $siswa->rt ?? '-' }}/RW {{ $siswa->rw ?? '-' }}, {{ $siswa->kecamatan ?? '-' }}
                            </div>
                        </div>

                    </div>

                    <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 110px; background-color: #FFFFFF; display: flex; justify-content: space-between; align-items: center; padding: 0 24px; z-index: 10;">
                        
                        <div style="width: 76px; height: 76px; background: white; padding: 4px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                            <img src="{{ $qrCodeUrl }}" crossorigin="anonymous" alt="QR Validasi" style="width: 100%; height: 100%;">
                        </div>
                        
                        <div style="text-align: right; display: flex; flex-direction: column; justify-content: flex-end; height: 100%; padding-bottom: 16px;">
                            <div style="font-size: 9px; font-weight: bold; color: #71717A; margin-bottom: 24px;">Kepala Sekolah</div>
                            <div>
                                <div style="font-size: 11px; font-weight: 900; color: #18181B; text-transform: uppercase; border-bottom: 1px solid #18181B; padding-bottom: 2px; display: inline-block;">{{ $namaKepsek }}</div>
                            </div>
                            <div style="font-size: 9px; font-weight: bold; color: #71717A; margin-top: 4px;">NIP. {{ $nipKepsek }}</div>
                        </div>

                    </div>

                </div>
            </div>
            
        </div>
    </div>

    <script>
        let generatedCardDataUrl = null;

        window.onload = function() {
            const cardTarget = document.getElementById('canvas-target');
            
            setTimeout(() => {
                html2canvas(cardTarget, { 
                    scale: 3, 
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: null
                }).then(canvas => {
                    generatedCardDataUrl = canvas.toDataURL('image/png', 1.0);
                    
                    const imgPreview = document.getElementById('card-preview-image');
                    imgPreview.src = generatedCardDataUrl;
                    
                    document.getElementById('loading-preview').style.display = 'none';
                    imgPreview.style.display = 'block';

                    const downloadBtn = document.getElementById('download-btn');
                    downloadBtn.disabled = false;
                    downloadBtn.style.opacity = '1';
                    
                    downloadBtn.onclick = function() {
                        let link = document.createElement('a');
                        link.download = 'Kartu_Pelajar_{{ $siswa->nisn ?? $siswa->nis }}.png';
                        link.href = generatedCardDataUrl;
                        link.click();
                    };
                }).catch(err => {
                    alert('Gagal merender kartu. Pastikan koneksi internet stabil.');
                    document.getElementById('loading-preview').innerHTML = '<span style="color:red; font-size:12px; font-weight:bold; font-family:sans-serif;">GAGAL MEMUAT GAMBAR</span>';
                    console.error(err);
                });
            }, 1000);
        };
    </script>
</x-filament-panels::page.simple>