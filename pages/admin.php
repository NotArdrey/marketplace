<?php
session_start();
require '../config/dbconn.php';

//Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: ../pages/index.php");
    exit();
}

// Check if user is verified
$userID = $_SESSION['userID'];
$sql = "SELECT * FROM users WHERE userID = '$userID'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$userRole = $row['user_type'];

if ($userRole != 'admin') {
    header("Location: ../pages/index.php");
    exit();
}

// Function to fetch products based on search query or category
function fetchProducts($conn, $searchQuery = '', $category = '') {
    $sql = "SELECT * FROM products";
    $conditions = [];
    if (!empty($searchQuery)) {
        $conditions[] = "(productName LIKE '%$searchQuery%' OR productDesc LIKE '%$searchQuery%')";
    }
    if (!empty($category)) {
        $conditions[] = "category = '$category'";
    }
    if (count($conditions) > 0) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    $result = mysqli_query($conn, $sql);
    $products = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }
    return $products;
}

// Check if the request is an AJAX call for searching products
if (isset($_POST['search'])) {
    $searchQuery = $_POST['search'];
    $products = fetchProducts($conn, $searchQuery);
    if (!empty($products)) {
        foreach ($products as $row) {
            $productID = $row['productID'];
            $productName = $row['productName'];
            $productDesc = $row['productDesc'];
            $productPrice = $row['productPrice'];
            $productImg = $row['productImg'];
            echo "<div class='item'>
                <div class='item-upper'>
                    <img src='../product_img/$productImg' class='product-img' draggable='false'>
                </div>
                <div class='item-lower'>
                    <div class='product-name'>$productName</div>
                    <div class='product-desc'>$productDesc</div>
                    <div class='price-rating'>
                        <div class='price'>₱$productPrice</div>
                        <div class='rating'>
                            <div class='stars'>
                                <i class='fa-solid fa-star' style='color: #FFD43B;'></i>
                                <i class='fa-solid fa-star' style='color: #FFD43B;'></i>
                                <i class='fa-solid fa-star' style='color: #FFD43B;'></i>
                                <i class='fa-solid fa-star' style='color: #FFD43B;'></i>
                                <i class='fa-solid fa-star' style='color: #FFD43B;'></i>
                            </div>
                            <div class='numeric-rating'>4.9</div>
                        </div>
                    </div>
                    <div class='details-cart'>
                        <a href='../pages/product_details.php?productID=$productID'>
                            <div class='details-button'>More details</div>
                        </a>
                        <div class='cart-button delete-product-btn' data-productid='$productID'>
                            <i class='fa-solid fa-trash' style='color: #ffffff;'></i>
                        </div>
                    </div>
                </div>
            </div>";
        }
    } else {
        echo "<p class='no-item-found'>No products found.</p>";
    }
    exit();
}

