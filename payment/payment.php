<?php 
include_once("../admin/userCheckLogin.php"); 
include_once("../admin/functions.php");

/* 
 * PayPal and database configuration 
 */ 

if(isset($_REQUEST['txtId']))
{
    // exit;
// //getting Details
$id           =      $_REQUEST['txtId'];
// $name         =      $_GET['txtName'];
// $phone        =      $_GET['txtPhone'];
// $email        =      $_GET['txtEmail'];
// The amount always comes from the order itself, and only the customer's own unpaid online order can be paid
include_once("../admin/orders_lib.php");
$order = orderFetch($con, $id, $_SESSION['userId']);
if ($order === null || !orderCanPayOnline($order)) {
    header('location:../admin/userOrders.php');
    exit;
}
$id = (int)$order['id'];
$amount = number_format((float)$order['total'], 2, '.', '');
// $donerId       =      $_GET['txtDonerId'];
// $YName = "-";
// $Purpose = "-";
// if(isset($_GET['txtYName']))
// {
//     if(!empty($_GET['txtYName']))
//     {
//         $YName = $_GET['txtYName'];
//     }
// }
// if(isset($_GET['txtPurpose']))
// {
//     if(!empty($_GET['txtPurpose']))
//     {
//         $Purpose = $_GET['txtPurpose'];
//     }
// }

// print_r($_GET);
// exit;

// //genrating order number
$orderno= mt_rand(100000000, 999999999);


// PayPal configuration 
define('PAYPAL_ID', 'sb-45ekz1506764@business.example.com'); //Tarang Sir
// define('PAYPAL_ID', 'sb-ptzye6086884@business.example.com'); //Sumit
define('PAYPAL_SANDBOX', TRUE); //TRUE or FALSE 

// Return to this same site, wherever it is running
$baseURL = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . rtrim(str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])), '/');
$returnURL = $baseURL . "/success.php?";
$returnURL.="orderId={$orderno}&";
$returnURL.="txtId={$id}&";
$returnURL.="amount={$amount}&";

define('PAYPAL_RETURN_URL', $returnURL);
define('PAYPAL_CANCEL_URL', $baseURL . '/cancel.php?txtId=' . $id); 
define('PAYPAL_NOTIFY_URL', $baseURL . '/cancel.php'); 
define('PAYPAL_CURRENCY', 'USD'); 
 
// Database configuration 
 
 
// Change not required 
define('PAYPAL_URL', (PAYPAL_SANDBOX == true)?"https://www.sandbox.paypal.com/cgi-bin/webscr":"https://www.sandbox.paypal.com/cgi-bin/webscr");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment | Pet Shop (demo project)</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap">
    <link rel="stylesheet" href="../assets/css/demo-notice.css">
    <script src="../assets/js/theme.js"></script>
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 20px; background: #f7f5f8; font-family: "Nunito", system-ui, sans-serif; }
        :root[data-theme="dark"] body { background: #17121b; }
        .demo-notice { box-shadow: 0 16px 40px rgba(64, 49, 72, .15); }
        .pay-summary { margin: 4px 0 14px; font-size: 15px; font-weight: 700; }
        .pay-back { display: inline-block; margin-top: 14px; color: #499f86; font-weight: 800; text-decoration: none; }
        .pay-back:hover { color: #f2ab00; }
    </style>
</head>
<body>

<!-- Demo project: don't send customers straight to payment; show the notice first -->
<div class="demo-notice" role="region" aria-labelledby="payTitle">
    <div class="demo-notice-icon" aria-hidden="true">!</div>
    <h2 id="payTitle">Demo project: do not pay</h2>
    <p class="pay-summary">Order <?php echo orderNumber($id); ?> · Rs. <?php echo number_format((float)$amount, 2); ?></p>
    <p class="demo-notice-warn"><b>This website is only a student project.</b> Please do not make any real payment. Payment uses the PayPal <b>sandbox</b> (test mode) and only works with PayPal test accounts.</p>
    <p class="demo-notice-gu" lang="gu">આ વેબસાઇટ ફક્ત પ્રોજેક્ટ માટે છે. અહીં કોઈ પેમેન્ટ કરવું નહીં.</p>
    <button type="submit" form="payment" class="demo-notice-btn">Continue to PayPal sandbox (test only)</button>
    <a class="pay-back" href="../admin/userOrderDetails.php?id=<?php echo $id; ?>">&larr; Back to my order</a>
</div>
   <!-- PayPal payment form for displaying the buy button -->
                <form action="<?php echo PAYPAL_URL; ?>" method="post" id="payment">
                    <!-- Identify your business so that you can collect the payments. -->
                    <input type="hidden" name="business" value="<?php echo PAYPAL_ID; ?>">
                    
                    <!-- Specify a Buy Now button. -->
                    <input type="hidden" name="cmd" value="_xclick">
                    
                    <!-- Specify details about the item that buyers will purchase. -->
                    <input type="hidden" name="item_name" value="<?php echo 'Pet Shop order ' . orderNumber($id); ?>">
                    <input type="hidden" name="item_number" value="<?php echo $id; ?>">
                    <input type="hidden" name="amount" value="<?php echo $amount; ?>">
                    <input type="hidden" name="currency_code" value="<?php echo PAYPAL_CURRENCY; ?>">
                    
                    <!-- Specify URLs -->
                    <input type="hidden" name="return" value="<?php echo PAYPAL_RETURN_URL ?>">
                    <input type="hidden" name="cancel_return" value="<?php echo PAYPAL_CANCEL_URL; ?>">
                    <input type='hidden' name='notify_url' value="<?php echo PAYPAL_RETURN_URL; ?>">
                    
                    <!-- Display the payment button. -->
                    <input type="image" name="submit" border="0" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" 
                    style="display: none;">
                </form>

 <script src="js/jquery.min.js"></script>
</body>
</html>
<?php
}
?>