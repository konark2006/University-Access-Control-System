<?php
require_once "db_connect.php";

$q = $_GET["term"] ?? "";

$stmt = $conn->prepare("
  SELECT CONCAT(name, ' (', email, ')') 
  FROM User
  WHERE name LIKE CONCAT('%', ?, '%')
     OR email LIKE CONCAT('%', ?, '%')
  LIMIT 10
");
$stmt->bind_param("ss", $q, $q);
$stmt->execute();
$stmt->bind_result($result);

$suggestions = [];
while ($stmt->fetch()) {
    $suggestions[] = $result;
}

echo json_encode($suggestions);
?>