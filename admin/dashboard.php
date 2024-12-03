<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
   
    <!-- Google Fonts Preconnect and Stylesheet -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=K2D:wght@100;200;300;400;500;600;700;800&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome (only one version) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="assets/css/admin.css">

    <!-- Bootstrap JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (only if needed) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


    <style>
        

        .content-wrapper {
            padding: 20px;
            background-color: #DBCE15;
            border-radius: 8px; 
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 1200px;
        }

        .header-line {
            font-size: 28px;
            color: #0A670C;
            font-weight: bold;
            margin-bottom: 20px;
            border-bottom: 4px solid #0a670c;
            padding-bottom: 10px;
        }

        .pad-botm {
            margin-bottom: 20px;
        }

        .dashboard-container {
            padding: 20px;
            text-align: center;
        }

        .dashboard-container h2 {
            color: #0A670C;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .dashboard-cards {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .dashboard-card {
            background-color: #0A670C;
            color: #DBCE15;
            padding: 20px;
            width: 200px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .dashboard-card:hover {
            transform: scale(1.05);
        }

        .dashboard-card img {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }

        .dashboard-card p {
            font-size: 16px;
            font-weight: bold;
        }

        .back-widget-set {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .back-widget-set:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        }

        .back-widget-set i {
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .back-widget-set h3 {
            font-size: 18px;
            color: #34495e;
            font-weight: bold;
            margin-top: 10px;
        }

        .alert-success {
            background-color: #e9f7ef;
            border: 1px solid #27ae60;
        }

        .alert-info {
            background-color: #ebf5fb;
            border: 1px solid #3498db;
        }

        .alert-warning {
            background-color: #fef5e6;
            border: 1px solid #f39c12;
        }

        .alert-danger {
            background-color: #f9ebea;
            border: 1px solid #e74c3c;
        }

        .text-center {
            text-align: center;
        }

        @media (max-width: 768px) {
            .col-md-3 {
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">ADMIN DASHBOARD</h4>
                </div>
                <div class="dashboard-container">

                    <div class="dashboard-cards">
                        <div class="dashboard-card">
                            <i class="fa fa-book fa-5x"></i>
                            <p>Books Listed</p>
                        </div>
                        <div class="dashboard-card">
                            <i class="fa fa-bars fa-5x"></i>
                            <p>Times Books Issued</p>
                        </div>
                        <div class="dashboard-card">
                            <i class="fa fa-recycle fa-5x"></i>
                            <p>Times Books Returned</p>
                        </div>
                        <div class="dashboard-card">
                            <i class="fa fa-users fa-5x"></i>
                            <p>Registered Users</p>
                        </div>
                        <div class="dashboard-card">
                            <i class="fa fa-user fa-5x"></i>
                            <p>Publications Listed</p>
                        </div>
                        <div class="dashboard-card">
                            <i class="fa fa-folder fa-5x"></i>
                            <p>Listed Categories</p>
                        </div>
                        <div class="dashboard-card">
                        <i class="fa fa-money-bill-alt fa-5x"></i>
                            <p>Current Fine Per Day</p>
                        </div>
                    </div>
                </div>
</body>

</html>