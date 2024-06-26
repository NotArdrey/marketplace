<?php
session_start();
require '../config/dbconn.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


function resend_email_verify($first_name, $email, $verify_token){
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'neilardrey14@gmail.com';
        $mail->Password   = 'nwkp lnkd qxja msid'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('neilardrey14@gmail.com', 'Sender');
        $mail->addAddress($email, $first_name);

        $mail->isHTML(true);
        $mail->Subject = 'Resend Email Verification';
        $mail->Body = "Hi $first_name,<br>Please click the link below to confirm your email address and activate your account:<br><a href='http://localhost/marketplace/crud/tokenVerification.php?token=". urlencode($verify_token) ."'>Verify Email</a>";
        $mail->send();
        $_SESSION['alert'] = "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Message has been sent.',
            });
        </script>
        ";
        header("Location: ../pages/resend_verification.php"); 
        exit();
    } catch (Exception $e) {
        $_SESSION['alert'] = "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Message could not be sent. Mailer Error: {$mail->ErrorInfo}',
            });
        </script>
        ";
        header("Location: ../pages/resend_verification.php"); 
        exit();
    }

}
if (isset($_POST['resend_btn'])) {
    if (!empty(trim($_POST['email']))) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }    
        function isValidOutlookEmail($email) {
            $pattern = '/^[a-zA-Z._%+-]+@([a-zA-Z0-9-]+\.)?nu-baliwag\.edu\.ph$/';
            return preg_match($pattern, $email);
        }
    
        if (empty($email) || !isValidOutlookEmail($email)) {
            $_SESSION['alert'] = "
            <script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Valid Outlook email is required.',
                });
            </script>
            ";
            header("Location: ../pages/resend_verification.php");
            exit(0);
        }

        $checkemail_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $checkemail_query_run = mysqli_query($conn, $checkemail_query);

        
        if (mysqli_num_rows($checkemail_query_run) > 0) {
            $row = mysqli_fetch_array($checkemail_query_run);
            if ($row['verify_status'] == "0") {
                $first_name = $row["first_name"];
                $verify_token = $row["verify_token"];
                $email = $row["email"];
   
                resend_email_verify($first_name, $email, $verify_token);
                $_SESSION['alert'] = "
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Verification Email Sent',
                        text: 'Verification has been sent to your email.',
                    });
                </script>
                ";
                header("Location: ../pages/resend_verification.php");
                exit(0);
            } else {
                $_SESSION['alert'] = "
                <script>
                    Swal.fire({
                        icon: 'info',
                        title: 'Already Verified',
                        text: 'Email already verified. Please login.',
                    });
                </script>
                ";
            }
                header("Location: ../pages/resend_verification.php");
                exit(0);
            }
        } else {
            $_SESSION['alert'] = "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Email Not Found',
                    text: 'Email not found. Please register your email now!',
                });
            </script>
            ";
            header("Location: ../pages/resend_verification.php");
            exit(0);
        }
    } else {
        $_SESSION['alert'] = "
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please enter the email field.',
            });
        </script>
        ";
    }

    header("Location: ../pages/resend_verification.php");
    exit(0);

?>