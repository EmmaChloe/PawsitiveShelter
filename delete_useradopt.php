<?php
// Include your database connection file
include 'dbConn.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the adoption ID to delete
    $adoptionIdToDelete = $_POST['adoption_id'];

    // Prepare the delete query using prepared statements
    $deleteQuery = "DELETE FROM Adoption WHERE Adoption_ID = ?";

    $stmt = $conn->prepare($deleteQuery);

    // Check if the prepared statement was successful
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("i", $adoptionIdToDelete);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect back to user.php with a success parameter
            header('Location: user.php?delete=success');
            exit();
        } else {
            // Handle the error
            $error_message = "Adoption request deletion failed: " . $stmt->error;
            error_log($error_message);
            header("Location: user.php?error=adoptiondeletefailed&message=" . urlencode($error_message));
            exit();
        }
    } else {
        // Handle the case where the prepared statement failed
        $error_message = "Prepared statement error: " . $conn->error;
        error_log($error_message);
        header("Location: user.php?error=statementerror&message=" . urlencode($error_message));
        exit();
    }
} else {
    // Redirect to an error page or handle the case where the form is not submitted
    header('Location: error.php');
    exit();
}
?>
