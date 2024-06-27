<?php
session_start();
require '../config/dbconn.php'; 

$orderID = $_GET['orderID'];

$sql = "UPDATE orders SET orderStatus = 'Cancelled', detailedStatus = 'Cancelled' WHERE orderID = '$orderID'";
$result = mysqli_query($conn, $sql);
if ($result) {
    $_SESSION['alert'] = '
    <script>
        Swal.fire({
          title: "Success!",
          text: "Order Cancelled!",
          icon: "success"
        });
    </script>;';
    header("Location: ../pages/my_orders.php");
} else {
    $_SESSION['alert'] = '
    <script>
        Swal.fire({
          title: "Error!",
          text: "Something went wrong!",
          icon: "error"
        });
    </script>;';
    header("Location: ../pages/my_orders.php");
}