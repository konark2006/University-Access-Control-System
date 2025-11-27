#!/bin/bash
# Fix deployment - deploy to correct HW4 subdirectory

cd ~/University-Access-Control-System
git pull
rsync -av --delete Homework_4/ ~/public_html/HW4/
chmod 755 ~/public_html/HW4 ~/public_html/HW4/img
chmod 644 ~/public_html/HW4/*.php ~/public_html/HW4/*.html ~/public_html/HW4/style.css ~/public_html/HW4/img/* 2>/dev/null || true

echo "Deployment complete! Access at: http://10.60.36.1/~kkonark/HW4/location.php"

