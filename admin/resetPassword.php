<?php
// resetPassword.php - step 2 of resetting a password: choose a new one.
// Only reachable for a few minutes after passing step 1 (forgotPassword.php).
session_start();
include('db.php');
include('password_reset_lib.php');

$type = resetAccountType(isset($_GET['type']) ? $_GET['type'] : 'user');
$isAdmin = $type === 'admin';
$email = resetVerifiedFor($type);
if ($email === null)
{
    header("location:forgotPassword.php?type=$type");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $newPassword = isset($_POST['txtNewPassword']) ? $_POST['txtNewPassword'] : '';
    $confirmPassword = isset($_POST['txtConfirmPassword']) ? $_POST['txtConfirmPassword'] : '';

    if (!resetCsrfValid(isset($_POST['csrf']) ? $_POST['csrf'] : ''))
    {
        $error = "Your session expired. Please start again.";
    }
    else if (strlen($newPassword) < 6)
    {
        $error = "Your new password must be at least 6 characters.";
    }
    else if (trim($newPassword) !== $newPassword)
    {
        $error = "Your new password can't start or end with a space.";
    }
    else if ($newPassword !== $confirmPassword)
    {
        $error = "The two passwords don't match.";
    }
    else if (resetSavePassword($con, $type, $email, $newPassword))
    {
        resetClearVerified();
        header("location:" . resetLoginPage($type) . "?reset=1");
        exit;
    }
    else
    {
        $error = "Something went wrong saving your password. Please try again.";
    }
}

$minutesLeft = max(1, (int)ceil(($_SESSION['reset_verified']['until'] - time()) / 60));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>New Password | Pet Shop</title>
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
      <h2>Almost done</h2>
      <p>Choose a new password you haven't used before.</p>
      <ul>
        <li><i class="fas fa-check-circle"></i> At least 6 characters</li>
        <li><i class="fas fa-check-circle"></i> Mix letters and numbers</li>
        <li><i class="fas fa-check-circle"></i> Don't share it with anyone</li>
      </ul>
    </div>
  </div>

  <div class="auth-form">
    <span class="auth-badge"><i class="fas fa-lock"></i> Step 2 of 2</span>
    <h1>Set a new password</h1>
    <p class="auth-sub">For <b><?php echo htmlspecialchars($email); ?></b>. This page stays open for about <?php echo $minutesLeft; ?> minute<?php echo $minutesLeft == 1 ? '' : 's'; ?>.</p>

    <?php if ($error != "") { ?>
      <div class="auth-alert error" role="alert"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
    <?php } ?>

    <form method="post" action="resetPassword.php?type=<?php echo $type; ?>" novalidate>
      <input type="hidden" name="csrf" value="<?php echo resetCsrfToken(); ?>">
      <div class="auth-field">
        <label for="txtNewPassword">New password</label>
        <div class="auth-input">
          <i class="fas fa-lock"></i>
          <input type="password" id="txtNewPassword" name="txtNewPassword" placeholder="At least 6 characters" minlength="6" required autocomplete="new-password" autofocus>
          <button type="button" class="auth-toggle" aria-label="Show password" onclick="togglePassword(this, 'txtNewPassword')"><i class="fas fa-eye"></i></button>
        </div>
      </div>
      <div class="auth-field">
        <label for="txtConfirmPassword">Confirm new password</label>
        <div class="auth-input">
          <i class="fas fa-lock"></i>
          <input type="password" id="txtConfirmPassword" name="txtConfirmPassword" placeholder="Type it again" minlength="6" required autocomplete="new-password">
          <button type="button" class="auth-toggle" aria-label="Show password" onclick="togglePassword(this, 'txtConfirmPassword')"><i class="fas fa-eye"></i></button>
        </div>
      </div>
      <button type="submit" class="auth-btn"><i class="fas fa-check"></i> Save new password</button>
    </form>

    <a class="auth-back" href="<?php echo resetLoginPage($type); ?>"><i class="fas fa-times"></i> Cancel</a>
  </div>
</div>
<script>
  function togglePassword(button, id) {
      var input = document.getElementById(id);
      var show = input.type === "password";
      input.type = show ? "text" : "password";
      button.innerHTML = show ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
      button.setAttribute("aria-label", show ? "Hide password" : "Show password");
  }
</script>
</body>
</html>
