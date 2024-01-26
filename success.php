<?php
session_start();
include_once 'dbConn.php';

// You might want to keep any other session-related logic if needed

// Update the donation status to 'Completed'
$updateDonationStatus = $conn->query("UPDATE Donation SET Donation_Status='Completed' WHERE User_ID='{$_SESSION['user_id']}'");

if ($updateDonationStatus) {
    // Update successful
} else {
    // Update failed
    echo 'Error updating donation status: ' . $conn->error;
}


// You can keep the rest of your success message or redirection logic
header('Location: donate_detail.php?donation=success');
exit;
?>
