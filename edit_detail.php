<?php
session_start();

require 'dbConn.php';

// Assuming you get the Pet_ID from the URL or another source
if (isset($_GET['pet_id'])) {
    $pet_id = $_GET['pet_id'];

    // Fetch user details from the User table
    $sqlUser = "SELECT * FROM User";
    $stmtUser = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmtUser, $sqlUser)) {
        // No need to bind parameters if not using any placeholders
        mysqli_stmt_execute($stmtUser);
        $resultUser = mysqli_stmt_get_result($stmtUser);

        if ($resultUser) {
            $user_details = mysqli_fetch_assoc($resultUser);

            // Fetch details for the selected pet
            $fetch_pet_query = "SELECT * FROM Pet INNER JOIN User ON Pet.User_ID = User.User_ID WHERE Pet_ID = ?";
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
            // Handle the case when no user details are found
            die("User not found");
        }
    } else {
        // Handle the case when the user query preparation fails
        die("User query preparation failed: " . mysqli_error($conn));
    }
} else {
    // Redirect to a page where users can select a pet if no Pet_ID is provided
    header('Location: edit_adopt.php');
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
                            $image_folder = "images/adopt/";

                            // Check if Pet_Photo is not empty
                            if (!empty($pet_details['Pet_Photo'])) {
                                // Assume Pet_Photo is a comma-separated list of image file names
                                $photos = explode(',', $pet_details['Pet_Photo']);

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
                  <li><strong>Name</strong>: <?php echo $pet_details['Pet_Name']; ?></li>
                <li><strong>Breed</strong>: <?php echo $pet_details['Pet_Breed']; ?></li>
                <li><strong>Age</strong>: <?php echo $pet_details['Pet_Age']; ?></li>
                <li><strong>Body</strong>: <?php echo $pet_details['Pet_Body']; ?></li>
                <li><strong>Gender</strong>: <?php echo $pet_details['Pet_Gender']; ?></li>
                <li><strong>Condition</strong>: <?php echo $pet_details['Pet_Condition']; ?></li>
                <li><strong>Vaccinated</strong>: <?php echo $pet_details['Pet_Vaccinated']; ?></li>
                <li><strong>Dewormed</strong>: <?php echo $pet_details['Pet_Dewormed']; ?></li>
                <li><strong>Neutered</strong>: <?php echo $pet_details['Pet_Neutered']; ?></li>
                <li><strong>Date</strong>: <?php echo $pet_details['Pet_Date']; ?></li>
                <li><strong>Location</strong>: <?php echo $pet_details['Pet_Location']; ?></li>
                  <li><strong>Owner Name</strong>: <?php echo $pet_details['User_Name']; ?></li>
                  <li><strong>Owner Phone</strong>: <?php echo $pet_details['User_Phone']; ?></li>
                  <li><strong>Owner Email</strong>: <?php echo $pet_details['User_Email']; ?></li>
                </ul>
              </div>
              
              <div class="portfolio-description">
                    <!-- Display pet description dynamically -->
                    <center><h2>Pet Description</h2></center>
                    <p>
                        <?php echo $pet_details['Pet_Description']; ?>
                    </p>
                </div>
              
              <section id="empty" class="empty section-bg"></section>

              <center>
              <?php
                    echo "<button class='btn btn-warning' data-toggle='modal' data-target='#editmodal' 
                            data-petid='" . htmlspecialchars($pet_details['Pet_ID']) . "'
                            data-petname='" . htmlspecialchars($pet_details['Pet_Name']) . "'
                            data-petbreed='" . htmlspecialchars($pet_details['Pet_Breed']) . "'
                            data-petage='" . htmlspecialchars($pet_details['Pet_Age']) . "'
                            data-petbody='" . htmlspecialchars($pet_details['Pet_Body']) . "'
                            data-petgender='" . htmlspecialchars($pet_details['Pet_Gender']) . "'
                            data-petcondition='" . htmlspecialchars($pet_details['Pet_Condition']) . "'
                            data-petvaccinated='" . htmlspecialchars($pet_details['Pet_Vaccinated']) . "'
                            data-petdewormed='" . htmlspecialchars($pet_details['Pet_Dewormed']) . "'
                            data-petneutered='" . htmlspecialchars($pet_details['Pet_Neutered']) . "'
                            data-petdate='" . htmlspecialchars($pet_details['Pet_Date']) . "'
                            data-petlocation='" . htmlspecialchars($pet_details['Pet_Location']) . "'
                            data-petdescription='" . htmlspecialchars($pet_details['Pet_Description']) . "'
                            data-petphoto='" . htmlspecialchars($pet_details['Pet_Photo']) . "'
                            data-ownername='{$pet_details['User_Name']}' 
                            data-ownerphone='{$pet_details['User_Phone']}' 
                            data-owneremail='{$pet_details['User_Email']}'>
                            <i class='bi bi-pencil-square'></i>Edit Pet Detail
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

        var petPhotoFilename = button.data('petphoto'); // Corrected variable name
        var petPhotoPath = 'images/adopt/' + petPhotoFilename; // Construct the full path
        var petId = button.data('petid');
            var petName = button.data('petname');
            var petBreed = button.data('petbreed');
            var petAge = button.data('petage');
            var petBody = button.data('petbody');
            var petGender = button.data('petgender');
            var petCondition = button.data('petcondition');
            var petVaccinated = button.data('petvaccinated');
            var petDewormed = button.data('petdewormed');
            var petNeutered = button.data('petneutered');
            var petDate = button.data('petdate');
            var petLocation = button.data('petlocation');
            var petDescription = button.data('petdescription');
            
            // Additional fields
            var ownerName = button.data('ownername');
            var ownerPhone = button.data('ownerphone');
            var ownerEmail = button.data('owneremail');

            $('#pet_id').val(petId);
            $('#pet_name').val(petName);
            $('#pet_breed').val(petBreed);
            $('#pet_age').val(petAge);
            $('#pet_body').val(petBody);
            $('#pet_gender').val(petGender);
            $('#pet_condition').val(petCondition);
            $('#pet_vaccinated').val(petVaccinated);
            $('#pet_dewormed').val(petDewormed);
            $('#pet_neutered').val(petNeutered);
            $('#pet_date').val(petDate);
            $('#pet_location').val(petLocation);
            $('#pet_description').val(petDescription);
            
            // Additional fields
            $('#owner_name').val(ownerName);
            $('#owner_phone').val(ownerPhone);
            $('#owner_email').val(ownerEmail);
            
            // Set Pet Photo
        $('#edit_petPhoto').attr('src', petPhotoPath); // Update the image source
        $('#edit_petPhotoInput').val(petPhotoPath); // Update the hidden input value
        
    });
});
</script>


