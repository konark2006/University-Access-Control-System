<?php
require_once "auth_check.php";
include 'db_connect.php';

$eid = $_POST['event_id'];

$sql = "INSERT INTO Success_Event(event_id) VALUES ('$eid')";
if ($conn->query($sql)) echo "✅ Success Event added!";
else echo "❌ Error: " . $conn->error;

$conn->close();
?>
<br>
<a href='maintenance.php'>Back</a>