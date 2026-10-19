<?php include_once("checkLogin.php");
include_once('functions.php');

// Returns the first column of the first row, or 0
function dashValue($sql)
{
    $data = selectData($sql);
    if(is_array($data) && count($data) >= 1)
    {
        $row = array_values($data[0]);
        return $row[0] === null ? 0 : $row[0];
    }
    return 0;
}

$dt = date("Y-m-d");
// Order status: 0 Pending, 1 Confirmed, 5 Shipped, 4 Delivered, 2 Canceled, 3 Canceled by customer
$todayOrders     = dashValue("SELECT count(*) FROM `sales` where dt='$dt'");
$totalOrders     = dashValue("SELECT count(*) FROM `sales`");
$pendingOrders   = dashValue("SELECT count(*) FROM `sales` where status='0'");
$confirmedOrders = dashValue("SELECT count(*) FROM `sales` where status='1'");
$canceledOrders  = dashValue("SELECT count(*) FROM `sales` where status='2' or status='3'");
$completedOrders = dashValue("SELECT count(*) FROM `sales` where status='4'");
$shippedOrders   = dashValue("SELECT count(*) FROM `sales` where status='5'");
$revenue         = dashValue("SELECT SUM(total) FROM `sales` where status NOT IN ('2','3')");
$paidRevenue     = dashValue("SELECT SUM(total) FROM `sales` where payment_status='1' and status NOT IN ('2','3')");
$customers       = dashValue("SELECT count(*) FROM `users`");
$products        = dashValue("SELECT count(*) FROM `products`");
$activeProducts  = dashValue("SELECT count(*) FROM `products` where status='1'");
$categories      = dashValue("SELECT count(*) FROM `category`");

$recentOrders = selectData("SELECT s.*, u.name AS customerName FROM `sales` s LEFT JOIN `users` u ON u.id = s.user_id ORDER BY s.id DESC LIMIT 6");
$latestProducts = selectData("SELECT p.*, c.name AS categoryName FROM `products` p LEFT JOIN `category` c ON c.id = p.category_id ORDER BY p.id DESC LIMIT 5");
$perCategory = selectData("SELECT c.name, COUNT(p.id) AS total FROM `category` c LEFT JOIN `products` p ON p.category_id = c.id GROUP BY c.id ORDER BY total DESC, c.name");

$categoryLabels = array();
$categoryCounts = array();
if(is_array($perCategory))
{
    foreach ($perCategory as $row)
    {
        $categoryLabels[] = $row['name'];
        $categoryCounts[] = (int)$row['total'];
    }
}

$statusNames = array(0 => array("Pending", "pending"), 1 => array("Confirmed", "confirmed"), 2 => array("Canceled", "canceled"), 3 => array("Canceled", "canceled"), 4 => array("Delivered", "completed"), 5 => array("Shipped", "shipped"));

$hour = (int)date("G");
$greeting = $hour < 12 ? "Good morning" : ($hour < 17 ? "Good afternoon" : "Good evening");
$adminFirstName = isset($_SESSION['adminName']) && $_SESSION['adminName'] != "" ? explode(" ", trim($_SESSION['adminName']))[0] : "Admin";

