<?php
include 'db_connect.php';
$id = $_GET['id'] ?? 0;

$sql = "SELECT * FROM Resource WHERE resource_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
  echo "<h2>Resource Details</h2>";
  echo "<p><b>ID:</b> " . $row['resource_id'] . "</p>";
  echo "<p><b>Name:</b> " . htmlspecialchars($row['name']) . "</p>";
} else {
  echo "<p>Resource not found.</p>";
}
$conn->close();
?>