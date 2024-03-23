<?php
session_start();
include('includes/dbconnection.php');

// Check if maid is logged in
if (!isset($_SESSION['maid_id'])) {
    // Redirect to maid login page
    header("Location: maid-login.php");
    exit; // Stop further execution
}

// Retrieve maid ID from session
$maid_id = $_SESSION['maid_id'];

try {
    // Fetch new jobs that have not been assigned to any maid, where deadline is not reached, and the admin has not assigned to another maid
    $sql = "SELECT mb.*, mc.CategoryName, mb.ServiceType, mb.skills, mb.experience_years, mb.education_level, mb.Job_description
            FROM tblmaidbooking mb
            LEFT JOIN tblcategory mc ON mb.CatID = mc.ID
            WHERE (mb.maid_id IS NULL OR mb.maid_id = 0) 
            AND (mb.deadline IS NULL OR mb.deadline > NOW()) 
            AND mb.AssignTo IS NULL
            AND mb.JobID NOT IN (SELECT JobID FROM tblmaidapplications WHERE ApplicationStatus = 'Approved')";

    $query = $dbh->prepare($sql);
    $query->execute();
    $jobs = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle SQL errors
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maid Dashboard</title>
    <style>
        /* CSS Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .job-details {
            margin-bottom: 20px;
        }

        .job-details p {
            margin: 5px 0;
            margin-left: 20px; /* Indent bullet points */
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .application-status {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            display: none; /* Initially hide the application status message */
        }

        .application-status.success {
            color: green;
        }

        .application-status.error {
            color: red;
        }

        /* Added style for the logout link */
        .logout-link {
            position: absolute;
            top: 10px;
            right: 10px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Added logout link -->
    <a href="logout.php" class="logout-link">Logout</a>

    <div class="container">
        <h2>Welcome to Maid Dashboard</h2>

        <?php
        // Check if jobs are retrieved
        if (!empty($jobs)) {
            // Display new jobs and apply button for each job
            foreach ($jobs as $job) {
                // Extract job details
                $ServiceType = $job['ServiceType'];
                $JobID = $job['JobID']; // Changed from BookingID to JobID
                $WorkingShiftFrom = $job['WorkingShiftFrom'];
                $WorkingShiftTo = $job['WorkingShiftTo'];
                $StartDate = $job['StartDate'];
                $Email = $job['Email'];
                $Address = $job['Address'];
                $ContactNumber = $job['ContactNumber'];
                $Skills = $job['skills'];
                $ExperienceYears = $job['experience_years'];
                $EducationLevel = $job['education_level'];
                $JobDescription = $job['Job_description']; // Adding Job_description

                // Split Job_description into separate points
                $jobDescriptionPoints = explode(". ", $JobDescription);

                // Check if the maid has already applied for this job
                $check_sql = "SELECT * FROM tblmaidapplications WHERE JobID = :JobID AND maid_id = :maid_id"; // Changed from BookingID to JobID
                $check_query = $dbh->prepare($check_sql);
                $check_query->bindParam(':JobID', $JobID, PDO::PARAM_INT);
                $check_query->bindParam(':maid_id', $maid_id, PDO::PARAM_INT);
                $check_query->execute();

                if ($check_query->rowCount() > 0) {
                    // Job already applied, display the details with a message
                    $application_status = $check_query->fetch(PDO::FETCH_ASSOC)['ApplicationStatus'];
                    if ($application_status === 'Approved') {
                        echo "<div class='job-details'>";
                        echo "<p><strong>ServiceType:</strong> " . $ServiceType . "</p>";
                        echo "<p><strong>Job ID:</strong> " . $JobID . "</p>";
                        echo "<p><strong>Working Shift From:</strong> " . $WorkingShiftFrom . "</p>";
                        echo "<p><strong>Working Shift To:</strong> " . $WorkingShiftTo . "</p>";
                        echo "<p><strong>Start Date:</strong> " . $StartDate . "</p>";
                        echo "<p><strong>User Email:</strong> " . $Email . "</p>";
                        echo "<p><strong>User Address:</strong> " . $Address . "</p>";
                        echo "<p><strong>User Contact Number:</strong> " . $ContactNumber . "</p>";
                        echo "<p><strong>Skills:</strong> " . $Skills . "</p>";
                        echo "<p><strong>Experience Years:</strong> " . $ExperienceYears . "</p>";
                        echo "<p><strong>Education Level:</strong> " . $EducationLevel . "</p>";
                        echo "<p><strong>Job Description:</strong></p>";
                        echo "<ul>";
                        foreach ($jobDescriptionPoints as $point) {
                            echo "<li>" . $point . "</li>";
                        }
                        echo "</ul>";
                        echo "<p style='color: green;'>We are happy to inform you that your application was a success.</p>";
                        echo "</div>";
                    } elseif ($application_status === 'Declined') {
                        echo "<div class='job-details'>";
                        echo "<p><strong>ServiceType:</strong> " . $ServiceType . "</p>";
                        echo "<p><strong>Job ID:</strong> " . $JobID . "</p>";
                        echo "<p><strong>Working Shift From:</strong> " . $WorkingShiftFrom . "</p>";
                        echo "<p><strong>Working Shift To:</strong> " . $WorkingShiftTo . "</p>";
                        echo "<p><strong>Start Date:</strong> " . $StartDate . "</p>";
                        echo "<p><strong>User Email:</strong> " . $Email . "</p>";
                        echo "<p><strong>User Address:</strong> " . $Address . "</p>";
                        echo "<p><strong>User Contact Number:</strong> " . $ContactNumber . "</p>";
                        echo "<p><strong>Skills:</strong> " . $Skills . "</p>";
                        echo "<p><strong>Experience Years:</strong> " . $ExperienceYears . "</p>";
                        echo "<p><strong>Education Level:</strong> " . $EducationLevel . "</p>";
                        echo "<p><strong>Job Description:</strong></p>";
                        echo "<ul>";
                        foreach ($jobDescriptionPoints as $point) {
                            echo "<li>" . $point . "</li>";
                        }
                        echo "</ul>";
                        echo "<p style='color: red;'>We regret to confirm your application has been declined.</p>";
                        echo "</div>";
                    } elseif ($application_status === 'Pending') {
                        echo "<div class='job-details'>";
                        echo "<p><strong>ServiceType:</strong> " . $ServiceType . "</p>";
                        echo "<p><strong>Job ID:</strong> " . $JobID . "</p>";
                        echo "<p><strong>Working Shift From:</strong> " . $WorkingShiftFrom . "</p>";
                        echo "<p><strong>Working Shift To:</strong> " . $WorkingShiftTo . "</p>";
                        echo "<p><strong>Start Date:</strong> " . $StartDate . "</p>";
                        echo "<p><strong>User Email:</strong> " . $Email . "</p>";
                        echo "<p><strong>User Address:</strong> " . $Address . "</p>";
                        echo "<p><strong>User Contact Number:</strong> " . $ContactNumber . "</p>";
                        echo "<p><strong>Skills:</strong> " . $Skills . "</p>";
                        echo "<p><strong>Experience Years:</strong> " . $ExperienceYears . "</p>";
                        echo "<p><strong>Education Level:</strong> " . $EducationLevel . "</p>";
                        echo "<p><strong>Job Description:</strong></p>";
                        echo "<ul>";
                        foreach ($jobDescriptionPoints as $point) {
                            echo "<li>" . $point . "</li>";
                        }
                        echo "</ul>";
                        echo "<p style='color: orange;'>Application sent successfully. We will get right back to you.</p>";
                        echo "</div>";
                    }
                } else {
                    // Job not applied, display the apply button
                    echo "<div class='job-details'>";
                    echo "<p><strong>ServiceType:</strong> " . $ServiceType . "</p>";
                    echo "<p><strong>Job ID:</strong> " . $JobID . "</p>";
                    echo "<p><strong>Working Shift From:</strong> " . $WorkingShiftFrom . "</p>";
                    echo "<p><strong>Working Shift To:</strong> " . $WorkingShiftTo . "</p>";
                    echo "<p><strong>Start Date:</strong> " . $StartDate . "</p>";
                    echo "<p><strong>User Email:</strong> " . $Email . "</p>";
                    echo "<p><strong>User Address:</strong> " . $Address . "</p>";
                    echo "<p><strong>User Contact Number:</strong> " . $ContactNumber . "</p>";
                    echo "<p><strong>Skills:</strong> " . $Skills . "</p>";
                    echo "<p><strong>Experience Years:</strong> " . $ExperienceYears . "</p>";
                    echo "<p><strong>Education Level:</strong> " . $EducationLevel . "</p>";
                    echo "<p><strong>Job Description:</strong></p>";
                    echo "<ul>";
                    foreach ($jobDescriptionPoints as $point) {
                        echo "<li>" . $point . "</li>";
                    }
                    echo "</ul>";
                    echo "<form method='post' action='submit-application.php'>";
                    echo "<input type='hidden' name='JobID' value='" . $JobID . "'>"; // Changed from BookingID to JobID
                    echo "<label for='jobID_" . $JobID . "'>Enter Job ID:</label>"; // Changed from bookingID to jobID
                    echo "<input type='text' id='jobID_" . $JobID . "' name='jobID_" . $JobID . "' required>"; // Changed from bookingID to jobID
                    echo "<button type='submit'>Apply</button>";
                    echo "</form>";
                    echo "</div>";
                }
            }
        } else {
            // No jobs found
            echo "<p>No jobs available.</p>";
        }
        ?>
    </div>
    <script>
        // JavaScript function to handle job application
        function applyForJob(jobID, button) {
            var statusElement = document.getElementById('status_' + jobID);
            // Simulate application submission
            // In a real scenario, you would make an AJAX request to submit the application
            // For demonstration, here we just display a success or error message after a short delay
            statusElement.innerHTML = 'Applying...';
            setTimeout(function() {
                var isSuccess = Math.random() < 0.5; // Simulating success or failure randomly
                if (isSuccess) {
                    statusElement.innerHTML = 'Application submitted successfully. We will get right back to you.';
                    statusElement.classList.add('success');
                } else {
                    statusElement.innerHTML = 'Error: Unable to submit your application. Please try again later.';
                    statusElement.classList.add('error');
                }
                statusElement.style.display = 'block'; // Show the application status message
                // Hide the status message after 3 seconds
                setTimeout(function() {
                    statusElement.style.display = 'none';
                }, 3000);
            }, 1500); // Simulate a delay of 1.5 seconds
        }
    </script>
</body>
</html>
