<?php 

    $servername = "localhost:3306";
    $dBUsername = "umskalfy1_pawadmin";
    $dBPassword = "Password@0101";
    $dBName = "umskalfy1_pawshelter";

    $conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);


    if(!$conn){
        die("Connection failed: ".mysqli_connect_error());
    }

?>