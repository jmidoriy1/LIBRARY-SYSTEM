<?php
include('includes/config.php');

// Check if a book ID is provided for editing
if (isset($_GET['bookid'])) {
    $id = $_GET['bookid'];

    // Fetch the current book data, including datepublished
    $bookQuery = "SELECT * FROM tblbooks WHERE id = '$id'";
    $bookResult = mysqli_query($conn, $bookQuery);
    $bookData = mysqli_fetch_assoc($bookResult);

    if (!$bookData) {
        echo "<script>alert('Book not found');</script>";
        exit;
    }
}

// Fetch categories from the database
$categoryQuery = "SELECT * FROM tblcategory WHERE Status = 1"; // Only fetch active categories
$categoryResult = mysqli_query($conn, $categoryQuery);

// Fetch authors from the database
$authorQuery = "SELECT * FROM tblauthors"; // Assuming you have an authors table
$authorResult = mysqli_query($conn, $authorQuery);

// Handle form submission for updating the book
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape form inputs to prevent SQL Injection
    $bookName = mysqli_real_escape_string($conn, $_POST['bookName']);
    $categoryID = mysqli_real_escape_string($conn, $_POST['category']);
    $authorID = mysqli_real_escape_string($conn, $_POST['author']);
    $numberOfCopies = mysqli_real_escape_string($conn, $_POST['numberOfCopies']);
    $bookNumber = mysqli_real_escape_string($conn, $_POST['bookNumber']);
    $datepublished = mysqli_real_escape_string($conn, $_POST['datepublished']); // Get datepublished

    // Update the book in the database
    $updateQuery = "UPDATE tblbooks SET BookName = ?, CatId = ?, AuthorId = ?, Copies = ?, BookNumber = ?, datepublished = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssiissi", $bookName, $categoryID, $authorID, $numberOfCopies, $bookNumber, $datepublished, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Book updated successfully');</script>";
        header("Location: manage-books.php"); // Redirect after successful update
        exit;
    } else {
        echo "<script>alert('Failed to update book: " . $stmt->error . "');</script>";
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
    <title>Admin Dashboard - Edit Book</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@100;200;300;400;500;600;700;800&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/tables.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!--MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">EDIT BOOK</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Book Info
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label for="bookName">Book Name</label>
                                    <input type="text" class="form-control" id="bookName" name="bookName" value="<?php echo htmlspecialchars($bookData['BookName']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <?php
                                        // Populate category options
                                        while ($row = mysqli_fetch_assoc($categoryResult)) {
                                            $selected = ($row['id'] == $bookData['CatId']) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($row['id']) . '" ' . $selected . '>' . htmlspecialchars($row['CategoryName']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="author">Author</label>
                                    <select class="form-control" id="author" name="author" required>
                                        <option value="">Select Author</option>
                                        <?php
                                        // Populate author options
                                        while ($authorRow = mysqli_fetch_assoc($authorResult)) {
                                            $selected = ($authorRow['id'] == $bookData['AuthorId']) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($authorRow['id']) . '" ' . $selected . '>' . htmlspecialchars($authorRow['AuthorName']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="numberOfCopies">No. of Copies</label>
                                    <input type="number" class="form-control" id="numberOfCopies" name="numberOfCopies" value="<?php echo htmlspecialchars($bookData['Copies']); ?>" required min="1">
                                </div>
                                <div class="form-group">
                                    <label for="bookNumber">Book Number</label>
                                    <input type="text" class="form-control" id="bookNumber" name="bookNumber" value="<?php echo htmlspecialchars($bookData['BookNumber']); ?>" required>
                                </div>
                                <!-- New Date Published Field -->
                                <div class="form-group">
                                    <label for="datepublished">Date Published</label>
                                    <input type="date" class="form-control" id="datepublished" name="datepublished" value="<?php echo htmlspecialchars($bookData['datepublished']); ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Book</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
