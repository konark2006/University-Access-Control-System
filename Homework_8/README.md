# UACS – Homework 8

This homework extends the University Access Control System (UACS) by adding **server log monitoring**, **statistics extraction**, and **data visualization** using Python. You will find two main components:

1. **Log Statistics Processor (log_stats.py)**
2. **Log Visualization Generator (log_charts.py)**

Both scripts work with custom access/error logs stored in your home directory.

---

## 📁 Directory Setup

We created a dedicated folder inside your CLAMV home directory to store simulated logs:

```
~/logs/
    ├── access.log
    └── error.log
```

These files are **not system logs** — they contain **dummy entries** created by you for the purpose of Homework 7 (since Apache logs are not accessible on CLAMV).

---

## 🔧 Part 1 — Log Statistics (log_stats.py)

### ✔ What the script does

`log_stats.py` reads your log files and extracts:

### 1. Page Access Count
How many times each page was visited.

### 2. Top IP Addresses
Shows which IPs accessed the system most frequently.

### 3. Browser / User Agents
Analyzes what types of browsers accessed your application.

### 4. Access Timeline (per day)
Shows activity grouped by date.

### 5. Error Timeline
Displays which client IPs generated error log entries.

---

## 📊 Example Output (from your dummy logs)

```
===== PAGE ACCESS COUNT =====
/index.html: 1
/favicon.ico: 1
/HWs/index.html: 1
/HWs/login.php: 1
/HWs/search_user.php?a=abc: 1

===== TOP IP ADDRESSES =====
192.168.1.10: 2
192.168.1.11: 1
192.168.1.99: 1
192.168.1.100: 1

===== BROWSER / USER AGENTS =====
Unknown: 2
Mozilla/5.0: 1
Chrome/122.0: 1
Firefox/118.8: 1

===== ACCESS TIMELINE (per day) =====
08/Nov/2025: 4
09/Nov/2025: 1

===== ERROR TIMELINE =====
192.168.1.12: 1
```

---

## 📈 Part 2 — Log Visualization (log_charts.py)

`log_charts.py` generates **3 PNG charts**:

| Chart | Filename | Description |
|-------|----------|-------------|
| 📊 Page Access Count | `page_access_count.png` | Bar chart showing number of hits per page |
| 📅 Access Timeline | `access_timeline.png` | Activity per day |
| ❌ Error Count | `error_count.png` | Bar chart of which IPs triggered errors |

### These files appear inside:
```
~/University-Access-Control-System/
```

---

## ✅ Matplotlib was installed locally:

```bash
pip install matplotlib
```

---

## 🧪 Adding More Fake Data

To improve your charts and make the stats meaningful, you can add **hundreds of random entries** to your logs:

### ▶ Add fake access logs:

```bash
for i in {1..200}; do
  IP="192.168.1.$((RANDOM % 50))"
  DAY=$((4 + RANDOM % 9))
  echo "$IP - - [0$DAY/Nov/2025:13:$((RANDOM % 60)):$((RANDOM % 60))] \"GET /HWs/((4 + RANDOM % 3))/index.html HTTP/1.1\" 200 512" >> ~/logs/access.log
done
```

### ▶ Add fake error logs:

```bash
for i in {1..50}; do
  IP="192.168.1.$((RANDOM % 50))"
  echo "[error] [client $IP] File does not exist: /var/www/missing" >> ~/logs/error.log
done
```

Then re-run:

```bash
python3 log_stats.py
python3 log_charts.py
```

---

## 📋 What This Homework Achieves

✔ Built a **private simulated Apache logging system** (since real Apache logs are restricted)  
✔ Implemented a **log parser** using regex to extract structured insights  
✔ Analyzed website activity:
  - IP patterns
  - Page popularity
  - User agents
  - Timeline patterns  
✔ **Created visual charts** for reporting  
✔ Practiced **Linux commands and file manipulation**  
✔ **Committed and pushed everything to GitHub**

This demonstrates an understanding of **web security monitoring**, **log analysis**, and **basic intrusion detection concepts** — all essential for cybersecurity and GRC work.

---

## 🔗 Files Included

```
logs/
    access.log
    error.log

log_stats.py
log_charts.py
page_access_count.png
access_timeline.png
error_count.png
README_HW7.md
```

---
