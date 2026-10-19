<?php include_once("userCheckLogin.php");
include_once('functions.php');

$userId = $_SESSION['userId'];

// Returns the first column of the first row, or 0
function userDashValue($sql)
{
    $data = selectData($sql);
    if(is_array($data) && count($data) >= 1)
    {
        $row = array_values($data[0]);
        return $row[0] === null ? 0 : $row[0];
    }
    return 0;
}

// Order status: 0 Pending, 1 Confirmed, 2 Canceled, 3 Canceled by customer, 4 Completed
$totalOrders     = userDashValue("SELECT count(*) FROM `sales` WHERE user_id='$userId'");
$pendingOrders   = userDashValue("SELECT count(*) FROM `sales` WHERE user_id='$userId' AND status='0'");
$confirmedOrders = userDashValue("SELECT count(*) FROM `sales` WHERE user_id='$userId' AND status='1'");
$completedOrders = userDashValue("SELECT count(*) FROM `sales` WHERE user_id='$userId' AND status='4'");
$shippedOrders   = userDashValue("SELECT count(*) FROM `sales` WHERE user_id='$userId' AND status='5'");
$canceledOrders  = userDashValue("SELECT count(*) FROM `sales` WHERE user_id='$userId' AND status IN ('2','3')");
$totalSpent      = userDashValue("SELECT SUM(total) FROM `sales` WHERE user_id='$userId' AND status NOT IN ('2','3')");

$recentOrders = selectData("SELECT s.*, (SELECT COUNT(*) FROM `sales_items` i WHERE i.sales_id = s.id) AS itemCount FROM `sales` s WHERE s.user_id='$userId' ORDER BY s.id DESC LIMIT 5");
$account = selectData("SELECT name, email, phone, address, dt FROM `users` WHERE id='$userId'");
$account = (is_array($account) && count($account) >= 1) ? $account[0] : array('name' => $_SESSION['userName'], 'email' => '', 'phone' => '', 'address' => '', 'dt' => '');
$picks = selectData("SELECT * FROM `products` WHERE status='1' ORDER BY RAND() LIMIT 4");

$statusNames = array(0 => array("Pending", "pending"), 1 => array("Confirmed", "confirmed"), 2 => array("Canceled", "canceled"), 3 => array("Canceled", "canceled"), 4 => array("Delivered", "completed"), 5 => array("Shipped", "shipped"));

$hour = (int)date("G");
$greeting = $hour < 12 ? "Good morning" : ($hour < 17 ? "Good afternoon" : "Good evening");
$firstName = explode(" ", trim($account['name'] != "" ? $account['name'] : "there"))[0];

