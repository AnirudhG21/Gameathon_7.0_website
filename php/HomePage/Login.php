<?php
session_start();  // Start the session to track user authentication

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gameathon_db";

try {
    // Create a new PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Set the PDO error mode to exception

    // Prepare the statement to get the hashed password and fname from the database
    $stmt = $conn->prepare("SELECT password, fname FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $email = $_POST['email'];

    // Execute the query
    $stmt->execute();

    // Check if the email exists
    if ($stmt->rowCount() > 0) {
        // Fetch the stored hashed password and fname
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stored_hashed_password = $row['password'];
        $fname = $row['fname'];  // Retrieve fname

        // Verify the entered password against the stored hashed password
        if (password_verify($_POST['password'], $stored_hashed_password)) {
            // Password is correct, login successful

            // Set session variable to indicate the user is logged in
            $_SESSION['email'] = $email;

            // Set a cookie for fname that expires in 12 hours
            setcookie("user_name", $fname, time() + (12 * 60 * 60), "/");  // 12 hours in seconds
            $_SESSION['name'] = $fname;
            // Redirect securely to the application page
            header("Location: /Gameathon_7.0_website/pages/application/index.html");
            exit();  // Ensure no further code is executed
        } else {
            // Password is incorrect, redirect to login page with error parameter
            header("Location: /Gameathon_7.0_website/pages/HomePage/login.html?error=1");
            exit();
        }
    } else {
    
        // Email not found, redirect to login page with error parameter
        echo "<script type='text/javascript'>
                alert('User is not registered, Please register the user first.');
                window.location.href = '/Gameathon_7.0_website/pages/HomePage/register.html';  // Redirect to the registration page
              </script>";
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the connection
$conn = null;
?>
