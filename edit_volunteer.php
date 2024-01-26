<?php
session_start();

require 'dbConn.php';

// Process volunteer form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $volunteerDate = $_POST['volunteer-date'];
    $volunteerReason = $_POST['volunteer-reason'];

    // Check if a file was uploaded without errors
    if (isset($_FILES['volunteer-cv']) && $_FILES['volunteer-cv']['error'] == 0) {
        $cv_temp = $_FILES['volunteer-cv']['tmp_name'];
        $cv_name = $_FILES['volunteer-cv']['name'];
        $cv_path = "cv/" . $cv_name; // Set the path as per your requirements

        // Move the uploaded file to the desired location
        if (move_uploaded_file($cv_temp, $cv_path)) {
            // Insert volunteer data into Volunteer Entity using prepared statements
            $insert_volunteer_query = "INSERT INTO Volunteer (Usr_ID, Apply_Date, Apply_CV, Apply_Reason, Volunteer_Name, Volunteer_Email, Volunteer_Phone)
                                        VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_volunteer_query);
            mysqli_stmt_bind_param($stmt, "issssss", $user_details['User_ID'], $volunteerDate, $cv_path, $volunteerReason, $volunteerName, $volunteerPhone, $volunteerEmail);

            if (mysqli_stmt_execute($stmt)) {
                // Redirect to the same page with success query parameter
                header("Location: volunteer.php?volunteer=success");
                exit();
            } else {
                $error_message = "Volunteer insertion failed: " . mysqli_error($conn);
                error_log($error_message);
                header("Location: volunteer.php?error=volunteerinsertfailed");
                exit();
            }
        } else {
            $error_message = "Error: File move failed.";
            error_log($error_message);
            echo $error_message;
        }
    } else {
        $error_message = "Error: No file uploaded.";
        error_log($error_message);
        echo $error_message;
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
                if (isset($_GET['volunteer']) && $_GET['volunteer'] === 'success') {
                        echo '<div class="alert alert-success" role="alert">Volunteer added successfully!</div>';
                    }
                
                // Check for success query parameter and display success message for deleting news
                if (isset($_GET['delete']) && $_GET['delete'] == 'success') {
                    echo '<div class="alert alert-success" role="alert">Volunteer deleted successfully!</div>';
                }
                
                // Check for success query parameter and display success message for updating news
                if (isset($_GET['update']) && $_GET['update'] == 'success') {
                    echo '<div class="alert alert-success" role="alert">Volunteer updated successfully!</div>';
                }
        
                ?>
                
                <!-- Left side columns -->
                <div class="col-lg-12">
                    <div class="row">
                        <!-- Recent Sales -->
                        <div class="col-12">
                            <div class="card recent-sales overflow-auto">
                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Volunteer</h5>
                                    <div class="col-sm-6">
                                    </div>

                                    <table class="table table-borderless datatable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Volunteer Name</th>
                                                <th>Volunteer Email</th>
                                                <th>Phone Number</th>
                                                <th>Apply Date</th>
                                                <th>CV</th>
                                                <th>Reason</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $rowNumber = 1; // Initialize the row number

                                            // Check if there are rows returned
                                            // Display volunteer
                                            $posts_per_page = 25;
                                            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                                            $start = ($page - 1) * $posts_per_page;

                                            $pagination_query = "SELECT * FROM Volunteer LIMIT $start, $posts_per_page";
                                            $pagination_result = mysqli_query($conn, $pagination_query);

                                            $rowNumber = $start + 1; // Initialize row number
                                            if (mysqli_num_rows($pagination_result) > 0) {
                                                while ($volunteerRow = mysqli_fetch_assoc($pagination_result)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $rowNumber . "</td>"; // Display the row number
                                                    echo "<td>" . (isset($volunteerRow['Volunteer_Name']) ? $volunteerRow['Volunteer_Name'] : "") . "</td>";
                                                    echo "<td>" . (isset($volunteerRow['Volunteer_Email']) ? $volunteerRow['Volunteer_Email'] : "") . "</td>";
                                                    echo "<td>" . $volunteerRow['Volunteer_Phone'] . "</td>";
                                                    echo "<td>" . $volunteerRow['Apply_Date'] . "</td>";
                                                    $cvPath = $volunteerRow['Apply_CV'];
                                                    $cvFileName = basename($cvPath); // Extracting the file name
                                                    echo "<td><a href='" . $cvPath . "' download='" . $cvFileName . "'><i class='bi bi-file-pdf'></i> Download " . $cvFileName . "</a></td>";
                                                    echo "<td>" . $volunteerRow['Apply_Reason'] . "</td>";
                                                    echo "<td>";
                                                    echo '<a href="#deletemodal" class="trigger-btn" data-toggle="modal" onclick="setUserIdForDeletion(' . $volunteerRow['Volunteer_ID'] . ')">
                                                        <span id="boot-icon" class="bi bi-x-circle-fill" style="color: rgb(255, 0, 0);"></span>
                                                    </a>';

                                                    echo "</td>";
                                                    echo "</tr>";
                                                    $rowNumber++; // Increment row number for the next iteration
                                                }
                                            } else {
                                                echo "<tr><td class='no-history-message' colspan='8'>No history available</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <br>
                                    <div class="clearfix">
                                        <ul class="pagination">
                                            <?php
                                            // Count total number of records
                                            $total_records_query = "SELECT COUNT(*) as total FROM Volunteer";
                                            $total_records_result = mysqli_query($conn, $total_records_query);
                                            $total_records = mysqli_fetch_assoc($total_records_result)['total'];
                                            $total_pages = ceil($total_records / $posts_per_page);

                                            // Previous button
                                            if ($page > 1) {
                                                echo '<li class="page-item"><a class="page-link" href="edit_volunteer.php?page=' . ($page - 1) . '">Previous</a></li>';
                                            } else {
                                                echo '<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>';
                                            }

                                            // Page numbers
                                            for ($i = 1; $i <= $total_pages; $i++) {
                                                echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '"><a class="page-link" href="edit_volunteer.php?page=' . $i . '">' . $i . '</a></li>';
                                            }

                                            // Next button
                                            if ($page < $total_pages) {
                                                echo '<li class="page-item"><a class="page-link" href="edit_volunteer.php?page=' . ($page + 1) . '">Next</a></li>';
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
        function setUserIdForDeletion(volunteerId) {
        document.getElementById('volunteer_id_to_delete').value = volunteerId;
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
                    <p>Are you sure you want to delete this volunteer? <br> This action cannot be undone and you will be unable to recover any data.</p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-info" data-dismiss="modal">Cancel</a>
                    <form method="post" action="delete_volunteer.php">
                        <!-- Add an input field for user_id -->
                        <input type="hidden" name="volunteer_id" id="volunteer_id_to_delete" value="">
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
                <h4 class="modal-title">Edit Volunteer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <!-- Profile Edit Form --><br>
            <form method="post" action="change_volunteer.php" enctype="multipart/form-data">
                <!-- Add hidden input for Volunteer_ID -->
                <input type="hidden" name="Volunteer_ID" value="<?php echo htmlspecialchars($volunteerRow['Volunteer_ID']); ?>">

                <div class="row mb-3">
                    <label for="volunteer-name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="volunteer-name" type="text" class="form-control" id="volunteer-name"  value="<?php echo htmlspecialchars($volunteerRow['Volunteer_Name']); ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="volunteer-email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="volunteer-email" type="email" class="form-control" id="volunteer-email"  value="<?php echo htmlspecialchars($volunteerRow['Volunteer_Email']); ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="volunteer-phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="volunteer-phone" type="tel" class="form-control" id="volunteer-phone"  value="<?php echo htmlspecialchars($volunteerRow['Volunteer_Phone']); ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="volunteer-date" class="col-md-4 col-lg-3 col-form-label">Date</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="volunteer-date" type="date" class="form-control" id="volunteer-date" value="<?php echo htmlspecialchars($volunteerRow['Apply_Date']); ?>" required>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <div class="input-group input-group-file">
                        <!-- Adjusted the name attribute to match the PHP script -->
                        <input type="file" class="form-control" id="volunteer-cv" name="apply_cv" accept=".pdf" required>
                        <label class="input-group-text" for="volunteer-cv">Upload CV</label>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="volunteer-reason" class="col-md-4 col-lg-3 col-form-label">Reason</label>
                    <div class="col-md-8 col-lg-9">
                        <textarea name="volunteer-reason" rows="5" class="form-control" id="volunteer-reason"><?php echo htmlspecialchars($volunteerRow['Apply_Reason']); ?></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Submit</button>
                </div>
            </form><!-- End Profile Edit Form -->
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#editmodal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var Volunteer_ID = button.data('volunteerid');
            var Volunteer_Name = button.data('volunteername');
            var Volunteer_Email = button.data('volunteeremail');
            var Volunteer_Phone = button.data('volunteerphone');
            var Apply_Date = button.data('applydate');
            var Apply_Reason = button.data('applyreason');
            var Apply_CV = button.data('applycv');

            // Adjusted IDs to match the form
            $('#volunteer-name').val(Volunteer_Name);
            $('#volunteer-email').val(Volunteer_Email);
            $('#volunteer-phone').val(Volunteer_Phone);
            $('#volunteer-date').val(Apply_Date);
            $('#volunteer-reason').val(Apply_Reason);
            // Adjusted to match the file input id
            // Removed the value attribute for file input
            $('#volunteer-cv').val(""); // Clear the file input

            // Also, update the hidden Volunteer_ID field
            $('input[name="Volunteer_ID"]').val(Volunteer_ID);
        });
    });
