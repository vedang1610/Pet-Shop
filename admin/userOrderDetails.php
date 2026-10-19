<?php include_once("userCheckLogin.php");
include_once("functions.php");
include_once("orders_lib.php");
orderEnsureTables($con);

$userId = (int)$_SESSION['userId'];
$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// Only the customer's own orders
$order = orderFetch($con, $orderId, $userId);
if ($order === null) {
    orderFlash("We couldn't find that order in your account.", "error");
    header("location:userOrders.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!orderCsrfValid(isset($_POST['csrf']) ? $_POST['csrf'] : '')) {
        orderFlash("Your session expired. Please try again.", "error");
    } else if (isset($_POST['op']) && $_POST['op'] === 'cancel') {
        $reason = isset($_POST['reason']) ? $_POST['reason'] : null;
        $result = orderChangeStatus($con, $order, ORDER_CANCELED_BY_CUSTOMER, 'customer', $reason);
        if ($result === true) {
            $paidNote = $order['payment_status'] == '1' ? " Your payment will be refunded by the shop." : "";
            orderFlash("Order " . orderNumber($orderId) . " has been canceled." . $paidNote, "success");
        } else {
            orderFlash($result, "error");
        }
    }
    header("location:userOrderDetails.php?id=$orderId");
    exit;
}

$items = orderItems($con, $orderId);
$history = orderHistory($con, $order);
$totals = orderTotals($order, $items);
$info = orderStatusInfo($order['status']);
$canceled = orderIsCanceled($order['status']);
$flash = orderFlash();

