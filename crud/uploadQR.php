<?php
session_start();
require "../config/dbconn.php";
$userID = $_SESSION['userID'];

if($_FILES["paymentQR"]["error"] === 4) {
    $_SESSION['alert'] = "<script>
    swal({
        title: 'Error!',
        text: 'Image not found',
        icon: 'error',
    });
    </script>";
    header("Location: ../pages/upload_qr.php");
    exit();
} else {
    $fileName = $_FILES["paymentQR"]["name"];
    $fileSize = $_FILES["paymentQR"]["size"];
    $tmpName = $_FILES["paymentQR"]["tmp_name"];

    $validImageExtension = ['jpg', 'jpeg', 'png', 'avif'];
    $imageExtension = explode('.', $fileName);
    $imageExtension = strtolower(end($imageExtension));
    if(!in_array($imageExtension, $validImageExtension)) {
        $_SESSION['alert'] = "<script>
        const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.onmouseenter = Swal.stopTimer;
          toast.onmouseleave = Swal.resumeTimer;
        }
    });
        Toast.fire({
          icon: 'error',
          title: 'Invalid file extension'
    });
        </script>";
    }
    else if($fileSize > 10000000) {
        $_SESSION['alert'] = "<script>
        const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.onmouseenter = Swal.stopTimer;
          toast.onmouseleave = Swal.resumeTimer;
        }
    });
        Toast.fire({
          icon: 'error',
          title: 'Invalid file size'
    });
        </script>";
    }
    else {
        $newImageName = uniqid() . '.' . $imageExtension;
        move_uploaded_file($tmpName, '../qr_codes/' . $newImageName);
        $sql = "UPDATE users SET qr_code = '$newImageName' WHERE userID = '$userID'";
        $result = mysqli_query($conn, $sql);
        $_SESSION['alert'] = "<script>
        Swal.fire({
          title: 'Success!',
          text: 'QR code successfully set!',
          icon: 'success'
        });
        </script>";
        header("Location: ../pages/upload_qr.php");
        exit();
    }
}



?>