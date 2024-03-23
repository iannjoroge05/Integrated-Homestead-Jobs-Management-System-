<?php 
session_start();

// Include database connection
include("php/config.php");

// Check if user is logged in
if(isset($_SESSION['valid'])){
    // Fetch hired person's details for the logged-in user from the database
    $user_id = $_SESSION['id'];
    $query = "SELECT * FROM hired_persons WHERE user_id = $user_id";
    $result = mysqli_query($con, $query) or die("Query Error: " . mysqli_error($con));

    // Check if there are hired persons
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Send email notification
        $to = $_SESSION['valid']; // User's email address
        $subject = "Request Approved";
        $message = "Your request has been approved. Maid assigned: \nName: " . $row['name'] . "\nAge: " . $row['age']; // Message with maid details
        $headers = "From: iannjoroge05@gmail.com"; // Your email address

        // Send email
        if(mail($to, $subject, $message, $headers)) {
            echo "Email notification sent successfully.";
        } else {
            echo "Failed to send email notification.";
        }
    } else {
        echo "No hired persons found for this user.";
    }
} else {
    // Redirect user to login page if not logged in
    header("Location: login.php");
    exit();
}
?>
