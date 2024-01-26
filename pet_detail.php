<?php
session_start();

require 'dbConn.php';

// Assuming you get the Lost_ID from the URL or another source
if (isset($_GET['lost_id'])) {
    $lost_id = $_GET['lost_id'];

    // Fetch details for the selected lost pet along with user details
    $fetch_pet_query = "SELECT LostPet.*, User.User_Name, User.User_Phone, User.User_Email
                   FROM LostPet
                   JOIN User ON LostPet.User_ID = User.User_ID
                   WHERE LostPet.Lost_ID = $lost_id";

    $result = mysqli_query($conn, $fetch_pet_query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $pet_details = mysqli_fetch_assoc($result);

    // Check if the pet is found
    if (!$pet_details) {
        // Redirect to a page where users can select a pet if no pet is found with the given Lost_ID
        header('Location: lost.php');
        exit();
    }
} else {
    // Redirect to a page where users can select a pet if no Lost_ID is provided
    header('Location: lost.php');
    exit();
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
          <li><a class="nav-link " href="volunteer.php">Volunteer</a></li>
          <li><a class="nav-link active" href="lost.php">Lost & Found</a></li>
          <li><a class="nav-link " href="news.php">News</a></li>
         <li class="dropdown active">
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

<!-- ======= Counts Section ======= -->
<section id="counts" class="counts  section-bg">
    <div class="container">

    </div>
  </section><!-- End Counts Section -->

      <!-- ======= Breadcrumbs Section ======= -->
      <section class="breadcrumbs">
        <div class="container">
  
          <div class="d-flex justify-content-between align-items-center">
            <h2>Pet Details</h2>
          </div>
  
        </div>
      </section><!-- Breadcrumbs Section -->
  
      <!-- ======= Portfolio Details Section ======= -->
      <section id="portfolio-details" class="portfolio-details">
        <div class="container">
  
          <div class="row gy-4">
  
            <div class="col-lg-8">
              <div class="portfolio-details-slider swiper">
                <div class="swiper-wrapper align-items-center">
  
                  <?php
                            // Display pet images dynamically
                            $image_folder = "images/lost/";

                            // Check if Pet_Photo is not empty
                            if (!empty($pet_details['Lost_Photo'])) {
                                // Assume Pet_Photo is a comma-separated list of image file names
                                $photos = explode(',', $pet_details['Lost_Photo']);

                                // Loop through pet images
                                foreach ($photos as $photo) {
                                    echo '<div class="swiper-slide">';
                                    echo '<img src="' . $image_folder . $photo . '" alt="">';
                                    echo '</div>';
                                }
                            } else {
                                // Display a default image if Pet_Photo is empty
                                echo '<div class="swiper-slide">';
                                echo '<img src="images/icon.png" alt="Default Pet Photo">';
                                echo '</div>';
                            }
                            ?>
  
                </div>
                <div class="swiper-pagination"></div>
              </div>
            </div>
  
            <div class="col-lg-4">
              <div class="portfolio-info">
                <h3>Pet information</h3>
                <ul>
                  <li><strong>Name</strong>: <?php echo $pet_details['Lost_Name']; ?></li>
                  <li><strong>Breed</strong>: <?php echo $pet_details['Lost_Breed']; ?></li>
                  <li><strong>Age</strong>: <?php echo $pet_details['Lost_Age']; ?></li>
                  <li><strong>Body</strong>: <?php echo $pet_details['Lost_Body']; ?></li>
                  <li><strong>Gender</strong>: <?php echo $pet_details['Lost_Gender']; ?></li>
                  <li><strong>Lost Date</strong>: <?php echo $pet_details['Lost_Date']; ?></li>
                  <li><strong>Location</strong>: <?php echo $pet_details['Lost_Location']; ?></li>
                </ul>
              </div>
              <div class="portfolio-description">
                <center><h2>For Lost & Found</h2></center>
                <center><p>
                    Let's reunite lost pets with their families.
                </p></center>
              </div>
              <div class="portfolio-info">
                <h3>Owner information</h3>
                <ul>
                  <li><strong>Name</strong>: <?php echo $pet_details['User_Name']; ?></li>
                  <li><strong>Phone</strong>: <?php echo $pet_details['User_Phone']; ?></li>
                  <li><strong>Email</strong>: <?php echo $pet_details['User_Email']; ?></li>
                </ul>
              </div>
            </div>
  
          </div>
  
        </div>
      </section><!-- End Portfolio Details Section -->
  
    </main><!-- End #main -->



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
