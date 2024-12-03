<?php 

// Include database connection script
require_once 'includes/config.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get the startDate and endDate from query parameters (if set)
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';

// Prepare SQL query with date filters if dates are provided
$sql = "
    SELECT 
        tblstudents.FullName AS StudentName, 
        tblstudents.StudentID,
        tblissuedbookdetails.BookId,
        tblbooks.BookName, 
        tblbooks.BookNumber, 
        tblbooks.id, 
        tblissuedbookdetails.IssueDate, 
        tblissuedbookdetails.ReturnDate, 
        tblissuedbookdetails.id as rid,
        tblfaculty.FullName AS FacultyName,
        tblfaculty.FacultyID
    FROM 
        tblissuedbookdetails
    LEFT JOIN 
        tblstudents ON tblstudents.StudentId = tblissuedbookdetails.StudentId 
    LEFT JOIN 
        tblbooks ON tblissuedbookdetails.BookId = tblbooks.BookNumber
    LEFT JOIN 
        tblfaculty ON tblfaculty.FacultyID = tblissuedbookdetails.FacultyID
    WHERE 
        tblissuedbookdetails.ReturnStatus = 1";

// If startDate and endDate are provided, add a date range filter to the SQL query
if ($startDate && $endDate) {
    $sql .= " AND tblissuedbookdetails.IssueDate BETWEEN ? AND ?";
}

// Add sorting order
$sql .= " ORDER BY tblissuedbookdetails.id DESC";

$query = $conn->prepare($sql);

// Bind parameters to the query if date range is applied
if ($startDate && $endDate) {
    $query->bind_param('ss', $startDate, $endDate); // 'ss' means two strings (dates)
}

if ($query) {
    $query->execute();
    $results = $query->get_result(); // Use get_result() for mysqli

    // Start building the table
    $table = '
    <table border="1" cellspacing="0" cellpadding="0" style="width:100%;">
        <tr>
            <th>Name</th>
            <th>ID</th>
            <th>Book Name</th>
            <th>Book Number</th>
            <th>Issued Date</th>
            <th>Return Date</th>
        </tr>';

    if ($results->num_rows > 0) {
        while ($result = $results->fetch_assoc()) {
            // Get the name (FacultyName if available, otherwise StudentName)
            $name = $result['FacultyID'] ? $result['FacultyName'] : $result['StudentName'];
            // Get the ID (FacultyID if available, otherwise StudentID)
            $id = $result['FacultyID'] ? $result['FacultyID'] : $result['StudentID'];

            $table .= '<tr>
                <td><center>' . htmlspecialchars($name ?? '') . '</center></td>
                <td><center>' . htmlspecialchars($id ?? '') . '</center></td> 
                <td><center>' . htmlspecialchars($result['BookName'] ?? 'N/A') . '</center></td>
                <td><center>' . htmlspecialchars($result['BookId'] ?? '') . '</center></td> 
                <td><center>' . htmlspecialchars($result['IssueDate'] ?? '') . '</center></td>
                <td><center>' . htmlspecialchars($result['ReturnDate'] ?? '') . '</center></td>
            </tr>';    
        }
    } else {
        $table .= '<tr><td colspan="6" style="text-align:center;">No records found.</td></tr>';
    }

    $table .= '</table>
    <button onClick="window.print()">Print this page</button>';    

    echo $table;
} else {
    echo "Error preparing statement: " . $conn->error;
}

?>
