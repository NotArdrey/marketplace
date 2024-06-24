<?php
session_start();
require '../config/dbconn.php';

if (isset($_POST['security-save'])) {
    $current_password = $_POST['current-password'];
    $new_password = $_POST['new-password'];
    $re_enter_password = $_POST['re-enter-password'];

    $userID = $_SESSION['userID'];

    $sql = "SELECT * FROM users WHERE userID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $hashed_password = $row['userPassword'];

        if (password_verify($current_password, $hashed_password)) {

            $pattern = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&_])[A-Za-z\d@$!%*?&_]{6,}$/';

            if ($new_password !== $re_enter_password) {
                $_SESSION['alert'] = "
                <script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: 'Passwords do not match.',
                    });
                </script>
                ";
                header("Location: ../pages/security.php");
                exit(0);
            } elseif (!preg_match($pattern, $new_password)) {
                $_SESSION['alert'] = "
                <script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: 'Your password must be at least 6 characters and should include a combination of numbers, letters, and special characters (!$@%).',
                    });
                </script>
                ";
                header("Location: ../pages/security.php");
                exit(0);
            }

            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET userPassword = ? WHERE userID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $new_hashed_password, $userID);

            if ($stmt->execute()) {
                $_SESSION['alert'] = "
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Password Changed Successfully!',
                        text: 'You can now use your new password.',
                    });
                </script>
                ";
                header("Location: ../pages/security.php");
                exit(0);
            } else {
                $_SESSION['alert'] = "
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Password change failed!',
                        text: 'Please try again.',
                    });
                </script>
                ";
                header("Location: ../pages/security.php");
                exit(0);
            }
        } else {
            $_SESSION['alert'] = "
            <script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Current password is incorrect.',
                });
            </script>
            ";
            header("Location: ../pages/security.php");
            exit(0);
        }
    } else {
        $_SESSION['alert'] = "
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Error',
                text: 'User not found.',
            });
        </script>
        ";
        header("Location: ../pages/security.php");
        exit(0);
    }
}
?>
