<?php include_once("checkLogin.php"); 
include_once("functions.php");

if(isset($_GET['getData']))
{
	$id = $_GET['getData'];
    $data = selectData("SELECT * FROM `category` where id='$id'");
    if(is_array($data) && count($data) >= 1)
    {
	    $data = $data[0];

	    $name = $data['name'];
    }
    else
    {
		runJavascript("alert('Data not found !!!.'); window.location = 'viewstaff.php'");
    }
}
?>
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

	<!-- Functions JS -->
	<script src="functions.js"></script>
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

<!-- Include Header -->
<?php 
$he = 3; 
include_once("header.php");


?>
<!-- Include Header End -->

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1><?php echo (isset($_GET['getData']) ? "Update" : "Add") ?> Category</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="userDashboard.php">Dashboard</a></li>
							<li class="breadcrumb-item active">Add Category</li>
						</ol>
					</div>
				</div>
			</div>
            <!-- /.container-fluid -->
		</section>

		<!-- Main content -->
		<section class="content">

		<div class="col-md-12">
		<div class="card card-success">
			<!-- <div class="card-header text-center">
										<span class="h3">Update Your Profile Details</span>
			</div> -->
			<!-- <div class="card-header">
				<h3 class="card-title">Enter Your Details for New Connection</h3>
			</div> -->
			<!-- /.card-header -->
			<!-- form start -->
			<form method="POST" enctype="multipart/form-data">
				<div class="card-body">
						<div class="row">
								<div class="col-md-6">
									<div class="form-group">
											<input type="text" class="form-control" id="id" name="id" value="<?php echo @$id; ?>" autocomplete="off" hidden>
                                            <input type="hidden" class="form-control" id="old_photo" name="old_photo" value="<?php echo @$data['image']; ?>">
											<label for="name">Name <small style="color: red;"><b>*</b></small></label>
											<input type="text" class="form-control" id="name" name="name" value="<?php echo @$name; ?>" placeholder="Enter Name" data-validation="required" >
									</div>
								</div>
                                <div class="col-md-6">
                                   <div class="form-group">
                                        <label for="photo">Photo  <?php if(!isset($data)) { ?><small style="color: red;"><b>*</b></small> <?php } ?></label>
                                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*" 
										<?php if(!isset($data)) { echo 'data-validation="required"'; } ?>>
                                   </div>
                              	</div>
								<br><br>
								<div class="col-md-12">
											<div class="form-group">
													<button type="submit" class="btn btn-success btn-block">Submit</button>
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
		//  addDecimalValidationEvent("txtPrice");
		 </script>

		 <!-- SweetAlert -->
		 <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


</body>
</html>
<?php



if(isset($_POST['name']))
{
	$id = $_REQUEST['id'];
	$name = $_REQUEST['name'];
	$photo = $_REQUEST['old_photo'];
    $dt = date("Y-m-d");

	if(isset($_FILES['photo']['name']))
    {
	   $uploadResult = uploadFile($_FILES,"photo","upload_images/", array("png","jpg","jpeg") );
	 
	   if($uploadResult['status'] == 'success')
	   {
		   $photo = $uploadResult['image'];
	   }
    }

	$message = "";
	$sql = "";

	if($id == "") // insert
	{
		$sql = "INSERT INTO `category`(`name`, `image`, `dt`) VALUES ('$name','$photo','$dt')";
		$message = "Category Added Successfully.";
	}	
	else
	{
		$sql = "UPDATE `category` set `name`='$name', `image`='$photo' where id='$id' ";
		$message = "Category Updated Successfully.";
	}
			
	if(insert_update_delete_data($sql) == true)
	{
		$msg = getSwalMessgage("$message","",
			'window.location = "category.php"','window.location = "category.php"',"success");
			runJavascript($msg);
	}
	else
	{
		runJavascript('swal("Error !!!.", "", "error")');
	}
}

?>