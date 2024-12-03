<?php
include 'includes/config.php';
session_start();

// Process the form submission if it happens
if (isset($_POST['submit'])) {
    // Check if 'pin', 'new_password', and 'confirm_password' are set before using them
    if (isset($_POST['pin']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        // Get the submitted PIN and passwords
        $entered_pin = $_POST['pin'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Check if the new password and confirm password match
        if ($new_password !== $confirm_password) {
            echo "<script>alert('New password and confirmation password do not match.');</script>";
        } else {
            // Validate the PIN from the database
            $query = "SELECT * FROM admins WHERE pin = '$entered_pin' LIMIT 1";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                // PIN matches, update the password
                $hashed_password = md5($new_password); // Hash the password using MD5

                $update_query = "UPDATE admins SET password = '$hashed_password' WHERE pin = '$entered_pin'";
                if (mysqli_query($conn, $update_query)) {
                    // Redirect to admin login page after successful password update
                    echo "<script>alert('Password updated successfully!');</script>";
                    echo "<script>window.location.href = 'admin_login.php';</script>";
                    exit;  // Make sure the script stops here and redirects
                } else {
                    echo "<script>alert('Error updating password.');</script>";
                }
            } else {
                // PIN does not match
                echo "<script>alert('Invalid PIN!');</script>";
            }
        }
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Forgot Password</title>
    <link rel="stylesheet" href="">

    <style>
        /* Your existing styles here */
        .navbar-admin {
            background-color: #0A670C;
            color: #DBCE15;
            padding: 10px 10px;
            display: flex;
        }

        .navbar-container {
            display: flex;
            align-items: center;
        }

        .logo {
            width: 60px;
            height: 60px;
            margin-right: 30px;
        }

        .navbar-title {
            font-size: 24px;
            font-weight: bold;
            display: inline-block;
        }

        body {
            font-family: "K2D", sans-serif;
            margin: 0;
            background-color: #fbf5df;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
            pointer-events: none;
        }

        .form-container {
            margin-top: 8%;
        }

        #box {
            position: relative;
            max-width: 500px;
            margin: 50px auto;
            background: #dbce15;
            padding: 40px;
            border-radius: 50px;
            border: 8px solid #0A670C;
            box-shadow: 0 0 20px rgba(10, 103, 12, 1);
            animation: slideIn 0.5s ease forwards;
            opacity: 0;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        h1 {
            text-align: center;
            color: #0a6706;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            box-sizing: border-box;
            border: 5px solid #0a6706;
            border-style: hidden hidden solid solid;
            border-radius: 50px;
            background-color: #f0f8ff;
        }

        input[type="submit"] {
            width: 100%;
            font-family: "K2D", sans-serif;
            font-size: 20px;
            background-color: #0a6706;
            color: #dbce15;
            padding: 15px 0;
            margin: 10px 0;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #052e05;
        }

        .forgot-password {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 16px;
        }

        .forgot-password a {
            color: #0A670C;
            text-decoration: none;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        /* Style for Back to Login button */
        .btn-back {
            width: 100%;
            font-family: "K2D", sans-serif;
            font-size: 20px;
            background-color: #0a6706;
            color: #dbce15;
            padding: 15px 0;
            margin: 10px 0;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            text-align: center;
            display: inline-block;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn-back:hover {
            background-color: #052e05;
        }

    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar-admin">
        <div class="navbar-container">
            <img src="assets/img/jbest_logo.png" alt="JBEST Logo" class="logo">
            <span class="navbar-title">JBEST LIBRARY<br>MANAGEMENT SYSTEM</span>
        </div>
    </nav>

    <div class="overlay"></div>
    <div class="form-container">
        <div id="box">
            <h1>Admin Forgot Password</h1>
            <!-- Form for entering PIN and changing password -->
            <form action="" method="POST">
                <input type="text" name="pin" placeholder="Enter PIN" class="box" maxlength="4"required>
                <input type="password" name="new_password" placeholder="Enter New Password" class="box" required>
                <input type="password" name="confirm_password" placeholder="Confirm New Password" class="box" required>
                <input type="submit" name="submit" value="Change Password" class="btn">
            </form>
            <form action="admin_login.php">
                <input type="submit" value="Back to Login" class="btn-back">
            </form>
        </div>
    </div>
</body>

</html>
