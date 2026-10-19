<?php
  session_start();
  include('db.php');

  if(isset($_SESSION['adminId']))
  {
      header('location:dashboard.php');
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Admin Login | Pet Shop</title>
  <link rel="icon" href="../assets/images/favicon/favicon.png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/auth.css">
  <script src="../assets/js/theme.js"></script>
</head>
<body class="auth-page auth-admin">
<button type="button" class="theme-toggle" id="themeToggle" data-theme-toggle aria-label="Switch to dark mode"><i class="fas fa-moon"></i><i class="fas fa-sun"></i></button>
<div class="auth-card">
  <div class="auth-side" style="background-image:url('../assets/images/slider-image/sample-6.jpg'); background-position: 72% center;">
    <a class="auth-brand" href="../index.php"><span><i class="fas fa-paw"></i></span> Pet Shop</a>
    <div>
      <h2>Admin Panel</h2>
      <p>Manage your shop from one place.</p>
      <ul>
        <li><i class="fas fa-box"></i> Products, categories and stock</li>
        <li><i class="fas fa-receipt"></i> Orders and bookings</li>
        <li><i class="fas fa-users"></i> Customers and staff</li>
      </ul>
    </div>
  </div>

  <div class="auth-form">
    <span class="auth-badge"><i class="fas fa-user-shield"></i> Admin Login</span>
    <h1>Sign in to dashboard</h1>
    <p class="auth-sub">For shop administrators only.</p>

    <?php if (isset($_GET['reset'])) { ?>
      <div class="auth-alert success" role="status"><i class="fas fa-check-circle"></i> Your password has been changed. Log in with your new password.</div>
    <?php } ?>

    <form action="" method="post">
      <div class="auth-field">
        <label for="txtEmail">Admin Email</label>
        <div class="auth-input">
          <i class="fas fa-envelope"></i>
          <input type="email" id="txtEmail" name="txtEmail" placeholder="admin@example.com" data-validation="required" autocomplete="username">
        </div>
      </div>
      <div class="auth-field">
        <div class="auth-label-row"><label for="txtPassword">Password</label><a class="auth-forgot" href="forgotPassword.php?type=admin">Forgot password?</a></div>
        <div class="auth-input">
          <i class="fas fa-lock"></i>
          <input type="password" id="txtPassword" name="txtPassword" placeholder="Enter your password" data-validation="required" autocomplete="current-password">
          <button type="button" class="auth-toggle" aria-label="Show password" onclick="togglePassword(this)"><i class="fas fa-eye"></i></button>
        </div>
      </div>
      <button type="submit" class="auth-btn"><i class="fas fa-sign-in-alt"></i> Sign In</button>
    </form>

    <div class="auth-divider">or</div>
    <div class="auth-links">
      <a class="auth-link" href="userLogin.php"><i class="fas fa-user"></i> Customer login</a>
    </div>
    <a class="auth-back" href="../index.php"><i class="fas fa-arrow-left"></i> Back to Pet Shop</a>
  </div>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>

<!-- Validation -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<script>
    $.validate();
    function togglePassword(button) {
        var input = document.getElementById("txtPassword");
        var show = input.type === "password";
        input.type = show ? "text" : "password";
        button.innerHTML = show ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
        button.setAttribute("aria-label", show ? "Hide password" : "Show password");
    }
</script>

<!-- SweetAlert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


</body>
</html>

<?php
if(isset($_REQUEST['txtEmail']))
{
    $email= $_REQUEST['txtEmail'];
    $password= $_REQUEST['txtPassword'];

    $email = trim($email);
    $password = trim($password);

     if($email!="" && $password!="")
     {
            $sql="select * from admin where email='$email' and pass='$password'";

            $r=mysqli_query($con,$sql);
            if($r  && mysqli_num_rows($r)==1){
                    $res=mysqli_fetch_assoc($r);
                    $_SESSION['adminId']=$res['id'];
                    $_SESSION['adminName']=$res['name'];
                    $_SESSION['adminEmail']=$res['email'];
                    $_SESSION['adminPassword']=$res['pass'];
                    $_SESSION['adminDate']=$res['dt'];

                    // header("location:dashboard.php");
                    // echo '<script> swal("Login Successfull.", "", "success");
                    // </script>';
                    echo "<script type='text/javascript'>  window.location='dashboard.php'; </script>";
            }
            else
            {
                echo '<script> swal("Username Or Password is Incorrect.", "Check your Username And Password And Try Again.", "error");
                    </script>';
            }

     }
     else
     {
      echo '<script> swal("Username And Password can not be Empty.", "White Space or Blank Data Not Allowed.", "info");
      </script>';
     }
}


?>
