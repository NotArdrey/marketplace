<?php
session_start();
include "../config/dbconn.php";

$loginEmail = $_POST['email'];
$loginPassword = $_POST['password'];



$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loginEmail);
$stmt->execute();
$result = $stmt->get_result();

if (mysqli_num_rows($result) == 1) {

    $row = mysqli_fetch_assoc($result);

    if($row["verify_status"] == 1){

        $_SESSION['authenticated'] = TRUE;
        $_SESSION['auth_user'] = [
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'email' => $row['email'],
            'contact_number' => $row['contact_number'],
            'userPassword' => $row['userPassword'],
        ];
        $_SESSION['userID'] = $row['userID'];
        header("Location: ../pages/customer_dashboard.php");
        exit();

    }else{
        $_SESSION['alert'] = "
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Verification Required',
                text: 'Please verify your email address.',
            });
        </script>
        ";
        header("Location: ../pages/index.php");
        exit(0);
    }


} else {
    $_SESSION['alert'] = "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Wrong username or password',
                text: 'Please try again.'
        });
        </script>
    ";
    header("Location: ../pages/index.php");
    exit();
}
?>
