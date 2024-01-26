<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Include your database connection script
require 'dbConn.php';

// Get user ID from the session
$username = $_SESSION['username'];

// Arrays to store fields and values for update
$fieldsToUpdate = array();
$valuesToUpdate = array();

// Handle image upload if a new image is provided
if ($_FILES['User_Photo']['error'] == 0) {
    // Validate and read the uploaded image file
    $imageData = file_get_contents($_FILES['User_Photo']['tmp_name']);

    // Update the user's profile image in the database
    $updateImageSQL = "UPDATE User SET User_Photo = ? WHERE User_Username = ?";
    $stmtUpdateImage = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmtUpdateImage, $updateImageSQL)) {
        // Bind parameters and execute the statement
        mysqli_stmt_bind_param($stmtUpdateImage, "ss", $imageData, $username);
        mysqli_stmt_send_long_data($stmtUpdateImage, 0, $imageData);
        if (mysqli_stmt_execute($stmtUpdateImage)) {
            // Image update successful
            mysqli_stmt_close($stmtUpdateImage);
        } else {
            // Handle the error (e.g., log it)
            echo "Error updating profile image: " . mysqli_error($conn);
            exit();
        }
    }
}

// Handle other profile fields update 
if (isset($_POST['User_Name'], $_POST['User_Username'], $_POST['User_Phone'], $_POST['User_Email'])) {
    // Add fields to update array
    $fieldsToUpdate[] = "User_Name = ?";
    $fieldsToUpdate[] = "User_Username = ?";
    $fieldsToUpdate[] = "User_Phone = ?";
    $fieldsToUpdate[] = "User_Email = ?";

    // Add values to update array
    $valuesToUpdate[] = $_POST['User_Name'];
    $valuesToUpdate[] = $_POST['User_Username'];
    $valuesToUpdate[] = $_POST['User_Phone'];
    $valuesToUpdate[] = $_POST['User_Email'];

    // Update the user's profile information in the database
    $updateProfileSQL = "UPDATE User SET " . implode(", ", $fieldsToUpdate) . " WHERE User_Username = ?";
    $stmtUpdateProfile = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmtUpdateProfile, $updateProfileSQL)) {
        // Bind parameters and execute the statement
        $paramTypes = str_repeat("s", count($valuesToUpdate)) . "s";
        
        // Combine the parameters into an array
        $params = array_merge([$stmtUpdateProfile, $paramTypes], $valuesToUpdate, [$username]);

        // Use call_user_func_array to bind the parameters
        call_user_func_array('mysqli_stmt_bind_param', $params);

        if (mysqli_stmt_execute($stmtUpdateProfile)) {
            // Profile update successful
            mysqli_stmt_close($stmtUpdateProfile);

            // Redirect with success message
            header("Location: user.php?success=profile_updated_successfully");
            exit();
        } else {
            // Handle the error (e.g., log it)
            echo "Error updating profile: " . mysqli_error($conn);
            exit();
        }
    }
}

// Close the database connection
mysqli_close($conn);

// Redirect back to the profile page (in case of any other condition)
header("Location: user.php");
exit();
?>
