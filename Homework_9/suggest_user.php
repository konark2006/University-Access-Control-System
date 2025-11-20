<?php
header("Content-Type: application/json");
require_once "../Homework_5/db_connect.php";   // adjust if needed

$term = isset($_GET["term"]) ? $_GET["term"] : "";

if ($term === "") {
    echo json_encode([]);
    exit;
}

$sql = "
    SELECT full_name 
    FROM UserAccount
    WHERE full_name LIKE CONCAT('%', ?, '%')
       OR email LIKE CONCAT('%', ?, '%')
    LIMIT 10
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $term, $term);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $suggestions[] = $row["full_name"];
}

echo json_encode($suggestions);
?>