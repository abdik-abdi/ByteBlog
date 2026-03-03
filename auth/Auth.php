<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session if not already started
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
