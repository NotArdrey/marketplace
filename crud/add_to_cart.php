<?php
session_start();
require "../config/dbconn.php";

$productID = $_GET['productID'];
$userID = $_SESSION['userID'];
$page = $_GET['pageID'];
$variationName = $_GET['variation'];
$size = $_GET['size'];
$quantity = $_GET['qty'];


$sql = "SELECT * FROM products WHERE productID = '$productID'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$productName = $row['productName'];
$productSellerID = $row['productSellerID'];

$sql = "SELECT * FROM variations WHERE variationName = '$variationName' AND variationSize = '$size' AND productID = '$productID'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$price = $row['variationPrice'];
$variationID = $row['variationID'];

$sql = "SELECT * FROM cart WHERE productID = '$productID' AND variationID = '$variationID' AND userID = '$userID'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $sql = "UPDATE cart SET quantity = quantity + '$quantity' WHERE productID = '$productID' AND variationID = '$variationID' AND userID = '$userID'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['alert'] = "
        <script>
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
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
              title: 'Added shit to cart'
            });
        ";
    } else {
        //write alert here
        
    }
    
} else {
    $sql = "INSERT INTO cart (userID, productID, sellerID, quantity, unitPrice, timeAdded, variationID) VALUES ('$userID', '$productID', '$productSellerID', '$quantity',
    '$price', CURRENT_TIMESTAMP, '$variationID')";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['alert'] = "
        <script>
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
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
              title: 'Added shit to cart'
            });
        ";
    } else {
        
    }
}
if ($page != "detailed") {
    header("Location: ../pages/customer_dashboard.php");
} else {
    header("Location: ../pages/product_details.php?productID=$productID");
}
exit();

?>