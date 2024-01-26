<?php
// Include the database connection file
include 'dbConn.php';

session_start();

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
    // Retrieve volunteer data from the form
    $volunteerName = mysqli_real_escape_string($conn, $_POST['volunteer-name']);
    $volunteerEmail = mysqli_real_escape_string($conn, $_POST['volunteer-email']);
    $volunteerPhone = mysqli_real_escape_string($conn, $_POST['volunteer-phone']);
    $volunteerCV = $_FILES['volunteer-cv'];
    $volunteerReason = mysqli_real_escape_string($conn, $_POST['volunteer-reason']);
    $volunteerDate = date("Y-m-d"); // or get the date from your form

    // Check if a file was uploaded without errors
    if ($volunteerCV['error'] == UPLOAD_ERR_OK) {
        $cv_temp = $volunteerCV['tmp_name'];
        $cv_name = basename($volunteerCV['name']);
        $cv_path = "cv/" . $cv_name; // Set the path as per your requirements

        // Specify the directory where you want to move the uploaded file
        $cvFolder = __DIR__ . '/cv/' . $cv_name;

        // Check if the directory is writable
        if (!is_writable(__DIR__ . '/cv/')) {
            // Handle directory not writable error
            header("Location: edit_volunteer.php?error=directorynotwritable");
            exit();
        }

        // Move the uploaded file to the desired folder
        if (move_uploaded_file($cv_temp, $cvFolder)) {
            // Insert volunteer data into Volunteer table using prepared statement
            $insert_volunteer_query = "INSERT INTO Volunteer (User_ID, Apply_Date, Apply_CV, Apply_Reason, Volunteer_Name, Volunteer_Email, Volunteer_Phone)
                              VALUES (?, ?, ?, ?, ?, ?, ?)";
        
            $stmt_insert_volunteer = mysqli_prepare($conn, $insert_volunteer_query);
        
            mysqli_stmt_bind_param(
                $stmt_insert_volunteer,
                "issssss",
                $user_details['User_ID'],
                $volunteerDate,
                $cv_path,
                $volunteerReason,
                $volunteerName,
                $volunteerEmail,
                $volunteerPhone
            );
        
            mysqli_stmt_execute($stmt_insert_volunteer);
        
            // Check if the volunteer insertion was successful
            if (mysqli_stmt_affected_rows($stmt_insert_volunteer) > 0) {
                // Redirect to the same page with success query parameter
                header("Location: edit_volunteer.php?volunteer=success");
                exit();
            } else {
                // Volunteer insertion failed
                $error_message = "Volunteer insertion failed: " . mysqli_error($conn);
                error_log($error_message); // Log the error
                header("Location: edit_volunteer.php?error=volunteerinsertfailed");
                exit();
            }
        } else {
            // Handle file move error
            header("Location: edit_volunteer.php?error=filemovefailed");
            exit();
        }
    } else {
        // Handle file upload error
        header("Location: edit_volunteer.php?error=fileuploadfailed");
        exit();
    }
}

// Close prepared statements
mysqli_stmt_close($stmt_user);
mysqli_stmt_close($stmt_insert_volunteer);
mysqli_close($conn);
?>