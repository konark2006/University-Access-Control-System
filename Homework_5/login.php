<?php
require_once "db_connect.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST["username"];
    $pass = $_POST["password"];

    $stmt = $conn->prepare("SELECT password_hash FROM admin_users WHERE username=?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->bind_result($hash);

    if ($stmt->fetch() && password_verify($pass, $hash)) {
        $_SESSION["admin"] = $user;
        echo "<p>✅ Login successful</p>";
        // header("Location: maintenance.html");
    } else {
        echo "<p>❌ Invalid username or password</p>";
    }
    $stmt->close();
}
?>
<form method="post">
  <label>Username: <input type="text" name="username"></label><br>
  <label>Password: <input type="password" name="password"></label><br>
  <button type="submit">Login</button>
</form>