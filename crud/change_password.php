<?php
session_start();
require '../config/dbconn.php'; 

if (isset($_POST['confirm-change-password'])) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    function validate($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $password = validate($password);
    $confirm_password = validate($confirm_password);

    if ($password === $confirm_password) {
        
        if (isset($_GET['token_pass'])) {
            $token = $_GET['token_pass'];

            $sql = "SELECT * FROM users WHERE token_pass = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $token = $row['token_pass'];
                $current_password = $row['userPassword'];

                if ($password !== $current_password) {
                    $update_stmt = $conn->prepare("UPDATE users SET userPassword = ? WHERE token_pass = ?");
                    $update_stmt->bind_param("ss", $password, $token);
                    if ($update_stmt->execute()) {
                        $_SESSION['alert'] = "
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Password successfully changed!',
                                text: 'Your password has been updated successfully.',
                            });
                        </script>
                        ";
                        header("Location: ../pages/change_password.php");
                        exit();
                        
                    } else {
                        $_SESSION['alert'] = "
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error updating password',
                                text: 'There was an error updating your password. Please try again.',
                            });
                        </script>
                        ";
                        header("Location: ../pages/change_password.php");
                        exit();
                    }
                } else {
                    $_SESSION['alert'] = "
                    <script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'New password cannot be the same as the current password',
                            text: 'The passwords you entered do not match. Please try again.',
                        });
                    </script>
                    ";
                    header("Location: ../pages/change_password.php");
                    exit();
                }
            } else {
                $_SESSION['alert'] = "
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'No user found with this email',
                        text: 'No user found with the provided email. Please check your email or register.',
                    });
                </script>
                ";
                header("Location: ../pages/change_password.php");
                exit();
            }
        } else {
            $_SESSION['alert'] = "
            <script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Token pass not set in session',
                    text: 'Please ensure the token is properly set in the session.',

                });
            </script>
            ";
            header("Location: ../pages/change_password.php");
            exit();
        }
    } else {
        $_SESSION['alert'] = "
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Passwords do not match',
                text: 'Please make sure your passwords match.',
                
            });
        </script>
        ";
        header("Location: ../pages/change_password.php");
        exit();
    }
} else {
    $_SESSION['alert'] = "
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Confirm change password not set',
            text: 'Please confirm your change password request.',
        });
    </script>
    ";
    header("Location: ../pages/change_password.php");
    exit();
}

?>
