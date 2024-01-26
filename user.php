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

        // Fetch user password from the database
        $sqlPassword = "SELECT User_Password FROM User WHERE User_ID = ?";
        $stmtPassword = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmtPassword, $sqlPassword)) {
            mysqli_stmt_bind_param($stmtPassword, "i", $user_details['User_ID']);
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

// Fetch details for the selected donation along with user details
$fetch_donation_query = "SELECT Donation.*, User.User_Name, User.User_Email, User.User_Phone
                          FROM Donation
                          JOIN User ON Donation.User_ID = User.User_ID
                          WHERE Donation.User_ID = ?";

$stmt_donation = mysqli_prepare($conn, $fetch_donation_query);
if ($stmt_donation) {
    mysqli_stmt_bind_param($stmt_donation, "i", $user_details['User_ID']);
    mysqli_stmt_execute($stmt_donation);
    $result_donation = mysqli_stmt_get_result($stmt_donation);
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

// Fetch details for the selected pet along with user details
$fetch_pet_query = "SELECT Pet.*, User.User_Name, User.User_Email, User.User_Phone
                      FROM Pet
                      JOIN User ON Pet.User_ID = User.User_ID
                      WHERE Pet.User_ID = ?";

$stmt_pet = mysqli_prepare($conn, $fetch_pet_query);
if ($stmt_pet) {
    mysqli_stmt_bind_param($stmt_pet, "i", $user_details['User_ID']);
    mysqli_stmt_execute($stmt_pet);
    $result_pet = mysqli_stmt_get_result($stmt_pet);
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
    
    <!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


  <!-- Template Main CSS File -->
  <link href="css/style.css" rel="stylesheet">
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
        <h1 class="text-light"><a href="index.php"><img src="images/logo.png" alt=""></a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="index.php"><img src="img/logo.png" alt="" class="img-fluid"></a>-->
      </div>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link " href="index.php">Home</a></li>
          <li><a class="nav-link " href="adopt.php">Adoption</a></li>
          <li><a class="nav-link " href="donate_detail.php">Donation</a></li>
          <li><a class="nav-link " href="volunteer.php">Volunteer</a></li>
          <li><a class="nav-link " href="lost.php">Lost & Found</a></li>
          <li><a class="nav-link " href="news.php">News</a></li>
          <li class="dropdown active">
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
                  <form method="post" action="update_profile.php" enctype="multipart/form-data">
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
                <form method="post" action="update_password.php">
            
                    <div class="row mb-3">
                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="currentPassword" type="password" class="form-control" id="currentPassword" required>
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
                    
                </form><!-- End Change Password Form -->
            </div>
          </div>
        </div>
      </div>
      </div>
      </div>
    </section>
    
    <section class="section dashboard">
    <div class="row">
        <div class="col-xl-12">
            
            <div class="pagetitle">
              <center><h1>History</h1></center>
            </div><!-- End Page Title -->
        <div class="card-body">
            <h5 class="card-title">History of Adoption Request</h5>
                  <div class="col-sm-6">
                </div>

                  <table class="table table-borderless datatable">
                    <tbody>
                        <?php
                        $rowNumber = 1; // Initialize the row number
                    
                        // Check if there are rows returned
                        if (mysqli_num_rows($result_adopt) > 0) {
                            echo "
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Pet Photo</th>
                                    <th>Pet Name</th>
                                    <th>Date</th>
                                    <th>Home Environment</th>
                                    <th>Previous Pet Experience</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>";
                    
                            while ($adoptionRow = mysqli_fetch_assoc($result_adopt)) {
                                echo "<tr>";
                                echo "<td>" . $rowNumber . "</td>"; // Display the row number
                                echo "<td><img src='images/adopt/" . $adoptionRow['Pet_Photo'] . "' alt='Pet Photo' width='80px' style='border-radius: 5px;'></td>";
                                echo "<td><a href='pet.php?pet_id=" . $adoptionRow['Pet_ID'] . "'>" . $adoptionRow['Pet_Name'] . "</a></td>";
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
                                echo "<td>";
                                    echo '<a href="#deleteadopt" class="trigger-btn" data-toggle="modal" onclick="setPetIdForDeletion(' . $adoptionRow['Adoption_ID'] . ')">
                                            <span id="boot-icon" class="bi bi-x-circle-fill" style="color: rgb(255, 0, 0);"></span>
                                        </a>';
                                
                                    echo "</td>";
                                echo "</tr>";
                                $rowNumber++; // Increment row number for the next iteration
                            }
                        } else {
                            echo "<tr><td class='no-history-message' colspan='6'>No history available</td></tr>";
                        }
                        ?>
                    </tbody>
                  </table>
                  
                  <?php

        // Check for success query parameter and display success message for deleting news
        if (isset($_GET['delete']) && $_GET['delete'] == 'success') {
            echo '<div class="alert alert-success" role="alert">Adopt Request deleted successfully!</div>';
        }
        ?>
        </div>
        
        <hr style="border-top: 3px solid #303c6c; margin: 0; padding: 0;">

        <div class="card-body">
                    <h5 class="card-title">History of Rehome Pet</h5>
                    <div class="col-sm-6">
                </div>

                  <table class="table table-borderless datatable">
                    <tbody>
                        <?php
                            $rowNumber = 1; // Initialize the row number
                        
                            // Check if there are rows returned
                            if (mysqli_num_rows($result_pet) > 0) {
                                echo "
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Location</th>
                                        <th>Uploader</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>";
                        
                                while ($pet_details = mysqli_fetch_assoc($result_pet)) {
                                    echo "<tr>";
                                    echo "<td>" . $rowNumber . "</td>"; // Display the row number
                                    echo "<td><img src='images/adopt/" . $pet_details['Pet_Photo'] . "' alt='Pet Photo' width='80px' style='border-radius: 5px;'></td>";
                                    echo '<td><a href="edit_detail.php?pet_id=' . $pet_details['Pet_ID'] . '">' . $pet_details['Pet_Name'] . '</a></td>';
                                    echo '<td>' . $pet_details['Pet_Date'] . '</td>';
                                    echo '<td>' . $pet_details['Pet_Location'] . '</td>';
                                    echo '<td>' . $user_details['User_Name'] . '</td>';
                                    echo '<td>' . $pet_details['Pet_Description'] . '</td>';
                                    echo '<td>';
                                    echo '<a href="#deletemodal" class="trigger-btn" data-toggle="modal" onclick="setAdoptIdForDeletion(' . $pet_details['Pet_ID'] . ')">
                                            <span id="boot-icon" class="bi bi-x-circle-fill" style="color: rgb(255, 0, 0);"></span>
                                          </a>';
                                    echo '</td>';
                                    echo '</tr>';
        
                                    $rowNumber++; // Increment row number for the next iteration
                                }
                            } else {
                                echo "<tr><td class='no-history-message' colspan='6'>No history available</td></tr>";
                            }
                        ?>
                    </tbody>
                  </table>
                  <?php

        // Check for success query parameter and display success message for deleting news
        if (isset($_GET['deletep']) && $_GET['deletep'] == 'success') {
            echo '<div class="alert alert-success" role="alert">Rehome deleted successfully!</div>';
        }

        ?>
                </div>

        <hr style="border-top: 3px solid #303c6c; margin: 0; padding: 0;">
        
        <div class="card-body">
                    <h5 class="card-title">History of Donation</h5>
                    <div class="col-sm-6">
                </div>

                  <table class="table table-borderless datatable">
                    <tbody>
                        <?php
                            $rowNumber = 1; // Initialize the row number
                        
                            // Check if there are rows returned
                            if (mysqli_num_rows($result_donation) > 0) {
                                echo "
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>";
                        
                                while ($donationRow = mysqli_fetch_assoc($result_donation)) {
                                    echo "<tr>";
                                    echo "<td>" . $rowNumber . "</td>"; // Display the row number
                                    echo "<td>" . (isset($donationRow['Donation_Name']) ? $donationRow['Donation_Name'] : "") . "</td>";
                                    echo "<td>" . (isset($donationRow['Donation_Email']) ? $donationRow['Donation_Email'] : "") . "</td>";
                                    echo "<td>" . $donationRow['Donation_Phone'] . "</td>";
                                    echo "<td>" . $donationRow['Payment_Amount'] . "</td>";
                                    echo "<td>" . $donationRow['Donation_Date'] . "</td>";
                                    echo "</tr>";
                                    $rowNumber++; // Increment row number for the next iteration
                                }
                            } else {
                                echo "<tr><td class='no-history-message' colspan='6'>No history available</td></tr>";
                            }
                        ?>
                    </tbody>
                  </table>
                </div>

                <hr style="border-top: 3px solid #303c6c; margin: 0; padding: 0;">

                <div class="card-body">
                    <h5 class="card-title">History of Volunteer</h5>
                    <div class="col-sm-6">
                </div>

                  <table class="table table-borderless datatable">
                    <tbody>
                        <?php
                            $rowNumber = 1; // Initialize the row number
                        
                            // Check if there are rows returned
                            if (mysqli_num_rows($result_volunteer) > 0) {
                                echo "
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
                                </thead>";
                        
                                while ($volunteerRow = mysqli_fetch_assoc($result_volunteer)) {
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
                                    echo '<a href="#deletevolunteer" class="trigger-btn" data-toggle="modal" onclick="setVolunteerIdForDeletion(' . $volunteerRow['Volunteer_ID'] . ')">
                                            <span id="boot-icon" class="bi bi-x-circle-fill" style="color: rgb(255, 0, 0);"></span>
                                        </a>';
                                    echo "</td>";
                                    echo "</tr>";
                                    $rowNumber++; // Increment row number for the next iteration
                                }
                            } else {
                                echo "<tr><td class='no-history-message' colspan='6'>No history available</td></tr>";
                            }
                        ?>
                    </tbody>
                  </table>
                  <?php

        // Check for success query parameter and display success message for deleting news
        if (isset($_GET['deletev']) && $_GET['deletev'] == 'success') {
            echo '<div class="alert alert-success" role="alert">Volunteer deleted successfully!</div>';
        }

        ?>
                </div>

                <hr style="border-top: 3px solid #303c6c; margin: 0; padding: 0;">

                <div class="card-body">
                    <h5 class="card-title">History of Lost Pet</h5>
                    <div class="col-sm-6">
                </div>

                  <table class="table table-borderless datatable">
                    <tbody>
                        <?php
                            $rowNumber = 1; // Initialize the row number
                        
                            // Check if there are rows returned
                            if (mysqli_num_rows($result_lost) > 0) {
                                echo "
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Pet Photo</th>
                                        <th>Pet Name</th>
                                        <th>Pet Breed</th>
                                        <th>Pet Age</th>
                                        <th>Pet Body</th>
                                        <th>Pet Gender</th>
                                        <th>Pet_Location</th>
                                        <th>Lost Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>";
                        
                                while ($lostRow = mysqli_fetch_assoc($result_lost)) {
                                    echo "<tr>";
                                    echo "<td>" . $rowNumber . "</td>"; // Display the row number
                                    echo "<td><img src='images/lost/" . $lostRow['Lost_Photo'] . "' alt='Lost Photo' width='80px' style='border-radius: 5px;'></td>";
                                    echo "<td><a href='pet_detail.php?lost_id=" . $lostRow['Lost_ID'] . "'>" .  $lostRow['Lost_Name'] . "</a></td>";
                                    echo "<td>" . $lostRow['Lost_Breed'] . "</td>";
                                    echo "<td>" . $lostRow['Lost_Age'] . "</td>";
                                    echo "<td>" . $lostRow['Lost_Body'] . "</td>";
                                    echo "<td>" . $lostRow['Lost_Gender'] . "</td>";
                                    echo "<td>" . $lostRow['Lost_Location'] . "</td>";
                                    echo "<td>" . $lostRow['Lost_Date'] . "</td>";
                                    echo "<td>";
                                    echo '<a href="#deletelost" class="trigger-btn" data-toggle="modal" onclick="setLostIdForDeletion(' . $lostRow['Lost_ID'] . ')">
                                            <span id="boot-icon" class="bi bi-x-circle-fill" style="color: rgb(255, 0, 0);"></span>
                                        </a>';
                                    echo "</td>";
                                    echo "</tr>";
                                    $rowNumber++; // Increment row number for the next iteration
                                }
                            } else {
                                echo "<tr><td class='no-history-message' colspan='6'>No history available</td></tr>";
                            }
                        ?>
                    </tbody>
                  </table>
                  <?php

        // Check for success query parameter and display success message for deleting news
        if (isset($_GET['deletel']) && $_GET['deletel'] == 'success') {
            echo '<div class="alert alert-success" role="alert">Lost Pet deleted successfully!</div>';
        }
        
        ?>
                </div>

            </div>
        </div><!-- End Recent Sales -->

</div><!-- End Left side columns -->
</section>


  </main><!-- End #main -->

<script>
        // JavaScript variable to store pet_id
        var petIdToDelete;

        // Function to set the pet_id before showing the modal
        function setPetIdForDeletion(petId) {
            petIdToDelete = petId;
            // Set the value of the hidden input using the JavaScript variable
            document.getElementById('pet_id_to_delete').value = petIdToDelete;
        }
    </script>


<!-- Modal HTML -->
    <div id="deleteadopt" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this pet? <br> This action cannot be undone and you will be unable to recover any data.</p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-info" data-dismiss="modal">Cancel</a>
                    <form method="post" action="delete_useradopt.php">
                        <!-- Add an input field for adoption_id -->
                        <input type="hidden" name="adoption_id" id="pet_id_to_delete" value="">
                        <button type="submit" class="btn btn-danger">Yes, delete it!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
        // JavaScript variable to store adopt_id
        var adoptIdToDelete;

        // Function to set the adoptadopt_id before showing the modal
        function setAdoptIdForDeletion(adoptId) {
            adoptIdToDelete = adoptId;
            // Set the value of the hidden input using the JavaScript variable
            document.getElementById('adopt_id_to_delete').value = adoptIdToDelete;
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
                <p>Are you sure you want to delete this pet? <br> This action cannot be undone, and you will be unable to recover any data.</p>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-info" data-dismiss="modal">Cancel</a>
                <!-- Form for submitting the deletion -->
                <form method="post" action="delete_userpet.php">
                    <!-- Add an input field for pet_id -->
                    <input type="hidden" name="adopt_id" id="adopt_id_to_delete" value="">
                    <button type="submit" class="btn btn-danger">Yes, delete it!</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
        /// JavaScript variable to store volunteer_id
var volunteerIdToDelete;

// Function to set the volunteer_id before showing the modal
function setVolunteerIdForDeletion(volunteerId) {
    volunteerIdToDelete = volunteerId;  // Fix the variable name here
    // Set the value of the hidden input using the JavaScript variable
    document.getElementById('volunteer_id_to_delete').value = volunteerIdToDelete;
}
    </script>

<!-- Modal HTML -->
    <div id="deletevolunteer" class="modal fade">
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
                    <form method="post" action="delete_uservolunteer.php">
                        <!-- Add an input field for volunteer_id -->
                        <input type="hidden" name="volunteer_id" id="volunteer_id_to_delete" value="">
                        <button type="submit" class="btn btn-danger">Yes, delete it!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
<script>
        /// JavaScript variable to store lost_id
var lostIdToDelete;

// Function to set the lost_id before showing the modal
function setLostIdForDeletion(lostId) {
    lostIdToDelete = lostId;  // Fix the variable name here
    // Set the value of the hidden input using the JavaScript variable
    document.getElementById('lost_id_to_delete').value = lostIdToDelete;
}
    </script>

<!-- Modal HTML -->
    <div id="deletelost" class="modal fade">
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
                    <form method="post" action="delete_userlost.php">
                        <!-- Add an input field for lost_id -->
                        <input type="hidden" name="lost_id" id="lost_id_to_delete" value="">
                        <button type="submit" class="btn btn-danger">Yes, delete it!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

  