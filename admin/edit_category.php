<?php
// Include database connection script
include('includes/config.php');

// Get the category ID from the URL
if (isset($_GET['id'])) {
    $categoryId = mysqli_real_escape_string($conn, $_GET['id']);

    // Fetch the existing category details
    $query = "SELECT CategoryName, Status FROM tblcategory WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $categoryData = $result->fetch_assoc();
    } else {
        echo "<script>alert('Category not found');</script>";
        exit;
    }
    $stmt->close();
}

// Start the PHP block for handling form submission
if (isset($_POST['update'])) {
    // Escape form inputs to prevent SQL Injection
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Prepare the SQL update query
    $sql = "UPDATE tblcategory SET CategoryName = ?, Status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $category, $status, $categoryId);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('Category updated successfully'); window.location.href='manage-categories.php';</script>";
    } else {
        echo "<script>alert('Failed to update category');</script>";
    }

    // Close the statement
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Edit Category</title>
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
                    <h4 class="header-line">EDIT CATEGORY</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Category Info
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label>Category Name</label>
                                    <input class="form-control" type="text" name="category" value="<?php echo htmlspecialchars($categoryData['CategoryName']); ?>" autocomplete="off" required />
                                </div>
                                
                                <button type="submit" name="update" class="btn btn-info">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
