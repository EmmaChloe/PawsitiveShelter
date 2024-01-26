<?php
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have a database connection
    // Include your database connection code here
    require 'dbConn.php';


    // Handle other profile fields update 
    if (isset($_POST['User_Name'], $_POST['User_Username'], $_POST['User_Phone'], $_POST['User_Email'])) {
        // Add fields to update array
        $fieldsToUpdate[] = "User_Name = ?";
        $fieldsToUpdate[] = "User_Username = ?";
        $fieldsToUpdate[] = "User_Phone = ?";
        $fieldsToUpdate[] = "User_Email = ?";
        $fieldsToUpdate[] = "User_Password = ?";
        $fieldsToUpdate[] = "User_Type = ?";

        // Add values to update array
        $valuesToUpdate[] = $_POST['User_Name'];
        $valuesToUpdate[] = $_POST['User_Username'];
        $valuesToUpdate[] = $_POST['User_Phone'];
        $valuesToUpdate[] = $_POST['User_Email'];
        $valuesToUpdate[] = $_POST['User_Password'];
        $valuesToUpdate[] = $_POST['User_Type'];

        // Update the user's profile information in the database
        $updateProfileSQL = "UPDATE User SET " . implode(", ", $fieldsToUpdate) . " WHERE User_ID = ?";
        $stmtUpdateProfile = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmtUpdateProfile, $updateProfileSQL)) {
            // Bind parameters and execute the statement
            $paramTypes = str_repeat("s", count($valuesToUpdate)) . "s";
            
            // Combine the parameters into an array
            $params = array_merge([$stmtUpdateProfile, $paramTypes], $valuesToUpdate, [$_POST['userID']]);

            // Use call_user_func_array to bind the parameters
            call_user_func_array('mysqli_stmt_bind_param', $params);

            if (mysqli_stmt_execute($stmtUpdateProfile)) {
                // Profile update successful
                mysqli_stmt_close($stmtUpdateProfile);

                // Set a success message
                $_SESSION['success_message'] = 'User information updated successfully';
            } else {
                // Handle the error (e.g., log it)
                $_SESSION['error_message'] = "Error updating profile: " . mysqli_error($conn);
            }
        }
    }

    // Close the database connection
    mysqli_close($conn);

    // Redirect back to the original page or any other page as needed
    header("Location: edit_public.php");
    exit();
}

// If the form is not submitted, redirect to the original page or handle accordingly
header('Location: edit_public.php');
exit();
?>
