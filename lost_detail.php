<?php
// Include the database connection file
include 'dbConn.php';

session_start();

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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve lost pet data from the form
    $lostName = mysqli_real_escape_string($conn, $_POST['lost-name']);
    $lostBreed = mysqli_real_escape_string($conn, $_POST['lost-breed']);
    $lostAge = mysqli_real_escape_string($conn, $_POST['lost-age']);
    $lostBody = mysqli_real_escape_string($conn, $_POST['lost-body']);
    $lostGender = mysqli_real_escape_string($conn, $_POST['lost-gender']);
    $lostLocation = mysqli_real_escape_string($conn, $_POST['lost-location']);
    $lostDate = mysqli_real_escape_string($conn, $_POST['lost-date']);
    $lostOwnerName = mysqli_real_escape_string($conn, $_POST['lost-owner-name']);
    $lostOwnerPhone = mysqli_real_escape_string($conn, $_POST['lost-owner-phone']);
    $lostOwnerEmail = mysqli_real_escape_string($conn, $_POST['lost-owner-email']);

    // Check if file upload was successful
    if ($lostPhoto !== false) {
        // Insert pet data into LostPet Entity
        $insert_lost_query = "INSERT INTO LostPet (User_ID, Lost_Name, Lost_Breed, Lost_Age, Lost_Body, Lost_Gender, Lost_Location, Pet_Photo, Pet_Date)
                              VALUES ('$user_details[User_ID]', '$lostName', '$lostBreed', '$lostAge', '$lostBody', '$lostGender', '$lostLocation', '$lostPhoto', '$lostDate')";

        $result_insert_lost = mysqli_query($conn, $insert_pet_query);

        if (!$result_insert_lost) {
            die("LostPet insertion failed: " . mysqli_error($conn));
        }

        // Redirect to the same page with success query parameter
        header("Location: lost_detail.php?lostpet=success");
        exit();
    } else {
        // File upload failed, handle the error
        echo "File upload failed.";
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
          <li><a class="nav-link " href="adopt.php">Adoption</a></li>
          <li><a class="nav-link " href="donate_detail.php">Donation</a></li>
          <li><a class="nav-link " href="volunteer.php">Volunteer</a></li>
          <li><a class="nav-link active" href="lost.php">Lost & Found</a></li>
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
            <h2>Pet Lost & Found</h2>
          </div>
  
        </div>
      </section><!-- Breadcrumbs Section -->
  
      <section class="volunteer-section section-padding" id="section_4">
        <div class="container">
            <div class="row">
    
                <div class="col-lg-6 col-12">
                    <h2 class="text-white mb-4">Lost & Found</h2>
    
                    <form class="custom-form volunteer-form mb-5 mb-lg-0" action="lostValidate.php" method="post" enctype="multipart/form-data" role="form">
                        <!-- Display success message if set -->
                            <div class="my-3">
                                <?php
                                if (isset($_GET['lostpet']) && $_GET['lostpet'] === 'success') {
                                    echo '<div class="alert alert-success" role="alert">Your rehome pet data has been stored. Thank you!</div>';
                                }
                                ?>
                                <div class="error-message"></div>
                            </div>
                        <h3 class="mb-4">Reuniting Lost Pets</h3>
    
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label for="lost-name">Pet Name</label>
                                <input type="text" name="lost-name" id="lost-name" class="form-control" placeholder="Ah Bii" required>
                            </div>

                            <div class="col-lg-6 form-group mt-3 mt-md-0">
                                <label for="lost-creed">Pet Breed</label>    
                                <input type="text" name="lost-breed" id="lost-breed" class="form-control" placeholder="Rabbit" required>
                            </div>

                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                            <label for="lost-age">Pet Age</label>
                                <input type="text" name="lost-age" id="lost-age" class="form-control" placeholder="1 Year 2 Months" required>
                            </div>

                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                            <label for="lost-body">Pet Body</label>
                                <input type="text" name="lost-body" id="lost-Body" class="form-control" placeholder="White Fur" required>
                            </div>

                            <div class="col-lg-6 form-group mt-3 mt-md-0">
                                <label for="lost-gender">Pet Gender</label>
                                <select name="lost-gender" id="lost-gender" class="form-control" required>
                                    <option value="Female">Female</option>
                                    <option value="Male">Male</option>
                                </select>
                            </div>
                            
                                                        
                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="lost-location">Pet Location</label>
                                <input type="text" name="lost-location" id="lost-location"  class="form-control" placeholder="Kuala Lumpur" required>
                            </div>
                            
                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="lost-date">Lost Date</label>
                                <input type="date" name="lost-date" id="lost-date" class="form-control" placeholder="2020-03-01" required>
                            </div>

                             <div class="col-lg-6 form-group">
                                <label for="lost-owner-name">Name</label>
                                <!-- Pre-fill lost name with user's name -->
                                <input type="text" name="lost-owner-name" id="lost-owner-name" class="form-control" placeholder="Name" value="<?php echo $user_details['User_Name']; ?>" required>
                            </div>

                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="lost-owner-phone">Phone Number</label>
                                <!-- Pre-fill lost phone with user's phone -->
                                <input type="tel" name="lost-owner-phone" id="lost-owner-phone" pattern="[0-9]{10,11}" class="form-control" placeholder="Phone" value="<?php echo $user_details['User_Phone']; ?>" required>
                            </div>

                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="lost-owner-email">Email</label>
                                <!-- Pre-fill lost email with user's email -->
                                <input type="email" name="lost-owner-email" id="lost-owner-email" pattern="[^ @]*@[^ @]*" class="form-control" placeholder="Email" value="<?php echo $user_details['User_Email']; ?>" required>
                            </div>
                            

                            <div class="form-group mt-3">
                                <label for="lost-photo">Upload your Pet photo</label>
                                <div class="input-group">
                                    <!-- Add the "name" attribute to the file input -->
                                    <input type="file" class="form-control" id="lost-photo" name="lost-photo" accept=".jpeg, .png, .jpg" required>
                                </div>
                            </div>
    
                        </div>

                        <button type="submit" class="form-control">Submit</button>
                        
                    </form>
                </div>
                
                <div class="col-lg-6 col-12">
                    <img src="images/icon.png" class="volunteer-image img-fluid" alt="">
    
                    <div class="custom-block-body text-center">
                        <h4 class="text-white mt-lg-3 mb-lg-3">About Losing And Finding</h4>
    
                        <p class="text-white">Lost But Not Forgotten.<br>
                            Our Pet Lost & Found Feature Brings Hope And Reunites Furry Friends With Their Loving Families.<br>
                            Together, We Can Make Every Lost Pet A Found Pet.</p>
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
  