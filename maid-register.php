<?php
session_start();
include('includes/dbconnection.php'); // Include your database connection configuration file

if(isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $skills = $_POST['skills']; // Add skills input
    $education_level = $_POST['education_level']; // Add education level input
    $experience_years = $_POST['experience_years']; // Add experience years input
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $sql = "SELECT * FROM maid_driver WHERE email = :email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);

    if($row) {
        echo "Email already exists. Please choose a different email.";
    } else {
        // Insert new maid data into the database
        $sql = "INSERT INTO maid_driver (username, email, password, skills, education_level, experience_years) VALUES (:username, :email, :password, :skills, :education_level, :experience_years)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $query->bindParam(':skills', $skills, PDO::PARAM_STR); // Bind skills parameter
        $query->bindParam(':education_level', $education_level, PDO::PARAM_STR); // Bind education level parameter
        $query->bindParam(':experience_years', $experience_years, PDO::PARAM_INT); // Bind experience years parameter
        $result = $query->execute();

        if($result) {
            echo "Registration successful! You can now login.";
        } else {
            echo "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maid Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            margin-top: 20px;
            text-align: center;
        }

        a {
            color: #4caf50;
        }

        a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            font-size: 12px;
            margin-top: -10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Maid Registration</h2>
        <form action="" method="post" id="registrationForm" onsubmit="return validateForm()">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <label for="skills">Skills:</label>
            <input type="text" id="skills" name="skills" required><br><br>
            <label for="education_level">Education Level:</label>
            <input type="text" id="education_level" name="education_level" required><br><br>
            <label for="experience_years">Experience Years:</label>
            <input type="number" id="experience_years" name="experience_years" required><br><br>
            <button type="submit" name="register">Register</button>
            <p id="error" class="error"></p>
        </form>
        <p>Already have an account? <a href="maid-login.php">Login here</a></p>
    </div>

    <script>
        function validateForm() {
            var skills = document.getElementById("skills").value;
            var educationLevel = document.getElementById("education_level").value;
            var experienceYears = document.getElementById("experience_years").value;
            var errorElement = document.getElementById("error");

            // Basic validation for skills, education level, and experience years
            if (skills.trim() === '' || educationLevel.trim() === '' || experienceYears.trim() === '') {
                errorElement.innerText = "Please fill out all fields.";
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
