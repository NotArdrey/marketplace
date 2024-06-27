<?php
session_start();
require '../config/dbconn.php'; 

$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $_SESSION['alert'] = '
    <script>
    Swal.fire({
      title: "Error!",
      text: "This email is already registered!",
      icon: "error"
    });
    </script>
    ';
} else {
    $sql = "INSERT INTO users (user_type, first_name, last_name, email, userPassword) VALUES ('admin', '$firstName', '$lastName', '$email', '$password')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $_SESSION['alert'] = '
    <script>
    Swal.fire({
      title: "Success!",
      text: "Admin account successfully created!",
      icon: "success"
    });
    </script>
    ';
    } else {
        $_SESSION['alert'] = '
    <script>
    Swal.fire({
      title: "Error!",
      text: "Something went wrong!",
      icon: "error"
    });
    </script>
    ';
    }
}
header("Location: ../pages/create_admin.php");