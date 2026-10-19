<?php
// orders_lib.php - one place for order rules, used by the admin and customer order pages,
// the cart (placing orders) and the payment pages. Expects $con (mysqli) from db.php.
//
// Order flow (sales.status):  0 Pending -> 1 Confirmed -> 5 Shipped -> 4 Delivered
//                             Pending/Confirmed can be canceled: 2 by the shop, 3 by the customer.

const ORDER_PENDING   = 0;
const ORDER_CONFIRMED = 1;
const ORDER_CANCELED  = 2;   // canceled by the shop
const ORDER_CANCELED_BY_CUSTOMER = 3;
const ORDER_DELIVERED = 4;
const ORDER_SHIPPED   = 5;

function orderStatusInfo($status)
{
    $all = array(
        ORDER_PENDING   => array('label' => 'Pending',   'class' => 'pending',   'icon' => 'fa-hourglass-half'),
        ORDER_CONFIRMED => array('label' => 'Confirmed', 'class' => 'confirmed', 'icon' => 'fa-thumbs-up'),
        ORDER_SHIPPED   => array('label' => 'Shipped',   'class' => 'shipped',   'icon' => 'fa-truck'),
        ORDER_DELIVERED => array('label' => 'Delivered', 'class' => 'completed', 'icon' => 'fa-check-circle'),
        ORDER_CANCELED  => array('label' => 'Canceled',  'class' => 'canceled',  'icon' => 'fa-times-circle'),
        ORDER_CANCELED_BY_CUSTOMER => array('label' => 'Canceled by customer', 'class' => 'canceled', 'icon' => 'fa-times-circle'),
    );
    $status = (int)$status;
    return isset($all[$status]) ? $all[$status] : array('label' => 'Unknown', 'class' => 'unpaid', 'icon' => 'fa-question-circle');
}

function orderIsCanceled($status)
{
    return in_array((int)$status, array(ORDER_CANCELED, ORDER_CANCELED_BY_CUSTOMER), true);
}

// The next steps the shop can take from each status
function orderAdminNextSteps($status)
{
    switch ((int)$status) {
        case ORDER_PENDING:   return array(ORDER_CONFIRMED, ORDER_CANCELED);
        case ORDER_CONFIRMED: return array(ORDER_SHIPPED, ORDER_CANCELED);
        case ORDER_SHIPPED:   return array(ORDER_DELIVERED);
        default:              return array();
    }
}

// Customers can cancel until the order has been shipped
function orderCustomerCanCancel($order)
{
    return in_array((int)$order['status'], array(ORDER_PENDING, ORDER_CONFIRMED), true);
}

// Online orders that aren't paid yet can be paid from the customer's account
function orderCanPayOnline($order)
{
    return $order['payment_mode'] == '1' && $order['payment_status'] == '0'
        && in_array((int)$order['status'], array(ORDER_PENDING, ORDER_CONFIRMED), true);
}

