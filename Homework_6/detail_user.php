<?php
include 'db_connect.php';
$id = $_GET['id'] ?? 0;

$sql = "SELECT * FROM UserAccount WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
  echo "<h2>User Details</h2>";
  echo "<p><b>ID:</b> {$row['user_id']}</p>";
  echo "<p><b>Name:</b> {$row['full_name']}</p>";
  echo "<p><b>Email:</b> {$row['email']}</p>";
} else {
  echo "<p>User not found.</p>";
}
$conn->close();
?>