$steps = array(ORDER_PENDING => 'Placed', ORDER_CONFIRMED => 'Confirmed', ORDER_SHIPPED => 'Shipped', ORDER_DELIVERED => 'Delivered');
$reached = array(ORDER_PENDING => 0, ORDER_CONFIRMED => 1, ORDER_SHIPPED => 2, ORDER_DELIVERED => 3);
$currentStep = isset($reached[(int)$order['status']]) ? $reached[(int)$order['status']] : 0;
if ($canceled) {
    $currentStep = 0;
    foreach ($history as $h) {
        if (isset($reached[(int)$h['status']])) $currentStep = max($currentStep, $reached[(int)$h['status']]);
    }
}
$statusMessages = array(
    ORDER_PENDING   => "We've received your order and will confirm it soon.",
    ORDER_CONFIRMED => "Your order is confirmed and being packed.",
    ORDER_SHIPPED   => orderAwaitingOfflinePayment($order) ? "Your order is on its way. Please keep " . orderMoney($order['total']) . " ready. It will be handed over once the payment is received." : "Your order is on its way.",
    ORDER_DELIVERED => "Your order has been delivered. Enjoy!",
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Order <?php echo orderNumber($orderId); ?> | Pet Shop</title>
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

        <a class="od-back" href="userOrders.php"><i class="fas fa-arrow-left"></i> My orders</a>
        <div class="od-head">
          <div>
            <h1 class="od-title">Order <?php echo orderNumber($orderId); ?> <span class="ad-badge <?php echo $info['class']; ?>"><?php echo $info['label']; ?></span></h1>
            <p class="od-sub">Placed on <?php echo date("d M Y", strtotime($order['dt'])); ?></p>
          </div>
          <div class="od-head-actions">
            <?php if (orderCanPayOnline($order)) { ?>
              <a class="btn btn-success" href="../payment/payment.php?txtId=<?php echo $orderId; ?>"><i class="fas fa-lock"></i> Pay <?php echo orderMoney($order['total']); ?> now</a>
            <?php } ?>
            <?php if ($order['payment_status'] == '1') { ?>
              <a class="btn btn-outline-secondary" href="viewInvoice.php?InvoiceId=<?php echo $orderId; ?>" target="_blank"><i class="fas fa-file-invoice"></i> Invoice</a>
            <?php } ?>
          </div>
        </div>

        <?php if ($flash) { ?>
          <div class="od-alert <?php echo $flash['type']; ?>"><i class="fas fa-<?php echo $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i> <?php echo htmlspecialchars($flash['message']); ?></div>
        <?php } ?>

        <!-- Tracker -->
        <div class="ad-panel od-tracker-panel">
          <ol class="od-tracker <?php echo $canceled ? 'is-canceled' : ''; ?>">
            <?php $i = 0; foreach ($steps as $status => $label) { ?>
              <li class="<?php echo $i < $currentStep || (!$canceled && $i == $currentStep) ? 'done' : ''; ?> <?php echo !$canceled && $i == $currentStep ? 'current' : ''; ?>">
                <span class="od-dot"><i class="fas <?php echo orderStatusInfo($status)['icon']; ?>"></i></span>
                <span class="od-step-label"><?php echo $label; ?></span>
              </li>
            <?php $i++; } ?>
          </ol>
          <p class="od-status-msg <?php echo $canceled ? 'od-canceled-note' : ''; ?>">
            <?php if ($canceled) { ?>
              <i class="fas fa-times-circle"></i> This order was <?php echo (int)$order['status'] === ORDER_CANCELED_BY_CUSTOMER ? 'canceled by you' : 'canceled by the shop'; ?><?php echo $order['remark'] ? ": " . htmlspecialchars($order['remark']) : "."; ?>
            <?php } else { ?>
              <i class="fas <?php echo $info['icon']; ?>"></i> <?php echo $statusMessages[(int)$order['status']]; ?>
            <?php } ?>
          </p>
        </div>

        <div class="od-layout">
          <div class="od-main">
            <div class="ad-panel">
              <div class="ad-panel-head"><div><h3>Items</h3><small><?php echo count($items); ?> product<?php echo count($items) == 1 ? '' : 's'; ?></small></div></div>
              <ul class="od-items">
                <?php foreach ($items as $item) { ?>
                  <li>
                    <img class="od-item-img" src="upload_images/<?php echo htmlspecialchars((string)$item['product_image']); ?>" alt="">
                    <div class="od-item-info">
                      <?php if ($item['liveProductId']) { ?>
                        <a href="../productDetails.php?getData=<?php echo (int)$item['product_id']; ?>"><b><?php echo htmlspecialchars((string)$item['product_name']); ?></b></a>
                      <?php } else { ?>
                        <b><?php echo htmlspecialchars((string)$item['product_name']); ?></b>
                      <?php } ?>
                      <span><?php echo orderMoney($item['price']); ?> × <?php echo (int)$item['qty']; ?></span>
                    </div>
                    <span class="od-item-total"><?php echo orderMoney($item['price'] * $item['qty']); ?></span>
                  </li>
                <?php } ?>
              </ul>
              <div class="od-summary">
                <div><span>Subtotal</span><span><?php echo orderMoney($totals['subtotal']); ?></span></div>
                <div><span>Delivery</span><span><?php echo orderMoney($totals['delivery']); ?></span></div>
                <?php if ($totals['tax'] > 0) { ?><div><span>Tax</span><span><?php echo orderMoney($totals['tax']); ?></span></div><?php } ?>
                <div class="od-summary-total"><span>Total</span><span><?php echo orderMoney($totals['total']); ?></span></div>
              </div>
            </div>

            <div class="ad-panel">
              <div class="ad-panel-head"><div><h3>Order updates</h3></div></div>
              <ul class="od-history">
                <?php foreach (array_reverse($history) as $entry) { $label = orderHistoryLabel($entry); ?>
                  <li>
                    <span class="od-history-icon <?php echo $label['class']; ?>"><i class="fas <?php echo $label['icon']; ?>"></i></span>
                    <div>
                      <b><?php echo $label['label']; ?></b>
                      <small><?php echo empty($entry['estimated']) ? date("d M Y, h:i A", strtotime($entry['created_at'])) : date("d M Y", strtotime($entry['created_at'])); ?></small>
                      <?php if (!empty($entry['note'])) { ?><p><?php echo htmlspecialchars($entry['note']); ?></p><?php } ?>
                    </div>
                  </li>
                <?php } ?>
              </ul>
            </div>
          </div>

          <div class="od-side">
            <div class="ad-panel">
              <div class="ad-panel-head"><div><h3>Delivery</h3></div></div>
              <div class="ad-panel-body">
                <ul class="ad-details">
                  <li><i class="fas fa-map-marker-alt"></i><div><small>Address</small><?php echo nl2br(htmlspecialchars((string)$order['delivery_address'])); ?></div></li>
                  <li><i class="fas fa-truck"></i><div><small>Delivery charge</small><?php echo orderMoney($order['delivery_changes']); ?></div></li>
                </ul>
              </div>
            </div>

            <div class="ad-panel">
              <div class="ad-panel-head"><div><h3>Payment</h3></div></div>
              <div class="ad-panel-body">
                <ul class="ad-details">
                  <li><i class="fas fa-wallet"></i><div><small>Method</small><?php echo $order['payment_mode'] == '1' ? 'Online (PayPal)' : 'Cash / offline'; ?></div></li>
                  <li><i class="fas fa-receipt"></i><div><small>Status</small><span class="ad-badge <?php echo $order['payment_status'] == '1' ? 'paid' : 'unpaid'; ?>"><?php echo $order['payment_status'] == '1' ? 'Paid' : ($order['payment_mode'] == '1' ? 'Not paid yet' : 'Pay on delivery'); ?></span></div></li>
                  <?php if ($order['transaction_id']) { ?><li><i class="fas fa-hashtag"></i><div><small>Transaction</small><?php echo htmlspecialchars($order['transaction_id']); ?></div></li><?php } ?>
                </ul>
              </div>
            </div>

            <?php if (orderCustomerCanCancel($order)) { ?>
              <div class="ad-panel">
                <div class="ad-panel-head"><div><h3>Need to cancel?</h3><small>You can cancel until the order ships</small></div></div>
                <div class="ad-panel-body">
                  <form method="post" class="od-actions" onsubmit="return confirm('Cancel order <?php echo orderNumber($orderId); ?>? This can\'t be undone.');">
                    <input type="hidden" name="csrf" value="<?php echo orderCsrfToken(); ?>">
                    <input type="hidden" name="op" value="cancel">
                    <label for="reason">Reason <small>(optional)</small></label>
                    <select class="form-control" id="reason" name="reason">
                      <option value="">Choose a reason</option>
                      <option>Ordered by mistake</option>
                      <option>Found a better price</option>
                      <option>Delivery takes too long</option>
                      <option>Want to change items or address</option>
                      <option>Other</option>
                    </select>
                    <?php if ($order['payment_status'] == '1') { ?>
                      <p class="od-help"><i class="fas fa-info-circle"></i> You've already paid. The shop will refund your payment.</p>
                    <?php } ?>
                    <button type="submit" class="btn btn-outline-danger btn-block od-btn"><i class="fas fa-times"></i> Cancel order</button>
                  </form>
                </div>
              </div>
            <?php } ?>

            <div class="ad-panel">
              <div class="ad-panel-body od-help-box">
                <i class="fas fa-headset"></i>
                <div><b>Questions about this order?</b><span>Call us on <a href="tel:+911234567890">+91 12345 67890</a></span></div>
              </div>
            </div>
          </div>
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
