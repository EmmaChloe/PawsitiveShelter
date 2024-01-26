<?php
session_start();
require 'dbConn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $news_id = isset($_POST['news_id']) ? intval($_POST['news_id']) : 0;

    if ($news_id > 0) {
        $sql = "DELETE FROM News WHERE News_ID = ?";
        $stmt = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $news_id);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                // After successfully deleting news
                $_SESSION['success_message'] = "News deleted successfully.";
                header("Location: edit_news.php?delete=success");
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
        error_log("Error: Invalid news_id provided.");
        echo "Error: Invalid news_id provided.";
    }
} else {
    error_log("Error: Invalid request method.");
    echo "Error: Invalid request method.";
}

mysqli_close($conn);
?>
