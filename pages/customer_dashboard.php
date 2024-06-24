<?php
session_start();
require '../config/dbconn.php';

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: ../pages/index.php");
    exit();
}

// Check if user is verified
$userID = $_SESSION['userID'];
$sql = "SELECT * FROM users WHERE userID = '$userID'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$vStatus = $row['verify_status'];
if ($vStatus === 0) {
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
                        <div class='cart-button' data-productid='$productID' data-productimg='$productImg'>
                            <i class='fa-solid fa-cart-shopping' style='color: #ffffff;'></i>
                        </div>
                        <div id='cartModal_$productID' class='modal' data-productid='$productID'>
                            <div class='modal-content'>
                                <span class='close' data-productid='$productID'>&times;</span>
                                <h2>Add to Cart</h2>
                                <div class='modal-details'>                                           
                                    <img src='../product_img/" . $productImg . "' class='modal-img'>
                                    <div class='modal-form'>
                                        <div class='upper-modal-form'>
                                            <div class='modal-row'>
                                                <label>Variation / Flavor</label>
                                                <select name='variation' id='variationSelect_$productID' class='modalInput'>
                                                </select>
                                            </div>
                                            <div class='modal-row'>
                                                <label>Size</label>
                                                <select name='size' id='sizeSelect_$productID' onchange='displayPrice($productID)' class='modalInput'>
                                                </select>
                                            </div>
                                            <div class='modal-row'>                                                                
                                                <label>Quantity</label>
                                                <input type='number' class='modalInputQty' id='qty_$productID'>
                                            </div>
                                        </div>
                                        <div class='bottom-modal-form'>
                                            <div class='modal-add-to-cart-btn'>
                                                <a href='' id='addToCartLink_$productID'>Add to Cart</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                        <div class='cart-button' data-productid='$productID' data-productimg='$productImg'>
                            <i class='fa-solid fa-cart-shopping' style='color: #ffffff;'></i>
                        </div>
                        <div id='cartModal_$productID' class='modal' data-productid='$productID'>
                            <div class='modal-content'>
                                <span class='close' data-productid='$productID'>&times;</span>
                                <h2>Add to Cart</h2>
                                <div class='modal-details'>                                           
                                    <img src='../product_img/" . $productImg . "' class='modal-img'>
                                    <div class='modal-form'>
                                        <div class='upper-modal-form'>
                                            <div class='modal-row'>
                                                <label>Variation / Flavor</label>
                                                <select name='variation' id='variationSelect_$productID' class='modalInput'>
                                                </select>
                                            </div>
                                            <div class='modal-row'>
                                                <label>Size</label>
                                                <select name='size' id='sizeSelect_$productID' onchange='displayPrice($productID)' class='modalInput'>
                                                </select>
                                            </div>
                                            <div class='modal-row'>
                                                <label>Quantity</label>
                                                <input type='number' class='modalInputQty' id='qty_$productID'>
                                            </div>
                                        </div>
                                        <div class='bottom-modal-form'>
                                            <div class='modal-add-to-cart-btn'>
                                                <a href='' id='addToCartLink_$productID'>Add to Cart</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    <title>Dashboard</title>
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
    <?php include_once '../components/navbar.php'; ?>

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
                                    <div class='cart-button' data-productid='$productID' data-productimg='$productImg'>
                                        <i class='fa-solid fa-cart-shopping' style='color: #ffffff;'></i>
                                    </div>
                                    <div id='cartModal_$productID' class='modal' data-productid='$productID'>
                                        <div class='modal-content'>
                                            <span class='close' data-productid='$productID'>&times;</span>
                                            <h2>Add to Cart</h2>
                                            <div class='modal-details'>                                           
                                                <img src='../product_img/" . $productImg . "' class='modal-img'>
                                                <div class='modal-form'>
                                                    <div class='upper-modal-form'>
                                                        <div class='modal-row'>
                                                            <label>Variation / Flavor</label>
                                                            <select name='variation' id='variationSelect_$productID' class='modalInput'>
                                                            </select>
                                                        </div>
                                                        <div class='modal-row'>
                                                            <label>Size</label>
                                                            <select name='size' id='sizeSelect_$productID' onchange='displayPrice($productID)' class='modalInput'>
                                                            </select>
                                                        </div>
                                                        <div class='modal-row'>                                                                
                                                            <label>Quantity</label>
                                                            <input type='number' class='modalInputQty' id='qty_$productID'>
                                                        </div>
                                                    </div>
                                                    <div class='bottom-modal-form'>
                                                        <div class='modal-add-to-cart-btn'>
                                                            <a href='' id='addToCartLink_$productID'>Add to Cart</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
    function searchProducts() {
        var input = document.getElementById('search-product-input').value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (this.status === 200) {
                document.getElementById('product-display').innerHTML = this.responseText;
                initializeCartButtons(); // Reinitialize the cart buttons after updating the content
            }
        };
        xhr.send('search=' + input);
    }

    document.addEventListener("DOMContentLoaded", function() {
        initializeCartButtons();
    });

    function initializeCartButtons() {
        var buttons = document.querySelectorAll(".cart-button");

        buttons.forEach(function(btn) {
            btn.addEventListener("click", function() {
                var productID = btn.getAttribute("data-productid");
                var productImg = btn.getAttribute("data-productimg");
                openModal(productID, productImg);
            });
        });

        var closeButtons = document.querySelectorAll(".close");

        closeButtons.forEach(function(closeBtn) {
            closeBtn.addEventListener("click", function() {
                var productID = closeBtn.getAttribute("data-productid");
                closeModal(productID);
            });
        });

        window.addEventListener("click", function(event) {
            if (event.target.classList.contains("modal")) {
                var productID = event.target.getAttribute("data-productid");
                closeModal(productID);
            }
        });
    }

    function openModal(productID, productImg) {
        var modal = document.getElementById("cartModal_" + productID);
        if (modal) {
            var modalImg = modal.querySelector('.modal-img');
            if (modalImg) {
                modalImg.src = '../product_img/' + productImg;
            } else {
                console.error('Modal image element not found');
            }
            modal.style.display = "flex";
            initializeModal(productID);
        } else {
            console.error('Modal not found for productID:', productID);
        }
    }

    function closeModal(productID) {
        var modal = document.getElementById("cartModal_" + productID);
        if (modal) {
            modal.style.display = "none";
        }
    }

    function initializeModal(productID) {
        var variationSelect = document.getElementById('variationSelect_' + productID);
        var sizeSelect = document.getElementById('sizeSelect_' + productID);
        var price = document.getElementById('productPrice_' + productID);
        var qty = document.getElementById('qty_' + productID);
        var addToCartLink = document.getElementById('addToCartLink_' + productID);

        if (!variationSelect || !sizeSelect || !addToCartLink || !qty) {
            console.error('One or more elements are not found in the DOM.');
            return;
        }

        function fetchVariations(productID) {
            fetch('../crud/getVariations.php?productID=' + productID)
                .then(response => response.json())
                .then(data => {
                    variationSelect.innerHTML = ''; // Clear existing options
                    data.forEach((variation, index) => {
                        var option = document.createElement('option');
                        option.value = variation;
                        option.text = variation;
                        if (index === 0) {
                            option.selected = true;
                        }
                        variationSelect.appendChild(option);
                    });
                    populateSizes(variationSelect.value, productID);
                })
                .catch(error => console.error('Error fetching variations:', error));
        }

        function populateSizes(variationName, productID) {
            let url = '../crud/getSizes.php';
            if (!variationName) {
                url += '?productID=' + productID;
            } else {
                url += '?variationName=' + encodeURIComponent(variationName) + '&productID=' + productID;
            }
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    sizeSelect.innerHTML = ''; // Clear existing options
                    data.forEach((size, index) => {
                        var option = document.createElement('option');
                        option.value = size;
                        option.text = size;
                        if (index === 0) {
                            option.selected = true;
                        }
                        sizeSelect.appendChild(option);
                    });
                    displayPrice(productID); // Call displayPrice after sizes are populated
                    updateAddToCartUrl(productID);
                })
                .catch(error => console.error('Error fetching sizes:', error));
        }

        function displayPrice(productID) {
            let variation = variationSelect.value;
            let size = sizeSelect.value;

            fetch('../crud/displayPrice.php?variation=' + encodeURIComponent(variation) + '&size=' + encodeURIComponent(
                    size) + '&productID=' + productID)
                .then(response => response.text())
                .then(data => {
                    price.textContent = data.replace(/"/g, '');
                })
                .catch(error => console.error('Error fetching price:', error));
        }

        function updateAddToCartUrl(productID) {
            let addToCartUrl = "../crud/add_to_cart.php?";
            addToCartUrl += "productID=" + productID + "&variation=" + encodeURIComponent(variationSelect.value) +
                "&size=" + encodeURIComponent(sizeSelect.value) + "&qty=" + encodeURIComponent(qty.value);
            addToCartLink.href = addToCartUrl;
            console.log('Add to Cart URL set to:', addToCartLink.href);
        }

        variationSelect.addEventListener('change', function() {
            populateSizes(this.value, productID);
        });

        sizeSelect.addEventListener('change', function() {
            displayPrice(productID);
            updateAddToCartUrl(productID);
        });

        qty.addEventListener('change', function() {
            if (qty.value == 0) {
                Swal.fire({
                    title: "Invalid Quantity!",
                    text: "You must order at least 1 item!",
                    icon: "error"
                });
                qty.value = 1;
            } else {
                updateAddToCartUrl(productID);
            }
        });

        fetchVariations(productID);
    }
    </script>

</body>

</html>

<?php
if (isset($_SESSION['alert'])) {
    echo $_SESSION['alert'];
    unset($_SESSION['alert']);
}
?>