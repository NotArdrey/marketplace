<?php
    session_start();
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
        <?php  include_once '../components/sidebar.php'; ?>
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
                                <input type="text" class="details-input">
                            </div>
                            <div class="row">
                                <input type="text"  class="details-input">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="row-1 label-row">
                                <label for="">Email</label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="row-1 long-row">
                                <input type="text" class="details-input">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="row-1 label-row">
                                <label for="">Contact Number</label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="row-1 long-row">
                                <input type="text" class="details-input">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="right-details">
                <div class="account-image-div">
                    <img src="../profile_pics/ant.png" alt="" class="account-image">
                </div>
            </div>
        </div>
    </div>
</body>
</html>