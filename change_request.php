<?php
session_start();

// Include the database connection file
include('dbConn.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the Adoption ID is set
    if (isset($_POST['adopt_id'])) {
        $adopt_id = $_POST['adopt_id'];

        // Check if the adoption ID exists in the database
        $checkAdoptionSQL = "SELECT * FROM Adoption WHERE Adoption_ID = ?";
        $stmtCheckAdoption = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmtCheckAdoption, $checkAdoptionSQL)) {
            mysqli_stmt_bind_param($stmtCheckAdoption, "i", $adopt_id);
            mysqli_stmt_execute($stmtCheckAdoption);

            // Fetch the result
            $resultCheckAdoption = mysqli_stmt_get_result($stmtCheckAdoption);

            // Check if the result is not null
            if ($resultCheckAdoption !== null) {
                $adoptionDetails = mysqli_fetch_assoc($resultCheckAdoption);

                // Check if adoption details are not null
                if ($adoptionDetails !== null) {
                    // The adoption ID exists, proceed with the update

                    // Assuming you have more fields to update, add them here
                    $edit_adoptionStatus = $_POST['edit_adoptionStatus'];

                    // Update the adoption status in the database
                    $updateRequestSQL = "UPDATE Adoption SET Adoption_Status = ? WHERE Adoption_ID = ?";
                    $stmtUpdateRequest = mysqli_stmt_init($conn);

                    if (mysqli_stmt_prepare($stmtUpdateRequest, $updateRequestSQL)) {
                        // Bind parameters and execute the statement
                        mysqli_stmt_bind_param($stmtUpdateRequest, "si", $edit_adoptionStatus, $adopt_id);

                        if (mysqli_stmt_execute($stmtUpdateRequest)) {
                            // Request update successful
                            mysqli_stmt_close($stmtUpdateRequest);

                            // Set a success message
                            $_SESSION['success_message'] = 'Adoption request updated successfully';
                        } else {
                            // Handle the error (e.g., log it)
                            $_SESSION['error_message'] = "Error updating adoption request: " . mysqli_error($conn);
                        }
                    } else {
                        die("Update request query preparation failed: " . mysqli_error($conn));
                    }
                } else {
                    // Adoption details are null, set an error message
                    $_SESSION['error_message'] = "Adoption details are null for Adoption ID: " . $adopt_id;
                }
            } else {
                // Result is null, set an error message
                $_SESSION['error_message'] = "Result is null for Adoption ID: " . $adopt_id;
            }

            // Close the statement
            mysqli_stmt_close($stmtCheckAdoption);
        } else {
            die("Check adoption query preparation failed: " . mysqli_error($conn));
        }

        // Close the database connection
        mysqli_close($conn);

        // Redirect back to the original page or any other page as needed
        header("Location: edit_request.php");
        exit();
    } else {
        // Handle case when Adoption ID is not set
        $_SESSION['error_message'] = "Adoption ID not set.";
        header("Location: edit_request.php");
        exit();
    }
} else {
    // If the form is not submitted, redirect to the original page or handle accordingly
    header('Location: edit_request.php');
    exit();
}
?>
