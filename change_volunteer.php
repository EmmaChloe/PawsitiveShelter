<?php
session_start();

require 'dbConn.php';

// Process volunteer form update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $volunteerID = $_POST['Volunteer_ID'];
    $volunteerName = $_POST['volunteer-name'];
    $volunteerEmail = $_POST['volunteer-email'];
    $volunteerPhone = $_POST['volunteer-phone'];
    $volunteerDate = $_POST['volunteer-date'];
    $volunteerReason = $_POST['volunteer-reason'];

    // Check if a file was uploaded without errors
    if (isset($_FILES['volunteer-cv']) && $_FILES['volunteer-cv']['error'] == 0) {
        $cv_temp = $_FILES['volunteer-cv']['tmp_name'];
        $cv_name = $_FILES['volunteer-cv']['name'];
        $cv_path = "cv/" . $cv_name; // Set the path as per your requirements

        // Move the uploaded file to the desired location
        if (move_uploaded_file($cv_temp, $cv_path)) {
            // Update volunteer data in Volunteer Entity using prepared statements
            $update_volunteer_query = "UPDATE Volunteer SET Volunteer_Name = ?, Volunteer_Email = ?, Volunteer_Phone = ?, Apply_Date = ?, Apply_Reason = ?, Apply_CV = ? WHERE Volunteer_ID = ?";
            $stmt = mysqli_prepare($conn, $update_volunteer_query);
            mysqli_stmt_bind_param($stmt, "ssssssi", $volunteerName, $volunteerEmail, $volunteerPhone, $volunteerDate, $volunteerReason, $cv_path, $volunteerID);

            if (mysqli_stmt_execute($stmt)) {
                // Redirect to the same page with success query parameter
                header("Location: edit_volunteer.php?update=success");
                exit();
            } else {
                $error_message = "Volunteer update failed: " . mysqli_error($conn);
                error_log($error_message);
                header("Location: edit_volunteer.php?error=volunteerupdatefailed");
                exit();
            }
        } else {
            $error_message = "Error: File move failed.";
            error_log($error_message);
            echo $error_message;
        }
    } else {
        // Update volunteer data without changing the CV
        $update_volunteer_query = "UPDATE Volunteer SET Volunteer_Name = ?, Volunteer_Email = ?, Volunteer_Phone = ?, Apply_Date = ?, Apply_Reason = ? WHERE Volunteer_ID = ?";
        $stmt = mysqli_prepare($conn, $update_volunteer_query);
        mysqli_stmt_bind_param($stmt, "sssssi", $volunteerName, $volunteerEmail, $volunteerPhone, $volunteerDate, $volunteerReason, $volunteerID);

        if (mysqli_stmt_execute($stmt)) {
            // Redirect to the same page with success query parameter
            header("Location: edit_volunteer.php?update=success");
            exit();
        } else {
            $error_message = "Volunteer update failed: " . mysqli_error($conn);
            error_log($error_message);
            header("Location: edit_volunteer.php?error=volunteerupdatefailed");
            exit();
        }
    }
}
?>
