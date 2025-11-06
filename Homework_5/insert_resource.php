<?php
require_once "auth_check.php";
include 'db_connect.php';

$name = $_POST['name'];
$relation = $_POST['relation'];

$sql = "INSERT INTO Resource(name, relation) VALUES ('$name', '$relation')";
if ($conn->query($sql)) echo "✅ Resource added!";
else echo "❌ Error: " . $conn->error;

$conn->close();
?>
<br>
<a href='maintenance.php'>Back</a>