<x-filament-panels::page>
    @php
        $pengaturan = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) {
                $pengaturan = \App\Models\Pengaturan::first();
            }
        } catch (\Exception $e) {}
    @endphp

    <div wire:ignore>
        <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;0,9..40,900&display=swap" rel="stylesheet">
        
        <script>
            // Memaksa warna status bar di mobile agar senada dengan background aplikasi
            const metaThemeColor = document.createElement('meta');
            metaThemeColor.name = 'theme-color';
            metaThemeColor.content = '#F5F5F7';
            document.head.appendChild(metaThemeColor);

            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['"DM Sans"', 'sans-serif'],
                        },
                        colors: {
                            ui: {
                                bg: '#F5F5F7',
                                surface: '#FFFFFF',
                                black: '#18181B',
                                text: '#27272A',
                                muted: '#71717A',
                                border: '#E4E4E7',
                            }
                        }
                    }
                }
            }
        </script>
        
        <style>
            body { 
                font-family: 'DM Sans', sans-serif !important; 
                background-color: #F5F5F7 !important; 
                color: #27272A !important; 
                margin: 0; padding: 0;
                overflow: hidden !important; 
                -webkit-font-smoothing: antialiased;
            }

            .fi-topbar, .fi-sidebar, .fi-simple-header, .fi-logo, .fi-simple-footer { display: none !important; }
            .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-page { 
                padding: 0 !important; margin: 0 !important; max-width: 100% !important; 
                background-color: transparent !important; box-shadow: none !important; border: none !important;
            }

            section.grid.auto-cols-fr.gap-y-6, .fi-simple-main-ctn, .fi-main-ctn { 
                gap: 0 !important; 
                padding: 0 !important; 
                margin: 0 !important; 
            }

            /* Workspace Mobile Container (Like Dashboard) */
            .workspace-container {
                width: 100%; max-width: 414px; margin: 0 auto;
                position: fixed; top: 0; bottom: 0; left: 0; right: 0;
                display: flex; flex-direction: column;
                background-color: #F5F5F7;
                overflow-y: auto; overflow-x: hidden;
            }
            @media (min-width: 640px) {
                .workspace-container {
                    left: 50%; right: auto; transform: translateX(-50%);
                    border-left: 1px solid #E4E4E7; border-right: 1px solid #E4E4E7;
                    box-shadow: 0 0 50px rgba(0,0,0,0.05);
                }
            }

            /* Customizing Filament Form Fields to Match Theme */
            #security-overlay .fi-fo-field-wrp label span { color: #18181B !important; font-weight: 700 !important; font-size: 13px !important; }
            #security-overlay .fi-fo-field-wrp p { color: #71717A !important; font-size: 11px !important; font-weight: 500 !important; margin-top: 4px !important;}
            #security-overlay .fi-fo-field-wrp-error-message { color: #DC2626 !important; font-size: 12px !important; font-weight: 600 !important; }
            
            #security-overlay .fi-input-wrp {
                background-color: #FFFFFF !important;
                border: 1px solid #E4E4E7 !important; 
                border-radius: 12px !important; 
                box-shadow: 0 2px 10px rgba(0,0,0,0.02) !important;
                transition: all 0.2s ease-out !important;
                position: relative;
                overflow: hidden;
            }
            
            #security-overlay .fi-input-wrp:focus-within {
                border-color: #18181B !important;
                box-shadow: 0 4px 12px rgba(24,24,27,0.08) !important; 
            }
            
            #security-overlay .fi-input { 
                color: #18181B !important; 
                padding: 14px 16px !important; 
                padding-right: 45px !important; /* Eye icon space */
                background: transparent !important;
                font-size: 14px !important;
                font-weight: 600 !important;
            }
        </style>
    </div>

    <div id="security-overlay" class="fixed inset-0 z-[99999] min-h-screen w-full flex flex-col selection:bg-zinc-900 selection:text-white bg-[#F5F5F7]">
        
        <div class="workspace-container">
            
            <div class="p-5 sm:p-8 flex flex-col justify-center min-h-full py-12">
                
                <div class="bg-white rounded-3xl p-6 sm:p-8 relative shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-gray-100">
                    
                    <div class="flex flex-col items-center text-center mb-8 pb-6 border-b border-gray-100">
                        <div class="w-16 h-16 rounded-2xl bg-zinc-900 flex items-center justify-center text-white mb-4 shadow-[0_8px_20px_rgba(24,24,27,0.2)]">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        
                        <h1 class="text-xl sm:text-2xl font-black text-zinc-900 tracking-tight leading-[1.2]">
                            Keamanan Akun
                        </h1>
                        <p class="text-xs sm:text-sm text-zinc-500 mt-2 font-medium leading-relaxed max-w-[280px]">
                            Sandi default terdeteksi. Demi keamanan data nilai Anda, wajib mengatur kata sandi rahasia yang baru.
                        </p>
                    </div>

                    <div>
                        <form wire:submit="simpan" class="space-y-5">
                            
                            {{ $this->form }}

                            <div class="pt-4">
                                <button type="submit" wire:loading.attr="disabled" class="w-full flex justify-center items-center py-4 px-6 rounded-2xl text-sm font-bold text-white bg-zinc-900 hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-900 transition-all duration-200 shadow-[0_4px_14px_rgba(24,24,27,0.25)] active:scale-[0.98]">
                                    
                                    <span wire:loading.remove wire:target="simpan" class="flex items-center gap-2">
                                        Simpan & Masuk
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </span>
                                    
                                    <span wire:loading wire:target="simpan" class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Memproses...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const attachEyeIcons = () => {
                // Mencari semua input yang tipenya password atau pernah menjadi password
                const passwordInputs = document.querySelectorAll('input[type="password"], input[data-is-password="true"]');
                
                passwordInputs.forEach(input => {
                    // Cek jika sudah ada tombol mata, lewati
                    if (input.parentNode.querySelector('.toggle-password-btn')) return;
                    
                    // Tandai bahwa input ini adalah input password
                    input.setAttribute('data-is-password', 'true');
                    
                    const wrapper = input.parentElement;
                    wrapper.style.position = 'relative';
                    
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'toggle-password-btn absolute right-3 top-1/2 transform -translate-y-1/2 text-zinc-400 hover:text-zinc-900 focus:outline-none p-1 transition-colors';
                    
                    const iconShow = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`;
                    const iconHide = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>`;
                    
                    btn.innerHTML = input.type === 'password' ? iconShow : iconHide;
                    
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        if (input.type === 'password') {
                            input.type = 'text';
                            btn.innerHTML = iconHide;
                            btn.classList.add('text-zinc-900');
                        } else {
                            input.type = 'password';
                            btn.innerHTML = iconShow;
                            btn.classList.remove('text-zinc-900');
                        }
                    });
                    
                    wrapper.appendChild(btn);
                });
            };

            // Jalankan saat pertama kali dimuat
            attachEyeIcons();
            
            // MutationObserver untuk memastikan ikon mata tetap ada saat Livewire me-render ulang komponen form
            const observer = new MutationObserver((mutations) => {
                attachEyeIcons();
            });
            observer.observe(document.body, { childList: true, subtree: true });
        });
    </script>
</x-filament-panels::page>