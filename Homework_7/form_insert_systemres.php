<?php
require_once "auth_check.php";
require_once "db_connect.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add System Resource — UACS</title>
  <link rel="stylesheet" href="style.css?v=15">
</head>
<body>
  <header class="uacs-topbar">
    <nav class="uacs-nav">
      <a class="logo" href="/~kkonark/">UACS</a>
      <a href="/~kkonark/HW5/maintenance.php">🧰 Dashboard</a>
      <a href="/~kkonark/HW5/logout.php" style="color:#ff4d4d;">🚪 Logout</a>
    </nav>
  </header>

  <h1>Add System Resource</h1>
  <form method="post" action="insert_systemres.php">
    <label>Resource ID:</label>
    <input type="number" name="resource_id" required><br>
    <label>Hostname:</label>
    <input type="text" name="hostname" required><br>
    <button type="submit">Add System Resource</button>
  </form>

  <footer><a href="maintenance.php">⬅ Back to Dashboard</a></footer>
</body>
</html>