<?php

if (!isset($_SESSION['userID'])) {
    header("Location: ../pages/index.php");
} else {
    $userID = $_SESSION['userID'];
    $sql = "SELECT * FROM users WHERE userID = '$userID'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $vStatus = $row['verify_status'];
    if ($vStatus == 0) {
        header("Location: ../pages/index.php");
    } 
}  

?>