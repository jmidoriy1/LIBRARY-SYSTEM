<?php
// Include the database connection
include('includes/config.php');

// Initialize message variable
$message = '';

if (isset($_POST['submit'])) {
    // Get the submitted email and password
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize input to prevent SQL injection
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // MD5 hash the password entered by the user
    $hashed_password = md5($password);

    // Query the database to find the admin with the entered email
    $sql = "SELECT * FROM admins WHERE email = '$email' AND password = '$hashed_password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Successful login, redirect to admin dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        // Incorrect email or password
        $message = 'Incorrect email or password. Please try again.';
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="">

    <style>
        /* Your existing CSS here */
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

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

        .btnhome {
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

        /* Style for Back to Home button */
        .btn-back {
            width: 100%;
            font-family: "K2D", sans-serif;
            font-size: 20px;
            background-color: #dbce15;
            color: #0a6706;
            padding: 15px 0;
            margin: 10px 0;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-back:hover {
            background-color: #c1b000;
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

    <!-- Login Form -->
    <div class="overlay"></div>
    <div class="form-container">
        <div id="box">
            <form action="" method="post" enctype="multipart/form-data">
                <h1>Admin Login</h1>

                <?php
                // Display error message if login fails
                if (!empty($message)) {
                    echo '<div class="error-message">' . $message . '</div>';
                }
                ?>

                <input type="text" name="email" placeholder="Email" class="box" required>
                <input type="password" name="password" placeholder="Password" class="box" required>
                <input type="submit" name="submit" value="Log In" class="btn">
                <a href="../index.php">
                    <input type="button" value="Back to Home" class="btn-back, btnhome">
                </a>
                <!-- Forgot Password Link -->
                <div class="forgot-password">
                    <a href="admin-forgot_pass.php">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
