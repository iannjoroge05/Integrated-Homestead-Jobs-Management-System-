<?php
// Include database connection
include('includes/dbconnection.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if application_id and status are set
    if (isset($_POST['application_id'], $_POST['status'])) {
        // Sanitize input to prevent SQL injection
        $application_id = $_POST['application_id'];
        $status = $_POST['status'];

        try {
            // Update application status in the database
            $sql = "UPDATE tblmaidapplications SET ApplicationStatus = :status WHERE application_id = :application_id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':status', $status, PDO::PARAM_STR);
            $query->bindParam(':application_id', $application_id, PDO::PARAM_INT);
            $query->execute();

            // Redirect back to the same page after updating status
            header("Location: admin_maid_applications.php");
            exit();
        } catch (PDOException $e) {
            // Handle database error
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Redirect if application_id or status is not set
        header("Location: C:\xampp\htdocs\Driver-Maid-Hiring-Management-System-main\admin\admin_maid_applications.php");
        exit();
    }
} else {
    // Redirect if form is not submitted
    header("Location: admin_maid_applications.php");
    exit();
}
?>
