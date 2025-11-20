<?php
require_once "db_connect.php";

$q = $_GET["term"] ?? "";

$suggestions = [];

/* Search by Event ID */
$stmt = $conn->prepare("
  SELECT CONCAT('Event #', event_id)
  FROM Access_Event
  WHERE event_id LIKE CONCAT('%', ?, '%')
  LIMIT 10
");
$stmt->bind_param("s", $q);
$stmt->execute();
$stmt->bind_result($res1);
while ($stmt->fetch()) $suggestions[] = $res1;

/* Search by User */
$stmt = $conn->prepare("
  SELECT CONCAT('User ', user_id)
  FROM Access_Event
  WHERE user_id LIKE CONCAT('%', ?, '%')
  LIMIT 10
");
$stmt->bind_param("s", $q);
$stmt->execute();
$stmt->bind_result($res2);
while ($stmt->fetch()) $suggestions[] = $res2;

/* Search by Resource */
$stmt = $conn->prepare("
  SELECT CONCAT('Resource ', resource_id)
  FROM Access_Event
  WHERE resource_id LIKE CONCAT('%', ?, '%')
  LIMIT 10
");
$stmt->bind_param("s", $q);
$stmt->execute();
$stmt->bind_result($res3);
while ($stmt->fetch()) $suggestions[] = $res3;

echo json_encode($suggestions);
?>