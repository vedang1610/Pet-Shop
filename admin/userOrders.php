<?php include_once("userCheckLogin.php");
include_once("functions.php");
include_once("orders_lib.php");
orderEnsureTables($con);

$userId = (int)$_SESSION['userId'];

$filters = array(
    'all'       => array('label' => 'All orders', 'statuses' => null),
    'active'    => array('label' => 'In progress', 'statuses' => array(ORDER_PENDING, ORDER_CONFIRMED, ORDER_SHIPPED)),
    'delivered' => array('label' => 'Delivered',   'statuses' => array(ORDER_DELIVERED)),
    'canceled'  => array('label' => 'Canceled',    'statuses' => array(ORDER_CANCELED, ORDER_CANCELED_BY_CUSTOMER)),
);
$filter = isset($_GET['show']) && isset($filters[$_GET['show']]) ? $_GET['show'] : 'all';

$counts = array_fill_keys(array_keys($filters), 0);
$stmt = mysqli_prepare($con, "SELECT status, COUNT(*) AS c FROM `sales` WHERE user_id = ? GROUP BY status");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
foreach (mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC) as $row) {
    $counts['all'] += $row['c'];
    foreach ($filters as $key => $f) {
        if ($f['statuses'] !== null && in_array((int)$row['status'], $f['statuses'], true)) $counts[$key] += $row['c'];
    }
}
mysqli_stmt_close($stmt);

$sql = "SELECT * FROM `sales` WHERE user_id = ?";
if ($filters[$filter]['statuses'] !== null) {
    $sql .= " AND status IN (" . implode(",", array_map('intval', $filters[$filter]['statuses'])) . ")";
}
$stmt = mysqli_prepare($con, $sql . " ORDER BY id DESC");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$orders = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Items for all listed orders in one query
$itemsByOrder = array();
if (count($orders)) {
    $ids = implode(",", array_map(function ($o) { return (int)$o['id']; }, $orders));
    $result = mysqli_query($con, "SELECT * FROM `sales_items` WHERE sales_id IN ($ids) ORDER BY id");
    while ($row = mysqli_fetch_assoc($result)) {
        $itemsByOrder[$row['sales_id']][] = $row;
    }
}
$flash = orderFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Orders | Pet Shop</title>
  <link rel="icon" href="../assets/images/favicon/favicon.png">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<?php $he = 2; include_once("userHeader.php") ?>

  <div class="content-wrapper">
    <section class="content pt-3">
      <div class="container-fluid ad-dash">

        <div class="od-head">
          <div>
            <h1 class="od-title">My Orders</h1>
            <p class="od-sub">Track your orders, pay, or cancel before they ship.</p>
          </div>
          <a class="btn btn-primary" href="../products.php"><i class="fas fa-store"></i> Continue shopping</a>
        </div>

        <?php if ($flash) { ?>
          <div class="od-alert <?php echo $flash['type']; ?>"><i class="fas fa-<?php echo $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i> <?php echo htmlspecialchars($flash['message']); ?></div>
        <?php } ?>

        <div class="od-tabs" role="tablist">
          <?php foreach ($filters as $key => $f) { ?>
            <a class="od-tab <?php echo $key === $filter ? 'active' : ''; ?>" href="userOrders.php?show=<?php echo $key; ?>" role="tab" aria-selected="<?php echo $key === $filter ? 'true' : 'false'; ?>"><?php echo $f['label']; ?> <span><?php echo $counts[$key]; ?></span></a>
          <?php } ?>
        </div>

        <?php if (count($orders) == 0) { ?>
          <div class="ad-panel">
            <div class="ad-empty">
              <i class="fas fa-shopping-bag"></i>
              <?php echo $filter === 'all' ? "You haven't placed any orders yet." : "No orders here."; ?>
              <div class="mt-3"><a class="btn btn-primary" href="../products.php"><i class="fas fa-store"></i> Start shopping</a></div>
            </div>
          </div>
        <?php } ?>

        <div class="od-cards">
          <?php foreach ($orders as $order) {
              $info = orderStatusInfo($order['status']);
              $items = isset($itemsByOrder[$order['id']]) ? $itemsByOrder[$order['id']] : array();
              $qty = array_sum(array_map(function ($i) { return (int)$i['qty']; }, $items));
          ?>
            <article class="od-card">
              <div class="od-card-head">
                <div>
                  <a class="ad-order-id" href="userOrderDetails.php?id=<?php echo $order['id']; ?>">Order <?php echo orderNumber($order['id']); ?></a>
                  <small class="ad-sub">Placed <?php echo date("d M Y", strtotime($order['dt'])); ?> · <?php echo $qty; ?> item<?php echo $qty == 1 ? '' : 's'; ?></small>
                </div>
                <span class="ad-badge <?php echo $info['class']; ?>"><i class="fas <?php echo $info['icon']; ?>"></i> <?php echo $info['label']; ?></span>
              </div>
              <div class="od-card-body">
                <div class="od-thumbs">
                  <?php foreach (array_slice($items, 0, 4) as $item) { ?>
                    <img src="upload_images/<?php echo htmlspecialchars((string)$item['product_image']); ?>" alt="<?php echo htmlspecialchars((string)$item['product_name']); ?>" title="<?php echo htmlspecialchars((string)$item['product_name']); ?>">
                  <?php } ?>
                  <?php if (count($items) > 4) { ?><span class="od-more">+<?php echo count($items) - 4; ?></span><?php } ?>
                </div>
                <div class="od-card-names"><?php echo htmlspecialchars(implode(", ", array_map(function ($i) { return $i['product_name']; }, $items))); ?></div>
              </div>
              <div class="od-card-foot">
                <div>
                  <span class="ad-amount"><?php echo orderMoney($order['total']); ?></span>
                  <small class="ad-sub"><?php echo $order['payment_mode'] == '1' ? 'Online' : 'Cash / offline'; ?> · <span class="<?php echo $order['payment_status'] == '1' ? 'ad-paid' : 'ad-unpaid'; ?>"><?php echo $order['payment_status'] == '1' ? 'Paid' : 'Unpaid'; ?></span></small>
                </div>
                <div class="od-card-actions">
                  <?php if (orderCanPayOnline($order)) { ?>
                    <a class="btn btn-success btn-sm" href="../payment/payment.php?txtId=<?php echo $order['id']; ?>"><i class="fas fa-lock"></i> Pay now</a>
                  <?php } ?>
                  <a class="btn btn-primary btn-sm" href="userOrderDetails.php?id=<?php echo $order['id']; ?>">View details</a>
                </div>
              </div>
            </article>
          <?php } ?>
        </div>

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
