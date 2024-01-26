<?php
session_start();
require 'dbConn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lost_id = isset($_POST['lost_id']) ? intval($_POST['lost_id']) : 0;

    if ($lost_id > 0) {
        $sql = "DELETE FROM LostPet WHERE Lost_ID = ?";
        $stmt = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $lost_id);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                // After successfully deleting the lost pet
                $_SESSION['success_message'] = "Lost pet deleted successfully.";
                header("Location: user.php?deletel=success");
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
        error_log("Error: Invalid lost_id provided.");
        echo "Error: Invalid lost_id provided.";
    }
} else {
    error_log("Error: Invalid request method.");
    echo "Error: Invalid request method.";
}

mysqli_close($conn);
?>
