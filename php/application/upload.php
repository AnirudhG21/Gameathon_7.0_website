<?php
// Database connection
$servername = "localhost";
$username = "root"; // replace with your database username
$password = "";     // replace with your database password
$dbname = "gameathon_db"; // replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize user input to prevent SQL Injection
$team_name = $conn->real_escape_string($_POST['team-name']);
$team_lead_email = $conn->real_escape_string($_POST['team-lead-email']);
$game_name = $conn->real_escape_string($_POST['game-name']);

//check if the the team leader email and team name entered is registerd 

$query = "SELECT * FROM application_data WHERE TL_email = '$team_lead_email' AND team_name ='$team_name'";
$validation = $conn->query($query);
if($validation->num_rows==1){
// Check if the email already exists in the database
$sql = "SELECT * FROM abstract_submissions WHERE TL_email = '$team_lead_email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Email already exists, raise an alert
    echo "<script>alert('This email has already submitted an abstract. Only one submission allowed.');window.location.href='../../pages/application/index.html';</script>";
} else {
    // Proceed with file upload if email does not exist
    // Define the upload directory
    $target_dir = "C:/Users/Kushal Kumar B P/Desktop/New folder/Abstracts/";

    // Sanitize email for file name (replace special characters)
    $safe_email = preg_replace("/[^a-zA-Z0-9]/", "_", $team_lead_email);

    // Format the file name as abstract_team_lead_email1.pdf
    $target_file = $target_dir . "abstract_" . $safe_email . ".pdf";

    $uploadOk = 1;
    $fileType = strtolower(pathinfo($_FILES["abstract"]["name"], PATHINFO_EXTENSION));

    // Check if file is a valid PDF
    if ($fileType != "pdf") {
        echo "<script>alert('Only PDF files are allowed.');window.location.href='../../pages/application/upload.html';</script>";
        $uploadOk = 0;
    }

    // Check file size (less than 2MB)
    if ($_FILES["abstract"]["size"] > 2 * 1024 * 1024) {
        echo "<script>alert('File is too large. Maximum size is 2MB.');window.location.href='../../pages/applications/upload.html';</script>";
        $uploadOk = 0;
    }

    // If everything is okay, try to upload file
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["abstract"]["tmp_name"], $target_file)) {
            // Save submission details in the database
            $stmt = $conn->prepare("INSERT INTO abstract_submissions (team_name, TL_email, game_name, abstract_file) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $team_name, $team_lead_email, $game_name, $target_file);

            if ($stmt->execute()) {
                echo "<script>alert('Abstract uploaded successfully!');window.location.href='../../pages/application/index.html';</script>";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');window.location.href='../../pages/application/upload.html';</script>";
        }
    }
}
}
else{
    echo "<script>alert('Invalid Team leader email or Team name.');window.location.href='../../pages/application/upload.html';</script>";
}

$conn->close();
