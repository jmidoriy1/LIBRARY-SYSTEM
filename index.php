<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Library Management System</title>
    <!-- Google Fonts Preconnect and Stylesheet -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=K2D:wght@100;200;300;400;500;600;700;800&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome (only one version) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="assets/css/.css">

    <!-- Bootstrap JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (only if needed) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f8f0;
        }

        .navbar-index {
            background-color: #0A670C;
            color: #DBCE15;
            padding: 40px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-container-index {
            display: flex;
            align-items: center;
        }

        .logo-index {
            width: 70px;
            height: 70px;
            margin-right: 15px;
        }

        .navbar-title-index {
            font-size: 36px;
            font-weight: bold;
        }


        .navbar {
            background-color: #0A670C;
            color: #DBCE15;
            padding: 20px 10px;
            /* Reduced padding */
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-container {
            display: flex;
            align-items: center;
        }

        .logo {
            width: 60px;
            /* Reduced width */
            height: 60pxpx;
            /* Reduced height */
            margin-right: 15px;
            /* Slightly reduced margin */
        }


        .navbar-title {
            font-size: 24px;
            font-weight: bold;
        }
        .card{
            align-items: center;
            justify-content:flex-end;
            
            
        }
        .box {
            width: 13%;
            /* Fixed width for the box */
            height: 200px;
            /* Fixed height for the box */
            margin: 20px;
            /* Margin around the box */
            margin-top: 10%;
            /* Top margin */
            margin-left: 5%;
            /* Left margin */
            display: inline-flex;
            /* Flexbox for alignment */
            flex-direction: column;
            /* Stack children vertically */
            justify-content: center;
            /* Center content vertically */
            align-items: center;
            /* Center content horizontally */
            background-color: #DBCE15;
            /* Background color */
            border: 8px solid #0A670C;
            /* Border color */
            color: #0A670C;
            /* Text color */
            text-align: center;
            /* Center text alignment */
            line-height: normal;
            /* Normal line-height for better spacing */
            padding: 10px;
            /* Optional padding */
            border-radius: 10px;
            /* Rounded corners */
            text-decoration: none;
            /* Remove underline from links */
        }

        .box i {
            margin-bottom: 50px;
            /* Space between the icon and label */
        }

        #b_admin {
            margin-left: 19%;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
            background-color: white;
        }

        /* Table Header Styling */
        table thead th {
            background-color: #f4b400;
            /* Yellow tone for headers */
            color: #fff;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #ddd;
        }

        /* Table Row Styling */
        table tbody td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        /* Alternate Row Colors */
        table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        table tbody tr:nth-child(even) {
            background-color: #f1f1f1;
        }

        /* Hover Effect on Table Rows */
        table tbody tr:hover {
            background-color: #fffbcc;
        }

        /* Button Styling */
        .btn {
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            color: #fff;
        }

        .btn-warning {
            background-color: #ffa500;
            border: none;
        }

        .btn-danger {
            background-color: #d9534f;
            border: none;
        }

        /* Search Box Styling */
        .search-box input {
            width: 300px;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }

        /* Responsive Table */
        @media screen and (max-width: 768px) {
            table {
                font-size: 14px;
            }

            .search-box input {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar-index">
        <div class="navbar-container-index">
            <img src="assets/img/jbest_logo.png" alt="" class="logo-index">
            <span class="navbar-title-index">JBEST LIBRARY MANAGEMENT SYSTEM</span>
        </div>
    </nav>

    <div class="card">
        <div class="container">
            <a href="admin/admin_login.php" class="box" id="b_admin">
                <i class="fas fa-user-shield fa-5x"></i>Admin</a>
            <a href="student/student-login.php" class="box" id="student">
                <i class="fas fa-user-graduate fa-5x"></i>Student</a>
            <a href="faculty/faculty-login.php" class="box" id="faculty">
                <i class="fas fa-chalkboard-teacher fa-5x"></i>Faculty</a>

        </div>

</body>

</html>