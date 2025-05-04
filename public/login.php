<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['username'] = $user['Username'];

            $_SESSION['membership_type'] = $user['MembershipType'];

            // Redirect to the main page after login
            header("Location: index.php");
            exit;
            } else {
            $error = "Invalid credentials. Please try again.";
            }
        } else {
        $error = "Invalid credentials. Please try again.";
        }

    $stmt->close();
    $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <div class="auth-container">
        <h2>Login</h2>
        <?php if (isset($error))
            echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="login.php">
            <input type="email" name="email" required placeholder="Email">
            <div class="password-container">
                <input type="password" name="password" id="password" required placeholder="Password">
                <span class="toggle-password" onclick="togglePasswordVisibility()">👁️</span>
            </div>
            <button type="submit" class="btn-primary">Login</button>
        </form>
        <div class="auth-links">
            <a href="register.php">Create an account</a>
        </div>
    </div>
    <script src="../js/login.js"></script>
</body>

</html>