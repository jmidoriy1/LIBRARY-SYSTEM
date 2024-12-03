<?php
// Start the session at the top of the page
session_start(); 

// Include database connection script
include('includes/config.php');

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    // If not logged in, redirect to login page with a message
    echo "<p>You must be logged in to request a book.</p>";
    exit;
}

// Check if the necessary data has been passed via POST
if (isset($_POST['BookNumber']) && isset($_POST['BookName']) && isset($_POST['AuthorName']) && isset($_POST['CategoryName'])) {
    // Retrieve book details from POST
    $bookNumber = $_POST['BookNumber'];
    $bookName = $_POST['BookName'];
    $authorName = $_POST['AuthorName'];
    $categoryName = $_POST['CategoryName'];

    // Retrieve student details from session
    $studentID = $_SESSION['student_id'];

    // Prepare SQL Query to fetch the FullName from tblstudents based on the studentID
    $queryStudent = "
        SELECT FullName FROM tblstudents WHERE StudentID = '$studentID'
    ";

    // Execute the query to get the student's FullName
    $resultStudent = mysqli_query($conn, $queryStudent);
    if ($resultStudent && mysqli_num_rows($resultStudent) > 0) {
        // Fetch the FullName
        $row = mysqli_fetch_assoc($resultStudent);
        $studentName = $row['FullName'];
    } else {
        // If no student found, display an error and exit
        echo "<p>Error: Student details not found. Please try again.</p>";
        exit;
    }

    // Prepare SQL Query to insert the request into the tblrequestedbookdetails table
    $query = "
        INSERT INTO tblrequestedbookdetails (studfacid, Name, BookName, CategoryName, AuthorName, BookNumber) 
        VALUES ('$studentID', '$studentName', '$bookName', '$categoryName', '$authorName', '$bookNumber')
    ";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        // Success - Display a pop-up message using JavaScript
        echo "<script>
                alert('Your Book Request Was Successful');
                window.location.href = 'student-request_book.php'; // Redirect back to the book list page
              </script>";
    } else {
        // Error - Display an error message
        echo "<script>
                alert('There was an error submitting your request. Please try again.');
              </script>";
    }
} else {
    echo "<script>
            alert('Missing book details. Please try again.');
            window.location.href = 'request-a-book.php'; // Redirect back to the book list page
          </script>";
}
?>
