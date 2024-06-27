<?php
session_start();
require "../config/dbconn.php";

$orderID = $_GET['orderID'];
$action = $_GET['action'];


if ($action == 'failed') {
    $sql = "UPDATE orders SET orderStatus = 'Unsuccessful', detailedStatus = 'Order Unsuccessful' WHERE orderID = '$orderID'";
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
              title: 'Order #{$orderID} marked as unsuccessful'
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
    $sql = "UPDATE orders SET orderStatus = 'Completed', paymentStatus = 'Payment Accepted', detailedStatus = 'Order Fulfilled' WHERE orderID = '$orderID'";
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
              title: 'Order #{$orderID} marked as completed'
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