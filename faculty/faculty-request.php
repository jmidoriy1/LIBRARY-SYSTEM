<?php
// Start the session at the top of the page
session_start();

// Include database connection script
include('includes/config.php');

// Check if the user is logged in (assuming faculty are logged in with 'faculty_id' in the session)
if (!isset($_SESSION['faculty_id'])) {
    // If not logged in, redirect to login page with a message
    echo "<p>You must be logged in to request a book.</p>";
    exit;
}

// Get the faculty ID from the session
$faculty_id = $_SESSION['faculty_id'];
$fac_id2 = $_SESSION['fac_id2'];

// Check if the necessary data has been passed via the URL
if (isset($_GET['BookNumber']) && isset($_GET['BookName']) && isset($_GET['AuthorName']) && isset($_GET['CategoryName'])) {
    $bookNumber = $_GET['BookNumber'];
    $bookName = $_GET['BookName'];
    $authorName = $_GET['AuthorName'];
    $categoryName = $_GET['CategoryName'];
} else {
    // If no data is passed, show an error message
    echo "<p>No book details were passed. Please select a book to request.</p>";
    exit;
}

// Query to check if the faculty has already requested this book
$requestCheckQuery = "
    SELECT * FROM tblrequestedbookdetails rbd
    JOIN tblfaculty b ON b.FacultyID = rbd.studfacid
    WHERE rbd.studfacid = '$fac_id2' AND rbd.BookNumber = '$bookNumber'
";

$requestResult = mysqli_query($conn, $requestCheckQuery);

// Check if the faculty has already requested the book
$bookAlreadyRequested = mysqli_num_rows($requestResult) > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Book Request</title>
    <link rel="stylesheet" href="assets/css/faculty.css">
</head>
<body>
    <!-- Header section -->
    <?php include('includes/header.php'); ?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Faculty Book Request</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Request Book: <?php echo $bookName; ?>
                        </div>
                        <div class="panel-body">
                            <!-- Display the book details -->
                            <p><strong>Book Name:</strong> <?php echo $bookName; ?></p>
                            <p><strong>Category:</strong> <?php echo $categoryName; ?></p>
                            <p><strong>Author:</strong> <?php echo $authorName; ?></p>
                            <p><strong>Book Number:</strong> <?php echo $bookNumber; ?></p>

                            <?php if ($bookAlreadyRequested): ?>
                                <!-- Message if the book has already been requested -->
                                <p class="text-danger">The book has already been requested.</p>
                                <!-- Disable the button -->
                                <button class="btn btn-secondary" disabled>Request Already Submitted</button>
                            <?php else: ?>
                                <!-- Form to request the book -->
                                <form method="POST" action="submit-request.php">
                                    <input type="hidden" name="BookNumber" value="<?php echo $bookNumber; ?>">
                                    <input type="hidden" name="BookName" value="<?php echo $bookName; ?>">
                                    <input type="hidden" name="AuthorName" value="<?php echo $authorName; ?>">
                                    <input type="hidden" name="CategoryName" value="<?php echo $categoryName; ?>">

                                    <button type="submit" class="btn btn-success">Submit Request</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
