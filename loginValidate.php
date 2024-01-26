<?php
session_start();
require 'dbConn.php';

if (isset($_POST['login'])) {
    $username = validate($_POST['username']);
    $password = validate($_POST['password']);

    if (empty($username) || empty($password)) {
        header("Location: login.php?error=Username and password are required");
        exit();
    }

    // Use prepared statements
    $sql = "SELECT * FROM User WHERE User_Username=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location:login.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $hashedPassword = $row['User_Password'];

            // Verify the hashed password
            if (password_verify($password, $hashedPassword)) {
                session_start();
                $_SESSION['username'] = $row['User_Username'];

                if ($row['User_Type'] == 'Admin') {
                    header("Location: admin.php?login=success");
                    exit();
                } else if ($row['User_Type'] == 'User') {
                    header("Location: index.php?login=success");
                    exit();
                }
            } else {
                header("Location:login.php?error=Incorrect password");
                exit();
            }
        } else {
            header("Location: login.php?error=Username not found");
            exit();
        }
    }
} else {
    header("Location: login.php");
    exit();
}

// Validation function
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
