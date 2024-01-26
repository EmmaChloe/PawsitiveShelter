<?php
session_start();

require 'dbConn.php';

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Fetch user details from the User table
$username = $_SESSION['username'];
$sqlUser = "SELECT * FROM User WHERE User_Username = ?";
$stmtUser = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmtUser, $sqlUser)) {
    mysqli_stmt_bind_param($stmtUser, "s", $username);
    mysqli_stmt_execute($stmtUser);
    $resultUser = mysqli_stmt_get_result($stmtUser);

    if ($resultUser) {
        $user_details = mysqli_fetch_assoc($resultUser);

        // Set default profile image path
        $defaultProfileImage = 'images/icon.png';

        // Update user's profile image to default if not set
        if (empty($user_details['User_Photo'])) {
            $sqlUpdateProfileImage = "UPDATE User SET User_Photo = ? WHERE User_Username = ?";
            $stmtUpdateProfileImage = mysqli_stmt_init($conn);

            if (mysqli_stmt_prepare($stmtUpdateProfileImage, $sqlUpdateProfileImage)) {
                mysqli_stmt_bind_param($stmtUpdateProfileImage, "si", $defaultProfileImage, $user_details['User_Username']);
                mysqli_stmt_execute($stmtUpdateProfileImage);
                mysqli_stmt_close($stmtUpdateProfileImage);

                // Update user data with the new profile image
                $user_details['User_Photo'] = $defaultProfileImage;
            }
        }

        // Fetch user password from the database
        $sqlPassword = "SELECT User_Password FROM User WHERE User_Username = ?";
        $stmtPassword = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmtPassword, $sqlPassword)) {
            mysqli_stmt_bind_param($stmtPassword, "i", $user_details['User_Username']);
            mysqli_stmt_execute($stmtPassword);
            $resultPassword = mysqli_stmt_get_result($stmtPassword);
            $userPasswordData = mysqli_fetch_assoc($resultPassword);

            // Assign the current password to a variable
            $currentPassword = $userPasswordData['User_Password'];

            mysqli_stmt_close($stmtPassword);
        }
    } else {
        echo "Error fetching user details: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmtUser);
} else {
    echo "Error preparing user query: " . mysqli_error($conn);
}

// Fetch details for the selected adopt pet along with user details
$fetch_adopt_query = "SELECT Adoption.*, Pet.Pet_Photo, Pet.Pet_Name
                      FROM Adoption
                      JOIN Pet ON Adoption.Pet_ID = Pet.Pet_ID
                      WHERE Adoption.User_ID = ?";

$stmt_adopt = mysqli_prepare($conn, $fetch_adopt_query);
if ($stmt_adopt){
    mysqli_stmt_bind_param($stmt_adopt, "i", $user_details['User_ID']);
    mysqli_stmt_execute($stmt_adopt);
    $result_adopt = mysqli_stmt_get_result($stmt_adopt);
} else {
    die("Query preparation failed: " . mysqli_error($conn));
}

// Fetch details for the selected volunteer along with user details
$fetch_volunteer_query = "SELECT Volunteer.*, User.User_Name, User.User_Email, User.User_Phone
                          FROM Volunteer
                          JOIN User ON Volunteer.User_ID = User.User_ID
                          WHERE Volunteer.User_ID = ?";

$stmt_volunteer = mysqli_prepare($conn, $fetch_volunteer_query);
if ($stmt_volunteer) {
    mysqli_stmt_bind_param($stmt_volunteer, "i", $user_details['User_ID']);
    mysqli_stmt_execute($stmt_volunteer);
    $result_volunteer = mysqli_stmt_get_result($stmt_volunteer);
} else {
    die("Query preparation failed: " . mysqli_error($conn));
}

// Fetch details for the selected lost pet along with user details
$fetch_lost_query = "SELECT LostPet.*, User.User_Name, User.User_Email, User.User_Phone
                      FROM LostPet
                      JOIN User ON LostPet.User_ID = User.User_ID
                      WHERE LostPet.User_ID = ?";

$stmt_lost = mysqli_prepare($conn, $fetch_lost_query);
if ($stmt_lost) {
    mysqli_stmt_bind_param($stmt_lost, "i", $user_details['User_ID']);
    mysqli_stmt_execute($stmt_lost);
    $result_lost = mysqli_stmt_get_result($stmt_lost);
} else {
    die("Query preparation failed: " . mysqli_error($conn));
}



