<?php
require_once "auth_check.php";
require_once "db_connect.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Access Event — UACS</title>
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

  <h1>Add Access Event</h1>
  <form method="post" action="insert_access_event.php">
    <label>User ID:</label>
    <input type="number" name="user_id" required><br>
    <label>Resource ID:</label>
    <input type="number" name="resource_id" required><br>
    <label>Outcome (Success/Fail):</label>
    <input type="text" name="outcome" required><br>
    <button type="submit">Add Access Event</button>
  </form>

  <footer><a href="maintenance.php">⬅ Back to Dashboard</a></footer>
</body>
</html>