<?php
// COPY this to db_connect.php on the server and fill the real password there.
$servername = "localhost";        // On ClamV this stays 'localhost'
$username   = "kkonark";          // Your MariaDB username
$password   = "REPLACE_ME";       // <-- leave dummy here in the *sample*
$dbname     = "db_kkonark";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  http_response_code(500);
  die("Database connection failed.");
}
$conn->set_charset("utf8mb4");