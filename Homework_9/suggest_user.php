<?php
header("Content-Type: application/json");

require_once "../Homework_5/db_connect.php";

$term = isset($_GET['term']) ? $_GET['term'] : '';

$stmt = $conn->prepare("
    SELECT full_name 
    FROM UserAccount
    WHERE full_name LIKE CONCAT('%', ?, '%')
    ORDER BY full_name
    LIMIT 10
");

$stmt->bind_param("s", $term);
$stmt->execute();

$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $suggestions[] = $row["full_name"];
}

echo json_encode($suggestions);
?>