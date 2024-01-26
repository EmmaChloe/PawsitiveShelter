<?php
// Include the database connection file
include 'dbConn.php';

session_start();

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
  <link href="css/form.css" rel="stylesheet">

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
          <li><a class="nav-link active" href="adopt.php">Adoption</a></li>
          <li><a class="nav-link " href="donate_detail.php">Donation</a></li>
          <li><a class="nav-link " href="volunteer.php">Volunteer</a></li>
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <h2>Terms and Conditions</h2>
          </div>
  
        </div>
      </section><!-- Breadcrumbs Section -->
  
      <!-- Single Post Start-->
     <div class="single">
      <div class="container">
          <div class="row">
              <div class="col-lg-8 col-12 mx-auto">
                  <div class="single-content">
                      <br>
                      <h3>Terms and Conditions of Adoption:</h3>
                      <p>
                        By adopting an animal through the Pawsitive Shelter Management System (PSMS), hereafter referred to as "the System," adopters, hereafter referred to as "the Adopter," agree to the following terms and conditions:                      
                        </p>
                        <br>
                        <h4>1. Adoption Limit:</h4>
                      <p>
                          - Each Adopter is allowed to adopt a maximum of three pets within a year from the date of the first adoption.
                          <br>
                          - The adoption limit is in place to promote responsible pet ownership, ensuring that each adopted pet receives adequate care, attention, and a loving home.
                      </p>
                      <br>
                      <h4>2. Responsibilities of the Adopter:</h4>
                      <p>
                          - The Adopter assumes full responsibility for the animal's well-being, including food bills, health care, and general care.
                          <br>
                            - Promptly inform PSMS if the adopted animal is lost or stolen.
                            <br>
                            - Allow periodic checks by PSMS to ensure the animal's well-being and compliance with adoption conditions.
                      </p>
                      <br>
                      <h4>3. Reclamation Rights of PSMS:</h4>
                      <p>
                          - PSMS retains the right to reclaim the animal if neglect, abuse, inadequate care, or a breach of contract is observed.
                          <br>
                            - The Adopter agrees to relinquish custody immediately upon request until the issues are rectified.
                      </p>
                <br>
                      <h4>4. Return Policy:</h4>
                      <p>
                          - Notify PSMS if unable to keep the animal and return it promptly, without transferring to another household or shelter.
                          <br>
                            - Provide one week's notice or pay boarding kennel fees for returning the animal.
                      </p>
                      <br>
                      <h4>5. Animal Care Standards:</h4>
                      <p>
                          - Follow proper veterinary care, including annual vaccinations, heartworm testing (for dogs), and adherence to state and federal laws.
                          <br>
                            - Animals must be kept as house pets, with no surprise gifting. Transportation should be safe, and a current license is mandatory.
                      </p>
                      <br>
                      <h4>6. Notification and Legal Actions:</h4>
                      <p>
                          - Notify PSMS of any change in address or phone number.
                          <br>
                            - Legal action expenses will be borne by the Adopter if necessary for reclamation or contract enforcement.
                      </p>
                      <br>
                      <h4>7. Euthanasia and Notification:</h4>
                      <p>
                          - Euthanasia, if necessary, must be performed by a licensed veterinarian.
                          <br>
                            - Notify PSMS in the event of the animal's death, with details on euthanasia, accident, illness, or natural causes.
                      </p>
                      <br>
                      <h4>8. Financial Responsibility:</h4>
                      <p>
                          - Assume full responsibility for all costs associated with the adopted animal.
                          <br>
                            - Acknowledge the potential transmission of diseases from animals to humans and hold PSMS harmless.
                      </p>
                      <br>
                      <h4>9. Liability and Legal Fees:</h4>
                      <p>
                          - Agree to pay legal fees and court costs if legal action is taken by PSMS.
                          <br>
                           - Hold PSMS harmless from any liability related to the adoption placement.
                      </p>
                      <br>
                      <h4>10. Policy Period:</h4>
                        <p>
                        This policy is effective indefinitely, starting from 01 January 2024.</h4>
                        </p>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- Single Post End-->
    
    
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
              <li><i class="bx bx-chevron-right"></i> <a href="volunteer.php">Volenteer</a></li>
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