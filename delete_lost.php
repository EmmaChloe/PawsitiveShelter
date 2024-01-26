<?php
session_start();

// Include your database connection script
require 'dbConn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize lost_id
    $lost_id = isset($_POST['lost_id']) ? intval($_POST['lost_id']) : 0;

    // Check if lost_id is a valid integer
    if ($lost_id > 0) {
        // Prepare the DELETE query
        $sql = "DELETE FROM LostPet WHERE Lost_ID = ?";

        // Initialize a prepared statement
        $stmt = mysqli_stmt_init($conn);

        // Check if the prepared statement can be initialized
        if (mysqli_stmt_prepare($stmt, $sql)) {
            // Bind the parameters to the prepared statement
            mysqli_stmt_bind_param($stmt, "i", $lost_id);

            // Execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Close the prepared statement
                mysqli_stmt_close($stmt);

                // Set success message
                $_SESSION['success_message'] = "Lost pet deleted successfully.";

                // Redirect to the appropriate page after successful deletion
                header("Location: edit_lost.php?delete=success");
                exit();
            } else {
                // Log the error and display a detailed error message
                $error_message = "Error deleting lost pet record: " . mysqli_error($conn);
                error_log($error_message);
                $_SESSION['error_message'] = "Error deleting lost pet record. Please try again later.";
            }
        } else {
            // Log the error instead of displaying it to the user
            error_log("Error preparing statement: " . mysqli_error($conn));
            $_SESSION['error_message'] = "Error preparing statement. Please try again later.";
        }
    } else {
        // Log the error instead of displaying it to the user
        error_log("Error: Invalid lost_id provided.");
        $_SESSION['error_message'] = "Error: Invalid lost_id provided.";
    }
} else {
    // Log the error instead of displaying it to the user
    error_log("Error: Invalid request method.");
    $_SESSION['error_message'] = "Error: Invalid request method.";
}

// Close the database connection
mysqli_close($conn);

// Redirect to an appropriate page (error page or previous page)
header("Location: edit_lost.php?delete=failed");
exit();
?>
