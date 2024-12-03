<?php
// Include database connection file
include('includes/config.php');

// Fetch existing issue details if issue_id is set
if (isset($_GET['issue_id'])) {
    $issueId = mysqli_real_escape_string($conn, $_GET['issue_id']);
    
    $query = "
        SELECT ib.id, ib.facultyID, ib.BookID, ib.IssueDate, ib.ReturnDate, ib.ReturnStatus, s.FullName, b.BookName 
        FROM tblissuedbookdetails ib 
        JOIN tblfaculty s ON ib.facultyID = s.facultyID 
        JOIN tblbooks b ON ib.BookID = b.BookNumber 
        WHERE ib.id = '$issueId'
    ";
    $result = mysqli_query($conn, $query);
    $issueDetails = mysqli_fetch_assoc($result);
}

// Handle form submission for update
if (isset($_POST['update'])) {
    $returnDate = mysqli_real_escape_string($conn, $_POST['return_date']) . ' ' . mysqli_real_escape_string($conn, $_POST['return_time']);
    $returnStatus = mysqli_real_escape_string($conn, $_POST['return_status']);
    
    $updateQuery = "UPDATE tblissuedbookdetails SET ReturnDate=?, ReturnStatus=? WHERE id=?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssi", $returnDate, $returnStatus, $issueId);
    
    if ($stmt->execute()) {
        echo "<script>alert('Update Successful'); window.location='manage-issue-fac.php';</script>";
    } else {    
        echo "<script>alert('Update Failed');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Edit Issue(Faculty)</title>
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
                                    <label>Faculty</label>
                                    <select class="form-control" name="faculty" required>
                                        <option value="<?php echo htmlspecialchars($issueDetails['facultyID']); ?>">
                                            <?php echo htmlspecialchars($issueDetails['facultyID'] . ' - ' . $issueDetails['FullName']); ?>
                                        </option>
                                        <?php
                                        // Fetch students, excluding the current one
                                        $studentQuery = "SELECT FacultyID, FullName FROM tblfaculty WHERE FacultyID != '" . htmlspecialchars($issueDetails['StudentID']) . "'";
                                        $studentResult = mysqli_query($conn, $studentQuery);
                                        while ($faculty = mysqli_fetch_assoc($studentResult)) {
                                            echo '<option value="' . htmlspecialchars($faculty['FacultyID']) . '">' . htmlspecialchars($faculty['FacultyID'] . ' - ' . $faculty['FullName']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Book</label>
                                    <select class="form-control" name="book" required>
                                        <option value="<?php echo htmlspecialchars($issueDetails['BookID']); ?>">
                                            <?php echo htmlspecialchars($issueDetails['BookID'] . ' - ' . $issueDetails['BookName']); ?>
                                        </option>
                                        <?php
                                        // Fetch books, excluding the current one
                                        $bookQuery = "SELECT BookNumber, BookName FROM tblbooks WHERE BookNumber != '" . htmlspecialchars($issueDetails['BookID']) . "'";
                                        $bookResult = mysqli_query($conn, $bookQuery);
                                        while ($book = mysqli_fetch_assoc($bookResult)) {
                                            echo '<option value="' . htmlspecialchars($book['BookNumber']) . '">' . htmlspecialchars($book['BookNumber'] . ' - ' . $book['BookName']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Return Date</label>
                                    <input type="date" class="form-control" name="return_date" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Return Time</label>
                                    <input type="time" class="form-control" name="return_time" value="<?php echo date('H:i'); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Return Status</label>
                                    <select class="form-control" name="return_status" required>
                                    <option value="1" <?php echo ($issueDetails['ReturnStatus'] == 1) ? 'selected' : ''; ?>>Returned</option>
                                    <option value="0" <?php echo ($issueDetails['ReturnStatus'] == 0) ? 'selected' : ''; ?>>Not Returned Yet</option>                                    </select>
                                </div>
                                <button type="submit" name="update" class="btn btn-info">Return Book</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
