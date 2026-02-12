'use strict';
const MANIFEST = 'flutter-app-manifest';
const TEMP = 'flutter-temp-cache';
const CACHE_NAME = 'flutter-app-cache';

const RESOURCES = {"flutter_bootstrap.js": "e0a4a3449cabe18d2f7be92dd02c46fb",
"version.json": "a3e482b349efbe3710be7db818147946",
"favicon.ico": "27ab0674367339cecd196744b93a2a7f",
"index.html": "5656d1fa8c99b162b99465506ba35674",
"/": "5656d1fa8c99b162b99465506ba35674",
"main.dart.js": "d209c26b1540770d9f49b3602ad0a782",
"flutter.js": "888483df48293866f9f41d3d9274a779",
"favicon.png": "5dcef449791fa27946b3d35ad8803796",
"icons/apple-touch-icon.png": "4354d5dee7d8b65d1424859600ef50f1",
"icons/icon-192.png": "541c1531f82015dcc814af1873de3342",
"icons/Icon-maskable-192.png": "c457ef57daa1d16f64b27b786ec2ea3c",
"icons/icon-192-maskable.png": "fdbaf5626c72c139002610140b84b779",
"icons/icon-512-maskable.png": "af4bf8dd2c8e4edb966ee862b4c16bd0",
"icons/Icon-maskable-512.png": "301a7604d45b3e739efc881eb04896ea",
"icons/icon-512.png": "7095b6ff3fe54ffa08115f934ab74428",
"manifest.json": "2c713916d616b0ff24d97bc82d82c1e5",
"favicons.jpeg": "b994681577319b98f8031aa35006e458",
"assets/AssetManifest.json": "3fe39300e99d0ac70669172ce4968c4d",
"assets/NOTICES": "def1260788e2d07b45d66e93918688c7",
"assets/FontManifest.json": "08f5b6592e6066f54e507e75cf53989b",
"assets/AssetManifest.bin.json": "38ba0af2da905935b2b9b0c8f06cbca9",
"assets/packages/cupertino_icons/assets/CupertinoIcons.ttf": "33b7d9392238c04c131b6ce224e13711",
"assets/shaders/ink_sparkle.frag": "ecc85a2e95f5e9f53123dcaf8cb9b6ce",
"assets/AssetManifest.bin": "42ce1aa08e281a8127c5a8be79cbfe0a",
"assets/fonts/MaterialIcons-Regular.otf": "e7069dfd19b331be16bed984668fe080",
"assets/assets/images/deposit.png": "1a5e55d74e007c88af4d7715eb901e78",
"assets/assets/images/add.png": "737ec290471f789e58b8e1e10cd45789",
"assets/assets/images/bold.png": "6fc7d229c896c002b2bb4010e6cdb7d8",
"assets/assets/images/left-arrow.png": "6d1d15a6fcb6c36927cd3114a2159cdb",
"assets/assets/images/transaction.png": "c2216a3a6167a9ab232c0258383dab3b",
"assets/assets/images/import.png": "4f7f8a481976e71c466ecd5ab213e70d",
"assets/assets/images/hidden.png": "39738cc3f3c6d5da18d4f9f1bec27ed5",
"assets/assets/images/user.png": "eeb7d332017343731ce45fdd17c46ccf",
"assets/assets/images/list.png": "0b721e533aedc38aa39530818148fc99",
"assets/assets/images/student.png": "1942266c80771a5ac6222b8d119d4b88",
"assets/assets/images/invoice.png": "da1ed83c55f47556f70498001804b4c4",
"assets/assets/images/ic_launcher.png": "fcf02800a94a8e12b4d9a8bc002d6fe2",
"assets/assets/images/eye.png": "bb8e6498d17a21face18972834122eed",
"assets/assets/images/graduation.png": "c36f7c113b14e6d8b1ba61aff8377e3e",
"assets/assets/images/delete.png": "94fe9f2bc1c059f27df2bbb19bb708e9",
"assets/assets/images/exit.png": "fb9c00ccdc42001911baf00d0a004f8f",
"assets/assets/images/pencil.png": "f8bfdeb0ddc29268be8877cad2c272e4",
"assets/assets/images/bill.png": "d128e4fa653cb262d061d0d53fec7df5",
"assets/assets/images/check-document.png": "ae1500b3e81e3beb5cd07948c3611900",
"assets/assets/images/setting.png": "f44035663044a853be570b1e75288f6b",
"assets/assets/animation/error_cat_animation.json": "7c1d698ef18c56931b9b9e28780e5be2",
"assets/assets/animation/66708-something-went-wrong.json": "46ef5ad803e022d36f0b070f60fe0fbe",
"assets/assets/icons/ic_clock.svg": "f52cbd8bb4b09d52fea1f18ffa818bff",
"assets/assets/icons/ic_share.svg": "257d5b6737ae9dd3f9d8a16b2ccca8ce",
"assets/assets/icons/ic_archiv.svg": "d4137caf791233164d4b08961392a08f",
"assets/assets/icons/ic_pay_pal.svg": "b4a91e0e553696afba9446ef647d5fff",
"assets/assets/icons/ic_insta.svg": "c9c4ed627a9ee6a0385ce26a3c265ca3",
"assets/assets/icons/ic_id.svg": "75e178ec0c086004725e1afd59e73b51",
"assets/assets/icons/ic_lock.svg": "979072d7e951d08484cb3005d1d58009",
"assets/assets/icons/ic_delete.svg": "99017f915f1b69e002e2c34e7691533b",
"assets/assets/icons/ic_info.svg": "e14341c0c6c9bfa1ab600c9b77a853a1",
"assets/assets/icons/ic_flipcart.svg": "fbd8d2dd1ca75141e54f1b7feade9904",
"assets/assets/icons/ic_box_edit.svg": "f3a9e2b90157a2ae8062a2012814777e",
"assets/assets/icons/ic_fill_email.svg": "78acd7583895e5e81147afb1b038d8be",
"assets/assets/icons/ic_right_arrow.svg": "eef74af77f020f1485fe3f1e985f274e",
"assets/assets/icons/ic_spotyfy.svg": "651d429dd459f36a807fe86b05c8ef6a",
"assets/assets/icons/ic_plus.svg": "5a13e365875d0fc1ee33c96f867cc1e6",
"assets/assets/icons/ic_flow_person.svg": "862b4b81c86dd929ba20fa22215593a4",
"assets/assets/icons/ic_round_add.svg": "807d5000b95949d3b7188727d4752fa0",
"assets/assets/icons/ic_reject_person.svg": "68231e2dfb9b5404c8cb2b237179a45c",
"assets/assets/icons/ic_right_arrow_person.svg": "1b65449329a96a033751571c47f52193",
"assets/assets/icons/ic_flag.svg": "1a90c89a1ac836d934681185d1d9b4b0",
"assets/assets/icons/cancel_round.png": "278d7f711b645786905fade1ffacf095",
"assets/assets/icons/ic_print.svg": "dacc1970f8a104942a60855f009915ac",
"assets/assets/icons/ic_fill_plus.svg": "37b04dfadb2e6399904481d2621fd263",
"assets/assets/icons/ic_verify.svg": "5be90e8e4fd94a69d6290d51ae6324b9",
"assets/assets/icons/ic_close.svg": "25c03253616a0e3f82adce38175b64b0",
"assets/assets/icons/ic_right.svg": "16d4a33f3f2ddf7ff9a1a1d94fefa17b",
"assets/assets/icons/ic_setting.svg": "728b85820868cb7ca03801c820b7a99e",
"assets/assets/icons/ic_image.svg": "ae2b7fd5b173d40d884b32c4351a19c7",
"assets/assets/icons/ic_message.svg": "1704202df6cc908c11edad38e842dc98",
"assets/assets/icons/ic_dots.svg": "88e991b66b5c93d168866cb0576f102f",
"assets/assets/icons/7651514.webp": "29554b02e28b59e0a10bdd4285263dcb",
"assets/assets/icons/ic_notification.svg": "4768078b94d2c64f0de338d6ce23c638",
"assets/assets/icons/ic_view_person.svg": "6c400879439aad8e2b621f0fb5bbd6df",
"assets/assets/icons/img_empty.png": "07930252df4e919412f84919b107edc1",
"assets/assets/icons/ic_row_dot.svg": "e81fa4aedb00ff9aead30465174b8a0f",
"assets/assets/icons/ic_left_arrow.svg": "852536f66a9429730939c1e0a10272a3",
"assets/assets/icons/ic_file.svg": "fff9c8603d0ef915fabfa1bb7d5464bc",
"assets/assets/icons/ic_delete_1.svg": "00b174bf94b03f1ed663a0f92a7b5432",
"assets/assets/icons/ic_CAZIPRO.svg": "ae1bdd10f5c6dde220216621fed453f2",
"assets/assets/icons/done_round.png": "096cff1e2601834b25f70f2b35f4c11a",
"assets/assets/icons/ic_right_full_arrow.svg": "d08dd05246d2694269676d10e7ac1a94",
"assets/assets/icons/img_login_bennar.png": "cc8c9b6794c6bf68f3678f536370e920",
"assets/assets/icons/ic_multi_person.svg": "e144857d9a5afef8d22d705461476d85",
"assets/assets/icons/ic_goverment_id.svg": "367f79ff90918908f57f03747fb6ca2b",
"assets/assets/icons/ic_passport.svg": "54c2d21bcfc556f9fe8aa888ea1ea342",
"assets/assets/icons/ic_aaple_logo.svg": "0f9ce5198c687cb9048b155b1f8b63ea",
"assets/assets/icons/ic_boarder_add.svg": "905a325878394bf99dd0bb144999824c",
"assets/assets/icons/ic_done.svg": "3dea05ea09bd7d7b12b72476987197cc",
"assets/assets/icons/ic_fill_save.svg": "263ab8b7f8024e924b60de641fc03c52",
"assets/assets/icons/ic_wallet.svg": "013d4c6615365c641c0ea6919dd5fcd1",
"assets/assets/icons/ic_email.svg": "aa5b947e365b98e52fc6c504117d119d",
"assets/assets/icons/ic_figma.svg": "a1b55bec18205530d25060ccfbfab76a",
"assets/assets/icons/ic_person.svg": "14ca50f0633e4745dd3e3f628375020a",
"assets/assets/icons/ic_calender.svg": "10d44c70ed775c1fc1748764c431c47e",
"assets/assets/icons/ic_google_logo.svg": "b78b2fc78477fd71304cb9e1b256687f",
"assets/assets/icons/ic_debit_arrow.svg": "4a21b9fbfa291dc8c24a7f7182f87020",
"assets/assets/icons/ic_save.svg": "6b7d4f5323ba8bda381a0913bd7a8279",
"assets/assets/icons/ic_camera_1.svg": "726d9531b2c34540f36730736d5eba0d",
"assets/assets/icons/ic_search.svg": "256ca9e126565b5d646fa61f562e2e7e",
"assets/assets/icons/ic_edit.svg": "8e030a06f2a0318dd566ad3507b54cd5",
"assets/assets/icons/ic_pdf.svg": "8f54b3623a1c3f5d1b27945ccabeb1f3",
"assets/assets/icons/images.png": "c90c9ed93501855a429cb9fbfedb7252",
"assets/assets/icons/ic_red_notification.svg": "06024ee6247abb66957fc05191eb3753",
"assets/assets/icons/ic_fill_multi_person.svg": "decaf8b9c56ad2aee9da25163636504f",
"assets/assets/fonts/Inter_28pt-Regular.ttf": "fc20e0880f7747bb39b85f2a0722b371",
"assets/assets/fonts/MaterialIcons-Regular.ttf": "4e85bc9ebe07e0340c9c4fc2f6c38908",
"canvaskit/skwasm.js": "1ef3ea3a0fec4569e5d531da25f34095",
"canvaskit/skwasm_heavy.js": "413f5b2b2d9345f37de148e2544f584f",
"canvaskit/skwasm.js.symbols": "0088242d10d7e7d6d2649d1fe1bda7c1",
"canvaskit/canvaskit.js.symbols": "58832fbed59e00d2190aa295c4d70360",
"canvaskit/skwasm_heavy.js.symbols": "3c01ec03b5de6d62c34e17014d1decd3",
"canvaskit/skwasm.wasm": "264db41426307cfc7fa44b95a7772109",
"canvaskit/chromium/canvaskit.js.symbols": "193deaca1a1424049326d4a91ad1d88d",
"canvaskit/chromium/canvaskit.js": "5e27aae346eee469027c80af0751d53d",
"canvaskit/chromium/canvaskit.wasm": "24c77e750a7fa6d474198905249ff506",
"canvaskit/canvaskit.js": "140ccb7d34d0a55065fbd422b843add6",
"canvaskit/canvaskit.wasm": "07b9f5853202304d3b0749d9306573cc",
"canvaskit/skwasm_heavy.wasm": "8034ad26ba2485dab2fd49bdd786837b"};
// The application shell files that are downloaded before a service worker can
// start.
const CORE = ["main.dart.js",
"index.html",
"flutter_bootstrap.js",
"assets/AssetManifest.bin.json",
"assets/FontManifest.json"];

