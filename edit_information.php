<?php
session_start();

require 'dbConn.php';

// Assuming you get the Pet_ID from the URL or another source
if (isset($_GET['lost_id'])) {
    $pet_id = $_GET['lost_id'];

    // Fetch pet details from the LostPet table
    $fetch_pet_query = "SELECT LostPet.*, User.User_Name, User.User_Phone, User.User_Email
                       FROM LostPet 
                       INNER JOIN User ON LostPet.User_ID = User.User_ID 
                       WHERE Lost_ID = ?";
    $stmtFetchPet = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmtFetchPet, $fetch_pet_query)) {
        mysqli_stmt_bind_param($stmtFetchPet, "i", $pet_id); // "i" represents an integer type for the parameter
        mysqli_stmt_execute($stmtFetchPet);
        $resultPet = mysqli_stmt_get_result($stmtFetchPet);

        if ($resultPet) {
            $pet_details = mysqli_fetch_assoc($resultPet);

            // Your code to display or process $pet_details goes here
        } else {
            // Handle the case when no pet details are found
            die("Pet not found");
        }
    } else {
        // Handle the case when the fetch pet query preparation fails
        die("Fetch pet query preparation failed: " . mysqli_error($conn));
    }
} else {
    // Redirect to a page where users can select a pet if no Pet_ID is provided
    header('Location: edit_lost.php');
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

  <!-- Vendor CSS Files -->
  <link href="css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="css/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="css/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="css/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="css/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="css/vendor/simple-datatables/style.css" rel="stylesheet">

  <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

  <!-- Template Main CSS File -->
  <link href="css/admin_style.css" rel="stylesheet">

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
          <li class="dropdown active"><a href="#"><span>Management</span> <i class="bi bi-chevron-down"></i></a>
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
          <li class="dropdown">
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
              
              <?php
        // Check for success query parameter and display success message for adding news
        if (isset($_GET['news']) && $_GET['news'] == 'success') {
            echo '<div class="alert alert-success" role="alert">Pet added successfully!</div>';
        }
        
        // Check for success query parameter and display success message for deleting news
        if (isset($_GET['delete']) && $_GET['delete'] == 'success') {
            echo '<div class="alert alert-success" role="alert">Pet deleted successfully!</div>';
        }
        
        // Check for success query parameter and display success message for updating news
        if (isset($_GET['update']) && $_GET['update'] == 'success') {
            echo '<div class="alert alert-success" role="alert">Pet updated successfully!</div>';
        }
  ?>
  
  <?php
// Check for error query parameter and display error message for failed pet update
if (isset($_GET['error'])) {
    $error_type = $_GET['error'];
    
    // Display different error messages based on the error type
    switch ($error_type) {
        case 'nochanges':
            $error_message = 'No changes were made to the pet.';
            break;
        case 'petupdatefailed':
            $error_message = 'Pet update failed. Please try again later.';
            break;
        // Add more cases as needed for different error types
        default:
            $error_message = 'An unexpected error occurred.';
    }

    echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
}
?>
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
                <li><strong>Date</strong>: <?php echo $pet_details['Lost_Date']; ?></li>
                <li><strong>Location</strong>: <?php echo $pet_details['Lost_Location']; ?></li>
                  <li><strong>Owner Name</strong>: <?php echo $pet_details['User_Name']; ?></li>
                  <li><strong>Owner Phone</strong>: <?php echo $pet_details['User_Phone']; ?></li>
                  <li><strong>Owner Email</strong>: <?php echo $pet_details['User_Email']; ?></li>
                </ul>
              </div>
              
              
              <section id="empty" class="empty section-bg"></section>

              <center>
              <?php
    echo "<button class='btn btn-warning' data-toggle='modal' data-target='#editmodal' data-lostpetid='{$pet_details['Lost_ID']}' data-lostpetname='" . htmlspecialchars($pet_details['Lost_Name']) . "' data-lostpetbreed='{$pet_details['Lost_Breed']}' data-lostpetage='{$pet_details['Lost_Age']}' data-lostpetbody='{$pet_details['Lost_Body']}' data-lostpetgender='{$pet_details['Lost_Gender']}' data-lostpetdate='{$pet_details['Lost_Date']}' data-lostpetlocation='{$pet_details['Lost_Location']}' data-lostpetphoto='{$pet_details['Lost_Photo']}' data-ownername='{$pet_details['User_Name']}' data-ownerphone='{$pet_details['User_Phone']}' data-owneremail='{$pet_details['User_Email']}'>
                    <i class='bi bi-pencil-square'></i>Edit Lost Pet Detail
                </button>";
?>

              </center>
            </div>
  
          </div>
  
        </div>
      </section><!-- End Portfolio Details Section -->
  
    </main><!-- End #main -->

    <section id="empty" class="empty section-bg"></section>

<script>
    $(document).ready(function() {
        $('#editmodal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);

            var lostPetPhotoFilename = button.data('lostpetphoto');
            var lostPetPhotoPath = 'images/lost/' + lostPetPhotoFilename;
            var lostPetId = button.data('lostpetid');
            var lostPetName = button.data('lostpetname');
            var lostPetBreed = button.data('lostpetbreed');
            var lostPetAge = button.data('lostpetage');
            var lostPetBody = button.data('lostpetbody');
            var lostPetGender = button.data('lostpetgender');
            var lostPetDate = button.data('lostpetdate');
            var lostPetLocation = button.data('lostpetlocation');
            
            // Additional fields
            var ownerName = button.data('ownername');
            var ownerPhone = button.data('ownerphone');
            var ownerEmail = button.data('owneremail');

            $('#lost_pet_id').val(lostPetId);
            $('#lost_pet_name').val(lostPetName);
            $('#lost_pet_breed').val(lostPetBreed);
            $('#lost_pet_age').val(lostPetAge);
            $('#lost_pet_body').val(lostPetBody);
            $('#lost_pet_gender').val(lostPetGender);
            $('#lost_pet_date').val(lostPetDate);
            $('#lost_pet_location').val(lostPetLocation);

            // Additional fields
            $('#owner_name').val(ownerName);
            $('#owner_phone').val(ownerPhone);
            $('#owner_email').val(ownerEmail);

            // Set Lost Pet Photo
            $('#edit_lostPetPhoto').attr('src', lostPetPhotoPath);
            $('#edit_lostPetPhotoInput').val(lostPetPhotoPath);
        });
    });
