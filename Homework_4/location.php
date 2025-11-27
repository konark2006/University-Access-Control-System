<?php
// Get client IP address
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

// Check if IP is localhost
$isLocalhost = false;
if ($clientIP === 'UNKNOWN' || empty($clientIP)) {
    $isLocalhost = true;
} else if (in_array($clientIP, ['127.0.0.1', '::1', 'localhost'])) {
    $isLocalhost = true;
} else if (strpos($clientIP, '127.') === 0) {
    $isLocalhost = true;
}

// Initialize variables
$geoData = null;
$lat = 53.0793; // Default: Bremen, Germany
$lng = 8.8017;
$error = null;
$isDemo = false;

// Try to get location from API if not localhost
if (!$isLocalhost && $clientIP !== 'UNKNOWN') {
    $cleanIP = trim($clientIP);
    $url = "https://ipinfo.io/{$cleanIP}/json";
    
    // Try to fetch location using cURL or file_get_contents
    $response = false;
    $httpCode = 0;
    
    if (function_exists('curl_init')) {
        // Use cURL if available
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
            $response = false;
        }
    } else if (ini_get('allow_url_fopen')) {
        // Fallback to file_get_contents if cURL is not available
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'user_agent' => 'Mozilla/5.0 (compatible; UACS Location Service)',
                'ignore_errors' => true
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        if ($response !== false) {
            $httpCode = 200; // file_get_contents doesn't return HTTP code, assume success if we got data
        }
    } else {
        $error = "cURL not available and allow_url_fopen is disabled";
        $isDemo = true;
    }
    
    // Process the response
    if ($response && $httpCode === 200) {
        $geoData = json_decode($response, true);
        if ($geoData && isset($geoData['loc'])) {
            $coords = explode(',', $geoData['loc']);
            if (count($coords) === 2) {
                $lat = floatval(trim($coords[0]));
                $lng = floatval(trim($coords[1]));
                
                // Validate coordinates
                if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
                    $error = "Invalid coordinates received";
                    $lat = 53.0793;
                    $lng = 8.8017;
                    $isDemo = true;
                }
            } else {
                $error = "Invalid location format";
                $isDemo = true;
            }
        } else {
            $error = "Location data not available";
            $isDemo = true;
        }
    } else if (!$error) {
        $error = "Failed to fetch location data";
        $isDemo = true;
    }
} else {
    // Localhost - use demo location
    $isDemo = true;
    $error = "Note: You're accessing from localhost. This is a demo location. On a real server, your actual IP location will be shown.";
}

// Ensure coordinates are valid numbers
if (!is_numeric($lat)) $lat = 53.0793;
if (!is_numeric($lng)) $lng = 8.8017;
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
        <span style="color: #f59e0b;">ℹ️ <strong>Demo Mode:</strong> <?php echo $isLocalhost ? 'Localhost detected' : 'Using demo location'; ?></span><br>
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
    var lat = <?php echo floatval($lat); ?>;
    var lng = <?php echo floatval($lng); ?>;
    var clientIP = <?php echo json_encode($clientIP); ?>;
    var isDemo = <?php echo $isDemo ? 'true' : 'false'; ?>;
    
    // Validate coordinates
    if (isNaN(lat) || isNaN(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) {
      console.error('Invalid coordinates:', lat, lng);
      document.getElementById('map').innerHTML = '<div style="padding: 2rem; text-align: center; color: #dc2626;"><strong>Error:</strong> Invalid coordinates. Please refresh the page.</div>';
    } else {
      try {
        // Create map instance
        var map = L.map('map').setView([lat, lng], 13);
        
        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
          maxZoom: 19
        }).addTo(map);
        
        // Add marker with IP address callout
        var marker = L.marker([lat, lng]).addTo(map);
        var popupContent = '<div style="text-align: center; padding: 8px;">' +
          '<strong style="font-size: 16px; color: #0B3D91;">📍 Your Location</strong><br>' +
          '<span style="font-size: 14px; color: #374151;">IP: <code>' + clientIP + '</code></span><br>' +
          '<span style="font-size: 12px; color: #6b7280;">Coordinates: ' + lat.toFixed(4) + ', ' + lng.toFixed(4) + '</span>';
        if (isDemo) {
          popupContent += '<br><span style="font-size: 11px; color: #f59e0b;">⚠️ Demo Mode</span>';
        }
        popupContent += '</div>';
        marker.bindPopup(popupContent).openPopup();
        
        // Handle map errors
        map.on('tileerror', function(error, tile) {
          console.warn('Map tile error:', error);
        });
      } catch (error) {
        console.error('Map initialization error:', error);
        document.getElementById('map').innerHTML = '<div style="padding: 2rem; text-align: center; color: #dc2626;"><strong>Error:</strong> Failed to load map. Please check your internet connection and refresh the page.</div>';
      }
    }
    
    // Dark mode toggle
    var toggle = document.getElementById("theme-toggle");
    if (toggle) {
      if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
        toggle.textContent = '☀️';
      }
      
      toggle.addEventListener("click", function() {
        document.body.classList.toggle("dark-mode");
        toggle.textContent = document.body.classList.contains("dark-mode") ? "☀️" : "🌙";
        localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
      });
    }
  </script>
</body>
</html>
