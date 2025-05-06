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