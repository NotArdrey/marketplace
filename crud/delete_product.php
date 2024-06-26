<?php
session_start();
include "../config/dbconn.php";

$productID = $_GET['productID'];

$sql = "DELETE FROM product_categories WHERE productID = '$productID'";
$result = mysqli_query($conn, $sql);
if ($result) {
    $sql = "DELETE FROM variations WHERE productID = '$productID'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $sql = "DELETE FROM payment_method WHERE productID = '$productID'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $sql = "DELETE FROM product_categories WHERE productID = '$productID'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $sql = "DELETE FROM products WHERE productID = '$productID'";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $_SESSION['alert'] = "
                                        <script>
                                            Swal.fire({
                                              title: 'Success!',
                                              text: 'You deleted your product!',
                                              icon: 'success'
                                            });
                                        </script>
                                        ";
                    header("Location: ../pages/manage_product.php");
                }
            }
        }
    }
}