</script>


<!-- Modal HTML -->
<div id="addmodal" class="modal fade">
	<div class="modal-dialog modal-confirm">
		<div class="modal-content">
			<div class="modal-header">			
				<h4 class="modal-title">Add Volunteer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<!-- Profile Add Form --><br>
            <form method="post" action="add_volunteer.php" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="volunteer-name" class="col-md-4 col-lg-3 col-form-label">Name</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="volunteer-name" type="text" class="form-control" id="volunteer-name" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="volunteer-email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="volunteer-email" type="volunteer-email" class="form-control" id="volunteer-email" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="volunteer-phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="volunteer-phone" type="tel" class="form-control" id="volunteer-phone" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="volunteer-date" class="col-md-4 col-lg-3 col-form-label">Date</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="volunteer-date" type="date" class="form-control" id="volunteer-date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <div class="input-group input-group-file">
                        <!-- Add the "name" attribute to the file input -->
                        <input type="file" class="form-control" id="volunteer-cv" name="volunteer-cv" accept=".pdf" required>
                        <label class="input-group-text" for="volunteer-cv">Upload CV</label>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="volunteer-reason" class="col-md-4 col-lg-3 col-form-label">Reason</label>
                    <div class="col-md-8 col-lg-9">
                        <textarea name="volunteer-reason" rows="5" class="form-control" id="volunteer-reason" required></textarea>
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
