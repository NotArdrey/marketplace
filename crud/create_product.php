<?php
session_start();
include "../config/dbconn.php";

$productName = $_POST['productName'];
$productDesc = $_POST['productDesc'];
$productPrice = $_POST['productPrice'];
$productStock = $_POST['productStock'];
$productSellerID = $_SESSION['userID'];

function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$productName = validate($productName);
$productDesc = validate($productDesc);
$productPrice = validate($productPrice);
$productStock = validate($productStock);

if ($_FILES["productImg"]["error"] === 4) {
    echo 
    "<script>alert('Image does not exist.');</script>";
} else {
    $fileName = $_FILES["productImg"]["name"];
    $fileSize = $_FILES["productImg"]["size"];
    $tmpName = $_FILES["productImg"]["tmp_name"];

    $validImageExtension = ['jpg', 'jpeg', 'png', 'avif'];
    $imageExtension = explode('.', $fileName);
    $imageExtension = strtolower(end($imageExtension));
    if (!in_array($imageExtension, $validImageExtension)) {
        echo "<script>alert('Invalid image extension.')</script>";
    } else if ($fileSize > 1000000) {
        echo "<script>alert('Image size is too large')</script>";
    } else {
        $newImageName = uniqid() . '.' . $imageExtension;
        $destination = '../product_img/' . $newImageName;

        move_uploaded_file($tmpName, $destination);
        
        $sql = "INSERT INTO products (productName, productSellerID, productDesc, productPrice, productStock, productImg) VALUES ('$productName', '$productSellerID', '$productDesc', '$productPrice', '$productStock', '$newImageName')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['alert'] = "
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Product Added!',
                    text: 'You can now view your product in the dashboard.'
            });
            </script>
            ";
        header("Location: ../pages/add_product.php");
        exit();
    } else {
        $_SESSION['alert'] = "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Creation Failed!',
                    text: 'Sum Ting Went Wong.'
            });
            </script>
            ";
        header("Location: ../pages/add_product.php");
        exit();
    }
}
}


?>