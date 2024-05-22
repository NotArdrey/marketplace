<?php 
require "../config/dbconn.php";
$prodID = $_GET["productID"];

$sql = "UPDATE cart SET quantity = quantity+1 WHERE productID = $prodID";
$result = mysqli_query($conn,$sql);
header("Location:../pages/cart.php");
exit();
?>