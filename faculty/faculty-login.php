<?php
// Include the database configuration file.
include('includes/config.php');

$error = ''; // Variable to store error messages.

if (isset($_POST['submit'])) {
    // Sanitize and capture the input values.
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // MD5 hash the password input for comparison.
    $hashedPassword = md5($password);
    
    // Query the database to check if the email and hashed password match.
    $sql = "SELECT * FROM tblfaculty WHERE email = '$email' AND status = 1"; // Assuming status=1 means active
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // If the faculty exists and status is active, check password.
        $row = mysqli_fetch_assoc($result);
        if ($hashedPassword === $row['Password']) { // Compare the MD5 hashed passwords.
            // Start session and set session variables.
            session_start();
            $_SESSION['faculty_id'] = $row['id'];
            $_SESSION['faculty_id2'] = $row['FacultyID'];
            $_SESSION['faculty_name'] = $row['FullName'];
            $_SESSION['faculty_email'] = $row['email'];

            // Redirect to the faculty dashboard (or any page you want).
            header("Location: faculty-dashboard.php");
            exit();
        } else {
            $error = "Incorrect password. Please try again.";
        }
    } else {
        $error = "No account found with that email or the account is inactive.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Login</title>
    <link rel="stylesheet" href="">
    <style>
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
                <h1>Faculty Login</h1>

                <!-- Error Message -->
                <?php if ($error): ?>
                    <p style="color: red; text-align: center;"><?php echo $error; ?></p>
                <?php endif; ?>

                <input type="text" name="email" placeholder="Username" class="box" required>
                <input type="password" name="password" placeholder="Password" class="box" required>
                <p class="text-center">Don't have an account?<a href="faculty-register.php" class="signup-link"> Register Here</a></p>
                <p class="text-center"><a href="faculty-reset_pass.php" class="signup-link"> Forgot password?</a></p>
                <input type="submit" name="submit" value="Log In" class="btn">
                <a href="../index.php">
                    <input type="button" value="Back to Home" class="btn-back, btnhome">
                </a>
            </form>
        </div>
    </div>
</body>

</html>
