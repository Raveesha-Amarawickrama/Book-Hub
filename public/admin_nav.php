<?php
// Ensure the session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }
require_once 'expairy_check.php';
// Check if user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['membership_type'] != 'admin') {
    header("Location: login.php");
    exit;
    }

// Get the current page for active tab highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
