<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gameathon_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Team Leader (Member 1) details
    $TL_name = htmlspecialchars($_POST['member1_name']);
    $TL_email = filter_var($_POST['member1_email'], FILTER_SANITIZE_EMAIL);
    $TL_mobile = htmlspecialchars($_POST['member1_mobile']);
    $TL_prefix = htmlspecialchars($_POST['member1_prefix']);
    $TL_mobile = $TL_prefix . $TL_mobile; // Combine prefix and mobile number
    $TL_usn = htmlspecialchars($_POST['member1_usn']);

    // Team Details
    $team_name = htmlspecialchars($_POST['team_name']);

    //Validating existing TL_email 
    $email_check_stmt = $conn->prepare("SELECT * FROM application_data WHERE TL_email = :email");
    $email_check_stmt->bindParam(':email', $TL_email);
    $email_check_stmt->execute();
    if ($email_check_stmt->rowCount() > 0) {
        // Email already exists, set error message
        $error_message = "Email already exists! Please use a different email.";
        echo "<script type='text/javascript'>alert('$error_message'); window.history.back();</script>";
        exit;
    }

    //Validating existing team name
    $email_check_stmt = $conn->prepare("SELECT * FROM application_data WHERE team_name = :team_name");
    $email_check_stmt->bindParam(':team_name', $team_name);
    $email_check_stmt->execute();

    if ($email_check_stmt->rowCount() > 0) {
        // Email already exists, set error message
        $error_message = "Team name already Taken! Please use a different Team name.";
        echo "<script type='text/javascript'>alert('$error_message'); window.history.back();</script>";
        exit;
    }


    

    $mode_of_participation = htmlspecialchars($_POST['mode_of_participation']);
    
    // Member 1 (Team Leader, TL) is already captured in TL_ variables
    
    // Member 2 details
    $M2_name = htmlspecialchars($_POST['member2_name']);
    $M2_email = filter_var($_POST['member2_email'], FILTER_SANITIZE_EMAIL);
    $M2_prefix = htmlspecialchars($_POST['member2_prefix']);
    $M2_mobile = htmlspecialchars($_POST['member2_mobile']);
    $M2_mobile = $M2_prefix . $M2_mobile;
    $M2_usn = htmlspecialchars($_POST['member2_usn']);
    
    // Member 3 details
    $M3_name = htmlspecialchars($_POST['member3_name']);
    $M3_email = filter_var($_POST['member3_email'], FILTER_SANITIZE_EMAIL);
    $M3_prefix = htmlspecialchars($_POST['member3_prefix']);
    $M3_mobile = htmlspecialchars($_POST['member3_mobile']);
    $M3_mobile = $M3_prefix . $M3_mobile;
    $M3_usn = htmlspecialchars($_POST['member3_usn']);
    
    // Member 4 details
    $M4_name = htmlspecialchars($_POST['member4_name']);
    $M4_email = filter_var($_POST['member4_email'], FILTER_SANITIZE_EMAIL);
    $M4_prefix = htmlspecialchars($_POST['member4_prefix']);
    $M4_mobile = htmlspecialchars($_POST['member4_mobile']);
    $M4_mobile = $M4_prefix . $M4_mobile;
    $M4_usn = htmlspecialchars($_POST['member4_usn']);

    // College and transaction details
    $college_name = htmlspecialchars($_POST['college_name']);
    $transaction_id = htmlspecialchars($_POST['transaction_id']);
    $transaction_date = htmlspecialchars($_POST['transaction_date']);
    
    // File uploads
    $id_card = htmlspecialchars(basename($_FILES["id_card"]["name"]));
    $acknowledgment = htmlspecialchars(basename($_FILES["acknowledgment"]["name"]));



    // Set directories for ID card and acknowledgment
    $target_dir_id = "C:\\Users\\Kushal Kumar B P\\Desktop\\New folder\\ID_Cards\\";
    $target_dir_ack = "C:\\Users\\Kushal Kumar B P\\Desktop\\New folder\\Acknowledgments\\";

    // Ensure there are no spaces in the filenames (optional)
    $TL_email = str_replace(' ', '_', $TL_email);
    $team_name = str_replace(' ', '_', $team_name);

    // Generate new filenames for ID card and acknowledgment
    $id_card_filename = $TL_email . "_" . $team_name . "_ID.pdf";
    $ack_filename = $TL_email . "_" . $team_name . "_ACK.pdf";

    // Full paths for storing the files
    $target_file_id = $target_dir_id . $id_card_filename;
    $target_file_ack = $target_dir_ack . $ack_filename;



    // Insert data into database using prepared statement
    $stmt = $conn->prepare("INSERT INTO application_data (TL_name, TL_email, TL_mobile, TL_usn, team_name, mode_of_participation, 
                            M1_name, M1_email, M1_mobile, M1_usn, 
                            M2_name, M2_email, M2_mobile, M2_usn, 
                            M3_name, M3_email, M3_mobile, M3_usn, 
                            M4_name, M4_email, M4_mobile, M4_usn, 
                            college_name, transaction_id, transaction_date, id_card, acknowledgment) 
                            VALUES (:TL_name, :TL_email, :TL_mobile, :TL_usn, :team_name, :mode_of_participation, 
                            :M1_name, :M1_email, :M1_mobile, :M1_usn, 
                            :M2_name, :M2_email, :M2_mobile, :M2_usn, 
                            :M3_name, :M3_email, :M3_mobile, :M3_usn, 
                            :M4_name, :M4_email, :M4_mobile, :M4_usn, 
                            :college_name, :transaction_id, :transaction_date, :id_card, :acknowledgment)");

    // Bind parameters
    $stmt->bindParam(':TL_name', $TL_name);
    $stmt->bindParam(':TL_email', $TL_email);
    $stmt->bindParam(':TL_mobile', $TL_mobile);
    $stmt->bindParam(':TL_usn', $TL_usn);
    $stmt->bindParam(':team_name', $team_name);
    $stmt->bindParam(':mode_of_participation', $mode_of_participation);
    $stmt->bindParam(':M1_name', $TL_name);
    $stmt->bindParam(':M1_email', $TL_email);
    $stmt->bindParam(':M1_mobile', $TL_mobile);
    $stmt->bindParam(':M1_usn', $TL_usn);
    $stmt->bindParam(':M2_name', $M2_name);
    $stmt->bindParam(':M2_email', $M2_email);
    $stmt->bindParam(':M2_mobile', $M2_mobile);
    $stmt->bindParam(':M2_usn', $M2_usn);
    $stmt->bindParam(':M3_name', $M3_name);
    $stmt->bindParam(':M3_email', $M3_email);
    $stmt->bindParam(':M3_mobile', $M3_mobile);
    $stmt->bindParam(':M3_usn', $M3_usn);
    $stmt->bindParam(':M4_name', $M4_name);
    $stmt->bindParam(':M4_email', $M4_email);
    $stmt->bindParam(':M4_mobile', $M4_mobile);
    $stmt->bindParam(':M4_usn', $M4_usn);
    $stmt->bindParam(':college_name', $college_name);
    $stmt->bindParam(':transaction_id', $transaction_id);
    $stmt->bindParam(':transaction_date', $transaction_date);
    $stmt->bindParam(':id_card', $id_card_filename);
    $stmt->bindParam(':acknowledgment', $ack_filename);

    // // Execute the statement
    // if ($stmt->execute()) {
    //     echo "Application submitted successfully!";
    // } else {
    //     echo "Error: Could not submit application.";
    // }

      
    if ($stmt->execute()) {
        // Upload the ID card
        if (move_uploaded_file($_FILES["id_card"]["tmp_name"], $target_file_id)) {
            // echo "The ID card has been uploaded as " . $id_card_filename;
            if (move_uploaded_file($_FILES["acknowledgment"]["tmp_name"], $target_file_ack)) {
                
                echo "<script type='text/javascript'>
                alert('Application submitted successfully');
                window.location.href = '/Gameathon_7.0_website/pages/application/index.html';  // Redirect to the home page
                </script>";;
                // echo "The acknowledgment has been uploaded as " . $ack_filename;
                header("Location: /Gameathon_7.0_website/pages/validation/indexV.php");
                exit();
            }
        }
    } else {
        echo "<script type='text/javascript'>
                alert('Error: Could not submit application.');
                window.location.href = '/Gameathon_7.0_website/pages/validation/applicationV.php';  // Redirect to the registration page
              </script>";;
    }

    // // Upload the acknowledgment
    //  else {
    //     echo "Sorry, there was an error uploading your acknowledgment.";
    // }

}

// Close the database connection
$conn = null;
?>