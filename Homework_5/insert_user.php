<?php
require_once "auth_check.php";
include 'db_connect.php';

$name = $_POST['name'];
$email = $_POST['email'];
$role = $_POST['role'];

$sql = "INSERT INTO User(name, email, role) VALUES ('$name', '$email', '$role')";
if ($conn->query($sql)) echo "✅ User added!";
else echo "❌ Error: " . $conn->error;

$conn->close();
?>
<br>
<a href='maintenance.php'>Back</a>