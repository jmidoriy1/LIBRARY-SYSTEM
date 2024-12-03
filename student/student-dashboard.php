<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>

    <!-- Google Fonts Preconnect and Stylesheet -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=K2D:wght@100;200;300;400;500;600;700;800&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome (only one version) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="assets/css/student.css">


    <!-- Bootstrap JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (only if needed) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jque 1ry/3.5.1/jquery.min.js"></script>

   
</head>

<body>

    <?php include('includes/header.php'); ?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Dashboard</h4>

                </div>

            </div>

            <div class="row box">

                <div class="dashboard-card">
                    <i class="fa fa-bars fa-5x"></i>
                    <p>Book Issued</p>
                </div>


                <div class="dashboard-card">
                    <i class="fa fa-recycle fa-5x"></i>
                    <p>Books Not Returned Yet</p>
                </div>
            </div>
        </div>
    </div>



    </div>
    </div>

</html>