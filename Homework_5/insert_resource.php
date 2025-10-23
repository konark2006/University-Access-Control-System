<?php
include 'db_connect.php';
$name = $_POST['name'];
$relation = $_POST['relation'];
$sql = "INSERT INTO Resource(name, relation) VALUES ('$name', '$relation')";
if ($conn->query($sql)) echo "✅ Resource added!";
else echo "❌ Error: " . $conn->error;
$conn->close();
?>
<a href='maintenance.html'>Back</a>