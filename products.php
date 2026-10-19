<?php
session_start();
include('admin/db.php');
include('admin/functions.php');
// if(isset($_SESSION['userId']))
// {
//     header('location:userDashboard.php');
// }
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
    </head>

    <body>
    <!-- Header -->
    <?php include("header.php"); ?>


    

    <div class="offcanvas-overlay"></div>

    <!-- Content Here -->
    <!-- Breadcrumb banner Area Start -->
    <div class="name-category">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="category-banner d-flex " data-bg-image="assets/images/banner-image/bg_breadcrumb.jpg">
                            <div class="align-self-center w-100">
                                <div class="text-center">
                                    <h2 class="color-white ">Pet Food</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Breadcrumb banner Area End -->
  <!-- Breadcrumb Area Start -->
        <div class="breadcrumb-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcrumb-content">
                            <ul class="nav">
                                <li><a href="index.html">Home</a></li>
                                <li>Shop</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Breadcrumb Area End-->
    
    <!-- Shop Category Area End -->
    <div class="shop-category-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 order-lg-last col-md-12 order-md-first">
                    <!-- Categories as scrollable chips on phones/tablets (the sidebar is hidden there) -->
                    <div class="m-cat-chips d-lg-none" role="navigation" aria-label="Categories">
                        <a href="products.php" class="<?php echo isset($_GET['categoryId']) ? '' : 'active'; ?>">All</a>
                        <?php
                        $chipCategories = selectData("SELECT * FROM `category` order by id desc");
                        if(is_array($chipCategories))
                        {
                            foreach ($chipCategories as $chip)
                            {
                                ?>
                                <a href="products.php?categoryId=<?php echo $chip['id']; ?>" class="<?php echo (isset($_GET['categoryId']) && $_GET['categoryId'] == $chip['id']) ? 'active' : ''; ?>">
                                    <img src="admin/upload_images/<?php echo $chip['image']; ?>" alt=""><?php echo htmlspecialchars($chip['name']); ?>
                                </a>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <!-- Shop Top Area Start -->
                    <div class="shop-top-bar">
                        <div class="row align-items-center">
                        <!-- <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <nav class="shop-grid-nav">
                                <ul class="nav nav-pills align-items-center" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#shop-1" role="tab" aria-selected="true">
                                            <i class="las la-table"></i>
                                        </a>
                                    </li>
                                    <li class="nav-item mr-0">
                                        <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#shop-2" role="tab" aria-selected="false"><i class="las la-list"></i></a>
                                    </li>
                                    <li> <span class="total-products text-capitalize">There are 13 products.</span></li>
                                </ul>
                            </nav>
                        </div> -->
                        <div class="col-8 col-md-8 position-relative">
                                    <input type="text" id="search" class="form-control form-rounded" placeholder="Search Product" value="<?php if(isset($_GET['searchId'])){ echo $_GET['searchId']; } ?>">
                        </div>
                        <div class="col-4 col-md-4 position-relative">
                            <button type="button" class="btn btn-primary" onclick="search();">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                        <!-- Right Side End -->
                    </div>
                    <!-- Shop Top Area End -->

                    <!-- Shop Bottom Area Start -->
                    <div class="shop-bottom-area mt-35">
                        <!-- Shop Tab Content Start -->
                        <div class="tab-content jump">
                            <!-- Tab One Start -->
                            <div id="shop-1" class="tab-pane active">
                                <div class="row">
                                    <?php
                                    $sql = "SELECT * FROM `products` where status='1' ";
                                    if(isset($_GET['categoryId']))
                                    {
                                        $categoryId = $_GET['categoryId'];
                                        $sql .= " and category_id='$categoryId' ";
                                    }
                                    if(isset($_GET['searchId']))
                                    {
                                        $searchId = $_GET['searchId'];
                                        $sql .= " and name like'%".$searchId."%' ";
                                    }
                                    $sql .= " order by id desc";
                                        $products = selectData($sql);
                                        if(is_array($products) && count($products) >= 1)
                                        {
                                            foreach ($products as $productsRow) 
                                            {
                                                ?>
                                    <div class="mb-60px col-md-4 col-sm-6">
                                        <div class="slider-single-item">
                                            <!-- Single Item -->
                                                <article class="list-product text-left">
                                                    <div class="product-inner">
                                                        <div class="img-block">
                                                            <a href="productDetails.php?getData=<?php echo $productsRow['id']; ?>" class="thumbnail">
                                                                <img class="first-img" src="admin/upload_images/<?php echo $productsRow['image'] ?>" alt="" />
                                                            </a>
                                                            <!-- <div class="add-to-link">
                                                                <ul>
                                                                    <li>
                                                                        <a href="wishlist.html" title="Add to Wishlist"><i class="las la-heart"></i></a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="compare.html" title="Add to compare"><i class="las la-sync"></i></a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                                            <i class="las la-search"></i>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div> -->
                                                            <!-- <ul class="product-flag">
                                                                <li class="new">New</li>
                                                            </ul> -->
                                                        </div>
                                                        <div class="product-decs">
                                                            <h2><a href="productDetails.php?getData=<?php echo $productsRow['id']; ?>" class="product-link"><?php echo $productsRow['name']; ?></a></h2>
                                                            <div class="pricing-meta">
                                                                <ul>
                                                                    <li class="current-price"><?php echo $productsRow['price']; ?></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="cart-btn">
                                                            <a href="cart.php?addProduct=<?php echo $productsRow['id']; ?>&qty=1" class="add-to-curt" title="Add to cart"><i class="las la-baby-carriage"></i></a>
                                                        </div>
                                                    </div>
                                                </article>
                                            <!-- Single Item -->
                                        </div>
                                    </div>
                                                <?php
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                            <!-- Tab One End -->
                        </div>
                        <!-- Shop Tab Content End -->
                        <!--  Pagination Area Start -->
                        <!-- <div class="pro-pagination-style">
                            <div class="pages">
                                <ul>
                                    <li><a href="#"><i class="ion-ios-arrow-left"></i></a></li>
                                    <li><a class="active" href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#"><i class="ion-ios-arrow-right"></i></a></li>
                                </ul>
                            </div>
                        </div> -->
                        <!--  Pagination Area End -->
                    </div>
                    <!-- Shop Bottom Area End -->
                </div>
                <!-- Sidebar Area Start -->
                <div class="col-lg-3 order-lg-first col-md-12 order-md-last mb-md-60px mb-lm-60px">
                    <div class="shop-sidebar-wrap">
                            <div class="sidebar-widget mb-30px bg-white d-lg-block d-none header-menu">
                                <div class="header-menu-vertical">
                                    <h4 class="menu-title">All Categories  </h4>
                                    <ul class="menu-content position-relative">
                                        <li class="menu-item">
                                            <a href="products.php">
                                                <i class="las la-paw"></i>All
                                            </a>
                                        </li>
                                        <?php
                                        $category = selectData("SELECT * FROM `category` order by id desc");
                                        if(is_array($category) && count($category) >= 1)
                                        {
                                            foreach ($category as $row) 
                                            {
                                                ?>
                                                <li class="menu-item">
                                                    <a href="products.php?categoryId=<?php echo $row['id']; ?>">
                                                        <i class="las la-paw"></i> <?php echo $row['name']; ?>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                    <!-- menu content -->
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Category Area End -->

        <!-- Subscribe Area Start -->
        <!-- <div class="subscribe-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="subscribe-wrapper p-0">
                            <div class="subscribe-inner">
                                <div class="subscribe-title">
                                    <h2>Subscribe to the exclusive deals!</h2>
                                    <p>You may unsubscribe at any moment. For that purpose, please find our contact info in the legal notice.</p>
                                </div>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>   -->
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

        <!-- Footer Area Start --> 
        <!-- <div class="footer-area">
            <div class="footer-container">
                <div class="footer-top">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 col-lg-3 mb-md-30px mb-lm-30px">
                                <div class="single-wedge">
                                    <h4 class="footer-herading">About Store</h4>
                                    <div class="need_help">
                                    <p class="add">8901 Marmora Road, Glasgow D04 89 GR, New York</p>
                                    <p class="phone"><a href="tel:1-800-345-6789"> Telephone: 866-540-3229</a> <br><a href="tel:1-800-345-6789"> Fax: (+1) 866-540-3229</a></p>
                                    <p class="mail"><a href="mailto:demo@hashemes.com">Email: demo@hasthemes.com</a></p>
                                    </div>
                                    <div class="social_follow"> 
                                        <ul>
                                              <li class="facebook" title="facebook"><a href="#" target="_blank">Facebook</a></li>
                                              <li class="twitter" title="twitter"><a href="#" target="_blank">Twitter</a></li>
                                              <li class="youtube" title="youtube"><a href="#" target="_blank">YouTube</a></li>
                                        </ul>
                                  </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-3 mb-md-30px mb-lm-30px">
                                <div class="single-wedge">
                                    <h4 class="footer-herading">Information</h4>
                                    <div class="footer-links">
                                        <ul>
                                            <li><a href="#">Delivery</a></li>
                                            <li><a href="about.html">About Us</a></li>
                                            <li><a href="#">Secure payment</a></li>
                                            <li><a href="contact.html">Contact Us</a></li>
                                            <li><a href="#">Sitemap</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6  col-sm-6 col-lg-3">
                                <div class="single-wedge">
                                    <h4 class="footer-herading">Custom Links</h4>
                                    <div class="footer-links">
                                        <ul>
                                            <li><a href="#">Legal Notice</a></li>
                                            <li><a href="#">Prices drop</a></li>
                                            <li><a href="#">New products</a></li>
                                            <li><a href="#">Best sales</a></li>
                                            <li><a href="#">Login</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="get-upto">
                                    <a href="#"><img class="img-responsive m-auto" src="assets/images/banner-image/get_upto.png" alt="banner img"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 mb-lm-20px text-lm-center">
                        <p class="copy-text">Copyright © <a href="https://hasthemes.com/"> HasThemes</a>. All Rights Reserved</p>
                    </div>
                    <div class="col-md-6 text-right text-lm-center">
                        <div class="payment text-right text-lm-center">
                            <img src="assets/images/icons/payment.png" alt="" class="img-responsive">
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- Footer Area End -->  
        
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
    </body>

<!-- Mirrored from template.hasthemes.com/minky/minky/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 09 Jan 2022 06:54:10 GMT -->
</html>
<script>
    function search()
    {
        var searchText =$("#search").val();
        if($.trim(searchText) != "")
        {
            window.location = 'products.php?searchId='+searchText;
        }
    }
</script>
