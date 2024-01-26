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
    // Retrieve pet data from the form
    $petName = mysqli_real_escape_string($conn, $_POST['pet-name']);
    $petBreed = mysqli_real_escape_string($conn, $_POST['pet-breed']);
    $petAge = mysqli_real_escape_string($conn, $_POST['pet-age']);
    $petBody = mysqli_real_escape_string($conn, $_POST['pet-body']);
    $petGender = mysqli_real_escape_string($conn, $_POST['pet-gender']);
    $petLocation = mysqli_real_escape_string($conn, $_POST['pet-location']);
    $petCondition = mysqli_real_escape_string($conn, $_POST['pet-condition']);
    $petVaccinated = mysqli_real_escape_string($conn, $_POST['pet-vaccinated']);
    $petDewormed = mysqli_real_escape_string($conn, $_POST['pet-dewormed']);
    $petNeutered = mysqli_real_escape_string($conn, $_POST['pet-neutered']);
    $rehomename = mysqli_real_escape_string($conn, $_POST['rehome-name']);
    $rehomephone = mysqli_real_escape_string($conn, $_POST['rehome-phone']);
    $rehomeemail = mysqli_real_escape_string($conn, $_POST['rehome-email']);
    $petDescription = mysqli_real_escape_string($conn, $_POST['pet-description']);
    $petPhoto = mysqli_real_escape_string($conn, $_POST['pet-photo']);
    $petDate = date("Y-m-d"); // or get the date from your form

    // Check if file upload was successful
    if ($petPhoto !== false) {
        // Insert pet data into Pet Entity
        $insert_pet_query = "INSERT INTO Pet (User_ID, Pet_Name, Pet_Breed, Pet_Age, Pet_Body, Pet_Gender, Pet_Location, Pet_Condition, Pet_Vaccinated, Pet_Dewormed, Pet_Neutered, Pet_Description, Pet_Photo, Pet_Date)
                              VALUES ('$user_details[User_ID]', '$petName', '$petBreed', '$petAge', '$petBody', '$petGender', '$petLocation', '$petCondition', '$petVaccinated', '$petDewormed', '$petNeutered', '$petDescription', '$petPhoto', '$petDate')";

        $result_insert_pet = mysqli_query($conn, $insert_pet_query);

        if (!$result_insert_pet) {
            die("Pet insertion failed: " . mysqli_error($conn));
        }

        // Redirect to the same page with success query parameter
        header("Location: rehome_detail.php?pet=success");
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
            <h2>Pet Rehome</h2>
          </div>
  
        </div>
      </section><!-- Breadcrumbs Section -->
  
      <section class="volunteer-section section-padding" id="section_4">
        <div class="container">
            <div class="row">
    
                <div class="col-lg-6 col-12">
                    <h2 class="text-white mb-4">Rehome</h2>
    
                    <form class="custom-form volunteer-form mb-5 mb-lg-0" action="rehomeValidate.php" method="post" role="form" enctype="multipart/form-data">
                        
                        <!-- Display success message if set -->
                            <div class="my-3">
                                <?php
                                if (isset($_GET['pet']) && $_GET['pet'] === 'success') {
                                    echo '<div class="alert alert-success" role="alert">Your rehome pet data has been stored. Thank you!</div>';
                                }
                                ?>
                                <div class="error-message"></div>
                            </div>
                        

                        <h3 class="mb-4">Reuniting and Rehoming Pets</h3>
    
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label for="pet-name">Pet Name</label>
                                <input type="text" name="pet-name" id="pet-name" class="form-control" placeholder="Ah Bii" required>
                                <div class="invalid-feedback">Please, enter your name!</div>
                            </div>

                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="pet-breed">Pet Breed</label>
                                <input type="text" name="pet-breed" id="pet-breed"  class="form-control" placeholder="Rabbit" required>
                            </div>

                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="pet-age">Pet Age</label>
                                <input type="text" name="pet-age" id="pet-age"  class="form-control" placeholder="1 Year 2 Months" required>
                            </div>

                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="pet-body">Pet Body</label>
                                <input type="text" name="pet-body" id="pet-body" class="form-control" placeholder="White Fur" required>
                            </div>
                            
                            
                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="pet-location">Pet Location</label>
                                <input type="text" name="pet-location" id="pet-location"  class="form-control" placeholder="Kuala Lumpur" required>
                            </div>
                            
                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="pet-date">Pet Date</label>
                                <input type="date" name="pet-date" id="pet-date" class="form-control" placeholder="2020-03-01" required>
                            </div>

                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="pet-gender">Pet Gender</label>
                                <select name="pet-gender" id="pet-gender" class="form-control" required>
                                    <option value="Female">Female</option>
                                    <option value="Male">Male</option>
                                </select>
                            </div>
                            
                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="pet-condition">Pet Condition</label>
                                <select name="pet-condition" id="pet-condition" class="form-control" required>
                                    <option value="Healthy">Healthy</option>
                                    <option value="Not-Healthy">Not Healthy</option>
                                </select>
                            </div>
                            
                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="pet-vaccinated">Pet Vaccinated</label>
                                <select name="pet-vaccinated" id="pet-vaccinated" class="form-control" required>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            
                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="pet-dewormed">Pet Dewormed</label>
                                <select name="pet-dewormed" id="pet-dewormed" class="form-control" required>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            
                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="pet-neutered">Pet Neutered</label>
                                <select name="pet-neutered" id="pet-neutered" class="form-control" required>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>

                            <div class="col-lg-6 form-group">
                                <label for="rehome-name">Name</label>
                                <!-- Pre-fill rehome name with user's name -->
                                <input type="text" name="rehome-name" id="rehome-name" class="form-control" placeholder="Name" value="<?php echo $user_details['User_Name']; ?>" required>
                            </div>

                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="rehome-phone">Phone Number</label>
                                <!-- Pre-fill rehome phone with user's phone -->
                                <input type="tel" name="rehome-phone" id="rehome-phone" pattern="[0-9]{10,11}" class="form-control" placeholder="Phone" value="<?php echo $user_details['User_Phone']; ?>" required>
                            </div>

                            <div class="col-lg-6 form-group mt-3 mt-md-0">    
                                <label for="rehome-email">Email</label>
                                <!-- Pre-fill rehome email with user's email -->
                                <input type="email" name="rehome-email" id="rehome-email" pattern="[^ @]*@[^ @]*" class="form-control" placeholder="Email" value="<?php echo $user_details['User_Email']; ?>" required>
                            </div>

                            
                            <div class="form-group mt-3">
                                <label for="pet-photo">Upload your Pet photo</label>
                                <div class="input-group">
                                    <!-- Add the "name" attribute to the file input -->
                                    <input type="file" class="form-control" id="pet-photo" name="pet-photo" accept=".jpeg, .png, .jpg" required>
                                </div>
                            </div>
                            
    
                        </div>
    
                        <label for="pet-description">Pet Description</label>
                        <textarea name="pet-description" rows="3" class="form-control" id="pet-description" placeholder="Description" required></textarea>

                        <button type="submit" class="form-control">Submit</button>
                    </form>
                </div>
    
                <div class="col-lg-6 col-12">
                    <img src="images/icon.png" class="volunteer-image img-fluid" alt="">
    
                    <div class="custom-block-body text-center">
                        <h4 class="text-white mt-lg-3 mb-lg-3">About Rehoming</h4>
    
                        <p class="text-white">Find a new home for your pet with ease. <br>
                            Our rehoming service connects pets with caring families for a smooth transition. <br>
                            Together, we can find the perfect home for your beloved companion.</p>
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