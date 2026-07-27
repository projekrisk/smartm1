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
            .dark .theme-bg { background-color: #0f172a; }
            .android-content { flex: 1; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; -ms-overflow-style: none; display: flex; flex-direction: column; }
            .android-content::-webkit-scrollbar { display: none; }
            * { box-sizing: border-box; }
            
            /* Sembunyikan KARTU HTML MURNI DI LUAR LAYAR */
            #print-card-wrapper {
                position: absolute;
                top: -9999px;
                left: -9999px;
                z-index: -100;
                opacity: 0;
                pointer-events: none;
            }

            /* Animasi Loading Berkedip */
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

    <div class="android-app-container theme-bg">
        
        <!-- HEADER APLIKASI -->
        <div style="flex-shrink: 0; background: linear-gradient(135deg, #2563eb, #3730a3); padding: 40px 24px 60px 24px; color: white; position: relative; z-index: 10; font-family: 'Inter', sans-serif;">
            <a href="/siswa" style="position: absolute; top: 32px; left: 20px; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.9)'" onmouseup="this.style.transform='scale(1)'">
                <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <div style="text-align: center; margin-top: 4px;">
                <p style="font-size: 10px; font-weight: 800; letter-spacing: 1px; color: #bfdbfe; text-transform: uppercase; margin-bottom: 8px;">Identitas Digital</p>
                <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0; line-height: 1.2; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Kartu Pelajar</h1>
            </div>
        </div>

        <div class="android-content theme-bg" style="border-top-left-radius: 2.5rem; border-top-right-radius: 2.5rem; margin-top: -30px; padding: 32px 20px 40px 20px; position: relative; z-index: 20; box-shadow: 0 -10px 25px rgba(0,0,0,0.1); display: flex; flex-direction: column; align-items: center;">
    

            @php
                // Barcode data murni: NISN/NIS, NAMA, KELAS
                $qrRawData = ($siswa->nisn ?? $siswa->nis) . ", " . $siswa->nama_lengkap . ", " . ($siswa->kelas->nama_kelas ?? '-');
                $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrRawData);
                $tanggalLahir = $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d-m-Y') : '-';
                $namaSekolah = $pengaturan->nama_sekolah ?? 'SMAN 1 MALINGPING';
                $namaKepsek = $pengaturan->nama_kepala_sekolah ?? 'NAMA KEPALA SEKOLAH';
                $nipKepsek = $pengaturan->nip_kepala_sekolah ?? '-';
            @endphp


            <!-- ========================================================================= -->
            <!-- 1. TAMPILAN PREVIEW GAMBAR (RESPONSIF DI LAYAR HP) -->
            <!-- ========================================================================= -->
            
            <!-- Kotak Loading (Ditampilkan saat gambar sedang di-generate) -->
            <div id="loading-preview" class="animate-pulse-card" style="width: 100%; max-width: 324px; aspect-ratio: 324/514; background-color: #cbd5e1; border-radius: 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; margin-bottom: 24px; box-shadow: 0 15px 35px rgba(0,0,0,0.05);">
                <svg style="width: 32px; height: 32px; color: #94a3b8; animation: spin 1s linear infinite;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span style="font-size: 11px; font-weight: 800; color: #64748b; margin-top: 12px; font-family: 'Inter', sans-serif;">MEMUAT KARTU...</span>
            </div>
            
            <!-- Gambar Asli (Disembunyikan dulu, muncul setelah digenerate js) -->
            <!-- Gambar bersifat max-width: 100% sehingga 100% aman dan responsif di layar HP sekecil apapun -->
            <img id="card-preview-image" src="" alt="Preview Kartu" style="display: none; width: 100%; max-width: 324px; height: auto; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.15); margin-bottom: 24px;">

            <!-- TOMBOL DOWNLOAD (Di-disable sampai gambar selesai di-generate) -->
            <button id="download-btn" style="width: 100%; max-width: 324px; background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 16px; padding: 16px; font-weight: 900; font-size: 14px; border: none; box-shadow: 0 8px 25px rgba(16,185,129,0.3); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: transform 0.1s, opacity 0.3s; opacity: 0.5; font-family: 'Inter', sans-serif;" disabled>
                <x-filament::icon icon="heroicon-s-arrow-down-tray" style="width: 20px; height: 20px;" />
                SIMPAN KARTU (JPG)
            </button>

            <!-- ========================================================================= -->
            <!-- 2. KARTU HTML MURNI (DISEMBUNYIKAN DI LUAR LAYAR, HANYA UNTUK HTML2CANVAS) -->
            <!-- ========================================================================= -->
            <div id="print-card-wrapper">
                <div id="canvas-target" style="width: 324px; height: 514px; background-color: #ffffff; border-radius: 20px; overflow: hidden; position: relative; border: 1px solid #cbd5e1; font-family: Arial, Helvetica, sans-serif;">
                    
                    <!-- Watermark -->
                    <div style="position: absolute; top: 160px; left: 52px; width: 220px; height: 220px; opacity: 0.04; z-index: 0;">
                        @if($pengaturan && $pengaturan->logo_sekolah)
                            <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" crossorigin="anonymous" style="width: 100%; height: 100%; object-fit: contain; filter: grayscale(100%);">
                        @endif
                    </div>

                    <!-- Header -->
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 120px; background: linear-gradient(135deg, #2563eb, #3730a3); border-bottom-left-radius: 30px; border-bottom-right-radius: 30px; z-index: 10;">
                        <div style="position: absolute; top: 16px; left: 20px; width: 46px; height: 46px; background: white; border-radius: 50%; padding: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                            @if($pengaturan && $pengaturan->logo_sekolah)
                                <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" crossorigin="anonymous" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                            @else
                                <div style="width:100%; height:100%; background-color:#2563eb; border-radius:50%;"></div>
                            @endif
                        </div>
                        <div style="position: absolute; top: 10px; left: 76px; right: 20px; text-align: left; color: white;">
                            <div style="font-size: 14px; font-weight: bold; letter-spacing: 2px; margin-bottom: 2px;">KARTU PELAJAR</div>
                            <div style="font-size: 16px; font-weight: bold; line-height: 1.2; text-transform: uppercase;">{{ $namaSekolah }}</div>
                        </div>
                    </div>

                    <!-- Foto -->
                    <div style="position: absolute; top: 75px; left: 117px; width: 90px; height: 90px; border-radius: 50%; border: 4px solid #ffffff; background-color: #f1f5f9; z-index: 20; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                        @if($siswa->foto)
                            <img src="{{ url('/uploads/' . $siswa->foto) }}" crossorigin="anonymous" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="font-size: 12px; font-weight: bold; color: #94a3b8;">FOTO</div>
                        @endif
                    </div>

                    <!-- Nama & Kelas -->
                   <div style="position: absolute; top: 160px; left: 0; width: 100%; text-align: center; z-index: 10;">
                        <div style="font-size: 16px; font-weight: 900; color: #0f172a; text-transform: uppercase; line-height: 1.1; padding: 0 10px; margin-bottom: 10px;">{{ $siswa->nama_lengkap }}</div>
                            
                        <!-- LENCANA KELAS ANTI-GAGAL: Menggunakan Fixed Height & Line-Height murni tanpa padding vertikal -->
                        <div style="width: 100%; text-align: center; margin-top: 6px;">
                            <span style="display: inline-block; height: 24px; line-height: 10px; padding: 0 14px; font-size: 11px; font-weight: 800; color: #2563eb; background-color: #eff6ff; border-radius: 12px; border: 1px solid #bfdbfe; vertical-align: middle;">
                                KELAS {{ $siswa->kelas->nama_kelas ?? '-' }}
                            </span>
                        </div>
                    </div>


                    <!-- Biodata -->
                    <div style="position: absolute; top: 245px; left: 24px; right: 24px; z-index: 10;">
                        <div style="margin-bottom: 8px;">
                            <div style="font-size: 9px; font-weight: bold; color: #64748b; text-transform: uppercase;">Tempat, Tgl Lahir</div>
                            <div style="font-size: 12px; font-weight: bold; color: #000000; margin-top: 1px;">{{ $siswa->tempat_lahir ?? '-' }}, {{ $tanggalLahir }}</div>
                        </div>
                        <div style="display: flex; gap: 16px; margin-bottom: 8px;">
                            <div style="flex: 1;">
                                <div style="font-size: 9px; font-weight: bold; color: #64748b; text-transform: uppercase;">NIS</div>
                                <div style="font-size: 12px; font-weight: bold; color: #000000; margin-top: 1px;">{{ $siswa->nis }}</div>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-size: 9px; font-weight: bold; color: #64748b; text-transform: uppercase;">NISN</div>
                                <div style="font-size: 12px; font-weight: bold; color: #000000; margin-top: 1px;">{{ $siswa->nisn ?? '-' }}</div>
                            </div>
                        </div>
                        <div>
                            <div style="font-size: 9px; font-weight: bold; color: #64748b; text-transform: uppercase;">Alamat Lengkap</div>
                            <div style="font-size: 11px; font-weight: bold; color: #000000; line-height: 1.3; margin-top: 1px;">{{ $siswa->alamat ?? '-' }} RT {{ $siswa->rt ?? '-' }}/RW {{ $siswa->rw ?? '-' }}, {{ $siswa->kecamatan ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 110px; padding: 14px 24px; display: flex; justify-content: space-between; align-items: flex-end; z-index: 10; background-color: #ffffff;">
                        <div style="width: 95px; background: white; padding: 3px; border: 1px solid #cbd5e1; border-radius: 6px; flex-shrink: 0;">
                            <img src="{{ $qrCodeUrl }}" crossorigin="anonymous" alt="QR" style="width: 100%; height: 100%;">
                        </div>
                        <div style="text-align: center; padding-bottom: 2px;">
                            <div style="font-size: 9px; font-weight: bold; color: #475569; margin-bottom: 26px;">Mengetahui,<br>Kepala Sekolah</div>
                            <div style="font-size: 11px; font-weight: bold; color: #000000; border-b: 1px solid #000000; padding-bottom: 2px; display: inline-block; text-transform: uppercase;">{{ $namaKepsek }}</div>
                            <div style="font-size: 9px; font-weight: bold; color: #475569; margin-top: 3px;">NIP. {{ $nipKepsek }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Script Pengaturan Otomatis (Load HTML to Image) -->
    <script>
        let generatedCardDataUrl = null;

        // Berjalan otomatis segera setelah font dan gambar di halaman selesai dimuat
        window.onload = function() {
            const cardTarget = document.getElementById('canvas-target');
            
            // Render kartu yang disembunyikan menggunakan resolusi tinggi
            html2canvas(cardTarget, { 
                scale: 3, 
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#ffffff' 
            }).then(canvas => {
                // Konversi canvas menjadi Base64 JPG
                generatedCardDataUrl = canvas.toDataURL('image/jpeg', 1.0);
                
                // Masukkan data gambar ke img preview
                const imgPreview = document.getElementById('card-preview-image');
                imgPreview.src = generatedCardDataUrl;
                
                // Matikan animasi loading, tampilkan gambar
                document.getElementById('loading-preview').style.display = 'none';
                imgPreview.style.display = 'block';

                // Nyalakan tombol download
                const downloadBtn = document.getElementById('download-btn');
                downloadBtn.disabled = false;
                downloadBtn.style.opacity = '1';
                
                // Aktifkan klik tombol download
                downloadBtn.onclick = function() {
                    let link = document.createElement('a');
                    link.download = 'Kartu_Pelajar_{{ $siswa->nisn ?? $siswa->nis }}.jpg';
                    link.href = generatedCardDataUrl;
                    link.click();
                };
            }).catch(err => {
                alert('Gagal merender kartu. Pastikan koneksi internet stabil.');
                document.getElementById('loading-preview').innerHTML = '<span style="color:red; font-size:12px;">Gagal Memuat</span>';
                console.error(err);
            });
        };
    </script>
</x-filament-panels::page.simple>