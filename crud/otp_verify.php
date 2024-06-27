<?php
session_start();
require '../config/dbconn.php';

if (isset($_POST['verify_otp_btn'])) {
    if (!empty($_POST['otp']) && is_array($_POST['otp'])) {
        $otp_array = $_POST['otp'];
        $otp = implode('', $otp_array);

        if (strlen($otp) !== 4 || !ctype_digit($otp)) {
            $_SESSION['alert'] = "
            <script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid OTP Format',
                    text: 'OTP must be a 4-digit number.',
                });
            </script>
            ";
            header("Location: ../pages/otp_verification.php");
            exit();
        }

        $verify_otp_query = "SELECT * FROM users WHERE otp='$otp' LIMIT 1";
        $verify_otp_query_run = mysqli_query($conn, $verify_otp_query);

        if ($verify_otp_query_run) {
            if (mysqli_num_rows($verify_otp_query_run) > 0) {
                $row = mysqli_fetch_array($verify_otp_query_run);
                $user_id = $row['userID']; // Get the user ID
                $verify_status = $row['verify_status']; 

                if ($verify_status == "0") {
                    $update_verify_status_query = "UPDATE users SET verify_status='1', otp=NULL WHERE userID='$user_id'";
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
                } else if ($verify_status == "1") {
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
                } else {
                    $_SESSION['alert'] = "
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Verification Failed',
                            text: 'An error occurred during verification. Please try again.',
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
                    title: 'Database Error',
                    text: 'There was an error processing your request. Please try again later.',
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