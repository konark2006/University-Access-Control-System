<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
header("Content-Type: application/json");
require_once "../HW6/db_connect.php";

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