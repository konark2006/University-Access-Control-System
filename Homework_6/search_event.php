<?php
include 'db_connect.php';
$outcome = $_GET['outcome'] ?? '';

if ($outcome) {
  $sql = "SELECT event_id, time_stamp, outcome FROM Access_Event WHERE outcome = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $outcome);
} else {
  $sql = "SELECT event_id, time_stamp, outcome FROM Access_Event";
  $stmt = $conn->prepare($sql);
}

$stmt->execute();
$res = $stmt->get_result();

echo "<h2>Event Results</h2>";
if ($res->num_rows > 0) {
  echo "<ul>";
  while ($row = $res->fetch_assoc()) {
    echo "<li><a href='detail_event.php?id=" . $row['event_id'] . "'>"
         . "Event #" . $row['event_id'] . " — " . $row['outcome'] . " @ " . $row['time_stamp'] . "</a></li>";
  }
  echo "</ul>";
} else {
  echo "<p>No events found.</p>";
}
$conn->close();
?>