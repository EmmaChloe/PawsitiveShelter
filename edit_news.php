<?php
session_start();

require 'dbConn.php';

// Initialize rowNumber for displaying row numbers
$rowNumber = 1;

// Define pagination parameters for News
$posts_per_page_news = 25;
$page_news = isset($_GET['page_news']) ? (int)$_GET['page_news'] : 1;
$start_news = ($page_news - 1) * $posts_per_page_news;

// Define the pagination query for News
$pagination_query_news = "SELECT COUNT(*) as total FROM News";

$pagination_result_news = mysqli_query($conn, $pagination_query_news);

// Check for errors in the query
if (!$pagination_result_news) {
    $error_message = "Error fetching News data: " . mysqli_error($conn);
    error_log($error_message);
    exit("Something went wrong. Please try again later.");
}

function getNewsData($conn, $start, $posts_per_page) {
    $sql = "SELECT * FROM News LIMIT $start, $posts_per_page";
    $result = mysqli_query($conn, $sql);

    $newsData = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $newsData[] = $row;
    }

    return $newsData;
}

// Get news data from the database with pagination
$newsData = getNewsData($conn, $start_news, $posts_per_page_news);

// Ensure the user is logged in, adjust this according to your authentication mechanism
if (empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Process news edit form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newsTitle = mysqli_real_escape_string($conn, $_POST['add_news_title']);
    $newsContent = mysqli_real_escape_string($conn, $_POST['publish_content']);
    $newsDate = mysqli_real_escape_string($conn, $_POST['publish_date']);
    $newsAuthor = mysqli_real_escape_string($conn, $_SESSION['add_news_author']);


    // Check if a file was uploaded without errors
    if (isset($_FILES['news-photo']) && $_FILES['news-photo']['error'] == 0) {
        $photoTemp = $_FILES['news-photo']['tmp_name'];
        $photoName = $_FILES['news-photo']['name'];
        $photoPath = "photos/" . $photoName; // Set the path as per your requirements

        // Move the uploaded file to the desired location
        if (move_uploaded_file($photoTemp, $photoPath)) {
            // Insert news data into the News table using prepared statements
            $insertNewsQuery = "INSERT INTO News (User_ID, Publish_Title, Publish_Content, Publish_Date, Author, Publish_Photo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insertNewsQuery);
            mysqli_stmt_bind_param($stmt, "isssss", $userId, $newsTitle, $newsContent, $newsDate, $newsAuthor, $photoPath);

            if (mysqli_stmt_execute($stmt)) {
                // Redirect to the same page with success query parameter
                header("Location: edit_news.php?news=success");
                exit();
            } else {
                // Handle the error by logging and redirecting with an error parameter
                $error_message = "News insertion failed: " . mysqli_error($conn);
                error_log($error_message);
                header("Location: edit_news.php?error=newsinsertfailed&message=" . urlencode($error_message));
                exit();
            }
        } else {
            // Handle file upload error
            echo "File upload failed.";
        }
    } else {
        // If no new photo is uploaded, update news data without changing the existing photo
        $updateNewsQuery = "UPDATE News SET Publish_Title = ?, Publish_Content = ?, Publish_Date = ? WHERE News_ID = ?";
        $stmt = mysqli_prepare($conn, $updateNewsQuery);
        mysqli_stmt_bind_param($stmt, "sssi", $newsTitle, $newsContent, $newsDate, $newsID);

        if (mysqli_stmt_execute($stmt)) {
            // Redirect to the same page with success query parameter
            header("Location: edit_news.php?news=success");
            exit();
        } else {
            $error_message = "News update failed: " . mysqli_error($conn);
            error_log($error_message);
            header("Location: edit_news.php?error=newsupdatefailed");
            exit();
        }
    }
}

