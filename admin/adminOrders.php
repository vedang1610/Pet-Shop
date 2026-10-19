<?php include_once("checkLogin.php");
include_once("functions.php");
include_once("orders_lib.php");
orderEnsureTables($con);

// Status tabs: key => statuses included
$tabs = array(
    'all'       => array('label' => 'All',       'statuses' => null),
    'pending'   => array('label' => 'Pending',   'statuses' => array(ORDER_PENDING)),
    'confirmed' => array('label' => 'Confirmed', 'statuses' => array(ORDER_CONFIRMED)),
    'shipped'   => array('label' => 'Shipped',   'statuses' => array(ORDER_SHIPPED)),
    'delivered' => array('label' => 'Delivered', 'statuses' => array(ORDER_DELIVERED)),
    'canceled'  => array('label' => 'Canceled',  'statuses' => array(ORDER_CANCELED, ORDER_CANCELED_BY_CUSTOMER)),
);
$tab = isset($_GET['tab']) && isset($tabs[$_GET['tab']]) ? $_GET['tab'] : 'all';
$search = trim(isset($_GET['q']) ? $_GET['q'] : '');
$unpaidOnly = isset($_GET['unpaid']);

// Counts per tab
$counts = array_fill_keys(array_keys($tabs), 0);
$result = mysqli_query($con, "SELECT status, COUNT(*) AS c FROM `sales` GROUP BY status");
while ($row = mysqli_fetch_assoc($result)) {
    $counts['all'] += $row['c'];
    foreach ($tabs as $key => $t) {
        if ($t['statuses'] !== null && in_array((int)$row['status'], $t['statuses'], true)) {
            $counts[$key] += $row['c'];
        }
    }
}

// The orders for this view
$where = array();
$types = "";
$params = array();
if ($tabs[$tab]['statuses'] !== null) {
    $where[] = "s.status IN (" . implode(",", array_map('intval', $tabs[$tab]['statuses'])) . ")";
}
if ($unpaidOnly) {
    $where[] = "s.payment_status = 0 AND s.status NOT IN (2,3)";
}
if ($search !== '') {
    $where[] = "(s.id = ? OR u.name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
    $like = "%" . $search . "%";
    $types .= "isss";
    array_push($params, (int)ltrim($search, "#0"), $like, $like, $like);
}
$sql = "SELECT s.*, u.name AS customerName, u.email AS customerEmail,
            (SELECT COALESCE(SUM(qty), 0) FROM `sales_items` i WHERE i.sales_id = s.id) AS itemCount
        FROM `sales` s LEFT JOIN `users` u ON u.id = s.user_id"
     . (count($where) ? " WHERE " . implode(" AND ", $where) : "")
     . " ORDER BY s.id DESC LIMIT 200";