// Close the database connection
mysqli_close($conn);
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
        <h1 class="text-light"><a href="admin.php"><img src="images/logo.png" alt=""></a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="admin.php"><img src="img/logo.png" alt="" class="img-fluid"></a>-->
      </div>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link" href="admin.php">Dashboard</a></li>
          <li class="dropdown"><a href="#"><span>Management</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="edit_request.php">Request</a></li>
              <li class="dropdown"><a href="edit_pet.php"><span>Pet</span> <i class="bi bi-chevron-right"></i></a>
                <ul>
                  <li><a href="edit_adopt.php">Adopt</a></li>
                  <li><a href="edit_lost.php">Lost</a></li>
                </ul>
              </li>
              <li><a href="edit_donation.php">Donation</a></li>
              <li><a href="edit_volunteer.php">Volunteer</a></li>
              <li><a href="edit_news.php">News</a></li>
              <li class="dropdown "><a href="edit_user.php"><span>User</span> <i class="bi bi-chevron-right"></i></a>
                <ul>
                  <li><a href="edit_admin.php">Admin</a></li>
                  <li><a href="edit_public.php">Public</a></li>
                </ul>
              </li>
            </ul>
          </li>
          <li class="dropdown active">
            <?php
            if (!isset($_SESSION["username"])) {
                echo '<a href="login.php">Log in</a>';
            } else {
                echo '<a href="#"><span>' . $_SESSION['username'] . '</span> <i class="bi bi-chevron-down"></i></a>';
                echo '<ul>
                        <li><a href="profile.php">Profile</a></li>
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

  <main id="main" class="main">

    <div class="pagetitle">
      <center><h1>Profile</h1></center>

    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
          
          <?php
                // Check for any messages passed through URL parameters
                if (isset($_GET['error'])) {
                    $error = $_GET['error'];
                
                    // Display error messages based on error code
                    if ($error === "incorrect_current_password") {
                        echo '<div class="alert alert-danger">Incorrect current password!</div>';
                    } elseif ($error === "new_passwords_do_not_match") {
                        echo '<div class="alert alert-danger">New passwords do not match!</div>';
                    } elseif ($error === "form_not_submitted") {
                        echo '<div class="alert alert-danger">Form not submitted try again!</div>';
                    }
                } elseif (isset($_GET['success'])) {
                    $success = $_GET['success'];
                
                    // Display success message
                    if ($success === "password_updated_successfully") {
                        echo '<div class="alert alert-success">Password updated successfully!</div>';
                    }
                }
                ?>
                
                <?php
                // Check if the success parameter is set in the URL
                if (isset($_GET['success'])) {
                    // Check if the value of the success parameter is "profile_updated_successfully"
                    $success = $_GET['success'];
                    if ($success === "profile_updated_successfully") {
                        echo '<div class="alert alert-success">Profile updated successfully!</div>';
                    }
                }
                ?>
                
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="display_image.php?User_ID=<?php echo $user_details['User_ID']; ?>" style="height: 120px; width: 120px; object-fit: cover;" alt="Profile" class="rounded-circle">
                        <h2><?php echo $user_details['User_Name']; ?></h2>
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                </li>
                

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  
                  <h5 class="card-title">Profile Details</h5>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Full Name</div>
                                        <div class="col-lg-9 col-md-8"><?php echo $user_details['User_Name']; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Phone</div>
                                        <div class="col-lg-9 col-md-8"><?php echo $user_details['User_Phone']; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Email</div>
                                        <div class="col-lg-9 col-md-8"><?php echo $user_details['User_Email']; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Username</div>
                                        <div class="col-lg-9 col-md-8"><?php echo $user_details['User_Username']; ?></div>
                                    </div>

                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->
                  <form method="post" action="update_adminprofile.php" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="User_Photo" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                    <div class="col-md-8 col-lg-9">
                        <?php
                        // Display the user's profile image
                        echo '<img src="display_image.php?User_ID=' . $user_details['User_ID'] . '" alt="Profile" style="height: 120px; width: 120px; object-fit: cover;">';
                        ?>
                        <div class="pt-2">
                            <input type="file" id="User_Photo" name="User_Photo" style="display: none;" onchange="handleImageUpload()">
                            <label for="User_Photo" class="btn btn-primary" title="Upload new profile image" style="color: white;"><i class="bi bi-upload" style="color: white;"></i>Upload Image</label>

                        </div>
                    </div>
                </div>

                    <div class="row mb-3">
                    <label for="User_Name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="User_Name" type="text" class="form-control" id="User_Name" placeholder="Enter your full name" value="<?php echo htmlspecialchars($user_details['User_Name']); ?>">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="User_Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="User_Phone" type="text" class="form-control" id="User_Phone" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($user_details['User_Phone']); ?>">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="User_Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="User_Email" type="text" class="form-control" id="User_Email" placeholder="Enter your email" value="<?php echo htmlspecialchars($user_details['User_Email']); ?>">
                    </div>
                </div>
                
                <div class="row mb-3">
                <label for="username" class="col-md-4 col-lg-3 col-form-label">Username</label>
                <div class="col-md-8 col-lg-9">
                    <input name="User_Username" type="text" class="form-control" id="User_Username" placeholder="Enter your username" value="<?php echo htmlspecialchars($user_details['User_Username']); ?>" readonly>
                    <small class="form-text text-muted">Note: The username cannot be edited.</small>
                </div>
                </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </form><!-- End Profile Edit Form -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                <!-- Change Password Form -->
                <form method="post" action="update_adminpassword.php">
            
                    <div class="row mb-3">
                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="currentPassword" type="password" class="form-control" id="currentPassword" >
                    </div>
                </div>
            
                    <div class="row mb-3">
                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="newPassword" type="password" class="form-control" id="newPassword" required>
                        </div>
                    </div>
            
                    <div class="row mb-3">
                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="renewPassword" type="password" class="form-control" id="renewPassword" required>
                        </div>
                    </div>
            
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                    
                    <?php
                    // Display error message if present
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_SESSION['error']) . '</div>';
                        unset($_SESSION['error']); // Clear the error message from the session
                    }
                    ?>
                </form><!-- End Change Password Form -->
            </div>
            
            
              </div>
            </div><!-- End Recent Sales -->

              </div><!-- End Bordered Tabs -->

        </div>
      </div>
    </section>

  </main><!-- End #main -->



  <!-- ======= Footer ======= -->
  <footer id="footer">
    

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


  <!-- Vendor JS Files -->
  <script src="css/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="css/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="css/vendor/chart.js/chart.umd.js"></script>
  <script src="acss/vendor/echarts/echarts.min.js"></script>
  <script src="css/vendor/quill/quill.min.js"></script>
  <script src="css/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="css/vendor/tinymce/tinymce.min.js"></script>
  <script src="css/vendor/php-email-form/validate.js"></script>


  <!-- Template Main JS File -->
  <script src="js/main.js"></script>

  <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

    <script src="js/script.js"></script>

  <!-- Template Main JS File -->
  <script src="css/js/main.js"></script>

</body>
</html>


  