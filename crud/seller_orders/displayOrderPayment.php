<?php
session_start();
require "../../config/dbconn.php";

$orderID = $_GET['orderID'];

$sql = "SELECT * FROM orders WHERE orderID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderID);
$stmt->execute();
$result = $stmt->get_result();

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    
    $orderID = $row['orderID'];
    $status = $row['orderStatus'];
    $totalPrice = $row['totalAmount'];
    $sellerID = $row['sellerID'];
    $orderPlaced = $row['orderDate'];
    $buyerID = $row['userID'];
    $modeOfPayment = $row['paymentMethod'];
    $paymentImg = $row['paymentImg'];
    $paymentStatus = $row['paymentStatus'];

    $userSql = "SELECT * FROM users WHERE userID = ?";
    $userStmt = $conn->prepare($userSql);
    $userStmt->bind_param("i", $buyerID);
    $userStmt->execute();
    $userResult = $userStmt->get_result();
    $userRow = mysqli_fetch_assoc($userResult);

    $buyerName = $userRow['first_name'] . ' ' . $userRow['last_name'];
    $contactNum = $userRow['contact_number'];

    echo '
    <div class="my-orders-display all-my-orders pending-orders" id="displayedOrder">
        <div class="orders-details">
            <div class="orders-details-row">
                <div class="left-details-row"><i class="fa-solid fa-circle-user"></i><strong>' . $buyerName . '</strong><i class="fa-solid fa-phone"></i><strong>' . $contactNum . '</strong></div>
                <div class="right-details-row">' . $status . '</div>
            </div>
        </div>
        <div class="orders-items">';

    // Fetch items for this order
    $itemSql = "SELECT * FROM order_items WHERE orderID = ?";
    $itemStmt = $conn->prepare($itemSql);
    $itemStmt->bind_param("i", $orderID);
    $itemStmt->execute();
    $itemResult = $itemStmt->get_result();

    if ($itemResult && mysqli_num_rows($itemResult) > 0) {
        while ($itemRow = mysqli_fetch_assoc($itemResult)) {
            $productID = $itemRow['productID'];
            $variationID = $itemRow['variationID'];
            $quantity = $itemRow['quantity'];
            $price = $itemRow['price'];

            // Fetch product details from the products table
            $productSql = "SELECT * FROM products WHERE productID = ?";
            $productStmt = $conn->prepare($productSql);
            $productStmt->bind_param("i", $productID);
            $productStmt->execute();
            $productResult = $productStmt->get_result();
            $productRow = mysqli_fetch_assoc($productResult);
            $productName = $productRow['productName'];
            $productImg = $productRow['productImg'];

            // Fetch variation details from the variations table
            $variationSql = "SELECT * FROM variations WHERE variationID = ?";
            $variationStmt = $conn->prepare($variationSql);
            $variationStmt->bind_param("i", $variationID);
            $variationStmt->execute();
            $variationResult = $variationStmt->get_result();
            $variationRow = mysqli_fetch_assoc($variationResult);
            $variationName = $variationRow['variationName'];
            $size = $variationRow['variationSize'];
            $dateTime = new DateTime($orderPlaced);
            $formattedDate = $dateTime->format('F j, Y');
            $formattedTime = $dateTime->format('h:i A');
            $formattedDateTime = $formattedDate . ' at ' . $formattedTime;

            echo '
            <div class="orders-product-display">
                <div class="left-product-display">
                    <div class="order-product-img-container">
                        <img src="../product_img/' . $productImg . '">
                    </div>
                    <div class="order-product-details">
                        <span>' . $productName . '</span>
                        <span>Variation: ' . $variationName . '</span>
                        <span>Size: ' . $size . '</span>
                        <span>x' . $quantity . '</span>
                    </div>
                </div>
                <div class="right-product-display"><i class="fa-solid fa-peso-sign"></i>' . $price . '</div>
            </div>';
        }
    }

    echo '
        </div>
        <div class="order-item-total flex-col">
        <div class="upper-order-details">
            <div class="left-order-total">
                <p>Payment Status: ' . $paymentStatus . '</p>
                <p>Order Placed: ' . $formattedDateTime . '</p>
                <p>Mode of Payment: ' . $modeOfPayment . '</p>
            </div>
            <div class="right-order-total">
                <div class="upper-order-item-total">
                    Order Total: <span class="order-total"><i class="fa-solid fa-peso-sign"></i>' . $totalPrice . '</span>
                </div>
                <div class="lower-order-item-total">
                <form method="POST" class="payment-approval-form" id="payment-approval-form" action="../crud/handleOrderPayment.php">
                    <input type="hidden" name="orderID" value="' . $orderID . '">';
    
    if (empty($paymentImg)) {
        echo '<input class="rate-product-link cancel" type="submit" name="action" value="Reject" onclick="console.log(\'Reject button clicked\')">';
    }

    echo ' 
        </div>
        </div>
    </div>';

    if (!empty($paymentImg)) {
        echo ' 
        <div class="proof-of-payment">
            <a href="../payment_proof/' . $paymentImg .'" target="_blank"><img src="../payment_proof/' . $paymentImg . '"></a>
            <p>Click to view image</p>
            <div class="input-holder">
                <input form="payment-approval-form" class="rate-product-link approve h-100" type="submit" name="action" value="Accept" onclick="console.log(\'Accept button clicked\')">
                <input form="payment-approval-form" class="rate-product-link cancel h-100" type="submit" name="action" value="Reject" onclick="console.log(\'Reject button clicked\')">
            </div>
        </div>
        </form>';
    }

    echo '
        </div>
    </div>';

} else {
    echo '<p>No orders found.</p>';
}
?>