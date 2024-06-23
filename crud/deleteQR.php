<?php
session_start();
require "../config/dbconn.php";
$userID = $_SESSION['userID'];

$sql = "UPDATE users SET qr_code = NULL WHERE userID = '$userID'";
$result = mysqli_query($conn, $sql);
if ($result) {
    $_SESSION['alert'] = "
                <script>
                    const Toast = Swal.mixin({
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                      toast.onmouseenter = Swal.stopTimer;
                      toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                    Toast.fire({
                      icon: 'success',
                      title: 'QR Code was successfully deleted.'
                    });
                </script>
                ";
} else {
    $_SESSION['alert'] = "
                <script>
                    const Toast = Swal.mixin({
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                      toast.onmouseenter = Swal.stopTimer;
                      toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                    Toast.fire({
                      icon: 'error',
                      title: 'Something went wrong.'
                    });
                </script>
                ";
}
header("Location: ../pages/upload_qr.php");
?>