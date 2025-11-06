<?php
require_once "auth_check.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add User — UACS</title>
  <link rel="stylesheet" href="site_style.css?v=14">
</head>
<body>
  <header>
    <h2>Add User</h2>
  </header>

  <form method="post" action="insert_user.php">
    <label>User ID: <input type="text" name="user_id" required></label><br>
    <label>Name: <input type="text" name="name" required></label><br>
    <label>Role: <input type="text" name="role" required></label><br>
    <button type="submit">Add User</button>
  </form>

  <a href="maintenance.php">← Back to Dashboard</a>
</body>
</html>