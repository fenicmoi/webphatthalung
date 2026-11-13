/**
 * Phatthalung PWA Service Worker (2026+)
 * รองรับการทำงานแบบ Offline Caching และ Fast Response สำหรับแอป Android
 */

const CACHE_NAME = 'phatthalung-pwa-v1';

// รายการไฟล์ App Shell ที่ต้องบันทึกลงแคชเบื้องต้น
const PRECACHE_ASSETS = [
    './manifest.json',
    './assets/images/pwa/icon-192.png',
    './assets/images/pwa/icon-512.png',
    './assets/images/pwa/icon-maskable.png',
    './favicon.png'
];

// Event: Install
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_ASSETS).catch((err) => {
                console.warn('Precache warning:', err);
            });
        }).then(() => self.skipWaiting())
    );
});

// Event: Activate
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((name) => {
                    if (name !== CACHE_NAME) {
                        return caches.delete(name);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Event: Fetch Strategy
self.addEventListener('fetch', (event) => {
    const request = event.request;

    // ข้าม non-GET requests หรือคำสั่ง POST/API
    if (request.method !== 'GET') {
        return;
    }

    // ตรวจสอบ URL
    const url = new URL(request.url);

    // กรณีเป็น Assets (CSS, JS, Fonts, Images) -> Stale While Revalidate
    if (
        request.destination === 'style' ||
        request.destination === 'script' ||
        request.destination === 'image' ||
        request.destination === 'font'
    ) {
        event.respondWith(
            caches.open(CACHE_NAME).then((cache) => {
                return cache.match(request).then((cachedResponse) => {
                    const fetchPromise = fetch(request).then((networkResponse) => {
                        if (networkResponse && networkResponse.status === 200) {
                            cache.put(request, networkResponse.clone());
                        }
                        return networkResponse;
                    }).catch(() => cachedResponse);

                    return cachedResponse || fetchPromise;
                });
            })
        );
        return;
    }

    // กรณีเป็นการเปิดหน้า HTML (เช่น /directory, /phone, /) -> Network First with Cache Fallback
    if (request.mode === 'navigate' || request.destination === 'document') {
        event.respondWith(
            fetch(request)
                .then((networkResponse) => {
                    if (networkResponse && networkResponse.status === 200) {
                        const copy = networkResponse.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                    }
                    return networkResponse;
                })
                .catch(async () => {
                    const cachedResponse = await caches.match(request);
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    // Fallback to directory cached page if available
                    const dirFallback = await caches.match('./directory');
                    if (dirFallback) return dirFallback;
                    return new Response(
                        `<!DOCTYPE html><html lang="th"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Offline - พัทลุงโฟน</title><style>body{font-family:sans-serif;padding:2rem;text-align:center;color:#064e3b;background:#f0fdf4}h1{font-size:1.6rem}button{padding:0.75rem 1.5rem;background:#059669;color:#fff;border:none;border-radius:2rem;cursor:pointer}</style></head><body><h1>📱 กำลังออฟไลน์ (Offline Mode)</h1><p>อุปกรณ์ของคุณไม่ได้เชื่อมต่ออินเทอร์เน็ต แต่สามารถเปิดดูข้อมูลสมุดโทรศัพท์ที่เคยเปิดไว้ได้ครับ</p><button onclick="location.reload()">ลองใหม่อีกครั้ง</button></body></html>`,
                        { headers: { 'Content-Type': 'text/html; charset=utf-8' } }
                    );
                })
        );
        return;
    }
});
