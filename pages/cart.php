<?php
session_start();
require "../config/dbconn.php";

$userID = $_SESSION['userID'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link rel="stylesheet" href="../styles/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body>
    <?php
        include_once '../components/navbar.php';
    ?>
    <div class="content">
        <h1>My Cart</h1>
        <div class="cart">

        <?php
            $sql = "SELECT DISTINCT sellerID FROM cart WHERE userID = '$userID'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $sellerID = $row['sellerID'];

                    $sql_items = "
                        SELECT e1.productName, e1.productPrice, e1.productImg, cart.quantity, cart.productID,
                        SUM(e1.productPrice * cart.quantity) OVER () AS totalAmount 
                        FROM products e1 
                        INNER JOIN cart ON e1.productID = cart.productID 
                        WHERE cart.userID = '$userID' AND cart.sellerID = '$sellerID' ORDER BY cart.timeAdded
                    ";
                    
                    $result_items = mysqli_query($conn, $sql_items);

                    echo "<h1>Seller ID: $sellerID </h1>";

                    if (mysqli_num_rows($result_items) > 0) {
                        $row = mysqli_fetch_assoc($result_items);
                        $totalAmount = $row['totalAmount'];
                        mysqli_data_seek($result_items, 0);
                        $num_rows = mysqli_num_rows($result_items);

                        echo "<form method='POST' action='../crud/process_order.php'>";
                        for ($i = 0; $i < $num_rows; $i++) {
                            $item = mysqli_fetch_assoc($result_items);
                            echo "
                                <div class='cart-item-container'>
                                    <div class='cart-item'>
                                        <div class='cart-product-img'><img src='../product_img/" . $item['productImg'] . "'></div>
                                        <div class='cart-product-name'><p>" . $item['productName'] . "</p></div>
                                        <div class='cart-product-unit-price'><p><i class='fa-solid fa-peso-sign'></i>" . $item['productPrice'] . "</p></div>
                                        <div class='cart-product-quantity'>
                                            <div class='quantity-icon-div'>
                                                <a href='../crud/minusQuantity.php?productID=".$item['productID']."'><i class='fa-solid fa-square-minus'></i></a>
                                            </div>
                                            <p>" . $item['quantity'] . "</p>
                                            <div class='quantity-icon-div'>
                                                <a href='../crud/addQuantity.php?productID=".$item['productID'] ."'><i class='fa-solid fa-square-plus'></i></a>
                                            </div>
                                        </div>
                                        <div class='cart-product-subtotal'>
                                            <p class=''><i class='fa-solid fa-peso-sign'></i>" . number_format((float)$item['productPrice'] * $item['quantity'], 2, '.', '') . "</p>
                                            <p class='cart-product-delete'><a href='../crud/delete_from_cart.php?productID=" . $item['productID'] . "'>Delete</a></p>
                                        </div>
                                    </div>
                                    <input type='hidden' name='productID[]' value='" . $item['productID'] . "'>
                                    <input type='hidden' name='quantity[]' value='" . $item['quantity'] . "'>
                                    <input type='hidden' name='price[]' value='" . $item['productPrice'] . "'>
                                </div>
                            ";
                        }
                        $row = mysqli_fetch_assoc($result_items);
                        echo "
                        <div class='cart-product-total'>
                            <div></div>
                            <div class='cart-total-div'>
                                <div class='total-amount'>
                                    Total: " . number_format((float)$totalAmount, 2, '.', '') . "
                                </div>
                                <input type='hidden' name='sellerID' value='" . $sellerID . "'>
                                <input type='hidden' name='totalAmount' value='" . $totalAmount . "'>
                                <button type='submit' class='place-order-btn'>
                                    Place Order
                                </button>
                            </div>
                        </div>
                        </form>";
                    }
                    
                }
            }
        ?>
        </div>
    </div>
</body>
</html>