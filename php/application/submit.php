<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Enter your database password
$dbname = "gameathon_db"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize user input
function sanitize_input($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $team_name = sanitize_input($_POST['team-name']);
    $team_lead_email = sanitize_input($_POST['team-lead-email']);
    $topic_chosen = sanitize_input($_POST['topic']);
    $github_link = sanitize_input($_POST['github-link']);
    $drive_link = sanitize_input($_POST['drive-link']);
    $github_link=$github_link."/";
    $drive_link=$drive_link."/";
    // Validate inputs
    if (empty($team_name) || empty($team_lead_email) || empty($topic_chosen) || empty($github_link) || empty($drive_link)) {
        echo "All fields are required!";
    } elseif (!filter_var($team_lead_email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
    } elseif (!filter_var($github_link, FILTER_VALIDATE_URL) || !filter_var($drive_link, FILTER_VALIDATE_URL)) {
        echo "Invalid URL format!";
    } else {
        // Check if the email is registered in the 'users' or 'registrations' table
        $email_registered_check = $conn->prepare("SELECT * FROM application_data WHERE TL_email = ? AND team_name = ?");
        $email_registered_check->bind_param("ss", $team_lead_email,$team_name);
        $email_registered_check->execute();
        $registered_result = $email_registered_check->get_result();

        if ($registered_result->num_rows == 0) {
            // Email is not registered
            echo "<script type='text/javascript'>alert('Team not registered(Check your email/Team name)'); window.history.back();</script>";
            exit;
        } else {
            // Email is registered, check if the email has already been used for submission
            $email_submission_check = $conn->prepare("SELECT * FROM game_submissions WHERE TL_email = ?");
            $email_submission_check->bind_param("s", $team_lead_email);
            $email_submission_check->execute();
            $submission_result = $email_submission_check->get_result();

            if ($submission_result->num_rows > 0) {
                // Email has already been used for submission
                echo "<script type='text/javascript'>alert('Game already submitted'); window.history.back();</script>";
                exit;
            } else {
                // Insert into the database
                $stmt = $conn->prepare("INSERT INTO game_submissions (team_name, TL_email, topic_chosen, github_link, drive_link) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $team_name, $team_lead_email, $topic_chosen, $github_link, $drive_link);

                if ($stmt->execute()) {
                    echo "Game submitted successfully!";
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
            }

            $email_submission_check->close();
        }

        $email_registered_check->close();
    }
}

$conn->close();
