<?php
// Include database connection file
include('includes/config.php');

// Fetch all categories or filtered ones if a search term is provided
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';
$sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'CategoryName'; // Default sorting by Category Name
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC'; // Default order

// Sanitize sort parameters to prevent SQL injection
$allowedSorts = ['CategoryName', 'CreationDate', 'UpdationDate'];
if (!in_array($sortBy, $allowedSorts)) {
    $sortBy = 'CategoryName';
}
$order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC'; // Ensure order is either ASC or DESC

$query = "SELECT ID, CategoryName, CreationDate, UpdationDate FROM tblcategory WHERE CategoryName LIKE '%$searchTerm%' ORDER BY $sortBy $order";

$result = mysqli_query($conn, $query);

// **If the request is AJAX (real-time search), return only the table rows**
if (isset($_POST['searchTerm'])) {
    $response = "";
    if (mysqli_num_rows($result) > 0) {
        $cnt = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $response .= "<tr>";
            $response .= "<td>" . $cnt . "</td>";
            $response .= "<td>" . $row['CategoryName'] . "</td>";
            $response .= "<td>" . $row['CreationDate'] . "</td>";
            $response .= "<td>" . $row['UpdationDate'] . "</td>";
            $response .= '<td><a href="edit_category.php?id=' . $row['ID'] . '" class="btn btn-warning btn-sm">Edit</a>
                          <a href="#" onclick="confirmDelete(' . $row['ID'] . ')" class="btn btn-danger btn-sm">Delete</a></td>';
            $response .= "</tr>";
            $cnt++;
        }
    } else {
        $response .= "<tr><td colspan='5'>No categories found.</td></tr>";
    }
    echo $response;
    exit; // **End script for AJAX requests**
}

// Handle deletion if requested
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $deleteQuery = "DELETE FROM tblcategory WHERE ID = '$id'";
    mysqli_query($conn, $deleteQuery);
    header("Location: manage-categories.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Categories - Manage Categories</title>
    <!-- Google Fonts Preconnect and Stylesheet -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
    
    <style>
        .table-container {
            max-height: 400px; /* Set the height for vertical scrolling */
            overflow-y: auto; /* Enable vertical scrolling */
            overflow-x: auto; /* Enable horizontal scrolling */
            display: block;
            width: 100%;
        }

        .table th, .table td {
            white-space: nowrap; /* Prevent table content from wrapping */
        }

        /* Optional: You can style the table header to stay fixed during scroll */
        .table thead th {
            position: sticky;
            top: 0;
            z-index: 1; /* Ensure it stays above the table content */
        }
    </style>

    <script>
        // **Real-time search with jQuery and AJAX**
        $(document).ready(function() {
            // **Trigger this function whenever the user types in the search box**
            $("#searchTerm").on("keyup", function() {
                var searchTerm = $(this).val(); // **Get the search term**
                $.ajax({
                    url: "", // **Same page, as PHP handles the request**
                    method: "POST",
                    data: { searchTerm: searchTerm }, // **Send search term to the server**
                    success: function(response) {
                        $("#categoryTable").html(response); // **Update table with the response**
                    }
                });
            });
        });

        // Confirmation dialog for deletion
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this category?")) {
                window.location.href = "manage-categories.php?action=delete&id=" + id;
            }
        }
    </script>
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Manage Categories</h4>
                </div>
            </div>
            <div class="search-box">
                <form>
                    <input type="text" id="searchTerm" name="searchTerm" placeholder="Search by Category Name">
                </form>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Make the table container scrollable -->
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><a href="?sortBy=CategoryName&order=<?php echo ($sortBy == 'CategoryName' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Category Name</a></th>
                                    <th><a href="?sortBy=CreationDate&order=<?php echo ($sortBy == 'CreationDate' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Creation Date</a></th>
                                    <th><a href="?sortBy=UpdationDate&order=<?php echo ($sortBy == 'UpdationDate' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Updation Date</a></th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="categoryTable">
                                <?php
                                // Reset query for initial load (without search)
                                $query = "SELECT ID, CategoryName, CreationDate, UpdationDate FROM tblcategory WHERE CategoryName LIKE '%$searchTerm%' ORDER BY $sortBy $order";
                                $result = mysqli_query($conn, $query);
                                
                                if (mysqli_num_rows($result) > 0) {
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $cnt . "</td>";
                                        echo "<td>" . $row['CategoryName'] . "</td>";
                                        echo "<td>" . $row['CreationDate'] . "</td>";
                                        echo "<td>" . $row['UpdationDate'] . "</td>";
                                        echo '<td>
                                            <a href="edit_category.php?id=' . $row['ID'] . '" class="btn btn-primary btn-sm">Edit</a>
                                            <a href="#" onclick="confirmDelete(' . $row['ID'] . ')" class="btn btn-danger btn-sm">Delete</a>
                                        </td>';
                                        echo "</tr>";
                                        $cnt++;
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>No categories found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> <!-- End of table-container -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>