</script>

<!-- Modal HTML -->
<div id="editmodal" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Lost Pet</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <!-- Lost Pet Edit Form -->
            <form method="post" action="change_lost.php" enctype="multipart/form-data">

                <input type="hidden" id="lost_pet_id" name="lost_pet_id" value="<?php echo $existing_lost_pet_data['Lost_ID'];?>">

                <div class="modal-body">
                    <!-- Your form elements go here -->
                    <div class="row mb-3">
                        <label for="lost_pet_name" class="col-md-4 col-lg-3 col-form-label">Pet Name</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="lost_pet_name" type="text" class="form-control" id="lost_pet_name" value="<?php echo htmlspecialchars($existing_lost_pet_data['Lost_Name']); ?>" required>
                        </div>
                    </div>
                
                <div class="row mb-3">
                    <label for="lost_pet_breed" class="col-md-4 col-lg-3 col-form-label">Pet Breed</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="lost_pet_breed" type="text" class="form-control" id="lost_pet_breed" value="<?php echo htmlspecialchars($existing_lost_pet_data['Lost_Breed']); ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="lost_pet_age" class="col-md-4 col-lg-3 col-form-label">Pet Age</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="lost_pet_age" type="text" class="form-control" id="lost_pet_age" value="<?php echo htmlspecialchars($existing_lost_pet_data['Lost_Age']); ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="lost_pet_body" class="col-md-4 col-lg-3 col-form-label">Pet Body</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="lost_pet_body" type="text" class="form-control" id="lost_pet_body" value="<?php echo htmlspecialchars($existing_lost_pet_data['Lost_Body']); ?>" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="lost_pet_gender" class="col-md-4 col-lg-3 col-form-label">Pet Gender</label>
                    <div class="col-md-8 col-lg-9">
                        <select name="lost_pet_gender" class="form-control" id="lost_pet_gender" value="<?php echo htmlspecialchars($existing_lost_pet_data['Lost_Gender']); ?>" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>

                  <div class="row mb-3">
                    <label for="lost_pet_date" class="col-md-4 col-lg-3 col-form-label">Date</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="lost_pet_date" type="date" class="form-control" id="lost_pet_date" value="" value="<?php echo htmlspecialchars($existing_lost_pet_data['Lost_Date']); ?>" required>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="lost_pet_location" class="col-md-4 col-lg-3 col-form-label">Location</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="lost_pet_location" type="text" class="form-control" id="lost_pet_location" value="" value="<?php echo htmlspecialchars($existing_lost_pet_data['Lost_Location']); ?>" required>
                    </div>
                  </div>
                  

                    <div class="row mb-3">
                        <label for="lost_pet_photo" class="col-md-4 col-lg-3 col-form-label">Lost Pet Photo</label>
                        <div class="col-md-8 col-lg-9">
                            <img id="edit_lostPetPhoto" src="<?php echo $lostPetPhotoPath; ?>" alt="Lost Pet Photo" style="max-width: 100%; border-radius: 5px;">

                            <!-- Input field to upload a new photo -->
                            <input type="file" class="form-control" id="lost_pet_photo" name="lost_pet_photo" accept="image/*">

                            <!-- Add an input field to store the Lost Pet Photo URL for updating if necessary -->
                            <input type="hidden" name="edit_lostPetPhoto" id="edit_lostPetPhotoInput" value="<?php echo $existing_lost_pet_data['Lost_Photo']; ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                    <label for="owner_name" class="col-md-4 col-lg-3 col-form-label">Owner Name</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="owner_name" type="text" class="form-control" id="owner_name" value="" value="<?php echo htmlspecialchars($existing_lost_pet_data['User_name']); ?>" readonly>
                    </div>
                  </div>
                  
                  <div class="row mb-3">
                    <label for="owner_phone" class="col-md-4 col-lg-3 col-form-label">Owner Phone</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="owner_phone" type="text" class="form-control" id="owner_phone" value="" value="<?php echo htmlspecialchars($existing_lost_pet_data['User_Phone']); ?>" readonly>
                    </div>
                  </div>
                  
                  <div class="row mb-3">
                    <label for="owner_email" class="col-md-4 col-lg-3 col-form-label">Owner Email</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="owner_email" type="text" class="form-control" id="owner_email" value="" value="<?php echo htmlspecialchars($existing_lost_pet_data['User_Email']); ?>" readonly>
                    </div>
                  </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Submit</button>
                </div>
            </form><!-- End Lost Pet Edit Form -->
        </div>
    </div>
</div>


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
