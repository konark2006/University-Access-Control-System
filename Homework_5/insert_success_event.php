<?php
session_start();
if (!isset($_SESSION["admin"])) {
    // Not logged in → redirect to login page
    header("Location: login.php?error=unauthorized");
    exit;
}
require_once "auth_check.php";
include 'db_connect.php';
$eid = $_POST['event_id'];
$sql = "INSERT INTO Success_Event(event_id) VALUES ('$eid')";
if ($conn->query($sql)) echo "✅ Success Event added!";
else echo "❌ Error: " . $conn->error;
$conn->close();
?>
<a href='maintenance.html'>Back</a>