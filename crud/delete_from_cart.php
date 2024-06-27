<?php
session_start();
require "../config/dbconn.php";

$variationID = $_GET['variationID'];
$userID = $_SESSION['userID'];

$sql = "DELETE FROM cart WHERE variationID = '$variationID' AND userID = '$userID'";
$result = mysqli_query($conn, $sql);

header("Location: ../pages/cart.php");
exit();



?>