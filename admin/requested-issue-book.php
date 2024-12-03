<?php
// Include database connection script
include('includes/config.php');

// Fetch existing request details if StudentID is set
if (isset($_GET['StudentID'])) {
    $StudentID = mysqli_real_escape_string($conn, $_GET['StudentID']);
    
    $query = "
        SELECT rbd.*, s.FullName, b.BookName 
        FROM tblrequestedbookdetails rbd 
        JOIN tblstudents s ON rbd.studfacid = s.StudentID 
        JOIN tblbooks b ON rbd.BookNumber = b.BookNumber 
        WHERE rbd.studfacid = '$StudentID'
    ";
    $result = mysqli_query($conn, $query);
    $requestDetails = mysqli_fetch_assoc($result);
}

// Handle form submission for issuing a new book
if (isset($_POST['update'])) {
    // Retrieve the book details
    $bookNumber = mysqli_real_escape_string($conn, $_POST['book']);
    $studentID = mysqli_real_escape_string($conn, $_POST['student']);
    
    // Get the current date for IssueDate
    $issueDate = date('Y-m-d H:i:s');

    // Insert into tblissuedbookdetails
    $insertQuery = "
        INSERT INTO tblissuedbookdetails (StudentID, BookID, IssueDate, ReturnStatus) 
        SELECT StudentID, BookNumber, '$issueDate', '0'
        FROM tblrequestedbookdetails 
        WHERE StudentID = ? AND BookNumber = ?
    ";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("is", $studentID, $bookNumber);
    
    if ($stmt->execute()) {
        // Optionally delete from tblrequestedbookdetails
        $deleteQuery = "DELETE FROM tblrequestedbookdetails WHERE StudentID=? AND BookNumber=?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("is", $studentID, $bookNumber);
        $deleteStmt->execute();
        $deleteStmt->close();
        
        echo "<script>alert('Book Issued Successfully'); window.location='manage-requested-books.php';</script>";
    } else {    
        echo "<script>alert('Issuing Book Failed');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Requested Issue Book</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@100;200;300;400;500;600;700;800&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">EDIT ISSUED BOOK</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">Issued Book Info</div>
                        <div class="panel-body">
                            <form method="post">
                                <div class="form-group">
                                    <label>Student</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($requestDetails['studfacid']) . ' - ' . htmlspecialchars($requestDetails['FullName']); ?>" readonly>
                                    <input type="hidden" name="student" value="<?php echo htmlspecialchars($requestDetails['studfacid']); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Book</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($requestDetails['BookNumber']) . ' - ' . htmlspecialchars($requestDetails['BookName']); ?>" readonly>
                                    <input type="hidden" name="book" value="<?php echo htmlspecialchars($requestDetails['BookNumber']); ?>">
                                </div>
                                <button type="submit" name="update" class="btn btn-info">Issue New Book</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>