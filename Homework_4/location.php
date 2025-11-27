<?php
// -----------------------------
// 1. GET CLIENT IP (private OK)
// -----------------------------
$clientIP = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

// -----------------------------
// 2. CALL IPINFO API
// -----------------------------
$apiUrl = "https://ipinfo.io/{$clientIP}/json";
$response = @file_get_contents($apiUrl);
$data = $response ? json_decode($response, true) : null;

// -----------------------------
// 3. EXTRACT LOCATION OR FALLBACK
// -----------------------------
$lat = 20;   // default (center of world) → used when IP is private
$lng = 0;
$error = "";

if ($data && isset($data['loc'])) {
    list($lat, $lng) = explode(",", $data["loc"]);
} else {
    $error = "API could not return location — likely because the IP is private (e.g., 172.x.x.x). Showing default map.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Client Location Map — UACS</title>
  <link rel="stylesheet" href="style.css?v=13">

  <!-- Leaflet CSS -->
  <link rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>

  <style>
    .map-wrapper {
        max-width: 1100px;
        margin: 2rem auto;
        padding: 0 1rem;
    }
    #map {
        height: 600px;
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .location-box {
        background: white;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        font-size: 1rem;
    }
    .dark-mode .location-box {
        background: #1f2937;
        color: #e5e7eb;
    }
  </style>

  <!-- Universal Navbar styling (same as index.html) -->
  <style>
    .uacs-topbar {
      position: sticky; top: 0; z-index: 9999;
      background: #0b1728; border-bottom: 1px solid rgba(255,255,255,.08);
    }
    .uacs-nav {
      max-width: 1100px; margin: 0 auto; padding: 10px 16px;
      display: flex; gap: 10px; align-items: center; flex-wrap: wrap;
    }
    .uacs-nav a {
      color: #e2e8f0; text-decoration: none;
      font: 600 14px/1.2 system-ui;
      padding: 8px 12px; border-radius: 10px;
      transition: background .2s, color .2s, transform .1s;
    }
    .uacs-nav a:hover { background: rgba(255,255,255,.08); }
    @media (prefers-color-scheme: light) {
      .uacs-topbar { background: #fff; border-bottom: 1px solid #e5e7eb; }
      .uacs-nav a { color: #111827; }
      .uacs-nav a:hover { background: #f3f4f6; }
    }
  </style>
</head>

<body>

<!-- UNIVERSAL NAVBAR -->
<header class="uacs-topbar">
  <nav class="uacs-nav">
    <a class="logo" href="/~kkonark/">UACS</a>
    <a href="/~kkonark/HW4/index.html">📘 Access Portal</a>
    <a href="/~kkonark/HW5/maintenance.php">🧰 Maintenance</a>
    <a href="/~kkonark/HW6/index.html">🔍 Search Portal</a>
    <a href="/~kkonark/HW4/location.php" style="font-weight:bold;">📍 Location Map</a>
    <a href="/~kkonark/HW5/logout.php" style="color:#ff4d4d; font-weight:bold;">🚪 Logout</a>
  </nav>
</header>

<!-- PAGE HEADER -->
<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="index.html"><img class="brand-logo" src="img/UACS_logo.png"><span class="brand-text">UACS</span></a>
    <nav class="nav">
      <a href="index.html">Home</a>
      <a href="/~kkonark/HW5/maintenance.php">Maintenance</a>
      <a href="/~kkonark/HW6/index.html">Search</a>
      <a class="active" href="location.php">Location Map</a>
      <button id="theme-toggle" class="btn small-btn">🌙</button>
    </nav>
  </div>
</header>

<div class="map-wrapper">
  
  <!-- INFO BOX -->
  <div class="location-box">
      <strong>IP Address:</strong> <?= htmlspecialchars($clientIP) ?><br>

      <?php if ($data && isset($data['city'])): ?>
        <strong>Location:</strong>
        <?= htmlspecialchars($data['city']) ?>, 
        <?= htmlspecialchars($data['region'] ?? '') ?>,
        <?= htmlspecialchars($data['country'] ?? '') ?>
      <?php endif; ?>

      <?php if ($error): ?>
        <br><span style="color:#dc2626; font-size:0.9em;">⚠ <?= $error ?></span>
      <?php endif; ?>
  </div>

  <!-- MAP -->
  <div id="map"></div>

</div>

<!-- FOOTER -->
<footer class="footer">
  <p>© 2025 UACS — <a href="imprint.html">Imprint / Disclaimer</a></p>
</footer>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
 integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="">
</script>

<script>
  var lat = <?= floatval($lat) ?>;
  var lng = <?= floatval($lng) ?>;
  var clientIP = <?= json_encode($clientIP) ?>;

  // Initialize map
  var map = L.map('map').setView([lat, lng], 4);

  // Layer
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      maxZoom: 19,
      attribution: "&copy; OpenStreetMap contributors"
  }).addTo(map);

  // Marker
  L.marker([lat, lng])
    .addTo(map)
    .bindPopup(`
      <div style="text-align:center; padding:6px;">
        <strong style="color:#0B3D91;">📍 Location</strong><br>
        <span>IP: <code>${clientIP}</code></span><br>
        <span>Lat/Lng: ${lat}, ${lng}</span>
      </div>
    `)
    .openPopup();

  // Dark mode toggle
  document.getElementById("theme-toggle").onclick = () => {
    document.body.classList.toggle("dark-mode");
    localStorage.setItem("theme", document.body.classList.contains("dark-mode") ? "dark" : "light");
  };
  if (localStorage.getItem("theme") === "dark") {
    document.body.classList.add("dark-mode");
    document.getElementById("theme-toggle").textContent = "☀️";
  }
</script>

</body>
</html>