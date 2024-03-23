 <!-- Preloader Start -->
 <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <strong>Intergrated Homestead Management System</strong>
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader Start -->

<?php
session_start();
// Check if the user is logged in
if (isset($_SESSION['valid'])) {
    // User is logged in, change the login button to logout
    $loginButtonText = 'Logout';
    $loginButtonLink = 'logout.php';
    $formfillLink = 'maid-hiring.php';
    $userProfileLink = 'edit.php'; // Adjust the link to your logout page
} else {
    // User is not logged in, keep the login button
    $loginButtonText = 'User Login';
    $loginButtonLink = 'login.php';
    $formfillLink = 'login.php';
    $userProfileLink = 'login.php'; // Adjust the link to your login page
}
?>

<header>
    <!-- Header Start -->
    <div class="header-area header-transparrent">
           <div class="headder-top header-sticky">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-3 col-md-2">
                            <!-- Logo -->
                            <div class="logo">
                                <a href="index.php"><img src="assets/img/logo/ss.jpg" alt="" width="250" height="120"></a>
                            </div>  
                        </div>
        <div class="col-lg-9 col-md-9">
            <div class="menu-wrapper">
                <!-- Main-menu -->
                <div class="main-menu">
                    <nav class="d-none d-lg-block">
                        <ul id="navigation">
                            <li><a href="index.php">Home</a></li>
                            <li><a href="<?php echo $formfillLink; ?>">Find a Maid / Driver</a></li>
                            <li><a href="about.php">About</a></li>
                            <li><a href="contact.php">Contact</a></li>
                            <li><a href="maid-login.php">Maid Login</a></li>
                            <li><a href="admin/login.php">Admin</a></li>
                            <li><a href="<?php echo $loginButtonLink; ?>"><?php echo $loginButtonText; ?></a></li>
                            <li><a href="<?php echo $userProfileLink; ?>">User Profile</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- Header-btn -->
            </div>
        </div>
        <!-- Mobile Menu -->
        <div class="col-12">
            <div class="mobile_menu d-block d-lg-none"></div>
        </div>
    </div>
</div>
<!-- Header End -->
</header>
