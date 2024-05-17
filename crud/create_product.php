<?php
session_start();
include "../config/dbconn.php";

if (isset($_POST['createProduct'])){
    if($_FILES["productImg"]["error"] === 4) {
        $_SESSION['alert'] = "<script>
        swal({
            title: 'Error!',
            text: 'Image not found',
            icon: 'error',
        });
        </script>";
        header("Location: ../pages/customer_dashboard.php");
        exit();
    } else {
        $fileName = $_FILES["productImg"]["name"];
        $fileSize = $_FILES["productImg"]["size"];
        $tmpName = $_FILES["productImg"]["tmp_name"];

        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = explode('.', $fileName);
        $imageExtension = strtolower(end($imageExtension));
        if(!in_array($imageExtension, $validImageExtension)) {
            $_SESSION['alert'] =
            "<script>
            swal({
                title: 'Error!',
                text: 'Invalid file type',
                icon: 'error',
            });
            </script>";
            header("Location: ../pages/profile.php");
            exit();
        }
        else if($fileSize > 10000000) {
            $_SESSION['alert'] =
            "<script>
            swal({
                title: 'Error!',
                text: 'File too large',
                icon: 'error',
            });
            </script>";
            header("Location: ../pages/profile.php");
            exit();
        }
        else {
            $newImageName = uniqid() . '.' . $imageExtension;
            move_uploaded_file($tmpName, '../profile-pics/' . $newImageName);
            mysqli_query($conn, "UPDATE users SET img_name = '$newImageName' WHERE userID = '{$_SESSION["userID"]}'");
            $_SESSION['alert'] =
            "<script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
                })
    
                Toast.fire({
                icon: 'success',
                title: 'Your profile picture has been uploaded successfully'
                })
            </script>"
            ;
            header("Location: ../pages/profile.php");
            exit();
        }
    }
}
?>