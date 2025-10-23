<?php
include 'db_connect.php';
$rid = $_POST['resource_id'];
$host = $_POST['hostname'];

$sql = "INSERT INTO SystemRes(resource_id, hostname) VALUES ('$rid', '$host')";
if ($conn->query($sql)) echo "✅ System Resource added!";
else echo "❌ Error: " . $conn->error;
$conn->close();
?>
<a href='maintenance.html'>Back</a>