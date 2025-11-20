<?php
require_once "db_connect.php";

$term = isset($_GET['term']) ? $_GET['term'] : '';

$sql = "
    SELECT e.event_id, u.full_name, r.name AS resource_name, e.outcome
    FROM Access_Event e
    LEFT JOIN UserAccount u ON u.user_id = e.user_id
    LEFT JOIN Resource r ON r.resource_id = e.resource_id
    WHERE e.event_id LIKE CONCAT('%', ?, '%')
       OR u.full_name LIKE CONCAT('%', ?, '%')
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $term, $term);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Event Results</title>
  <link rel="stylesheet" href="site_style.css">
</head>
<body>
<h2>Event Search Results</h2>

<?php
if ($result->num_rows === 0) {
    echo "<p>No events found.</p>";
} else {
    echo "<table border='1' cellpadding='10'>
            <tr><th>Event ID</th><th>User</th><th>Resource</th><th>Outcome</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['event_id']}</td>
                <td>{$row['full_name']}</td>
                <td>{$row['resource_name']}</td>
                <td>{$row['outcome']}</td>
              </tr>";
    }
    echo "</table>";
}

$stmt->close();
$conn->close();
?>
</body>
</html>