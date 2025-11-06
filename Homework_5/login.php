<?php
session_start();
require_once "db_connect.php";

// If already logged in, skip login page
if (isset($_SESSION["admin"])) {
    header("Location: maintenance.html");
    exit();
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = trim($_POST["username"]);
    $pass = $_POST["password"];

    $stmt = $conn->prepare("SELECT password_hash FROM admin_users WHERE username=?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hash);
        $stmt->fetch();

        if (password_verify($pass, $hash)) {
            $_SESSION["admin"] = $user;
            header("Location: maintenance.html");  // ✅ Redirect to maintenance
            exit();
        } else {
            $error = "❌ Invalid password.";
        }
    } else {
        $error = "❌ Username not found.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>UACS Admin Login</title>
  <style>
    body {
      font-family: "Segoe UI", sans-serif;
      background: #f5f7fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .login-box {
      background: white;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      width: 300px;
      text-align: center;
    }
    h2 { color: #0B3D91; margin-bottom: 20px; }
    input {
      width: 90%;
      padding: 10px;
      margin: 8px 0;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    button {
      background: #0B3D91;
      color: white;
      border: none;
      padding: 10px 16px;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 10px;
      font-weight: bold;
      width: 100%;
    }
    button:hover { background: #09306d; }
    .error { color: red; margin-bottom: 10px; }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>🔒 Admin Login</h2>
    <?php if (isset($error)): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
      <input type="text" name="username" placeholder="Username" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>