<?php
session_start();
require "../config/dbconn.php";

if (isset($_SESSION['token'])) {
    $token = $_SESSION['token'];
} else {

    $token = ''; 
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="../styles/index.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>

<body>

    <div class="verification-container">
        <div class="verification-header">
            <h1>Verification Page</h1>
        </div>
        <div class="otp-container">
            <h2>OTP Verification</h2>
            <p id="container-paragraph">Enter the 4 digits code that you have received on your NU email</p>
            <form action="../crud/otp_verify.php?token=<?php echo urlencode($token);?>" method="POST" id="otp-form">
    
                <div class="otp-card-inputs">
                    <input type="text" maxlength="1" name="otp[]" autofocus>
                    <input type="text" maxlength="1" name="otp[]">
                    <input type="text" maxlength="1" name="otp[]">
                    <input type="text" maxlength="1" name="otp[]">
                </div>
                <button type="submit" name="verify_otp_btn">Verify</button>
            </form>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const inputs = document.querySelectorAll('.otp-card-inputs input');
        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });
    });
    </script>
    <?php
    if (isset($_SESSION['alert'])) {
        echo $_SESSION['alert'];
        unset($_SESSION['alert']);
    }
    ?>
</body>

</html>