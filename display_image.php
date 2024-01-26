<?php
// Include your database connection script
require 'dbConn.php';

// Get user ID from the query string
if (isset($_GET['User_ID'])) {
    $User_ID = $_GET['User_ID'];

    // Fetch user details from the User table
    $sqlUser = "SELECT User_Photo FROM User WHERE User_ID = ?";
    $stmtUser = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmtUser, $sqlUser)) {
        mysqli_stmt_bind_param($stmtUser, "i", $User_ID);
        mysqli_stmt_execute($stmtUser);
        $result_user = mysqli_stmt_get_result($stmtUser);

        if ($result_user) {
            $user_details = mysqli_fetch_assoc($result_user);

            if ($user_details) {
                if ($user_details['User_Photo'] === 'jpeg') {
                    header("Content-type: image/jpeg");
                } elseif ($user_details['User_Photo'] === 'png') {
                    header("Content-type: image/png");
                } elseif ($user_details['User_Photo'] === 'jpg') {
                    header("Content-type: image/jpg");
                } elseif ($user_details['User_Photo'] === 'jfif') {
                    header("Content-type: image/jfif");
                }

                // Output the image data directly
                echo $user_details['User_Photo'];
            } else {
                echo 'No user data found for the given ID';
            }
        } else {
            echo 'Error executing the statement: ' . mysqli_stmt_error($stmtUser);
        }

        mysqli_stmt_close($stmtUser);
    } else {
        echo 'Error preparing statement: ' . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // Handle the case where no user ID is provided
    echo 'User ID not provided';
}
?>
