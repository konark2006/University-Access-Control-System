<?php
// Get client IP address (improved version)
function getClientIP() {
    // Check for forwarded IPs first (most reliable for proxies)
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ips[0]);
        // Filter out private IPs
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $ip;
        }
    }
    
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $ip;
        }
    }
    
    // Fallback to REMOTE_ADDR
    if (!empty($_SERVER['REMOTE_ADDR'])) {
        return $_SERVER['REMOTE_ADDR'];
    }
    
    return 'UNKNOWN';
}

$clientIP = getClientIP();

// Check if IP is localhost or private
$isLocalhost = in_array($clientIP, ['127.0.0.1', '::1', 'localhost', 'UNKNOWN']) || 
               strpos($clientIP, '127.') === 0 ||
               strpos($clientIP, '192.168.') === 0 ||
               strpos($clientIP, '10.') === 0 ||
               strpos($clientIP, '172.') === 0;

// Fetch geo location from ipinfo.io
$geoData = null;
$lat = null;
$lng = null;
$error = null;
$isDemo = false;

if ($isLocalhost) {
    // For localhost/private IPs, use demo location
    $lat = 53.0793; // Demo: Bremen, Germany
    $lng = 8.8017;
    $isDemo = true;
    $error = "Note: You're accessing from localhost/private network. This is a demo location. On a public server, your actual IP location will be shown.";
} else if ($clientIP && $clientIP !== 'UNKNOWN') {
    // Clean IP address (remove any whitespace)
    $cleanIP = trim($clientIP);
    
    // Use ipinfo.io API
    $url = "https://ipinfo.io/{$cleanIP}/json";
    
    // Initialize cURL with better error handling
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; UACS Location Service)');
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    if ($curlError) {
        $error = "Network error: " . htmlspecialchars($curlError);
        $lat = 53.0793;
        $lng = 8.8017;
    } else if ($httpCode === 200 && $response) {
        $geoData = json_decode($response, true);
        if ($geoData && isset($geoData['loc'])) {
            $coords = explode(',', $geoData['loc']);
            if (count($coords) === 2) {
                // ipinfo.io returns "lat,lng" format
                $lat = floatval(trim($coords[0]));
                $lng = floatval(trim($coords[1]));
                
                // Validate coordinates
                if ($lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180) {
                    // Success!
                } else {
                    $error = "Invalid coordinates received";
                    $lat = 53.0793;
                    $lng = 8.8017;
                }
            } else {
                $error = "Invalid location format";
                $lat = 53.0793;
                $lng = 8.8017;
            }
        } else {
            $error = "Location data not available in response";
            $lat = 53.0793;
            $lng = 8.8017;
        }
    } else {
        $error = "Failed to fetch location data (HTTP {$httpCode})";
        $lat = 53.0793;
        $lng = 8.8017;
    }
} else {
    $error = "Could not determine IP address";
    $lat = 53.0793;
    $lng = 8.8017;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Client Location Map — UACS</title>
  <link rel="stylesheet" href="style.css?v=13" />
  
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
  
  <style>
    /* === UNIVERSAL NAVBAR STYLING === */
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
      font: 600 14px/1.2 system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
      padding: 8px 12px; border-radius: 10px;
      transition: background .2s, color .2s, transform .1s;
    }
    .uacs-nav a:hover { background: rgba(255,255,255,.08); transform: translateY(-1px); }
    .uacs-nav .logo { margin-right: auto; font-weight: 800; letter-spacing: .3px; background: transparent; }
    @media (prefers-color-scheme: light){
      .uacs-topbar { background: #fff; border-bottom: 1px solid #e5e7eb; }
      .uacs-nav a { color: #111827; }
      .uacs-nav a:hover { background: #f3f4f6; }
    }
    
    /* Map container styling */
    .map-container {
      max-width: 1100px;
      margin: 2rem auto;
      padding: 0 1rem;
    }
    
    .map-header {
      text-align: center;
      margin-bottom: 2rem;
    }
    
    .map-header h1 {
      font-size: 2rem;
      color: #0B3D91;
      margin-bottom: 0.5rem;
    }
    
    .map-header p {
      color: #374151;
      font-size: 1.1rem;
    }
    
    .map-info {
      background: white;
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1rem;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .map-info strong {
      color: #0B3D91;
    }
    
    #map {
      height: 600px;
      width: 100%;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      z-index: 1;
    }
    
    .dark-mode .map-info {
      background: #1f2937;
      color: #e5e7eb;
    }
    
    .dark-mode .map-header h1 {
      color: #58a6ff;
    }
    
    .dark-mode .map-header p {
      color: #cbd5e1;
    }
  </style>
</head>
<body>
  <!-- === UNIVERSAL NAV BAR === -->
  <header class="uacs-topbar">
    <nav class="uacs-nav">
      <a class="logo" href="/~kkonark/">UACS</a>
      <a href="/~kkonark/HW4/index.html">📘 Access Portal</a>
      <a href="/~kkonark/HW5/maintenance.php">🧰 Maintenance</a>
      <a href="/~kkonark/HW6/index.html">🔍 Search Portal</a>
      <a href="/~kkonark/HW4/location.php">📍 Location Map</a>
      <a href="/~kkonark/HW5/logout.php" style="color:#ff4d4d;font-weight:bold;">🚪 Logout</a>
    </nav>
  </header>

  <!-- === MAIN HEADER === -->
  <header class="site-header">
    <div class="container header-inner">
      <a class="brand" href="index.html" aria-label="UACS Home">
        <img class="brand-logo" src="img/UACS_logo.png" alt="UACS logo" />
        <span class="brand-text">UACS</span>
      </a>

      <nav class="nav">
        <a href="index.html">Home</a>
        <a href="/~kkonark/HW5/maintenance.php" class="nav-admin">Maintenance</a>
        <a href="/~kkonark/HW6/index.html" class="nav-search">Search</a>
        <a href="location.php" class="active">Location Map</a>
        <button id="theme-toggle" class="btn small-btn" aria-label="Toggle dark mode">🌙</button>
      </nav>
    </div>
  </header>

  <!-- === MAP SECTION === -->
  <div class="map-container">
    <div class="map-header">
      <h1>📍 Client Location Map</h1>
      <p>Your geographical location based on IP address</p>
    </div>
    
    <div class="map-info">
      <strong>IP Address:</strong> <?php echo htmlspecialchars($clientIP); ?><br>
      <?php if ($isDemo): ?>
        <span style="color: #f59e0b;">ℹ️ <strong>Demo Mode:</strong> Localhost detected</span><br>
      <?php elseif ($geoData && isset($geoData['city'])): ?>
        <strong>Location:</strong> <?php echo htmlspecialchars($geoData['city']); ?>
        <?php if (isset($geoData['region'])): ?>, <?php echo htmlspecialchars($geoData['region']); ?><?php endif; ?>
        <?php if (isset($geoData['country'])): ?> — <?php echo htmlspecialchars($geoData['country']); ?><?php endif; ?>
      <?php endif; ?>
      <?php if ($error): ?>
        <br><span style="color: <?php echo $isDemo ? '#f59e0b' : '#dc2626'; ?>;">⚠️ <?php echo htmlspecialchars($error); ?></span>
      <?php endif; ?>
    </div>
    
    <div id="map"></div>
  </div>

  <!-- === FOOTER === -->
  <footer class="footer">
    <p>© 2025 UACS — <a href="imprint.html">Imprint / Disclaimer</a></p>
  </footer>

  <!-- Leaflet JavaScript -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

  <script>
    // Initialize map centered on the client's location
    const lat = <?php echo $lat; ?>;
    const lng = <?php echo $lng; ?>;
    const clientIP = <?php echo json_encode($clientIP); ?>;
    
    // Create map instance
    const map = L.map('map').setView([lat, lng], 13);
    
    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      maxZoom: 19
    }).addTo(map);
    
    // Add marker with IP address callout
    const marker = L.marker([lat, lng]).addTo(map);
    marker.bindPopup(`
      <div style="text-align: center; padding: 8px;">
        <strong style="font-size: 16px; color: #0B3D91;">📍 Your Location</strong><br>
        <span style="font-size: 14px; color: #374151;">IP: <code>${clientIP}</code></span><br>
        <span style="font-size: 12px; color: #6b7280;">Coordinates: ${lat.toFixed(4)}, ${lng.toFixed(4)}</span>
      </div>
    `).openPopup();
    
    // Dark mode toggle
    const toggle = document.getElementById("theme-toggle");
    if (localStorage.getItem('theme') === 'dark') {
      document.body.classList.add('dark-mode');
      toggle.textContent = '☀️';
    }
    
    toggle.addEventListener("click", () => {
      document.body.classList.toggle("dark-mode");
      toggle.textContent = document.body.classList.contains("dark-mode") ? "☀️" : "🌙";
      localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
    });
  </script>
</body>
</html>

