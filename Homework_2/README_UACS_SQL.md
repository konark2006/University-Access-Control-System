# University Access Control System — SQL Setup Guide

This README shows how to run the MySQL schema from a `.sql` file, seed optional demo data, and inspect the database schema (tables, columns, keys). Works on macOS, Windows, and Linux.

---

## 0) Prerequisites

- **MySQL 8+** installed and running
  - macOS: Install *MySQL Community Server* from dev.mysql.com or `brew install mysql`
  - Windows: Install *MySQL Community Server* and ensure **MySQL Server** service is running
  - Linux (Debian/Ubuntu): `sudo apt-get install mysql-server`
- You know your MySQL user & password (often `root`)
- You have the project folder with:
  - `code.sql` (schema script) — required
  - `seed.sql` (optional, demo rows) — optional

> Tip (macOS): Start/stop MySQL from **System Settings → MySQL** or `brew services start mysql`.

---

## 1) Open a terminal **in the project folder**

For example, if your files are in `University-Access-Control-System/Homework_2`:

```bash
cd /path/to/University-Access-Control-System/Homework_2
ls
# you should see: code.sql (and optionally seed.sql)
```

---

## 2) Enter the MySQL shell

```bash
mysql -u root -p
# enter your password when prompted
```

You should now see the prompt: `mysql>`

---

## 3) Run the schema

From inside the **MySQL shell** (`mysql>`), run:

```sql
SOURCE code.sql;
```

If the file is not found, provide the absolute path, e.g.:

```sql
SOURCE /Users/yourname/University-Access-Control-System/Homework_2/code.sql;
```

This will:
- Create/select the database (e.g., `database_project` or backticked names with spaces)
- Drop old tables in FK-safe order
- Recreate all tables, constraints, and enums

> If your DB name contains spaces, always wrap it in backticks, e.g. ``USE `DATABASE PROJECT`;``

---

## 4) (Optional) Seed demo data

If you have a `seed.sql` file and want a quick smoke test:

```sql
SOURCE seed.sql;
-- or with absolute path:
-- SOURCE /absolute/path/to/seed.sql;
```

---

## 5) Verify the database exists and switch into it

```sql
SHOW DATABASES;
-- If your script created a DB named database_project:
USE database_project;
-- If your script used a name with spaces:
-- USE `DATABASE PROJECT`;
```

---

## 6) Inspect the schema (tables & structure)

List all tables:
```sql
SHOW TABLES;
```

Describe one table’s columns and types:
```sql
DESCRIBE UserAccount;
-- or
SHOW COLUMNS FROM UserAccount;
```

Show the full CREATE statement (includes PKs/FKs/engine/charset):
```sql
SHOW CREATE TABLE UserAccount\G
```

Dump the entire database **structure only** (no data) to a file (run **outside** mysql shell):
```bash
mysqldump -u root -p --no-data database_project > schema_dump.sql
```

---

## 7) Common issues & quick fixes

- **ERROR 1049 (Unknown database)**  
  → Run the schema first (Step 3). The script creates the database.

- **File not found when using `SOURCE code.sql;`**  
  → Use an **absolute path** to the file. The mysql shell runs relative to its own working directory, not necessarily your project folder.

- **Access denied / Authentication issues**  
  → Ensure the MySQL server is running and you’re using the correct user/password.

- **Database name has spaces**  
  → Always wrap in backticks: ``USE `DATABASE PROJECT`;``

- **Foreign key errors on re-run**  
  → This schema already sets `SET FOREIGN_KEY_CHECKS = 0` and drops tables in the correct order. If you changed the file, ensure drop order still matches FK dependencies.

---

## 8) One-liners (alternative ways)

Run the schema **without** entering the mysql shell:
```bash
mysql -u root -p < code.sql
```

Run schema **and** seed back-to-back:
```bash
mysql -u root -p < code.sql && mysql -u root -p < seed.sql
```

---

## 9) Useful references (optional)

- Start MySQL (macOS Homebrew): `brew services start mysql`
- Stop MySQL (macOS Homebrew): `brew services stop mysql`
- Exit the mysql shell: `exit;` or press `Ctrl+D`

---

## 10) Quick sanity checks

After running the schema and switching into your DB:
```sql
SHOW TABLES;
SELECT COUNT(*) FROM UserAccount;  -- if you seeded data
SHOW CREATE TABLE Request\G
```

If those commands return results without errors, your setup is correct ✅

---

**Done!** You can now run queries against your database, insert data, and explore the schema.
