<?php
// forgotPassword.php - step 1 of resetting a password: prove it's your account.
// Customers: registered email + phone number. Admin (?type=admin): admin email + recovery key.
session_start();
include('db.php');
include('password_reset_lib.php');
resetEnsureTable($con);

$type = resetAccountType(isset($_REQUEST['type']) ? $_REQUEST['type'] : 'user');
$isAdmin = $type === 'admin';
$error = "";
$email = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $email = strtolower(trim(isset($_POST['txtEmail']) ? $_POST['txtEmail'] : ''));
    $proof = trim(isset($_POST['txtProof']) ? $_POST['txtProof'] : '');

    if (!resetCsrfValid(isset($_POST['csrf']) ? $_POST['csrf'] : ''))
    {
        $error = "Your session expired. Please try again.";
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $error = "Please enter a valid email address.";
    }
    else if ($proof === '')
    {
        $error = $isAdmin ? "Please enter the recovery key." : "Please enter your phone number.";
    }
    else if (resetIsLocked($con, $type, $email))
    {
        $error = "Too many wrong tries. Please wait " . RESET_WINDOW_MINUTES . " minutes and try again.";
    }
    else if (resetVerify($con, $type, $email, $proof))
    {
        resetLogAttempt($con, $type, $email, true);
        resetMarkVerified($type, $email);
        header("location:resetPassword.php?type=$type");
        exit;
    }
    else
    {
        resetLogAttempt($con, $type, $email, false);
        // Same message whichever part is wrong, so this page never reveals who has an account
        $error = $isAdmin ? "Those details don't match. Check the email and recovery key." : "Those details don't match our records. Check your email and phone number.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Forgot Password | Pet Shop</title>
  <link rel="icon" href="../assets/images/favicon/favicon.png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/auth.css">
  <script src="../assets/js/theme.js"></script>
</head>
<body class="auth-page<?php echo $isAdmin ? ' auth-admin' : ''; ?>">
<button type="button" class="theme-toggle" id="themeToggle" data-theme-toggle aria-label="Switch to dark mode"><i class="fas fa-moon"></i><i class="fas fa-sun"></i></button>
<div class="auth-card">
  <div class="auth-side" style="background-image:url('../assets/images/<?php echo $isAdmin ? 'slider-image/sample-6.jpg' : 'blog-image/single-blog.jpg'; ?>'); background-position: 70% center;">
    <a class="auth-brand" href="../index.php"><span><i class="fas fa-paw"></i></span> Pet Shop</a>
    <div>
      <h2>Forgot your password?</h2>
      <p>No problem. Confirm it's your account and choose a new password.</p>
      <ul>
        <li><i class="fas fa-envelope"></i> Enter your account email</li>
        <?php if ($isAdmin) { ?>
          <li><i class="fas fa-key"></i> Enter the admin recovery key</li>
        <?php } else { ?>
          <li><i class="fas fa-phone"></i> Enter the phone number you registered with</li>
        <?php } ?>
        <li><i class="fas fa-lock"></i> Choose a new password</li>
      </ul>
    </div>
  </div>

  <div class="auth-form">
    <span class="auth-badge"><i class="fas fa-<?php echo $isAdmin ? 'user-shield' : 'user'; ?>"></i> Step 1 of 2</span>
    <h1>Confirm it's you</h1>
    <p class="auth-sub">
      <?php echo $isAdmin
          ? "Enter the admin email and the recovery key from <b>admin/recovery_config.php</b> on the server."
          : "Enter the email and phone number you used when you created your account."; ?>
    </p>

    <?php if ($error != "") { ?>
      <div class="auth-alert error" role="alert"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
    <?php } ?>

    <form method="post" action="forgotPassword.php?type=<?php echo $type; ?>" novalidate>
      <input type="hidden" name="csrf" value="<?php echo resetCsrfToken(); ?>">
      <div class="auth-field">
        <label for="txtEmail">Email</label>
        <div class="auth-input">
          <i class="fas fa-envelope"></i>
          <input type="email" id="txtEmail" name="txtEmail" value="<?php echo htmlspecialchars($email); ?>" placeholder="<?php echo $isAdmin ? 'admin@example.com' : 'you@example.com'; ?>" required autocomplete="email" autofocus>
        </div>
      </div>
      <div class="auth-field">
        <?php if ($isAdmin) { ?>
          <label for="txtProof">Recovery key</label>
          <div class="auth-input">
            <i class="fas fa-key"></i>
            <input type="password" id="txtProof" name="txtProof" placeholder="XXXX-XXXX-XXXX" required autocomplete="off">
            <button type="button" class="auth-toggle" aria-label="Show recovery key" onclick="toggleField(this, 'txtProof')"><i class="fas fa-eye"></i></button>
          </div>
        <?php } else { ?>
          <label for="txtProof">Registered phone number</label>
          <div class="auth-input">
            <i class="fas fa-phone"></i>
            <input type="tel" id="txtProof" name="txtProof" placeholder="10-digit mobile number" inputmode="numeric" required autocomplete="tel">
          </div>
        <?php } ?>
      </div>
      <button type="submit" class="auth-btn"><i class="fas fa-user-check"></i> Continue</button>
    </form>

    <a class="auth-back" href="<?php echo resetLoginPage($type); ?>"><i class="fas fa-arrow-left"></i> Back to login</a>
  </div>
</div>
<script>
  function toggleField(button, id) {
      var input = document.getElementById(id);
      var show = input.type === "password";
      input.type = show ? "text" : "password";
      button.innerHTML = show ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
  }
</script>
</body>
</html>
