<?php
require_once "db_connect.php";

$name = isset($_GET['name']) ? $_GET['name'] : '';

$sql = "
    SELECT resource_id, name, relation
    FROM Resource
    WHERE name LIKE CONCAT('%', ?, '%')
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $name);
$stmt->execute();

$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Resource Search Results</title>
  <link rel="stylesheet" href="site_style.css">
</head>
<body>
<h2>Resource Search Results</h2>

<?php
if ($result->num_rows === 0) {
    echo "<p>No resources found.</p>";
} else {
    echo "<table border='1' cellpadding='10'>
            <tr><th>ID</th><th>Name</th><th>Relation</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['resource_id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['relation']}</td>
              </tr>";
    }
    echo "</table>";
}

$stmt->close();
$conn->close();
?>
</body>
</html>