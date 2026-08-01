<div id="pwa-install-popup" style="display: none; position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%); width: calc(100% - 32px); max-width: 400px; z-index: 9999; background-color: white; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); padding: 16px; border: 1px solid #f1f5f9; align-items: center; justify-content: space-between; gap: 12px; font-family: 'Inter', system-ui, sans-serif;" class="dark:bg-slate-900 dark:border-slate-800">
    
    <div style="display: flex; align-items: center; gap: 12px; flex: 1; min-width: 0;">
        <div style="width: 46px; height: 46px; border-radius: 12px; background: linear-gradient(135deg, #2563eb, #3730a3); color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 10px rgba(37,99,235,0.3);">
            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
        </div>
        
        <div style="flex: 1; min-width: 0;">
            <h4 style="margin: 0 0 2px 0; font-size: 14px; font-weight: 800; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" class="dark:text-white">Install Smart-M1</h4>
            <p style="margin: 0; font-size: 11px; font-weight: 500; color: #64748b; line-height: 1.3;" class="dark:text-slate-400">Akses lebih cepat & hemat kuota.</p>
        </div>
    </div>

    <div style="display: flex; gap: 8px; flex-shrink: 0;">
        <button id="pwa-close-btn" style="padding: 8px 12px; border: none; background-color: #f1f5f9; color: #64748b; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer;" class="dark:bg-slate-800 dark:text-slate-300">
            Nanti
        </button>
        <button id="pwa-install-btn" style="padding: 8px 16px; border: none; background-color: #2563eb; color: white; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 10px rgba(37,99,235,0.3);">
            Install
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let deferredPrompt;
        const pwaPopup = document.getElementById('pwa-install-popup');
        const installBtn = document.getElementById('pwa-install-btn');
        const closeBtn = document.getElementById('pwa-close-btn');

        const isDismissed = localStorage.getItem('pwa_prompt_dismissed');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            if (!isDismissed) {
                pwaPopup.style.display = 'flex';
            }
        });

        installBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                pwaPopup.style.display = 'none';
                deferredPrompt.prompt();
                
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    console.log('Siswa menginstal PWA');
                }
                deferredPrompt = null;
            }
        });

        closeBtn.addEventListener('click', () => {
            pwaPopup.style.display = 'none';
            localStorage.setItem('pwa_prompt_dismissed', 'true');
        });

        window.addEventListener('appinstalled', () => {
            pwaPopup.style.display = 'none';
            deferredPrompt = null;
        });
    });
</script>