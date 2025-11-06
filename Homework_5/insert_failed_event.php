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
<br>
<a href='maintenance.php'>Back</a>