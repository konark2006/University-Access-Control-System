<?php
include 'db_connect.php';
$name = "%" . ($_GET['name'] ?? '') . "%";

$sql = "SELECT resource_id, name FROM Resource WHERE name LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $name);
$stmt->execute();
$res = $stmt->get_result();

echo "<h2>Resource Results</h2>";
if ($res->num_rows > 0) {
  echo "<ul>";
  while ($row = $res->fetch_assoc()) {
    echo "<li><a href='detail_resource.php?id=" . $row['resource_id'] . "'>"
         . htmlspecialchars($row['name']) . "</a></li>";
  }
  echo "</ul>";
} else {
  echo "<p>No resources found.</p>";
}
$conn->close();
?>