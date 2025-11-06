<?php
require_once "auth_check.php";
require_once "db_connect.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Room — UACS</title>
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

  <h1>Add Room</h1>
  <form method="post" action="insert_room.php">
    <label>Resource ID:</label>
    <input type="number" name="resource_id" required><br>
    <label>Building:</label>
    <input type="text" name="building" required><br>
    <label>Room No:</label>
    <input type="text" name="room_no" required><br>
    <label>Capacity:</label>
    <input type="number" name="capacity" required><br>
    <button type="submit">Add Room</button>
  </form>

  <footer><a href="maintenance.php">⬅ Back to Dashboard</a></footer>
</body>
</html>