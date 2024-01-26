<?php
// Include the database connection file
include('dbConn.php');

// Start the session
session_start();

// Check if the user is logged in
if (empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from the User table
    $sqlUser = "SELECT * FROM User";
    $stmtUser = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmtUser, $sqlUser)) {
        // No need to bind parameters if not using any placeholders
        mysqli_stmt_execute($stmtUser);
        $resultUser = mysqli_stmt_get_result($stmtUser);

        if ($resultUser) {
            $user_details = mysqli_fetch_assoc($resultUser);

            // Fetch details for the selected lostpet
            $fetch_pet_query = "SELECT LostPet.*, User.User_Name
                         FROM LostPet 
                         JOIN User ON LostPet.User_ID = User.User_ID
                         WHERE LostPet.Lost_ID = ?";

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
                    die("Lost Pet not found");
                }
            } else {
                // Handle the case when the fetch pet query preparation fails
                die("Fetch pet query preparation failed: " . mysqli_error($conn));
            }
        } else {
            // Handle the case when no user details are found
            die("User not found");
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


<main id="main" class="main">

    <section class="section dashboard">
      <div class="row">
          
          <!-- Display success message if set -->
               <?php
                // Check for success query parameter and display success message for adding news
                if (isset($_GET['pet']) && $_GET['pet'] === 'success') {
                        echo '<div class="alert alert-success" role="alert">Lost Pet added successfully!</div>';
                    }
                
                // Check for success query parameter and display success message for deleting news
                if (isset($_GET['delete']) && $_GET['delete'] == 'success') {
                    echo '<div class="alert alert-success" role="alert">Lost Pet deleted successfully!</div>';
                }
        
                ?>
                
                <?php
                // Check for errors in the URL parameter
                if (isset($_GET['error'])) {
                    $error = $_GET['error'];
                    echo '<p style="color: red;">Error: ' . $error . '</p>';
                }
                ?>
        
                <!-- Left side columns -->
                <div class="col-lg-12">
                  <div class="row">

            <!-- Recent Sales -->
<div class="col-12">
    <div class="card recent-sales overflow-auto">

        <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
        </div>

        <div class="card-body">
            <h5 class="card-title">Pet List</h5>
            <div class="col-sm-6">
            </div>

            <table class="table table-borderless datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Owner</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display pets
                    $posts_per_page = 25;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $start = ($page - 1) * $posts_per_page;

                    $pagination_query = "SELECT * FROM LostPet INNER JOIN User ON LostPet.User_ID = User.User_ID LIMIT $start, $posts_per_page";
                    $pagination_result = mysqli_query($conn, $pagination_query);

                    $rowNumber = $start + 1; // Initialize row number

                    if (mysqli_num_rows($pagination_result) > 0) {
                        while ($pet_details = mysqli_fetch_assoc($pagination_result)) {
                            echo "<tr>";
                            echo "<td>" . $rowNumber . "</td>"; // Display the row number
                            echo "<td><img src='images/lost/" . $pet_details['Lost_Photo'] . "' alt='Pet Photo' width='80px' style='border-radius: 5px;'></td>";
                            echo '<td><a href="edit_information.php?lost_id=' . $pet_details['Lost_ID'] . '">' . $pet_details['Lost_Name'] . '</a></td>';
                            echo '<td>' . $pet_details['Lost_Date'] . '</td>';
                            echo '<td>' . $pet_details['Lost_Location'] . '</td>';
                            echo '<td>' . $pet_details['User_Name'] . '</td>';
                            echo '<td>' . $pet_details['User_Phone'] . '</td>';
                            echo '<td>' . $pet_details['User_Email'] . '</td>';
                            echo '<td>';
                            echo "<a href='edit_information.php?lost_id=" . $pet_details['Lost_ID'] . "' class='view' title='View' data-toggle='tooltip'><span id='boot-icon' class='bi bi-eye-fill' style='color: rgb(83, 190, 108);'></span></a>";
                            echo '<a href="#deletemodal" class="trigger-btn" data-toggle="modal" onclick="setLostIdForDeletion(' . $pet_details['Lost_ID'] . ')">
                                    <span id="boot-icon" class="bi bi-x-circle-fill" style="color: rgb(255, 0, 0);"></span>
                                  </a>';
                            echo '</td>';
                            echo '</tr>';

                            $rowNumber++; // Increment row number for the next iteration
                        }
                    }
                    ?>
                </tbody>
            </table>
            <br>
            <div class="clearfix">
                <ul class="pagination">
                    <?php
                    // Count total number of records
                    $total_records_query = "SELECT COUNT(*) as total FROM LostPet";
                    $total_records_result = mysqli_query($conn, $total_records_query);
                    $total_records = mysqli_fetch_assoc($total_records_result)['total'];
                    $total_pages = ceil($total_records / $posts_per_page);

                    // Previous button
                    if ($page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="edit_lost.php?page=' . ($page - 1) . '">Previous</a></li>';
                    } else {
                        echo '<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>';
                    }

                    // Page numbers
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '"><a class="page-link" href="edit_lost.php?page=' . $i . '">' . $i . '</a></li>';
                    }

                    // Next button
                    if ($page < $total_pages) {
                        echo '<li class="page-item"><a class="page-link" href="edit_lost.php?page=' . ($page + 1) . '">Next</a></li>';
                    } else {
                        echo '<li class="page-item disabled"><a class="page-link" href="#">Next</a></li>';
                    }
                    ?>
                </ul>
            </div>

        </div>

    </div>
