<?php
session_start();
include('admin/db.php');
include('admin/functions.php');
// if(isset($_SESSION['userId']))
// {
//     header('location:userDashboard.php');
// }
?>
<!DOCTYPE html>
<html class="no-js" lang="en-US">
    
<head>
        <meta charset="UTF-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <title>Pet Shop</title>
        <meta name="description" content="240+ Best Bootstrap Templates are available on this website. Find your template for your project compatible with the most popular HTML library in the world."/>
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
        <link rel="profile" href="https://gmpg.org/xfn/11">
        <link rel="canonical" href="Replace_with_your_PAGE_URL.html" />

        <!-- Open Graph (OG) meta tags are snippets of code that control how URLs are displayed when shared on social media  -->
        <meta property="og:locale" content="en_US" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="Add a Title" />
        <meta property="og:url" content="PAGE_URL" />
        <meta property="og:site_name" content="SITE_NAME" />
        <!-- For the og:image content, replace the # with a link of an image -->
        <meta property="og:image" content="#" />
        <meta property="og:description" content="Add_Description_Text" />
        <meta name="robots" content="noindex, follow" />
        <!-- Add site Favicon -->
        <link rel="icon" href="assets/images/favicon/favicon.png" sizes="32x32" />
        <link rel="icon" href="assets/images/favicon/favicon.png" sizes="192x192" />
        <link rel="apple-touch-icon" href="assets/images/favicon/favicon.png" />
        <meta name="msapplication-TileImage" content="assets/images/favicon/favicon.png" />
        <!-- Structured Data  -->
        <script type="application/ld+json">
        {
          "@context": "http://schema.org",
          "@type": "WebSite",
          "name": "Replace_with_your_site_title",
          "url": "Replace_with_your_site_URL"
        }
        </script>
        <!-- Google Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap&amp;subset=cyrillic,cyrillic-ext,latin-ext,vietnamese" type="text/css" media="all">

        <!-- CSS
  ============================================ -->

            <!-- Vendor CSS (Bootstrap & Icon Font) -->
       <!--   <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/vendor/ionicons.min.css">
        <link rel="stylesheet" href="assets/css/vendor/linearicon.css">
        <link rel="stylesheet" href="assets/css/vendor/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/vendor/line-awesome.css"> -->

            <!-- Plugins CSS (All Plugins Files) -->
        <!--  <link rel="stylesheet" href="assets/css/plugins/animate.css">
        <link rel="stylesheet" href="assets/css/plugins/jquery-ui.min.css">
        <link rel="stylesheet" href="assets/css/plugins/slick.css"> -->

        <!-- Use the minified version files listed below for better performance and remove the files listed above -->
        <link rel="stylesheet" href="assets/css/vendor/vendor.min.css" />
        <link rel="stylesheet" href="assets/css/plugins/plugins.min.css" />
        <!-- <link rel="stylesheet" href="assets/css/style.min.css"> -->

        <!-- Main Style CSS -->
        <link rel="stylesheet" href="assets/css/style.css" />
        <link rel="stylesheet" href="assets/css/register.css" />
    </head>

    <body>
    <!-- Header -->
    <?php include("header.php"); ?>


    

    <div class="offcanvas-overlay"></div>

    <!-- Content Here -->
     <!-- Breadcrumb Area Start -->
     <div class="breadcrumb-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcrumb-content">
                            <ul class="nav">
                                <li><a href="index.php">Home</a></li>
                                <li>Login / Register</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Breadcrumb Area End-->

    <!-- Register area start -->
    <div class="reg-area">
        <div class="container">
            <div class="reg-card">
                <div class="reg-side reg-login" id="loginPanel">
                    <span class="reg-badge"><i class="las la-paw"></i> Welcome back</span>
                    <h2>Login</h2>
                    <p>Already have an account? Sign in to shop and track your orders.</p>

                    <form id="loginForm" action="admin/userLogin.php" method="post">
                        <div class="form-group">
                            <label for="loginEmail">Email</label>
                            <div class="reg-input">
                                <i class="las la-envelope"></i>
                                <input type="email" class="form-control" id="loginEmail" name="txtEmail" placeholder="you@example.com" data-validation="required" autocomplete="email">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="reg-label-row"><label for="loginPassword">Password</label><a class="reg-forgot" href="admin/forgotPassword.php">Forgot password?</a></div>
                            <div class="reg-input">
                                <i class="las la-lock"></i>
                                <input type="password" class="form-control" id="loginPassword" name="txtPassword" placeholder="Enter your password" data-validation="required" autocomplete="current-password">
                                <button type="button" class="reg-eye" onclick="toggleLoginPassword(this)" aria-label="Show password"><i class="las la-eye"></i></button>
                            </div>
                        </div>
                        <button type="submit" class="reg-btn reg-btn-primary reg-btn-full"><i class="las la-sign-in-alt"></i> Login</button>
                    </form>

                    <div class="reg-or"><span>New here?</span></div>
                    <p class="reg-login-note"><i class="las la-arrow-right"></i><span>Fill in the form <span class="d-none d-lg-inline">on the right</span><span class="d-lg-none">below</span> to create your free account.</span></p>
                </div>

                <div class="reg-form">
                    <h1>Create an account</h1>
                    <p class="reg-sub">New to Pet Shop? Create your free account below.</p>

                    <form id="userForm" method="POST" enctype="multipart/form-data">
                        <input type="text" id="txtId" name="txtId" value="" autocomplete="off" hidden>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="txtName">Full Name <span class="req">*</span></label>
                                    <div class="reg-input">
                                        <i class="las la-user"></i>
                                        <input type="text" class="form-control" id="txtName" name="txtName" placeholder="Enter your name" data-validation="required" autocomplete="name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="txtPhone">Phone <span class="req">*</span></label>
                                    <div class="reg-input">
                                        <i class="las la-phone"></i>
                                        <input type="number" class="form-control" id="txtPhone" name="txtPhone" placeholder="10-digit mobile number" data-validation="required" minlength="10" maxlength="10" autocomplete="tel">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="txtEmail">Email <span class="req">*</span></label>
                                    <div class="reg-input">
                                        <i class="las la-envelope"></i>
                                        <input type="text" class="form-control" id="txtEmail" name="txtEmail" placeholder="you@example.com" data-validation="required" autocomplete="email">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="txtPassword">Password <span class="req">*</span></label>
                                    <div class="reg-input">
                                        <i class="las la-lock"></i>
                                        <input type="password" class="form-control" id="txtPassword" name="txtPassword" placeholder="Choose a password" data-validation="required" autocomplete="new-password">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="txtAddress">Address <span class="req">*</span></label>
                                    <div class="reg-input reg-textarea">
                                        <i class="las la-map-marker"></i>
                                        <textarea class="form-control" id="txtAddress" name="txtAddress" placeholder="House no., street, area, city" data-validation="required"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="idProofImage">ID Proof Image <span class="req">*</span></label>
                                    <input type="file" class="form-control" id="idProofImage" name="idProofImage" accept="image/*" data-validation="required">
                                    <small class="reg-hint">PNG, JPG or JPEG</small>
                                </div>
                            </div>
                        </div>
                        <div class="reg-actions">
                            <a href="index.php" class="reg-btn reg-btn-outline">Cancel</a>
                            <button type="submit" class="reg-btn reg-btn-primary"><i class="las la-user-plus"></i> Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Register area end -->
        
        
        <!-- Footer -->
        <?php include("footer.php"); ?>

      
        <!-- Modal end -->
        <!-- JS
