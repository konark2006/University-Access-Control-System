<?php
include 'db_connect.php';
$query = "%" . ($_GET['query'] ?? '') . "%";

$sql = "SELECT user_id, full_name, email FROM UserAccount
        WHERE full_name LIKE ? OR email LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $query, $query);
$stmt->execute();
$res = $stmt->get_result();

echo "<h2>Search Results</h2>";
if ($res->num_rows > 0) {
  echo "<ul>";
  while ($row = $res->fetch_assoc()) {
    echo "<li><a href='detail_user.php?id=" . $row['user_id'] . "'>" .
         htmlspecialchars($row['full_name']) . " (" .
         htmlspecialchars($row['email']) . ")</a></li>";
  }
  echo "</ul>";
} else {
  echo "<p>No matching users found.</p>";
}
$conn->close();
?>