<?php
// Include the database connection file
include('dbConn.php');

// Start the session
session_start();

if (empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Initialize rowNumber for displaying row numbers
$rowNumber = 1;

// Fetch user details from the User table
$username = $_SESSION['username'];
$sqlUser = "SELECT * FROM User WHERE User_Username = ?";
$stmtUser = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmtUser, $sqlUser)) {
    mysqli_stmt_bind_param($stmtUser, "s", $username);
    mysqli_stmt_execute($stmtUser);
    $resultUser = mysqli_stmt_get_result($stmtUser);

    // Assuming you get the Adoption_ID from the URL or another source
    if (isset($_GET['request_id'])) {
        $request_id = $_GET['request_id'];

        // Fetch details for the selected request along with user details
        $fetch_request_query = "SELECT Adoption.*, Pet.Pet_Photo, Pet.Pet_Name, User.User_Name
                              FROM Adoption
                              JOIN Pet ON Adoption.Pet_ID = Pet.Pet_ID
                              JOIN User ON Adoption.User_ID = User.User_ID
                              WHERE Adoption.Adoption_ID = ?";

        // Use prepared statement to avoid SQL injection
        $stmtFetchRequest = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmtFetchRequest, $fetch_request_query)) {
            mysqli_stmt_bind_param($stmtFetchRequest, "i", $request_id);
            mysqli_stmt_execute($stmtFetchRequest);
            $resultRequest = mysqli_stmt_get_result($stmtFetchRequest);

            // Check if data is found for the given adoption ID
            if ($adoptionRow = mysqli_fetch_assoc($resultRequest)) {
                // Your existing code here
            } else {
                // Redirect if no request details are found
                header('Location: edit_request.php');
                exit();
            }
        } else {
            die("Fetch request query preparation failed: " . mysqli_error($conn));
        }
    } else {
        // Handle case when $_GET['request_id'] is not set
    }
} else {
    die("User query preparation failed: " . mysqli_error($conn));
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
          
          
<!-- Display a success message if set -->
<?php
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']); // Unset to avoid displaying it again
}

// Display an error message if set
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']); // Unset to avoid displaying it again
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
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Adoption Request</h5>
                            <div class="col-sm-6">
                            </div>

                            <table class="table table-borderless datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Pet Photo</th>
                                        <th scope="col">Pet</th>
                                        <th scope="col">Adopter</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Home Environment</th>
                                        <th scope="col">Previous Pet Experience</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                  // Display adoption
                                    $posts_per_page = 25;
                                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                    $start = ($page - 1) * $posts_per_page;
                                    
                                    $pagination_query = "SELECT * FROM Adoption
                                                         INNER JOIN Pet ON Pet.Pet_ID = Adoption.Pet_ID
                                                         INNER JOIN User ON User.User_ID = Adoption.User_ID
                                                         LIMIT $start, $posts_per_page";
                                    $pagination_result = mysqli_query($conn, $pagination_query);

                                  if (mysqli_num_rows($pagination_result) > 0) {
                                    while ($adoptionRow = mysqli_fetch_assoc($pagination_result)) {
                                        echo "<tr>";
                                        echo "<td>" . $rowNumber . "</td>"; // Display the row number
                                        echo "<td><img src='images/adopt/" . $adoptionRow['Pet_Photo'] . "' alt='Pet Photo' width='80px' style='border-radius: 5px;'></td>";
                                        echo "<td> " . $adoptionRow['Pet_Name']. "</td>";
                                        echo "<td>" . $adoptionRow['User_Name'] . "</td>";
                                      echo "<td>" . $adoptionRow['Adoption_Date'] . "</td>";
                                      echo "<td>" . $adoptionRow['Home_Environment'] . "</td>";
                                      echo "<td>" . $adoptionRow['Previous_Pet_Experience'] . "</td>";
                                      echo "<td>" . $adoptionRow['Adoption_Address'] . "</td>";
                                      echo "<td>";
                                      // Check the value of Adoption_Status and set the corresponding badge
                                      if ($adoptionRow['Adoption_Status'] == 'Approved') {
                                          echo '<span class="badge bg-success">Approved</span>';
                                      } elseif ($adoptionRow['Adoption_Status'] == 'Pending') {
                                          echo '<span class="badge bg-warning">Pending</span>';
                                      } elseif ($adoptionRow['Adoption_Status'] == 'Rejected') {
                                          echo '<span class="badge bg-danger">Rejected</span>';
                                      } else {
                                          // Handle any other status values here
                                          echo $adoptionRow['Adoption_Status']; // Display the status as is if not recognized
                                      }
                                      echo "</td>";
                                      echo "<td>
                                        <a href='#editmodal' class='trigger-btn' data-toggle='modal' title='Edit Adoption' data-toggle='tooltip'
                                            data-adopt-id='{$adoptionRow['Adoption_ID']}'
                                            data-petphoto='{$adoptionRow['Pet_Photo']}'
                                            data-petname='{$adoptionRow['Pet_Name']}'
                                            data-adoptername='{$adoptionRow['User_Name']}'
                                            data-adoptiondate='{$adoptionRow['Adoption_Date']}'
                                            data-homeenvironment='{$adoptionRow['Home_Environment']}'
                                            data-previouspetexperience='{$adoptionRow['Previous_Pet_Experience']}'
                                            data-adoptionaddress='{$adoptionRow['Adoption_Address']}'
                                            data-adoptionstatus='{$adoptionRow['Adoption_Status']}'>
                                            <span id='boot-icon' class='bi bi-gear-fill' style='color: rgb(48, 60, 108);'></span>
                                        </a>
                                    </td>";
                                    echo "</tr>";
                                      $rowNumber++; // Increment row number for the next iteration
                                    }
                                  } else {
                                      echo "<tr><td class='no-history-message' colspan='7'>No history available</td></tr>";
                                  } // End while loop

                                          // Display a message if no records are found
                                          if (mysqli_num_rows($pagination_result) == 0) {
                                            echo "<tr><td class='no-history-message' colspan='7'>No history available</td></tr>";
                                          }
                                    
                                    ?>
                                </tbody>
                            </table>
                            
                            <div class="clearfix">
                                <ul class="pagination">
                                <?php
                                // Count total number of records
                                $total_records_query = "SELECT COUNT(*) as total FROM Adoption";
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
                </div><!-- End Recent Sales -->

          </div>
        </div><!-- End Left side columns -->
      </div>
    </section>

  </main><!-- End #main -->

