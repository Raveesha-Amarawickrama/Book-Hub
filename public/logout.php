<?php
// logout.php

// Start session to access session variables
session_start();

// Destroy all session variables
session_unset();
session_destroy();

// Redirect the user to the login page after logging out
header("Location: index.php");
exit();
