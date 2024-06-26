<?php
session_start();
require "../config/dbconn.php";

$orderID = $_POST['orderID'];
$action = $_POST['action'];
$action = strtolower($action);


if ($action == 'reject') {
    $sql = "UPDATE orders SET orderStatus = 'Rejected', paymentStatus = 'Payment Rejected', detailedStatus = 'Payment Rejected' WHERE orderID = '$orderID'";
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
              title: 'Payment for Order #{$orderID} Rejected'
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
              title: 'Something went wrong'
            });
        </script>
    ";
    }
} else {
    $sql = "UPDATE orders SET orderStatus = 'To Receive', paymentStatus = 'Payment Accepted', detailedStatus = 'To Receive' WHERE orderID = '$orderID'";
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
              title: 'Payment for Order #{$orderID} Accepted'
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
              title: 'Something went wrong'
            });
        </script>
    ";
    }
}

header("Location: ../pages/orders.php");