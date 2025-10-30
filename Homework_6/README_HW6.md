
🌐 Homepage:
```
https://clabsql.clamv.constructor.university/~kkonark/

```

📘 HW4:
```
https://clabsql.clamv.constructor.university/~kkonark/HW4/
```

🧰 HW5 (Maintenance):
```
https://clabsql.clamv.constructor.university/~kkonark/HW5/maintenance.html
```

🔍 HW6 (Search):
```
https://clabsql.clamv.constructor.university/~kkonark/HW6/index.html￼
```
---

## 🧩 Features Implemented

### 🔍 Search Portal (index.html)
- Central interface for searching across UACS data.  
- Navigation links to **Access Requests (HW4)**, **Admin Dashboard (HW5)**, and **Search Portal (HW6)**.  
- Includes dark mode toggle, glowing buttons, and smooth animations.

### 🧾 PHP Search Scripts
Each script interacts with the MariaDB database via `db_connect.php`:
- `search_user.php` — Search for users by name or email.  
- `search_resource.php` — View all registered resources and search by ID/type.  
- `search_request.php` — Display requests with dynamic filters.

All return formatted HTML tables or messages for user-friendly feedback.

### 🔐 Database Integration
Uses the **same database schema** as previous homeworks. Connection example:
```php
$servername = "localhost";
$username   = "kkonark";
$password   = "your_db_password";
$dbname     = "db_kkonark";
$conn = new mysqli($servername, $username, $password, $dbname);
```
✅ Tested via `db_connect.php` — shows a blank page if connected successfully.

### 🎨 Interface Enhancements
- Shared navigation bar linking all homework modules.  
- Glowing button design for all interactive links.  
- Smooth transitions, cursor effects, and full dark-mode compatibility.  
- Fully responsive for desktop and mobile.

---

## 🧭 Navigation Map

| Section | URL | Description |
|----------|-----|-------------|
| 🏠 Home | `/~kkonark/` | Main UACS portal |
| 📄 Access Requests | `/~kkonark/HW4/index.html` | Add & manage requests |
| 🧰 Admin Dashboard | `/~kkonark/HW5/maintenance.html` | Entity maintenance & admin tools |
| 🔍 Search Portal | `/~kkonark/HW6/index.html` | Search users, requests & resources |

---

## ⚙️ Testing Steps

### 1️⃣ Access your hosted HW6
```
https://clabsql.clamv.constructor.university/~kkonark/HW6/index.html
```

### 2️⃣ Verify DB connection
```
https://clabsql.clamv.constructor.university/~kkonark/HW6/db_connect.php
```
- Blank page = ✅ connected.  
- Error message = check credentials.

### 3️⃣ Run example search
```
https://clabsql.clamv.constructor.university/~kkonark/HW6/search_user.php?query=konark
```
Displays matching users or “No users found.”

---

## 🧠 Tech Stack

| Component | Purpose |
|------------|----------|
| **HTML5 / CSS3** | Page layout and styling |
| **PHP (MySQLi)** | Server-side database queries |
| **MariaDB / MySQL** | Data storage backend |
| **JavaScript** | Dark mode, animations, and interactivity |

---

## 🧾 Folder Structure

```
HW6/
├── index.html              # Search Portal
├── site_style.css          # Styling for all HW6 pages
├── db_connect.php          # Database connection
├── search_user.php         # User search handler
├── search_resource.php     # Resource search handler
├── search_request.php      # Request search handler
└── README_HW6.md           # Documentation
```

---

## ✨ Author
**Konark** — Constructor University, Bremen  
_University Access Control System (UACS) — Homework 6_
"""
