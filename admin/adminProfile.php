<?php include_once("checkLogin.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Admin Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

<!-- Include Header -->
<?php 
$he = 9; 
include_once("header.php");
include_once("functions.php");
$userId = $_SESSION['adminId'];
$userArray = selectData("SELECT * FROM `admin` where id='$userId'");
if(is_array($userArray) && count($userArray) >= 1)
{
     $userArray = $userArray[0];
}
?>
<!-- Include Header End -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Admin Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="userDashboard.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Admin Profile</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="col-md-12">
		<div class="card card-primary">
			<div class="card-header text-center">
                    <span class="h3">Update Your Profile Details</span>
			</div>
			<!-- <div class="card-header">
				<h3 class="card-title">Enter Your Details for New Connection</h3>
			</div> -->
			<!-- /.card-header -->
			<!-- form start -->
			<form method="POST">
				<div class="card-body">
                         <div class="row">
                              <div class="col-md-12">
                                   <div class="form-group">
                                        <input type="text" class="form-control" id="txtId" name="txtId" 
                                        value="<?php echo $userArray['id']; ?>" autocomplete="off" hidden>
                                        <label for="txtName">Name <small style="color: red;"><b>*</b></small></label>
                                        <input type="text" class="form-control" id="txtName" name="txtName" placeholder="Enter Name" data-validation="required" value="<?php echo $userArray['name']; ?>">
                                   </div>
                              </div>
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label for="txtEmail">Email <small style="color: red;"><b>*</b></small></label>
                                        <input type="text" class="form-control" id="txtEmail" name="txtEmail" placeholder="Enter Email" data-validation="required" value="<?php echo $userArray['email']; ?>">
                                   </div>
                              </div>
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label for="txtPassword">Password <small style="color: red;"><b>*</b></small></label>
                                        <input type="password" class="form-control" id="txtPassword" name="txtPassword" 
                                        placeholder="Enter Password" data-validation="required" value="<?php echo $userArray['pass']; ?>">
                                   </div>
                              </div>
                              <br><br>
                              <div class="col-md-12">
                                   <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">Update</button>
                                   </div>
                              </div>
                         </div>
				</div>
			</form>
		</div>
	</div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Include Footer -->
  <?php include_once("footer.php"); ?>
  <!-- Include Footer End -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<!-- Validation -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
     <script>
     $.validate();
     </script>

     <!-- SweetAlert -->
     <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


</body>
</html>

<?php

include_once('functions.php');

if(isset($_POST['txtId']))
{
     $id = $_REQUEST['txtId'];
     $name = $_REQUEST['txtName'];
     $email = $_REQUEST['txtEmail'];
     $password = $_REQUEST['txtPassword'];
     $status = 1;//$_REQUEST['isActive'] == "true" ? "active" : "inactive";
     // $idproof = $uploadResult['image'];

     $sql = "UPDATE `admin` set
               `name` = '$name',
               `email` = '$email', 
               `pass` = '$password' 
               where id = '$id' ";
    //  echo $sql;
     if(insert_update_delete_data($sql) == true)
     {
            $msg = getSwalMessgage("Profile Updated Successfully.","",'window.location = "adminProfile.php"','window.location = "adminProfile.php"',"success");
		    runJavascript($msg);
     }
     else
     {
          runJavascript('swal("Error !!!.", "", "error")');
     }

  }

?>
