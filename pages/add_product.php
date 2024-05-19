<?php
session_start();
require "../config/dbconn.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Products</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="settings-container">
        <?php  include_once '../components/sidebar.php'; ?>
        <div class="add-product-container">
            <form action="../crud/create_product.php" method="POST" enctype="multipart/form-data">
                <label for="">Product Name</label>
                <input type="text" name="productName"><br>
                <label for="">Product Description</label>
                <input type="text" name="productDesc"><br>
                <label for="">Product Price</label>
                <input type="number" name="productPrice"><br>
                <label for="">Product Stock</label>
                <input type="number" name="productStock"><br>
                <label for="">Upload Picture</label>
                <input type="file" name="productImg" id="productImg" accept=".jpg, .jpeg, .png, .avif"/><br>
                <input type="submit" value="Add Product" name="createProduct" id="createProduct">
            </form>
        </div>
    </div>
</body>
</html>

<?php
if(isset($_SESSION['alert'])) {
    echo $_SESSION['alert'];
    unset($_SESSION['alert']);
}
?>