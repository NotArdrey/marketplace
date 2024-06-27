<?php
session_start();
require '../config/dbconn.php'; 

$userID = $_GET['userID'];

$sql = "DELETE FROM users WHERE userID = $userID";
$result = mysqli_query($conn, $sql);
if ($result) {
    $_SESSION['alert'] = '
    <script>
        Swal.fire({
          title: "Success!",
          text: "Account Deleted!",
          icon: "success"
        });
    </script>;';
} else {
    $_SESSION['alert'] = '
    <script>
        Swal.fire({
          title: "Error!",
          text: "Something went wrong!",
          icon: "error"
        });
    </script>;';
}
header("Location: ../pages/users.php");