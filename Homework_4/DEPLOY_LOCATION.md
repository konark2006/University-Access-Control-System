# Deploy Location Map to Server

## Quick Deploy Command

SSH to your server and run:

```bash
ssh kkonark@clabsql.clamv.constructor.university
cd ~/University-Access-Control-System
git pull
rsync -av --delete Homework_4/ ~/public_html/HW4/
chmod 755 ~/public_html/HW4 ~/public_html/HW4/img
chmod 644 ~/public_html/HW4/*.php ~/public_html/HW4/*.html ~/public_html/HW4/style.css ~/public_html/HW4/img/* 2>/dev/null || true
```

## Access URL

After deployment, access at:
```
http://10.60.36.1/~kkonark/HW4/location.php
```

## Verify File is Deployed

Check if file exists:
```bash
ls -la ~/public_html/HW4/location.php
```

If it doesn't exist, the rsync command above will copy it.

