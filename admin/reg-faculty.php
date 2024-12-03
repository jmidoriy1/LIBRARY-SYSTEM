<?php
// Include database connection file
include('includes/config.php');

// Handle sorting
$sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'FacultyID';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

$allowedSorts = ['FacultyID', 'FullName', 'email', 'MobileNumber', 'RegDate', 'UpdationDate'];
if (!in_array($sortBy, $allowedSorts)) {
    $sortBy = 'FacultyID';
}

// Handle search requests
$searchTerm = "";
if (isset($_POST['searchTerm'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);
}

$query = "SELECT id, FacultyID, FullName, email, MobileNumber, RegDate, UpdationDate, Status 
          FROM tblfaculty 
          WHERE FullName LIKE '%$searchTerm%' 
          ORDER BY $sortBy $order";
$result = mysqli_query($conn, $query);

// Handle status change requests via AJAX
if (isset($_POST['facultyID']) && isset($_POST['currentStatus'])) {
    $facultyID = mysqli_real_escape_string($conn, $_POST['facultyID']);
    $currentStatus = $_POST['currentStatus'];
    
    // Determine new status based on current status
    $newStatus = ($currentStatus === '1') ? '0' : '1';

    $updateQuery = "UPDATE tblfaculty SET Status = '$newStatus' WHERE FacultyID = '$facultyID'";
    if (mysqli_query($conn, $updateQuery)) {
        // Return the updated status (1 or 0) to the front end
        echo $newStatus;
    } else {
        echo "Error updating status.";
    }
    exit;
}

$response = "";
if (mysqli_num_rows($result) > 0) {
    $cnt = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $statusText = ($row['Status'] == '1') ? 'Active' : 'Inactive';
        $response .= "<tr>";
        $response .= "<td>" . $cnt . "</td>";
        $response .= "<td>" . htmlspecialchars($row['FacultyID']) . "</td>";
        $response .= "<td>" . htmlspecialchars($row['FullName']) . "</td>";
        $response .= "<td>" . htmlspecialchars($row['email']) . "</td>";
        $response .= "<td>" . htmlspecialchars($row['MobileNumber']) . "</td>";
        $response .= "<td>" . htmlspecialchars($row['RegDate']) . "</td>";
        $response .= "<td>" . htmlspecialchars($row['UpdationDate']) . "</td>";
        $response .= "<td><button class='status-btn' data-id='" . $row['FacultyID'] . "' data-status='" . $row['Status'] . "'>" . $statusText . "</button></td>";
        $response .= "</tr>";
        $cnt++;
    }
} else {
    $response .= "<tr><td colspan='8'>No faculty found.</td></tr>";
}

if (isset($_POST['searchTerm'])) {
    echo $response;
    exit; // End script for AJAX requests
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Registered Faculty</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/tables.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@100;200;300;400;500;600;700;800&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .search-box {
            width: 98%;
        }

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
    <script>
        $(document).ready(function() {
            // Search functionality
            $("#searchTerm").on("keyup", function() {
                var searchTerm = $(this).val();
                $.ajax({
                    url: "",
                    method: "POST",
                    data: { searchTerm: searchTerm },
                    success: function(response) {
                        $("#facultyTable").html(response);
                    }
                });
            });

            // Handle status change with confirmation popup
            $(document).on('click', '.status-btn', function() {
                var facultyID = $(this).data('id');
                var currentStatus = $(this).data('status');

                // Determine the action (activate or deactivate)
                var action = (currentStatus === '1') ? 'deactivate' : 'activate';
                var confirmationMessage = 'Are you sure you want to ' + action + ' this faculty member?';

                // Show confirmation dialog
                if (confirm(confirmationMessage)) {
                    $.ajax({
                        url: "",
                        method: "POST",
                        data: {
                            facultyID: facultyID,
                            currentStatus: currentStatus
                        },
                        success: function(response) {
                            // Update the button text and data-status attribute based on the new status
                            var newStatusText = (response === '1') ? 'Active' : 'Inactive';
                            $("button[data-id='" + facultyID + "']").text(newStatusText);
                            $("button[data-id='" + facultyID + "']").data('status', response);
                        }
                    });
                }
            });
        });
    </script>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Registered Faculty</h4>
                </div>
            </div>
            <div class="search-box">
                <input type="text" id="searchTerm" placeholder="Search by Full Name" class="form-control" style="width: 300px;">
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><a href="?sortBy=FacultyID&order=<?php echo ($sortBy == 'FacultyID' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Faculty ID</a></th>
                                    <th><a href="?sortBy=FullName&order=<?php echo ($sortBy == 'FullName' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Full Name</a></th>
                                    <th><a href="?sortBy=email&order=<?php echo ($sortBy == 'email' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Email</a></th>
                                    <th><a href="?sortBy=MobileNumber&order=<?php echo ($sortBy == 'MobileNumber' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Mobile Number</a></th>
                                    <th><a href="?sortBy=RegDate&order=<?php echo ($sortBy == 'RegDate' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Registration Date</a></th>
                                    <th><a href="?sortBy=UpdationDate&order=<?php echo ($sortBy == 'UpdationDate' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>">Updation Date</a></th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="facultyTable">
                                <?php echo $response; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
