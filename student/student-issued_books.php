<?php
include('includes/config.php');
session_start(); 

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student-login.php");
    exit();
}

// Get the logged-in student's ID
$student_id = $_SESSION['student_id'];

// Fetch the issued books for the logged-in student, limited to 6 results
$query = "
    SELECT ibd.*, b.BookName, b.BookNumber, ibd.IssueDate, ibd.ReturnDate, ibd.ReturnStatus
    FROM tblissuedbookdetails ibd
    JOIN tblbooks b ON ibd.BookID = b.BookNumber
    WHERE ibd.StudentID = '$student_id'
    
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Issued Books</title>

    <!-- Google Fonts Preconnect and Stylesheet -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@100;200;300;400;500;600;700;800&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="assets/css/student.css">

    <!-- Bootstrap JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        // Function to sort the table
        function sortTable(n, order) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("dataTables-example");
            switching = true;
            dir = order === 'asc' ? "asc" : "desc"; // Set the sorting direction

            // Keep looping until no switching is done
            while (switching) {
                switching = false;
                rows = table.rows;

                // Loop through all table rows (except the first, which contains table headers)
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];

                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }

                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++; // If a switch has been done, increase switchcount
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>

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
                                                
                                                // Check if ReturnDate is empty or NULL
                                                $returnDate = !empty($row['ReturnDate']) ? date("d-m-Y", strtotime($row['ReturnDate'])) : '';

                                                // Borrowing Status Logic - based on Issued Date
                                                $status = "";
                                                $daysExceeded = 0;
                                                $daysBorrowed = 0;

                                                // Calculate the number of days borrowed from the Issue Date
                                                $currentDate = time(); // Current date as timestamp
                                                $issueTimestamp = strtotime($row['IssueDate']);
                                                
                                                // Calculate Days Borrowed (difference between today and issue date)
                                                $daysBorrowed = floor(($currentDate - $issueTimestamp) / (60 * 60 * 24));

                                                // Check the ReturnStatus and calculate borrowing status
                                                if ($row['ReturnStatus'] == 1) {
                                                    $status = "Returned"; // Book has been returned
                                                } else {
                                                    // Book is not returned, calculate overdue status based on issue date
                                                    if ($daysBorrowed > 14) {
                                                        $status = "Exceeded " . ($daysBorrowed - 14) . " Days"; // Overdue message
                                                    } elseif ($daysBorrowed <= 1) {
                                                        $status = "Borrowed Today"; // Borrowed today
                                                    } elseif ($daysBorrowed == 1) {
                                                        $status = "Borrowed 1 Day Ago"; // Borrowed 1 day ago
                                                    } else {
                                                        $status = "Borrowed $daysBorrowed Days Ago"; // Borrowed n days ago
                                                    }
                                                }

                                                // Display the row in the table
                                                echo "<tr class='odd gradeX'>";
                                                echo "<td class='center'>$counter</td>";
                                                echo "<td class='center'>" . htmlspecialchars($row['BookName']) . "</td>";
                                                echo "<td class='center'>" . htmlspecialchars($row['BookNumber']) . "</td>";
                                                echo "<td class='center'>$issuedDate</td>";
                                                echo "<td class='center'>$returnDate</td>"; // Blank if ReturnDate is empty or NULL
                                                echo "<td class='center'>$status</td>"; // Display the borrowing status
                                                echo "</tr>";
                                                
                                                $counter++;
                                            }
                                        } else {
                                            // If no books are issued, display a message
                                            echo "<tr><td colspan='6' class='center'>No issued books found.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>
