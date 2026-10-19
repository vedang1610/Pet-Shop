<?php
session_start();
include('admin/db.php');
include('admin/functions.php');

$productsData = null;
if(isset($_GET['getData']))
{
	$id = $_GET['getData'];
    $productsData = selectData("SELECT p.*, c.name AS categoryName FROM `products` p LEFT JOIN `category` c ON c.id = p.category_id WHERE p.id='$id'");
    $productsData = (is_array($productsData) && count($productsData) >= 1) ? $productsData[0] : null;
}
if($productsData == null)
{
    header('location:products.php');
    exit;
}

// Main photo first, then the extra photos uploaded for this product
$productId = $productsData['id'];
$gallery = array($productsData['image']);
$productImages = selectData("SELECT * FROM `product_images` where product_id='$productId' order by id desc");
if(is_array($productImages))
{
    foreach ($productImages as $productImage)
    {
        $gallery[] = $productImage['image'];
    }
}

$related = selectData("SELECT * FROM `products` WHERE status='1' AND category_id='".$productsData['category_id']."' AND id <> '$productId' ORDER BY id DESC LIMIT 4");
if(!is_array($related) || count($related) < 1)
{
    $related = selectData("SELECT * FROM `products` WHERE status='1' AND id <> '$productId' ORDER BY id DESC LIMIT 4");
}

$name = htmlspecialchars($productsData['name']);
$categoryName = htmlspecialchars((string)$productsData['categoryName']);
$isAvailable = $productsData['status'] == '1';
?>
<!DOCTYPE html>
<html class="no-js" lang="en-US">
    
