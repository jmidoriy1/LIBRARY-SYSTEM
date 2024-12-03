<?php
include('includes/config.php');
session_start(); 
if(isset($_POST['change'])){
    $student_id = $_SESSION['student_id']; 
    $current_password = $_POST['password'];
    $new_password = $_POST['newpassword'];
    $confirm_password = $_POST['confirmpassword'];

    if($new_password != $confirm_password) {
        echo "<script>alert('New Password and Confirm Password do not match!');</script>";
    } else {
        $hashed_current_password = md5($current_password);
        $query = "SELECT password FROM tblstudents WHERE StudentID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stored_password = $row['password'];  

            if($hashed_current_password == $stored_password) {
                
                $hashed_new_password = md5($new_password);
                $update_query = "UPDATE tblstudents SET password = ? WHERE studentid = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("ss", $hashed_new_password, $student_id);
                if($update_stmt->execute()) {
                    echo "<script>alert('Password successfully changed!');</script>";
                } else {
                    echo "<script>alert('Error updating password.');</script>";
                }
            } else {
                echo "<script>alert('Current password is incorrect!');</script>";
            }
        } else {
            echo "<script>alert('Student not found!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Change Password</title>

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
<script type="text/javascript">
function valid()
{
if(document.chngpwd.newpassword.value!= document.chngpwd.confirmpassword.value)
{
alert("New Password and Confirm Password Field do not match  !!");
document.chngpwd.confirmpassword.focus();
return false;
}
return true;
}
</script>

<body>
    
<?php include('includes/header.php');?>
<div class="content-wrapper">
<div class="container">
<div class="row pad-botm">
<div class="col-md-12">
<h4 class="header-line">User Change Password</h4>
</div>
</div>
               
<div class="row">
<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3" >
<div class="panel panel-info">
<div class="panel-heading">
Change Password
</div>
<div class="panel-body">
<form role="form" method="post" onSubmit="return valid();" name="chngpwd">
    <div class="form-group">
        <label>Current Password</label>
        <input class="form-control" type="password" name="password" autocomplete="off" required />
    </div>

    <div class="form-group">
        <label>Enter Password</label>
        <input class="form-control" type="password" name="newpassword" autocomplete="off" required />
    </div>

    <div class="form-group">
        <label>Confirm Password </label>
        <input class="form-control" type="password" name="confirmpassword" autocomplete="off" required />
    </div>

    <button type="submit" name="change" class="btn btn-info">Change Password</button> 
</form>

 </div>
</div>
</div>
</div>  

</body>
</html>