<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

$message = '';

if (strlen($_SESSION['mhmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['assign'])) {
        $jobID = $_POST['jobID'];

        // Retrieve information about the request
        $sql = "SELECT mb.*, ma.Applicationstatus, md.name AS assignedName
                FROM tblmaidbooking mb
                LEFT JOIN tblmaidapplications ma ON mb.JobID = ma.JobID
                LEFT JOIN maid_driver md ON mb.maid_id = md.maid_id
                WHERE mb.JobID = :jobID";
        $query = $dbh->prepare($sql);
        $query->bindParam(':jobID', $jobID, PDO::PARAM_STR);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $status = $row['Status'];
            $applicationStatus = $row['Applicationstatus'];
            $assignedName = $row['assignedName'];

            if ($applicationStatus == 'Approved') {
                $message = "The request with Job ID $jobID has already been approved and assigned to $assignedName.";
            } elseif ($applicationStatus == 'Cancelled' || $applicationStatus == 'Pending') {
                $message = "The request with Job ID $jobID has been $applicationStatus and cannot be assigned.";
            } else {
                // Update the database to assign the request
                $assignTo = $row['Name'];
                $sql = "UPDATE tblmaidbooking SET AssignTo=:assignTo WHERE JobID=:jobID";
                $query = $dbh->prepare($sql);
                $query->bindParam(':assignTo', $assignTo, PDO::PARAM_STR);
                $query->bindParam(':jobID', $jobID, PDO::PARAM_STR);

                if ($query->execute()) {
                    $message = "The request with Job ID $jobID has been approved and assigned to $assignTo.";

                    // Send email notification to the user
                    $to = $row['Email'];
                    $subject = "Request Status Update";
                    $message = "Your request with Job ID $jobID has been approved. We have assigned $assignTo for your service.";
                    $headers = "From: iannjoroge05@gmail.com"; // Replace with your email address

                    // Send email
                    if (mail($to, $subject, $message, $headers)) {
                        $message .= ' Email notification sent successfully.';
                    } else {
                        $message .= ' Failed to send email notification. Please try again.';
                    }
                } else {
                    $message = 'Failed to update database. Please try again.';
                }
            }
        } else {
            $message = "No request found with Job ID $jobID.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NOTIFY</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>NOTIFY</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="bookingID">Booking ID:</label>
                <input type="text" id="bookingID" name="bookingID" required>
            </div>
            <button type="submit" name="assign">SEND</button>
        </form>
        <?php if (!empty($message)): ?>
            <div><?php echo $message; ?></div>
        <?php endif; ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Notification</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <p><?php echo $message; ?></p>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function(){
            <?php if (!empty($message)): ?>
                $('#myModal').modal('show');
                <?php if (strpos($message, 'Email notification sent successfully') !== false): ?>
                    alert("Email notification sent successfully.");
                <?php elseif (strpos($message, 'Failed to send email notification') !== false): ?>
                    alert("Failed to send email notification. Please try again.");
                <?php endif; ?>
            <?php endif; ?>
        });
    </script>
</body>
</html>
