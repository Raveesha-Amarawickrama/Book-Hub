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

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin.css">
    <script src="../js/script.js" defer></script>
</head>

<body>

    <div class="admin-container">
        <?php require_once 'admin_nav.php'; ?>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="stats">
                <div class="dash_ico">
                    <img src="../assets/bookicon.png" alt="" width="70%">
                    <h2>Total Books: <?= $total_books; ?></h2>
                </div>
                <div class="dash_ico">
                    <img src="../assets/bookreq.png" alt="" width="50%" style="margin-top:10%; margin-bottom:7%">
                    <h2>Total Pending Book Requests: <?= $total_pending_requests; ?></h2>
                </div>
            </div>


        </main>
    </div>
</body>

</html>