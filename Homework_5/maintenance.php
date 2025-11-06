<?php
require_once "auth_check.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>UACS Maintenance Dashboard</title>
  <link rel="stylesheet" href="./site_style.css?v=11" />
  <style>
    .uacs-topbar{
      position:sticky; top:0; z-index:9999;
      background:#0b1728; border-bottom:1px solid rgba(255,255,255,.08);
    }
    .uacs-nav{
      max-width:1100px; margin:0 auto; padding:10px 16px;
      display:flex; gap:10px; align-items:center; flex-wrap:wrap;
    }
    .uacs-nav a{
      color:#e2e8f0; text-decoration:none; font:600 14px/1.2 system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
      padding:8px 12px; border-radius:10px; transition:background .2s, color .2s, transform .1s;
    }
    .uacs-nav a:hover{ background:rgba(255,255,255,.08); transform:translateY(-1px); }
    .uacs-nav .logo{ margin-right:auto; font-weight:800; letter-spacing:.3px; background:transparent; }
    @media (prefers-color-scheme: light){
      .uacs-topbar{ background:#ffffff; border-bottom:1px solid #e5e7eb; }
      .uacs-nav a{ color:#111827; }
      .uacs-nav a:hover{ background:#f3f4f6; }
    }
  </style>
</head>

<body>
  <!-- ✅ Global Navigation -->
  <header class="uacs-topbar">
    <nav class="uacs-nav">
      <a class="logo" href="../">UACS</a>
      <a href="../HW4/index.html">Requests UI</a>
      <a href="./maintenance.php">Admin / Maintenance</a>
      <a href="../HW6/index.html">Search</a>
      <a href="logout.php" style="color:#ef4444;font-weight:bold;">Logout</a>
    </nav>
  </header>

  <main>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION["admin"]); ?> 👋</h1>
    <h2>UACS Maintenance</h2>
    <ul>
      <li><a href="insert_user.html">Add User</a></li>
      <li><a href="insert_resource.html">Add Resource</a></li>
      <li><a href="insert_request.html">Add Request</a></li>
      <li><a href="insert_access_event.html">Add Access Event</a></li>
      <li><a href="insert_room.html">Add Room</a></li>
      <li><a href="insert_systemres.html">Add System Resource</a></li>
      <li><a href="insert_success_event.html">Add Success Event</a></li>
      <li><a href="insert_failed_event.html">Add Failed Event</a></li>
    </ul>
  </main>

  <footer>
    <p>© 2025 UACS Team | Homework 5 (Security II)</p>
  </footer>
</body>
</html>