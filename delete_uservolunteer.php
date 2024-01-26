<?php
session_start();
require 'dbConn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $volunteer_id = isset($_POST['volunteer_id']) ? intval($_POST['volunteer_id']) : 0;

    if ($volunteer_id > 0) {
        $sql = "DELETE FROM Volunteer WHERE Volunteer_ID = ?";
        $stmt = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $volunteer_id);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                // After successfully deleting the volunteer
                $_SESSION['success_message'] = "Volunteer deleted successfully.";
                header("Location: user.php?deletev=success");
                exit();
            } else {
                error_log("Error deleting record: " . mysqli_error($conn));
                echo "Error deleting record. Please try again later.";
            }
        } else {
            error_log("Error preparing statement: " . mysqli_error($conn));
            echo "Error preparing statement. Please try again later.";
        }
    } else {
        error_log("Error: Invalid volunteer_id provided.");
        echo "Error: Invalid volunteer_id provided.";
    }
} else {
    error_log("Error: Invalid request method.");
    echo "Error: Invalid request method.";
}

mysqli_close($conn);
?>
