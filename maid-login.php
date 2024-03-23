<?php
session_start();

include('includes/dbconnection.php'); // Include your database connection configuration file

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Retrieve maid data from the database using email
    $sql = "SELECT * FROM maid_driver WHERE email = :email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);

    if($row) {
        // Check if the provided password matches the stored password
        if(password_verify($password, $row['password'])) {
            // Passwords match, set session and redirect
            $_SESSION['maid_id'] = $row['maid_id'];
            header("Location: maid_dashboard.php");
            exit;
        } else {
            // Password does not match
            $login_error = "Invalid password.";
        }
    } else {
        // No maid found with the provided email
        $login_error = "Maid not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maid Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 300px;
            margin: 100px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            text-align: center;
            margin-top: 20px;
        }

        p.error {
            color: red;
        }

        p.success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Maid Login</h2>
        <form id="maidForm" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <button type="submit" name="login">Login</button>
            <p id="formError" class="error"></p>
        </form>
        
        <p>Don't have an account? <a href="maid-register.php">Register here</a></p>
    </div>

    <script>
        document.getElementById("maidForm").addEventListener("submit", function(event) {
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var formError = document.getElementById("formError");

            // Add client-side validation if needed

            // Example: Check if email and password are provided
            if (!email || !password) {
                formError.textContent = "Please provide email and password.";
                event.preventDefault();
            } else {
                formError.textContent = "";
            }
        });
    </script>
</body>
</html>
