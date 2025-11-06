<?php
require_once "auth_check.php";
include 'db_connect.php';
$eid = $_POST['event_id'];
$reason = $_POST['fail_reason'];
$sql = "INSERT INTO Failed_Event(event_id, fail_reason) VALUES ('$eid', '$reason')";
if ($conn->query($sql)) echo "✅ Failed Event added!";
else echo "❌ Error: " . $conn->error;
$conn->close();
?>
<a href='maintenance.html'>Back</a>