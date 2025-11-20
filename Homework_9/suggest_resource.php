<?php
require_once "db_connect.php";

$q = $_GET["term"] ?? "";

$stmt = $conn->prepare("
  SELECT name 
  FROM Resource
  WHERE name LIKE CONCAT('%', ?, '%')
  LIMIT 10
");
$stmt->bind_param("s", $q);
$stmt->execute();
$stmt->bind_result($result);

$suggestions = [];
while ($stmt->fetch()) {
    $suggestions[] = $result;
}

echo json_encode($suggestions);
?>