<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Products</title>
</head>
<body>
    <div class="settings-container">
        <?php  include_once '../components/sidebar.php'; ?>
        <div class="add-product-container">
            <form action="">
                <label for="">Product Name</label>
                <input type="text"><br>
                <label for="">Product Description</label>
                <input type="text"><br>
                <label for="">Product Price</label>
                <input type="number"><br>
                <label for="">Product Stock</label>
                <input type="number"><br>
                <label for="">Upload Picture</label>
                <input type="file" name="productImg" id="productImg" accept=".jpg, .jpeg, .png, .avif"/><br>
                <input type="submit" value="Add Product" name="createProduct">
            </form>
        </div>
    </div>
</body>
</html>