// Check if a file was uploaded without errors
if (isset($_FILES['publish_photo']) && $_FILES['publish_photo']['error'] == 0) {
    $photoTemp = $_FILES['publish_photo']['tmp_name'];
    $photoName = $_FILES['publish_photo']['name'];
    $photoPath = "photos/" . $photoName; // Set the path as per your requirements

    // Move the uploaded file to the desired location
    if (move_uploaded_file($photoTemp, $photoPath)) {
        // Continue with your existing code to insert data into the database
        // Update your SQL query to include the Publish_Photo column
        // Assuming $userId is the user ID you want to associate with the news item
        $insertNewsQuery = "INSERT INTO News (User_ID, Publish_Title, Publish_Content, Publish_Date, Author, Publish_Photo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertNewsQuery);
        mysqli_stmt_bind_param($stmt, "isssss", $userId, $newsTitle, $newsContent, $newsDate, $newsAuthor, $photoPath);

        // Execute the statement and handle success/failure
        if (mysqli_stmt_execute($stmt)) {
            // Insertion successful
            header("Location: add_news.php?news=success");
            exit();
        } else {
            // Insertion failed
            $error_message = "News insertion failed: " . mysqli_error($conn);
            error_log($error_message);
            header("Location: add_news.php?error=newsinsertfailed");
            exit();
        }
    } else {
        // File move failed
        $error_message = "Error: File move failed.";
        error_log($error_message);
        echo $error_message;
    }
} else {
    // Handle the case when no file is uploaded
    // Continue with your existing code to insert data into the database without a photo
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
          
          <?php
        // Check for success query parameter and display success message for adding news
        if (isset($_GET['news']) && $_GET['news'] == 'success') {
            echo '<div class="alert alert-success" role="alert">News added successfully!</div>';
        }
        
        // Check for success query parameter and display success message for deleting news
        if (isset($_GET['delete']) && $_GET['delete'] == 'success') {
            echo '<div class="alert alert-success" role="alert">News deleted successfully!</div>';
        }
        
        // Check for success query parameter and display success message for updating news
        if (isset($_GET['update']) && $_GET['update'] == 'success') {
            echo '<div class="alert alert-success" role="alert">News updated successfully!</div>';
        }
        
        // Check for error messages in the URL parameters
if (isset($_GET['error'])) {
    $error_type = $_GET['error'];
    $error_message = urldecode($_GET['message']);

    // Display error message based on the error type
    if ($error_type === 'nochanges') {
        echo '<div class="alert alert-warning" role="alert">' . $error_message . '</div>';
    } elseif ($error_type === 'newsupdatefailed') {
        echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
    }
}

        ?>

        
                <!-- Left side columns -->
                <div class="col-lg-12">
                  <div class="row">

            <!-- Recent Sales -->
            <div class="col-12">
              <div class="card recent-sales overflow-auto">

                <div class="filter">
                    <div class="btn-group" data-toggle="buttons">
                        <a href="#addmodal" class="trigger-btn" data-toggle="modal"><i class="bi bi-plus-circle-fill"></i> <span>Add New Update</span></a>						
                    </div>
                  <a class="icon" href="" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                </div>

                <div class="card-body">
                  <h5 class="card-title">News & Update</h5>
                  <div class="col-sm-6">
                </div>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Title</th>
                        <th scope="col">Content</th>
                        <th scope="col">Date</th>
                        <th scope="col">Author</th>
                        <th scope="col">Photo</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        // Display news
                        $posts_per_page = 25; // You can adjust this number based on your preference
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $start = ($page - 1) * $posts_per_page;
                        
                        $pagination_query = "SELECT * FROM News LIMIT $start, $posts_per_page";
                        $pagination_result = mysqli_query($conn, $pagination_query);
                        
                        $rowNumber = $start + 1; // Initialize row number
                        
                       while ($row = mysqli_fetch_assoc($pagination_result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($rowNumber) . "</td>"; // Display the row number
                            echo "<td>" . htmlspecialchars($row['Publish_Title']) . "</a></td>";
                            echo "<td>" . htmlspecialchars($row['Publish_Content']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Publish_Date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Author']) . "</td>";
                            echo "<td><img src='" . $row['Publish_Photo'] . "' alt='News Photo' width='80px' style='border-radius: 5px;'></td>";
                            echo "<td>";
                            echo "<a href='#editmodal' class='edit' data-toggle='modal' data-newsid='{$row['News_ID']}' data-newstitle='" . htmlspecialchars($row['Publish_Title']) . "' data-newscontent='" . htmlspecialchars($row['Publish_Content']) . "' data-newsdate='{$row['Publish_Date']}' data-newsauthor='" . htmlspecialchars($row['Author']) . "' data-newsphoto='" . htmlspecialchars($row['Publish_Photo']) . "' title='Edit News'>
                                <span id='boot-icon' class='bi bi-gear-fill' style='color: rgb(48, 60, 108);'></span>
                            </a>";
                            echo '<a href="#deletemodal" class="trigger-btn" data-toggle="modal" onclick="setItemIdForDeletion(' . $row['News_ID'] . ')">
                                    <span id="boot-icon" class="bi bi-x-circle-fill" style="color: rgb(255, 0, 0);"></span>
                                </a>';
                            echo "</td>";
                            echo "</tr>";
                        
                            $rowNumber++; // Increment row number for the next iteration
                        }
                        
                        
                        
                        ?>
                    </tbody>
                  </table>
                  <br>
                  <div class="clearfix">
                                <ul class="pagination">
                                <?php
                                // Count total number of records
                                $total_records_query = "SELECT COUNT(*) as total FROM News";
                                $total_records_result = mysqli_query($conn, $total_records_query);
                                $total_records = mysqli_fetch_assoc($total_records_result)['total'];
                                $total_pages = ceil($total_records / $posts_per_page);

                                // Previous button
                                if ($page > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="edit_news.php?page=' . ($page - 1) . '">Previous</a></li>';
                                } else {
                                    echo '<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>';
                                }

                                // Page numbers
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '"><a class="page-link" href="edit_news.php?page=' . $i . '">' . $i . '</a></li>';
                                }

                                // Next button
                                if ($page < $total_pages) {
                                    echo '<li class="page-item"><a class="page-link" href="edit_news.php?page=' . ($page + 1) . '">Next</a></li>';
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
function setItemIdForDeletion(newsId) {
    document.getElementById('news_id_to_delete').value = newsId;
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
                    <p>Are you sure you want to delete this news? <br> This action cannot be undone and you will be unable to recover any data.</p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-info" data-dismiss="modal">Cancel</a>
                    <form method="post" action="delete_news.php">
                        <!-- Add an input field for news_id -->
                        <input type="hidden" name="news_id" id="news_id_to_delete" value="">
                        <button type="submit" class="btn btn-danger">Yes, delete it!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
<script>
    $(document).ready(function() {
        $('#editmodal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Move this line before using 'button'
            
            var publishPhotoFilename = button.data('newsphoto'); // Corrected variable name
            var publishPhotoPath = '' + publishPhotoFilename; // Construct the full path
            var newsID = button.data('newsid');
            var newsTitle = button.data('newstitle');
            var newsContent = button.data('newscontent');
            var newsDate = button.data('newsdate');
            var newsAuthor = button.data('newsauthor');

            // Set values in the modal form
            $('#edit_newsID').val(newsID);
            $('#add_news_title').val(newsTitle);
            $('#publish_content').val(newsContent);
            $('#publish_date').val(newsDate);
            $('#add_news_author').val(newsAuthor);

            // Set Pet Photo
            $('#edit_publishPhoto').attr('src', publishPhotoPath); // Update the image source
            $('#edit_publishPhotoInput').val(publishPhotoPath); // Update the hidden input value
        });
    });
</script>





<!-- Modal HTML -->
<div id="editmodal" class="modal fade">
	<div class="modal-dialog modal-confirm">
		<div class="modal-content">
			<div class="modal-header">			
				<h4 class="modal-title">Edit</h4>	
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<!-- Profile Edit Form --><br>
            <form action="change_news.php" method="POST" enctype="multipart/form-data">
                
                <input type="hidden" id="edit_newsID" name="news_id" value="<?php echo $row['News_ID'];?>">

                <div class="row mb-3">
                    <label for="add_news_title" class="col-md-4 col-lg-3 col-form-label">Title</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="add_news_title" type="text" class="form-control" id="add_news_title" placeholder="Enter Title" value="<?php echo (htmlspecialchars($row['Publish_Title'])); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="publish_content" class="col-md-4 col-lg-3 col-form-label">Content</label>
                    <div class="col-md-8 col-lg-9">
                        <textarea name="publish_content" rows="5" class="form-control" id="publish_content" placeholder="Enter Content"><?php echo (htmlspecialchars($row['Publish_Content'])); ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="publish_date" class="col-md-4 col-lg-3 col-form-label">Date</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="publish_date" type="date" class="form-control" id="publish_date" placeholder="Enter Date" value="<?php echo ($row['Publish_Date']); ?>">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="add_news_author" class="col-md-4 col-lg-3 col-form-label">Author</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="add_news_author" type="text" class="form-control" id="add_news_author" placeholder="Enter Author" value="<?php echo ($row['Author']); ?>">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="publish_photo" class="col-md-4 col-lg-3 col-form-label">Publish Photo</label>
                    <div class="col-md-8 col-lg-9">
                        <img id="edit_publishPhoto" src="<?php echo $publishPhotoPath; ?>" alt="Publish Photo" style="max-width: 100%; border-radius: 5px;">
                        
                        <!-- Input field to upload a new photo -->
                        <input type="file" class="form-control" id="publish_photo" name="publish_photo" accept="image/*">
                        
                        <!-- Add an input field to store the Publish Photo URL for updating if necessary -->
                        <input type="hidden" name="edit_publishPhoto" id="edit_publishPhotoInput" value="<?php echo $row['Publish_Photo']; ?>" >
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

<!-- Modal HTML -->
<div id="addmodal" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header">            
                <h4 class="modal-title">Add News</h4>    
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <!-- Profile Add Form --><br>
            <form action="add_news.php" method="post" enctype="multipart/form-data">

                <div class="row mb-3">
                    <label for="add_news_title" class="col-md-4 col-lg-3 col-form-label">Title</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="add_news_title" type="text" class="form-control" id="add_news_title" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="publish_content" class="col-md-4 col-lg-3 col-form-label">Content</label>
                    <div class="col-md-8 col-lg-9">
                        <textarea name="publish_content" rows="5" class="form-control" id="publish_content"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="publish_date" class="col-md-4 col-lg-3 col-form-label">Date</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="publish_date" type="date" class="form-control" id="publish_date" value="">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="add_news_author" class="col-md-4 col-lg-3 col-form-label">Author</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="add_news_author" type="text" class="form-control" id="add_news_author" value="">
                    </div>
                </div>


                <div class="form-group mt-3">
                    <div class="input-group input-group-file">
                        <input type="file" name="publish_photo" class="form-control" id="inputGroupFile02">
                        <label class="input-group-text" for="inputGroupFile02">Photo</label>
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
