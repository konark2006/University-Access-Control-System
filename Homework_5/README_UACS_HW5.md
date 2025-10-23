# 🏫 University Access Control System (UACS) - Homework 5

## 📁 Project Structure

```
~/public_html/
├── HW4/                          # Frontend static website (user interface)
│   ├── index.html                # Homepage with navigation and portal buttons
│   ├── imprint.html              # Imprint & disclaimer page
│   ├── style.css                 # Frontend styling + dark mode toggle
│   └── img/UACS_logo.png         # Website logo
│
├── HW5/                          # Maintenance & database interaction portal
│   ├── maintenance.html          # Admin dashboard
│   ├── insert_user.html/.php     # Add new users
│   ├── insert_room.html/.php     # Add or manage rooms
│   ├── insert_resource.html/.php # Add or manage system resources
│   ├── insert_access_event.html/.php # Log or review access events
│   ├── insert_request.html/.php  # Create or approve access requests
│   ├── site_style.css            # Local styling for maintenance portal
│   └── db_connect.php            # Database connection credentials
│
└── Database (MariaDB)
    ├── UserAccount, Student, Staff, AdminUser, SecurityUser, Visitor
    ├── Resource, Room, SystemRes
    ├── Request, Access_Request, Extension_Request
    ├── Access_Event, Success_Event, Failed_Event
    └── Database name: db_kkonark
```

---

## 🌐 Accessing the System

### 1. Homepage (HW4)
**URL:**  
`http://10.60.36.1/~kkonark/HW4/index.html`

**Features include:**
- Dynamic hero section with navigation buttons.  
- 🌙 **Dark mode toggle**.  
- Buttons linking directly to the admin/maintenance portal (HW5).  
- Links to GitHub and the Imprint page.

### 2. Maintenance Portal (HW5)
**URL:**  
`http://10.60.36.1/~kkonark/HW5/maintenance.html`

| Page | Function |
|------|-----------|
| `insert_user.html/.php` | Add new users (students, staff, admins, visitors). |
| `insert_room.html/.php` | Register rooms by number, building, and capacity. |
| `insert_resource.html/.php` | Create resources (rooms, systems) for linking requests. |
| `insert_request.html/.php` | Make access requests (pending/approved/denied). |
| `insert_access_event.html/.php` | Record successful or failed access events. |

---

## 🗄️ Database Verification (MariaDB)

To verify changes in the database after adding data via the website:

### Step 1 — Connect
```bash
ssh kkonark@clabsql.clamv.constructor.university
mysql -u kkonark -p
USE db_kkonark;
```

### Step 2 — Check data
```sql
SHOW TABLES;
SELECT user_id, full_name, email FROM UserAccount ORDER BY user_id DESC LIMIT 10;
```

### Step 3 — Other sample queries
```sql
SELECT r.resource_id, r.name, room.building, room.room_no
FROM Room room JOIN Resource r ON r.resource_id = room.resource_id;

SELECT req.request_id, req.status, u.full_name, r.name
FROM Request req JOIN UserAccount u ON u.user_id = req.made_by_user_id
JOIN Resource r ON r.resource_id = req.resource_id;

SELECT e.event_id, e.time_stamp, e.outcome, u.full_name, r.name
FROM Access_Event e JOIN UserAccount u ON u.user_id = e.user_id
JOIN Resource r ON r.resource_id = e.resource_id;
```

---

## 🧠 Tech Stack

| Component | Technology |
|------------|-------------|
| **Frontend** | HTML5, CSS3, JavaScript |
| **Backend** | PHP 8+ |
| **Database** | MariaDB |
| **Hosting** | ClamV Apache Server |
| **Version Control** | GitHub |

---

## 🚀 Features

✅ Responsive homepage with animated hero section  
✅ Dark mode toggle  
✅ CRUD operations for all tables  
✅ GitHub-linked project  
✅ Logical database relations with foreign keys  

---

## 🔗 GitHub Repository
**[https://github.com/konark2006/University-Access-Control-System]**

---

## 👥 Team

- **Konark**
- **Sibel**
- **Ashraya**
- **Mubariz**

---

**© 2025 Constructor University — University Access Control System (UACS)**  
_All rights reserved._
