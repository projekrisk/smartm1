const CACHE_NAME = 'smart-m1-pwa-v1';
const urlsToCache = [
    '/',
    '/manifest.json'
];

// Instalasi Service Worker
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(urlsToCache);
            })
    );
});

// Mengambil request (Bypass cache agar data selalu realtime)
self.addEventListener('fetch', event => {
    event.respondWith(
        fetch(event.request).catch(function() {
            return caches.match(event.request);
        })
    );
});