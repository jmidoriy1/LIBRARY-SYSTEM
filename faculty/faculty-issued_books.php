<?php
include('includes/config.php');
session_start(); 

// Check if the faculty is logged in
if (!isset($_SESSION['faculty_id'])) {
    header("Location: faculty-login.php");
    exit();
}

// Get the logged-in faculty's ID
$faculty_id = $_SESSION['faculty_id'];  // Use correct session variable for faculty
$fac_id2 = isset($_SESSION['faculty_id2']) ? $_SESSION['faculty_id2'] : ''; // Safe check for faculty_id2

// If $fac_id2 is not set, redirect to login page
if (empty($fac_id2)) {
    header("Location: faculty-login.php");
    exit();
}

// Fetch the issued books for the logged-in faculty
$query = "
    SELECT ibd.*, b.BookName, b.BookNumber, ibd.IssueDate, ibd.ReturnDate, ibd.ReturnStatus
    FROM tblissuedbookdetails ibd
    JOIN tblbooks b ON ibd.BookID = b.BookNumber
    WHERE ibd.FacultyID = '$fac_id2'";  // Corrected the comment

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard - Issued Book</title>
    <link rel="stylesheet" href="assets/css/faculty.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Scrollable table body */
        .table-responsive {
            max-height: 400px;  /* You can adjust this value based on your design */
            overflow-y: auto;   /* Enable vertical scrolling */
        }

        table {
            width: 120%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
            background-color: white;
        }

        table thead th {
            background-color: #0a670c;
            color: #dbce15;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #ddd;
            border-right: 2px solid #ddd;
            cursor: pointer;
        }

        table thead th a {
            color: #dbce15;
            text-decoration: none;
        }

        table tbody td {
            padding: 10px;
            border-bottom: 2px solid #ddd;
            border-right: 2px solid #ddd;
        }

        table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        table tbody tr:nth-child(even) {
            background-color: #f1f1f1;
        }

        table tbody tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <!-- MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Manage Issued Books</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Issued Books
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th onclick="sortTable(0, 'asc')">#</th>
                                            <th onclick="sortTable(1, 'asc')">Book Name</th>
                                            <th onclick="sortTable(2, 'asc')">Book Number</th>
                                            <th onclick="sortTable(3, 'asc')">Issued Date</th>
                                            <th onclick="sortTable(4, 'asc')">Return Date</th>
                                            <th onclick="sortTable(5, 'asc')">Borrowing Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Check if any rows were returned
                                        if (mysqli_num_rows($result) > 0) {
                                            $counter = 1; // Row counter
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                // Format the issued and return date
                                                $issuedDate = date("d-m-Y", strtotime($row['IssueDate']));
                                                $returnDate = !empty($row['ReturnDate']) ? date("d-m-Y", strtotime($row['ReturnDate'])) : '';

                                                // Borrowing Status Logic
                                                $status = "Returned"; // Assuming it is returned
                                                if ($row['ReturnStatus'] != 1) {
                                                    $status = "Not Returned";
                                                }

                                                // Display the row in the table
                                                echo "<tr class='odd gradeX'>";
                                                echo "<td class='center'>$counter</td>";
                                                echo "<td class='center'>" . htmlspecialchars($row['BookName']) . "</td>";
                                                echo "<td class='center'>" . htmlspecialchars($row['BookNumber']) . "</td>";
                                                echo "<td class='center'>$issuedDate</td>";
                                                echo "<td class='center'>$returnDate</td>";
                                                echo "<td class='center'>$status</td>";
                                                echo "</tr>";
                                                
                                                $counter++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='center'>No issued books found.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End Advanced Tables -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>
