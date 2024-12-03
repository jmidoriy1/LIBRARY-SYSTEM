<?php
include('includes/config.php');
session_start(); 
if (!isset($_SESSION['student_id'])) {
    header("Location: student-login.php");
    exit();
}

$studentId = $_SESSION['student_id'];
$query = "SELECT * FROM tblstudents WHERE StudentID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $studentId); 
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc(); 
} else {
    echo "Student record not found!";
    exit();
}

if (isset($_POST['update'])) {
    // Get the Name (First Name), Last Name, and Email fields from the form
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $email = $_POST['email'];  // Getting the updated email
    $mobileNo = $_POST['mobileno'];

    // Combine Name and LastName into FullName
    $fullName = $firstName . ' ' . $lastName;

    // Update query with the new values
    $updateQuery = "UPDATE tblstudents SET Name = ?, LastName = ?, FullName = ?, MobileNumber = ?, email = ? WHERE StudentID = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ssssss", $firstName, $lastName, $fullName, $mobileNo, $email, $studentId);

    if ($updateStmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully', 'fullName' => $fullName, 'mobileNo' => $mobileNo, 'email' => $email]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update profile']);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Profile</title>
    <link rel="stylesheet" href="assets/css/student.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@100;200;300;400;500;600;700;800&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <script type="text/javascript">
        // Email validation function
        function validateEmail() {
            var email = document.getElementById("email").value;
            var emailPattern = /^[a-zA-Z0-9._%+-]+@(gmail\.com|outlook\.com|yahoo\.com|yandex\.ru)$/; // Allows Gmail, Outlook, Yahoo, Yandex
            var emailCheck = emailPattern.test(email);

            if (!emailCheck) {
                alert("Please enter a valid email address from Gmail, Outlook, Yahoo, or Yandex.");
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }

        $(document).ready(function () {
            $("form[name='signup']").on('submit', function (e) {
                e.preventDefault(); // Prevent the form from submitting the normal way
                
                // Validate the email
                if (!validateEmail()) {
                    return; // Stop form submission if email is invalid
                }

                var firstName = $("input[name='firstname']").val();
                var lastName = $("input[name='lastname']").val();
                var mobileNo = $("input[name='mobileno']").val();
                var email = $("input[name='email']").val();

                $.ajax({
                    url: '', // Same file to process the form
                    type: 'POST',
                    data: {
                        update: true,
                        firstname: firstName,
                        lastname: lastName,
                        mobileno: mobileNo,
                        email: email // Include the email in the POST data
                    },
                    dataType: 'json', 
                    success: function (response) {
                        if (response.status === 'success') {
                            // Update the form with the new data
                            $("input[name='fullname']").val(response.fullName);
                            $("input[name='mobileno']").val(response.mobileNo);
                            $("input[name='email']").val(response.email); // Update the email field
                            alert(response.message);  // Show the success message
                        } else {
                            alert(response.message);  // Show the error message if update failed
                        }
                    },
                    error: function () {
                        alert('An error occurred while updating the profile.');
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
                    <h4 class="header-line">My Profile</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-md-offset-1">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            My Profile
                        </div>
                        <div class="panel-body">
                            <form name="signup" method="post">
                                <!-- Student ID -->
                                <div class="form-group">
                                    <label>Student ID :</label>
                                    <input class="form-control" type="text" name="studentid" value="<?php echo $student['StudentID']; ?>" readonly />
                                </div>

                                <!-- Registration Date -->
                                <div class="form-group">
                                    <label>Reg Date :</label>
                                    <input class="form-control" type="text" name="regdate" value="<?php echo $student['RegDate']; ?>" readonly />
                                </div>

                                <!-- Last Update Date -->
                                <div class="form-group">
                                    <label>Last Updation Date :</label>
                                    <input class="form-control" type="text" name="updationdate" value="<?php echo $student['UpdationDate']; ?>" readonly />
                                </div>

                                <!-- Profile Status -->
                                <div class="form-group">
                                    <label>Profile Status :</label>
                                    <span style="color: green">Active</span>
                                </div>

                                <!-- First Name -->
                                <div class="form-group">
                                    <label>Enter First Name</label>
                                    <input class="form-control" type="text" name="firstname" value="<?php echo $student['Name']; ?>" required />
                                </div>

                                <!-- Last Name -->
                                <div class="form-group">
                                    <label>Enter Last Name</label>
                                    <input class="form-control" type="text" name="lastname" value="<?php echo $student['LastName']; ?>" required />
                                </div>

                                <!-- Mobile Number -->
                                <div class="form-group">
                                    <label>Mobile Number :</label>
                                    <input class="form-control" type="text" name="mobileno" maxlength="10" value="<?php echo $student['MobileNumber']; ?>" required />
                                </div>

                                <!-- Email (Now Editable) -->
                                <div class="form-group">
                                    <label>Enter Email</label>
                                    <input class="form-control" type="email" name="email" id="email" value="<?php echo $student['email']; ?>" required />
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" name="update" class="btn btn-primary">Update Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
