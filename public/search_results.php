<?php
require_once '../config/db.php';
require_once '../includes/header.php';

// Check if the search query exists in the URL
if (isset($_GET['query'])) {
    $search_query = "%" . $_GET['query'] . "%";

    // Prepare and execute the SQL query to search for books by title or author
    $query = "SELECT Title, Author, Genre, PublicationDate, Description, CoverImage 
              FROM books 
              WHERE Title LIKE ? OR Author LIKE ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $search_query, $search_query);
    $stmt->execute();
    $result = $stmt->get_result();
    } else {
    echo "No search query provided.";
    exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="../css/search_results.css"> <!-- Link to external CSS -->
    <script src="../js/search_results.js" defer></script> <!-- Link to external JS -->
</head>

<body>
    <div class="container">
        <h1 class="title">Search Results</h1>

        <?php if ($result->num_rows > 0): ?>
            <div class="results-list">
                <?php while ($book = $result->fetch_assoc()): ?>
                    <div class="book-item">
                        <img class="book-cover" src="../assets/cover/<?= htmlspecialchars($book['CoverImage']); ?>"
                            alt="<?= htmlspecialchars($book['Title']); ?>">
                        <div class="book-info">
                            <h2 class="book-title"><?= htmlspecialchars($book['Title']); ?></h2>
                            <p class="book-author">by <?= htmlspecialchars($book['Author']); ?></p>
                            <p class="book-genre">Genre: <?= htmlspecialchars($book['Genre']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="no-results">No books found for your search query. Please try again.</p>
        <?php endif; ?>
    </div>
</body>

</html>