// Check if the request is an AJAX call for filtering products by category
if (isset($_POST['category'])) {
    $category = $_POST['category'];
    $products = fetchProducts($conn, '', $category);
    if (!empty($products)) {
        foreach ($products as $row) {
            $productID = $row['productID'];
            $productName = $row['productName'];
            $productDesc = $row['productDesc'];
            $productPrice = $row['productPrice'];
            $productImg = $row['productImg'];
            echo "<div class='item'>
                <div class='item-upper'>
                    <img src='../product_img/$productImg' class='product-img' draggable='false'>
                </div>
                <div class='item-lower'>
                    <div class='product-name'>$productName</div>
                    <div class='product-desc'>$productDesc</div>
                    <div class='price-rating'>
                        <div class='price'>₱$productPrice</div>
                        <div class='rating'>
                            <div class='stars'>
                                <i class='fa-solid fa-star' style='color: #FFD43B;'></i>
                                <i class='fa-solid fa-star' style='color: #FFD43B;'></i>
                                <i class='fa-solid fa-star' style='color: #FFD43B;'></i>
                                <i class='fa-solid fa-star' style='color: #FFD43B;'></i>
                                <i class='fa-solid fa-star' style='color: #FFD43B;'></i>
                            </div>
                            <div class='numeric-rating'>4.9</div>
                        </div>
                    </div>
                    <div class='details-cart'>
                        <a href='../pages/product_details.php?productID=$productID'>
                            <div class='details-button'>More details</div>
                        </a>
                        <div class='cart-button delete-product-btn' data-productid='$productID'>
                            <i class='fa-solid fa-cart-shopping' style='color: #ffffff;'></i>
                        </div>
                    </div>
                </div>
            </div>";
        }
    } else {
        echo "<p>No products found.</p>";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../styles/index.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');

    *::-webkit-scrollbar {
        display: none;
    }
    </style>
</head>

<body>
    <?php include_once '../components/admin_navbar.php'; ?>

    <div class="content">
        <div class="searchbar">
            <div class="searchbar-text">
                <input type="text" placeholder="Search..." id="search-product-input" onkeyup="searchProducts()">
            </div>
            <div class="searchbar-button" id="search-product-button">
                <div><i class="fa-solid fa-magnifying-glass"></i></div>
            </div>
        </div>
        <div class="products-div">
            <div class="product-display" id="product-display">
                <?php
            $products = fetchProducts($conn);
            if (!empty($products)) {
                foreach ($products as $row) {
                    $productID = $row['productID'];
                    $productName = $row['productName'];
                    $productDesc = $row['productDesc'];
                    $productPrice = $row['productPrice'];
                    $productImg = $row['productImg'];
                    echo "<div class='item'>
                        <div class='item-upper'>
                            <img src='../product_img/$productImg' class='product-img' draggable='false'>
                        </div>
                        <div class='item-lower'>
                            <div class='product-name'>$productName</div>
                            <div class='product-desc'>$productDesc</div>
                            <div class='price-rating'>
                                <div class='price'>₱$productPrice</div>
                                <div class='rating'>
                                    <div class='stars'>
                                        <i class='fa-solid fa-star' style='color: #FFD43B;'></i>
                                        <i class='fa-solid fa-star' style='color: #FFD43B;'></i>
                                        <i class='fa-solid fa-star' style='color: #FFD43B;'></i>
                                        <i class='fa-solid fa-star' style='color: #FFD43B;'></i>
                                        <i class='fa-solid fa-star' style='color: #FFD43B;'></i>
                                    </div>
                                    <div class='numeric-rating'>4.9</div>
                                </div>
                            </div>
                            <div class='details-cart'>
                                    <a href='../pages/product_details.php?productID=$productID'>
                                        <div class='details-button'>More details</div>
                                    </a>
                                    <div class='cart-button delete-product-btn' data-productid='$productID'>
                                        <i class='fa-solid fa-trash delete-product-btn' style='color: #ffffff;'></i>
                                    </div>
                                </div>
                        </div>
                    </div>";
                }
            } else {
                echo "No products found";
            }
            ?>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Function to handle the click event
        function handleDeleteClick(event) {
            // Check if the clicked element has the class 'delete-product-btn'
            if (event.target.classList.contains('delete-product-btn')) {
                const productID = event.target.getAttribute('data-productid');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href =
                            `../crud/delete_product.php?productID=${productID}&userRole=admin`;
                    }
                });
            }
        }

        // Add event listener to product-display using event delegation
        document.getElementById('product-display').addEventListener('click', handleDeleteClick);

        // Function to handle the AJAX search
        function searchProducts() {
            var input = document.getElementById('search-product-input').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('product-display').innerHTML = this.responseText;
                }
                // After updating content, reattach event listener
                document.getElementById('product-display').addEventListener('click', handleDeleteClick);
            };
            xhr.send('search=' + input);
        }

        // Call searchProducts() when the search input changes
        document.getElementById('search-product-input').addEventListener('keyup', searchProducts);
    });
    </script>

</body>

</html>

<?php
if (isset($_SESSION['alert'])) {
    echo $_SESSION['alert'];
    unset($_SESSION['alert']);
}
?>