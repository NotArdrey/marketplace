<?php
session_start();
require "../config/dbconn.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/index.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>

<body>
    <div class="login-left">
        <div class="upper-login">
            <h2>NUB Marketplace</h2>
        </div>
        <div class="lower-login">
            <h1 class="login-h1">Login To Your Account</h1>
            <form action="../crud/login.php" method="POST" id="login-form">
                <input type="text" placeholder="NU Email" class="input-box" name="email" required>
                <input type="password" placeholder="Password" class="input-box" name="password" required>
                <div>
                    <div class="pass-checkbox-container">
                        <div class="left-pass">
                            <a href="../pages/send_code_password.php" class="forget-password-login">Forget Password?</a>
                        </div>
                        <div class="right-checkbox">
                            <label label class="show-pass-index-label">Show Password</label>
                            <input type="checkbox" name="checkbox" class="checkbox-index" onclick="myFunction()"
                                id="box">
                        </div>
                    </div>
                    <input type="submit" value="Login" name="login_btn" class="login-button">
                </div>

            </form>
        </div>
    </div>
    <div class="login-right">
        <div class="triangle"></div>
        <div class="small-triangle"></div>
        <div class="login-right-container">
            <h1>New Here?</h1>
            <p>Sign up and discover a great amount of new items!</p>
            <button onclick="location.href='../pages/registration.php'">Sign Up</button>
        </div>
    </div>

    <script>
    function myFunction() {
        var passwordInputs = document.getElementsByName('password');
        Array.from(passwordInputs).forEach(function(input) {
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        });
    }
    </script>

    <?php
            if(isset($_SESSION['alert'])) {
            echo $_SESSION['alert'];
            unset($_SESSION['alert']);
            }
        ?>
</body>

</html>