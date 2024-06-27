<?php
session_start();
include "../config/dbconn.php";

class UserAuth {
    private $conn;
    private $email;
    private $password;

    public function __construct($conn, $email, $password) {
        $this->conn = $conn;
        $this->email = $email;
        $this->password = $password;
    }

    public function authenticate() {
        $user = $this->getUserByEmail();
        
        if ($user && $this->verifyPassword($user['userPassword'])) {
            if ($user['verify_status'] == 1) {
                $this->setUserSession($user);
                $this->redirect('../pages/customer_dashboard.php');
            } else {
                $this->setAlert('warning', 'Verification Required', 'Please verify your email address.');
                $this->redirect('../pages/index.php');
            }
        } else {
            $this->setAlert('error', 'Wrong username or password', 'Please try again.');
            $this->redirect('../pages/index.php');
        }
    }

    private function getUserByEmail() {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows == 1 ? $result->fetch_assoc() : null;
    }

    private function verifyPassword($hashedPassword) {
        // Assuming passwords are hashed, use password_verify() for checking.
        return password_verify($this->password, $hashedPassword);
    }

    private function setUserSession($user) {
        $_SESSION['authenticated'] = true;
        $_SESSION['auth_user'] = [
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'contact_number' => $user['contact_number'],
            'userPassword' => $user['userPassword'],
        ];
        $_SESSION['userID'] = $user['userID'];
    }

    private function setAlert($icon, $title, $text) {
        $_SESSION['alert'] = "
            <script>
                Swal.fire({
                    icon: '$icon',
                    title: '$title',
                    text: '$text',
                });
            </script>
        ";
    }

    private function redirect($url) {
        header("Location: $url");
        exit();
    }
}

$loginEmail = $_POST['email'];
$loginPassword = $_POST['password'];

$auth = new UserAuth($conn, $loginEmail, $loginPassword);
$auth->authenticate();
?>