============================================ -->

            <!-- Vendors JS -->
        <!-- <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
        <script src="assets/js/vendor/bootstrap.bundle.min.js"></script>
        <script src="assets/js/vendor/jquery-migrate-3.3.2.min.js"></script>
        <script src="assets/js/vendor/modernizr-3.11.2.min.js"></script> -->

            <!-- Plugins JS -->
         <!-- <script src="assets/js/plugins/jquery-ui.min.js"></script>
        <script src="assets/js/plugins/slick.js"></script>
        <script src="assets/js/plugins/countdown.js"></script>
        <script src="assets/js/plugins/scrollup.js"></script>
        <script src="assets/js/plugins/ripples.js"></script>
        <script src="assets/js/plugins/elevateZoom.js"></script></script> -->

        <!-- Use the minified version files listed below for better performance and remove the files listed above -->
        <script src="assets/js/vendor/vendor.min.js"></script>
        <script src="assets/js/plugins/plugins.min.js"></script>

        <!-- Main Activation JS -->
        <script src="assets/js/main.js"></script>

        <!-- Validation -->
     <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
     <script>
     $.validate();
     function toggleLoginPassword(button) {
         var input = document.getElementById("loginPassword");
         var show = input.type === "password";
         input.type = show ? "text" : "password";
         button.innerHTML = show ? '<i class="las la-eye-slash"></i>' : '<i class="las la-eye"></i>';
         button.setAttribute("aria-label", show ? "Hide password" : "Show password");
     }
     </script>

     <!-- SweetAlert -->
     <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
     
    </body>

</html>
<?php

// include('admin/functions.php');
if(isset($_POST['txtName']))
{
    if(isset($_FILES['idProofImage']['name']))
    {
     //     print_r($_FILES);
	   $uploadResult = uploadFile($_FILES,"idProofImage","admin/upload_images/", array("png","jpg","jpeg") );
	   
	   if($uploadResult['status'] == 'success')
	   {
			$name = $_REQUEST['txtName'];
			$phone = $_REQUEST['txtPhone'];
			$email = $_REQUEST['txtEmail'];
			$password = $_REQUEST['txtPassword'];
			$address = $_REQUEST['txtAddress'];
			$status = 1;//$_REQUEST['isActive'] == "true" ? "active" : "inactive";
			$idproof = $uploadResult['image'];
		
			$sql = "INSERT INTO `users`(`name`,`email`, `pass`, `phone`,`address`, `idproof`, `status`) 
				VALUES ('$name','$email','$password','$phone','$address','$idproof','$status')";
			if(insert_update_delete_data($sql) == true)
			{
                    $msg = getSwalMessgage("You're Registered Successfully, please Login.","",'window.location = "admin/userLogin.php"','window.location = "admin/userLogin.php"',"success");
                    runJavascript($msg);
			}
			else
			{
				runJavascript('swal("Error !!!.", "", "error")');
			}
	   }
	   else
	   {
               runJavascript('swal("Error !!!.", "", "error")');
	   }
    }

  }

?>