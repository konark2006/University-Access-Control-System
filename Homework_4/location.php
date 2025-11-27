<?php
// --- SIMPLE AND RELIABLE CLIENT IP DETECTION ---
$ip = $_SERVER["HTTP_X_FORWARDED_FOR"] ?? $_SERVER["REMOTE_ADDR"] ?? "UNKNOWN";
$ip = trim(explode(",", $ip)[0]);  // in case it's "1.2.3.4, 5.6.7.8"

// --- CALL IPINFO ---
$url = "https://ipinfo.io/$ip/json";

$lat = 20;       // safe default
$lng = 0;        // safe default
$city = "";
$region = "";
$country = "";
$error = "";

// If CLAMV gives private IP 10.x.x.x → API will still work sometimes
$response = @file_get_contents($url);

if ($response !== false) {
    $data = json_decode($response, true);

    // Example API response:
    // {
    //   "ip": "37.201.xx.xx",
    //   "city": "Bremen",
    //   "region": "Bremen",
    //   "country": "DE",
    //   "loc": "53.0752,8.8078"
    // }

    if (isset($data["loc"])) {
        list($lat, $lng) = explode(",", $data["loc"]);
        $lat = floatval($lat);
        $lng = floatval($lng);

        $city = $data["city"] ?? "";
        $region = $data["region"] ?? "";
        $country = $data["country"] ?? "";
    } else {
        $error = "API did not return location.";
    }
} else {
    $error = "Failed to contact ipinfo.io";
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Client Location Map — UACS</title>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
  #map { height: 600px; width: 100%; margin-top: 20px; border-radius: 8px; }
  .info-box { background: #fff; padding: 15px; border-radius: 8px; width: 600px; margin: 20px auto; font-size: 1.1rem; }
</style>
</head>

<body>

<h2 style="text-align:center;">📍 Client Location</h2>

<div class="info-box">
  <strong>IP Address:</strong> <?= htmlspecialchars($ip) ?><br>
  <strong>Location:</strong>
  <?= htmlspecialchars("$city $region $country") ?><br>

  <?php if ($error): ?>
    <span style="color:red;">⚠ <?= htmlspecialchars($error) ?></span>
  <?php endif; ?>
</div>

<div id="map"></div>

<script>
// Create map
var lat = <?= $lat ?>;
var lng = <?= $lng ?>;

var map = L.map('map').setView([lat, lng], 12);

// Add OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
   maxZoom: 19
}).addTo(map);

// Add marker
L.marker([lat, lng]).addTo(map)
  .bindPopup("IP: <b><?= htmlspecialchars($ip) ?></b><br>Lat/Lng: <?= $lat ?>, <?= $lng ?>")
  .openPopup();
</script>

</body>
</html>