<?php
require_once "auth_check.php"; // ✅ Protects page, requires login
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>UACS Admin Dashboard — Homework 5</title>
  <link rel="stylesheet" href="./site_style.css?v=13" />

  <style>
    /* ===== UNIVERSAL NAVBAR ===== */
    .uacs-topbar {
      position: sticky;
      top: 0;
      z-index: 9999;
      background: #0b1728;
      border-bottom: 1px solid rgba(255,255,255,.08);
    }
    .uacs-nav {
      max-width: 1100px;
      margin: 0 auto;
      padding: 10px 16px;
      display: flex;
      gap: 10px;
      align-items: center;
      flex-wrap: wrap;
    }
    .uacs-nav a {
      color: #e2e8f0;
      text-decoration: none;
      font: 600 14px/1.2 system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
      padding: 8px 12px;
      border-radius: 10px;
      transition: background .2s, color .2s, transform .1s;
    }
    .uacs-nav a:hover {
      background: rgba(255,255,255,.08);
      transform: translateY(-1px);
    }
    .uacs-nav .logo {
      margin-right: auto;
      font-weight: 800;
      letter-spacing: .3px;
    }
    @media (prefers-color-scheme: light) {
      .uacs-topbar { background: #ffffff; border-bottom: 1px solid #e5e7eb; }
      .uacs-nav a { color: #111827; }
      .uacs-nav a:hover { background: #f3f4f6; }
    }

    /* ===== DASHBOARD ===== */
    body {
      font-family: "Segoe UI", sans-serif;
      background: #f6f8fb;
      color: #111;
      margin: 0;
      line-height: 1.6;
      transition: background 0.4s ease, color 0.4s ease;
    }
    main {
      max-width: 900px;
      margin: 50px auto;
      padding: 0 16px;
    }
    h1 {
      font-size: 2rem;
      color: #0b1728;
      margin-bottom: 0.4em;
    }
    h2 {
      color: #0b3d91;
      margin-top: 1em;
      margin-bottom: 0.6em;
    }
    p.welcome {
      font-size: 1.05rem;
      color: #333;
      margin-bottom: 1.5em;
    }

    /* Entity links grid */
    .entity-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 1.5rem;
    }
    .card {
      background: #fff;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
      transition: all 0.3s ease;
      border-top: 4px solid #007bff;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    .card h3 {
      color: #0052d4;
      margin-bottom: 0.6rem;
    }
    .card p {
      font-size: 0.95rem;
      margin-bottom: 1rem;
      color: #333;
    }
    .card a {
      display: inline-block;
      background: #007bff;
      color: #fff;
      padding: 8px 14px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      transition: background 0.2s, transform 0.2s;
    }
    .card a:hover {
      background: #0052d4;
      transform: translateY(-2px);
    }

    footer {
      text-align: center;
      padding: 2rem 1rem;
      color: #555;
      border-top: 1px solid #e5e7eb;
      margin-top: 40px;
      font-size: 0.9rem;
    }

    /* ===== DARK MODE ===== */
    body.dark-mode {
      background: #0b1728;
      color: #e2e8f0;
    }
    body.dark-mode .card {
      background: #1e293b;
      color: #e2e8f0;
      box-shadow: 0 6px 15px rgba(0,0,0,0.5);
    }
    body.dark-mode .uacs-topbar {
      background: #111b2b;
    }
    body.dark-mode footer {
      background: #111b2b;
      color: #bbb;
      border-top: 1px solid #333;
    }

    #theme-toggle {
      border: none;
      background: none;
      font-size: 1.4rem;
      cursor: pointer;
      color: #e2e8f0;
      margin-left: auto;
    }
    body.dark-mode #theme-toggle { color: #f1f5f9; }
  </style>
</head>

<body>
  <!-- ===== UNIVERSAL NAV ===== -->
  <header class="uacs-topbar">
    <nav class="uacs-nav">
      <a class="logo" href="/~kkonark/">UACS</a>
      <a href="/~kkonark/HW4/index.php">📘 Access Portal</a>
      <a href="/~kkonark/HW5/maintenance.php" class="active">🧰 Maintenance</a>
      <a href="/~kkonark/HW6/index.php">🔍 Search</a>
      <a href="/~kkonark/HW5/logout.php" style="color:#ef4444;font-weight:bold;">🚪 Logout</a>
      <button id="theme-toggle" aria-label="Toggle dark mode">🌙</button>
    </nav>
  </header>

  <!-- ===== DASHBOARD CONTENT ===== -->
  <main>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION["admin"]); ?> 👋</h1>
    <p class="welcome">
      You are logged in as an <strong>Administrator</strong>.  
      Use the dashboard below to manage university access control data.
    </p>

    <h2>Entity Management</h2>
    <div class="entity-grid">
      <div class="card">
        <h3>👤 Users</h3>
        <p>Add or update user details including students, staff, or visitors.</p>
        <a href="insert_user.php">Manage Users →</a>
      </div>

      <div class="card">
        <h3>💻 Resources</h3>
        <p>Create new system or room resources available for access requests.</p>
        <a href="insert_resource.php">Add Resource →</a>
      </div>

      <div class="card">
        <h3>📩 Requests</h3>
        <p>Monitor or create access requests between users and resources.</p>
        <a href="insert_request.php">Add Request →</a>
      </div>

      <div class="card">
        <h3>🧾 Access Events</h3>
        <p>Record successful and failed login attempts and room access data.</p>
        <a href="insert_access_event.php">Add Event →</a>
      </div>

      <div class="card">
        <h3>🏢 Rooms</h3>
        <p>Manage physical rooms, capacity, and building details.</p>
        <a href="insert_room.php">Add Room →</a>
      </div>

      <div class="card">
        <h3>🖥️ System Resources</h3>
        <p>Link resources to host systems or networked infrastructure.</p>
        <a href="insert_systemres.php">Add System Resource →</a>
      </div>

      <div class="card">
        <h3>✅ Success Events</h3>
        <p>Record validated access events for audit and reporting.</p>
        <a href="insert_success_event.php">Add Success Event →</a>
      </div>

      <div class="card">
        <h3>❌ Failed Events</h3>
        <p>Log rejected access attempts for security review.</p>
        <a href="insert_failed_event.php">Add Failed Event →</a>
      </div>
    </div>
  </main>

  <footer>
    <p>© 2025 UACS Team | Homework 5 (Security II) | <a href="/~kkonark/HW6/index.html">Search Portal</a></p>
  </footer>

  <!-- ===== DARK MODE SCRIPT ===== -->
  <script>
    const toggle = document.getElementById("theme-toggle");
    toggle.addEventListener("click", () => {
      document.body.classList.toggle("dark-mode");
      toggle.textContent = document.body.classList.contains("dark-mode") ? "☀️" : "🌙";
    });
  </script>
</body>
</html>