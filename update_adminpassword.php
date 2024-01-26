<?php
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include your database connection script
    require 'dbConn.php';

    // Fetch user details from the User table
    $username = $_SESSION['username'];
    $sqlUser = "SELECT User_ID, User_Password FROM User WHERE User_Username = ?";
    $stmtUser = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmtUser, $sqlUser)) {
        mysqli_stmt_bind_param($stmtUser, "s", $username);
        mysqli_stmt_execute($stmtUser);
        $resultUser = mysqli_stmt_get_result($stmtUser);

        // Check if the user exists
        if ($resultUser && mysqli_num_rows($resultUser) > 0) {
            // Fetch user ID and current password
            $userDetails = mysqli_fetch_assoc($resultUser);
            $userID = $userDetails['User_ID'];
            $currentPasswordHash = $userDetails['User_Password'];

            // Validate and sanitize input
            $currentPassword = mysqli_real_escape_string($conn, $_POST['currentPassword']);
            $newPassword = mysqli_real_escape_string($conn, $_POST['newPassword']);
            $renewPassword = mysqli_real_escape_string($conn, $_POST['renewPassword']);

            // Verify the current password against the one stored in the database
            if (password_verify($currentPassword, $currentPasswordHash)) {
                // Check if new password and re-entered password match
                if ($newPassword === $renewPassword) {
                    // Hash the new password
                    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                    // Update the user's password in the database
                    $updatePasswordSQL = "UPDATE User SET User_Password = ? WHERE User_ID = ?";
                    $stmtUpdatePassword = mysqli_stmt_init($conn);

                    if (mysqli_stmt_prepare($stmtUpdatePassword, $updatePasswordSQL)) {
                        mysqli_stmt_bind_param($stmtUpdatePassword, "si", $hashedNewPassword, $userID);
                        mysqli_stmt_execute($stmtUpdatePassword);

                        // Password update successful
                        mysqli_stmt_close($stmtUpdatePassword);
                        mysqli_close($conn);
                        header("Location: profile.php?success=password_updated_successfully#change-password");
                        exit();
                    } else {
                        // Handle the error (e.g., log it)
                        echo "Error updating password: " . mysqli_error($conn);
                    }
                } else {
                    // New password and re-entered password don't match
                    mysqli_close($conn);
                    header("Location: profile.php?error=new_passwords_do_not_match#change-password");
                    exit();
                }
            } else {
                // Incorrect current password
                mysqli_close($conn);
                header("Location: profile.php?error=incorrect_current_password#change-password");
                exit();
            }

            mysqli_stmt_close($stmtUser);
        } else {
            // Redirect to an appropriate page or display an error message
            mysqli_close($conn);
            header("Location: login.php");
            exit();
        }
    } else {
        echo "Error preparing profile query: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // Redirect back to the profile page if the form is not submitted
    header("Location: profile.php?error=form_not_submitted#change-password");
    exit();
}
?>
