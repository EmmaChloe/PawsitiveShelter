<?php
session_start();


require 'dbConn.php';

// Check if the user is logged in
if (isset($_SESSION["username"])) {
    // Fetch user details from the database using $_SESSION["username"]
    $user_details = array(
        'User_ID' => 1,
        'User_Name' => 'John Doe',
        'User_Phone' => '123456789',
        'User_Email' => 'john.doe@example.com'
    );
} else {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Fetch user information
$username = $_SESSION['username'];
$fetch_user_query = "SELECT * FROM User WHERE User_Username = '$username'";
$result_user = mysqli_query($conn, $fetch_user_query);

if (!$result_user) {
    die("Query failed: " . mysqli_error($conn));
}

$user_details = mysqli_fetch_assoc($result_user);

// Assume you get Pet_ID from the URL or another source
if (isset($_GET['pet_id'])) {
    $pet_id = $_GET['pet_id'];

    // Process adoption form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $adoptor_name = $_POST['adoptor-name'];
        $adoptor_email = $_POST['adoptor-email'];
        $adoptor_phone = $_POST['adoptor-phone'];
        $adoptor_date = $_POST['adoptor-date'];
        $adoptor_home = $_POST['adoptor-home'];
        $adoptor_previous = $_POST['adoptor-previous'];

        // Additional code to get the adoptor address
    $adoptor_address = mysqli_real_escape_string($conn, $_POST['adoptor-address']);

    // Insert adoption data into Adoption Entity
    $insert_adoption_query = "INSERT INTO Adoption (User_ID, Pet_ID, Adoption_Date, Home_Environment, Previous_Pet_Experience, Adoption_Address)
                              VALUES ('$user_details[User_ID]', '$pet_id', '$adoptor_date', '$adoptor_home', '$adoptor_previous', '$adoptor_address')";
    $result_insert_adoption = mysqli_query($conn, $insert_adoption_query);

    if (!$result_insert_adoption) {
        die("Adoption insertion failed: " . mysqli_error($conn));
    }

    // Redirect to the same page with success query parameter
    header("Location: {$_SERVER['PHP_SELF']}?pet_id=$pet_id&adoption=success");
    exit();
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
            <h2>Pet Adoption</h2>
          </div>
  
        </div>
      </section><!-- Breadcrumbs Section -->
  
      <section class="volunteer-section section-padding" id="section_4">
        <div class="container">
            <div class="row">
    
                <div class="col-lg-6 col-12">
                    <h2 class="text-white mb-4">Adoption</h2>
    
                    <form class="custom-form volunteer-form mb-5 mb-lg-0" action="#" method="post" role="form">
                        <!-- Display success message if set -->
                            <div class="my-3">
                                <?php
                                if (isset($_GET['adoption']) && $_GET['adoption'] === 'success') {
                                    echo '<div class="alert alert-success" role="alert">Your adoption form has been sent. Thank you!</div>';
                                }
                                ?>
                                <div class="error-message"></div>
                            </div>
                        <h3 class="mb-4">Become an adoptor today</h3>
    
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label for="adoptor-name">Name</label>
                                <!-- Pre-fill adoptor name with user's name -->
                                <input type="text" name="adoptor-name" id="adoptor-name" class="form-control" placeholder="Name" value="<?php echo $user_details['User_Name']; ?>" required>
                            </div>
                        
                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="adoptor-email">Email</label>
                                <!-- Pre-fill adoptor email with user's email -->
                                <input type="email" name="adoptor-email" id="adoptor-email" pattern="[^ @]*@[^ @]*" class="form-control" placeholder="Email" value="<?php echo $user_details['User_Email']; ?>" required>
                            </div>
                        
                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="adoptor-phone">Phone Number</label>
                                <!-- Pre-fill adoptor phone with user's phone -->
                                <input type="tel" name="adoptor-phone" id="adoptor-phone" pattern="[0-9]{10,11}" class="form-control" placeholder="Phone" value="<?php echo $user_details['User_Phone']; ?>" required>
                            </div>
    
                            <div class="col-lg-6 form-group mt-3 mt-md-0">
                                <label for="adoptor-date">Date of apply</label>
                                <!-- Set the default value to the current date -->
                                <input type="date" name="adoptor-date" id="adoptor-date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
    
                            <div class="col-lg-6 form-group mt-3 mt-md-0">
                                <label for="adoptor-home">Home Environment</label>
                                <select name="adoptor-home" id="adoptor-home" class="form-control" required>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Good">Good</option>
                                    <option value="Normal">Normal</option>
                                    <option value="Bad">Bad</option>
                                    <option value="Worst">Worst</option>
                                </select>
                            </div>
                            
                            <div class="col-lg-6 form-group mt-3 mt-md-0">
                                <label for="adoptor-previous">Previous Adopt Pet Experience</label>
                                <select name="adoptor-previous" id="adoptor-previous" class="form-control" required>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
    
                        </div>
                        
                        <label for="adoptor-address">Address</label>
                        <textarea name="adoptor-address" rows="3" class="form-control" id="adoptor-address" placeholder="Address" required></textarea>
                        
                        <div class="tacbox">
                          <input id="checkbox" type="checkbox" required/>
                          <label for="checkbox"> I agree to these <a href="terms.php">Terms and Conditions</a>.</label>
                        </div>
                        
                         <button type="submit" class="form-control">Submit</button>
                            
                    </form>
                </div>
    
                <div class="col-lg-6 col-12">
                    <img src="images/icon.png" class="volunteer-image img-fluid" alt="">
    
                    <div class="custom-block-body text-center">
                        <h4 class="text-white mt-lg-3 mb-lg-3">About Adopting</h4>
    
                        <p class="text-white">Adopting a pet is not just about giving them a home, <br>
                            but also about finding a lifelong companion who will <br>
                            fill your days with unconditional love and joy.</p>
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