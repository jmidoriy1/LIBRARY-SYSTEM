<?php
// Include database connection script
include('includes/config.php'); // Update this path to where your connection script is located

// Start the PHP block for handling form submission
if (isset($_POST['create'])) {
    // Escape form inputs to prevent SQL Injection
    $publication = mysqli_real_escape_string($conn, $_POST['publication']); // 'author' matches the form input name

    // Check if the author already exists in the database
    $select = mysqli_query($conn, "SELECT * FROM tblauthors WHERE AuthorName = '$publication'") or die('Query failed');

    // If no matching author is found, insert the new author
    if (mysqli_num_rows($select) == 0) {
        // Prepare the SQL insert query
        $sql = "INSERT INTO tblauthors (AuthorName) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $publication);

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>alert('Author added successfully');</script>";
        } else {
            echo "<script>alert('Failed to add Author');</script>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "<script>alert('Author already exists');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Publication</title>
    <!-- Google Fonts Preconnect and Stylesheet -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=K2D:wght@100;200;300;400;500;600;700;800&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome (only one version) -->
    <!-- Font Awesome (only one version) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="assets/css/admin.css">

    <!-- Bootstrap JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (only if needed) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

<body>
    <!--MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class=" content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">ADD PUBLICATION</h4>

                </div>

            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class=" panel panel-info">
                        <div class="panel-heading">
                            Publication Info
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label>Publication Name</label>
                                    <input class="form-control" type="text" name="publication" autocomplete="off"
                                        required />
                                </div>

                                <button type="submit" name="create" class="btn btn-info">Add</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    </div>
</body>

</html>