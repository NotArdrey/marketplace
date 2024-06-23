<?php
session_start();
require '../config/dbconn.php';
if (!isset($_SESSION['userID'])) {
    header("Location: ../pages/index.php");
} else {
    $userID = $_SESSION['userID'];
    $sql = "SELECT * FROM users WHERE userID = '$userID'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $vStatus = $row['verify_status'];
    $qr_code = $row['qr_code'];
    if ($vStatus == 0) {
        header("Location: ../pages/index.php");
    } 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../styles/index.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>

<body>
    <div class="seller-dashboard-container">
        <?php 
        include_once '../components/seller_sidebar.php'; 
        ?>
        <div class="inner-dashboard-container" id="qr-div">
            <form action="../crud/uploadQR.php" method="POST" id="qrForm" enctype="multipart/form-data">
                <div class="qr-img-container" id="qrImgContainer"
                    style="display: <?php echo ($qr_code != '') ? 'flex' : 'none'; ?>;">
                    <img id="uploadedQr"
                        src="<?php echo ($qr_code != '') ? '../qr_codes/' . htmlspecialchars($qr_code) : ''; ?>"
                        class="uploadedQR">

                </div>
                <?php
                    if ($qr_code == '' || $qr_code == NULL) {
                        echo '
                        <label class="custum-file-upload" for="paymentQR" id="qrInput">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="" viewBox="0 0 24 24">
                            <g stroke-width="0" id="SVGRepo_bgCarrier"></g>
                            <g stroke-linejoin="round" stroke-linecap="round" id="SVGRepo_tracerCarrier"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path fill=""
                                    d="M10 1C9.73478 1 9.48043 1.10536 9.29289 1.29289L3.29289 7.29289C3.10536 7.48043 3 7.73478 3 8V20C3 21.6569 4.34315 23 6 23H7C7.55228 23 8 22.5523 8 22C8 21.4477 7.55228 21 7 21H6C5.44772 21 5 20.5523 5 20V9H10C10.5523 9 11 8.55228 11 8V3H18C18.5523 3 19 3.44772 19 4V9C19 9.55228 19.4477 10 20 10C20.5523 10 21 9.55228 21 9V4C21 2.34315 19.6569 1 18 1H10ZM9 7H6.41421L9 4.41421V7ZM14 15.5C14 14.1193 15.1193 13 16.5 13C17.8807 13 19 14.1193 19 15.5V16V17H20C21.1046 17 22 17.8954 22 19C22 20.1046 21.1046 21 20 21H13C11.8954 21 11 20.1046 11 19C11 17.8954 11.8954 17 13 17H14V16V15.5ZM16.5 11C14.142 11 12.2076 12.8136 12.0156 15.122C10.2825 15.5606 9 17.1305 9 19C9 21.2091 10.7909 23 13 23H20C22.2091 23 24 21.2091 24 19C24 17.1305 22.7175 15.5606 20.9844 15.122C20.7924 12.8136 18.858 11 16.5 11Z"
                                    clip-rule="evenodd" fill-rule="evenodd"></path>
                            </g>
                        </svg>
                    </div>
                    <div class="text">
                        <span>Click to upload image</span>
                    </div>
                    <input type="file" id="paymentQR" name="paymentQR" accept=".jpg, .jpeg, .png, .avif">
                </label>
                        ';
                    }

                ?>
                <div class=" form-buttons-div">
                    <?php
                        if ($qr_code != '' || $qr_code != NULL) {
                            echo '<div class="payment-form-btn delete-picture-btn" id="deletePictureBtn">Delete QR Code</div>';
                        } else { 
                            echo '<div class="payment-form-btn submit-payment-btn" id="uploadQRBtn">Submit</div>';
                            echo '<div class="payment-form-btn change-picture-btn" id="changePictureBtn">Change Picture</div>';
                        }
                    ?>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const changePictureBtn = document.getElementById("changePictureBtn");
        const uploadedQrDiv = document.getElementById("qrImgContainer");
        const uploadedQr = document.getElementById("uploadedQr");
        const paymentInput = document.getElementById("qrInput");
        const paymentQR = document.getElementById("paymentQR");
        const uploadQRBtn = document.getElementById("uploadQRBtn");
        const qrForm = document.getElementById("qrForm");
        const deletePictureBtn = document.getElementById("deletePictureBtn");

        if (deletePictureBtn) {
            deletePictureBtn.addEventListener("click", function() {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../crud/deleteQR.php";
                    }
                });
            });
        }

        if (changePictureBtn) {
            changePictureBtn.addEventListener("click", function() {
                if (paymentQR.files.length > 0) {
                    // Hide the uploaded image
                    uploadedQrDiv.style.display = "none";
                    uploadedQr.style.display = "none";
                    uploadedQr.src = "";

                    // Show the input field
                    paymentInput.style.display = "flex";
                    paymentQR.style.display = "block";
                } else {
                    Swal.fire({
                        title: "No uploaded image!",
                        text: "You must upload a picture!",
                        icon: "error"
                    });
                }
            });
        }

        if (paymentQR) {
            paymentQR.addEventListener("change", function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        uploadedQr.src = e.target.result;
                        uploadedQrDiv.style.display = "flex";
                        uploadedQr.style.display = "block";
                        paymentInput.style.display = "none"; // Hide the input field
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        if (uploadQRBtn) {
            uploadQRBtn.addEventListener("click", function(event) {
                if (paymentQR.files.length > 0) {
                    qrForm.submit();
                } else {
                    Swal.fire({
                        title: "No uploaded image!",
                        text: "You must upload a picture!",
                        icon: "error"
                    });
                }
            });
        }
    });
    </script>
</body>

</html>

<?php
    if(isset($_SESSION['alert'])) {
        echo $_SESSION['alert'];
        unset($_SESSION['alert']);
    }
?>