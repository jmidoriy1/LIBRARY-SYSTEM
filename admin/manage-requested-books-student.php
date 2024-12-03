<?php
// Include database connection file
include('includes/config.php');

// Define sorting parameters
$sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 's.StudentID'; // Default sorting by Student ID
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Fetch requested books that match students, book numbers, categories, authors, and issue status
$query = "
    SELECT rbd.*, s.FullName, b.BookName, c.CategoryName, a.AuthorName, b.BookNumber, b.issuedcopies 
    FROM tblrequestedbookdetails rbd 
    JOIN tblstudents s ON rbd.studfacid = s.StudentID 
    JOIN tblbooks b ON rbd.BookNumber = b.BookNumber 
    JOIN tblcategory c ON rbd.CategoryName = c.CategoryName 
    JOIN tblauthors a ON rbd.AuthorName = a.AuthorName 
    WHERE rbd.name = s.FullName 
    ORDER BY $sortBy $order
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Requested Books</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/tables.css">
    <link
        href="https://fonts.googleapis.com/css2?family=K2D:wght@100;200;300;400;500;600;700;800&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome (only one version) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/tables.css">

    <!-- Bootstrap JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (only if needed) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Manage Requested Books</h4>
                    <table class='table'>
                        <thead>
                            <tr>
                                <th><a href="?sortBy=s.StudentID&order=<?php echo ($sortBy == 's.StudentID' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Student ID</a></th>
                                <th><a href="?sortBy=s.FullName&order=<?php echo ($sortBy == 's.FullName' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Student Name</a></th>
                                <th><a href="?sortBy=b.BookName&order=<?php echo ($sortBy == 'b.BookName' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Book Name</a></th>
                                <th><a href="?sortBy=c.CategoryName&order=<?php echo ($sortBy == 'c.CategoryName' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Category Name</a></th>
                                <th><a href="?sortBy=a.AuthorName&order=<?php echo ($sortBy == 'a.AuthorName' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Author Name</a></th>
                                <th><a href="?sortBy=b.BookNumber&order=<?php echo ($sortBy == 'b.BookNumber' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Book Number</a></th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['studfacid']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['FullName']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['BookName']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['CategoryName']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['AuthorName']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['BookNumber']) . "</td>";

                                if ($row['issuedcopies'] == 1) {
                                    echo "<td><button class='btn btn-danger' disabled>Already Issued</button></td>";
                                } else {
                                    // Otherwise, show the "Issue" button
                                    echo "<td><a href='requested-issue-book.php?StudentID=" . htmlspecialchars($row['studfacid']) . "' class='btn btn-primary'>Issue</a></td>";
                                }

                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>Error: " . mysqli_error($conn) . "</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
