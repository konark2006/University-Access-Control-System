<?php
// ------------------------------------------------------
//  auth_check.php
//  Reusable session guard for all admin-only pages
// ------------------------------------------------------

session_start();

// If no admin session exists → redirect to login
if (!isset($_SESSION["admin"])) {
    header("Location: login.php?error=unauthorized");
    exit;
}
?>