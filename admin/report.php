<?php
// Include database connection script
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Report</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@100;200;300;400;500;600;700;800&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .form-group{
            width: 98%;
            
        }
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Generate Reports</h4>
                    <form method="post" action="">
                        <!-- Date Range Selection -->
                        <div class="form-group">
                            <label for="startDate">Starting From:</label>
                            <input type="date" id="startDate" name="startDate" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="endDate">Up To:</label>
                            <input type="date" id="endDate" name="endDate" class="form-control" required>
                        </div>

                        <!-- Report Type Selection -->
                        <div class="form-group">
                            <label for="reportType">Select Report Type:</label>
                            <select id="reportType" name="reportType" class="form-control" required>
                                <option value="">-- Select Report Type --</option>
                                <option value="report-client.php">Returned</option>
                                <option value="report-not_return.php">Not Returned Yet</option>
                                <option value="report-client_and_not-return.php">Returned & Not Returned Yet</option>
                                <option value="report-obsolete.php">Obsolete</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-info" onclick="generateReport()">Generate Report</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function generateReport() {
        var startDate = document.getElementById("startDate").value;
        var endDate = document.getElementById("endDate").value;
        var reportType = document.getElementById("reportType").value;

        // Check if all fields are filled
        if (startDate && endDate && reportType) {
            // If the report type is "Obsolete", append the date range to the clientreport.php URL
            if (reportType === "clientreport.php?type=obsolete") {
                var url = reportType + "&startDate=" + startDate + "&endDate=" + endDate;
            } else {
                // Otherwise, use the selected reportType
                var url = reportType + "?startDate=" + startDate + "&endDate=" + endDate;
            }
            window.location.href = url;
        } else {
            alert("Please fill in all the fields.");
        }
    }
</script>

</body>
</html>
