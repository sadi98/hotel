const staticCacheName = "pre-cache-v2.6.4";
const dynamicCacheName = "runtime-cache-v2.6.4";

// Asset yang akan disimpan ke cache
const precacheAssets = [
    "/",
    "/users/css/bootstrap.min.css",

    "/users/img/core-img/curve.png",
    "/users/img/core-img/curve2.png",
    "/users/img/core-img/dot-blue.png",
    "/users/img/core-img/dot.png",
    "/users/img/core-img/logo-small.png",
    "/users/img/core-img/logo-white.png",
    "/users/img/bg-img/no-internet.png",

    "/users/js/jquery.min.js",
    "/users/js/pwa.js",
    "/users/js/dark-mode-switch.js",
    "/users/js/active.js",

    "/users/manifest.json",
    "/users/style.css",
];

// INSTALL
self.addEventListener("install", function (event) {
    event.waitUntil(
        caches.open(staticCacheName).then(function (cache) {
            return cache.addAll(precacheAssets);
        }),
    );

    self.skipWaiting();
});

// ACTIVATE
self.addEventListener("activate", function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(
                keys
                    .filter(function (key) {
                        return (
                            key !== staticCacheName && key !== dynamicCacheName
                        );
                    })
                    .map(function (key) {
                        return caches.delete(key);
                    }),
            );
        }),
    );

    self.clients.claim();
});

// FETCH
self.addEventListener("fetch", function (event) {
    // Cache hanya untuk request GET
    if (event.request.method !== "GET") {
        return;
    }

    event.respondWith(
        caches.match(event.request).then(function (cachedResponse) {
            // Kalau ada di cache, gunakan cache
            if (cachedResponse) {
                return cachedResponse;
            }

            // Kalau tidak ada, ambil dari server
            return fetch(event.request).then(function (response) {
                // Jangan cache response gagal
                if (!response || response.status !== 200) {
                    return response;
                }

                const responseClone = response.clone();

                caches.open(dynamicCacheName).then(function (cache) {
                    cache.put(event.request, responseClone);
                });

                return response;
            });
        }),
    );
});