<script>
        // JavaScript variable to store adoption_id
        var adoptionIdToDelete;
        
        // Function to set the adoption_id before showing the modal
        function setAdoptionIdForDeletion(adoptionId) {
            adoptionIdToDelete = adoptionId;
            // Set the value of the hidden input using the JavaScript variable
            document.getElementById('adopt_id_to_delete').value = adoptionIdToDelete;
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
                    <p>Are you sure you want to delete this adopt? <br> This action cannot be undone and you will be unable to recover any data.</p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-info" data-dismiss="modal">Cancel</a>
                    <form method="post" action="delete_request.php">
                        <!-- Add an input field for adopt_id -->
                        <input type="hidden" id="adopt_id_to_delete" name="adopt_id" value="">
                        <button type="submit" class="btn btn-danger">Yes, delete it!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



<!-- Combined Edit Modal HTML -->
<div id="editmodal" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Adoption Request</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <!-- Adoption Edit Form -->
            <form method="post" action="change_request.php" >

                <div class="modal-body">
                    <!-- Display Pet Photo -->
                    <input type="hidden" id="edit_adoptID" name="adopt_id" value="<?php echo $adoptionRow['Adoption_ID'];?>">
                    
                    <div class="row mb-3">
                        <label for="Pet_Photo" class="col-md-4 col-lg-3 col-form-label">Pet Photo</label>
                        <div class="col-md-8 col-lg-9">

                                <img id="edit_petPhoto" src="<?php echo $petPhotoPath; ?>" alt="Pet Photo" style="max-width: 100%; border-radius: 5px;">
                                <!-- Add an input field to store the Pet Photo URL for updating if necessary -->
                                <input type="hidden" name="edit_petPhoto" id="edit_petPhotoInput" value="<?php echo $adoptionRow['Pet_Photo']; ?>" readonly>

                        </div>
                    </div>

                    <!-- Display Pet Name -->
                    <div class="row mb-3">
                        <label for="Pet_Name" class="col-md-4 col-lg-3 col-form-label">Pet</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="edit_petName" type="text" class="form-control" id="edit_petName" value="<?php echo ($adoptionRow['Pet_Name']); ?>" readonly>
                        </div>
                    </div>

                    <!-- Display Adopter Name -->
                    <div class="row mb-3">
                        <label for="Adopter_Name" class="col-md-4 col-lg-3 col-form-label">Adopter</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="edit_adopterName" type="text" class="form-control" id="edit_adopterName" value="<?php echo ($adoptionRow['User _Name']); ?>" readonly>
                        </div>
                    </div>

                    <!-- Display Adoption Date -->
                    <div class="row mb-3">
                        <label for="Adoption_Date" class="col-md-4 col-lg-3 col-form-label">Date</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="edit_adoptionDate" type="text" class="form-control" id="edit_adoptionDate" value="<?php echo ($adoptionRow['Adoption_Date']); ?>" readonly>
                        </div>
                    </div>

                    <!-- Display Home Environment -->
                    <div class="row mb-3">
                        <label for="Home_Environment" class="col-md-4 col-lg-3 col-form-label">Home Environment</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="edit_homeEnvironment" type="text" class="form-control" id="edit_homeEnvironment" value="<?php echo ($adoptionRow['Home_Environment']); ?>" readonly>
                        </div>
                    </div>

                    <!-- Display Previous Pet Experience -->
                    <div class="row mb-3">
                        <label for="Previous_Pet_Experience" class="col-md-4 col-lg-3 col-form-label">Previous Pet Experience</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="edit_previousPetExperience" type="text" class="form-control" id="edit_previousPetExperience" value="<?php echo ($adoptionRow['Previous_Pet_Experience']); ?>" readonly>
                        </div>
                    </div>

                    <!-- Display Adoption Address -->
                    <div class="row mb-3">
                        <label for="Adoption_Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="edit_adoptionAddress" type="text" class="form-control" id="edit_adoptionAddress" value="<?php echo ($adoptionRow['Adoption_Address']); ?>" readonly>
                        </div>
                    </div>

                    <!-- Display Adoption Status -->
                    <div class="row mb-3">
                        <label for="Adoption_Status" class="col-md-4 col-lg-3 col-form-label" required>Status</label>
                        <div class="col-md-8 col-lg-9">
                            <select name="edit_adoptionStatus" class="form-select" id="edit_adoptionStatus">
                                <option value="Pending" <?php echo ($adoptionRow && isset($adoptionRow['Adoption_Status']) && $adoptionRow['Adoption_Status'] === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Approved" <?php echo ($adoptionRow && isset($adoptionRow['Adoption_Status']) && $adoptionRow['Adoption_Status'] === 'Approved') ? 'selected' : ''; ?>>Approved</option>
                                <option value="Rejected" <?php echo ($adoptionRow && isset($adoptionRow['Adoption_Status']) && $adoptionRow['Adoption_Status'] === 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                        </div>
                    </div>

                    <!-- Display Action (Edit and Delete buttons) -->
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <a href="#" class="btn btn-info" data-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                </div>

            </form><!-- End Adoption Edit Form -->
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#editmodal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);

            // Retrieve values from the button's data attributes
            var petPhotoFilename = button.data('petphoto'); // Assuming this is just the filename, not the full path
            var petPhotoPath = 'images/adopt/' + petPhotoFilename; // Construct the full path
            var adopt_ID = button.data('adopt-id');
            var petName = button.data('petname');
            var adopterName = button.data('adoptername');
            var adoptionDate = button.data('adoptiondate');
            var homeEnvironment = button.data('homeenvironment');
            var previousPetExperience = button.data('previouspetexperience');
            var adoptionAddress = button.data('adoptionaddress');
            var adoptionStatus = button.data('adoptionstatus');

            // Set values to the corresponding fields in the modal
            $('#edit_adoptID').val(adopt_ID);
            $('#edit_petName').val(petName);
            $('#edit_adopterName').val(adopterName);
            $('#edit_adoptionDate').val(adoptionDate);
            $('#edit_homeEnvironment').val(homeEnvironment);
            $('#edit_previousPetExperience').val(previousPetExperience);
            $('#edit_adoptionAddress').val(adoptionAddress);
            $('#edit_adoptionStatus').val(adoptionStatus === 'Pending' ? 'Pending' : (adoptionStatus === 'Approved' ? 'Approved' : 'Rejected'));


            // Set Pet Photo
        $('#edit_petPhoto').attr('src', petPhotoPath); // Update the image source
        $('#edit_petPhotoInput').val(petPhotoPath); // Update the hidden input value
        });
    });
</script>



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
