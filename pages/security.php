<?php
session_start();
require '../config/dbconn.php';

if (!isset($_SESSION['userID'])) {
    header("Location: ../pages/index.php");
} else {
    $userID = $_SESSION['userID'];
    $sql = "SELECT * FROM users WHERE userID = '$userID'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $vStatus = $row['verify_status'];
    if ($vStatus === 0) {
        header("Location: ../pages/index.php");
    }
}  
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Products</title>
    <link rel="stylesheet" href="../styles/index.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/validation.js"></script>

</head>

<body>
    <div class="settings-container">
        <?php  
        include_once '../components/sidebar.php'; 
        $sql = "SELECT * FROM users WHERE userID = '$userID'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        ?>

        <div class="Password-container">
            <div class="Header">
                <h1>Change Password</h1>
            </div>
        </div>

        <div class="text-container">
            <div class="information-text">
                <p>Your password must be at least 6 characters and should include a combination of numbers, letters and
                    special characters (!$@%).</p>
            </div>

            <form action="../crud/settings_change_password.php" method="POST" id="registration-form" name="password-form">
                <div class="row-container">

                    <div class="rows">
                        Current Password
                        <div>
                            <input type="password" placeholder="Enter Current Password" name="current-password"
                                class="security-input-box" id = "current-password">
                                <div class="error"></div>
                        </div>
                    </div>
                    <div class="rows">
                        New Password
                        <div>
                            <input type="password" placeholder="Enter New Password" name="new-password"
                                class="security-input-box" id = "new-password">
                                <div class="error"></div>
                        </div>
                    </div>
                    <div class="rows">
                        Re-type New Password
                        <div class="check-box">
                            <input type="password" placeholder="Re-type New Password" name="re-enter-password"
                                class="security-input-box" id = "re-enter-password">
                                <div class="error"></div>

                            Show Password
                            <input type="checkbox" name="checkbox" class="checkbox" onclick="myFunction()" id="box">

                        </div>
                    </div class="security-submit">
                    <input type="submit" class="security-save" name="security-save" value="Save"
                        onclick="validatePasswords()">

            </form>

            <script>
            function myFunction() {
                var passwordInputs = document.querySelectorAll('.security-input-box');
                passwordInputs.forEach(function(input) {
                    if (input.type === "password") {
                        input.type = "text";
                    } else {
                        input.type = "password";
                    }
                });
            }
            </script>


        </div>
    </div>


    </div>


    <?php
    if (isset($_SESSION['alert'])) {
        echo $_SESSION['alert'];
        unset($_SESSION['alert']);
    }
    ?>
</body>

</html>