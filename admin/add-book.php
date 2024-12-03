<?php
// Include database connection script
include('includes/config.php');

// Fetch categories from the database
$categoryQuery = "SELECT * FROM tblcategory WHERE Status = 1"; // Only fetch active categories
$categoryResult = mysqli_query($conn, $categoryQuery);

// Fetch authors from the database in descending order
$authorQuery = "SELECT * FROM tblauthors ORDER BY AuthorName ASC"; // Sort authors by name in descending order
$authorResult = mysqli_query($conn, $authorQuery);

// Check if editing an existing book
$bookID = isset($_GET['bookid']) ? intval($_GET['bookid']) : 0;
$book = null;
if ($bookID > 0) {
    $bookQuery = "SELECT * FROM tblbooks WHERE id = ?";
    $stmt = $conn->prepare($bookQuery);
    $stmt->bind_param("i", $bookID);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape form inputs to prevent SQL Injection
    $bookName = mysqli_real_escape_string($conn, $_POST['bookName']);
    $categoryID = mysqli_real_escape_string($conn, $_POST['category']);
    $authorID = mysqli_real_escape_string($conn, $_POST['author']);
    $numberOfCopies = mysqli_real_escape_string($conn, $_POST['numberOfCopies']);
    $bookNumber = mysqli_real_escape_string($conn, $_POST['bookNumber']);
    $datepublished = mysqli_real_escape_string($conn, $_POST['datepublished']);

    if ($bookID > 0) {
        // Update existing book
        $updateQuery = "UPDATE tblbooks SET BookName = ?, CatId = ?, AuthorId = ?, Copies = ?, BookNumber = ?, datepublished = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssiissi", $bookName, $categoryID, $authorID, $numberOfCopies, $bookNumber, $datepublished, $bookID);
    } else {
        // Insert a new book
        $insertQuery = "INSERT INTO tblbooks (BookName, CatId, AuthorId, Copies, BookNumber, datepublished) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssiiss", $bookName, $categoryID, $authorID, $numberOfCopies, $bookNumber, $datepublished);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Book saved successfully');</script>";
    } else {
        echo "<script>alert('Failed to save book: " . $stmt->error . "');</script>";
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
    <title>Admin Dashboard - Add Book</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=K2D:wght@100;200;300;400;500;600;700;800&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Add the Select2 CSS for searchable dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    
</head>

<body>
    <!--MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line"><?php echo $bookID > 0 ? 'EDIT BOOK' : 'ADD BOOK'; ?></h4>
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
                                    <label for="bookName">Book Name </label>
                                    <input type="text" class="form-control" id="bookName" name="bookName" required
                                        value="<?php echo $book ? htmlspecialchars($book['BookName']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="category">Category </label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <?php
                                        // Populate category options
                                        while ($row = mysqli_fetch_assoc($categoryResult)) {
                                            $selected = $book && $row['id'] == $book['CatId'] ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($row['id']) . '" ' . $selected . '>' . htmlspecialchars($row['CategoryName']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="author">Author </label>
                                    <select class="form-control" id="author" name="author" required>
                                        <option value="">Select Author</option>
                                        <?php
                                        while ($authorRow = mysqli_fetch_assoc($authorResult)) {
                                            $selected = $book && $authorRow['id'] == $book['AuthorId'] ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($authorRow['id']) . '" ' . $selected . '>' . htmlspecialchars($authorRow['AuthorName']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="datepublished">Date Published</label>
                                    <input type="date" class="form-control" id="datepublished" name="datepublished"
                                        required
                                        value="<?php echo $book ? htmlspecialchars($book['datepublished']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="numberOfCopies">No. of Copies </label>
                                    <input type="number" class="form-control" id="numberOfCopies" name="numberOfCopies"
                                        required min="1"
                                        value="<?php echo $book ? htmlspecialchars($book['Copies']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="bookNumber">Book Number</label>
                                    <input type="text" class="form-control" id="bookNumber" name="bookNumber" required
                                        value="<?php echo $book ? htmlspecialchars($book['BookNumber']) : ''; ?>">
                                </div>
                                <button type="submit"
                                    class="btn btn-info"><?php echo $bookID > 0 ? 'Update Book' : 'Add Book'; ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->


    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>

</html>