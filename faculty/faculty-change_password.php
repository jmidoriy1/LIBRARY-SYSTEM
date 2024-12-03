<?php
// Include database configuration file
include('includes/config.php');

// Start session to access user data
session_start();

if (!isset($_SESSION['faculty_id'])) {
    // Redirect to login if not authenticated
    header('Location: faculty-login.php');
    exit();
}

$faculty_id = $_SESSION['faculty_id'];

$error = '';
$success = '';

if (isset($_POST['change'])) {
    // Sanitize and capture the input values
    $currentPassword = mysqli_real_escape_string($conn, $_POST['password']);
    $newPassword = mysqli_real_escape_string($conn, $_POST['newpassword']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmpassword']);

    // Ensure new passwords match
    if ($newPassword !== $confirmPassword) {
        $error = "New Password and Confirm Password do not match!";
    } else {
        // Hash current password and check in the database
        $hashedCurrentPassword = md5($currentPassword);

        $sql = "SELECT * FROM tblfaculty WHERE id = '$faculty_id' AND Password = '$hashedCurrentPassword'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            // The current password is correct, now update with the new password
            $hashedNewPassword = md5($newPassword); // Hash the new password

            $updateSql = "UPDATE tblfaculty SET Password = '$hashedNewPassword' WHERE id = '$faculty_id'";
            if (mysqli_query($conn, $updateSql)) {
                $success = "Password changed successfully.";
            } else {
                $error = "Failed to change password. Please try again.";
            }
        } else {
            $error = "Current password is incorrect.";
        }
    }

    // Return the result as a JSON response for AJAX
    echo json_encode([
        'status' => $error ? 'error' : 'success',
        'message' => $error ? $error : $success
    ]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard - Change Password</title>
    <link rel="stylesheet" href="assets/css/faculty.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script type="text/javascript">
        $(document).ready(function () {
            // Show/Hide password toggle function
            $(".toggle-password").click(function () {
                var input = $($(this).attr("toggle"));
                var type = input.attr("type") === "password" ? "text" : "password";
                input.attr("type", type);

                // Change icon based on password visibility
                if (type === "password") {
                    $(this).removeClass("fa-eye-slash").addClass("fa-eye");
                } else {
                    $(this).removeClass("fa-eye").addClass("fa-eye-slash");
                }
            });

            // Handle form submission via AJAX
            $("form[name='chngpwd']").on('submit', function (e) {
                e.preventDefault(); // Prevent the form from submitting the normal way

                var currentPassword = $("input[name='password']").val();
                var newPassword = $("input[name='newpassword']").val();
                var confirmPassword = $("input[name='confirmpassword']").val();

                $.ajax({
                    url: '', // Same page to process the form
                    type: 'POST',
                    data: {
                        change: true,
                        password: currentPassword,
                        newpassword: newPassword,
                        confirmpassword: confirmPassword
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            alert(response.message);  // Replace with custom modal if preferred
                        } else {
                            alert(response.message);
                        }

                        // Clear form fields after submission
                        $("input[name='password']").val('');
                        $("input[name='newpassword']").val('');
                        $("input[name='confirmpassword']").val('');
                    },
                    error: function () {
                        alert('An error occurred while changing the password.');
                    }
                });
            });
        });
    </script>
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Change Password</h4>
                </div>
            </div>

            <!-- Form to change password -->
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">Change Password</div>
                        <div class="panel-body">
                            <!-- Display error or success messages -->
                            <?php if ($error): ?>
                                <p style="color: red;"><?php echo $error; ?></p>
                            <?php elseif ($success): ?>
                                <p style="color: green;"><?php echo $success; ?></p>
                            <?php endif; ?>

                            <!-- Password Change Form -->
                            <form name="chngpwd" method="post">
                                <!-- Current Password -->
                                <div class="form-group position-relative">
                                    <label>Current Password</label>
                                    <input class="form-control" type="password" name="password" autocomplete="off" required />
                                </div>

                                <!-- New Password -->
                                <div class="form-group position-relative">
                                    <label>Enter New Password</label>
                                    <input class="form-control" type="password" name="newpassword" autocomplete="off" required />
                                </div>

                                <!-- Confirm New Password -->
                                <div class="form-group position-relative">
                                    <label>Confirm New Password</label>
                                    <input class="form-control" type="password" name="confirmpassword" autocomplete="off" required />
                                </div>

                                <button type="submit" name="change" class="btn btn-info">Change Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</body>
</html>
