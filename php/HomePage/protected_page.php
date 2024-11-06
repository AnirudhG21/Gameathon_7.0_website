<?php
session_start();  // Start the session

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // User is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// User is logged in, proceed with the rest of the page
echo "Welcome, " . $_SESSION['email'];
?>