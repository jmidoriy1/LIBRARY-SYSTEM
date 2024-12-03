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

// Prepare the base SQL query to fetch issued book details
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
        tblissuedbookdetails.ReturnStatus,
        tblfaculty.FullName AS FacultyName,
        tblfaculty.FacultyID,
        DATEDIFF(CURDATE(), tblissuedbookdetails.IssueDate) AS DaysBorrowed,
        CASE 
            WHEN tblissuedbookdetails.ReturnStatus = 0 AND DATEDIFF(CURDATE(), tblissuedbookdetails.IssueDate) > 14 THEN DATEDIFF(CURDATE(), tblissuedbookdetails.IssueDate) - 14
            ELSE 0
        END AS DaysExceeded
    FROM 
        tblissuedbookdetails
    LEFT JOIN 
        tblstudents ON tblstudents.StudentId = tblissuedbookdetails.StudentId 
    LEFT JOIN 
        tblbooks ON tblissuedbookdetails.BookId = tblbooks.BookNumber
    LEFT JOIN
        tblfaculty ON tblfaculty.FacultyID = tblissuedbookdetails.FacultyID
    WHERE 
        tblissuedbookdetails.ReturnStatus = 0";

// Add date range filtering to the SQL query if both startDate and endDate are provided
if ($startDate && $endDate) {
    $sql .= " AND tblissuedbookdetails.IssueDate BETWEEN ? AND ?";
}

// Order by the issued book details ID
$sql .= " ORDER BY tblissuedbookdetails.id DESC";

$query = $conn->prepare($sql);

// Bind the date parameters to the query if date range is applied
if ($startDate && $endDate) {
    $query->bind_param('ss', $startDate, $endDate); // 'ss' means two string parameters (dates)
}

if ($query) {
    $query->execute();
    $results = $query->get_result(); // Use get_result() for mysqli

    // Start building the main table
    $table = '
    <table border="1" cellspacing="0" cellpadding="0" style="width:100%;">
        <tr>
            <th>Name</th>
            <th>ID</th>
            <th>Book Name</th>
            <th>Book Number</th>
            <th>Issued Date</th>
            <th>Return Date</th>
            <th>Borrowing Status</th>
        </tr>';

    if ($results->num_rows > 0) {
        while ($result = $results->fetch_assoc()) {
            // Determine borrowing status
            $daysBorrowed = $result['DaysBorrowed'];
            $returnStatus = $result['ReturnStatus'];
            $daysExceeded = $result['DaysExceeded'];

            if ($returnStatus == 1) {
                $borrowingStatus = "Returned";
            } else {
                if ($daysExceeded > 0) {
                    $borrowingStatus = "Exceeded $daysExceeded Days";
                } else {
                    if ($daysBorrowed == 0) {
                        $borrowingStatus = "Borrowed Today";
                    } elseif ($daysBorrowed == 1) {
                        $borrowingStatus = "Borrowed 1 Day Ago";
                    } elseif ($daysBorrowed <= 14) {
                        $borrowingStatus = "Borrowed $daysBorrowed Days Ago";
                    } else {
                        $borrowingStatus = "Exceeded 1 Day";
                    }
                }
            }

            // Set row background color based on days exceeded
            $rowStyle = $daysExceeded > 0 ? 'background-color: red;' : '';

            // Get the name (FacultyName if available, otherwise StudentName)
            $name = $result['FacultyID'] ? $result['FacultyName'] : $result['StudentName'];

            // Build the table row
            $table .= '<tr style="' . $rowStyle . '">
                <td><center>' . htmlspecialchars($name ?? '') . '</center></td>
                <td><center>' . htmlspecialchars($result['FacultyID'] ?? $result['StudentID']) . '</center></td> 
                <td><center>' . htmlspecialchars($result['BookName'] ?? 'N/A') . '</center></td>
                <td><center>' . htmlspecialchars($result['BookId'] ?? '') . '</center></td> 
                <td><center>' . htmlspecialchars($result['IssueDate'] ?? '') . '</center></td>
                <td><center>' . htmlspecialchars($result['ReturnDate'] ?? 'N/A') . '</center></td>
                <td><center>' . htmlspecialchars($borrowingStatus) . '</center></td>
            </tr>';    
        }
    } else {
        $table .= '<tr><td colspan="7" style="text-align:center;">No records found.</td></tr>';
    }

    $table .= '</table>
    <button onClick="window.print()">Print this page</button>';    

    echo $table;

} else {
    echo "Error preparing statement: " . $conn->error;
}

?>
