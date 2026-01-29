<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$userId = (int)$_SESSION['user_id'];
$userName = $_SESSION['full_name'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>My Map Planner | TravelEase</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <!-- Leaflet Draw -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

  <style>
    #map { height: calc(100vh - 72px); }
  </style>
</head>

<body class="bg-gray-50">
  <!-- Top bar -->
  <header class="h-[72px] bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 h-full flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="user_dashboard.php" class="text-sm font-semibold text-gray-700 hover:text-black">
          ← Back
        </a>
        <h1 class="text-lg font-bold">My Map Planner</h1>
      </div>
      <div class="text-sm text-gray-600">
        Logged in as <span class="font-semibold"><?= htmlspecialchars($userName) ?></span>
      </div>
    </div>
  </header>

  <div class="max-w-7xl mx-auto px-4 py-4 grid grid-cols-1 lg:grid-cols-4 gap-4">
    <!-- Left panel -->
    <aside class="lg:col-span-1 bg-white rounded-xl border p-4 space-y-4">
      <div class="p-3 rounded-lg bg-yellow-50 border border-yellow-100 text-sm text-gray-700">
        <div class="font-semibold mb-1">How it works</div>
        <ul class="list-disc ml-5 space-y-1">
          <li>Click map → add a pin</li>
          <li>Use draw tool → create a route</li>
          <li>Select pin → upload photos</li>
        </ul>
      </div>

      <!-- Add pin form -->
      <div class="border rounded-lg p-3">
        <div class="font-semibold mb-2">Add Pin</div>
        <p class="text-xs text-gray-500 mb-3">Click on the map to fill Lat/Lng.</p>

        <div class="space-y-2">
          <input id="pinTitle" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Title (e.g., Hotel)" />
          <textarea id="pinNotes" class="w-full border rounded-lg px-3 py-2 text-sm" rows="3" placeholder="Notes"></textarea>

          <div class="grid grid-cols-2 gap-2">
            <input id="pinLat" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Lat" readonly />
            <input id="pinLng" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Lng" readonly />
          </div>

          <button id="savePinBtn" class="w-full bg-yellow-400 hover:bg-yellow-300 text-black font-semibold py-2 rounded-lg text-sm">
            Save Pin
          </button>
        </div>
      </div>

      <!-- Selected pin photo upload -->
      <div class="border rounded-lg p-3">
        <div class="font-semibold mb-2">Selected Pin Photos</div>
        <div id="selectedPinBox" class="text-xs text-gray-500 mb-3">
          No pin selected yet.
        </div>

        <form id="photoForm" class="space-y-2 hidden" enctype="multipart/form-data">
          <input type="hidden" name="pin_id" id="photoPinId" />
          <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="w-full text-sm" required />
          <input type="text" name="caption" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Caption (optional)"/>
          <button class="w-full bg-gray-900 hover:bg-black text-white font-semibold py-2 rounded-lg text-sm">
            Upload Photo
          </button>
        </form>

        <div id="photoList" class="mt-3 space-y-2"></div>
      </div>

      <!-- Lists -->
      <div class="border rounded-lg p-3">
        <div class="font-semibold mb-2">My Pins</div>
        <div id="pinsList" class="space-y-2 text-sm"></div>
      </div>

      <div class="border rounded-lg p-3">
        <div class="font-semibold mb-2">My Routes</div>
        <div id="routesList" class="space-y-2 text-sm"></div>
      </div>
    </aside>

    <!-- Map -->
    <main class="lg:col-span-3 bg-white rounded-xl border overflow-hidden">
      <div id="map"></div>
    </main>
  </div>

<script>
  // ===== Helpers =====
  async function apiGet(url) {
    const res = await fetch(url, { credentials: "same-origin" });
    return res.json();
  }
  async function apiPost(url, bodyObj) {
    const res = await fetch(url, {
      method: "POST",
      credentials: "same-origin",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(bodyObj)
    });
    return res.json();
  }

  // ===== Map init (default: Sri Lanka) =====
  const map = L.map('map').setView([7.8731, 80.7718], 8);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap'
  }).addTo(map);

  // Layers
  const pinsLayer = L.layerGroup().addTo(map);
  const routesLayer = L.layerGroup().addTo(map);

  // Draw controls
  const drawnItems = new L.FeatureGroup();
  map.addLayer(drawnItems);

  const drawControl = new L.Control.Draw({
    edit: { featureGroup: drawnItems },
    draw: {
      polygon: false,
      rectangle: false,
      circle: false,
      circlemarker: false,
      marker: false, // we use click-to-add pins (better)
      polyline: true
    }
  });
  map.addControl(drawControl);

  let selectedPinId = null;
  let selectedPinMarker = null;

  // Click map to fill lat/lng
  map.on('click', (e) => {
    document.getElementById('pinLat').value = e.latlng.lat.toFixed(6);
    document.getElementById('pinLng').value = e.latlng.lng.toFixed(6);
  });

  // Save pin
  document.getElementById('savePinBtn').addEventListener('click', async () => {
    const title = document.getElementById('pinTitle').value.trim();
    const notes = document.getElementById('pinNotes').value.trim();
    const lat = document.getElementById('pinLat').value;
    const lng = document.getElementById('pinLng').value;

    if (!title || !lat || !lng) {
      alert("Please set Title and click map to choose location (Lat/Lng).");
      return;
    }

    const out = await apiPost('api/pins_create.php', { title, notes, lat, lng });
    if (!out.ok) {
      alert(out.error || "Failed to save pin");
      return;
    }

    // clear form
    document.getElementById('pinTitle').value = "";
    document.getElementById('pinNotes').value = "";
    document.getElementById('pinLat').value = "";
    document.getElementById('pinLng').value = "";

    await loadPins();
  });

  // Draw created (routes)
  map.on(L.Draw.Event.CREATED, async function (event) {
    const layer = event.layer;

    if (event.layerType === 'polyline') {
      // Convert to GeoJSON LineString
      const geo = layer.toGeoJSON();

      const name = prompt("Route name:", "My Route");
      if (!name) return;

      const out = await apiPost('api/routes_create.php', { name, geojson: geo });
      if (!out.ok) {
        alert(out.error || "Failed to save route");
        return;
      }

      await loadRoutes();
    }
  });

  // Load pins from DB
  async function loadPins() {
    pinsLayer.clearLayers();
    const pins = await apiGet('api/pins_list.php');
    const list = document.getElementById('pinsList');
    list.innerHTML = "";

    pins.forEach(p => {
      const marker = L.marker([parseFloat(p.lat), parseFloat(p.lng)]).addTo(pinsLayer);
      marker.bindPopup(`<b>${escapeHtml(p.title)}</b><br>${escapeHtml(p.notes || "")}`);

      marker.on('click', () => selectPin(p, marker));

      // sidebar item
      const item = document.createElement('div');
      item.className = "p-2 rounded-lg border hover:bg-gray-50 cursor-pointer flex items-start justify-between gap-2";
      item.innerHTML = `
        <div>
          <div class="font-semibold">${escapeHtml(p.title)}</div>
          <div class="text-xs text-gray-500">${escapeHtml((p.notes || "").slice(0, 60))}</div>
        </div>
        <button class="text-xs text-red-600 hover:underline" data-id="${p.id}">Delete</button>
      `;
      item.addEventListener('click', () => {
        map.setView([parseFloat(p.lat), parseFloat(p.lng)], 14);
        selectPin(p, marker);
      });

      item.querySelector("button").addEventListener('click', async (ev) => {
        ev.stopPropagation();
        if (!confirm("Delete this pin?")) return;
        const out = await apiPost('api/pins_delete.php', { id: p.id });
        if (!out.ok) alert(out.error || "Failed to delete");
        if (selectedPinId === p.id) clearSelectedPin();
        await loadPins();
      });

      list.appendChild(item);
    });
  }

  function clearSelectedPin() {
    selectedPinId = null;
    selectedPinMarker = null;
    document.getElementById('selectedPinBox').textContent = "No pin selected yet.";
    document.getElementById('photoForm').classList.add('hidden');
    document.getElementById('photoList').innerHTML = "";
  }

  async function selectPin(pin, marker) {
    selectedPinId = pin.id;
    selectedPinMarker = marker;

    document.getElementById('selectedPinBox').innerHTML = `
      <div class="text-sm font-semibold">${escapeHtml(pin.title)}</div>
      <div class="text-xs text-gray-500">Pin ID: ${pin.id}</div>
    `;

    document.getElementById('photoPinId').value = pin.id;
    document.getElementById('photoForm').classList.remove('hidden');

    await loadPinPhotos(pin.id);
  }

  // Upload photo
  document.getElementById('photoForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!selectedPinId) return;

    const formData = new FormData(e.target);
    const res = await fetch('api/pin_photo_upload.php', {
      method: 'POST',
      credentials: 'same-origin',
      body: formData
    });
    const out = await res.json();
    if (!out.ok) {
      alert(out.error || "Upload failed");
      return;
    }

    e.target.reset();
    document.getElementById('photoPinId').value = selectedPinId;
    await loadPinPhotos(selectedPinId);
  });

  async function loadPinPhotos(pinId) {
    const out = await apiGet('api/pin_photos_list.php?pin_id=' + encodeURIComponent(pinId));
    const box = document.getElementById('photoList');
    box.innerHTML = "";

    if (!out.ok) {
      box.innerHTML = `<div class="text-xs text-red-600">${escapeHtml(out.error || "Failed")}</div>`;
      return;
    }

    if (!out.photos.length) {
      box.innerHTML = `<div class="text-xs text-gray-500">No photos yet.</div>`;
      return;
    }

    out.photos.forEach(ph => {
      const div = document.createElement('div');
      div.className = "border rounded-lg p-2";
      div.innerHTML = `
        <img src="uploads/pin_photos/${encodeURIComponent(ph.file_name)}" class="w-full h-40 object-cover rounded-md mb-2"/>
        <div class="text-xs text-gray-700">${escapeHtml(ph.caption || "")}</div>
        <div class="text-[11px] text-gray-400">${escapeHtml(ph.uploaded_at)}</div>
      `;
      box.appendChild(div);
    });
  }

  // Load routes
  async function loadRoutes() {
    routesLayer.clearLayers();
    const routes = await apiGet('api/routes_list.php');
    const list = document.getElementById('routesList');
    list.innerHTML = "";

    routes.forEach(r => {
      // draw GeoJSON
      const geo = r.geojson;
      const layer = L.geoJSON(geo).addTo(routesLayer);

      const item = document.createElement('div');
      item.className = "p-2 rounded-lg border hover:bg-gray-50 cursor-pointer";
      item.innerHTML = `
        <div class="font-semibold">${escapeHtml(r.name)}</div>
        <div class="text-xs text-gray-500">Route ID: ${r.id}</div>
      `;
      item.addEventListener('click', () => {
        const b = layer.getBounds();
        if (b.isValid()) map.fitBounds(b.pad(0.2));
      });
      list.appendChild(item);
    });
  }

  function escapeHtml(str) {
    return (str ?? "").toString()
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#039;");
  }

  // Initial load
  clearSelectedPin();
  loadPins();
  loadRoutes();
</script>
</body>
</html>
