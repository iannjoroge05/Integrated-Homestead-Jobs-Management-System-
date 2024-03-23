<?php
// Check if the JobIDZ is received
if (isset($_POST['JobID'])) {
    // Include database connection file
    include('includes/dbconnection.php');

    // Retrieve maid ID from session
    session_start();
    $maid_id = $_SESSION['maid_id'];

    // Get the JobID from the POST data
    $booking_id = $_POST['JobID'];

    try {
        // Check if the maid has already applied for this job
        $check_sql = "SELECT * FROM tblmaidapplications WHERE maid_id = :maid_id AND JobID = :booking_id";
        $check_stmt = $dbh->prepare($check_sql);
        $check_stmt->bindParam(':maid_id', $maid_id, PDO::PARAM_INT);
        $check_stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
        $check_stmt->execute();

        if ($check_stmt->rowCount() > 0) {
            // The maid has already applied for this job
            echo "<script>alert('You have already applied for this job.'); window.location.href = 'maid_dashboard.php';</script>";
            exit;
        }

        // Prepare and execute the SQL query to insert the application into tblmaidapplications
        $sql = "INSERT INTO tblmaidapplications (maid_id, JobID) VALUES (:maid_id, :booking_id)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':maid_id', $maid_id, PDO::PARAM_INT);
        $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
        $stmt->execute();

        // Check if the application was successfully inserted
        if ($stmt->rowCount() > 0) {
            // Application successfully submitted
            echo "<script>alert('Application submitted successfully.'); window.location.href = 'maid_dashboard.php';</script>";
            exit;
        } else {
            // Failed to submit application
            echo "<script>alert('Error: Unable to submit application.'); window.location.href = 'maid_dashboard.php';</script>";
            exit;
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href = 'maid_dashboard.php';</script>";
        exit;
    }
} else {
    // If JobID is not received, display an error message
    echo "<script>alert('Error: JobID not provided.'); window.location.href = 'maid_dashboard.php';</script>";
    exit;
}
?>
