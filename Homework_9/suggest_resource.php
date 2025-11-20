<?php
header("Content-Type: application/json");
require_once "../Homework_6/db_connect.php";

$term = $_GET["term"] ?? "";

$sql = "
    SELECT name
    FROM Resource
    WHERE name LIKE CONCAT('%', ?, '%')
    LIMIT 10
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $term);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row["name"];
}

echo json_encode($data);
?>