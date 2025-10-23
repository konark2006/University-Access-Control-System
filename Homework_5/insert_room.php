<?php
include 'db_connect.php';
$rid = $_POST['resource_id'];
$building = $_POST['building'];
$room_no = $_POST['room_no'];
$capacity = $_POST['capacity'];

$sql = "INSERT INTO Room(resource_id, building, room_no, capacity) VALUES ('$rid', '$building', '$room_no', '$capacity')";
if ($conn->query($sql)) echo "✅ Room added!";
else echo "❌ Error: " . $conn->error;
$conn->close();
?>
<a href='maintenance.html'>Back</a>