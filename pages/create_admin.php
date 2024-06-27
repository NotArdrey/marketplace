<?php
session_start();
require '../config/dbconn.php';

// Check if user is logged in
// if (!isset($_SESSION['userID'])) {
//     header("Location: ../pages/index.php");
//     exit();
// }

// // Check if user is verified
// $userID = $_SESSION['userID'];
// $sql = "SELECT * FROM users WHERE userID = '$userID'";
// $result = mysqli_query($conn, $sql);
// $row = mysqli_fetch_assoc($result);
// $vStatus = $row['verify_status'];
// $initialQty = 1;
// if ($vStatus === 0) {
//     header("Location: ../pages/index.php");
//     exit();
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

    <div class="content" id="create-admin-page">
        <h1>Create Admin</h1>
        <form action="../crud/createAdmin.php" method="POST" class="create-admin-form">
            <div class="create-admin-div">
                <div class="upper-admin-form">
                    <div class="admin-form-row">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" required>
                    </div>
                    <div class="admin-form-row">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" required>
                    </div>
                    <div class="admin-form-row">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="admin-form-row">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                </div>
                <input type="submit" class="form-next-button" id="create-admin-btn" value="Create Admin"
                    style="color: white;">
            </div>
        </form>
    </div>

    <script>

    </script>

</body>

</html>

<?php
if (isset($_SESSION['alert'])) {
    echo $_SESSION['alert'];
    unset($_SESSION['alert']);
}
?>