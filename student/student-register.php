<?php
include('includes/config.php');
if (isset($_POST['submit'])) {
    $studentID = $_POST['studentID'];
    $name = $_POST['name'];          // Changed to 'name' instead of 'fullName'
    $lastName = $_POST['lastName'];  // Added 'lastName' field
    $email = $_POST['email'];
    $mobileNumber = $_POST['mobileNumber']; 
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if password and confirm password match
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    } else {
        // Check if studentID already exists
        $checkIDQuery = "SELECT * FROM tblstudents WHERE studentID = ?";
        $stmt = $conn->prepare($checkIDQuery);
        $stmt->bind_param("s", $studentID);
        $stmt->execute();
        $resultID = $stmt->get_result();

        if ($resultID->num_rows > 0) {
            echo "<script>alert('Error: The Student ID is already registered');</script>";
        } else {
            // Check if email is already registered
            $checkEmailQuery = "SELECT * FROM tblstudents WHERE email = ?";
            $stmt = $conn->prepare($checkEmailQuery);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultEmail = $stmt->get_result();

            if ($resultEmail->num_rows > 0) {
                echo "<script>alert('The email address is already registered.');</script>";
            } else {
                // Combine Name and LastName into FullName
                $fullName = $name . ' ' . $lastName;

                // Hash password
                $hashedPassword = md5($password);

                // Insert into the database
                $sql = "INSERT INTO tblstudents (StudentID, Name, LastName, FullName, email, MobileNumber, Password) 
                        VALUES ('$studentID', '$name', '$lastName', '$fullName', '$email', '$mobileNumber', '$hashedPassword')";

                if (mysqli_query($conn, $sql)) {
                    echo "<script>alert('Registration successful!');</script>";
                } else {
                    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                }
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
    <title>Student Registration</title>
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
        input[type="email"],
        input[type="password"],
        input[type="tel"] {
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

        .btn-back {
            background-color: #0A670C;
            color: #dbce15;
            padding: 12px;
            margin-top: 10px;
            font-size: 18px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            width: 100%;
            text-align: center;
        }

        .btn-back:hover {
            background-color: #052e05;
        }
    </style>


<script type="text/javascript">
        // JavaScript function to ensure mobile number is numeric and has 11 digits
        function validateMobileNumber() {
            var mobileNumber = document.getElementById("mobileNumber").value;

            // Check if the mobile number is numeric and has 11 digits
            if (!/^\d{11}$/.test(mobileNumber)) {
                alert("Please enter a valid mobile number with exactly 11 digits.");
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }

        // JavaScript function to validate password match
        function validatePasswordMatch() {
            var password = document.getElementsByName("password")[0].value;
            var confirmPassword = document.getElementsByName("confirmPassword")[0].value;

            // Check if password and confirm password match
            if (password !== confirmPassword) {
                alert("Passwords do not match. Please try again.");
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }

        // JavaScript function to validate email format for multiple providers
        function validateEmail() {
            var email = document.getElementById("email").value;
            var emailPattern = /^[a-zA-Z0-9._%+-]+@(gmail\.com|outlook\.com|yahoo\.com|yandex\.ru)$/; // Allows Gmail, Outlook, Yahoo, and Yandex
            var emailCheck = emailPattern.test(email);

            if (!emailCheck) {
                alert("Please enter a valid email address from Gmail, Outlook, Yahoo, or Yandex.");
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }

        // Combine all validation functions before form submission
        function validateForm() {
            if (validateMobileNumber() && validatePasswordMatch() && validateEmail()) {
                return true; // Allow form submission
            }
            return false; // Prevent form submission
        }
    </script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar-admin">
        <div class="navbar-container">
            <img src="assets/img/jbest_logo.png" alt="JBEST Logo" class="logo">
            <span class="navbar-title">JBEST LIBRARY<br>MANAGEMENT SYSTEM</span>
        </div>
    </nav>

    <!-- Registration Form -->
    <div class="overlay"></div>
    <div class="form-container">
        <div id="box">
            <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <h1>Student Register</h1>
                <input type="text" name="studentID" placeholder="Student ID" class="box" required>
                <input type="text" name="name" placeholder="First Name" class="box" required pattern="[A-Za-z\s]+" minlength="3">
                <input type="text" name="lastName" placeholder="Last Name" class="box" required pattern="[A-Za-z\s]+" minlength="3">
                <input type="email" name="email" id="email" placeholder="Email Address" class="box" required>
                <input type="tel" id="mobileNumber" name="mobileNumber" placeholder="Mobile Number (11 digits)" class="box" maxlength="11" required pattern="\d{11}" title="Mobile number must be 11 digits" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                <input type="password" name="password" placeholder="Password" class="box" required>
                <input type="password" name="confirmPassword" placeholder="Confirm Password" class="box" required>
                <input type="submit" name="submit" value="Register">
            </form>
            <form action="student-login.php">
                <input type="submit" value="Back to Login" class="btn-back">
            </form>
        </div>
    </div>
</body>

</html>

