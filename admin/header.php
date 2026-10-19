<?php
// header.php - admin top bar and sidebar (included right after <div class="wrapper"> on every admin page).
// $he = the active menu item: 1 Dashboard, 2 Orders, 3 Category, 4 Products, 5 Users, 9 Profile.
$he = isset($he) ? $he : 0;
$adminName = isset($_SESSION['adminName']) && $_SESSION['adminName'] != "" ? $_SESSION['adminName'] : "Admin";
$adminInitial = strtoupper(substr(trim($adminName), 0, 1));
$pageTitles = array(1 => "Dashboard", 2 => "Orders", 3 => "Categories", 4 => "Products", 5 => "Users", 9 => "Profile");
$pageTitle = isset($pageTitles[$he]) ? $pageTitles[$he] : "Admin Panel";
?>
<script src="../assets/js/theme.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap">
<link rel="stylesheet" href="dist/css/admin-theme.css">

<!-- Top bar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav align-items-center">
      <li class="nav-item">
        <a class="nav-link ad-icon-btn" data-widget="pushmenu" href="#" role="button" aria-label="Toggle menu"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-block">
        <div class="ad-topbar-title"><?php echo $pageTitle; ?><small>Pet Shop admin panel</small></div>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto align-items-center">
      <li class="nav-item">
        <a class="nav-link ad-shop-link" href="../index.php" target="_blank" title="Open the shop"><i class="fas fa-store"></i><span>View shop</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link ad-icon-btn theme-nav-toggle" href="#" role="button" data-theme-toggle aria-label="Switch to dark mode">
          <i class="fas fa-moon"></i><i class="fas fa-sun"></i>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link ad-user-btn" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">
          <span class="ad-avatar"><?php echo htmlspecialchars($adminInitial); ?></span>
          <span class="name d-none d-md-inline"><?php echo htmlspecialchars($adminName); ?></span>
          <i class="fas fa-chevron-down" style="font-size:.7rem;"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <a href="adminProfile.php" class="dropdown-item"><i class="fas fa-user-cog"></i> My profile</a>
          <a href="../index.php" target="_blank" class="dropdown-item"><i class="fas fa-store"></i> View shop</a>
          <div class="dropdown-divider"></div>
          <a href="logout.php" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
      </li>
    </ul>
</nav>

<!-- Sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="dashboard.php" class="brand-link">
      <span class="ad-brand-mark"><i class="fas fa-paw"></i></span>
      <span class="brand-text">Pet Shop<small>Admin</small></span>
    </a>

    <div class="sidebar">
      <div class="ad-user-card">
        <span class="ad-avatar"><?php echo htmlspecialchars($adminInitial); ?></span>
        <div class="ad-user-info">
          <b><?php echo htmlspecialchars($adminName); ?></b>
          <span>Administrator</span>
        </div>
      </div>

      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search menu" aria-label="Search menu">
          <div class="input-group-append">
            <button class="btn btn-sidebar"><i class="fas fa-search fa-fw"></i></button>
          </div>
        </div>
      </div>

      <div class="ad-menu-label">Menu</div>
      <nav>
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="dashboard.php" class="nav-link <?php echo ($he == 1) ? "active" : ""; ?>">
                <i class="nav-icon fas fa-th-large"></i><p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="adminOrders.php" class="nav-link <?php echo ($he == 2) ? "active" : ""; ?>">
                <i class="nav-icon fas fa-receipt"></i><p>Orders</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="category.php" class="nav-link <?php echo ($he == 3) ? "active" : ""; ?>">
                <i class="nav-icon fas fa-tags"></i><p>Category</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="products.php" class="nav-link <?php echo ($he == 4) ? "active" : ""; ?>">
                <i class="nav-icon fas fa-box-open"></i><p>Products</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="viewusers.php" class="nav-link <?php echo ($he == 5) ? "active" : ""; ?>">
                <i class="nav-icon fas fa-users"></i><p>Users</p>
              </a>
            </li>
        </ul>
      </nav>

      <div class="ad-menu-label">Account</div>
      <nav>
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="adminProfile.php" class="nav-link <?php echo ($he == 9) ? "active" : ""; ?>">
                <i class="nav-icon fas fa-user-cog"></i><p>Profile</p>
              </a>
            </li>
            <li class="nav-item ad-logout">
              <a href="logout.php" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i><p>Logout</p>
              </a>
            </li>
        </ul>
      </nav>
    </div>
</aside>
