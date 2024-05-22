<?php 
session_start();
require "../config/dbconn.php";
$productID = $_GET["productID"];
$userID = $_SESSION["userID"];

$checkQty = "SELECT quantity FROM cart WHERE productID = $productID AND userID = $userID";
$query = mysqli_query($conn, $checkQty);
$qtyFetched = mysqli_fetch_assoc($query);


if ($qtyFetched['quantity'] == 1) {
    $sql = "DELETE FROM cart WHERE productID = $productID AND userID = $userID";
    $result = mysqli_query($conn, $sql);
    header("Location: ../pages/cart.php");
    exit();
} else {
    $sql = "UPDATE cart SET quantity = quantity - 1 WHERE productID = $productID AND userID = $userID";
    $result = mysqli_query($conn, $sql);
    header("Location: ../pages/cart.php");
    exit();
}



?>