<?php 
    include 'dbConn.php';
    session_start(); // Make sure to start the session if not started


// Assuming you get the News_ID from the URL or another source
if (isset($_GET['news_id'])) {
    $news_id = $_GET['news_id'];

    // Fetch details for the selected news
    $fetch_news_query = "SELECT * FROM News WHERE News_ID = $news_id";
    $result = mysqli_query($conn, $fetch_news_query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $news_details = mysqli_fetch_assoc($result);
} else {
    // Redirect to a page where users can select a news if no News_ID is provided
    header('Location: news.php');
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
          <li><a class="nav-link " href="lost.php">Lost & Found</a></li>
          <li><a class="nav-link active" href="news.php">News</a></li>
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

     <!-- Single Post Start-->
<div class="single">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="single-content">
                    <center>
                        <?php
                        // Display news images dynamically
                        $image_folder = "";
        
                        // Check if ImageURL is not empty
                        if (!empty($news_details['Publish_Photo'])) {
                          // Display the image based on the ImageURL
                          echo '<img src="' . $image_folder . $news_details['Publish_Photo'] . '" alt="' . $news_details['Publish_Title'] . '" style="width: 50%; height: auto;">';
                        } else {
                          // Display a default image if ImageURL is empty
                          echo '<img src="images/icon.png" alt="Default News Image">';
                        }
                        ?>
                    </center>
                    <h3><?php echo $news_details['Publish_Title']; ?></h3>
                    <br>
                    <?php
                    // Display the content with special symbols in the next paragraph
                    echo "<p class='justified-text'>" . nl2br(htmlspecialchars($news_details['Publish_Content'])) . "</p>";
                    ?>
                    <!-- Add more dynamic content here based on your News entity fields -->
                </div>
                <div class="single-bio">
                    <div class="single-bio-text">
                        <h3><?php echo $news_details['Author']; ?></h3>
                        <p><?php echo $news_details['Publish_Date']; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Single Post End-->

<?php
if (!isset($news_details)) {
    // Handle the case where $news_details is not set or not valid
    echo "News details not available.";
} else {
?>
<!-- Single Post Start-->
<div class="single">
    <!-- Rest of the code for displaying the news details -->
</div>
<!-- Single Post End-->
<?php
}
?>



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
