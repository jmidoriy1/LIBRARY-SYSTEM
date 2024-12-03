<?php
include('includes/config.php');

// Get sorting and search parameters
$sortColumn = isset($_POST['sortColumn']) ? $_POST['sortColumn'] : 'ib.IssueDate';
$sortOrder = isset($_POST['sortOrder']) ? $_POST['sortOrder'] : 'ASC';
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';

// Query to get the issued books details with BorrowingStatus
$query = "
    SELECT ib.id, ib.IssueDate, ib.ReturnDate, ib.ReturnStatus, s.StudentID, s.FullName, b.BookNumber, b.BookName,
        DATEDIFF(CURDATE(), ib.IssueDate) AS DaysBorrowed,
        CASE 
            WHEN ib.ReturnStatus = 0 AND DATEDIFF(CURDATE(), ib.IssueDate) > 14 THEN DATEDIFF(CURDATE(), ib.IssueDate) - 14
            ELSE 0
        END AS DaysExceeded,
        CASE
            WHEN ib.ReturnStatus = 1 THEN 'Returned'
            WHEN DATEDIFF(CURDATE(), ib.IssueDate) > 14 THEN CONCAT('Exceeded ', DATEDIFF(CURDATE(), ib.IssueDate) - 14, ' Days')
            WHEN DATEDIFF(CURDATE(), ib.IssueDate) = 0 THEN 'Borrowed Today'
            WHEN DATEDIFF(CURDATE(), ib.IssueDate) = 1 THEN 'Borrowed 1 Day Ago'
            ELSE CONCAT('Borrowed ', DATEDIFF(CURDATE(), ib.IssueDate), ' Days Ago')
        END AS BorrowingStatus
    FROM tblissuedbookdetails ib 
    JOIN tblstudents s ON ib.StudentID = s.StudentID
    JOIN tblbooks b ON ib.BookID = b.BookNumber
    WHERE (s.FullName LIKE '%$searchTerm%' 
        OR b.BookName LIKE '%$searchTerm%'
        OR s.StudentID LIKE '%$searchTerm%'
        OR ib.IssueDate LIKE '%$searchTerm%'
        OR b.BookNumber LIKE '%$searchTerm%')
        AND ib.studorfac = 1
   ORDER BY 
        CASE 
            WHEN BorrowingStatus LIKE 'Exceeded%' THEN 1 
            WHEN BorrowingStatus LIKE 'Borrowed%' THEN 2 
            WHEN BorrowingStatus LIKE 'Returned' THEN 3  
            ELSE 4  
        END $sortOrder, 
        $sortColumn $sortOrder
";
$result = mysqli_query($conn, $query);

mysqli_data_seek($result, 0);

// Check if search term is set for AJAX
if (isset($_POST['searchTerm'])) {
    $response = "";
    $cnt = 1; 
    while ($row = mysqli_fetch_assoc($result)) {
        $response .= "<tr>
            <td></td> <!-- Leave empty for the counter -->
            <td>" . htmlspecialchars($row['StudentID']) . "</td>
            <td>" . htmlspecialchars($row['FullName']) . "</td>
            <td>" . htmlspecialchars($row['BookNumber']) . "</td>
            <td>" . htmlspecialchars($row['BookName']) . "</td>
            <td>" . htmlspecialchars($row['IssueDate']) . "</td>
            <td>" . htmlspecialchars($row['ReturnDate']) . "</td>
            <td>" . htmlspecialchars($row['BorrowingStatus']) . "</td> <!-- Borrowing Status Column -->
            <td>
                <a href='edit_issue-stud.php?issue_id=" . htmlspecialchars($row['id']) . "' class='btn btn-primary btn-sm'>Edit</a>
            </td>
        </tr>";
        $cnt++;
    }
    echo $response;
    exit; // End script for AJAX requests
}

// Query to update `issuedcopies` in tblbooks
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

// Execute the update query directly
mysqli_query($conn, $updateIssuedBooksQuery);

// You can continue with the rest of your code
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Issue(Student)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@100;200;300;400;500;600;700;800&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/tables.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        th {
            cursor: pointer;
            color: blue; /* Change header color to blue */
        }
        .sort-icon {
            margin-left: 5px; /* Space between text and icon */
        }
        /* Scrollable table container */
        .table-container {
            max-height: 500px; /* Adjust based on your needs */
            overflow-y: auto;
            display: block;
        }
    </style>
    <script>
        $(document).ready(function() {
            $("#searchTerm").on("keyup", function() {
                var searchTerm = $(this).val();
                $.ajax({
                    url: "", // Same page for processing
                    method: "POST",
                    data: { searchTerm: searchTerm, sortColumn: "<?php echo $sortColumn; ?>", sortOrder: "<?php echo $sortOrder; ?>" },
                    success: function(response) {
                        $("#categoryTable").html(response);
                        updateRowNumbers(); // Update row numbers after AJAX response
                    }
                });
            });

            $("th").on("click", function() {
                var sortColumn = $(this).data("column");
                var sortOrder = $(this).data("order") === "ASC" ? "DESC" : "ASC";
                $(this).data("order", sortOrder); // Toggle order

                $.ajax({
                    url: "", // Same page for processing
                    method: "POST",
                    data: { searchTerm: $("#searchTerm").val(), sortColumn: sortColumn, sortOrder: sortOrder },
                    success: function(response) {
                        $("#categoryTable").html(response);
                        updateRowNumbers(); // Update row numbers after AJAX response
                        updateSortIcons(sortColumn, sortOrder); // Update sort icons
                    }
                });
            });

            function updateRowNumbers() {
                $("#categoryTable tr").each(function(index) {
                    $(this).find("td:first").text(index + 1); // Update the first cell with row number
                });
            }

            function updateSortIcons(column, order) {
                $("th").find(".sort-icon").remove(); // Remove existing icons
                $("th[data-column='" + column + "']").append('<i class="fas ' + (order === "ASC" ? 'fa-sort-up' : 'fa-sort-down') + ' sort-icon"></i>');
            }
        });
    </script>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Manage Issued Books</h4>
                    <div class="search-box">
                        <input type="text" id="searchTerm" placeholder="Search by Student Name, Student ID, Book Number, or Issue Date">
                    </div>
                    <div class="table-container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th data-column="id" data-order="ASC">#</th>
                                    <th data-column="s.StudentID" data-order="ASC">Student ID</th>
                                    <th data-column="s.FullName" data-order="ASC">Student Name</th>
                                    <th data-column="b.BookNumber" data-order="ASC">Book Number</th>
                                    <th data-column="b.BookName" data-order="ASC">Book Name</th>
                                    <th data-column="ib.IssueDate" data-order="ASC">Issued Date</th>
                                    <th data-column="ib.ReturnDate" data-order="ASC">Return Date</th>
                                    
                                    <th data-column="BorrowingStatus" data-order="ASC">Borrowing Status</th> 
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="categoryTable">
                                <?php 
                                $cnt = 1; // Initialize counter
                                while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $cnt++; ?></td>
                                    <td><?php echo htmlspecialchars($row['StudentID']); ?></td>
                                    <td><?php echo htmlspecialchars($row['FullName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['BookNumber']); ?></td>
                                    <td><?php echo htmlspecialchars($row['BookName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['IssueDate']); ?></td>
                                    <td><?php echo htmlspecialchars($row['ReturnDate']); ?></td>
                                    
                                    <td><?php echo htmlspecialchars($row['BorrowingStatus']); ?></td> 
                                    <td>
                                        <a href="edit_issue-stud.php?issue_id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-primary btn-sm">Edit</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
