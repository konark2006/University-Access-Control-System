<?php
session_start();

// Destroy all session data securely
$_SESSION = [];
session_unset();
session_destroy();

// Redirect with confirmation
header("Location: /~kkonark/HW5/login.php?msg=loggedout");
exit();
?>