<?php
// Include database connection script
include('includes/config.php'); // Update this path to where your connection script is located

// Initialize variables with default empty values
$changeType = '';
$currentPassword = '';
$newPassword = '';
$confirmPassword = '';
$currentPin = '';
$newPin = '';
$confirmPin = '';

// Start the PHP block for handling form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the form fields are set before using them
    if (isset($_POST['changeType'])) {
        $changeType = $_POST['changeType']; // Store the selected type (password or pin)
    }
    if (isset($_POST['currentPassword'])) {
        $currentPassword = mysqli_real_escape_string($conn, $_POST['currentPassword']);
    }
    if (isset($_POST['newPassword'])) {
        $newPassword = mysqli_real_escape_string($conn, $_POST['newPassword']);
    }
    if (isset($_POST['confirmPassword'])) {
        $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);
    }
    if (isset($_POST['currentPin'])) {
        $currentPin = mysqli_real_escape_string($conn, $_POST['currentPin']);
    }
    if (isset($_POST['newPin'])) {
        $newPin = mysqli_real_escape_string($conn, $_POST['newPin']);
    }
    if (isset($_POST['confirmPin'])) {
        $confirmPin = mysqli_real_escape_string($conn, $_POST['confirmPin']);
    }

    // Determine what action to take based on the change type
    if ($changeType == 'password') {
        // Check if the new password and confirm password match
        if ($newPassword !== $confirmPassword) {
            echo "<script>alert('New password and confirm password do not match');</script>";
        } else {
            // Check if the current password is correct (hashed with MD5)
            $adminQuery = "SELECT * FROM admins WHERE password = MD5(?)";
            $stmt = $conn->prepare($adminQuery);
            $stmt->bind_param("s", $currentPassword);
            $stmt->execute();
            $result = $stmt->get_result();

            if (mysqli_num_rows($result) > 0) {
                // Update the password in the database
                $updateQuery = "UPDATE admins SET password = MD5(?) WHERE password = MD5(?)";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("ss", $newPassword, $currentPassword);

                if ($stmt->execute()) {
                    echo "<script>alert('Password changed successfully');</script>";
                } else {
                    echo "<script>alert('Failed to change password');</script>";
                }

                $stmt->close();
            } else {
                echo "<script>alert('Current password is incorrect');</script>";
            }
        }
    } elseif ($changeType == 'pin') {
        // Check if the new pin and confirm pin match
        if ($newPin !== $confirmPin) {
            echo "<script>alert('New PIN and confirm PIN do not match');</script>";
        } else {
            // Check if the current PIN is correct
            $adminQuery = "SELECT * FROM admins WHERE pin = ?";
            $stmt = $conn->prepare($adminQuery);
            $stmt->bind_param("s", $currentPin);
            $stmt->execute();
            $result = $stmt->get_result();

            if (mysqli_num_rows($result) > 0) {
                // Update the pin in the database
                $updateQuery = "UPDATE admins SET pin = ? WHERE pin = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("ss", $newPin, $currentPin);

                if ($stmt->execute()) {
                    echo "<script>alert('PIN changed successfully');</script>";
                } else {
                    echo "<script>alert('Failed to change PIN');</script>";
                }

                $stmt->close();
            } else {
                echo "<script>alert('Current PIN is incorrect');</script>";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Change Password</title>

    <!-- Google Fonts Preconnect and Stylesheet -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=K2D:wght@100;200;300;400;500;600;700;800&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome (only one version) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/tables.css">

    <!-- Bootstrap JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (only if needed) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .btn-info {
    background-color: #0A670C;
    color: #dbce15;
    font-size: 16px;
    font-weight: 500;
    padding: 10px 20px;
    margin-top: 20px;
    border: none;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}
    </style>
</head>

<body>
    <!--MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Admin Change Password or PIN</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Change Password or PIN
                        </div>
                        <div class="panel-body">
                            <!-- Change Password and PIN Form -->
                            <form role="form" method="POST">
                                <!-- Selection of what to change -->
                                <div class="form-group">
                                    <label for="changeType">Select Action</label>
                                    <select name="changeType" id="changeType" class="form-control" required>
                                        <option value="">--Select--</option>
                                        <option value="password">Change Password</option>
                                        <option value="pin">Change PIN</option>
                                    </select>
                                </div>

                                <!-- Current password field (hidden by default) -->
                                <div class="form-group" id="currentPasswordField" style="display:none;">
                                    <label for="currentPassword">Current Password</label>
                                    <input type="password" class="form-control" id="currentPassword" name="currentPassword">
                                </div>

                                <!-- Current PIN field (hidden by default) -->
                                <div class="form-group" id="currentPinField" style="display:none;">
                                    <label for="currentPin">Current PIN</label>
                                    <input type="text" class="form-control" id="currentPin" name="currentPin" maxlength="4">
                                </div>

                                <!-- Password change fields (hidden by default) -->
                                <div id="passwordFields" style="display:none;">
                                    <div class="form-group">
                                        <label for="newPassword">New Password</label>
                                        <input type="password" class="form-control" id="newPassword" name="newPassword">
                                    </div>
                                    <div class="form-group">
                                        <label for="confirmPassword">Confirm New Password</label>
                                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                                    </div>
                                </div>

                                <!-- PIN change fields (hidden by default) -->
                            <div id="pinFields" style="display:none;">
                                <div class="form-group">
                                    <label for="newPin">New PIN</label>
                                    <input type="text" class="form-control" id="newPin" name="newPin" maxlength="4">
                                </div>
                                <div class="form-group">
                                    <label for="confirmPin">Confirm New PIN</label>
                                    <input type="text" class="form-control" id="confirmPin" name="confirmPin" maxlength="4">
                                </div>
                            </div>

                                <button type="submit" name="changePassword" class="btn btn-info">Change</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->

    <!-- jQuery to handle dynamic form changes -->
    <script>
        $(document).ready(function () {
            $('#changeType').change(function () {
                var selectedOption = $(this).val();

                // Hide all fields first
                $('#currentPasswordField').hide();
                $('#currentPinField').hide();
                $('#passwordFields').hide();
                $('#pinFields').hide();

                // Show relevant fields based on the selection
                if (selectedOption == 'password') {
                    $('#currentPasswordField').show();
                    $('#passwordFields').show();
                    $('#currentPassword').prop('required', true);
                    $('#currentPin').prop('required', false);
                } else if (selectedOption == 'pin') {
                    $('#currentPinField').show();
                    $('#pinFields').show();
                    $('#currentPin').prop('required', true);
                    $('#currentPassword').prop('required', false);
                }
            }).trigger('change'); // Trigger change on page load to set initial visibility
        });
    </script>
</body>

</html>
