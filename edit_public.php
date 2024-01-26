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
$sqlUser = "SELECT * FROM User WHERE User_Username = ? LIMIT 1";
$stmtUser = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmtUser, $sqlUser)) {
    mysqli_stmt_bind_param($stmtUser, "s", $username);
    mysqli_stmt_execute($stmtUser);
    $resultUser = mysqli_stmt_get_result($stmtUser);

    if ($resultUser) {
        $user_details = mysqli_fetch_assoc($resultUser);

        // Check if $user_details is set before accessing its keys
        if ($user_details && isset($user_details['User_ID'], $user_details['User_Photo'])) {
            // Set default profile image path
            $defaultProfileImage = 'images/icon.png';

            // Update user's profile image to default if not set
            if (empty($user_details['User_Photo'])) {
                $sqlUpdateProfileImage = "UPDATE User SET User_Photo = ? WHERE User_ID = ?";
                $stmtUpdateProfileImage = mysqli_stmt_init($conn);

                if (mysqli_stmt_prepare($stmtUpdateProfileImage, $sqlUpdateProfileImage)) {
                    mysqli_stmt_bind_param($stmtUpdateProfileImage, "si", $defaultProfileImage, $user_details['User_ID']);
                    mysqli_stmt_execute($stmtUpdateProfileImage);
                    mysqli_stmt_close($stmtUpdateProfileImage);

                    // Update user data with the new profile image
                    $user_details['User_Photo'] = $defaultProfileImage;
                }
            }

            // Access other keys as needed
            $userID = $user_details['User_ID'];
            // Access other fields using the same pattern
        } else {
            echo "Error: User details not found or missing keys.";
            // Handle the error appropriately
        }
    } else {
        echo "Error fetching user details: " . mysqli_error($conn);
        // Handle the error appropriately
    }

    mysqli_stmt_close($stmtUser);
} else {
    echo "Error preparing statement: " . mysqli_error($conn);
    // Handle the error appropriately
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
  
  <script>
        // JavaScript variable to store user_id
        var userIdToDelete;

        // Function to set the user_id before showing the modal
        function setUserIdForDeletion(userId) {
            userIdToDelete = userId;
            // Set the value of the hidden input using the JavaScript variable
            document.getElementById('user_id_to_delete').value = userIdToDelete;
        }
    </script>
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
          
          <?php
                if (isset($_GET['signup']) && $_GET['signup'] === 'success') {
                    echo '<div class="success-message">Add new public successful!</div>';
                }
    
                // Check for success message
                if (isset($_SESSION['success_message'])) {
                    echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
                
                    // Clear the session variable to avoid displaying the message again
                    unset($_SESSION['success_message']);
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
                  <h5 class="card-title">Public</h5>
                  <div class="col-sm-6">
                </div>

                  <table class="table table-borderless datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Display adoption
                        $posts_per_page = 25;
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $start = ($page - 1) * $posts_per_page;
                        
                        $pagination_query = "SELECT * FROM User LIMIT $start, $posts_per_page";
                        $pagination_result = mysqli_query($conn, $pagination_query);
                        
                        $rowNumber = $start + 1; // Initialize row number
                        
                        if (mysqli_num_rows($pagination_result) > 0) {
                            while ($user_details = mysqli_fetch_assoc($pagination_result)) {
                                // Add a condition to check if the user type is 'User'
                                if ($user_details['User_Type'] === 'User') {
                                    echo "<tr>";
                                    echo "<td>" . $rowNumber . "</td>"; // Display the row number
                                    echo '<td>' . $user_details['User_Name'] . '</td>';
                                    echo '<td>' . $user_details['User_Email'] . '</td>';
                                    echo '<td>' . $user_details['User_Phone'] . '</td>';
                                    echo '<td>' . $user_details['User_Username'] . '</td>';
                                    echo '<td>' . $user_details['User_Password'] . '</td>';
                                    echo '<td>' . $user_details['User_Type'] . '</td>';
                                    echo '<td>';
                                    echo "<a href='#editmodal' class='edit' data-toggle='modal' data-userid='{$user_details['User_ID']}' data-username='" . htmlspecialchars($user_details['User_Name']) . "' data-useremail='{$user_details['User_Email']}' data-userphone='{$user_details['User_Phone']}' data-userusername='{$user_details['User_Username']}' data-userpassword='{$user_details['User_Password']}' data-usertype='{$user_details['User_Type']}'>
                                        <span id='boot-icon' class='bi bi-gear-fill' style='color: rgb(48, 60, 108);'></span>
                                    </a>";
                                    echo '</td>';
                                    echo '</tr>';
                                }
                        
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
                                $total_records_query = "SELECT COUNT(*) as total FROM User";
                                $total_records_result = mysqli_query($conn, $total_records_query);
                                $total_records = mysqli_fetch_assoc($total_records_result)['total'];
                                $total_pages = ceil($total_records / $posts_per_page);

                                // Previous button
                                if ($page > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="edit_public.php?page=' . ($page - 1) . '">Previous</a></li>';
                                } else {
                                    echo '<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>';
                                }

                                // Page numbers
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '"><a class="page-link" href="edit_public.php?page=' . $i . '">' . $i . '</a></li>';
                                }

                                // Next button
                                if ($page < $total_pages) {
                                    echo '<li class="page-item"><a class="page-link" href="edit_public.php?page=' . ($page + 1) . '">Next</a></li>';
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

<!-- Modal HTML -->
    <div id="deletemodal" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this user? <br> This action cannot be undone and you will be unable to recover any data.</p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-info" data-dismiss="modal">Cancel</a>
                    <form method="post" action="delete_user.php">
                        <!-- Add an input field for user_id -->
                        <input type="hidden" name="user_id" id="user_id_to_delete" value="">
                        <button type="submit" class="btn btn-danger">Yes, delete it!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <?php

// Check if a success message is set
if (isset($_SESSION['success_message'])) {
    // Display the success message in your desired format (e.g., an alert box)
    echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';

    // Unset the success message to avoid displaying it on subsequent page loads
    unset($_SESSION['success_message']);
}
?>

<script>
    $(document).ready(function() {
        $('#editmodal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var User_ID = button.data('userid');
            var User_Name = button.data('username');
            var User_Email = button.data('useremail');
            var User_Phone = button.data('userphone');
            var User_Username = button.data('userusername');
            var User_Password = button.data('userpassword');
            var User_Type = button.data('usertype');

            $('#edit_userID').val(User_ID);
            $('#edit_userName').val(User_Name);
            $('#edit_userEmail').val(User_Email);
            $('#edit_userPhone').val(User_Phone);
            $('#edit_userUsername').val(User_Username);
            $('#edit_userPassword').val(User_Password);
            // Adjusted to match the PHP variable names
            $('#edit_userType').val(User_Type === 'User' ? 'User' : 'Admin');
        });
    });
</script>

<!-- Combined Edit Modal HTML -->
<div id="editmodal" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Public</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <!-- Profile Edit Form -->
            <form method="post" action="change_user.php">

                    <input type="hidden" name="userID" id="edit_userID" value="<?php echo $userID; ?>">
                    <div class="row mb-3">
                        <label for="User_Name" class="col-md-4 col-lg-3 col-form-label">Name</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="User_Name" type="text" class="form-control" id="edit_userName" placeholder="Enter your full name" value="<?php echo htmlspecialchars($user_details['User_Name']); ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="User_Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="User_Email" type="text" class="form-control" id="edit_userEmail" placeholder="Enter your email" value="<?php echo ($user_details['User_Email']); ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="User_Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="User_Phone" type="text" class="form-control" id="edit_userPhone" placeholder="Enter your phone number" value="<?php echo ($user_details['User_Phone']); ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="User_Username" class="col-md-4 col-lg-3 col-form-label">Username</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="User_Username" type="text" class="form-control" id="edit_userUsername" placeholder="Enter your username" value="<?php echo ($user_details['User_Username']); ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="User_Password" class="col-md-4 col-lg-3 col-form-label">Password</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="User_Password" type="text" class="form-control" id="edit_userPassword" placeholder="Enter your password" value="<?php echo ($user_details['User_Password']); ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">    
                        <label for="User_Type" class="col-md-4 col-lg-3 col-form-label">User Type</label>
                        <select name="User_Type" id="edit_userType" class="col-md-8 col-lg-9 form-control" required>
                            <option value="User" <?php echo (isset($user_details['User_Type']) && $user_details['User_Type'] === 'User') ? 'selected' : ''; ?>>User</option>
                            <option value="Admin" <?php echo (isset($user_details['User_Type']) && $user_details['User_Type'] === 'Admin') ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <a href="#" class="btn btn-info" data-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </div>

            </form><!-- End Profile Edit Form -->
        </div>
    </div>
</div>

<?php
// Check if a success message is set
if (isset($_SESSION['success_message'])) {
    // Display the success message in your desired format (e.g., an alert box)
    echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';

    // Unset the success message to avoid displaying it on subsequent page loads
    unset($_SESSION['success_message']);
}
?>

<!-- Modal HTML -->
<div id="my" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <!-- Profile Edit Form -->
            <form method="post" action="add_user.php">
                <div class="row mb-3">
                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Name</label>
                    <div class="col-md-8 col-lg-9">
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                    <div class="col-md-8 col-lg-9">
                        <input type="email" name="email" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                    <div class="col-md-8 col-lg-9">
                        <input type="phone" name="phone" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="username" class="col-md-4 col-lg-3 col-form-label">Username</label>
                    <div class="col-md-8 col-lg-9">
                        <input type="text" name="username" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="password" class="col-md-4 col-lg-3 col-form-label">Password</label>
                    <div class="col-md-8 col-lg-9">
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="adduser" class="btn btn-danger">Submit</button>
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