$stmt = mysqli_prepare($con, $sql);
if ($types !== "") {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$orders = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

$unpaidCount = (int)mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM `sales` WHERE payment_status = 0 AND status NOT IN (2,3)"))[0];
$flash = orderFlash();

function adminOrdersLink($tab, $search, $unpaid)
{
    $query = array('tab' => $tab);
    if ($search !== '') $query['q'] = $search;
    if ($unpaid) $query['unpaid'] = 1;
    return "adminOrders.php?" . http_build_query($query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Orders | Pet Shop Admin</title>
  <link rel="icon" href="../assets/images/favicon/favicon.png">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<?php $he = 2; include_once("header.php") ?>

  <div class="content-wrapper">
    <section class="content pt-3">
      <div class="container-fluid ad-dash">

        <div class="od-head">
          <div>
            <h1 class="od-title">Orders</h1>
            <p class="od-sub"><?php echo $counts['all']; ?> orders in total<?php echo $unpaidCount ? " · <a href=\"" . adminOrdersLink('all', '', true) . "\">$unpaidCount waiting for payment</a>" : ""; ?></p>
          </div>
          <form class="od-search" method="get" action="adminOrders.php" role="search">
            <input type="hidden" name="tab" value="<?php echo $tab; ?>">
            <?php if ($unpaidOnly) { ?><input type="hidden" name="unpaid" value="1"><?php } ?>
            <i class="fas fa-search"></i>
            <input type="search" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Order #, customer name, email or phone" aria-label="Search orders">
          </form>
        </div>

        <?php if ($flash) { ?>
          <div class="od-alert <?php echo $flash['type']; ?>"><i class="fas fa-<?php echo $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i> <?php echo htmlspecialchars($flash['message']); ?></div>
        <?php } ?>

        <div class="od-tabs" role="tablist">
          <?php foreach ($tabs as $key => $t) { ?>
            <a class="od-tab <?php echo $key === $tab ? 'active' : ''; ?>" href="<?php echo adminOrdersLink($key, $search, $unpaidOnly); ?>" role="tab" aria-selected="<?php echo $key === $tab ? 'true' : 'false'; ?>">
              <?php echo $t['label']; ?> <span><?php echo $counts[$key]; ?></span>
            </a>
          <?php } ?>
          <a class="od-tab od-tab-filter <?php echo $unpaidOnly ? 'active' : ''; ?>" href="<?php echo adminOrdersLink($tab, $search, !$unpaidOnly); ?>">
            <i class="fas fa-<?php echo $unpaidOnly ? 'check-square' : 'square'; ?>"></i> Unpaid only
          </a>
        </div>

        <div class="ad-panel">
          <?php if (count($orders) >= 1) { ?>
            <div class="ad-table-wrap">
              <table class="ad-table od-table">
                <thead>
                  <tr><th>Order</th><th>Customer</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                  <?php foreach ($orders as $order) {
                      $info = orderStatusInfo($order['status']);
                      $customer = $order['customerName'] != '' ? $order['customerName'] : 'Customer #' . $order['user_id'];
                  ?>
                    <tr onclick="if (!event.target.closest('a')) location.href='adminOrderDetails.php?id=<?php echo $order['id']; ?>'">
                      <td><a class="ad-order-id" href="adminOrderDetails.php?id=<?php echo $order['id']; ?>"><?php echo orderNumber($order['id']); ?></a><small class="ad-sub"><?php echo date("d M Y", strtotime($order['dt'])); ?></small></td>
                      <td><div class="ad-cust"><span class="ad-avatar"><?php echo htmlspecialchars(strtoupper(substr($customer, 0, 1))); ?></span><div><?php echo htmlspecialchars($customer); ?><small class="ad-sub"><?php echo htmlspecialchars((string)$order['customerEmail']); ?></small></div></div></td>
                      <td><?php echo (int)$order['itemCount']; ?></td>
                      <td class="ad-amount"><?php echo orderMoney($order['total']); ?></td>
                      <td>
                        <span class="ad-badge <?php echo $order['payment_status'] == '1' ? 'paid' : 'unpaid'; ?>"><?php echo $order['payment_status'] == '1' ? 'Paid' : 'Unpaid'; ?></span>
                        <small class="ad-sub"><?php echo $order['payment_mode'] == '1' ? 'Online' : 'Cash / offline'; ?></small>
                      </td>
                      <td><span class="ad-badge <?php echo $info['class']; ?>"><?php echo $info['label']; ?></span></td>
                      <td><a class="od-open" href="adminOrderDetails.php?id=<?php echo $order['id']; ?>">Manage <i class="fas fa-chevron-right"></i></a></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          <?php } else { ?>
            <div class="ad-empty">
              <i class="fas fa-receipt"></i>
              <?php echo $search !== '' || $tab !== 'all' || $unpaidOnly ? "No orders match this filter." : "No orders yet."; ?>
              <?php if ($search !== '' || $tab !== 'all' || $unpaidOnly) { ?><div class="mt-2"><a href="adminOrders.php">Show all orders</a></div><?php } ?>
            </div>
          <?php } ?>
        </div>

      </div>
    </section>
  </div>

  <?php include_once("footer.php"); ?>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