<!-- Modal HTML -->
<div id="editmodal" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Pet</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <!-- Profile Add Form -->
            <form method="post" action="change_pet.php" enctype="multipart/form-data">
                
                <input type="hidden" id="pet_id" name="pet_id" value="<?php echo $existing_pet_data['Pet_ID'];?>">

                <div class="modal-body">
                    <!-- Your form elements go here -->
                    <div class="row mb-3">
                        <label for="pet_name" class="col-md-4 col-lg-3 col-form-label">Pet Name</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="pet_name" type="text" class="form-control" id="pet_name" value="<?php echo htmlspecialchars($existing_pet_data['pet_name']); ?>" required>
                        </div>
                    </div>
                
                <div class="row mb-3">
                    <label for="pet_breed" class="col-md-4 col-lg-3 col-form-label">Pet Breed</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="pet_breed" type="text" class="form-control" id="pet_breed" value="<?php echo htmlspecialchars($existing_pet_data['pet_breed']); ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="pet_age" class="col-md-4 col-lg-3 col-form-label">Pet Age</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="pet_age" type="text" class="form-control" id="pet_age" value="<?php echo htmlspecialchars($existing_pet_data['pet_age']); ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="pet_body" class="col-md-4 col-lg-3 col-form-label">Pet Body</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="pet_body" type="text" class="form-control" id="pet_body" value="<?php echo htmlspecialchars($existing_pet_data['pet_body']); ?>" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="pet_gender" class="col-md-4 col-lg-3 col-form-label">Pet Gender</label>
                    <div class="col-md-8 col-lg-9">
                        <select name="pet_gender" class="form-control" id="pet_gender" value="<?php echo htmlspecialchars($existing_pet_data['pet_gender']); ?>" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="pet_condition" class="col-md-4 col-lg-3 col-form-label">Pet Condition</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="pet_condition" type="text" class="form-control" id="pet_condition" value="<?php echo htmlspecialchars($existing_pet_data['pet_condition']); ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="pet_vaccinated" class="col-md-4 col-lg-3 col-form-label">Pet Vaccinated</label>
                    <div class="col-md-8 col-lg-9">
                        <select name="pet_vaccinated" class="form-control" id="pet_vaccinated" value="<?php echo htmlspecialchars($existing_pet_data['pet_vaccinated']); ?>" required> 
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="pet_dewormed" class="col-md-4 col-lg-3 col-form-label" >Pet Dewormed</label>
                    <div class="col-md-8 col-lg-9">
                        <select name="pet_dewormed" class="form-control" id="pet_dewormed" value="<?php echo htmlspecialchars($existing_pet_data['pet_dewormed']); ?>" required>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="pet_neutered" class="col-md-4 col-lg-3 col-form-label">Pet Neutered</label>
                    <div class="col-md-8 col-lg-9">
                        <select name="pet_neutered" class="form-control" id="pet_neutered" value="<?php echo htmlspecialchars($existing_pet_data['pet_neutered']); ?>" required>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>

                  <div class="row mb-3">
                    <label for="pet_date" class="col-md-4 col-lg-3 col-form-label">Date</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="pet_date" type="date" class="form-control" id="pet_date" value="" value="<?php echo htmlspecialchars($existing_pet_data['pet_date']); ?>" required>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="pet_location" class="col-md-4 col-lg-3 col-form-label">Location</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="pet_location" type="text" class="form-control" id="pet_location" value="" value="<?php echo htmlspecialchars($existing_pet_data['pet_location']); ?>" required>
                    </div>
                  </div>
                  
                  <div class="row mb-3">
                    <label for="pet_description" class="col-md-4 col-lg-3 col-form-label">Description</label>
                    <div class="col-md-8 col-lg-9">
                        <textarea name="pet_description" rows="5" class="form-control" id="pet_description" value="<?php echo htmlspecialchars($existing_pet_data['pet_description']); ?>" required></textarea>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="pet_photo" class="col-md-4 col-lg-3 col-form-label">Pet Photo</label>
                    <div class="col-md-8 col-lg-9">
                        <img id="edit_petPhoto" src="<?php echo $petPhotoPath; ?>" alt="Pet Photo" style="max-width: 100%; border-radius: 5px;">
                        
                        <!-- Input field to upload a new photo -->
                        <input type="file" class="form-control" id="pet_photo" name="pet_photo" accept="image/*">
                        
                        <!-- Add an input field to store the Pet Photo URL for updating if necessary -->
                        <input type="hidden" name="edit_petPhoto" id="edit_petPhotoInput" value="<?php echo $row['Pet_Photo']; ?>" >
                        
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="owner_name" class="col-md-4 col-lg-3 col-form-label">Owner Name</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="owner_name" type="text" class="form-control" id="owner_name" value="" value="<?php echo htmlspecialchars($existing_pet_data['User_Name']); ?>" readonly>
                    </div>
                  </div>
                  
                  <div class="row mb-3">
                    <label for="owner_phone" class="col-md-4 col-lg-3 col-form-label">Owner Phone</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="owner_phone" type="text" class="form-control" id="owner_phone" value="" value="<?php echo htmlspecialchars($existing_pet_data['User_Phone']); ?>" readonly>
                    </div>
                  </div>
                  
                  <div class="row mb-3">
                    <label for="owner_email" class="col-md-4 col-lg-3 col-form-label">Owner Email</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="owner_email" type="text" class="form-control" id="owner_email" value="" value="<?php echo htmlspecialchars($existing_pet_data['User_Email']); ?>" readonly>
                    </div>
                  </div>
            </div>
			<div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Submit</button>
                </div>
            </form><!-- End Profile Add Form -->
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