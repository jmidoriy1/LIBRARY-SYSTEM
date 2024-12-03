<?php
// Include database connection script
include('includes/config.php'); // Update this path to where your connection script is located

// Check if an ID is provided for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the current publication data
    $select = mysqli_query($conn, "SELECT * FROM tblauthors WHERE id = '$id'") or die('Query failed');
    $publicationData = mysqli_fetch_assoc($select);

    if (!$publicationData) {
        echo "<script>alert('Publication not found');</script>";
        exit;
    }
}

// Start the PHP block for handling form submission
if (isset($_POST['update'])) {
    // Escape form inputs to prevent SQL Injection
    $publication = mysqli_real_escape_string($conn, $_POST['publication']); // 'publication' matches the form input name

    // Update the publication in the database
    $sql = "UPDATE tblauthors SET AuthorName = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $publication, $id);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('Publication updated successfully');</script>";
        header("Location: manage-publications.php"); // Redirect after successful update
        exit;
    } else {
        echo "<script>alert('Failed to update publication');</script>";
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
    <title>Admin Dashboard - Edit Publication</title>
    <!-- Google Fonts Preconnect and Stylesheet -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@100;200;300;400;500;600;700;800&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <!--MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">EDIT PUBLICATION</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Publication Info
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label>Publication Name</label>
                                    <input class="form-control" type="text" name="publication" value="<?php echo htmlspecialchars($publicationData['AuthorName']); ?>" autocomplete="off" required />
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
