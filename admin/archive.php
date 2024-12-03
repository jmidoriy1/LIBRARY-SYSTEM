<?php
include('includes/config.php');

// Get sorting parameters
$sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'BookNumber';
$order = isset($_GET['order']) && $_GET['order'] == 'DESC' ? 'DESC' : 'ASC';

// Fetch data from the tblbooks table with sorting and joins for authors and categories
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';
$query = "SELECT b.id, b.BookName, b.Copies, b.datepublished, b.UpdationDate,
                 COALESCE(COUNT(i.BookID), 0) AS IssuedCopies, 
                 b.BookNumber, a.AuthorName, c.CategoryName, b.archive
          FROM tblbooks b
          JOIN tblauthors a ON b.AuthorId = a.id 
          JOIN tblcategory c ON b.CatId = c.id
          LEFT JOIN tblissuedbookdetails i ON i.BookID = b.BookNumber
          WHERE (b.BookName LIKE '%$searchTerm%' 
             OR a.AuthorName LIKE '%$searchTerm%'
             OR c.CategoryName LIKE '%$searchTerm%')
          GROUP BY b.id, b.BookName, b.Copies, b.BookNumber, a.AuthorName, c.CategoryName, b.archive, b.UpdationDate
          ORDER BY $sortBy $order";

$result = mysqli_query($conn, $query);

// Handle AJAX request for real-time search
if (isset($_POST['searchTerm'])) {
    $response = "";
    if (mysqli_num_rows($result) > 0) {
        $cnt = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            // Only show non-archived books
            if ($row['archive'] == 0) {
                $response .= "<tr>";
                $response .= "<td>" . $cnt . "</td>";
                $response .= "<td>" . $row['BookName'] . "</td>";
                $response .= "<td>" . $row['Copies'] . "</td>";
                $response .= "<td>" . $row['IssuedCopies'] . "</td>";
                $response .= "<td>" . $row['BookNumber'] . "</td>";
                $response .= "<td>" . $row['AuthorName'] . "</td>";
                $response .= "<td>" . $row['CategoryName'] . "</td>";
                $response .= "<td>" . $row['datepublished'] . "</td>";
                $response .= "<td>" . $row['UpdationDate'] . "</td>";
                $response .= '<td>
                    <a href="#" onclick="confirmArchive(' . $row['id'] . ')" class="btn btn-primary btn-sm">Retrieve</a>
                </td>';
                $response .= "</tr>";
                $cnt++;
            }
        }
    } else {
        $response .= "<tr><td colspan='9'>No books found.</td></tr>";
    }
    echo $response;
    exit;
}

// Handle archiving if requested
if (isset($_GET['action']) && $_GET['action'] == 'archive' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $archiveQuery = "UPDATE tblbooks SET archive = 1 WHERE id = '$id'";
    mysqli_query($conn, $archiveQuery);
    header("Location: manage-books.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Books Archive</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@100;200;300;400;500;600;700;800&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/tables.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#searchTerm").on("keyup", function() {
                var searchTerm = $(this).val();
                $.ajax({
                    url: "",
                    method: "POST",
                    data: { searchTerm: searchTerm },
                    success: function(response) {
                        $("#bookTable").html(response);
                    }
                });
            });
        });

        function confirmArchive(id) {
            if (confirm("Are you sure you want to retrieve this book?")) {
                window.location.href = "manage-books.php?action=archive&id=" + id;
            }
        }
    </script>
    <style>
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }

        .table th {
            position: sticky;
            top: 0;
            z-index: 1;
        }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Manage Books</h4>
                </div>
            </div>
            <div class="search-box">
                <form>
                    <input type="text" id="searchTerm" name="searchTerm" placeholder="Search by Book Name, Author Name, or Category Name">
                </form>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Table Container with Scrollable Body -->
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><a href="?sortBy=BookName&order=<?php echo ($sortBy == 'BookName' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Book Name</a></th>
                                    <th><a href="?sortBy=Copies&order=<?php echo ($sortBy == 'Copies' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Copies</a></th>
                                    <th><a href="?sortBy=IssuedCopies&order=<?php echo ($sortBy == 'IssuedCopies' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Issued Copies</a></th>
                                    <th><a href="?sortBy=BookNumber&order=<?php echo ($sortBy == 'BookNumber' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Book Number</a></th>
                                    <th><a href="?sortBy=AuthorName&order=<?php echo ($sortBy == 'AuthorName' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Author Name</a></th>
                                    <th><a href="?sortBy=CategoryName&order=<?php echo ($sortBy == 'CategoryName' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Category Name</a></th>
                                    <th><a href="?sortBy=datepublished&order=<?php echo ($sortBy == 'datepublished' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Date Published</a></th>
                                    <th><a href="?sortBy=UpdationDate&order=<?php echo ($sortBy == 'UpdationDate' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Date Archived</a></th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="bookTable">
                                <?php
                                if (mysqli_num_rows($result) > 0) {
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        // Only display non-archived books (archive = 0)
                                        if ($row['archive'] == 0) {
                                            echo "<tr>";
                                            echo "<td>" . $cnt . "</td>";
                                            echo "<td>" . $row['BookName'] . "</td>";
                                            echo "<td>" . $row['Copies'] . "</td>";
                                            echo "<td>" . $row['IssuedCopies'] . "</td>";
                                            echo "<td>" . $row['BookNumber'] . "</td>";
                                            echo "<td>" . $row['AuthorName'] . "</td>";
                                            echo "<td>" . $row['CategoryName'] . "</td>";
                                            echo "<td>" . $row['datepublished'] . "</td>";
                                            echo "<td>" . $row['UpdationDate'] . "</td>";
                                            echo '<td>
                                                <a href="#" onclick="confirmArchive(' . $row['id'] . ')" class="btn btn-primary btn-sm">Retrieve</a>
                                            </td>';
                                            echo "</tr>";
                                            $cnt++;
                                        }
                                    }
                                } else {
                                    echo "<tr><td colspan='9'>No books found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
