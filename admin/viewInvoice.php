<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
include_once('functions.php');
// Invoices are only for the admin, or the customer who placed the order
$invoiceAllowed = false;
if (isset($_GET['InvoiceId']) && $_GET['InvoiceId'] !== '') {
     $invoiceOwner = selectData("SELECT user_id FROM `sales` WHERE id='" . (int)$_GET['InvoiceId'] . "'");
     $invoiceAllowed = isset($_SESSION['adminId'])
          || (isset($_SESSION['userId']) && is_array($invoiceOwner) && count($invoiceOwner) >= 1 && (int)$invoiceOwner[0]['user_id'] === (int)$_SESSION['userId']);
}
if (!$invoiceAllowed) {
     header('location:' . (isset($_SESSION['adminId']) ? 'adminOrders.php' : (isset($_SESSION['userId']) ? 'userOrders.php' : 'userLogin.php')));
     exit;
}
if(isset($_GET['InvoiceId']))
{
     if(!empty($_GET['InvoiceId']))
     {
          $invoiceId = (int)$_GET['InvoiceId'];
          $salesData = selectData("SELECT * FROM `sales` where id='$invoiceId'");
          if(is_array($salesData) && count($salesData) >= 1)
          {
               $userId = $salesData[0]['user_id'];
               $userData = selectData("SELECT * FROM `users` where id='$userId'");
               if(is_array($userData) && count($userData) >= 1)
               {
                    $salesData = $salesData[0];
                    $userData = $userData[0];

                    $bookingDate=date_create($salesData['dt']);
                    $bookingDate = date_format($bookingDate,"d/m/Y");

                    $adminData = selectData("SELECT * FROM `admin` limit 1");
                    if(is_array($adminData) && count($adminData) >= 1)
                    {
                         $adminData = $adminData[0];
                    }
               }
          }
     }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoice</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body>
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-12">
        <h2 class="page-header">
          <i class="fas fa-globe"></i> Pet Care.
          <small class="float-right">Date: <?php echo $bookingDate; ?></small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        From
        <address>
          <strong>Pet care.</strong><br>
          Nr Railway Station, Vadodara.<br>
          Phone: +91 1234567890<br>
          Email: admin@gmail.com
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        To
        <address>
          <strong><?php echo $userData['name']; ?></strong><br>
          <?php echo $salesData['delivery_address']; ?><br>
          Phone: <?php echo $userData['phone']; ?><br>
          Email: <?php echo $userData['email']; ?>
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b>Invoice: <?php echo $salesData['id']; ?></b><br>
        
        <!-- <b>Order ID:</b> <?php //echo $salesData['id']; ?><br> -->
        <b>Payment Status:</b> <?php echo ($salesData['payment_status'] == "0" ? "Pending" : "Paid"); ?><br>
        <b>Payment Status:</b> <?php echo ($salesData['payment_mode'] == "0" ? "Offline" : "Online"); ?><br>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-striped">
          <thead>
          <tr>
               <th>Id</th>
               <th>Product</th>
               <th>Description</th>
               <th>Qty</th>
               <th>Price</th>
               <th>Subtotal</th>
          </tr>
          </thead>
          <tbody>
            <?php
                    $salesId = $salesData['id'];
                    $productsData =   selectData("SELECT 
                                                  p.*,
                                                  si.qty,
                                                  si.sales_id
                                                  FROM 
                                                  `sales_items` si 
                                                  inner join products p on p.id = si.product_id 
                                                  where sales_id = '$salesId' ; ");
                    $rowCount = 1;
                    $subTotal = 0;
                    if(is_array($productsData) && count($productsData) >= 1)
                    {
                         foreach ($productsData as $row) 
                         {
                              ?>
                                   <tr>
                                        <td><?php echo $rowCount; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['description']; ?></td>
                                        <td><?php echo $row['qty']; ?></td>
                                        <td><?php echo $row['price']; ?></td>
                                        <td><?php echo ($row['price'] * $row['qty']); ?></td>
                                   </tr>
                              <?php
                              $rowCount++;
                              $subTotal = ($subTotal + ($row['price'] * $row['qty']));
                         }
                    }

            ?>
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-6">
        <p class="lead">Payment Methods:</p>
        <img src="dist/img/credit/visa.png" alt="Visa">
        <img src="dist/img/credit/mastercard.png" alt="Mastercard">
        <img src="dist/img/credit/american-express.png" alt="American Express">
        <img src="dist/img/credit/paypal2.png" alt="Paypal">

        <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
          You can pay Payment using online transection also.
        </p>
      </div>
      <!-- /.col -->
      <div class="col-6">
        <!-- <p class="lead">Amount Due 2/22/2014</p> -->

        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Subtotal:</th>
              <td>₹ <?php echo $subTotal; ?></td>
            </tr>

            <tr>
              <th>Tax</th>
              <td>₹ <?php echo $salesData['tax']; ?></td>
            </tr>
            <tr>
              <th>Shipping:</th>
              <td>₹ <?php echo $salesData['delivery_changes']; ?></td>
            </tr>
            <tr>
              <th>Total:</th>
              <td>₹ <?php echo $salesData['total']; ?></td>
            </tr>
          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
<!-- Page specific script -->
<script>
//   window.addEventListener("load", window.print());
</script>
</body>
</html>
