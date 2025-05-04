<?php
require_once '../config/db.php';

// Initialize variables for error/success messages
$error_message = $success_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    if (empty($email)) {
        $error_message = "Please enter your email.";
        } else {
        // Prepare query to check if the email exists
        $query = "SELECT UserID FROM users WHERE Email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // Generate a secure password reset token
            $reset_token = bin2hex(random_bytes(32));
            $expiry_time = date("Y-m-d H:i:s", strtotime("+1 hour"));

            // Save the token and expiry to a temporary password reset table
            $user = $result->fetch_assoc();
            $insert_query = "INSERT INTO password_resets (UserID, Token, Expiry) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("iss", $user['UserID'], $reset_token, $expiry_time);
            $insert_stmt->execute();

            // Send the reset email (Replace with actual email sending logic)
            $reset_link = "http://yourwebsite.com/reset_password.php?token=" . urlencode($reset_token);
            // Placeholder for email sending
            mail($email, "Password Reset Request", "Click the link to reset your password: $reset_link");

            $success_message = "A password reset link has been sent to your email. Please check your inbox.";
            } else {
            $error_message = "Email not found. Please check and try again.";
            }

        $stmt->close();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../css/forgotpw.css">
</head>

<body>
    <div class="container">
        <h1>Forgot Password</h1>
        <p>Enter your email to receive a password reset link.</p>

        <!-- Error or Success Messages -->
        <?php if ($error_message): ?>
            <div class="error-message"><?= htmlspecialchars($error_message); ?></div>
        <?php elseif ($success_message): ?>
            <div class="success-message"><?= htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" action="forgotpw.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
            </div>
            <button type="submit" class="submit-btn">Send Reset Link</button>
        </form>
    </div>
</body>

</html>