function money($value)
{
    return "Rs. " . number_format((float)$value, 2);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard | Pet Shop Admin</title>
  <link rel="icon" href="../assets/images/favicon/favicon.png">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<?php $he = 1; include_once("header.php") ?>

  <div class="content-wrapper">
    <section class="content pt-3">
      <div class="container-fluid ad-dash">

        <!-- Welcome -->
        <div class="ad-hero">
          <div>
            <h2><?php echo $greeting . ", " . htmlspecialchars($adminFirstName); ?> 👋</h2>
            <p>Here's what's happening in your shop.</p>
            <span class="ad-hero-date"><i class="far fa-calendar-alt"></i> <?php echo date("l, j F Y"); ?></span>
          </div>
          <div class="ad-actions">
            <a class="ad-action primary" href="addProducts.php"><i class="fas fa-plus"></i> Add product</a>
            <a class="ad-action" href="addCategory.php"><i class="fas fa-tags"></i> Add category</a>
            <a class="ad-action" href="adminOrders.php"><i class="fas fa-receipt"></i> Orders</a>
          </div>
        </div>

        <!-- KPI cards -->
        <div class="ad-kpis">
          <a class="ad-kpi gold" href="adminOrders.php">
            <div class="ad-kpi-top">
              <div>
                <div class="ad-kpi-label">Total revenue</div>
                <div class="ad-kpi-value"><?php echo money($revenue); ?></div>
              </div>
              <span class="ad-kpi-icon"><i class="fas fa-rupee-sign"></i></span>
            </div>
            <div class="ad-kpi-foot"><i class="fas fa-check-circle"></i> <?php echo money($paidRevenue); ?> paid</div>
          </a>
          <a class="ad-kpi green" href="adminOrders.php">
            <div class="ad-kpi-top">
              <div>
                <div class="ad-kpi-label">Total orders</div>
                <div class="ad-kpi-value"><?php echo $totalOrders; ?></div>
              </div>
              <span class="ad-kpi-icon"><i class="fas fa-shopping-bag"></i></span>
            </div>
            <div class="ad-kpi-foot"><i class="fas fa-check-double"></i> <?php echo $completedOrders; ?> delivered</div>
          </a>
          <a class="ad-kpi orange" href="adminOrders.php">
            <div class="ad-kpi-top">
              <div>
                <div class="ad-kpi-label">Pending orders</div>
                <div class="ad-kpi-value"><?php echo $pendingOrders; ?></div>
              </div>
              <span class="ad-kpi-icon"><i class="fas fa-hourglass-half"></i></span>
            </div>
            <div class="ad-kpi-foot"><i class="fas fa-thumbs-up"></i> <?php echo $confirmedOrders; ?> confirmed</div>
          </a>
          <a class="ad-kpi purple" href="viewusers.php">
            <div class="ad-kpi-top">
              <div>
                <div class="ad-kpi-label">Customers</div>
                <div class="ad-kpi-value"><?php echo $customers; ?></div>
              </div>
              <span class="ad-kpi-icon"><i class="fas fa-users"></i></span>
            </div>
            <div class="ad-kpi-foot"><i class="fas fa-user-plus"></i> Registered accounts</div>
          </a>
        </div>

        <!-- Mini stats -->
        <div class="ad-minis">
          <a class="ad-mini" href="products.php"><i class="fas fa-box-open"></i><div><b><?php echo $products; ?></b><span><?php echo $activeProducts; ?> active products</span></div></a>
          <a class="ad-mini" href="category.php"><i class="fas fa-tags"></i><div><b><?php echo $categories; ?></b><span>Categories</span></div></a>
          <a class="ad-mini blue" href="adminOrders.php"><i class="fas fa-calendar-day"></i><div><b><?php echo $todayOrders; ?></b><span>Orders today</span></div></a>
          <a class="ad-mini red" href="adminOrders.php"><i class="fas fa-ban"></i><div><b><?php echo $canceledOrders; ?></b><span>Canceled orders</span></div></a>
        </div>

        <!-- Charts -->
        <div class="ad-grid">
          <div class="ad-panel">
            <div class="ad-panel-head">
              <div><h3>Products per category</h3><small>How your catalogue is spread</small></div>
              <a href="category.php">Manage <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="ad-panel-body">
              <?php if(count($categoryLabels) >= 1) { ?>
                <div class="ad-chart"><canvas id="categoryChart"></canvas></div>
              <?php } else { ?>
                <div class="ad-empty"><i class="fas fa-tags"></i>No categories yet</div>
              <?php } ?>
            </div>
          </div>
          <div class="ad-panel">
            <div class="ad-panel-head">
              <div><h3>Orders by status</h3><small>All <?php echo $totalOrders; ?> orders</small></div>
            </div>
            <div class="ad-panel-body">
              <?php if($totalOrders > 0) { ?>
                <div class="ad-chart" style="height:200px;"><canvas id="statusChart"></canvas></div>
                <ul class="ad-legend">
                  <li><span class="ad-dot" style="background:#f97316"></span>Pending <b><?php echo $pendingOrders; ?></b></li>
                  <li><span class="ad-dot" style="background:#3b82f6"></span>Confirmed <b><?php echo $confirmedOrders; ?></b></li>
                  <li><span class="ad-dot" style="background:#8c6aa0"></span>Shipped <b><?php echo $shippedOrders; ?></b></li>
                  <li><span class="ad-dot" style="background:#499f86"></span>Delivered <b><?php echo $completedOrders; ?></b></li>
                  <li><span class="ad-dot" style="background:#e5484d"></span>Canceled <b><?php echo $canceledOrders; ?></b></li>
                </ul>
              <?php } else { ?>
                <div class="ad-empty"><i class="fas fa-receipt"></i>No orders yet</div>
              <?php } ?>
            </div>
          </div>
        </div>

        <!-- Recent orders + latest products -->
        <div class="ad-grid">
          <div class="ad-panel">
            <div class="ad-panel-head">
              <div><h3>Recent orders</h3><small>The latest orders from your customers</small></div>
              <a href="adminOrders.php">View all <i class="fas fa-arrow-right"></i></a>
            </div>
            <?php if(is_array($recentOrders) && count($recentOrders) >= 1) { ?>
              <div class="ad-table-wrap">
                <table class="ad-table">
                  <thead>
                    <tr><th>Order</th><th>Customer</th><th>Amount</th><th>Status</th><th></th></tr>
                  </thead>
                  <tbody>
                    <?php foreach ($recentOrders as $order) {
                        $status = isset($statusNames[$order['status']]) ? $statusNames[$order['status']] : array("Unknown", "unpaid");
                        $customer = $order['customerName'] != "" ? $order['customerName'] : "Customer #" . $order['user_id'];
                    ?>
                      <tr>
                        <td><span class="ad-order-id">#<?php echo $order['id']; ?></span><small class="ad-sub"><?php echo $order['dt'] ? date("d M Y", strtotime($order['dt'])) : "-"; ?></small></td>
                        <td><div class="ad-cust"><span class="ad-avatar"><?php echo htmlspecialchars(strtoupper(substr($customer, 0, 1))); ?></span><?php echo htmlspecialchars($customer); ?></div></td>
                        <td><span class="ad-amount"><?php echo money($order['total']); ?></span><small class="ad-sub"><?php echo $order['payment_mode'] == '1' ? 'Online' : 'Offline'; ?> · <span class="<?php echo $order['payment_status'] == '1' ? 'ad-paid' : 'ad-unpaid'; ?>"><?php echo $order['payment_status'] == '1' ? 'Paid' : 'Unpaid'; ?></span></small></td>
                        <td><span class="ad-badge <?php echo $status[1]; ?>"><?php echo $status[0]; ?></span></td>
                        <td><a class="ad-view" href="adminOrderDetails.php?id=<?php echo $order['id']; ?>" title="View order" aria-label="View order <?php echo $order['id']; ?>"><i class="fas fa-eye"></i></a></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <div class="ad-empty"><i class="fas fa-receipt"></i>No orders yet</div>
            <?php } ?>
          </div>

          <div class="ad-panel">
            <div class="ad-panel-head">
              <div><h3>Latest products</h3><small>Recently added to the shop</small></div>
              <a href="products.php">View all <i class="fas fa-arrow-right"></i></a>
            </div>
            <?php if(is_array($latestProducts) && count($latestProducts) >= 1) { ?>
              <ul class="ad-products">
                <?php foreach ($latestProducts as $product) { ?>
                  <li>
                    <img class="ad-prod-img" src="upload_images/<?php echo $product['image']; ?>" alt="">
                    <div class="ad-prod-info">
                      <a href="addProducts.php?getData=<?php echo $product['id']; ?>" title="Edit product"><?php echo htmlspecialchars($product['name']); ?></a>
                      <span><?php echo htmlspecialchars((string)$product['categoryName']); ?><?php echo $product['status'] == '1' ? '' : ' · Inactive'; ?></span>
                    </div>
                    <span class="ad-prod-price"><?php echo money($product['price']); ?></span>
                  </li>
                <?php } ?>
              </ul>
            <?php } else { ?>
              <div class="ad-empty"><i class="fas fa-box-open"></i>No products yet</div>
            <?php } ?>
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
<script src="plugins/chart.js/Chart.min.js"></script>
<script>
(function () {
    var categoryLabels = <?php echo json_encode($categoryLabels); ?>;
    var categoryCounts = <?php echo json_encode($categoryCounts); ?>;
    var statusCounts = <?php echo json_encode(array((int)$pendingOrders, (int)$confirmedOrders, (int)$shippedOrders, (int)$completedOrders, (int)$canceledOrders)); ?>;
    var charts = [];

    function css(name) { return getComputedStyle(document.documentElement).getPropertyValue(name).trim(); }

    function draw() {
        charts.forEach(function (c) { c.destroy(); });
        charts = [];
        var text = css("--ad-muted"), grid = css("--ad-line"), card = css("--ad-card");
        Chart.defaults.global.defaultFontFamily = "Nunito, sans-serif";
        Chart.defaults.global.defaultFontColor = text;

        var categoryCanvas = document.getElementById("categoryChart");
        if (categoryCanvas) {
            charts.push(new Chart(categoryCanvas, {
                type: "bar",
                data: {
                    labels: categoryLabels,
                    datasets: [{ data: categoryCounts, backgroundColor: "#f2ab00", hoverBackgroundColor: "#499f86", barPercentage: 0.55, maxBarThickness: 46 }]
                },
                options: {
                    maintainAspectRatio: false, legend: { display: false },
                    tooltips: { callbacks: { label: function (item) { return " " + item.yLabel + " product" + (item.yLabel == 1 ? "" : "s"); } } },
                    scales: {
                        xAxes: [{ gridLines: { display: false }, ticks: { fontStyle: "bold" } }],
                        yAxes: [{ gridLines: { color: grid, zeroLineColor: grid, drawBorder: false }, ticks: { beginAtZero: true, precision: 0 } }]
                    }
                }
            }));
        }

        var statusCanvas = document.getElementById("statusChart");
        if (statusCanvas) {
            charts.push(new Chart(statusCanvas, {
                type: "doughnut",
                data: {
                    labels: ["Pending", "Confirmed", "Shipped", "Delivered", "Canceled"],
                    datasets: [{ data: statusCounts, backgroundColor: ["#f97316", "#3b82f6", "#8c6aa0", "#499f86", "#e5484d"], borderColor: card, borderWidth: 4 }]
                },
                options: { maintainAspectRatio: false, cutoutPercentage: 68, legend: { display: false } }
            }));
        }
    }

    draw();
    // Redraw in the new colours when the theme is switched
    new MutationObserver(function () { setTimeout(draw, 60); }).observe(document.documentElement, { attributes: true, attributeFilter: ["data-theme"] });
})();
</script>
</body>
</html>
