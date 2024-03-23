<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve form data
    $catid = $_POST['catid'];
    $name = $_POST['name'];
    $contno = $_POST['contno'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $wsf = $_POST['wsf'];
    $wst = $_POST['wst'];
    $startdate = $_POST['startdate'];
    $job_description = $_POST['notes']; // Updated variable name
    $skills = $_POST['skills'];
    $education_level = $_POST['education_level'];
    $experience_years = $_POST['experience_years'];
    $JobID = mt_rand(100000000, 999999999);

    // Insert data into the database with Job_description column
    $sql = "INSERT INTO tblmaidbooking (JobID, CatID, Name, ContactNumber, Email, Address, Gender, WorkingShiftFrom, WorkingShiftTo, StartDate, Job_description, skills, education_level, experience_years) VALUES (:JobID, :catid, :name, :contno, :email, :address, :gender, :wsf, :wst, :startdate, :job_description, :skills, :education_level, :experience_years)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':JobID', $JobID, PDO::PARAM_STR);
    $query->bindParam(':catid', $catid, PDO::PARAM_STR);
    $query->bindParam(':name', $name, PDO::PARAM_STR);
    $query->bindParam(':contno', $contno, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':address', $address, PDO::PARAM_STR);
    $query->bindParam(':gender', $gender, PDO::PARAM_STR);
    $query->bindParam(':wsf', $wsf, PDO::PARAM_STR);
    $query->bindParam(':wst', $wst, PDO::PARAM_STR);
    $query->bindParam(':startdate', $startdate, PDO::PARAM_STR);
    $query->bindParam(':job_description', $job_description, PDO::PARAM_STR); // Updated parameter name
    $query->bindParam(':skills', $skills, PDO::PARAM_STR);
    $query->bindParam(':education_level', $education_level, PDO::PARAM_STR);
    $query->bindParam(':experience_years', $experience_years, PDO::PARAM_INT);
    $query->execute();
    $LastInsertId = $dbh->lastInsertId();

    // Check if the insertion was successful
    if ($LastInsertId > 0) {
        echo '<script>alert("Your Booking Request Has Been Sent. We Will Contact You Soon")</script>';
        echo "<script>window.location.href ='maid-hiring.php'</script>";
    } else {
        echo '<script>alert("Something Went Wrong. Please try again")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <title>Maid / Driver Hiring Management System || Hiring Form</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Pacifico&display=swap">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f7f7f7;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h2 {
            font-family: 'Pacifico', cursive;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .contact-form-wrap {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            font-size: 18px;
            color: #4CAF50;
        }

        select,
        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        select {
            background-color: #f9f9f9;
        }

        .form-group {
            margin-bottom: 20px;
        }

        button {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            font-size: 16px;
            color: #008CBA;
        }
    </style>
</head>

<head>
    
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.css">
    <link rel="stylesheet" href="assets/css/price_rangs.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/slick.css">
    <link rel="stylesheet" href="assets/css/nice-select.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body>
    <?php include_once('includes/header.php');?>
    <!-- Hero Area Start-->
    <div class="slider-area">
        <div class="single-slider section-overly slider-height2 d-flex align-items-center"
            data-background="assets/img/hero/new_about.jpg">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="hero-cap text-center">
                            <h2>Hiring Form</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero Area End -->

    <section class="contact-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="contact-form-wrap">
                        <h2 class="contact-title text-center">Looking To Hire A Maid / Driver?</h2>
                        <p class="contact-title text-center">Post Requirement Here ></p>

                        <form class="form-contact contact_form" action="" method="post">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label style="color: red;font-weight: bold;font-size: 18px;">Select Hiring Type:</label>
                                        <select name="hiring_type" class="form-control" id="hiring_type" required='true'>
                                            <option value="">Select Hiring Type</option>
                                            <option value="Driver">Driver</option>
                                            <option value="Maid">Maid</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label style="color: red;font-weight: bold;font-size: 18px;">Name:</label>
                                        <input class="form-control" name="name" id="name" type="text"
                                            placeholder="Enter your name">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label style="color: red;font-weight: bold;font-size: 18px;">Contact Number:</label>
                                        <input type="text" name="contno" value="" class="form-control" required='true'
                                            maxlength="10" pattern="[0-9]+">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label style="color: red;font-weight: bold;font-size: 18px;">Address(to be hired):</label>
                                        <textarea class="form-control" name="address" id="address" placeholder="Enter Your Address"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label style="color: red;font-weight: bold;font-size: 18px; padding-left: 20px;">Gender:</label>
                                        <select name="gender" class="form-control" required='true'>
                                            <option value="">Select Gender</option>
                                            <option value="Female">Female</option>
                                            <option value="Male">Male</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label style="color: red;font-weight: bold;font-size: 18px;">Email:</label>
                                        <input class="form-control" name="email" id="email" type="email" placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-sm-6">
    <div class="form-group">
        <label style="color: red;font-weight: bold;font-size: 18px;padding-left: 20px;">Select Service:</label>
        <select name="catid" class="form-control" id="catid" required='true' onchange="updateServiceType()">
            <option value="">Select Service</option>
            <?php 
            $sql2 = "SELECT * from tblcategory";
            $query2 = $dbh->prepare($sql2);
            $query2->execute();
            $result2 = $query2->fetchAll(PDO::FETCH_OBJ);

            foreach ($result2 as $row2) {
            ?>
            <option value="<?php echo htmlentities($row2->ID);?>">
                <?php echo htmlentities($row2->CategoryName);?>
            </option>
            <?php } ?>
        </select>
    </div>
</div>

<input type="hidden" name="service_type" id="service_type_hidden">

<script>
    function updateServiceType() {
        var selectElement = document.getElementById("catid");
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var serviceTypeHiddenInput = document.getElementById("service_type_hidden");
        serviceTypeHiddenInput.value = selectedOption.text;
    }
</script>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label style="color: red;font-weight: bold;font-size: 18px;">Work Shift From:</label>
                                        <input class="form-control" name="wsf" id="wsf" type="time">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label style="color: red;font-weight: bold;font-size: 18px;">Work Shift To:</label>
                                        <input class="form-control" name="wst" id="wst" type="time">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label style="color: red;font-weight: bold;font-size: 18px;">Date:</label>
                                        <input class="form-control" name="startdate" id="startdate" type="date">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label style="color: red;font-weight: bold;font-size: 18px;">Notes:</label>
                                        <textarea class="form-control" name="notes" id="notes" type="text"
                                            placeholder="Job Description"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label style="color: red;font-weight: bold;font-size: 18px;">Skills:</label>
                                        <input class="form-control" name="skills" id="skills" type="text"
                                            placeholder="Enter Maid's Skills">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label style="color: red;font-weight: bold;font-size: 18px;">Education Level:</label>
                                        <input class="form-control" name="education_level" id="education_level" type="text"
                                            placeholder="Enter Maid's Education Level">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label style="color: red;font-weight: bold;font-size: 18px;">Experience Years:</label>
                                        <input class="form-control" name="experience_years" id="experience_years" type="number"
                                            placeholder="Enter Maid's Experience Years">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-3 text-center">
                                <button type="submit" class="button button-contactForm boxed-btn" name="submit">Send</button>
                            </div>
                        </form>

                        <!-- Display the expiration time -->
                        <div class="row">
                            <div class="col-12">
                                <p style="color: red; font-weight: bold; font-size: 18px;">Job Expiration Time: <?php echo $expiry_time_formatted; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include_once('includes/footer.php');?>

    <!-- JS here -->
    <!-- Include your JS scripts here -->
</body>

</html>


<?php include_once('includes/footer.php');?>

<!-- All JS Custom Plugins Link Here here -->
<script src="./assets/js/vendor/modernizr-3.5.0.min.js"></script>
<!-- Jquery, Popper, Bootstrap -->
<script src="./assets/js/vendor/jquery-1.12.4.min.js"></script>
<script src="./assets/js/popper.min.js"></script>
<script src="./assets/js/bootstrap.min.js"></script>
<!-- Jquery Mobile Menu -->
<script src="./assets/js/jquery.slicknav.min.js"></script>

<!-- Jquery Slick , Owl-Carousel Plugins -->
<script src="./assets/js/owl.carousel.min.js"></script>
<script src="./assets/js/slick.min.js"></script>
<script src="./assets/js/price_rangs.js"></script>

<!-- One Page, Animated-HeadLin -->
<script src="./assets/js/wow.min.js"></script>
<script src="./assets/js/animated.headline.js"></script>

<!-- Scrollup, nice-select, sticky -->
<script src="./assets/js/jquery.scrollUp.min.js"></script>
<script src="./assets/js/jquery.nice-select.min.js"></script>
<script src="./assets/js/jquery.sticky.js"></script>
<script src="./assets/js/jquery.magnific-popup.js"></script>

<!-- contact js -->
<script src="./assets/js/contact.js"></script>
<script src="./assets/js/jquery.form.js"></script>
<script src="./assets/js/jquery.validate.min.js"></script>
<script src="./assets/js/mail-script.js"></script>
<script src="./assets/js/jquery.ajaxchimp.min.js"></script>

<!-- Jquery Plugins, main Jquery -->
<script src="./assets/js/plugins.js"></script>
<script src="./assets/js/main.js"></script>
</body>

</html>
