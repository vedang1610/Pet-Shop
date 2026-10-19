<?php
// userHeader.php - customer "My Account" top bar and sidebar (included right after <div class="wrapper">).
// $he = the active menu item: 1 Dashboard, 2 Orders, 3 Profile.
$he = isset($he) ? $he : 0;
$customerName = isset($_SESSION['userName']) && $_SESSION['userName'] != "" ? $_SESSION['userName'] : "Customer";
$customerInitial = strtoupper(substr(trim($customerName), 0, 1));
$pageTitles = array(1 => "Dashboard", 2 => "My Orders", 3 => "My Profile");
$pageTitle = isset($pageTitles[$he]) ? $pageTitles[$he] : "My Account";
?>
<script src="../assets/js/theme.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap">
<link rel="stylesheet" href="dist/css/admin-theme.css">
<script src="../assets/js/image-knockout.js" defer></script>

<!-- Top bar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav align-items-center">
      <li class="nav-item">
        <a class="nav-link ad-icon-btn" data-widget="pushmenu" href="#" role="button" aria-label="Toggle menu"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-block">
        <div class="ad-topbar-title"><?php echo $pageTitle; ?><small>My Pet Shop account</small></div>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto align-items-center">
      <li class="nav-item">
        <a class="nav-link ad-shop-link" href="../products.php" title="Continue shopping"><i class="fas fa-store"></i><span>Shop</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link ad-icon-btn" href="../cart.php" title="My cart" aria-label="My cart"><i class="fas fa-shopping-cart"></i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link ad-icon-btn theme-nav-toggle" href="#" role="button" data-theme-toggle aria-label="Switch to dark mode">
          <i class="fas fa-moon"></i><i class="fas fa-sun"></i>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link ad-user-btn" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">
          <span class="ad-avatar"><?php echo htmlspecialchars($customerInitial); ?></span>
          <span class="name d-none d-md-inline"><?php echo htmlspecialchars($customerName); ?></span>
          <i class="fas fa-chevron-down" style="font-size:.7rem;"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <a href="userProfile.php" class="dropdown-item"><i class="fas fa-user-edit"></i> My profile</a>
          <a href="userOrders.php" class="dropdown-item"><i class="fas fa-receipt"></i> My orders</a>
          <a href="../index.php" class="dropdown-item"><i class="fas fa-home"></i> Back to shop</a>
          <div class="dropdown-divider"></div>
          <a href="userLogout.php" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
      </li>
    </ul>
</nav>

<!-- Sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="../index.php" class="brand-link">
      <span class="ad-brand-mark"><i class="fas fa-paw"></i></span>
      <span class="brand-text">Pet Shop<small>My account</small></span>
    </a>

    <div class="sidebar">
      <div class="ad-user-card">
        <span class="ad-avatar"><?php echo htmlspecialchars($customerInitial); ?></span>
        <div class="ad-user-info">
          <b><?php echo htmlspecialchars($customerName); ?></b>
          <span>Customer</span>
        </div>
      </div>

      <div class="ad-menu-label">Menu</div>
      <nav>
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="userDashboard.php" class="nav-link <?php echo ($he == 1) ? "active" : ""; ?>">
                <i class="nav-icon fas fa-th-large"></i><p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="userOrders.php" class="nav-link <?php echo ($he == 2) ? "active" : ""; ?>">
                <i class="nav-icon fas fa-receipt"></i><p>My Orders</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="userProfile.php" class="nav-link <?php echo ($he == 3) ? "active" : ""; ?>">
                <i class="nav-icon fas fa-user-edit"></i><p>Profile</p>
              </a>
            </li>
        </ul>
      </nav>

      <div class="ad-menu-label">Shop</div>
      <nav>
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="../products.php" class="nav-link"><i class="nav-icon fas fa-store"></i><p>Continue shopping</p></a>
            </li>
            <li class="nav-item">
              <a href="../cart.php" class="nav-link"><i class="nav-icon fas fa-shopping-cart"></i><p>My cart</p></a>
            </li>
            <li class="nav-item ad-logout">
              <a href="userLogout.php" class="nav-link"><i class="nav-icon fas fa-sign-out-alt"></i><p>Logout</p></a>
            </li>
        </ul>
      </nav>
    </div>
</aside>
