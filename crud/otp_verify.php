<?php
session_start();
require '../config/dbconn.php';

if (isset($_POST['verify_otp_btn'])) {
    if (!empty($_POST['otp'])) {
        $otp_array = $_POST['otp'];
        $otp = implode('', $otp_array);

        if (isset($_GET['token'])) {
            $token = $_GET['token'];

            $verify_otp_query = "SELECT * FROM users WHERE verify_token='$token' AND otp='$otp' LIMIT 1";
            $verify_otp_query_run = mysqli_query($conn, $verify_otp_query);

            if (mysqli_num_rows($verify_otp_query_run) > 0) {
                $row = mysqli_fetch_array($verify_otp_query_run);
                if ($row['verify_status'] == "0") {
                    $update_verify_status_query = "UPDATE users SET verify_status='1', otp=NULL WHERE verify_token='$token'";
                    mysqli_query($conn, $update_verify_status_query);

                    $_SESSION['alert'] = "
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Verification Successful',
                            text: 'Your account has been verified successfully.',
                        });
                    </script>
                    ";
                    header("Location: ../pages/index.php");
                    exit();
                } else {
                    $_SESSION['alert'] = "
                    <script>
                        Swal.fire({
                            icon: 'info',
                            title: 'Already Verified',
                            text: 'Your account is already verified.',
                        });
                    </script>
                    ";
                    header("Location: ../pages/index.php");
                    exit();
                }
            } else {
                $_SESSION['alert'] = "
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid OTP',
                        text: 'The OTP you entered is incorrect. Please try again.',
                    });
                </script>
                ";
                header("Location: ../pages/otp_verification.php");
                exit();
            }
        } else {
            $_SESSION['alert'] = "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Token Missing',
                    text: 'Verification token is missing. Please check your email and try again.',
                });
            </script>
            ";
            header("Location: ../pages/otp_verification.php");
            exit();
        }
    } else {
        $_SESSION['alert'] = "
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please enter the OTP.',
            });
        </script>
        ";
        header("Location: ../pages/otp_verification.php");
        exit();
    }
}
?>
