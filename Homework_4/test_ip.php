<?php
// Quick test script to verify IP detection and geo-location

function getClientIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

$clientIP = getClientIP();
echo "<h2>IP Detection Test</h2>";
echo "<p><strong>Detected IP:</strong> " . htmlspecialchars($clientIP) . "</p>";

if ($clientIP && $clientIP !== 'UNKNOWN') {
    echo "<p>Testing geo-location lookup...</p>";
    
    $url = "https://ipinfo.io/{$clientIP}/json";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    echo "<p><strong>HTTP Status:</strong> " . $httpCode . "</p>";
    
    if ($curlError) {
        echo "<p><strong>Error:</strong> " . htmlspecialchars($curlError) . "</p>";
    }
    
    if ($httpCode === 200 && $response) {
        $geoData = json_decode($response, true);
        echo "<h3>Geo-location Data:</h3>";
        echo "<pre>" . print_r($geoData, true) . "</pre>";
        
        if (isset($geoData['loc'])) {
            $coords = explode(',', $geoData['loc']);
            $lat = floatval($coords[0]);
            $lng = floatval($coords[1]);
            echo "<p><strong>Coordinates:</strong> Lat: {$lat}, Lng: {$lng}</p>";
        }
    } else {
        echo "<p><strong>Failed to fetch location data</strong></p>";
        echo "<p>Response: " . htmlspecialchars(substr($response, 0, 200)) . "</p>";
    }
} else {
    echo "<p><strong>Note:</strong> IP detection returned UNKNOWN (this is normal for localhost)</p>";
}

echo "<hr>";
echo "<p><a href='location.php'>Go to Location Map →</a></p>";
?>

