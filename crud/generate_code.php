<?php
session_start();
require '../config/dbconn.php';
require '../vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if (isset($_GET['action']) && $_GET['action'] == 'resend' && isset($_SESSION['user_email'])) {
    $email = $_SESSION['user_email'];

   
    $sql = "SELECT first_name, verify_token, verified FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        
        if ($user['verified'] == 0) {
            sendemail_verify($user['first_name'], $email, $user['verify_token']);
            $_SESSION['alert'] = "Verification email has been resent. Please check your inbox.";
        } else {
            $_SESSION['alert'] = "Your email is already verified.";
            $_SESSION['needs_verification'] = false;
        }
    } else {
        $_SESSION['alert'] = "Error: Could not resend verification email.";
    }

    header("");  
}

function sendemail_verify($first_name, $email, $verify_token) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'neilardrey14@gmail.com';
        $mail->Password   = 'heoh kept fxrg wrhs'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('neilardrey14@gmail.com', 'Sender');
        $mail->addAddress($email, $first_name);

        $mail->isHTML(true);
        $mail->Subject = 'Test Mail';
        $mail->Body = "Hi $first_name,<br>Please click the link below to confirm your email address and activate your account:<br><a href='http://localhost/Project/marketplace/crud/tokenVerification.php?token=". urlencode($verify_token) ."'>Verify Email</a>";
        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if (isset($_POST["register_btn"])) {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $contact_number = $_POST["contact_number"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $verify_token = md5(uniqid(time()));

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['alert'] = "Passwords do not match";
        header("Location: ../pages/registration.php");
        exit(0);
    }

    // Check database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the email or contact number already exists
    $sql = "SELECT * FROM users WHERE email = ? OR contact_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $contact_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['alert'] = "Email or Contact Number already registered";
        header("Location: ../pages/registration.php");
        exit(0);
    } else {
        // Insert new user
        $sql = "INSERT INTO users (first_name, last_name, email, contact_number, userPassword, verify_token) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $first_name, $last_name, $email, $contact_number, $password, $verify_token);

        if ($stmt->execute()) {
            // Send verification email
            sendemail_verify($first_name, $email, $verify_token);
            $_SESSION['alert'] = "Registration successful! Please verify your email.";
            $_SESSION['user_email'] = $email;  
            $_SESSION['needs_verification'] = true;
            header("Location: ../pages/registration.php");
            exit(0);
        } else {
            $_SESSION['alert'] = "Registration failed! Please try again.";
            header("Location: ../pages/registration.php");
            exit(0);
        }
    }
}
?>
