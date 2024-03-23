<?php
// Include database connection
include('../includes/dbconnection.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['application_id']) && isset($_POST['status'])) {
    // Retrieve form data
    $applicationId = $_POST['application_id'];
    $status = $_POST['status'];

    try {
        // Update status in tblmaidapplications
        $updateSql = "UPDATE tblmaidapplications SET ApplicationStatus = :status WHERE application_id = :applicationId";
        $updateQuery = $dbh->prepare($updateSql);
        $updateQuery->bindParam(':status', $status, PDO::PARAM_STR);
        $updateQuery->bindParam(':applicationId', $applicationId, PDO::PARAM_INT);
        $updateQuery->execute();

        // Optionally, you can perform additional actions here, such as sending notifications or logging the status update.

        // Redirect back to the admin maid application page after the update
        header("Location: admin_maid_application.php");
        exit; // Stop further execution
    } catch (PDOException $e) {
        // Handle database error
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect to error page or perform other actions if form data is not submitted
}
?>
