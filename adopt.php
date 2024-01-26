<?php
session_start();

require 'dbConn.php';

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
  
  <!-- banner -->

  <section class="banner">

    <div class="row">
        
        <div class="content">
            <h3>Adopt</h3>
        <p>Adopting a pet is not just about saving them,
             it's about finding a lifelong companion 
             who will fill your life with love and joy.</p> 
        </div>

        <div class="image">
            <img src="images/adopt.png" alt="">
        </div>
        
    </div>

</section>

<!-- end -->

<!-- ======= Cta Section ======= -->
<section id="cta" class="cta">
    <div class="container" data-aos="zoom-in">

      <div class="text-center">
        <h3>Rehoming Your Pet?</h3>
        <p> Register your pet for rehoming today, and let's make a positive difference in their lives.<br>Your pet's happiness is just a step away.</p>
        <a class="cta-btn" href="rehome_detail.php">Register</a>
      </div>

    </div>
  </section><!-- End Cta Section -->

  <br>
<br>
<br>

<!-- input tag -->
<input id="searchbar" onkeyup="search_animal()" type="text" name="search" placeholder="Search pet breed..">

<script>
function search_animal() {
    // Get input value and convert to lowercase for case-insensitive comparison
    var searchInput = document.getElementById("searchbar").value.toLowerCase();

    // Get all posts in the recent posts section
    var posts = document.querySelectorAll('#recent-posts .col-xl-4');

    // Loop through each post
    for (var i = 0; i < posts.length; i++) {
        // Get the post title, icon text, description text, and category text, and convert to lowercase
        var postCategory = posts[i].querySelector('.post-category').textContent.toLowerCase();

        // Check if the input value is found in the post title, icon, description, or category
        if (postCategory.includes(searchInput)) {
            // If found, show the post
            posts[i].style.display = "block";
        } else {
            // If not found, hide the post
            posts[i].style.display = "none";
        }
    }
}
</script>



    <!-- ======= Recent Blog Posts Section ======= -->
<section id="recent-posts" class="recent-posts sections-bg">
    <div class="container" data-aos="fade-up">
        <div class="row gy-4">
            <?php
            // Display pets
            $posts_per_page = 9; // You can adjust this number based on your preference
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $start = ($page - 1) * $posts_per_page;

            $pagination_query = "SELECT * FROM Pet LIMIT $start, $posts_per_page";
            $pagination_result = mysqli_query($conn, $pagination_query);

            while ($row = mysqli_fetch_assoc($pagination_result)) {
                ?>
                <div class="col-xl-4 col-md-6">
                    <article>
                        <div class="post-img">
                            <img src="images/adopt/<?php echo $row['Pet_Photo']; ?>" alt="" class="img-fluid">
                            
                            <?php
                            // Display pet images dynamically
                            $image_folder = "images/adopt/";
                        
                            // Check if Pet_Photo is not empty
                            if (!empty($row['Pet_Photo'])) {
                                // Assume Pet_Photo is a comma-separated list of image file names
                                $photos = explode(',', $row['Pet_Photo']);

                            } else {
                                // Display a default image if Pet_Photo is empty
                                echo '<img src="images/icon.png" alt="Default Pet Photo">';
                            }
                            ?>
                        </div>
                        <p class="post-category"><?php echo $row['Pet_Breed']; ?></p>
                        <h2 class="title">
                            <!-- Provide a link to pet.php with the specific Pet_ID -->
                            <a href="pet.php?pet_id=<?php echo $row['Pet_ID']; ?>"><?php echo $row['Pet_Name']; ?></a>
                        </h2>
                        <div class="d-flex align-items-center">
                            <div class="post-meta">
                                <?php
                                // Display additional pet information
                                echo '<i class="fas fa-star"><span class="post-author"> ' . $row['Pet_Body'] . ' </span></i><br>';
                                echo '<i class="fas fa-star"><span class="post-author"> ' . $row['Pet_Gender'] . ' </span></i><br>';
                                echo '<p class="post-date"><time datetime="' . $row['Pet_Date'] . '">' . $row['Pet_Date'] . ' | ' . $row['Pet_Location'] . '</time></p>';
                                ?>
                            </div>
                        </div>
                    </article>
                </div><!-- End post list item -->
            <?php
            }
            ?>
        </div><!-- End recent posts list -->
    </div>
</section><!-- End Recent Blog Posts Section -->

<!-- Pagination -->
<div class="blog">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="pagination justify-content-center">
                    <?php
                    // Count total number of records
                    $total_records_query = "SELECT COUNT(*) as total FROM Pet";
                    $total_records_result = mysqli_query($conn, $total_records_query);
                    $total_records = mysqli_fetch_assoc($total_records_result)['total'];
                    $total_pages = ceil($total_records / $posts_per_page);

                    // Previous button
                    if ($page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="adopt.php?page=' . ($page - 1) . '">Previous</a></li>';
                    } else {
                        echo '<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>';
                    }

                    // Page numbers
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '"><a class="page-link" href="adopt.php?page=' . $i . '">' . $i . '</a></li>';
                    }

                    // Next button
                    if ($page < $total_pages) {
                        echo '<li class="page-item"><a class="page-link" href="adopt.php?page=' . ($page + 1) . '">Next</a></li>';
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