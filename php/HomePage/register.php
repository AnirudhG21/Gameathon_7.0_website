<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gameathon_db";

$error_message = ""; // Initialize an empty error message
$success_message = ""; // Initialize an empty success message

// Enable exceptions for mysqli
mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);

// Create connection
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
} catch (mysqli_sql_exception $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture the first name from the form
    $fname = $_POST['fname']; 
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    $email_check_stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $email_check_stmt->bind_param("s", $email);
    $email_check_stmt->execute();
    $email_check_stmt->store_result();

    if ($email_check_stmt->num_rows > 0) {
        // Email already exists, set error message
        $error_message = "Email already exists! Please use a different email.";
    } else {
        try {
            // Hash the password before storing
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare and bind the insert statement including the fname
            $stmt = $conn->prepare("INSERT INTO users (fname, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $fname, $email, $hashed_password); // Bind fname, email, and password

            if ($stmt->execute()) {
                // Registration successful, redirect to login page
                // setcookie("user_name", $fname, time() + 3600, "/"); 
                header("Location: http://localhost/Gameathon_7.0_website/pages/HomePage/login.html");
                exit;
            }

            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            // Handle the duplicate entry error here
            if ($e->getCode() == 1062) {
                $error_message = "Email already exists! Please use a different email.";
            } else {
                $error_message = "Error: " . $e->getMessage();
            }
        }
    }

    $email_check_stmt->close();
}

// Close the database connection
$conn->close();

// Display the error message in JavaScript alert if there's an error
if (!empty($error_message)) {
    echo "<script type='text/javascript'>alert('$error_message'); window.history.back();</script>";
    exit;
}
?>
