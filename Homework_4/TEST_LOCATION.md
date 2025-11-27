# Testing the Location Map Feature

## 🧪 Local Testing (Quick Check)

### Start PHP Server
```bash
cd Homework_4
php -S localhost:8000
```

### Open in Browser
- **Location Map:** http://localhost:8000/location.php
- **Homepage:** http://localhost:8000/index.html

### What to Verify
✅ Map loads with OpenStreetMap tiles  
✅ Your IP address is detected and displayed  
✅ Map centers on your location coordinates  
✅ Marker appears at your location  
✅ Clicking marker shows popup with IP address  
✅ Map is interactive (zoom, pan works)  
✅ Navigation links work from homepage  

---

## 🚀 Deploy to Server (Final Testing)

### Step 1: SSH to Server
```bash
ssh kkonark@clabsql.clamv.constructor.university
```

### Step 2: Navigate and Pull Latest Code
```bash
cd ~/University-Access-Control-System
git pull
```

### Step 3: Deploy Files
```bash
# Copy Homework_4 files to public_html
rsync -av --delete Homework_4/ ~/public_html/

# Set proper permissions
chmod 755 ~/public_html ~/public_html/img
chmod 644 ~/public_html/*.html ~/public_html/*.php ~/public_html/style.css ~/public_html/img/* 2>/dev/null || true
```

### Step 4: Access in Browser
- **Location Map:** http://10.60.36.1/~kkonark/location.php
- **Homepage:** http://10.60.36.1/~kkonark/index.html

---

## 🔍 Troubleshooting

### Map doesn't load
- Check browser console for errors (F12)
- Verify Leaflet CDN is accessible
- Check internet connection (needs OpenStreetMap tiles)

### IP shows as "UNKNOWN"
- This is normal for localhost (127.0.0.1)
- Will work correctly on the actual server
- The fallback location (Bremen) should still display

### Geo-location fails
- Check if ipinfo.io API is accessible
- Verify curl is enabled in PHP
- Check PHP error logs: `tail -f /var/log/php_errors.log`

### Map shows wrong location
- ipinfo.io uses IP-based geolocation (not GPS)
- Location accuracy depends on IP database
- This is expected behavior for IP-based geolocation

---

## ✅ Expected Behavior

1. **IP Detection:** Should show your real IP (not localhost on server)
2. **Map Display:** Interactive Leaflet map with OpenStreetMap
3. **Marker:** Blue marker at detected coordinates
4. **Popup:** Shows IP address when marker is clicked
5. **Centering:** Map automatically centers on your location
6. **Responsive:** Works on mobile and desktop

---

## 📝 Notes

- The page uses **ipinfo.io** for free IP geolocation
- No API key required for basic usage
- Location accuracy is city-level (not GPS-precise)
- Works with any IP address (IPv4)

