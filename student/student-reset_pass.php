<?php
include('includes/config.php');

// If the form is submitted for forgot password
if (isset($_POST['submit_email'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $query = "SELECT * FROM tblstudents WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email exists, show reset password form
        $showResetForm = true;
    } else {
        // Email not found in the database
        $error = "Email not found in our records.";
    }
}

// If the form is submitted for resetting the password
if (isset($_POST['reset_password'])) {
    $email = $_POST['email'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if passwords match
    if ($newPassword === $confirmPassword) {
        // MD5 hash the new password
        $hashedPassword = md5($newPassword);

        // Update the password in the database
        $updateQuery = "UPDATE tblstudents SET Password = ? WHERE email = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ss", $hashedPassword, $email);

        if ($updateStmt->execute()) {
            // Set success message and trigger modal
            $successMessage = "Successfully recovered the account. You can now log in with your new password.";
        } else {
            $error = "Error updating the password. Please try again.";
        }
    } else {
        $error = "Passwords do not match. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot / Reset Password</title>
    <link rel="stylesheet" href="assets/css/student.css">
    <style>
        /* Basic styling for the form */
        body {
            font-family: "K2D", sans-serif;
            margin: 0;
            background-color: #fbf5df;
        }

        .form-container {
            margin-top: 8%;
            text-align: center;
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
        }

        h1 {
            color: #0a6706;
        }

        input[type="email"], input[type="password"] {
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

        .error {
            color: red;
        }

        /* Success Modal Styling */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0, 0, 0); /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe; /* White background */
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 10px;
            color: black; /* Black text */
        }

        .close {
            color: green; /* Green color for the 'X' */
            float: right;
            font-size: 36px; /* Larger 'X' */
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: darkgreen; /* Darker green on hover */
            text-decoration: none;
            cursor: pointer;
        }

        .success {
            color: green;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 15px;
            margin-top: 20px;
            border-radius: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div id="box">
            <?php if (!isset($showResetForm)): ?>
                <!-- Forgot Password Form -->
                <h1>Forgot Password</h1>
                <form method="post" action="">
                    <input type="email" name="email" placeholder="Enter your registered email" required>
                    <input type="submit" name="submit_email" value="Submit">
                    <a href="student-login.php">
                    <input type="button" value="Back to Login" class="btnhome">
                    </a>
                </form>
                <?php if (isset($error)): ?>
                    <p class="error"><?= $error ?></p>
                <?php endif; ?>
            <?php else: ?>
                <!-- Reset Password Form -->
                <h1>Reset Password</h1>
                <form method="post" action="">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                    <input type="password" name="newPassword" placeholder="New Password" required>
                    <input type="password" name="confirmPassword" placeholder="Confirm New Password" required>
                    <input type="submit" name="reset_password" value="Reset Password">
                </form>
                <?php if (isset($error)): ?>
                    <p class="error"><?= $error ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Success Modal -->
    <?php if (isset($successMessage)): ?>
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p><?= $successMessage ?></p>
            <a href="student-login.php">
                <input type="button" value="Back to Login" class="btnhome">
            </a>
        </div>
    </div>
    <script>
        // Show the modal
        var modal = document.getElementById("successModal");
        modal.style.display = "block";

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
    <?php endif; ?>
</body>
</html>
