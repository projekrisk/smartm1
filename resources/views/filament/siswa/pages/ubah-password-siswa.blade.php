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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['"Inter"', 'sans-serif'],
                        },
                        colors: {
                            base: {
                                50: '#F9FAFB',
                                900: '#111827',
                            }
                        }
                    }
                }
            }
        </script>
        
        <style>
            body { 
                font-family: 'Inter', sans-serif !important; 
                background-color: #F9FAFB !important; 
                color: #111827 !important; 
                margin: 0; padding: 0;
                overflow: hidden !important; 
            }
            
            .bg-structural {
                background-image: 
                    linear-gradient(to right, rgba(17, 24, 39, 0.04) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(17, 24, 39, 0.04) 1px, transparent 1px);
                background-size: 64px 64px;
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

            #security-overlay .fi-fo-field-wrp label span { color: #111827 !important; font-weight: 800 !important; font-size: 13px !important; text-transform: uppercase; letter-spacing: 0.05em; }
            #security-overlay .fi-fo-field-wrp p { color: #4B5563 !important; font-size: 12px !important; font-weight: 600 !important; line-height: 1.4 !important; margin-top: 4px !important;}
            #security-overlay .fi-fo-field-wrp-error-message { color: #DC2626 !important; font-size: 12px !important; font-weight: 700 !important; }
            
            #security-overlay .fi-input-wrp {
                background-color: #ffffff !important;
                border: 2px solid #111827 !important; 
                border-radius: 0px !important; 
                box-shadow: none !important;
                transition: all 0.2s ease-out !important;
                position: relative;
            }
            
            #security-overlay .fi-input-wrp:focus-within {
                box-shadow: 4px 4px 0px 0px #111827 !important; 
                transform: translate(-2px, -2px) !important;
            }
            
            #security-overlay .fi-input { 
                color: #111827 !important; 
                padding: 12px 14px !important; 
                padding-right: 45px !important;
                background: transparent !important;
                font-size: 15px !important;
                font-weight: 600 !important;
            }
        </style>
    </div>

    <div id="security-overlay" class="fixed inset-0 z-[99999] min-h-screen w-full flex flex-col items-center justify-center p-4 sm:p-8 bg-base-50 bg-structural selection:bg-base-900 selection:text-white">
        
        <div class="relative z-10 w-full max-w-[460px]">
            
            <div class="bg-white border-2 border-base-900 p-8 md:p-10 relative shadow-[8px_8px_0px_0px_#111827]">
                
                <div class="relative z-10">
                    
                    <div class="flex flex-col items-start mb-8 border-b-2 border-base-900 pb-6">
                        <div class="w-14 h-14 bg-base-900 flex items-center justify-center text-white mb-5 shadow-[4px_4px_0px_0px_#D97706]">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        
                        <h1 class="text-2xl sm:text-3xl font-black text-base-900 tracking-tight uppercase leading-[1.1]">
                            Keamanan <br/> Akun
                        </h1>
                        <p class="text-xs sm:text-sm text-gray-600 mt-3 font-semibold leading-relaxed">
                            Sandi default terdeteksi. Demi keamanan data nilai dan absensi Anda, wajib mengatur kata sandi rahasia yang baru.
                        </p>
                    </div>

                    <div>
                        <form wire:submit="simpan" class="space-y-6">
                            
                            {{ $this->form }}

                            <div class="pt-6">
                                <button type="submit" wire:loading.attr="disabled" class="w-full flex justify-center items-center py-4 px-6 border-2 border-base-900 text-sm font-black text-white bg-base-900 uppercase tracking-widest hover:bg-white hover:text-base-900 focus:outline-none transition-colors duration-200 group relative">
                                    
                                    <span wire:loading.remove wire:target="simpan" class="flex items-center gap-3">
                                        SIMPAN & MASUK
                                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </span>
                                    
                                    <span wire:loading wire:target="simpan" class="flex items-center gap-3">
                                        MEMPROSES...
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
                const passwordInputs = document.querySelectorAll('input[type="password"], input[data-is-password="true"]');
                
                passwordInputs.forEach(input => {
                    if (input.parentNode.querySelector('.toggle-password-btn')) return;
                    
                    input.setAttribute('data-is-password', 'true');
                    
                    const wrapper = input.parentElement;
                    wrapper.style.position = 'relative';
                    
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'toggle-password-btn absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-base-900 focus:outline-none p-1 transition-colors';
                    
                    const iconShow = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`;
                    const iconHide = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>`;
                    
                    btn.innerHTML = input.type === 'password' ? iconShow : iconHide;
                    
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        if (input.type === 'password') {
                            input.type = 'text';
                            btn.innerHTML = iconHide;
                            btn.classList.add('text-base-900');
                        } else {
                            input.type = 'password';
                            btn.innerHTML = iconShow;
                            btn.classList.remove('text-base-900');
                        }
                    });
                    
                    wrapper.appendChild(btn);
                });
            };

            attachEyeIcons();
            
            const observer = new MutationObserver((mutations) => {
                attachEyeIcons();
            });
            observer.observe(document.body, { childList: true, subtree: true });
        });
    </script>
</x-filament-panels::page>