function userMoney($value)
{
    return "Rs. " . number_format((float)$value, 2);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Account | Pet Shop</title>
  <link rel="icon" href="../assets/images/favicon/favicon.png">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<?php $he = 1; include_once("userHeader.php") ?>

  <div class="content-wrapper">
    <section class="content pt-3">
      <div class="container-fluid ad-dash">

        <!-- Welcome -->
        <div class="ad-hero">
          <div>
            <h2><?php echo $greeting . ", " . htmlspecialchars($firstName); ?> 👋</h2>
            <p>Welcome to your Pet Shop account.</p>
            <?php if($account['dt'] != "") { ?>
              <span class="ad-hero-date"><i class="fas fa-paw"></i> Member since <?php echo date("F Y", strtotime($account['dt'])); ?></span>
            <?php } ?>
          </div>
          <div class="ad-actions">
            <a class="ad-action primary" href="../products.php"><i class="fas fa-store"></i> Shop now</a>
            <a class="ad-action" href="userOrders.php"><i class="fas fa-receipt"></i> My orders</a>
            <a class="ad-action" href="../cart.php"><i class="fas fa-shopping-cart"></i> My cart</a>
          </div>
        </div>

        <!-- KPI cards -->
        <div class="ad-kpis">
          <a class="ad-kpi green" href="userOrders.php">
            <div class="ad-kpi-top">
              <div>
                <div class="ad-kpi-label">Total orders</div>
                <div class="ad-kpi-value"><?php echo $totalOrders; ?></div>
              </div>
              <span class="ad-kpi-icon"><i class="fas fa-shopping-bag"></i></span>
            </div>
            <div class="ad-kpi-foot"><i class="fas fa-check-double"></i> <?php echo $completedOrders; ?> delivered</div>
          </a>
          <a class="ad-kpi orange" href="userOrders.php">
            <div class="ad-kpi-top">
              <div>
                <div class="ad-kpi-label">In progress</div>
                <div class="ad-kpi-value"><?php echo $pendingOrders + $confirmedOrders + $shippedOrders; ?></div>
              </div>
              <span class="ad-kpi-icon"><i class="fas fa-truck"></i></span>
            </div>
            <div class="ad-kpi-foot"><i class="fas fa-hourglass-half"></i> <?php echo $pendingOrders + $confirmedOrders; ?> being prepared · <?php echo $shippedOrders; ?> shipped</div>
          </a>
          <a class="ad-kpi gold" href="userOrders.php">
            <div class="ad-kpi-top">
              <div>
                <div class="ad-kpi-label">Total spent</div>
                <div class="ad-kpi-value"><?php echo userMoney($totalSpent); ?></div>
              </div>
              <span class="ad-kpi-icon"><i class="fas fa-rupee-sign"></i></span>
            </div>
            <div class="ad-kpi-foot"><i class="fas fa-info-circle"></i> Canceled orders not included</div>
          </a>
          <a class="ad-kpi purple" href="userOrders.php">
            <div class="ad-kpi-top">
              <div>
                <div class="ad-kpi-label">Canceled</div>
                <div class="ad-kpi-value"><?php echo $canceledOrders; ?></div>
              </div>
              <span class="ad-kpi-icon"><i class="fas fa-ban"></i></span>
            </div>
            <div class="ad-kpi-foot"><i class="fas fa-receipt"></i> See all in My orders</div>
          </a>
        </div>

        <div class="ad-grid">
          <!-- Recent orders -->
          <div class="ad-panel">
            <div class="ad-panel-head">
              <div><h3>Recent orders</h3><small>Your latest orders and their status</small></div>
              <a href="userOrders.php">View all <i class="fas fa-arrow-right"></i></a>
            </div>
            <?php if(is_array($recentOrders) && count($recentOrders) >= 1) { ?>
              <div class="ad-table-wrap">
                <table class="ad-table">
                  <thead>
                    <tr><th>Order</th><th>Items</th><th>Amount</th><th>Status</th><th></th></tr>
                  </thead>
                  <tbody>
                    <?php foreach ($recentOrders as $order) {
                        $status = isset($statusNames[$order['status']]) ? $statusNames[$order['status']] : array("Unknown", "unpaid");
                    ?>
                      <tr>
                        <td><span class="ad-order-id">#<?php echo $order['id']; ?></span><small class="ad-sub"><?php echo $order['dt'] ? date("d M Y", strtotime($order['dt'])) : "-"; ?></small></td>
                        <td><?php echo $order['itemCount']; ?> item<?php echo $order['itemCount'] == 1 ? '' : 's'; ?></td>
                        <td><span class="ad-amount"><?php echo userMoney($order['total']); ?></span><small class="ad-sub"><?php echo $order['payment_mode'] == '1' ? 'Online' : 'Offline'; ?> · <span class="<?php echo $order['payment_status'] == '1' ? 'ad-paid' : 'ad-unpaid'; ?>"><?php echo $order['payment_status'] == '1' ? 'Paid' : 'Unpaid'; ?></span></small></td>
                        <td><span class="ad-badge <?php echo $status[1]; ?>"><?php echo $status[0]; ?></span></td>
                        <td><a class="ad-view" href="userOrderDetails.php?id=<?php echo $order['id']; ?>" title="View order" aria-label="View order <?php echo $order['id']; ?>"><i class="fas fa-eye"></i></a></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <div class="ad-empty">
                <i class="fas fa-shopping-bag"></i>
                You haven't placed any orders yet.
                <div class="mt-3"><a class="btn btn-primary" href="../products.php"><i class="fas fa-store"></i> Start shopping</a></div>
              </div>
            <?php } ?>
          </div>

          <!-- Profile -->
          <div class="ad-panel">
            <div class="ad-panel-head">
              <div><h3>My details</h3><small>Used for delivery and contact</small></div>
              <a href="userProfile.php">Edit <i class="fas fa-pen"></i></a>
            </div>
            <div class="ad-panel-body">
              <div class="ad-profile-top">
                <span class="ad-avatar ad-avatar-lg"><?php echo htmlspecialchars(strtoupper(substr(trim($account['name']), 0, 1))); ?></span>
                <div>
                  <b><?php echo htmlspecialchars($account['name']); ?></b>
                  <span>Customer</span>
                </div>
              </div>
              <ul class="ad-details">
                <li><i class="fas fa-envelope"></i><div><small>Email</small><?php echo $account['email'] != "" ? htmlspecialchars($account['email']) : "—"; ?></div></li>
                <li><i class="fas fa-phone"></i><div><small>Phone</small><?php echo $account['phone'] != "" ? htmlspecialchars($account['phone']) : "—"; ?></div></li>
                <li><i class="fas fa-map-marker-alt"></i><div><small>Address</small><?php echo $account['address'] != "" ? nl2br(htmlspecialchars($account['address'])) : "—"; ?></div></li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Product picks -->
        <?php if(is_array($picks) && count($picks) >= 1) { ?>
          <div class="ad-panel">
            <div class="ad-panel-head">
              <div><h3>Picked for your pet</h3><small>A few things from the shop</small></div>
              <a href="../products.php">Shop all <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="ad-picks">
              <?php foreach ($picks as $product) { ?>
                <a class="ad-pick" href="../productDetails.php?getData=<?php echo $product['id']; ?>">
                  <span class="ad-pick-img"><img src="upload_images/<?php echo $product['image']; ?>" alt="" loading="lazy"></span>
                  <span class="ad-pick-name"><?php echo htmlspecialchars($product['name']); ?></span>
                  <span class="ad-pick-price"><?php echo userMoney($product['price']); ?></span>
                </a>
              <?php } ?>
            </div>
          </div>
        <?php } ?>

      </div>
    </section>
  </div>

  <?php include_once("userFooter.php"); ?>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
