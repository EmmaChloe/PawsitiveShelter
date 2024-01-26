<?php
session_start();
include_once 'dbConn.php';

if (isset($_SESSION['user_id'])) {
    $pid = $_SESSION['user_id'];

    // Update the donation status to 'Cancelled'
    $updateDonationStatus = $conn->query("UPDATE Donation SET Donation_Status='Cancelled' WHERE User_ID='$pid'");

    if ($updateDonationStatus) {
        // Update successful
    } else {
        // Update failed
        echo 'Error updating donation status: ' . $conn->error;
    }

    session_destroy();
} else {
    // Handle the case when 'user_id' is not set in the session
    echo 'Error: User ID not set.';
}

// Redirect to donate_detail.php with the 'donation' query parameter set to 'success'
header('Location: donate_detail.php?donation=error');
exit;
?>
