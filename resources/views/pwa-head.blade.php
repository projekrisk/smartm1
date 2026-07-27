<!-- Konfigurasi Progressive Web App (PWA) -->
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#2563eb">
<link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js').then(function(registration) {
                console.log('PWA ServiceWorker Aktif');
            }, function(err) {
                console.log('PWA ServiceWorker Gagal: ', err);
            });
        });
    }
</script>