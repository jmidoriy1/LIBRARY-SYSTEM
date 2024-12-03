<?php
// Start session at the top of the file, before any output
session_start();

// Include the database connection
include('includes/config.php');

// Check if the user is logged in by verifying the session
if (!isset($_SESSION['faculty_id'])) {
    // If not logged in, redirect to login page
    header("Location: faculty-login.php");
    exit(); // Always call exit after header redirection
}

// Get faculty ID from session
$faculty_id = $_SESSION['faculty_id'];

// Fetch faculty data from database
$query = "SELECT * FROM tblfaculty WHERE id = ? AND status = 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $faculty_id); // Using 'i' for integer as FacultyID is likely an integer.
$stmt->execute();
$result = $stmt->get_result();

// Check if a valid faculty record was found
if ($result->num_rows > 0) {
    $faculty = $result->fetch_assoc();
} else {
    // Handle case where no valid record is found (this shouldn't happen in a valid session)
    echo "Faculty not found!";
    exit();
}

// Handle form submission to update profile
if (isset($_POST['update'])) {
    // Sanitize and capture input values
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $mobileno = $_POST['mobileno'];
    $email = $_POST['email']; // Capture the email value

    // Combine first name and last name to update FullName
    $fullName = $firstName . ' ' . $lastName;

    // Update faculty details in the database
    $update_query = "UPDATE tblfaculty SET name = ?, LastName = ?, FullName = ?, MobileNumber = ?, email = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssssi", $firstName, $lastName, $fullName, $mobileno, $email, $faculty_id); // Bind the parameters.

    if ($update_stmt->execute()) {
        // Return a success response in JSON format
        echo json_encode([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'firstName' => $firstName,
            'lastName' => $lastName,
            'mobileNo' => $mobileno,
            'email' => $email // Return the updated email as well
        ]);
    } else {
        // Return an error response
        echo json_encode([
            'status' => 'error',
            'message' => 'An error occurred while updating the profile.'
        ]);
    }
    exit(); // Ensure the script ends here after processing the update.
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard - Profile</title>
    <link rel="stylesheet" href="assets/css/faculty.css">
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
            var emailPattern = /^[a-zA-Z0-9._%+-]+@(gmail\.com|outlook\.com|yahoo\.com|yandex\.ru)$/; // Allows Gmail, Outlook, Yahoo, and Yandex
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
                        email: email // Send the email value for updating
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            // Update form values with the new data
                            $("input[name='firstname']").val(response.firstName);
                            $("input[name='lastname']").val(response.lastName);
                            $("input[name='mobileno']").val(response.mobileNo);
                            $("input[name='email']").val(response.email); // Update the email field with new data
                            alert(response.message);
                        } else {
                            alert(response.message);
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
                                <!-- Faculty ID -->
                                <div class="form-group">
                                    <label>Faculty ID :</label>
                                    <input class="form-control" type="text" name="facultyid" value="<?php echo $faculty['FacultyID']; ?>" readonly />
                                </div>

                                <!-- Registration Date -->
                                <div class="form-group">
                                    <label>Reg Date :</label>
                                    <input class="form-control" type="text" name="regdate" value="<?php echo $faculty['RegDate']; ?>" readonly />
                                </div>

                                <!-- Last Update Date -->
                                <div class="form-group">
                                    <label>Last Updation Date :</label>
                                    <input class="form-control" type="text" name="updationdate" value="<?php echo $faculty['UpdationDate']; ?>" readonly />
                                </div>

                                <!-- Profile Status -->
                                <div class="form-group">
                                    <label>Profile Status :</label>
                                    <span style="color: green">Active</span>
                                </div>

                                <!-- First Name -->
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input class="form-control" type="text" name="firstname" value="<?php echo $faculty['name']; ?>" required />
                                </div>

                                <!-- Last Name -->
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input class="form-control" type="text" name="lastname" value="<?php echo $faculty['LastName']; ?>" required />
                                </div>

                                <!-- Mobile Number -->
                                <div class="form-group">
                                    <label>Mobile Number :</label>
                                    <input class="form-control" type="text" name="mobileno" maxlength="10" value="<?php echo $faculty['MobileNumber']; ?>" required />
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" type="email" name="email" id="email" value="<?php echo $faculty['email']; ?>" required />
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
