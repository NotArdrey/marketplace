<?php
session_start();
require '../config/dbconn.php';

if (!isset($_SESSION['userID'])) {
    header("Location: ../pages/index.php");
    exit();
}

$userID = $_SESSION['userID'];
$sql = "SELECT * FROM users WHERE userID = '$userID'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$vStatus = $row['verify_status'];

if ($vStatus == 0) {
    header("Location: ../pages/index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $target_dir = "../profile_pics/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

   
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        $uploadOk = 0;
        $_SESSION['alert'] = "
        <script>
            Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'File is not an image.',
            });
        </script>
        ";
        header("Location: ../pages/settings.php");
        exit();
    }

    // Check file size
    if ($_FILES["image"]["size"] > 500000) {
        $uploadOk = 0;
        $_SESSION['alert'] = "
        <script>
            Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Sorry, your file is too large.',
            });
        </script>
        ";
        header("Location: ../pages/settings.php");
        exit();
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $uploadOk = 0;
        $_SESSION['alert'] = "
        <script>
            Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Sorry, only JPG, JPEG, & PNG files are allowed.',
            });
        </script>
        ";
        header("Location: ../pages/settings.php");
        exit();
    }


    if ($uploadOk == 0) {
        $_SESSION['alert'] = "
        <script>
            Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Sorry, your file was not uploaded.',
            });
        </script>
        ";
        header("Location: ../pages/settings.php");
        exit();
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $sql = "UPDATE users SET profilePicture = '$target_file' WHERE userID = '$userID'";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['alert'] = "
                <script>
                    Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.',
                    });
                </script>
                ";
                header("Location: ../pages/settings.php");
                exit();
            } else {
                $_SESSION['alert'] = "
                <script>
                    Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Sorry, there was an error updating your profile picture.',
                    });
                </script>
                ";
                header("Location: ../pages/settings.php");
                exit();
            }
        } else {
            $_SESSION['alert'] = "
            <script>
                Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Sorry, there was an error uploading your file.',
                });
            </script>
            ";
            header("Location: ../pages/settings.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="../styles/index.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php
    if (isset($_SESSION['alert'])) {
        echo $_SESSION['alert'];
        unset($_SESSION['alert']);
    }
    ?>
    <div class="settings-container">
        <?php include_once '../components/sidebar.php'; 
        $sql = "SELECT * FROM users WHERE userID = '$userID'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        ?>
        <div class="details-container">
            <div class="left-details">
                <div class="details-header">
                    <h1>Account Details</h1>
                </div>
                <div class="details">
                    <form action="">
                        <div class="form-row">
                            <div class="row-1 label-row">
                                <label for="">First Name</label>
                            </div>
                            <div class="row label-row">
                                <label for="">Last Name</label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="row-1">
                                <input type="text" class="details-input" value="<?php echo $row['first_name']; ?>"
                                    readonly>
                            </div>
                            <div class="row">
                                <input type="text" class="details-input" value="<?php echo $row['last_name']; ?>"
                                    readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="row-1 label-row">
                                <label for="">Email</label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="row-1 long-row">
                                <input type="text" class="details-input" value="<?php echo $row['email']; ?>" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="row-1 label-row">
                                <label for="">Contact Number</label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="row-1 long-row">
                                <input type="text" class="details-input" value="<?php echo $row['contact_number']; ?>"
                                    readonly>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="right-details">
                <div class="account-image-div">
                    <img src="<?php echo !empty($row['profilePicture']) ? $row['profilePicture'] : '../img/default.png'; ?>"
                        alt="Profile Picture" class="account-image">
                </div>

                <form id="form" action="settings.php" method="post" enctype="multipart/form-data"
                    class="settings-picture-form">
                    <div class="custom-file-input">
                        <label for="image" class="file-input-label">Choose File</label>
                        <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png">
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script type="text/javascript">
    document.getElementById("image").onchange = function() {
        document.getElementById("form").submit();
    }
    </script>
</body>

</html>
