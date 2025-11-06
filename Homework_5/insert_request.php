<?php
require_once "auth_check.php";
include 'db_connect.php';

$user = $_POST['made_by_user_id'];
$resource = $_POST['resource_id'];
$status = $_POST['status'];

$sql = "INSERT INTO Request(made_by_user_id, resource_id, status) VALUES ('$user', '$resource', '$status')";
if ($conn->query($sql)) echo "✅ Request added!";
else echo "❌ Error: " . $conn->error;

$conn->close();
?>
<br>
<a href='maintenance.php'>Back</a>