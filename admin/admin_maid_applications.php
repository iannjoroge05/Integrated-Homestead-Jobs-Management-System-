<?php
// Include database connection
include('includes/dbconnection.php');

// Check if form is submitted for status update or deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['application_id']) && isset($_POST['status'])) {
        // Update status in tblmaidapplications
        $applicationId = $_POST['application_id'];
        $status = $_POST['status'];
        try {
            $updateSql = "UPDATE tblmaidapplications SET ApplicationStatus = :status WHERE application_id = :applicationId";
            $updateQuery = $dbh->prepare($updateSql);
            $updateQuery->bindParam(':status', $status, PDO::PARAM_STR);
            $updateQuery->bindParam(':applicationId', $applicationId, PDO::PARAM_INT);
            $updateQuery->execute();
        } catch (PDOException $e) {
            // Handle database error
            echo "Error: " . $e->getMessage();
        }
    } elseif (isset($_POST['delete_application_id'])) {
        // Delete application from tblmaidapplications
        $deleteApplicationId = $_POST['delete_application_id'];
        try {
            $deleteSql = "DELETE FROM tblmaidapplications WHERE application_id = :deleteApplicationId";
            $deleteQuery = $dbh->prepare($deleteSql);
            $deleteQuery->bindParam(':deleteApplicationId', $deleteApplicationId, PDO::PARAM_INT);
            $deleteQuery->execute();
        } catch (PDOException $e) {
            // Handle database error
            echo "Error: " . $e->getMessage();
        }
    }
}

// Retrieve maid applications from the database
try {
    $sql = "SELECT ma.application_id, ma.JobID, ma.application_date, ma.ApplicationStatus, md.Username as MaidUsername, md.Email as MaidEmail, md.maid_id, mb.Status as JobStatus, mb.skills as RequiredSkills, mb.education_level as RequiredEducationLevel, mb.experience_years as RequiredExperienceYears, md.skills as MaidSkills, md.education_level as MaidEducationLevel, md.experience_years as MaidExperienceYears FROM tblmaidapplications ma JOIN maid_driver md ON ma.maid_id = md.maid_id JOIN tblmaidbooking mb ON ma.JobID = mb.JobID";
    $query = $dbh->prepare($sql);
    $query->execute();
    $maid_applications = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle database error
    echo "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Maid Job Applications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin-top: 30px;
            color: #333;
            font-size: 28px; /* Previous font size */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd; /* Adding vertical borders */
            color: #333;
        }
        th:last-child, td:last-child {
            border-right: none; /* Remove right border from last column */
        }
        th {
            background-color: #2c3e50; /* Navy Blue */
            font-weight: bold;
            color: #fff; /* White */
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
        select, button {
            padding: 8px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button {
            background-color: #007bff; /* Teal */
            color: #fff;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .maid-name {
            color: #c0392b; /* Maroon */
        }
        .job-id {
            color: #27ae60; /* Olive Green */
        }
    </style>
</head>
<body>
    <h1 style="color: #333;">Admin Maid Job Applications</h1>

    <table>
        <thead>
            <tr>
                <th>Application ID</th>
                <th>Job ID</th>
                <th>Maid Username</th>
                <th>Maid Email</th>
                <th>Job Status</th>
                <th>Required Skills</th>
                <th>Required Education Level</th>
                <th>Required Experience Years</th>
                <th>Maid Skills</th>
                <th>Maid Education Level</th>
                <th>Maid Experience Years</th>
                <th>Application Status</th>
                <th>Date Applied</th>
                <th>Action</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($maid_applications) && !empty($maid_applications)): ?>
                <?php foreach ($maid_applications as $application) : ?>
                    <tr>
                        <td><?php echo $application['application_id']; ?></td>
                        <td><?php echo $application['JobID']; ?></td>
                        <td><?php echo $application['MaidUsername']; ?></td>
                        <td><?php echo $application['MaidEmail']; ?></td>
                        <td><?php echo $application['JobStatus']; ?></td>
                        <td><?php echo $application['RequiredSkills']; ?></td>
                        <td><?php echo $application['RequiredEducationLevel']; ?></td>
                        <td><?php echo $application['RequiredExperienceYears']; ?></td>
                        <td><?php echo $application['MaidSkills']; ?></td>
                        <td><?php echo $application['MaidEducationLevel']; ?></td>
                        <td><?php echo $application['MaidExperienceYears']; ?></td>
                        <td><?php echo $application['ApplicationStatus']; ?></td>
                        <td><?php echo $application['application_date']; ?></td>
                        <td>
                            <form id="updateForm_<?php echo $application['application_id']; ?>" method="post" action="">
                                <input type="hidden" name="application_id" value="<?php echo $application['application_id']; ?>">
                                <select name="status" onchange="updateStatus(this)">
                                    <?php if ($application['RequiredSkills'] == $application['MaidSkills'] &&
                                        $application['RequiredEducationLevel'] == $application['MaidEducationLevel'] &&
                                        $application['RequiredExperienceYears'] == $application['MaidExperienceYears']) {
                                        echo '<option value="Approved" selected>Approved</option>';
                                    } else {
                                        echo '<option value="Approved">Approved</option>';
                                    }
                                    ?>
                                    <option value="Pending"<?php if ($application['ApplicationStatus'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                    <option value="Cancelled"<?php if ($application['ApplicationStatus'] == 'Cancelled') echo ' selected'; ?>>Cancelled</option>
                                </select>
                                <button type="submit">Update Status</button>
                            </form>
                        </td>
                        <td>
                            <form id="deleteForm_<?php echo $application['application_id']; ?>" method="post" action="">
                                <input type="hidden" name="delete_application_id" value="<?php echo $application['application_id']; ?>">
                                <button type="button" onclick="confirmDelete('<?php echo $application['application_id']; ?>')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="15">No maid applications found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
        function updateStatus(selectElement) {
            var formId = selectElement.parentNode.id;
            document.getElementById(formId).submit();
        }

        function confirmDelete(applicationId) {
            if (confirm("Are you sure you want to delete this application?")) {
                var formId = 'deleteForm_' + applicationId;
                document.getElementById(formId).submit();
            }
        }
    </script>
</body>
</html>
