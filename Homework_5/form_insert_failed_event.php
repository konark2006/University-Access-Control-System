<?php
require_once "auth_check.php";
require_once "db_connect.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Failed Event — UACS</title>
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

  <h1>Add Failed Event</h1>
  <form method="post" action="insert_failed_event.php">
    <label>Event ID:</label>
    <input type="number" name="event_id" required><br>
    <label>Failure Reason:</label>
    <input type="text" name="fail_reason" required><br>
    <button type="submit">Add Failed Event</button>
  </form>

  <footer><a href="maintenance.php">⬅ Back to Dashboard</a></footer>
</body>
</html>