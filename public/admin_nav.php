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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin_nav.css">
</head>

<body>
    <div class="admin-sidebar">

        <ul>
            <li>
                <a href="admin.php" class="<?= $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="view_book_requests.php"
                    class="<?= $current_page == 'view_book_requests.php' ? 'active' : ''; ?>">
                    View Book Requests
                </a>
            </li>
            <li>
                <a href="add_book.php" class="<?= $current_page == 'add_book.php' ? 'active' : ''; ?>">
                    Add New Book
                </a>
            </li>
            <li>
                <a href="manage_book.php" class="<?= $current_page == 'manage_book.php' ? 'active' : ''; ?>">
                    Manage Books
                </a>
            </li>

        </ul>
    </div>
</body>

</html>