// During install, the TEMP cache is populated with the application shell files.
self.addEventListener("install", (event) => {
  self.skipWaiting();
  return event.waitUntil(
    caches.open(TEMP).then((cache) => {
      return cache.addAll(
        CORE.map((value) => new Request(value, {'cache': 'reload'})));
    })
  );
});
// During activate, the cache is populated with the temp files downloaded in
// install. If this service worker is upgrading from one with a saved
// MANIFEST, then use this to retain unchanged resource files.
self.addEventListener("activate", function(event) {
  return event.waitUntil(async function() {
    try {
      var contentCache = await caches.open(CACHE_NAME);
      var tempCache = await caches.open(TEMP);
      var manifestCache = await caches.open(MANIFEST);
      var manifest = await manifestCache.match('manifest');
      // When there is no prior manifest, clear the entire cache.
      if (!manifest) {
        await caches.delete(CACHE_NAME);
        contentCache = await caches.open(CACHE_NAME);
        for (var request of await tempCache.keys()) {
          var response = await tempCache.match(request);
          await contentCache.put(request, response);
        }
        await caches.delete(TEMP);
        // Save the manifest to make future upgrades efficient.
        await manifestCache.put('manifest', new Response(JSON.stringify(RESOURCES)));
        // Claim client to enable caching on first launch
        self.clients.claim();
        return;
      }
      var oldManifest = await manifest.json();
      var origin = self.location.origin;
      for (var request of await contentCache.keys()) {
        var key = request.url.substring(origin.length + 1);
        if (key == "") {
          key = "/";
        }
        // If a resource from the old manifest is not in the new cache, or if
        // the MD5 sum has changed, delete it. Otherwise the resource is left
        // in the cache and can be reused by the new service worker.
        if (!RESOURCES[key] || RESOURCES[key] != oldManifest[key]) {
          await contentCache.delete(request);
        }
      }
      // Populate the cache with the app shell TEMP files, potentially overwriting
      // cache files preserved above.
      for (var request of await tempCache.keys()) {
        var response = await tempCache.match(request);
        await contentCache.put(request, response);
      }
      await caches.delete(TEMP);
      // Save the manifest to make future upgrades efficient.
      await manifestCache.put('manifest', new Response(JSON.stringify(RESOURCES)));
      // Claim client to enable caching on first launch
      self.clients.claim();
      return;
    } catch (err) {
      // On an unhandled exception the state of the cache cannot be guaranteed.
      console.error('Failed to upgrade service worker: ' + err);
      await caches.delete(CACHE_NAME);
      await caches.delete(TEMP);
      await caches.delete(MANIFEST);
    }
  }());
});
// The fetch handler redirects requests for RESOURCE files to the service
// worker cache.
self.addEventListener("fetch", (event) => {
  if (event.request.method !== 'GET') {
    return;
  }
  var origin = self.location.origin;
  var key = event.request.url.substring(origin.length + 1);
  // Redirect URLs to the index.html
  if (key.indexOf('?v=') != -1) {
    key = key.split('?v=')[0];
  }
  if (event.request.url == origin || event.request.url.startsWith(origin + '/#') || key == '') {
    key = '/';
  }
  // If the URL is not the RESOURCE list then return to signal that the
  // browser should take over.
  if (!RESOURCES[key]) {
    return;
  }
  // If the URL is the index.html, perform an online-first request.
  if (key == '/') {
    return onlineFirst(event);
  }
  event.respondWith(caches.open(CACHE_NAME)
    .then((cache) =>  {
      return cache.match(event.request).then((response) => {
        // Either respond with the cached resource, or perform a fetch and
        // lazily populate the cache only if the resource was successfully fetched.
        return response || fetch(event.request).then((response) => {
          if (response && Boolean(response.ok)) {
            cache.put(event.request, response.clone());
          }
          return response;
        });
      })
    })
  );
});
self.addEventListener('message', (event) => {
  // SkipWaiting can be used to immediately activate a waiting service worker.
  // This will also require a page refresh triggered by the main worker.
  if (event.data === 'skipWaiting') {
    self.skipWaiting();
    return;
  }
  if (event.data === 'downloadOffline') {
    downloadOffline();
    return;
  }
});
// Download offline will check the RESOURCES for all files not in the cache
// and populate them.
async function downloadOffline() {
  var resources = [];
  var contentCache = await caches.open(CACHE_NAME);
  var currentContent = {};
  for (var request of await contentCache.keys()) {
    var key = request.url.substring(origin.length + 1);
    if (key == "") {
      key = "/";
    }
    currentContent[key] = true;
  }
  for (var resourceKey of Object.keys(RESOURCES)) {
    if (!currentContent[resourceKey]) {
      resources.push(resourceKey);
    }
  }
  return contentCache.addAll(resources);
}
// Attempt to download the resource online before falling back to
// the offline cache.
function onlineFirst(event) {
  return event.respondWith(
    fetch(event.request).then((response) => {
      return caches.open(CACHE_NAME).then((cache) => {
        cache.put(event.request, response.clone());
        return response;
      });
    }).catch((error) => {
      return caches.open(CACHE_NAME).then((cache) => {
        return cache.match(event.request).then((response) => {
          if (response != null) {
            return response;
          }
          throw error;
        });
      });
    })
  );
}
