<?php
// Start the session
session_start();

// Include the database configuration
include('includes/config.php');

// Check if the user is logged in
if (!isset($_SESSION['faculty_id'])) {
    // If not logged in, redirect to the login page
    echo "<script>
            alert('You must be logged in to request a book.');
            window.location.href = 'faculty-login.php';
          </script>";
    exit;
}

// Get the faculty ID from session
$faculty_id = $_SESSION['faculty_id'];

// Check if the required data is posted
if (isset($_POST['BookNumber']) && isset($_POST['BookName']) && isset($_POST['AuthorName']) && isset($_POST['CategoryName'])) {
    // Get the form data
    $bookNumber = $_POST['BookNumber'];
    $bookName = $_POST['BookName'];
    $authorName = $_POST['AuthorName'];
    $categoryName = $_POST['CategoryName'];

    // Check if the faculty exists in tblfaculty by ID
    $faculty_check_query = "SELECT FacultyID, Fullname FROM tblfaculty WHERE ID = '$faculty_id'";
    $faculty_check_result = mysqli_query($conn, $faculty_check_query);

    // If the faculty ID is valid, proceed with the insert
    if ($faculty_check_result && mysqli_num_rows($faculty_check_result) > 0) {
        // Fetch the FacultyID and Fullname from the result
        $faculty_data = mysqli_fetch_assoc($faculty_check_result);
        $facultyID = $faculty_data['FacultyID'];
        $facultyFullname = $faculty_data['Fullname'];

        // Proceed to insert the request into the database
        $query = "
            INSERT INTO tblrequestedbookdetails (studfacid, BookName, AuthorName, CategoryName, BookNumber, entity)
            VALUES ('$facultyID', '$bookName', '$authorName', '$categoryName', '$bookNumber', 0)
        ";

        if (mysqli_query($conn, $query)) {
            // Now that the record is inserted, let's update the 'Name' field in tblrequestedbookdetails
            // Check if the studfacid matches the FacultyID and update the Name
            $update_query = "
                UPDATE tblrequestedbookdetails 
                SET Name = '$facultyFullname' 
                WHERE studfacid = '$facultyID' AND BookNumber = '$bookNumber'
            ";

            if (mysqli_query($conn, $update_query)) {
                // If the update is successful, show the success message
                echo "<script>
                        alert('Your book request was successful!');
                        window.location.href = 'faculty-request_book.php'; // Redirect to the faculty book request page
                      </script>";
            } else {
                // If there was an error updating the Name field
                echo "<script>
                        alert('There was an error updating the Name field in your request. Please try again.');
                        window.location.href = 'faculty-request_book.php';
                      </script>";
            }
        } else {
            // If there is an error inserting the request, show an error message
            echo "<script>
                    alert('There was an error submitting your request. Please try again.');
                  </script>";
        }
    } else {
        // If the faculty does not exist in tblfaculty
        echo "<script>
                alert('Faculty ID is invalid. Please log in again.');
                window.location.href = 'faculty-login.php';
              </script>";
    }
} else {
    // If required data is missing, show an error message
    echo "<script>
            alert('Missing book details. Please try again.');
            window.location.href = 'faculty-request_book.php';
          </script>";
}
?>
