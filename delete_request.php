<?php
session_start();

// Include your database connection script
require 'dbConn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize adopt_id
    $adopt_id = isset($_POST['adopt_id']) ? intval($_POST['adopt_id']) : 0;

    // Check if adopt_id is a valid integer
    if ($adopt_id > 0) {
        // Prepare the DELETE query
        $sql = "DELETE FROM Adoption WHERE Adoption_ID = ?";

        // Initialize a prepared statement
        $stmt = mysqli_stmt_init($conn);

        // Check if the prepared statement can be initialized
        if (mysqli_stmt_prepare($stmt, $sql)) {
            // Bind the parameters to the prepared statement
            mysqli_stmt_bind_param($stmt, "i", $adopt_id);

            // Execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Set success message
                $_SESSION['success_message'] = "Adoption record deleted successfully.";
            } else {
                // Log the error instead of displaying it to the user
                error_log("Error deleting record: " . mysqli_error($conn));
            }

            // Close the prepared statement
            mysqli_stmt_close($stmt);
        } else {
            // Log the error instead of displaying it to the user
            error_log("Error preparing statement: " . mysqli_error($conn));
        }
    } else {
        // Log the error instead of displaying it to the user
        error_log("Error: Invalid adopt_id provided.");
    }
} else {
    // Log the error instead of displaying it to the user
    error_log("Error: Invalid adopt method.");
}

// Close the database connection
mysqli_close($conn);

// Redirect to the appropriate page after processing
header("Location: edit_request.php");
exit();
?>
