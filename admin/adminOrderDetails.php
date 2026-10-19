<?php include_once("checkLogin.php");
include_once("functions.php");
include_once("orders_lib.php");
orderEnsureTables($con);

$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$order = orderFetch($con, $orderId);
if ($order === null) {
    orderFlash("That order doesn't exist.", "error");
    header("location:adminOrders.php");
    exit;
}

// Actions (POST, then redirect back so a refresh doesn't repeat them)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = isset($_POST['note']) ? $_POST['note'] : null;
    if (!orderCsrfValid(isset($_POST['csrf']) ? $_POST['csrf'] : '')) {
        orderFlash("Your session expired. Please try again.", "error");
    } else if (isset($_POST['op']) && $_POST['op'] === 'status') {
        $newStatus = (int)$_POST['to'];
        $result = orderChangeStatus($con, $order, $newStatus, 'admin', $note);
        orderFlash($result === true ? "Order " . orderNumber($orderId) . " is now " . orderStatusInfo($newStatus)['label'] . "." : $result, $result === true ? "success" : "error");
    } else if (isset($_POST['op']) && $_POST['op'] === 'paid') {
        $result = orderMarkPaid($con, $order, $note);
        orderFlash($result === true ? ($order['payment_mode'] == '0' ? "Payment received for order " . orderNumber($orderId) . ". You can now mark it as delivered." : "Order " . orderNumber($orderId) . " is marked as paid.") : $result, $result === true ? "success" : "error");
    }
    header("location:adminOrderDetails.php?id=$orderId");
    exit;
}

$items = orderItems($con, $orderId);
$history = orderHistory($con, $order);
$totals = orderTotals($order, $items);
$info = orderStatusInfo($order['status']);
$nextSteps = orderAdminNextSteps($order['status']);
$canceled = orderIsCanceled($order['status']);
$flash = orderFlash();
$customer = $order['customerName'] != '' ? $order['customerName'] : 'Customer #' . $order['user_id'];

// Progress steps for the tracker
$steps = array(ORDER_PENDING => 'Placed', ORDER_CONFIRMED => 'Confirmed', ORDER_SHIPPED => 'Shipped', ORDER_DELIVERED => 'Delivered');
$reached = array(ORDER_PENDING => 0, ORDER_CONFIRMED => 1, ORDER_SHIPPED => 2, ORDER_DELIVERED => 3);
$currentStep = isset($reached[(int)$order['status']]) ? $reached[(int)$order['status']] : -1;
if ($canceled) {
    // How far it got before it was canceled
    $currentStep = 0;
    foreach ($history as $h) {
        if (isset($reached[(int)$h['status']])) $currentStep = max($currentStep, $reached[(int)$h['status']]);
    }
}

