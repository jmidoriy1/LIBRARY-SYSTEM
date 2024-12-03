<?php
require_once 'includes/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';

if ($startDate && $endDate) {
    $startDateObj = DateTime::createFromFormat('Y-m-d', $startDate);
    $endDateObj = DateTime::createFromFormat('Y-m-d', $endDate);

    if ($startDateObj && $endDateObj) {
        $startDate = $startDateObj->format('Y-m-d');
        $endDate = $endDateObj->format('Y-m-d') . ' 23:59:59'; 
    }
}

$sql = "
    SELECT 
        tblbooks.id, 
        tblbooks.BookName, 
        tblbooks.BookNumber, 
        tblcategory.CategoryName, 
        tblauthors.AuthorName,     
        tblbooks.UpdationDate, 
        tblbooks.archive
    FROM 
        tblbooks
    LEFT JOIN 
        tblcategory ON tblbooks.CatId = tblcategory.id
    LEFT JOIN 
        tblauthors ON tblbooks.AuthorId = tblauthors.id
    WHERE 
        tblbooks.archive = 0";

if ($startDate && $endDate) {
    $sql .= " AND tblbooks.UpdationDate BETWEEN ? AND ?";
}

$sql .= " ORDER BY tblbooks.id DESC";
$query = $conn->prepare($sql);

if ($startDate && $endDate) {
    $query->bind_param('ss', $startDate, $endDate); 
}

if ($query) {
    $query->execute();
    $results = $query->get_result(); 

    $table = '
    <table border="1" cellspacing="0" cellpadding="0" style="width:100%;">
        <tr>
            <th>Book Name</th>
            <th>Book Number</th>
            <th>Category</th>
            <th>Author</th>
            <th>Updation Date</th>
        </tr>';

    if ($results->num_rows > 0) {
        while ($result = $results->fetch_assoc()) {
            $table .= '<tr>
                <td><center>' . htmlspecialchars($result['BookName']) . '</center></td>
                <td><center>' . htmlspecialchars($result['BookNumber']) . '</center></td>
                <td><center>' . htmlspecialchars($result['CategoryName'] ?? 'N/A') . '</center></td>
                <td><center>' . htmlspecialchars($result['AuthorName'] ?? 'N/A') . '</center></td>
                <td><center>' . htmlspecialchars($result['UpdationDate']) . '</center></td>
            </tr>';
        }
    } else {
        $table .= '<tr><td colspan="5" style="text-align:center;">No records found.</td></tr>';
    }

    $table .= '</table>
    <button onClick="window.print()">Print this page</button>';    

    echo $table;
} else {
    echo "Error preparing statement: " . $conn->error;
}
?>
