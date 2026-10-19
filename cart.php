<?php
session_start();
include('admin/db.php');
include('admin/functions.php');
if(!isset($_SESSION['userId']))
{
    header('location:admin/userLogin.php');
    exit;
}

$userId = (int)$_SESSION['userId'];

// Saved delivery addresses + details to pre-fill a new one
include_once('admin/address_lib.php');
addressEnsureTable($con);
$savedAddresses = addressList($con, $userId);
$accountRow = selectData("SELECT name, phone FROM `users` WHERE id='$userId'");
$prefillName = is_array($accountRow) && count($accountRow) ? $accountRow[0]['name'] : '';
$prefillPhone = is_array($accountRow) && count($accountRow) ? substr(preg_replace('/\D/', '', $accountRow[0]['phone']), -10) : '';

if(isset($_GET['addProduct']) && isset($_GET['qty']))
{
	$productId = $_GET['addProduct'];
    $qty = $_GET['qty'];

    $sqlSelect = selectData("SELECT * FROM cart WHERE user_id = '$userId' and product_id = '$productId' ");
        // print_r($sqlSelect);
        // exit;
    if(is_array($sqlSelect) && count($sqlSelect) >= 1)
    {
        $sqlSelect = $sqlSelect[0];

        $selectId = $sqlSelect['id'];
        $selectProductId = $sqlSelect['product_id']; 
        $qty = ($sqlSelect['qty'] + $qty);
        $sql = "UPDATE `cart` SET `qty` = '$qty' where id='$selectId' and product_id='$selectProductId' ";
        insert_update_delete_data($sql);
    }
    else
    {   
        $sql = "INSERT INTO `cart`(`user_id`, `product_id`, `qty`) 
                        VALUES ('$userId','$productId','$qty')";
        insert_update_delete_data($sql);
    }
    runJavascript('window.location.replace("cart.php");');
}
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

        <script src="admin/functions.js"></script>
            <link rel="stylesheet" href="assets/css/cart-address.css" />
    </head>

    <body>
    <!-- Header -->
    <?php include("header.php"); ?>


    

    <div class="offcanvas-overlay"></div>

    <!-- Content Here -->

    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcrumb-content">
                            <ul class="nav">
                                <li><a href="index.html">Home</a></li>
                                <li>Cart</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Breadcrumb Area End-->
    <!-- cart area start -->
    <div class="cart-main-area pb-100px">
        <div class="container">
            <h3 class="cart-page-title">Your cart items</h3>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <form method="POST">
                        <div class="table-content table-responsive cart-table-content">
                            <table>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Image</th>
                                        <th>Product Name</th>
                                        <th>Until Price</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $productsData =   selectData("
                                                                    SELECT 
                                                                    product.* ,
                                                                    cart.qty as 'cartQty',
                                                                    cart.id as 'cartId',
                                                                    cart.user_id as 'userId'
                                                                    FROM `cart` cart
                                                                    inner join products product on product.id = cart.product_id 
                                                                    where 
                                                                    cart.user_id = '$userId'
                                                                ");
                                    $finalTotal = 0;
                                    $shippingCharges = 50;
                                    $rowCount = 1;
                                    if(is_array($productsData) && count($productsData) >= 1)
                                    {
                                        foreach ($productsData as $row) 
                                        {
                                            $finalTotal = ($finalTotal + $row['price'] * $row['cartQty']);
                                            ?>
                                            <tr>
                                                <td><?php echo $rowCount; ?></td>
                                                <td class="product-thumbnail">
                                                    <img class="img-responsive" src="admin/upload_images/<?php echo $row['image']; ?>" alt="" />
                                                </td>
                                                <td class="product-name"><?php echo $row['name']; ?></td>
                                                <td class="product-price-cart">
                                                    <input type="hidden" name="productPrice[]" value="<?php echo $row['price']; ?>" />
                                                    <span class="amount">Rs. <?php echo $row['price']; ?></span>
                                                </td>
                                                <td class="product-qty">
                                                    <div class="">
                                                        <input type="hidden" name="productId[]" value="<?php echo $row['id']; ?>" />
                                                        <input type="hidden" name="cartId[]" value="<?php echo $row['cartId']; ?>" />
                                                        <input class="form-control" type="number" min="1" max="50" name="productQty[]" onchange="qtyChange(this)" data-productId="<?php echo $row['id']; ?>" data-productPrice="<?php echo $row['price']; ?>" data-cartId="<?php echo $row['cartId']; ?>" value="<?php echo $row['cartQty']; ?>" />
                                                    </div>
                                                </td>
                                                <td class="product-subtotal" id="priceText-<?php echo $row['cartId']; ?>">Rs. <?php echo ( $row['price'] * $row['cartQty'] ); ?></td>
                                                <td class="product-remove">
                                                    <!-- <a href="#"><i class="fa fa-pencil-alt"></i></a> -->
                                                    <button type="button" onclick="javascript: $(this).closest('tr').remove();">
                                                        <i class="fa fa-times"></i></a>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php
                                            $rowCount++;
                                        }
                                    }
                                    ?>
                                    <!-- <tr>
                                        <td class="product-thumbnail">
                                            <a  href="#"><img class="img-responsive" src="assets/images/product-image/2.jpg" alt="" /></a>
                                        </td>
                                        <td class="product-name"><a href="#">Product Name</a></td>
                                        <td class="product-price-cart"><span class="amount">$50.00</span></td>
                                        <td class="product-quantity">
                                            <div class="cart-plus-minus">
                                                <input class="cart-plus-minus-box" type="text" name="qtybutton" value="1" />
                                            </div>
                                        </td>
                                        <td class="product-subtotal">$80.00</td>
                                        <td class="product-remove">
                                            <a href="#"><i class="fa fa-pencil-alt"></i></a>
                                            <a href="#"><i class="fa fa-times"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="product-thumbnail">
                                            <a  href="#"><img class="img-responsive" src="assets/images/product-image/3.jpg" alt="" /></a>
                                        </td>
                                        <td class="product-name"><a href="#">Product Name</a></td>
                                        <td class="product-price-cart"><span class="amount">$70.00</span></td>
                                        <td class="product-quantity">
                                            <div class="cart-plus-minus">
                                                <input class="cart-plus-minus-box" type="text" name="qtybutton" value="1" />
                                            </div>
                                        </td>
                                        <td class="product-subtotal">$90.00</td>
                                        <td class="product-remove">
                                            <a href="#"><i class="fa fa-pencil-alt"></i></a>
                                            <a href="#"><i class="fa fa-times"></i></a>
                                        </td>
                                    </tr> -->
                                    <?php
                                        if(count($productsData) <= 0)
                                        {
                                            $shippingCharges = 0;
                                            ?>
                                                <tr>
                                                    <td colspan="7">
                                                        No Items in Cart !!!
                                                    </td>
                                                </tr>
                                            <?php
                                        }
                                        ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="cart-shiping-update-wrapper">
                                    <div class="cart-shiping-update">
                                        <a href="products.php">Continue Shopping</a>
                                    </div>
                                    <div class="cart-clear">
                                        <?php
                                        if(is_array($productsData) && count($productsData) >= 1)
                                        {
                                            ?>
                                                <button name="buttonClearCart">Clear Shopping Cart</button>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- <div class="row"> -->
                        <!-- <div class="col-lg-4 col-md-6 mb-lm-30px">
                            <div class="cart-tax">
                                <div class="title-wrap">
                                    <h4 class="cart-bottom-title section-bg-gray">Estimate Shipping And Tax</h4>
                                </div>
                                <div class="tax-wrapper">
                                    <p>Enter your destination to get a shipping estimate.</p>
                                    <div class="tax-select-wrapper">
                                        <div class="tax-select">
                                            <label>
                                                * Country
                                            </label>
                                            <select class="email s-email s-wid">
                                                <option>Bangladesh</option>
                                                <option>Albania</option>
                                                <option>Åland Islands</option>
                                                <option>Afghanistan</option>
                                                <option>Belgium</option>
                                            </select>
                                        </div>
                                        <div class="tax-select">
                                            <label>
                                                * Region / State
                                            </label>
                                            <select class="email s-email s-wid">
                                                <option>Bangladesh</option>
                                                <option>Albania</option>
                                                <option>Åland Islands</option>
                                                <option>Afghanistan</option>
                                                <option>Belgium</option>
                                            </select>
                                        </div>
                                        <div class="tax-select mb-25px">
                                            <label>
                                                * Zip/Postal Code
                                            </label>
                                            <input type="text" />
                                        </div>
                                        <button class="cart-btn-2" type="submit">Get A Quote</button>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <form id="checkoutForm" method="post">
                            <div class="row">
                                <div class="col-lg-7 col-md-12 mb-lm-30px">
                                    <div class="addr-box">
                                        <div class="addr-head">
                                            <span class="addr-step">1</span>
                                            <div>
                                                <h4>Delivery Address</h4>
                                                <small><?php echo count($savedAddresses) ? "Choose where we should deliver" : "Where should we deliver your order?"; ?></small>
                                            </div>
                                        </div>
                                        <input type="hidden" name="placeOrder" value="1">

                                        <?php if (count($savedAddresses)) { ?>
                                            <div class="addr-list" role="radiogroup" aria-label="Saved addresses">
                                                <?php foreach ($savedAddresses as $index => $saved) { ?>
                                                    <label class="addr-card">
                                                        <input type="radio" name="address_id" value="<?php echo (int)$saved['id']; ?>" <?php echo $index === 0 ? 'checked' : ''; ?>>
                                                        <span class="addr-radio"></span>
                                                        <span class="addr-body">
                                                            <span class="addr-top">
                                                                <b><?php echo htmlspecialchars($saved['full_name']); ?></b>
                                                                <span class="addr-type"><?php echo $saved['address_type'] === 'work' ? 'Work' : 'Home'; ?></span>
                                                                <span class="addr-phone"><?php echo htmlspecialchars($saved['phone']); ?></span>
                                                            </span>
                                                            <span class="addr-text">
                                                                <?php echo htmlspecialchars($saved['address_line'] . ", " . $saved['locality'] . ", " . $saved['city'] . ", " . $saved['state']); ?> - <b><?php echo htmlspecialchars($saved['pincode']); ?></b>
                                                                <?php if ($saved['landmark']) { ?><br><span class="addr-muted">Landmark: <?php echo htmlspecialchars($saved['landmark']); ?></span><?php } ?>
                                                            </span>
                                                        </span>
                                                        <button type="button" class="addr-remove" onclick="removeAddress(event, <?php echo (int)$saved['id']; ?>)" title="Remove this address" aria-label="Remove this address"><i class="las la-trash-alt"></i></button>
                                                    </label>
                                                <?php } ?>
                                                <label class="addr-card addr-new-choice">
                                                    <input type="radio" name="address_id" value="new" id="addrNewRadio">
                                                    <span class="addr-radio"></span>
                                                    <span class="addr-body"><span class="addr-add-label"><i class="las la-plus"></i> Add a new address</span></span>
                                                </label>
                                            </div>
                                        <?php } else { ?>
                                            <input type="hidden" name="address_id" value="new">
                                        <?php } ?>

                                        <div class="addr-form" id="addrForm" <?php echo count($savedAddresses) ? 'hidden' : ''; ?>>
                                            <div class="addr-grid">
                                                <div class="addr-field">
                                                    <label for="addrName">Full name <span class="req">*</span></label>
                                                    <input class="form-control" id="addrName" name="addrName" maxlength="100" autocomplete="name" value="<?php echo htmlspecialchars($prefillName); ?>">
                                                </div>
                                                <div class="addr-field">
                                                    <label for="addrPhone">10-digit mobile number <span class="req">*</span></label>
                                                    <input class="form-control" id="addrPhone" name="addrPhone" inputmode="numeric" maxlength="10" autocomplete="tel-national" value="<?php echo htmlspecialchars($prefillPhone); ?>">
                                                </div>
                                                <div class="addr-field">
                                                    <label for="addrPincode">PIN code <span class="req">*</span></label>
                                                    <input class="form-control" id="addrPincode" name="addrPincode" inputmode="numeric" maxlength="6" autocomplete="postal-code">
                                                    <small class="addr-hint" id="pinHint"></small>
                                                </div>
                                                <div class="addr-field">
                                                    <label for="addrLocality">Locality <span class="req">*</span></label>
                                                    <input class="form-control" id="addrLocality" name="addrLocality" maxlength="150" placeholder="e.g. Alkapuri" list="localityOptions">
                                                    <datalist id="localityOptions"></datalist>
                                                </div>
                                                <div class="addr-span-2 pin-card" id="pinCard" hidden aria-live="polite"></div>
                                                <div class="addr-field addr-span-2">
                                                    <label for="addrLine">Address (house no., building, street, area) <span class="req">*</span></label>
                                                    <textarea class="form-control" id="addrLine" name="addrLine" rows="2" maxlength="255" autocomplete="street-address"></textarea>
                                                </div>
                                                <div class="addr-field">
                                                    <label for="addrCity">City / District / Town <span class="req">*</span></label>
                                                    <input class="form-control" id="addrCity" name="addrCity" maxlength="100" autocomplete="address-level2">
                                                </div>
                                                <div class="addr-field">
                                                    <label for="addrState">State <span class="req">*</span></label>
                                                    <select class="form-control" id="addrState" name="addrState" autocomplete="address-level1">
                                                        <option value="">Select state</option>
                                                        <?php foreach (addressStates() as $state) { ?>
                                                            <option><?php echo $state; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="addr-field">
                                                    <label for="addrLandmark">Landmark <span class="addr-optional">(optional)</span></label>
                                                    <input class="form-control" id="addrLandmark" name="addrLandmark" maxlength="150" placeholder="e.g. Near City Mall">
                                                </div>
                                                <div class="addr-field">
                                                    <label for="addrAltPhone">Alternate phone <span class="addr-optional">(optional)</span></label>
                                                    <input class="form-control" id="addrAltPhone" name="addrAltPhone" inputmode="numeric" maxlength="10">
                                                </div>
                                                <div class="addr-field addr-span-2">
                                                    <span class="addr-label">Address type</span>
                                                    <div class="addr-types">
                                                        <label><input type="radio" name="addrType" value="home" checked> <i class="las la-home"></i> Home <small>(all day delivery)</small></label>
                                                        <label><input type="radio" name="addrType" value="work"> <i class="las la-briefcase"></i> Work <small>(10 AM - 5 PM)</small></label>
                                                    </div>
                                                </div>
                                                <div class="addr-field addr-span-2">
                                                    <label class="addr-save"><input type="checkbox" name="addrSave" value="1" checked> Save this address for next time</label>
                                                </div>
                                            </div>
                                            <div class="addr-error" id="addrError" role="alert" hidden></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-12 mt-md-30px">
                                    <div class="grand-totall">
                                        <div class="title-wrap">
                                            <h4 class="cart-bottom-title section-bg-gary-cart">Cart Total</h4>
                                        </div>
                                        <h5>Total Products 
                                            <span>
                                                Rs. <?php echo $finalTotal; ?>
                                            </span>
                                        </h5>
                                        <div class="total-shipping">
                                            <h5>Total Shipping</h5>
                                            <ul>
                                                <li>Shipping Charges 
                                                    <span>
                                                        Rs. <?php echo $shippingCharges; ?>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                        <h4 class="grand-totall-title">Grand Total 
                                            <span>
                                                Rs. <?php echo ($finalTotal + $shippingCharges); ?>
                                            </span>
                                        </h4>
                                        <?php
                                        if(count($productsData) >= 1)
                                        {
                                            ?>
                                            <input class="form-check-input" type="radio" name="paymentMode" id="paymentOffline" value="0">
                                            <label class="form-check-label" for="paymentOffline">
                                                Offline
                                            </label>
                                            <br>

                                            <input class="form-check-input" type="radio" name="paymentMode" id="paymentOnline" value="1" checked>
                                            <label class="form-check-label" for="paymentOnline">
                                                Online
                                            </label>

                                            <div class="demo-pay-note">Demo project: please don't make any real payment.</div>
                                            <br>
                                               <a href="javascript:void(0);" onclick="submitform()" id="buttonCheckout">Proceed to Checkout</a>
                                            <?php
                                        }
                                        ?> 
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form id="removeAddressForm" method="post" hidden><input type="hidden" name="removeAddress" id="removeAddressId"></form>
                        <?php if (is_array($productsData) && count($productsData) >= 1) { ?>
                            <!-- Phones/tablets: total + Place order always in reach (like Flipkart) -->
                            <div class="m-checkout-bar d-lg-none">
                                <a href="#" class="m-checkout-total" onclick="document.querySelector('.grand-totall').scrollIntoView({behavior: 'smooth', block: 'center'}); return false;">
                                    <b>Rs. <?php echo number_format($finalTotal + $shippingCharges, 2); ?></b>
                                    <small>View price details</small>
                                </a>
                                <button type="button" class="m-checkout-btn" onclick="submitform()">Place order</button>
                            </div>
                        <?php } ?>
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </div>
    <!-- cart area end -->
        <!-- Subscribe Area Start -->
        <div class="subscribe-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="subscribe-wrapper p-0">
                            <div class="subscribe-inner">
                                <div class="subscribe-title">
                                    <h2>Subscribe to the exclusive deals!</h2>
                                    <p>You may unsubscribe at any moment. For that purpose, please find our contact info in the legal notice.</p>
                                </div>
                                    <!-- News letter area -->
                                <div id="mc_embed_signup" class="subscribe-form">
                                    <form
                                        id="mc-embedded-subscribe-form"
                                        class="validate"
                                        novalidate=""
                                        target="_blank"
                                        name="mc-embedded-subscribe-form"
                                        method="post"
                                        action="http://devitems.us11.list-manage.com/subscribe/post?u=6bbb9b6f5827bd842d9640c82&amp;id=05d85f18ef"
                                    >
                                        <div id="mc_embed_signup_scroll" class="mc-form">
                                            <input class="email" type="email" required="" placeholder="Enter your email here.." name="EMAIL" value="" />
                                            <div class="mc-news" aria-hidden="true">
                                                <input type="text" value="" tabindex="-1" name="b_6bbb9b6f5827bd842d9640c82_05d85f18ef" />
                                            </div>
                                            <div class="clear">
                                                <button id="mc-embedded-subscribe" class="button" type="submit" name="subscribe" value="Subscribe">Subscribe</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- News letter area  End -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
        <!-- Blog Area End -->
            <!-- Brand area start -->
            <div class="brand-area pb-100px">
                <div class="container">
                    <div class="brand-slider slider-nav-style-1">
                        <div class="brand-slider-item">
                            <a href="#"><img class=" img-responsive" src="assets/images/brand-logo/1.jpg" alt="" /></a>
                        </div>
                        <div class="brand-slider-item">
                            <a href="#"><img class=" img-responsive" src="assets/images/brand-logo/2.jpg" alt="" /></a>
                        </div>
                        <div class="brand-slider-item">
                            <a href="#"><img class=" img-responsive" src="assets/images/brand-logo/3.jpg" alt="" /></a>
                        </div>
                        <div class="brand-slider-item">
                            <a href="#"><img class=" img-responsive" src="assets/images/brand-logo/1.jpg" alt="" /></a>
                        </div>
                        <div class="brand-slider-item">
                            <a href="#"><img class=" img-responsive" src="assets/images/brand-logo/4.jpg" alt="" /></a>
                        </div>
                        <div class="brand-slider-item">
                            <a href="#"><img class=" img-responsive" src="assets/images/brand-logo/5.jpg" alt="" /></a>
                        </div>
                        <div class="brand-slider-item">
                            <a href="#"><img class=" img-responsive" src="assets/images/brand-logo/6.jpg" alt="" /></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Brand area end -->
        
        
        <!-- Footer -->
        <?php include("footer.php"); ?>

      
        <!-- Modal end -->
        <!-- JS
============================================ -->

            <!-- Vendors JS -->
        <!-- <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
        <script src="assets/js/vendor/bootstrap.bundle.min.js"></script>
        <script src="assets/js/vendor/jquery-migrate-3.3.2.min.js"></script>
        <script src="assets/js/vendor/modernizr-3.11.2.min.js"></script> -->

            <!-- Plugins JS -->
         <!-- <script src="assets/js/plugins/jquery-ui.min.js"></script>
        <script src="assets/js/plugins/slick.js"></script>
        <script src="assets/js/plugins/countdown.js"></script>
        <script src="assets/js/plugins/scrollup.js"></script>
        <script src="assets/js/plugins/ripples.js"></script>
        <script src="assets/js/plugins/elevateZoom.js"></script></script> -->

        <!-- Use the minified version files listed below for better performance and remove the files listed above -->
        <script src="assets/js/vendor/vendor.min.js"></script>
        <script src="assets/js/plugins/plugins.min.js"></script>

        <!-- Main Activation JS -->
        <script src="assets/js/main.js"></script>

        <!-- SweetAlert -->
		 <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

        <script>
        if (document.getElementById("qty")) { addDecimalValidationEvent2("qty"); }  // only on pages with a single qty box

        function qtyChange(textBox)
        {
            var qty = textBox.value;
            var productId = textBox.getAttribute("data-productId");
            var productPrice = textBox.getAttribute("data-productPrice");
            var cartId = textBox.getAttribute("data-cartId");

            var totalText = document.getElementById("priceText-"+cartId);

            var total = (productPrice * qty);
            totalText.innerHTML = "Rs. "+total;

            // console.log(totalText.innerHTML);
            // console.log("qty = "+ qty);
            // console.log("ProductId = "+ productId);
            // console.log("productPrice = "+ productPrice);
            // console.log("cartId = "+ cartId);
        }

        // ---------- Delivery address ----------
        var addrForm = document.getElementById("addrForm");
        var addrError = document.getElementById("addrError");

        function usingNewAddress() {
            var chosen = document.querySelector('input[name="address_id"]:checked') || document.querySelector('input[name="address_id"]');
            return !chosen || chosen.value === "new";
        }

        // Show the form only when "Add a new address" is chosen
        document.querySelectorAll('input[name="address_id"]').forEach(function (radio) {
            radio.addEventListener("change", function () {
                if (!addrForm) return;
                addrForm.hidden = !usingNewAddress();
                if (!addrForm.hidden) document.getElementById("addrName").focus();
            });
        });

        // Digits only in phone / PIN boxes
        ["addrPhone", "addrAltPhone", "addrPincode"].forEach(function (id) {
            var input = document.getElementById(id);
            if (input) input.addEventListener("input", function () { this.value = this.value.replace(/\D/g, ""); });
        });

        // PIN code details (like Amazon): city, state, areas and estimated delivery appear as soon as a valid PIN is typed.
        // The lookup runs on our server (admin/pincode_lookup.php); typing city/state by hand still works if it fails.
        var pinInput = document.getElementById("addrPincode");
        var pinCard = document.getElementById("pinCard");
        var pinTimer = null;
        var localityFromChip = false;   // true when the locality was picked from the area chips

        function escapeHtml(text) {
            return String(text).replace(/[&<>"']/g, function (c) { return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c]; });
        }

        function chooseArea(name) {
            document.getElementById("addrLocality").value = name;
            localityFromChip = true;
            document.getElementById("addrLocality").classList.remove("is-invalid");
            pinCard.querySelectorAll(".pin-area").forEach(function (b) { b.classList.toggle("active", b.dataset.area === name); });
        }

        function showPinDetails(d) {
            document.getElementById("addrCity").value = d.city;
            var stateSelect = document.getElementById("addrState");
            for (var i = 0; i < stateSelect.options.length; i++) {
                if (stateSelect.options[i].value.toLowerCase() === String(d.state).toLowerCase()) stateSelect.selectedIndex = i;
            }
            ["addrCity", "addrState"].forEach(function (id) { document.getElementById(id).classList.remove("is-invalid"); });

            var list = document.getElementById("localityOptions");
            list.innerHTML = "";
            d.areas.forEach(function (a) { var opt = document.createElement("option"); opt.value = a; list.appendChild(opt); });

            // An area picked for a different PIN code no longer applies
            var locality = document.getElementById("addrLocality").value;
            if (localityFromChip && d.areas.indexOf(locality) === -1) {
                document.getElementById("addrLocality").value = locality = "";
                localityFromChip = false;
            }
            pinCard.innerHTML =
                '<div class="pin-top">' +
                    '<span class="pin-icon"><i class="las la-map-marker"></i></span>' +
                    '<div class="pin-place"><b>' + escapeHtml(d.city) + ', ' + escapeHtml(d.state) + ' ' + escapeHtml(d.pincode) + '</b>' +
                    '<small>' + escapeHtml(d.division ? d.division + ' · ' : '') + d.areas.length + ' area' + (d.areas.length === 1 ? '' : 's') + ' in this PIN code</small></div>' +
                    '<span class="pin-ok"><i class="las la-check-circle"></i> We deliver here</span>' +
                '</div>' +
                '<div class="pin-delivery"><i class="las la-shipping-fast"></i><div><b>' + escapeHtml(d.delivery.text) + '</b>' +
                    '<small>' + escapeHtml(d.delivery.label) + ' · usually ' + d.delivery.min + '-' + d.delivery.max + ' days · Rs. 50 delivery charge</small></div></div>' +
                '<div class="pin-areas-label">Select your area</div>' +
                '<div class="pin-areas">' + d.areas.map(function (a) {
                    return '<button type="button" class="pin-area' + (a === locality ? ' active' : '') + '" data-area="' + escapeHtml(a) + '">' + escapeHtml(a) + '</button>';
                }).join("") + '</div>';
            pinCard.querySelectorAll(".pin-area").forEach(function (b) {
                b.addEventListener("click", function () { chooseArea(b.dataset.area); });
            });
            if (!locality && d.areas.length === 1) chooseArea(d.areas[0]);
            pinCard.className = "addr-span-2 pin-card";
            pinCard.hidden = false;
        }

        function showPinMessage(text, type) {
            pinCard.className = "addr-span-2 pin-card pin-" + type;
            pinCard.innerHTML = '<div class="pin-msg">' + (type === "loading" ? '<span class="pin-spinner"></span>' : '<i class="las la-' + (type === "error" ? "exclamation-circle" : "info-circle") + '"></i>') + '<span>' + escapeHtml(text) + '</span></div>';
            pinCard.hidden = false;
        }

        function lookUpPin(pin) {
            showPinMessage("Checking PIN code " + pin + "...", "loading");
            fetch("admin/pincode_lookup.php?pin=" + encodeURIComponent(pin), { credentials: "same-origin" })
                .then(function (r) { return r.json(); })
                .then(function (d) {
                    if (pinInput.value !== pin) return;   // the customer kept typing
                    if (d.ok) showPinDetails(d);
                    else showPinMessage(d.message, d.unavailable ? "info" : "error");
                })
                .catch(function () {
                    if (pinInput.value === pin) showPinMessage("We couldn't check this PIN code right now. You can still fill in the city and state.", "info");
                });
        }

        if (pinInput) {
            pinInput.addEventListener("input", function () {
                var pin = this.value;
                clearTimeout(pinTimer);
                document.getElementById("pinHint").textContent = "";
                if (pin.length < 6) { pinCard.hidden = true; return; }
                if (!/^[1-9]\d{5}$/.test(pin)) { showPinMessage("PIN codes have 6 digits and don't start with 0.", "error"); return; }
                pinTimer = setTimeout(function () { lookUpPin(pin); }, 250);
            });
            // Keep the area chips in sync when the locality is typed
            document.getElementById("addrLocality").addEventListener("input", function () {
                var value = this.value;
                localityFromChip = false;
                pinCard.querySelectorAll(".pin-area").forEach(function (b) { b.classList.toggle("active", b.dataset.area === value); });
            });
        }

        function checkAddress() {
            if (!usingNewAddress()) return [];
            var v = function (id) { return $.trim(document.getElementById(id).value); };
            var errors = [];
            if (v("addrName").length < 2) errors.push(["addrName", "Please enter the full name."]);
            if (!/^[6-9]\d{9}$/.test(v("addrPhone"))) errors.push(["addrPhone", "Please enter a valid 10-digit mobile number."]);
            if (!/^[1-9]\d{5}$/.test(v("addrPincode"))) errors.push(["addrPincode", "Please enter a valid 6-digit PIN code."]);
            if (v("addrLocality").length < 2) errors.push(["addrLocality", "Please enter the locality."]);
            if (v("addrLine").length < 5) errors.push(["addrLine", "Please enter the house number, building and street."]);
            if (v("addrCity").length < 2) errors.push(["addrCity", "Please enter the city or district."]);
            if (v("addrState") === "") errors.push(["addrState", "Please choose a state."]);
            if (v("addrAltPhone") !== "" && !/^[6-9]\d{9}$/.test(v("addrAltPhone"))) errors.push(["addrAltPhone", "The alternate phone number isn't valid."]);
            return errors;
        }

        function submitform()
        {
            document.querySelectorAll(".addr-field .is-invalid").forEach(function (el) { el.classList.remove("is-invalid"); });
            var errors = checkAddress();
            if (errors.length) {
                errors.forEach(function (e) { document.getElementById(e[0]).classList.add("is-invalid"); });
                addrError.innerHTML = errors.map(function (e) { return "<div>" + e[1] + "</div>"; }).join("");
                addrError.hidden = false;
                document.getElementById(errors[0][0]).focus();
                return;
            }
            if (addrError) addrError.hidden = true;
            document.getElementById('checkoutForm').submit();
        }

        function removeAddress(event, id)
        {
            event.preventDefault();
            event.stopPropagation();
            if (confirm("Remove this saved address?")) {
                document.getElementById("removeAddressId").value = id;
                document.getElementById("removeAddressForm").submit();
            }
        }

        function addCard(id)
        {
            var qty = $("#qty").val();
            if(isNaN(qty) || qty <= 0)
            {
                swal("Qty Should be Number and grater than 1.","","error");
            }
            else
            {
                var url = "cart.php?addProduct="+id+"&qty="+qty;
                window.location=url;
            }
        }
        </script>

    </body>

</html>

<?php

if(isset($_POST['buttonUpateCart']))
{
    if(isset($_POST['productPrice']) && isset($_POST['productId']) && isset($_POST['cartId']) && isset($_POST['productQty']))
    {
        $userId = (int)$_SESSION['userId'];
    
        $productPrice = $_POST['productPrice'];
        $productId = $_POST['productId'];
        $cartId = $_POST['cartId'];
        $productQty = $_POST['productQty'];
        

        $sqlDelete = "DELETE FROM `cart` where user_id='$userId'";
        insert_update_delete_data($sqlDelete);

        $count = 0;
        foreach ($productId as $row) 
        {
            $productIdRow = $row;
            $qtyRow = $productQty[$count];
            $cartIdRow = $cartId[$count];

            $sql = "INSERT INTO `cart`(`user_id`, `product_id`, `qty`) 
                            VALUES ('$userId','$productIdRow','$qtyRow')";
            insert_update_delete_data($sql);
            $count++;
        }
        $msg = getSwalMessgage("Cart updated Successfully.","",'window.location = "cart.php"','window.location = "cart.php"',"success");
        runJavascript($msg);
    }
    else
    {
        $sqlDelete = "DELETE FROM `cart` where user_id='$userId'";
        insert_update_delete_data($sqlDelete);
        runJavascript("window.location='cart.php';");
    }
}
else if(isset($_POST['buttonClearCart']))
{
    $sqlDelete = "DELETE FROM `cart` where user_id='$userId'";
    insert_update_delete_data($sqlDelete);
    runJavascript("window.location='cart.php';");
}
else if(isset($_POST['removeAddress']))
{
    addressDelete($con, $userId, $_POST['removeAddress']);
    runJavascript("window.location='cart.php';");
}
else if(isset($_POST['placeOrder']))
{
    // Place the order: everything is read from the database, nothing from hidden form fields
    include_once('admin/orders_lib.php');
    orderEnsureTables($con);
    $customerId = (int)$_SESSION['userId'];
    $paymentMode = (isset($_POST['paymentMode']) && $_POST['paymentMode'] == '1') ? 1 : 0; // 0 = Offline, 1 = Online
    $shippingCharges = 50;

    // Delivery address: one of the customer's saved addresses, or a new one from the form
    $address = "";
    $addressError = "";
    $chosenAddress = isset($_POST['address_id']) ? $_POST['address_id'] : 'new';
    if($chosenAddress !== 'new')
    {
        $savedAddress = addressFind($con, $customerId, $chosenAddress);
        if($savedAddress)
        {
            $address = addressFormat($savedAddress);
            addressTouch($con, $savedAddress['id']);
        }
        else
        {
            $addressError = "Please choose a delivery address.";
        }
    }
    else
    {
        list($newAddress, $addressErrors) = addressFromPost($_POST);
        if(count($addressErrors))
        {
            $addressError = implode("\\n", $addressErrors);
        }
        else
        {
            $address = addressFormat($newAddress);
            if(isset($_POST['addrSave']))
            {
                addressSave($con, $customerId, $newAddress);
            }
        }
    }

    $stmt = mysqli_prepare($con, "SELECT p.id, p.name, p.image, p.price, c.qty FROM `cart` c
                                  INNER JOIN `products` p ON p.id = c.product_id
                                  WHERE c.user_id = ? AND c.qty > 0");
    mysqli_stmt_bind_param($stmt, "i", $customerId);
    mysqli_stmt_execute($stmt);
    $cartItems = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    if($address == "")
    {
        runJavascript('swal("Please check the delivery address", ' . json_encode($addressError != "" ? str_replace("\\n", "\n", $addressError) : "Please enter your delivery address.") . ', "warning");');
    }
    else if(count($cartItems) == 0)
    {
        runJavascript('swal("Your cart is empty.", "", "warning").then(function(){ window.location = "cart.php"; });');
    }
    else
    {
        $subtotal = 0;
        foreach ($cartItems as $item)
        {
            $subtotal += $item['price'] * $item['qty'];
        }
        $grandTotal = $subtotal + $shippingCharges;
        $dt = date("Y-m-d");

        mysqli_begin_transaction($con);
        try
        {
            $stmt = mysqli_prepare($con, "INSERT INTO `sales`(`user_id`, `payment_mode`, `payment_status`, `delivery_status`, `delivery_address`, `delivery_changes`, `tax`, `total`, `status`, `dt`)
                                          VALUES (?, ?, 0, 0, ?, ?, 0, ?, 0, ?)");
            mysqli_stmt_bind_param($stmt, "iisdds", $customerId, $paymentMode, $address, $shippingCharges, $grandTotal, $dt);
            mysqli_stmt_execute($stmt);
            $saledId = mysqli_insert_id($con);
            mysqli_stmt_close($stmt);

            $stmt = mysqli_prepare($con, "INSERT INTO `sales_items`(`sales_id`, `product_id`, `product_name`, `product_image`, `qty`, `price`, `total`) VALUES (?, ?, ?, ?, ?, ?, ?)");
            foreach ($cartItems as $item)
            {
                $qty = (int)$item['qty'];
                $price = (float)$item['price'];
                $lineTotal = $price * $qty;
                $productId = (int)$item['id'];
                mysqli_stmt_bind_param($stmt, "iissidd", $saledId, $productId, $item['name'], $item['image'], $qty, $price, $lineTotal);
                mysqli_stmt_execute($stmt);
            }
            mysqli_stmt_close($stmt);

            $stmt = mysqli_prepare($con, "DELETE FROM `cart` WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $customerId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            orderLogStatus($con, $saledId, ORDER_PENDING, 'customer', $paymentMode == 1 ? "Paying online" : "Paying cash / offline");
            mysqli_commit($con);
        }
        catch (Exception $e)
        {
            mysqli_rollback($con);
            error_log("Placing order failed: " . $e->getMessage());
            runJavascript('swal("Sorry, we couldn\'t place your order. Please try again.", "", "error");');
            $saledId = 0;
        }

        if($saledId)
        {
            if($paymentMode == 1) // Online
            {
                runJavascript("window.location = 'payment/payment.php?txtId=$saledId';");
            }
            else // Offline
            {
                orderFlash("Thank you! Your order " . orderNumber($saledId) . " has been placed. We'll confirm it soon.", "success");
                runJavascript("window.location = 'admin/userOrderDetails.php?id=$saledId';");
            }
        }
    }
}

?>