<head>
        <meta charset="UTF-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <title>Pet Shop</title>
        <meta name="description" content="240+ Best Bootstrap Templates are available on this website. Find your template for your project compatible with the most popular HTML library in the world."/>
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
        <link rel="profile" href="https://gmpg.org/xfn/11">
        <link rel="canonical" href="Replace_with_your_PAGE_URL.html" />

        <!-- Open Graph (OG) meta tags are snippets of code that control how URLs are displayed when shared on social media  -->
        <meta property="og:locale" content="en_US" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="Add a Title" />
        <meta property="og:url" content="PAGE_URL" />
        <meta property="og:site_name" content="SITE_NAME" />
        <!-- For the og:image content, replace the # with a link of an image -->
        <meta property="og:image" content="#" />
        <meta property="og:description" content="Add_Description_Text" />
        <meta name="robots" content="noindex, follow" />
        <!-- Add site Favicon -->
        <link rel="icon" href="assets/images/favicon/favicon.png" sizes="32x32" />
        <link rel="icon" href="assets/images/favicon/favicon.png" sizes="192x192" />
        <link rel="apple-touch-icon" href="assets/images/favicon/favicon.png" />
        <meta name="msapplication-TileImage" content="assets/images/favicon/favicon.png" />
        <!-- Structured Data  -->
        <script type="application/ld+json">
        {
          "@context": "http://schema.org",
          "@type": "WebSite",
          "name": "Replace_with_your_site_title",
          "url": "Replace_with_your_site_URL"
        }
        </script>
        <!-- Google Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap&amp;subset=cyrillic,cyrillic-ext,latin-ext,vietnamese" type="text/css" media="all">

        <!-- CSS
  ============================================ -->

            <!-- Vendor CSS (Bootstrap & Icon Font) -->
       <!--   <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/vendor/ionicons.min.css">
        <link rel="stylesheet" href="assets/css/vendor/linearicon.css">
        <link rel="stylesheet" href="assets/css/vendor/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/vendor/line-awesome.css"> -->

            <!-- Plugins CSS (All Plugins Files) -->
        <!--  <link rel="stylesheet" href="assets/css/plugins/animate.css">
        <link rel="stylesheet" href="assets/css/plugins/jquery-ui.min.css">
        <link rel="stylesheet" href="assets/css/plugins/slick.css"> -->

        <!-- Use the minified version files listed below for better performance and remove the files listed above -->
        <link rel="stylesheet" href="assets/css/vendor/vendor.min.css" />
        <link rel="stylesheet" href="assets/css/plugins/plugins.min.css" />
        <!-- <link rel="stylesheet" href="assets/css/style.min.css"> -->

        <!-- Main Style CSS -->
        <link rel="stylesheet" href="assets/css/style.css" />
        <!-- Functions JS -->
        <link rel="stylesheet" href="assets/css/product-details.css" />
    </head>

    <body>
    <!-- Header -->
    <?php include("header.php"); ?>

    <div class="offcanvas-overlay"></div>

        <!-- Breadcrumb Area Start -->
        <div class="breadcrumb-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcrumb-content">
                            <ul class="nav">
                                <li><a href="index.php">Home</a></li>
                                <li><a href="products.php">Shop</a></li>
                                <?php if($categoryName != "") { ?>
                                    <li><a href="products.php?categoryId=<?php echo $productsData['category_id']; ?>"><?php echo $categoryName; ?></a></li>
                                <?php } ?>
                                <li><?php echo $name; ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Breadcrumb Area End-->

        <!-- Product Details Start -->
        <section class="pd-section">
            <div class="container">
                <div class="pd-layout">

                    <!-- Gallery -->
                    <div class="pd-gallery">
                        <div class="pd-main" id="pdMain">
                            <?php if($categoryName != "") { ?><span class="pd-chip"><i class="las la-paw"></i> <?php echo $categoryName; ?></span><?php } ?>
                            <img id="pdMainImage" src="admin/upload_images/<?php echo $gallery[0]; ?>" alt="<?php echo $name; ?>">
                            <span class="pd-zoom-hint"><i class="las la-search-plus"></i> Hover to zoom</span>
                        </div>
                        <?php if(count($gallery) > 1) { ?>
                            <div class="pd-thumbs">
                                <?php foreach ($gallery as $index => $image) { ?>
                                    <button type="button" class="pd-thumb <?php echo $index == 0 ? 'active' : ''; ?>" data-image="admin/upload_images/<?php echo $image; ?>" aria-label="Show photo <?php echo $index + 1; ?>">
                                        <img src="admin/upload_images/<?php echo $image; ?>" alt="">
                                    </button>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Info -->
                    <div class="pd-info">
                        <?php if($categoryName != "") { ?>
                            <a class="pd-category" href="products.php?categoryId=<?php echo $productsData['category_id']; ?>"><?php echo $categoryName; ?></a>
                        <?php } ?>
                        <h1 class="pd-title"><?php echo $name; ?></h1>

                        <div class="pd-price-row">
                            <div class="pd-price"><span>Rs.</span><?php echo $productsData['price']; ?></div>
                            <?php if($isAvailable) { ?>
                                <span class="pd-stock"><i class="las la-check-circle"></i> In stock</span>
                            <?php } else { ?>
                                <span class="pd-stock pd-stock-out"><i class="las la-times-circle"></i> Not available</span>
                            <?php } ?>
                        </div>

                        <p class="pd-summary"><?php echo nl2br(htmlspecialchars($productsData['description'])); ?></p>

                        <div class="pd-buy" id="pdBuy">
                            <label class="pd-label" for="qty">Quantity</label>
                            <div class="pd-buy-row">
                                <div class="pd-qty">
                                    <button type="button" data-step="-1" aria-label="Decrease quantity"><i class="las la-minus"></i></button>
                                    <input type="number" min="1" max="50" name="qty" id="qty" value="1" aria-label="Quantity" ondrop="return false;" onpaste="return false;">
                                    <button type="button" data-step="1" aria-label="Increase quantity"><i class="las la-plus"></i></button>
                                </div>
                                <button type="button" class="pd-btn" onclick="addCard(<?php echo $productsData['id']; ?>);" <?php echo $isAvailable ? '' : 'disabled'; ?>>
                                    <i class="las la-shopping-cart"></i> Add to Cart
                                </button>
                            </div>
                            <div class="pd-total">Total: <b>Rs. <span id="pdTotal"><?php echo $productsData['price']; ?></span></b></div>
                        </div>

                        <ul class="pd-perks">
                            <li><i class="las la-shipping-fast"></i><div><b>Home delivery</b><span>Flat Rs. 50 shipping per order</span></div></li>
                            <li><i class="las la-wallet"></i><div><b>Pay your way</b><span>Online payment or offline</span></div></li>
                            <li><i class="las la-headset"></i><div><b>Need help?</b><span><a href="tel:+911234567890">+91 12345 67890</a></span></div></li>
                        </ul>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="pd-tabs">
                    <div class="pd-tab-list" role="tablist">
                        <button type="button" class="pd-tab active" role="tab" aria-selected="true" data-tab="pdDesc">Description</button>
                        <button type="button" class="pd-tab" role="tab" aria-selected="false" data-tab="pdDelivery">Delivery &amp; Payment</button>
                    </div>
                    <div class="pd-tab-panel active" id="pdDesc" role="tabpanel">
                        <p><?php echo nl2br(htmlspecialchars($productsData['description'])); ?></p>
                    </div>
                    <div class="pd-tab-panel" id="pdDelivery" role="tabpanel">
                        <ul class="pd-info-list">
                            <li><i class="las la-truck"></i> A flat shipping charge of Rs. 50 is added to every order.</li>
                            <li><i class="las la-map-marker"></i> Enter your delivery address in the cart before placing the order.</li>
                            <li><i class="las la-credit-card"></i> Choose <b>Online</b> to pay right away, or <b>Offline</b> to pay later.</li>
                            <li><i class="las la-user-circle"></i> Track your orders any time from <a href="admin/userDashboard.php">My Account</a>.</li>
                        </ul>
                    </div>
                </div>

                <!-- Related products -->
                <?php if(is_array($related) && count($related) >= 1) { ?>
                    <div class="pd-related">
                        <div class="pd-related-head">
                            <h2>You may also like</h2>
                            <a href="products.php?categoryId=<?php echo $productsData['category_id']; ?>">View all <i class="las la-arrow-right"></i></a>
                        </div>
                        <div class="pd-related-grid">
                            <?php foreach ($related as $row) { ?>
                                <article class="pd-card">
                                    <a class="pd-card-img" href="productDetails.php?getData=<?php echo $row['id']; ?>">
                                        <img src="admin/upload_images/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" loading="lazy">
                                    </a>
                                    <div class="pd-card-body">
                                        <a class="pd-card-title" href="productDetails.php?getData=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></a>
                                        <div class="pd-card-foot">
                                            <span class="pd-card-price">Rs. <?php echo $row['price']; ?></span>
                                            <a class="pd-card-add" href="cart.php?addProduct=<?php echo $row['id']; ?>&qty=1" title="Add to cart" aria-label="Add <?php echo htmlspecialchars($row['name']); ?> to cart"><i class="las la-cart-plus"></i></a>
                                        </div>
                                    </div>
                                </article>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>
        <!-- Product Details End -->

        <!-- Mobile: sticky add-to-cart bar -->
        <div class="pd-sticky" id="pdSticky" aria-hidden="true">
            <div class="pd-sticky-info">
                <span class="pd-sticky-name"><?php echo $name; ?></span>
                <span class="pd-sticky-price">Rs. <?php echo $productsData['price']; ?></span>
            </div>
            <button type="button" class="pd-btn" onclick="addCard(<?php echo $productsData['id']; ?>);" tabindex="-1" <?php echo $isAvailable ? '' : 'disabled'; ?>><i class="las la-shopping-cart"></i> Add</button>
        </div>

        <!-- Footer -->
        <?php include("footer.php"); ?>

        <script src="assets/js/vendor/vendor.min.js"></script>
        <script src="assets/js/plugins/plugins.min.js"></script>
        <script src="assets/js/main.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
        (function () {
            var price = <?php echo json_encode((float)$productsData['price']); ?>;
            var qtyInput = document.getElementById("qty");
            var totalText = document.getElementById("pdTotal");

            function updateTotal() {
                var qty = parseInt(qtyInput.value, 10);
                if (isNaN(qty) || qty < 1) qty = 1;
                if (qty > 50) qty = 50;
                totalText.textContent = (price * qty).toFixed(2);
            }

            // Quantity stepper
            document.querySelectorAll(".pd-qty [data-step]").forEach(function (button) {
                button.addEventListener("click", function () {
                    var qty = (parseInt(qtyInput.value, 10) || 1) + Number(button.dataset.step);
                    qtyInput.value = Math.min(50, Math.max(1, qty));
                    updateTotal();
                });
            });
            qtyInput.addEventListener("input", updateTotal);
            qtyInput.addEventListener("blur", function () {
                var qty = parseInt(qtyInput.value, 10);
                qtyInput.value = isNaN(qty) ? 1 : Math.min(50, Math.max(1, qty));
                updateTotal();
            });

            // Gallery thumbnails
            var mainImage = document.getElementById("pdMainImage");
            document.querySelectorAll(".pd-thumb").forEach(function (thumb) {
                thumb.addEventListener("click", function () {
                    var img = thumb.querySelector("img");
                    mainImage.src = img.src;   // already background-removed when image-knockout has run
                    mainImage.dataset.originalSrc = thumb.dataset.image;
                    document.querySelectorAll(".pd-thumb").forEach(function (t) { t.classList.toggle("active", t === thumb); });
                });
            });

            // Hover zoom on the main photo
            var main = document.getElementById("pdMain");
            if (window.matchMedia("(hover: hover)").matches) {
                main.addEventListener("mousemove", function (e) {
                    var r = main.getBoundingClientRect();
                    mainImage.style.transformOrigin = ((e.clientX - r.left) / r.width * 100) + "% " + ((e.clientY - r.top) / r.height * 100) + "%";
                    main.classList.add("zooming");
                });
                main.addEventListener("mouseleave", function () { main.classList.remove("zooming"); });
            }

            // Tabs
            document.querySelectorAll(".pd-tab").forEach(function (tab) {
                tab.addEventListener("click", function () {
                    document.querySelectorAll(".pd-tab").forEach(function (t) {
                        t.classList.toggle("active", t === tab);
                        t.setAttribute("aria-selected", t === tab ? "true" : "false");
                    });
                    document.querySelectorAll(".pd-tab-panel").forEach(function (p) { p.classList.toggle("active", p.id === tab.dataset.tab); });
                });
            });

            // Show the sticky bar on phones once the main Add to Cart button scrolls out of view
            var sticky = document.getElementById("pdSticky");
            if ("IntersectionObserver" in window) {
                new IntersectionObserver(function (entries) {
                    var hidden = !entries[0].isIntersecting && entries[0].boundingClientRect.top < 0;
                    sticky.classList.toggle("show", hidden);
                    sticky.setAttribute("aria-hidden", hidden ? "false" : "true");
                }).observe(document.getElementById("pdBuy"));
            }
        })();

        function addCard(id)
        {
            var qty = parseInt($("#qty").val(), 10);
            if(isNaN(qty) || qty <= 0)
            {
                swal("Quantity should be a number greater than 0.","","error");
            }
            else
            {
                window.location = "cart.php?addProduct="+id+"&qty="+qty;
            }
        }
        </script>
    </body>
</html>
