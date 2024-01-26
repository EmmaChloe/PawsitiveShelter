<?php
session_start();

// Include your database connection script
require 'dbConn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize user_id
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

    // Check if user_id is a valid integer
    if ($user_id > 0) {
        // Prepare the DELETE query
        $sql = "DELETE FROM User WHERE User_ID = ?";

        // Initialize a prepared statement
        $stmt = mysqli_stmt_init($conn);

        // Check if the prepared statement can be initialized
        if (mysqli_stmt_prepare($stmt, $sql)) {
            // Bind the parameters to the prepared statement
            mysqli_stmt_bind_param($stmt, "i", $user_id);

            // Execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Close the prepared statement
                mysqli_stmt_close($stmt);

                // Set success message
                $_SESSION['success_message'] = "User deleted successfully.";

                // Redirect to the appropriate page after successful deletion
                header("Location: edit_admin.php");
                exit();
            } else {
                // Log the error instead of displaying it to the user
                error_log("Error deleting record: " . mysqli_error($conn));
                echo "Error deleting record. Please try again later.";
            }
        } else {
            // Log the error instead of displaying it to the user
            error_log("Error preparing statement: " . mysqli_error($conn));
            echo "Error preparing statement. Please try again later.";
        }
    } else {
        // Log the error instead of displaying it to the user
        error_log("Error: Invalid user_id provided.");
        echo "Error: Invalid user_id provided.";
    }
} else {
    // Log the error instead of displaying it to the user
    error_log("Error: Invalid request method.");
    echo "Error: Invalid request method.";
}

// Close the database connection
mysqli_close($conn);
?>