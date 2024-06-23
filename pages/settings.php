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
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Sorry, only JPG, JPEG, & PNG files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $sql = "UPDATE users SET profilePicture = '$target_file' WHERE userID = '$userID'";
            if (mysqli_query($conn, $sql)) {
                echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
                header("Location: settings.php");
                exit();
            } else {
                echo "Sorry, there was an error updating your profile picture.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
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
</head>

<body>
    <div class="settings-container">
        <?php  
        include_once '../components/sidebar.php'; 
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
                    <img src="<?php echo !empty($row['profilePicture']) ? $row['profilePicture'] : '../profile_pics/default.png'; ?>"
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