<?php
require_once '../config/db.php';
session_start();

// Check if the book ID is passed
if (!isset($_GET['book_id'])) {
    header("Location: browse_books.php");
    exit;
    }

$book_id = $_GET['book_id'];

// Fetch book details including the Content field (PDF file name)
$book_query = "
    SELECT BookID, Title, Content
    FROM books
    WHERE BookID = ?";

$stmt = $conn->prepare($book_query);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();

if (!$book) {
    echo "Book not found.";
    exit;
    }

// User session variables
$user_id = $_SESSION['user_id'] ?? null;
$membership_type = $_SESSION['membership_type'] ?? null; // Free or Paid

// Determine which file to display based on membership
if ($membership_type === 'Paid' || $membership_type === 'admin') {
    // Use the full PDF for paid members or admin
    $pdf_file = "../assets/books/" . htmlspecialchars($book['Content']);

    // Check if full content is available
    if (empty($book['Content']) || !file_exists($pdf_file)) {
        echo "This book's content is currently unavailable.";
        exit;
        }
    } else {
    // Use the sample PDF for free members
    $pdf_file = "../assets/books/sample_" . htmlspecialchars($book['Content']);

    if (!file_exists($pdf_file)) {
        echo "<p>Preview is unavailable for this book. <a href='get_membership.php'>Upgrade now</a> to access the full content.</p>";
        exit;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read <?= htmlspecialchars($book['Title']); ?></title>
    <link rel="stylesheet" href="../css/read_book.css">
</head>

<body>
    <!-- Display PDF in an iframe -->
    <iframe src="<?= htmlspecialchars($pdf_file); ?>" width="100%" height="750px" style="border: none;"></iframe>
</body>

</html>