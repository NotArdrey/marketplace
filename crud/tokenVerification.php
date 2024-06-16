<?php
session_start();
require '../config/dbconn.php';


if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT verify_status FROM users WHERE verify_token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
 
        if ($row["verify_status"] === 0) {  
            $update_stmt = $conn->prepare("UPDATE users SET verify_status = 1 WHERE verify_token = ? LIMIT 1");
            $update_stmt->bind_param("s", $token);
            $update_stmt->execute();

            if ($update_stmt->affected_rows > 0) {
                $_SESSION['alert'] = "
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Account Verified',
                        text: 'Account has been verified successfully. You can now login.',
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
                        text: 'Verification failed.',
                    });
                </script>
                ";
                header("Location: ../pages/index.php");
                exit();
;
            }
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
            header("Location: ../pages/index.php");
            exit();

        }
    } else {
        $_SESSION['alert'] = "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Token Error',
                text: 'Token does not exist.',
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
            icon: 'warning',
            title: 'Not Allowed',
            text: 'Not allowed.',
        });
    </script>
    ";
    header("Location: ../pages/index.php");
    exit();
}

?>