</div><!-- End Recent Sales -->


          </div>
        </div><!-- End Left side columns -->
      </div>
    </section>

  </main><!-- End #main -->

<script>
        // JavaScript variable to store lost_id
        var lostIdToDelete;
        
        // Function to set the lost_id before showing the modal
        function setLostIdForDeletion(lostId) {
            lostIdToDelete = lostId;
            // Set the value of the hidden input using the JavaScript variable
            document.getElementById('lost_id_to_delete').value = lostIdToDelete;
        }

    </script>

<!-- Modal HTML -->
    <div id="deletemodal" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this lost pet? <br> This action cannot be undone and you will be unable to recover any data.</p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-info" data-dismiss="modal">Cancel</a>
                    <form method="post" action="delete_lost.php">
                        <!-- Add an input field for lost_id -->
                        <input type="hidden" id="lost_id_to_delete" name="lost_id" value="">
                        <button type="submit" class="btn btn-danger">Yes, delete it!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Modal HTML -->
<div id="editmodal" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Lost Pet</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <!-- Profile Edit Form --><br>
            <form action="add_lost.php" method="post" enctype="multipart/form-data">
                
                <div class="row mb-3">
                    <label for="User_Name" class="col-md-4 col-lg-3 col-form-label">Owner Name</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="User_Name" type="text" class="form-control" id="User_Name" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="User_Phone" class="col-md-4 col-lg-3 col-form-label">Owner Phone</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="User_Phone" type="text" class="form-control" id="User_Phone" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="Owner_Email" class="col-md-4 col-lg-3 col-form-label">Owner Email</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="Owner_Email" type="text" class="form-control" id="Owner_Email" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="Lost_Name" class="col-md-4 col-lg-3 col-form-label">Name</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="Lost_Name" type="text" class="form-control" id="Lost_Name" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="Lost_Breed" class="col-md-4 col-lg-3 col-form-label">Breed</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="Lost_Breed" type="text" class="form-control" id="Lost_Breed" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="Lost_Age" class="col-md-4 col-lg-3 col-form-label">Age</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="Lost_Age" type="text" class="form-control" id="Lost_Age" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="Lost_Body" class="col-md-4 col-lg-3 col-form-label">Body</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="Lost_Body" type="text" class="form-control" id="Lost_Body" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="Lost_Gender" class="col-md-4 col-lg-3 col-form-label">Gender</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="Lost_Gender" type="text" class="form-control" id="Lost_Gender" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="Lost_Date" class="col-md-4 col-lg-3 col-form-label">Date</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="Lost_Date" type="date" class="form-control" id="Lost_Date" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="Lost_Location" class="col-md-4 col-lg-3 col-form-label">Location</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="Lost_Location" type="text" class="form-control" id="Lost_Location" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="Lost_Photo" class="col-md-4 col-lg-3 col-form-label">Photo</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="Lost_Photo" type="file" class="form-control" id="Lost_Photo" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="#" class="btn btn-info" data-dismiss="modal">Cancel</a>
                    <button type="submit" class="btn btn-danger">Submit</button>
                </div>
            </form><!-- End Profile Edit Form -->
        </div>
    </div>
</div>


<section id="empty" class="empty section-bg"></section>

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