function orderEnsureTables($con)
{
    mysqli_query($con, "CREATE TABLE IF NOT EXISTS `order_status_history` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `sales_id` int(11) NOT NULL,
        `status` int(11) NOT NULL,
        `note` varchar(255) DEFAULT NULL,
        `changed_by` varchar(20) NOT NULL,
        `created_at` datetime NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        KEY `by_order` (`sales_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

// One order with its customer. Pass $userId to only return it if it belongs to that customer.
function orderFetch($con, $orderId, $userId = null)
{
    $orderId = (int)$orderId;
    $sql = "SELECT s.*, u.name AS customerName, u.email AS customerEmail, u.phone AS customerPhone
            FROM `sales` s LEFT JOIN `users` u ON u.id = s.user_id WHERE s.id = ?";
    if ($userId !== null) {
        $sql .= " AND s.user_id = ?";
    }
    $stmt = mysqli_prepare($con, $sql);
    if ($userId !== null) {
        $userId = (int)$userId;
        mysqli_stmt_bind_param($stmt, "ii", $orderId, $userId);
    } else {
        mysqli_stmt_bind_param($stmt, "i", $orderId);
    }
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $row ? $row : null;
}

// Items as they were when the order was placed (name, photo and price are copied into sales_items)
function orderItems($con, $orderId)
{
    $orderId = (int)$orderId;
    $stmt = mysqli_prepare($con, "SELECT si.*, p.id AS liveProductId FROM `sales_items` si
        LEFT JOIN `products` p ON p.id = si.product_id WHERE si.sales_id = ? ORDER BY si.id");
    mysqli_stmt_bind_param($stmt, "i", $orderId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $items;
}

function orderHistory($con, $order)
{
    $orderId = (int)$order['id'];
    $stmt = mysqli_prepare($con, "SELECT * FROM `order_status_history` WHERE sales_id = ? ORDER BY id");
    mysqli_stmt_bind_param($stmt, "i", $orderId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $history = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $history[] = $row;
    }
    mysqli_stmt_close($stmt);
    // Orders placed before history was recorded start with "placed" on their order date
    if (count($history) == 0 || (int)$history[0]['status'] !== ORDER_PENDING) {
        array_unshift($history, array('status' => ORDER_PENDING, 'note' => null, 'changed_by' => 'customer', 'created_at' => $order['dt'] . ' 00:00:00', 'estimated' => true));
    }
    return $history;
}

function orderLogStatus($con, $orderId, $status, $changedBy, $note = null)
{
    $orderId = (int)$orderId;
    $status = (int)$status;
    $note = ($note !== null && trim($note) !== '') ? mb_substr(trim($note), 0, 255) : null;
    $stmt = mysqli_prepare($con, "INSERT INTO `order_status_history` (sales_id, status, note, changed_by) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iiss", $orderId, $status, $note, $changedBy);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Moves an order to a new status if that step is allowed. Returns true or an error message.
function orderChangeStatus($con, $order, $newStatus, $changedBy, $note = null)
{
    $newStatus = (int)$newStatus;
    $current = (int)$order['status'];

    if ($changedBy === 'admin') {
        $allowed = in_array($newStatus, orderAdminNextSteps($current), true);
    } else {
        $allowed = $newStatus === ORDER_CANCELED_BY_CUSTOMER && orderCustomerCanCancel($order);
    }
    if (!$allowed) {
        return "This order can't be changed to " . orderStatusInfo($newStatus)['label'] . " any more.";
    }
    // Payment first: an order is only handed over once its payment has been received
    if ($newStatus === ORDER_DELIVERED && !orderIsPaid($order)) {
        return "Receive the payment first. Mark the payment as received, then mark the order as delivered.";
    }

    $id = (int)$order['id'];
    $delivered = $newStatus === ORDER_DELIVERED ? 1 : 0;
    $remark = orderIsCanceled($newStatus) && $note !== null && trim($note) !== '' ? mb_substr(trim($note), 0, 255) : $order['remark'];

    // Only update if nobody changed the order in the meantime (status and payment)
    $paidNow = (int)$order['payment_status'];
    $stmt = mysqli_prepare($con, "UPDATE `sales` SET status = ?, delivery_status = ?, remark = ? WHERE id = ? AND status = ? AND payment_status = ?");
    mysqli_stmt_bind_param($stmt, "iisiii", $newStatus, $delivered, $remark, $id, $current, $paidNow);
    mysqli_stmt_execute($stmt);
    $changed = mysqli_stmt_affected_rows($stmt) === 1;
    mysqli_stmt_close($stmt);
    if (!$changed) {
        return "The order was updated by someone else. Please check it again.";
    }

    orderLogStatus($con, $id, $newStatus, $changedBy, $note);
    return true;
}

function orderIsPaid($order)
{
    return $order['payment_status'] == '1';
}

// Offline orders that are on their way and still waiting for the cash/offline payment
function orderAwaitingOfflinePayment($order)
{
    return $order['payment_mode'] == '0' && !orderIsPaid($order) && (int)$order['status'] === ORDER_SHIPPED;
}

// Admin marks an unpaid order as paid (e.g. cash or bank transfer received)
function orderMarkPaid($con, $order, $note = null)
{
    if ($order['payment_status'] == '1' || orderIsCanceled($order['status'])) {
        return "This order can't be marked as paid.";
    }
    $id = (int)$order['id'];
    $stmt = mysqli_prepare($con, "UPDATE `sales` SET payment_status = 1 WHERE id = ? AND payment_status = 0");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    orderLogStatus($con, $id, -1, 'admin', $note !== null && trim($note) !== '' ? $note : ($order['payment_mode'] == '0' ? "Cash / offline payment received" : "Marked as paid"));
    return true;
}

// Order totals from the saved items (never trust totals sent by the browser)
function orderTotals($order, $items)
{
    $subtotal = 0;
    foreach ($items as $item) {
        $subtotal += (float)$item['price'] * (int)$item['qty'];
    }
    $delivery = (float)$order['delivery_changes'];
    return array('subtotal' => $subtotal, 'delivery' => $delivery, 'tax' => (float)$order['tax'], 'total' => (float)$order['total']);
}

function orderMoney($value)
{
    return "Rs. " . number_format((float)$value, 2);
}

function orderNumber($id)
{
    return "#" . str_pad((string)(int)$id, 5, "0", STR_PAD_LEFT);
}

function orderHistoryLabel($entry)
{
    if ((int)$entry['status'] === -1) {
        return array('label' => 'Payment received', 'class' => 'paid', 'icon' => 'fa-rupee-sign');
    }
    $info = orderStatusInfo($entry['status']);
    if ((int)$entry['status'] === ORDER_PENDING) {
        $info['label'] = 'Order placed';
        $info['icon'] = 'fa-shopping-bag';
    }
    return $info;
}

// ---------- CSRF for order actions ----------
function orderCsrfToken()
{
    if (empty($_SESSION['order_csrf'])) {
        $_SESSION['order_csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['order_csrf'];
}

function orderCsrfValid($token)
{
    return !empty($_SESSION['order_csrf']) && is_string($token) && hash_equals($_SESSION['order_csrf'], $token);
}

// One-time message shown after a redirect
function orderFlash($message = null, $type = 'success')
{
    if ($message !== null) {
        $_SESSION['order_flash'] = array('message' => $message, 'type' => $type);
        return null;
    }
    $flash = isset($_SESSION['order_flash']) ? $_SESSION['order_flash'] : null;
    unset($_SESSION['order_flash']);
    return $flash;
}
