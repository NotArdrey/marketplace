<?php
require '../config/dbconn.php';

if (isset($_GET['productID'])) {
    $productID = $_GET['productID'];
    $sql = "SELECT productImg FROM products WHERE productID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $productID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['productImg' => $row['productImg']]);
    } else {
        echo json_encode(['error' => 'Product not found']);
    }
} else {
    echo json_encode(['error' => 'No productID provided']);
}

$conn->close();
?>