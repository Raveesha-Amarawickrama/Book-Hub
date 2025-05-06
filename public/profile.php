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