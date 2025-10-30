<?php
// COPY this to db_connect.php on the server and fill the real password there.
$servername = "localhost";
$username   = "kkonark";
$password   = "p8vnJQoKySIwx2EL";
$dbname     = "db_kkonark";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  http_response_code(500);
  die("Database connection failed.");
}
$conn->set_charset("utf8mb4");