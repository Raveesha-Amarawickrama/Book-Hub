<?php
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $membershipType = "Free";

    $sql = "INSERT INTO users (Username, Email, Password, MembershipType) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $password, $membershipType);

    if ($stmt->execute()) {
        // Redirect to login page upon successful registration
        header("Location: login.php");
        exit;
        } else {
        $error = "An error occurred: " . $conn->error;
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
    <title>Register</title>
    <link rel="stylesheet" href="../css/register.css">
</head>

<body>
    <div class="auth-container">
        <h2>Register</h2>
        <?php if (isset($error))
            echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="register.php">
            <input type="text" name="username" required placeholder="Username">
            <input type="email" name="email" required placeholder="Email">
            <div class="password-container">
                <input type="password" name="password" id="password" required placeholder="Password">
                <span class="toggle-password" onclick="togglePasswordVisibility()">👁️</span>
                <button type="submit" class="btn-primary">Register</button>
                <a href="login.php">Sign in</a>
            </div>

        </form>
    </div>
    <script src="../js/login.js"></script>
</body>

</html>