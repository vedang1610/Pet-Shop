<?php include_once("checkLogin.php"); 
include_once("functions.php");

if(isset($_GET['getData']))
{
	$id = $_GET['getData'];
    $data = selectData("SELECT * FROM `products` where id='$id'");
    if(is_array($data) && count($data) >= 1)
    {
	    $data = $data[0];

	    $name = $data['name'];
    }
    else
    {
		runJavascript("alert('Data not found !!!.'); window.location = 'products.php'");
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
$he = 4; 
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
                                        <label for="category">Category <small style="color: red;"><b>*</b></small></label>
                                        <select class="form-control" name="category" id="category" data-validation="required">
                                            <option value="">Select Category</option>
                                            <?php
                                            $category = selectData("SELECT * FROM `category`");
                                            if(is_array($category) && count($category) >= 1)
                                            {
                                                foreach ($category as $row)
                                                {
                                                    $selected = @$data['category_id'] == $row['id'] ? "selected" : "";
                                                   echo "<option value='".$row['id']."' $selected>".$row['name']."</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
									<div class="form-group">
											<input type="text" class="form-control" id="id" name="id" value="<?php echo @$id; ?>" autocomplete="off" hidden>
                                            <input type="hidden" class="form-control" id="old_photo" name="old_photo" value="<?php echo @$data['image']; ?>">
											<label for="name">Product Name <small style="color: red;"><b>*</b></small></label>
											<input type="text" class="form-control" id="name" name="name" value="<?php echo @$name; ?>" placeholder="Enter Product Name" data-validation="required" >
									</div>
								</div>
                                <div class="col-md-6">
									<div class="form-group">
											<label for="price">Price <small style="color: red;"><b>*</b></small></label>
											<input type="text" class="form-control" id="price" name="price" value="<?php echo @$data['price']; ?>" placeholder="Enter Price" data-validation="required" 
                                            onpaste="return false;" ondrop="return false;">
									</div>
								</div>
                                <div class="col-md-6">
                                   <div class="form-group">
                                        <label for="photo">Image  <?php if(!isset($data)) { ?><small style="color: red;"><b>*</b></small> <?php } ?></label>
                                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*" 
										<?php if(!isset($data)) { echo 'data-validation="required"'; } ?>>
                                   </div>
                              	</div>
                                <div class="col-md-6">
										<div class="form-group">
											<label for="description">Description <small style="color: red;"><b>*</b></small></label>
											<textarea class="form-control" id="description" name="description" data-validation="required"><?php echo @$data['description']; ?></textarea>
										</div>
								</div>
                                <div class="col-md-6">
                                   <div class="form-group">
                                        <label for="photo2">Product Images</label>
                                        <input type="file" class="form-control" id="photo2" name="photo2[]" accept="image/*" multiple>
                                   </div>
                              	</div>
                                <div class="col-md-12">
                                   <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="status" name="status" <?php if(@$data['status'] == 1){echo "checked";} ?>>
                                        <label class="form-check-label" for="status">is Active ?</label>
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
		 addDecimalValidationEvent("price");
		 </script>

		 <!-- SweetAlert -->
		 <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


</body>
</html>
<?php


// echo "<pre>";
if(isset($_POST['name']))
{
	$id = $_REQUEST['id'];
    $category = $_REQUEST['category'];
	$name = $_REQUEST['name'];
    $price = $_REQUEST['price'];
    $description = $_REQUEST['description'];
	$photo = $_REQUEST['old_photo'];
    $dt = date("Y-m-d");
    $status = 0;
    if(isset($_POST['status']))
    {
        $status = 1;
    }

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
		$sql = "INSERT INTO `products`(`category_id`, `name`, `image`, `description`, `price`, `status`, `dt`) 
                                VALUES('$category','$name','$photo','$description','$price','$status','$dt')";
		$message = "Product Added Successfully.";
	}	
	else
	{
		$sql = "UPDATE `products` set 
                    `category_id`='$category', 
                    `name`='$name', 
                    `image`='$photo', 
                    `description`='$description', 
                    `price`='$price', 
                    `status`='$status' 
                    where id='$id' ";
		$message = "Product Updated Successfully.";
	}
			
	if(insert_update_delete_data($sql) == true)
	{
        $lastProduct = selectData("SELECT * FROM `products` order by id desc");
        if(is_array($lastProduct) && count($lastProduct) >= 1)
        {
            $lastProduct = $lastProduct[0];
            $productId = $lastProduct['id']; 
            if(isset($_FILES['photo2']))
            {        
                $index = 0;
                foreach ($_FILES['photo2']['name'] as $row) 
                {
                    $uploadResult = uploadFile2($_FILES,$index,"photo2","upload_images/", array("png","jpg","jpeg") );
                    if($uploadResult['status'] == 'success')
                    {
                        $photo = $uploadResult['image'];
                        $sql2 = "INSERT INTO `product_images`(`product_id`, `image`) VALUES ('$productId','$photo')";
                        insert_update_delete_data($sql2);
                    }
                    $index ++;
                }
            }
        
		    $msg = getSwalMessgage("$message","",
			'window.location = "products.php"','window.location = "products.php"',"success");
			runJavascript($msg);
        }
	}
	else
	{
		runJavascript('swal("Error !!!.", "", "error")');
	}
}

?>