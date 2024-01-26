<?php
// Include the database connection file
include 'dbConn.php';

session_start();

if (!isset($_SESSION["username"])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Fetch user details from the database using $_SESSION["username"]
$username = $_SESSION["username"];
$fetch_user_query = "SELECT * FROM User WHERE User_Username = ?";
$stmt_user = mysqli_prepare($conn, $fetch_user_query);

mysqli_stmt_bind_param($stmt_user, "s", $username);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);

if (!$result_user) {
    die("Query failed: " . mysqli_error($conn));
}

$user_details = mysqli_fetch_assoc($result_user);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve lost pet data from the form
    $lostName = mysqli_real_escape_string($conn, $_POST['lost-name']);
    $lostBreed = mysqli_real_escape_string($conn, $_POST['lost-breed']);
    $lostAge = mysqli_real_escape_string($conn, $_POST['lost-age']);
    $lostBody = mysqli_real_escape_string($conn, $_POST['lost-body']);
    $lostGender = mysqli_real_escape_string($conn, $_POST['lost-gender']);
    $lostLocation = mysqli_real_escape_string($conn, $_POST['lost-location']);
    $lostDate = mysqli_real_escape_string($conn, $_POST['lost-date']);
    $lostOwnerName = mysqli_real_escape_string($conn, $_POST['lost-owner-name']);
    $lostOwnerPhone = mysqli_real_escape_string($conn, $_POST['lost-owner-phone']);
    $lostOwnerEmail = mysqli_real_escape_string($conn, $_POST['lost-owner-email']);

    // Check if file upload was successful
    if ($_FILES['lost-photo']['error'] == UPLOAD_ERR_OK) {
        // Process file upload
        $lostPhoto = mysqli_real_escape_string($conn, $_FILES['lost-photo']['name']);
        $tempName = $_FILES['lost-photo']['tmp_name'];

        // Check if the file is an image
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $fileType = mime_content_type($tempName);

        if (!in_array($fileType, $allowedTypes)) {
            // Handle invalid file type error
            error_log("Invalid file type: " . $fileType); // Log the file type
            header("Location: lost_detail.php?error=invalidfiletype");
            exit();
        }

        // Specify the directory where you want to move the uploaded file
        $folder = 'images/lost/' . $lostPhoto;

        // Check if the directory is writable
        if (!is_writable('images/lost/')) {
            // Handle directory not writable error
            header("Location: lost_detail.php?error=directorynotwritable");
            exit();
        }

        // Move the uploaded file to the desired folder
        if (move_uploaded_file($tempName, $folder)) {
            // Insert lost pet data into Lost table using prepared statement
            $insert_lost_query = "INSERT INTO LostPet (User_ID, Lost_Name, Lost_Breed, Lost_Age, Lost_Body, Lost_Gender, Lost_Location, Lost_Date, Lost_Photo)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt_insert_lost = mysqli_prepare($conn, $insert_lost_query);
            
            mysqli_stmt_bind_param(
                $stmt_insert_lost,
                "issssssss",
                $user_details['User_ID'],
                $lostName,
                $lostBreed,
                $lostAge,
                $lostBody,
                $lostGender,
                $lostLocation,
                $lostDate,
                $lostPhoto
            );

            mysqli_stmt_execute($stmt_insert_lost);

            // Check if the lost pet insertion was successful
            if (mysqli_stmt_affected_rows($stmt_insert_lost) > 0) {
                // Redirect to the same page with success query parameter
                header("Location: lost_detail.php?lostpet=success");
                exit();
            } else {
                // Lost pet insertion failed
                $error_message = "Lost pet insertion failed: " . mysqli_error($conn);
                error_log($error_message); // Log the error
                header("Location: lost_detail.php?error=lostpetinsertfailed&message=" . urlencode($error_message));
                exit();
            }
            
            // Close the prepared statement
            mysqli_stmt_close($stmt_insert_lost);
            
        } else {
            // Handle file move error
            error_log("File move failed");
            header("Location: lost_detail.php?error=filemovefailed");
            exit();
        }
    } else {
        // Handle file upload error
        error_log("File upload failed");
        header("Location: lost_detail.php?error=fileuploadfailed");
        exit();
    }
}

// Close prepared statements
mysqli_stmt_close($stmt_user);
mysqli_stmt_close($stmt_insert_lost);
mysqli_close($conn);
?>