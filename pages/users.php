<?php
session_start();
require '../config/dbconn.php';
// if (!isset($_SESSION['userID'])) {
//     header("Location: ../pages/index.php");
// } else {
//     $userID = $_SESSION['userID'];
//     $sql = "SELECT * FROM users WHERE userID = '$userID'";
//     $result = mysqli_query($conn, $sql);
//     $row = mysqli_fetch_assoc($result);
//     $vStatus = $row['verify_status'];
//     if ($vStatus == 0) {
//         header("Location: ../pages/index.php");
//     } 
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../styles/index.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    *::-webkit-scrollbar {
        display: none;
    }
    </style>
</head>

<body>
    <?php
        include_once '../components/admin_navbar.php';
        $sql = "SELECT * FROM users";
        $result = mysqli_query($conn, $sql);
        $totalOrders = mysqli_num_rows($result);
        $sql = "SELECT * FROM users WHERE user_type = 'admin'";
        $result = mysqli_query($conn, $sql);
        $totalPendingOrders = mysqli_num_rows($result);
        $sql = "SELECT * FROM users WHERE user_type = 'user'";
        $result = mysqli_query($conn, $sql);
        $totalToPayOrders = mysqli_num_rows($result);
        
    ?>
    <div class="content" id="my-orders-page">
        <div class="my-orders-navbar" id="users-navbar">
            <div class="my-orders-nav-link" id="allMyOrdersBtn">
                <div class="my-orders-nav-item">All
                    <span id="allCount">
                        <?php 
                        if ($totalOrders > 0) {
                            echo "(" . $totalOrders . ")";
                        }
                        ?>
                    </span>
                </div>
            </div>
            <div class="my-orders-nav-link" id="myPendingOrdersBtn">
                <div class="my-orders-nav-item">Admin
                    <span id="pendingCount">
                        <?php 
                        if ($totalPendingOrders > 0) {
                            echo "(" . $totalPendingOrders . ")";
                        }
                        ?>
                    </span>
                </div>
            </div>
            <div class="my-orders-nav-link" id="myToPayOrdersBtn">
                <div class="my-orders-nav-item">User
                    <span id="toPayCount">
                        <?php 
                        if ($totalToPayOrders > 0) {
                            echo "(" . $totalToPayOrders . ")";
                        }
                        ?>
                    </span>
                </div>
            </div>
        </div>
        <div id="orders-display">
            <?php
                include_once "../crud/allUsers.php"; 
            ?>
        </div>
    </div>
    <script>
    const allMyOrdersBtn = document.getElementById("allMyOrdersBtn");
    const myPendingOrdersBtn = document.getElementById("myPendingOrdersBtn");
    const myToPayOrdersBtn = document.getElementById("myToPayOrdersBtn");
    const myToReceiveOrdersBtn = document.getElementById("myToReceiveOrdersBtn");
    const myCompletedOrdersBtn = document.getElementById("myCompletedOrdersBtn");
    const navbarLink1 = document.getElementById('allMyOrdersBtn');
    const navbarLink2 = document.getElementById('myPendingOrdersBtn');
    const navbarLink3 = document.getElementById('myToPayOrdersBtn');
    const navbarLink4 = document.getElementById('myToReceiveOrdersBtn');

    document.addEventListener("DOMContentLoaded", function() {
        navbarLink1.style.borderBottom = "5px solid #3182CE";
        navbarLink1.style.color = "#3182CE";
    });

    navbarLink1.addEventListener("click", function() {
        navbarLink1.style.borderBottom = "5px solid #3182CE";
        navbarLink1.style.color = "#3182CE";
        navbarLink2.style.borderBottom = "none";
        navbarLink2.style.color = "black";
        navbarLink3.style.borderBottom = "none";
        navbarLink3.style.color = "black";
    });

    navbarLink2.addEventListener("click", function() {
        navbarLink2.style.borderBottom = "5px solid #3182CE";
        navbarLink2.style.color = "#3182CE";
        navbarLink1.style.borderBottom = "none";
        navbarLink1.style.color = "black";
        navbarLink3.style.borderBottom = "none";
        navbarLink3.style.color = "black";
    });

    navbarLink3.addEventListener("click", function() {
        navbarLink3.style.borderBottom = "5px solid #3182CE";
        navbarLink3.style.color = "#3182CE";
        navbarLink1.style.borderBottom = "none";
        navbarLink1.style.color = "black";
        navbarLink2.style.borderBottom = "none";
        navbarLink2.style.color = "black";
    });

    function initializeDeleteButtons() {
        const deleteIcons = document.querySelectorAll(".fa-trash");

        deleteIcons.forEach(icon => {
            icon.addEventListener("click", function() {
                const userID = this.getAttribute("data-user-id");

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
                        window.location.href = `../crud/deleteUser.php?userID=${userID}`;
                    }
                });
            });
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        initializeDeleteButtons();

        const allMyOrdersBtn = document.getElementById("allMyOrdersBtn");
        const myPendingOrdersBtn = document.getElementById("myPendingOrdersBtn");
        const myToPayOrdersBtn = document.getElementById("myToPayOrdersBtn");

        allMyOrdersBtn.addEventListener("click", function() {
            fetch('../crud/allUsers.php')
                .then(response => response.text())
                .then(data => {
                    const myOrdersPage = document.getElementById("my-orders-page");
                    const preserveElement = document.getElementById("users-navbar");

                    if (preserveElement) {
                        myOrdersPage.removeChild(preserveElement);
                    }

                    myOrdersPage.innerHTML = '';

                    if (preserveElement) {
                        myOrdersPage.appendChild(preserveElement);
                    }

                    myOrdersPage.insertAdjacentHTML('beforeend', data);

                    initializeDeleteButtons();
                })
                .catch(error => console.error('There was a problem with the fetch operation:', error));
        });

        myPendingOrdersBtn.addEventListener("click", function() {
            fetch('../crud/adminUsers.php')
                .then(response => response.text())
                .then(data => {
                    const myOrdersPage = document.getElementById("my-orders-page");
                    const preserveElement = document.getElementById("users-navbar");

                    if (preserveElement) {
                        myOrdersPage.removeChild(preserveElement);
                    }

                    myOrdersPage.innerHTML = '';

                    if (preserveElement) {
                        myOrdersPage.appendChild(preserveElement);
                    }

                    myOrdersPage.insertAdjacentHTML('beforeend', data);

                    initializeDeleteButtons();
                })
                .catch(error => console.error('There was a problem with the fetch operation:', error));
        });

        myToPayOrdersBtn.addEventListener("click", function() {
            fetch('../crud/onlyUsers.php')
                .then(response => response.text())
                .then(data => {
                    const myOrdersPage = document.getElementById("my-orders-page");
                    const preserveElement = document.getElementById("users-navbar");

                    if (preserveElement) {
                        myOrdersPage.removeChild(preserveElement);
                    }

                    myOrdersPage.innerHTML = '';

                    if (preserveElement) {
                        myOrdersPage.appendChild(preserveElement);
                    }

                    myOrdersPage.insertAdjacentHTML('beforeend', data);

                    initializeDeleteButtons();
                })
                .catch(error => console.error('There was a problem with the fetch operation:', error));
        });
    });
    </script>
</body>

</html>

<?php
    if(isset($_SESSION['alert'])) {
        echo $_SESSION['alert'];
        unset($_SESSION['alert']);
    }
?>