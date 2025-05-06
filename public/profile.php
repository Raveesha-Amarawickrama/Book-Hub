<?php
session_start();
require_once '../config/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
    }

$user_id = $_SESSION['user_id'];
