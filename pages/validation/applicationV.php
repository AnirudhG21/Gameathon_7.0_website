<?php

session_start();

if (!isset($_SESSION['email'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: /Gameathon_7.0_website/pages/HomePage/login.html");
    exit;
} else {
    // Load the HTML content if the user is logged in
    readfile("../application/application.html");
}
?>
