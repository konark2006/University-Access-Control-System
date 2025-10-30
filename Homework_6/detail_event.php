<?php
include 'db_connect.php';
$id = $_GET['id'] ?? 0;

$sql = "SELECT * FROM Access_Event WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
  echo "<h2>Event Details</h2>";
  foreach ($row as $key => $val) {
    echo "<p><b>$key:</b> " . htmlspecialchars($val) . "</p>";
  }
} else {
  echo "<p>Event not found.</p>";
}
$conn->close();
?>