<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['mhmsaid']) == 0) {
    header('location:logout.php');
} else {
    // Check if Job ID is provided
    if (isset($_GET['jobid'])) {
        $jobID = $_GET['jobid'];
        
        // Query to update status for expired jobs
        $sqlUpdateExpiredJobs = "UPDATE tblmaidbooking SET Status = 'Job expired' WHERE JobID = :jobID AND Status != 'Approved' AND Status != 'Cancelled' AND created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)";
        $stmtUpdateExpiredJobs = $dbh->prepare($sqlUpdateExpiredJobs);
        $stmtUpdateExpiredJobs->bindParam(':jobID', $jobID, PDO::PARAM_STR);
        $stmtUpdateExpiredJobs->execute();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Maid Hiring Management System || Expired Jobs</title>
    <!-- Include your CSS and JS files here -->
    <!-- Your CSS and JS links -->
</head>
<body class="inner_page tables_page">
<div class="full_container">
    <div class="inner_container">
        <!-- Sidebar  -->
        <?php include_once('includes/sidebar.php');?>
        <!-- right content -->
        <div id="content">
            <!-- topbar -->
            <?php include_once('includes/header.php');?>
            <!-- end topbar -->
            <!-- dashboard inner -->
            <div class="midde_cont">
                <div class="container-fluid">
                    <!-- Your HTML content here -->
                </div>
                <!-- footer -->
                <?php include_once('includes/footer.php');?>
            </div>
            <!-- end dashboard inner -->
        </div>
    </div>
    <!-- model popup -->
</div>
<!-- jQuery -->
<!-- Your jQuery and other JS scripts -->
</body>
</html>
<?php
}
?>