$actionText = array(
    ORDER_CONFIRMED => array('Confirm order', 'fa-thumbs-up', 'primary'),
    ORDER_SHIPPED   => array('Mark as shipped', 'fa-truck', 'primary'),
    ORDER_DELIVERED => array('Mark as delivered', 'fa-check-circle', 'primary'),
    ORDER_CANCELED  => array('Cancel order', 'fa-times', 'danger'),
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Order <?php echo orderNumber($orderId); ?> | Pet Shop Admin</title>
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

        <a class="od-back" href="adminOrders.php"><i class="fas fa-arrow-left"></i> All orders</a>
        <div class="od-head">
          <div>
            <h1 class="od-title">Order <?php echo orderNumber($orderId); ?> <span class="ad-badge <?php echo $info['class']; ?>"><?php echo $info['label']; ?></span></h1>
            <p class="od-sub">Placed on <?php echo date("d M Y", strtotime($order['dt'])); ?> by <?php echo htmlspecialchars($customer); ?></p>
          </div>
          <div class="od-head-actions">
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
          <?php if ($canceled) { ?>
            <p class="od-canceled-note"><i class="fas fa-times-circle"></i> <?php echo $info['label']; ?><?php echo $order['remark'] ? ": " . htmlspecialchars($order['remark']) : "."; ?></p>
          <?php } ?>
        </div>

        <div class="od-layout">
          <div class="od-main">
            <!-- Items -->
            <div class="ad-panel">
              <div class="ad-panel-head"><div><h3>Items</h3><small><?php echo count($items); ?> product<?php echo count($items) == 1 ? '' : 's'; ?></small></div></div>
              <ul class="od-items">
                <?php foreach ($items as $item) { ?>
                  <li>
                    <img class="od-item-img" src="upload_images/<?php echo htmlspecialchars((string)$item['product_image']); ?>" alt="">
                    <div class="od-item-info">
                      <b><?php echo htmlspecialchars((string)$item['product_name']); ?></b>
                      <span><?php echo orderMoney($item['price']); ?> × <?php echo (int)$item['qty']; ?></span>
                    </div>
                    <span class="od-item-total"><?php echo orderMoney($item['price'] * $item['qty']); ?></span>
                  </li>
                <?php } ?>
                <?php if (count($items) == 0) { ?><li class="ad-empty">No items found for this order.</li><?php } ?>
              </ul>
              <div class="od-summary">
                <div><span>Subtotal</span><span><?php echo orderMoney($totals['subtotal']); ?></span></div>
                <div><span>Delivery</span><span><?php echo orderMoney($totals['delivery']); ?></span></div>
                <?php if ($totals['tax'] > 0) { ?><div><span>Tax</span><span><?php echo orderMoney($totals['tax']); ?></span></div><?php } ?>
                <div class="od-summary-total"><span>Total</span><span><?php echo orderMoney($totals['total']); ?></span></div>
              </div>
            </div>

            <!-- History -->
            <div class="ad-panel">
              <div class="ad-panel-head"><div><h3>History</h3><small>Every change to this order</small></div></div>
              <ul class="od-history">
                <?php foreach (array_reverse($history) as $entry) { $label = orderHistoryLabel($entry); ?>
                  <li>
                    <span class="od-history-icon <?php echo $label['class']; ?>"><i class="fas <?php echo $label['icon']; ?>"></i></span>
                    <div>
                      <b><?php echo $label['label']; ?></b>
                      <small><?php echo empty($entry['estimated']) ? date("d M Y, h:i A", strtotime($entry['created_at'])) : date("d M Y", strtotime($entry['created_at'])); ?> · by <?php echo $entry['changed_by'] === 'admin' ? 'shop' : htmlspecialchars($entry['changed_by']); ?></small>
                      <?php if (!empty($entry['note'])) { ?><p><?php echo htmlspecialchars($entry['note']); ?></p><?php } ?>
                    </div>
                  </li>
                <?php } ?>
              </ul>
            </div>
          </div>

          <div class="od-side">
            <!-- Actions -->
            <div class="ad-panel">
              <div class="ad-panel-head"><div><h3>Next step</h3><small><?php echo count($nextSteps) || ($order['payment_status'] == '0' && !$canceled) ? "Update this order" : "Nothing left to do"; ?></small></div></div>
              <div class="ad-panel-body">
                <?php if (count($nextSteps) == 0 && ($order['payment_status'] == '1' || $canceled)) { ?>
                  <p class="od-done"><i class="fas fa-<?php echo $canceled ? 'ban' : 'check-circle'; ?>"></i> This order is <?php echo strtolower($info['label']); ?>.</p>
                <?php } else { ?>
                  <form method="post" class="od-actions">
                    <input type="hidden" name="csrf" value="<?php echo orderCsrfToken(); ?>">
                    <label for="note">Note <small>(optional, shown in history)</small></label>
                    <textarea class="form-control" id="note" name="note" rows="2" maxlength="255" placeholder="e.g. Courier: BlueDart, tracking 12345"></textarea>
                    <?php
                    $paymentLabel = $order['payment_mode'] == '0' ? 'Payment received (cash / offline)' : 'Mark as paid';
                    $payButton = '<button type="submit" class="btn btn-success btn-block od-btn" onclick="this.form.op.value=\'paid\'; return confirm(\'Confirm that the payment of ' . orderMoney($order['total']) . ' has been received?\');"><i class="fas fa-rupee-sign"></i> ' . $paymentLabel . '</button>';
                    $awaitingPayment = in_array(ORDER_DELIVERED, $nextSteps, true) && !orderIsPaid($order);
                    ?>
                    <?php if ($awaitingPayment) { ?>
                      <!-- Payment first, then delivery -->
                      <div class="od-step-note"><i class="fas fa-info-circle"></i><span>Collect the payment of <b><?php echo orderMoney($order['total']); ?></b> first. You can mark the order as delivered after that.</span></div>
                      <?php echo $payButton; ?>
                    <?php } ?>
                    <?php foreach ($nextSteps as $step) { $a = $actionText[$step]; $locked = $step === ORDER_DELIVERED && !orderIsPaid($order); ?>
                      <button type="submit" name="to" value="<?php echo $step; ?>" class="btn btn-<?php echo $a[2] === 'danger' ? 'outline-danger' : 'primary'; ?> btn-block od-btn"
                        <?php echo $locked ? 'disabled title="Receive the payment first"' : ''; ?>
                        onclick="this.form.op.value='status'; <?php echo $a[2] === 'danger' ? "return confirm('Cancel this order? This can\\'t be undone.');" : ""; ?>">
                        <i class="fas <?php echo $locked ? 'fa-lock' : $a[1]; ?>"></i> <?php echo $a[0]; ?>
                      </button>
                    <?php } ?>
                    <?php if (!orderIsPaid($order) && !$canceled && !$awaitingPayment) { echo $payButton; } ?>
                    <input type="hidden" name="op" value="status">
                  </form>
                <?php } ?>
              </div>
            </div>

            <!-- Customer -->
            <div class="ad-panel">
              <div class="ad-panel-head"><div><h3>Customer</h3></div></div>
              <div class="ad-panel-body">
                <ul class="ad-details">
                  <li><i class="fas fa-user"></i><div><small>Name</small><?php echo htmlspecialchars($customer); ?></div></li>
                  <li><i class="fas fa-envelope"></i><div><small>Email</small><?php echo $order['customerEmail'] ? '<a href="mailto:' . htmlspecialchars($order['customerEmail']) . '">' . htmlspecialchars($order['customerEmail']) . '</a>' : '—'; ?></div></li>
                  <li><i class="fas fa-phone"></i><div><small>Phone</small><?php echo $order['customerPhone'] ? '<a href="tel:' . htmlspecialchars($order['customerPhone']) . '">' . htmlspecialchars($order['customerPhone']) . '</a>' : '—'; ?></div></li>
                  <li><i class="fas fa-map-marker-alt"></i><div><small>Delivery address</small><?php echo nl2br(htmlspecialchars((string)$order['delivery_address'])); ?></div></li>
                </ul>
              </div>
            </div>

            <!-- Payment -->
            <div class="ad-panel">
              <div class="ad-panel-head"><div><h3>Payment</h3></div></div>
              <div class="ad-panel-body">
                <ul class="ad-details">
                  <li><i class="fas fa-wallet"></i><div><small>Method</small><?php echo $order['payment_mode'] == '1' ? 'Online (PayPal)' : 'Cash / offline'; ?></div></li>
                  <li><i class="fas fa-receipt"></i><div><small>Status</small><span class="ad-badge <?php echo $order['payment_status'] == '1' ? 'paid' : 'unpaid'; ?>"><?php echo $order['payment_status'] == '1' ? 'Paid' : 'Unpaid'; ?></span></div></li>
                  <?php if ($order['transaction_id']) { ?><li><i class="fas fa-hashtag"></i><div><small>Transaction</small><?php echo htmlspecialchars($order['transaction_id']); ?></div></li><?php } ?>
                </ul>
              </div>
            </div>
          </div>
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
