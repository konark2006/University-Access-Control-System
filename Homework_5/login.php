<?php
session_start();
require_once "db_connect.php";

// If already logged in, skip login page
if (isset($_SESSION["admin"])) {
    header("Location: /~kkonark/HW5/maintenance.php");
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
            header("Location: /~kkonark/HW5/maintenance.php");
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
      background: linear-gradient(135deg, #0b1728, #1e3a8a);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      color: #fff;
    }
    .login-box {
      background: #ffffff;
      padding: 35px 40px;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      width: 340px;
      text-align: center;
      color: #111;
    }
    h2 {
      color: #0B3D91;
      margin-bottom: 20px;
      font-size: 1.6rem;
    }
    input {
      width: 90%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 1rem;
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
      font-size: 1rem;
    }
    button:hover { background: #09306d; }
    .error { color: red; margin-bottom: 10px; }
    .message { color: green; margin-bottom: 10px; }
    footer {
      margin-top: 15px;
      font-size: 0.9rem;
    }
    footer a {
      color: #0b3d91;
      text-decoration: none;
      font-weight: 600;
    }
    footer a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>🔒 Admin Login</h2>

    <?php if (isset($error)): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (isset($_GET["msg"]) && $_GET["msg"] === "loggedout"): ?>
      <p class="message">✅ You have logged out successfully.</p>
    <?php endif; ?>

    <?php if (isset($_GET["error"]) && $_GET["error"] === "unauthorized"): ?>
      <p class="error">⚠️ Please log in to access that page.</p>
    <?php endif; ?>

    <form method="post">
      <input type="text" name="username" placeholder="Username" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit">Login</button>
    </form>

    <footer>
      <p>← <a href="/~kkonark/HW4/index.html">Back to UACS Portal</a></p>
    </footer>
  </div>
</body>
</html>