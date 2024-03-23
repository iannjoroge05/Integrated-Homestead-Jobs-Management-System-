<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle job posting
if (isset($_POST['post_job'])) {
    $user_id = $_SESSION['user_id'];
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];

    $sql = "INSERT INTO tblmaidbooking (user_id, job_title, job_description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $job_title, $job_description);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Job posted successfully.";
    } else {
        echo "Error posting job.";
    }

    $stmt->close();
}

// Fetch jobs for maid dashboard
$sql = "SELECT * FROM tblmaidbooking";
$result = $conn->query($sql);

$jobs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Posting System & Maid Dashboard</title>
    <style>
        /* Add your CSS styles here */
    </style>
</head>

<body>

    <!-- Job Posting Form -->
    <h2>Post a Job</h2>
    <form action="" method="post">
        <label for="job_title">Job Title:</label><br>
        <input type="text" id="job_title" name="job_title" required><br>
        <label for="job_description">Job Description:</label><br>
        <textarea id="job_description" name="job_description" rows="4" required></textarea><br>
        <button type="submit" name="post_job">Post Job</button>
    </form>

    <!-- Maid Dashboard -->
    <h2>Available Jobs</h2>
    <?php if (!empty($jobs)) : ?>
        <ul>
            <?php foreach ($jobs as $job) : ?>
                <li><?php echo $job['job_title']; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>No jobs available.</p>
    <?php endif; ?>

</body>

</html>
