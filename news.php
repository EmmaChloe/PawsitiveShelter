<?php
// Include the database connection file
include 'dbConn.php';
session_start(); // Make sure to start the session if not started

// Function to get news data from the database
function getNewsData($conn) {
    $sql = "SELECT * FROM News"; // Adjust the query based on your database structure
    $result = mysqli_query($conn, $sql);

    $newsData = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $newsData[] = $row;
    }

    return $newsData;
}

// Get news data from the database
$newsData = getNewsData($conn);
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
  </header><!-- End Header -->

  <!-- banner -->
  <section class="banner">
    <div class="row">
      <div class="content">
        <h3>News</h3>
        <p>Bringing Hope and Happiness to Every Paw: Unleashing the Potential of
          the Pets Shelter Management System in Transforming Lives.</p>
      </div>

      <div class="image">
        <img src="images/news.png" alt="">
      </div>
    </div>
  </section>
  <!-- end -->

  <!-- Blog Start -->
  <div class="blog">
    <div class="container">
        <div class="row">
            <?php
      // Display news
      $posts_per_page = 6; // You can adjust this number based on your preference
      $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
      $start = ($page - 1) * $posts_per_page;

      $pagination_query = "SELECT * FROM News LIMIT $start, $posts_per_page";
      $pagination_result = mysqli_query($conn, $pagination_query);

      while ($row = mysqli_fetch_assoc($pagination_result)) {
      ?>
          <div class="col-lg-4">
            <div class="blog-item">
              <div class="blog-img">
                <?php
                // Display news images dynamically
                $image_folder = "";

                // Check if ImageURL is not empty
                if (!empty($row['Publish_Photo'])) {
                  // Display the image based on the ImageURL
                  echo '<img src="' . $image_folder . $row['Publish_Photo'] . '" alt=" '. $row['Publish_Title'] . '">';
                } else {
                  // Display a default image if ImageURL is empty
                  echo '<img src="images/icon.png" alt="Default News Image">';
                }
                ?>
              </div>
              <div class="blog-text">
                <h3><a href="news_detail.php?news_id=<?php echo $row['News_ID']; ?>"><?php echo $row['Publish_Title']; ?></a></h3>
                <p>
                  <?php
                  $content = $row['Publish_Content'];
                  $wordArray = str_word_count($content, 1);
                  $limitedContent = implode(' ', array_slice($wordArray, 0, 50));
                  echo $limitedContent . (count($wordArray) > 50 ? '...' : ''); // Add ellipsis if content exceeds 50 words
                  ?>
                </p>
              </div>
              <div class="blog-meta">
                <p><i class="fa fa-user"></i><a href="#"><?php echo $row['Author']; ?></a></p>
              </div>
            </div>
          </div>
           <?php } ?>
        </div>
    </div>
  </div>
  <!-- Blog End -->

  <!-- Pagination -->
  <div class="blog">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <ul class="pagination justify-content-center">
            <?php
            // Count total number of records
            $total_records_query = "SELECT COUNT(*) as total FROM News";
            $total_records_result = mysqli_query($conn, $total_records_query);
            $total_records = mysqli_fetch_assoc($total_records_result)['total'];
            $total_pages = ceil($total_records / $posts_per_page);

            // Previous button
            if ($page > 1) {
              echo '<li class="page-item"><a class="page-link" href="news.php?page=' . ($page - 1) . '">Previous</a></li>';
            } else {
              echo '<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>';
            }

            // Page numbers
            for ($i = 1; $i <= $total_pages; $i++) {
              echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '"><a class="page-link" href="news.php?page=' . $i . '">' . $i . '</a></li>';
            }

            // Next button
            if ($page < $total_pages) {
              echo '<li class="page-item"><a class="page-link" href="news.php?page=' . ($page + 1) . '">Next</a></li>';
            } else {
              echo '<li class="page-item disabled"><a class="page-link" href="#">Next</a></li>';
            }
            ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <br>

  <section id="empty" class="empty section-bg"></section>

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
