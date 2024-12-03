<?php
// Include database connection file
include('includes/config.php');

// Process the form submission
if (isset($_POST['create'])) {
    // Get form input data
    $userId = mysqli_real_escape_string($conn, $_POST['user']);
    $bookNumber = mysqli_real_escape_string($conn, $_POST['book']);

    // Check if the user is a student or faculty by checking if StudentID or FacultyID exists
    $userType = 'student';
    $studOrFac = 1;  // Default is student (studorfac = 1 for student)
    
    $query = "SELECT StudentID FROM tblstudents WHERE StudentID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        // If the user is not a student, check if the user is a faculty
        $userType = 'faculty';
        $studOrFac = 0;  // For faculty, studorfac = 0
        $query = "SELECT FacultyID FROM tblfaculty WHERE FacultyID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $stmt->store_result();
    }

    // Insert the issued book details into the database if a valid user is found
    if ($stmt->num_rows > 0) {
        // Prepare the correct query based on user type
        if ($userType === 'student') {
            $query = "INSERT INTO tblissuedbookdetails (StudentID, BookID, studorfac) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $userId, $bookNumber, $studOrFac);
        } else {
            $query = "INSERT INTO tblissuedbookdetails (FacultyID, BookID, studorfac) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $userId, $bookNumber, $studOrFac);
        }

        // Execute and check if successful
        if ($stmt->execute()) {
            // Just set the issuedcopies to 1
            $updateIssuedBooksQuery = "
            UPDATE tblbooks b
            JOIN (
                SELECT 
                    b.id, 
                    LEAST(COUNT(CASE WHEN i.Returnstatus = 0 THEN i.BookID END), b.Copies) AS IssuedCopies
                FROM tblbooks b
                LEFT JOIN tblissuedbookdetails i ON i.BookID = b.BookNumber
                GROUP BY b.id
            ) AS issued_books_data
            ON b.id = issued_books_data.id
            SET b.issuedcopies = 
                CASE
                    WHEN issued_books_data.IssuedCopies >= b.Copies THEN 1
                    WHEN issued_books_data.IssuedCopies < b.Copies THEN 0
                    ELSE b.issuedcopies  -- Keep current value if no condition is met
                END
        ";

            // Execute the update query to set issued copies
            $updateStmt = mysqli_query($conn, $updateIssuedBooksQuery);
            if ($updateStmt) {
                echo "<script>alert('Book Issued Successfully');</script>";
            } else {
                echo "<script>alert('Failed to update book records.');</script>";
            }
        } else {
            echo "<script>alert('Failed to issue book.');</script>";
        }
    } else {
        echo "<script>alert('User not found.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Issue Book</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <style>
        .select2 {
            width: 100%;
        }
        .panel-body {
            padding: 20px;
        }
        select.form-control{
            border-radius: 4px;
            border: 2px solid #0a670c;
            padding: 10px;
            margin-top: 5px;
            width: 100% !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Issue Book</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Issue Book Info
                        </div>
                        <div class="panel-body">
                            <form method="POST">
                                <!-- User Select Dropdown (Student or Faculty) -->
                                <div class="form-group">
                                    <label>Select User (Student or Faculty)</label>
                                    <select class="form-control select2" name="user" required>
                                        <option value="">Select User</option>
                                        <?php
                                        // Fetch students and faculty with status 1 (active)
                                        $studentQuery = "SELECT StudentID, FullName FROM tblstudents WHERE status = 1";
                                        $facultyQuery = "SELECT FacultyID, FullName FROM tblfaculty WHERE status = 1";
                                        
                                        $resultStudent = mysqli_query($conn, $studentQuery);
                                        while ($student = mysqli_fetch_assoc($resultStudent)) {
                                            echo '<option value="' . htmlspecialchars($student['StudentID']) . '">' . htmlspecialchars($student['FullName']) . ' - ' . htmlspecialchars($student['StudentID']) . ' (Student)</option>';
                                        }

                                        $resultFaculty = mysqli_query($conn, $facultyQuery);
                                        while ($faculty = mysqli_fetch_assoc($resultFaculty)) {
                                            echo '<option value="' . htmlspecialchars($faculty['FacultyID']) . '">' . htmlspecialchars($faculty['FullName']) . ' - ' . htmlspecialchars($faculty['FacultyID']) . ' (Faculty)</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- Book Select Dropdown -->
                                <div class="form-group">
                                    <label>Select Book</label>
                                    <select class="form-control select2" name="book" required>
                                        <option value="">Select Book</option>
                                        <?php
                                        // Fetch books with the updated query to exclude books with 0 available copies and ensure archive = 1
                                        $searchTerm = ''; // Replace with actual search term if needed
                                        $sortBy = 'BookName'; // Or replace with the column you want to sort by
                                        $order = 'ASC'; // Or replace with 'DESC' for descending order

                                        $bookQuery = "
                                            SELECT 
                                                b.id, 
                                                b.BookName, 
                                                b.Copies, 
                                                b.datepublished,
                                                COALESCE(LEAST(COUNT(CASE WHEN i.Returnstatus = 0 THEN i.BookID END), b.Copies), 0) AS IssuedCopies, 
                                                b.BookNumber, 
                                                a.AuthorName, 
                                                c.CategoryName, 
                                                b.archive
                                            FROM tblbooks b
                                            JOIN tblauthors a ON b.AuthorId = a.id 
                                            JOIN tblcategory c ON b.CatId = c.id
                                            LEFT JOIN tblissuedbookdetails i ON i.BookID = b.BookNumber
                                            WHERE 
                                                b.archive = 1 AND  -- Ensure only active books (archive = 1) are included
                                                (b.BookName LIKE '%$searchTerm%' 
                                                OR a.AuthorName LIKE '%$searchTerm%'
                                                OR c.CategoryName LIKE '%$searchTerm%')
                                            GROUP BY 
                                                b.id, b.BookName, b.Copies, b.BookNumber, a.AuthorName, c.CategoryName, b.archive
                                            HAVING 
                                                LEAST(COUNT(CASE WHEN i.Returnstatus = 0 THEN i.BookID END), b.Copies) < b.Copies
                                            ORDER BY $sortBy $order
                                        ";

                                        $resultBooks = mysqli_query($conn, $bookQuery);
                                        while ($book = mysqli_fetch_assoc($resultBooks)) {
                                            echo '<option value="' . htmlspecialchars($book['BookNumber']) . '">' . htmlspecialchars($book['BookNumber'] . ' - ' . $book['BookName']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <button type="submit" name="create" class="btn btn-info">Issue Book</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
