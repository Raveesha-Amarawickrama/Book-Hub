<?php
require_once '../config/db.php';
require_once '../includes/header.php';


// Make sure the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['membership_type'] != 'admin') {
    header("Location: login.php");
    exit;
    }
// Fetch total number of books
$total_books_query = "SELECT COUNT(*) AS total_books FROM books";
$total_books_result = $conn->query($total_books_query);
$total_books = $total_books_result->fetch_assoc()['total_books'];

// Fetch total number of pending book requests
$pending_requests_query = "SELECT COUNT(*) AS total_pending_requests FROM bookrequest WHERE Status = 'Pending'";
$pending_requests_result = $conn->query($pending_requests_query);
$total_pending_requests = $pending_requests_result->fetch_assoc()['total_pending_requests'];

?>
