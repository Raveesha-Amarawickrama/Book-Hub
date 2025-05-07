<?php
session_start();
require_once '../config/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
    }

$user_id = $_SESSION['user_id'];

// Fetch the user's current profile details
$query = "SELECT Username, Email, profileImage, MembershipType, MembershipEndDate FROM users WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Check for different form submissions and handle accordingly
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update Profile Image
    if (isset($_POST['update_image']) && !empty($_FILES['profileImage']['name'])) {
        $target_dir = "../uploads/";

        // Generate a unique file name
        $file_extension = pathinfo($_FILES['profileImage']['name'], PATHINFO_EXTENSION);
        $new_filename = "profile_" . $user_id . "_" . time() . "." . $file_extension;
        $target_file = $target_dir . $new_filename;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $target_file)) {
            // Update profile image path in the database
            $image_update_query = "UPDATE users SET profileImage = ? WHERE UserID = ?";
            $image_stmt = $conn->prepare($image_update_query);
            $image_stmt->bind_param('si', $new_filename, $user_id);
            $image_stmt->execute();
            $_SESSION['success_message'] = "Profile image updated successfully!";
            } else {
            $_SESSION['error_message'] = "Failed to upload profile image.";
            }
        }
        
    // Update Username
    if (isset($_POST['update_username'])) {
        $username = $_POST['username'];
        $username_update_query = "UPDATE users SET Username = ? WHERE UserID = ?";
        $username_stmt = $conn->prepare($username_update_query);
        $username_stmt->bind_param('si', $username, $user_id);
        $username_stmt->execute();
        $_SESSION['success_message'] = "Username updated successfully!";
        }

    // Update Email
    if (isset($_POST['update_email'])) {
        $email = $_POST['email'];
        $email_update_query = "UPDATE users SET Email = ? WHERE UserID = ?";
        $email_stmt = $conn->prepare($email_update_query);
        $email_stmt->bind_param('si', $email, $user_id);
        $email_stmt->execute();
        $_SESSION['success_message'] = "Email updated successfully!";
        }
      
          // Update Password
    if (isset($_POST['update_password']) && !empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $password_update_query = "UPDATE users SET Password = ? WHERE UserID = ?";
        $password_stmt->bind_param('si', $password, $user_id);
        $password_stmt->execute();
        $_SESSION['success_message'] = "Password updated successfully!";
        }

    header("Location: profile.php");
    exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profile</title>
    <link rel="stylesheet" href="../css/profile.css">
    <script src="../js/profile.js"></script>
</head>

<body>
    <div class="container">
        <h1 class="profile-title">User Profile</h1>

        <?php if (isset($_SESSION['success_message'])): ?>
            <p class="success-message"><?= $_SESSION['success_message']; ?></p>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <p class="error-message"><?= $_SESSION['error_message']; ?></p>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Update Profile Image -->
        <form action="profile.php" method="POST" enctype="multipart/form-data" class="form-section">
            <div class="form-group">
                <label for="profileImage">Profile Image:</label>
                <?php if (!empty($user['profileImage'])): ?>
                    <img src="../uploads/<?= $user['profileImage']; ?>?v=<?= time(); ?>" alt="Profile Image"
                        class="profile-img">
                <?php else: ?>
                    <p>No profile image uploaded.</p>
                <?php endif; ?>
                <input type="file" name="profileImage" id="profileImage">
                <button type="submit" name="update_image" class="btn">Update Profile Image</button>
            </div>
        </form>

        <!-- Update Username -->
        <form action="profile.php" method="POST" class="form-section">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['Username']); ?>"
                    required>
                <button type="submit" name="update_username" class="btn">Update Username</button>
            </div>
        </form>

        <!-- Update Email -->
        <form action="profile.php" method="POST" class="form-section">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['Email']); ?>" required>
                <button type="submit" name="update_email" class="btn">Update Email</button>
            </div>
        </form>

        <!-- Update Password -->
        <form action="profile.php" method="POST" class="form-section">
            <div class="form-group">
                <label for="password">New Password (Leave blank if you don't want to change):</label>
                <input type="password" name="password" id="password">
                <button type="submit" name="update_password" class="btn">Update Password</button>
            </div>
        </form>

        <!-- Display Membership Details -->
        <?php if ($user['MembershipType'] != 'admin' && $user['MembershipType'] == 'Paid'): ?>
            <div class="membership-details">
                <h2>Membership Details</h2>
                <p><strong>Membership Type:</strong> <?= htmlspecialchars($user['MembershipType']); ?></p>
                <p><strong>Membership Expiry Date:</strong> <?= htmlspecialchars($user['MembershipEndDate']); ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>

<?php
$conn->close();
?>