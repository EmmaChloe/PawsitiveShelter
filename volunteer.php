<?php
session_start();

require 'dbConn.php';


// Process volunteer form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $volunteerDate = $_POST['volunteer-date'];
    $volunteerReason = $_POST['volunteer-reason'];

    // Check if a file was uploaded without errors
    if (isset($_FILES['volunteer-cv']) && $_FILES['volunteer-cv']['error'] == 0) {
        $cv_temp = $_FILES['volunteer-cv']['tmp_name'];
        $cv_name = $_FILES['volunteer-cv']['name'];
        $cv_path = "cv/" . $cv_name; // Set the path as per your requirements

        // Move the uploaded file to the desired location
        if (move_uploaded_file($cv_temp, $cv_path)) {
            // Insert volunteer data into Volunteer Entity using prepared statements
            $insert_volunteer_query = "INSERT INTO Volunteer (User_ID, Apply_Date, Apply_CV, Apply_Reason, Volunteer_Name, Volunteer_Email, Volunteer_Phone)
                                        VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_volunteer_query);
            mysqli_stmt_bind_param($stmt, "issssss", $user_details['User_ID'], $volunteerDate, $cv_path, $volunteerReason, $volunteerName, $volunteerPhone, $volunteerEmail);

            if (mysqli_stmt_execute($stmt)) {
                // Redirect to the same page with success query parameter
                header("Location: volunteer.php?volunteer=success");
                exit();
            } else {
                $error_message = "Volunteer insertion failed: " . mysqli_error($conn);
                error_log($error_message);
                header("Location: volunteer.php?error=volunteerinsertfailed");
                exit();
            }
        } else {
            $error_message = "Error: File move failed.";
            error_log($error_message);
            echo $error_message;
        }
    } else {
        $error_message = "Error: No file uploaded.";
        error_log($error_message);
        echo $error_message;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

  <title>Pawsitive Shelter</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="images/icon.png" rel="icon">
  <link href="images/icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="vendor/aos/aos.css" rel="stylesheet">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Squadfree
  * Updated: May 30 2023 with Bootstrap v5.3.0
  * Template URL: https://bootstrapmade.com/squadfree-free-bootstrap-template-creative/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top header-transparent">
    <div class="container d-flex align-items-center justify-content-between position-relative">

      <div class="logo">
        <h1 class="text-light"><a href="index.php"><img src="images/logo.png" alt=""></a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="index.php"><img src="img/logo.png" alt="" class="img-fluid"></a>-->
      </div>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link " href="index.php">Home</a></li>
          <li><a class="nav-link " href="adopt.php">Adoption</a></li>
          <li><a class="nav-link " href="donate_detail.php">Donation</a></li>
          <li><a class="nav-link active" href="volunteer.php">Volunteer</a></li>
          <li><a class="nav-link " href="lost.php">Lost & Found</a></li>
          <li><a class="nav-link " href="news.php">News</a></li>
          <li class="dropdown">
            <?php
            if (!isset($_SESSION["username"])) {
                echo '<a href="login.php">Log in</a>';
            } else {
                echo '<a href="#"><span>' . $_SESSION['username'] . '</span> <i class="bi bi-chevron-down"></i></a>';
                echo '<ul>
                        <li><a href="user.php">Profile</a></li>
                        <li><a href="logout.php">Log Out</a></li>
                      </ul>';
            }
            ?>
        </li>
          
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

<!-- banner -->

<section class="banner">

    <div class="row">
        
        <div class="content">
            <h3>Volunteer</h3>
        <p>Make A Paw-Sitive Impact On The Lives Of Animals In Need
            By Becoming A Volunteer And Lend Your Heart And Hands To Their Well-Being.</p> 
        </div>

        <div class="image">
            <img src="images/volunteer.png" alt="">
        </div>
        
    </div>

</section>

<!-- end -->

<section class="volunteer-section section-padding" id="section_4">
    <div class="container">
        <div class="row">

            <div class="col-lg-6 col-12">
                <h2 class="text-white mb-4">Volunteer</h2>

                <form class="custom-form volunteer-form mb-5 mb-lg-0" method="post" action="volunteerValidate.php" enctype="multipart/form-data" role="form">
                    <!-- Display success message if set -->
                            <div class="my-3">
                                
                                <?php
                                if (isset($_GET['volunteer']) && $_GET['volunteer'] === 'success') {
                                    echo '<div class="alert alert-success" role="alert">Your volunteer form has been submitted. Thank you!</div>';
                                } elseif (isset($_GET['error']) && $_GET['error'] === 'validation') {
                                    echo '<div class="alert alert-danger" role="alert">Please fill out all required fields.</div>';
                                } elseif (isset($_GET['error']) && $_GET['error'] === 'submission') {
                                    echo '<div class="alert alert-danger" role="alert">An error occurred during form submission. Please try again.</div>';
                                } elseif (isset($_GET['error']) && $_GET['error'] === 'volunteerinsertfailed') {
                                    echo '<div class="alert alert-danger" role="alert">Volunteer insertion failed. Please try again later.</div>';
                                }
                                ?>

                                <div class="error-message"></div>
                            </div>

                    <h3 class="mb-4">Become a volunteer today</h3>

                    <div class="row">
                        <div class="col-lg-6 form-group">
                                <label for="volunteer-name">Name</label>
                                <!-- Pre-fill volunteer name with user's name -->
                                <input type="text" name="volunteer-name" id="volunteer-name" class="form-control" placeholder="Name" required>
                            </div>
                        
                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="volunteer-email">Email</label>
                                <!-- Pre-fill volunteer email with user's email -->
                                <input type="email" name="volunteer-email" id="volunteer-email" pattern="[^ @]*@[^ @]*" class="form-control" placeholder="Email" required>
                            </div>
                            
                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="volunteer-phone">Phone Number</label>
                                <!-- Pre-fill volunteer phone with user's phone -->
                                <input type="tel" name="volunteer-phone" id="volunteer-phone" pattern="[0-9]{10,11}" class="form-control" placeholder="Phone" required>
                            </div>
                            
                            <div class="col-lg-6 form-group mt-3 mt-md-0">
                                <label for="volunteer-date">Date of apply</label>
                                <!-- Set the default value to the current date -->
                                <input type="date" name="volunteer-date" id="volunteer-date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
    

                            <div class="form-group mt-3">
                                <label for="volunteer-cv">Upload your CV</label>
                                <div class="input-group">
                                    <!-- Add the "name" attribute to the file input -->
                                    <input type="file" class="form-control" id="volunteer-cv" name="volunteer-cv" accept=".pdf" required>
                                </div>
                            </div>
                    </div>
                    
                    <label for="volunteer-reason">Reason</label>
                    <textarea name="volunteer-reason" rows="3" class="form-control" id="volunteer-reason" placeholder="Reason" required></textarea>

                    <button type="submit" class="form-control">Submit</button>
                    
                </form>
            </div>

            <div class="col-lg-6 col-12">
                <img src="images/icon.png" class="volunteer-image img-fluid" alt="">

                <div class="custom-block-body text-center">
                    <h4 class="text-white mt-lg-3 mb-lg-3">About Volunteering</h4>

                    <p class="text-white">Volunteers don't necessarily have the time; <br>
                        they just have the heart."
                        <br> - Elizabeth Andrew</p>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- ======= Contact Section ======= -->
<section id="contact" class="contact section-bg">

  </section><!-- End Contact Section -->


    <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-6 col-md-6">
            <div class="footer-info">
              <img src="images/logo.png" alt="" width="300" height="60">
              <br>
              <br>
              <strong>PAWSITIVE SHELTER MANGEMENT SYSTEM</strong>
              <br>
              <br>
              <p>
                UMSKAL Labuan <br>
                87000 Malaysia<br><br>
              <p class="pb-3"><em>Enhancing Efficiency, Adoption, and Care.</em></p>
              </p>
            </div>
          </div>

          <div class="col-lg-2 col-md-6 footer-links"></div>

          <div class="col-lg-2 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="index.php">Home</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="adopt.php">Adoption</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="donate_detail.php">Donation</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="volunteer.php">Volunteer</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="lost.php">Lost & Found</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="news.php">News</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="terms.php">Terms and Condition</a></li>
            </ul>
          </div>



          </div>

        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; 2023 Copyright <strong><span>Pawsitive</span></strong>. All Rights Reserved.
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/squadfree-free-bootstrap-template-creative/ -->
        For Educational Purpose Only.
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="vendor/aos/aos.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/glightbox/js/glightbox.min.js"></script>
  <script src="vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="vendor/swiper/swiper-bundle.min.js"></script>
  <script src="vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="js/main.js"></script>

  <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

    <script src="js/script.js"></script>

</body>
</html>
