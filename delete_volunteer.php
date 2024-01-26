<?php
session_start();

// Include your database connection script
require 'dbConn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize volunteer_id
    $volunteer_id = isset($_POST['volunteer_id']) ? intval($_POST['volunteer_id']) : 0;

    // Check if volunteer_id is a valid integer
    if ($volunteer_id > 0) {
        // Prepare the DELETE query
        $sql = "DELETE FROM Volunteer WHERE Volunteer_ID = ?";

        // Initialize a prepared statement
        $stmt = mysqli_stmt_init($conn);

        // Check if the prepared statement can be initialized
        if (mysqli_stmt_prepare($stmt, $sql)) {
            // Bind the parameters to the prepared statement
            mysqli_stmt_bind_param($stmt, "i", $volunteer_id);

            // Execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Close the prepared statement
                mysqli_stmt_close($stmt);

                // Set success message
                $_SESSION['success_message'] = "Volunteer deleted successfully.";

                // Redirect to the appropriate page after successful deletion
                header("Location: edit_volunteer.php?delete=success");
                exit();
            } else {
                // Log the error and display a detailed error message
                $error_message = "Error deleting volunteer record: " . mysqli_error($conn);
                error_log($error_message);
                header("Location: edit_volunteer.php?error=volunteerdeletefailed&message=" . urlencode($error_message));
                exit();
            }
        } else {
            // Log the error and display a detailed error message
            $error_message = "Error preparing statement: " . mysqli_error($conn);
            error_log($error_message);
            header("Location: edit_volunteer.php?error=volunteerdeletefailed&message=" . urlencode($error_message));
            exit();
        }
    } else {
        // Log the error and display a detailed error message
        $error_message = "Error: Invalid volunteer_id provided.";
        error_log($error_message);
        header("Location: edit_volunteer.php?error=volunteerdeletefailed&message=" . urlencode($error_message));
        exit();
    }
} else {
    // Log the error and display a detailed error message
    $error_message = "Error: Invalid request method.";
    error_log($error_message);
    header("Location: edit_volunteer.php?error=volunteerdeletefailed&message=" . urlencode($error_message));
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
