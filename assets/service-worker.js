const CACHE_NAME = 'my-cache-v2';
const urlsToCache = [
   '/',
   './service-worker.js',
   './manifest.json',
   './assets/css/style.css',
   './assets/js/helper.css',
];

self.addEventListener('install', event => {
   event.waitUntil(
      caches.open(CACHE_NAME)
         .then(cache => {
            console.log('Cache opened');
            return cache.addAll(urlsToCache);
         })
   );
});
// Escucha los eventos fetch
self.addEventListener('fetch', event => {
   event.respondWith(
     self.caches.match(event.request.url)
       .then(response => response || fetch(event.request.url))
   );
 });