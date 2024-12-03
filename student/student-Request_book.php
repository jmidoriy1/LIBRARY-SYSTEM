<?php
include('includes/config.php');

// First, run the update query to sync issued copies
$updateIssuedBooksQuery = "
UPDATE tblbooks b
JOIN (
    SELECT 
        b.id, 
        LEAST(COUNT(CASE WHEN i.ReturnStatus = 0 THEN i.BookID END), b.Copies) AS IssuedCopies
    FROM tblbooks b
    LEFT JOIN tblissuedbookdetails i ON i.BookID = b.BookNumber
    GROUP BY b.id
) AS issued_books_data
ON b.id = issued_books_data.id
SET b.issuedcopies = 
    CASE
        WHEN issued_books_data.IssuedCopies = b.Copies THEN 1
        WHEN issued_books_data.IssuedCopies != b.Copies THEN 0
        ELSE b.issuedcopies  -- Keep current value if no condition is met
    END
";

// Execute the update query to refresh issued copies count
mysqli_query($conn, $updateIssuedBooksQuery);

// SQL Query to fetch data from multiple tables using JOIN and limit the results to 6 books
$query = "
    SELECT tblbooks.BookName, tblcategory.CategoryName, tblauthors.AuthorName, tblbooks.BookNumber, tblbooks.issuedcopies
    FROM tblbooks
    INNER JOIN tblcategory ON tblbooks.CatID = tblcategory.id
    INNER JOIN tblauthors ON tblbooks.AuthorID = tblauthors.id
    WHERE tblbooks.archive = 1 AND tblbooks.issuedcopies = 0

";

// Execute the query and fetch the results
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Request a Book</title>

    <!-- Google Fonts Preconnect and Stylesheet -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@100;200;300;400;500;600;700;800&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">

    <!-- Font Awesome (only one version) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="assets/css/student.css">

    <!-- Bootstrap JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (only if needed) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
            background-color: white;
        }

        /* Table Header Styling */
        table thead th {
            background-color: #0a670c;
            color: #dbce15;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #ddd;
            border-right: 2px solid #ddd;
        }

        table thead th a {
            color: #dbce15;
            text-decoration: none;
        }

        /* Table Row Styling */
        table tbody td {
            padding: 10px;
            border-bottom: 2px solid #ddd;
            border-right: 2px solid #ddd;
        }

        /* Alternate Row Colors */
        table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        table tbody tr:nth-child(even) {
            background-color: #f1f1f1;
        }

        /* Hover Effect on Table Rows */
        table tbody tr:hover {
            background-color: #ddd;
        }

        /* Button Styling */
        .btn {
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            color: #fff;
        }

        .btn-warning .btn-primary {
            background-color: #dbce15;
            color: #0a670c;
            font-weight: 600;
            border: none;
        }

        .btn-primary {
            background-color: #dbce15;
            color: #0a670c;
            font-weight: 600;
            border: none;
        }

        .btn-danger {
            background-color: #d9534f;
            font-weight: 600;
            border: none;
        }

        /* Disabled Button Styling */
        .btn-disabled {
            background-color: #ddd;
            color: #777;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Search Box Styling */
        .search-box input {
            width: 300px;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }

        /* Scrollable Table Body */
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }

        /* Responsive Table */
        @media screen and (max-width: 768px) {
            table {
                font-size: 14px;
            }

            .search-box input {
                width: 100%;
            }
        }
    </style>

    <script>
        // Function to filter table rows based on search input
        function filterTable() {
            const searchQuery = document.getElementById("searchInput").value.toLowerCase();
            const rows = document.getElementById("dataTables-example").getElementsByTagName("tr");

            // Loop through table rows and hide those that do not match the search query
            for (let i = 1; i < rows.length; i++) {
                let row = rows[i];
                let columns = row.getElementsByTagName("td");
                let found = false;

                // Loop through columns to check if any text matches the search query
                for (let j = 0; j < columns.length; j++) {
                    if (columns[j].textContent.toLowerCase().includes(searchQuery)) {
                        found = true;
                        break;
                    }
                }

                // Show or hide the row based on the search match
                if (found) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
        }
    </script>
</head>

<body>
    <!-- MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Request a Book</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Available Books
                        </div>
                        <div class="panel-body">
                            <!-- Search Box -->
                            <div class="search-box">
                                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search for books...">
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Book Name</th>
                                            <th>Category</th>
                                            <th>Author Name</th>
                                            <th>Book Number</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $counter = 1; // Initialize counter

                                        // Check if query has results
                                        if (mysqli_num_rows($result) > 0) {
                                            // Output data of each row
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                // Disable button if issuedcopies = 1
                                                $isDisabled = ($row['issuedcopies'] == 1) ? 'btn-disabled' : '';

                                                echo "<tr>";
                                                echo "<td class='center'>{$counter}</td>";  // Display the counter instead of BookNumber
                                                echo "<td class='center'>{$row['BookName']}</td>";
                                                echo "<td class='center'>{$row['CategoryName']}</td>";
                                                echo "<td class='center'>{$row['AuthorName']}</td>";
                                                echo "<td class='center'>{$row['BookNumber']}</td>";
                                                echo "<td class='center'>
                                                        <a href='student-request.php?BookNumber={$row['BookNumber']}&BookName={$row['BookName']}&AuthorName={$row['AuthorName']}&CategoryName={$row['CategoryName']}'>
                                                        <button class='btn btn-primary {$isDisabled}' name='submit' id='submit' type='submit' ".($isDisabled ? 'disabled' : '').">
                                                            <i class='fa fa-edit'></i> Request
                                                        </button>
                                                        </a>
                                                      </td>";
                                                echo "</tr>";

                                                $counter++;  // Increment the counter
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='center'>No books available</td></tr>";
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
