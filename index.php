<?php
session_start();
include('admin/functions.php');
include('admin/db.php');

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

        <!-- Slider Start -->
        <div class="slider-area">
            <div class="hero-slider-wrapper">
                <!-- Single Slider  -->
                <div class="single-slide slider-height-1 bg-img d-flex" data-bg-image="assets/images/slider-image/sample-1.jpg">
                </div>
                <!-- Single Slider  -->
                <div class="single-slide slider-height-1 bg-img d-flex" data-bg-image="assets/images/slider-image/sample-2.jpg">
                </div>
                <!-- Single Slider  -->
            </div>
        </div>
        <!-- Slider End -->
        <!-- Thumb Category Area Start --> 
        <!-- <div class="thumb-category-area ptb-100px">
            <div class="container">
                <div class="row">
                    <div class="thumb-category-slider slider-nav-style-1">
                        <div class="thumb-slider-inner">
                              <div class="thumb-category">
                                  <a href="shop-left-sidebar.html">
                                      <img src="assets/images/thumb-image/thumb-1.jpg" alt="thumb-image">
                                  </a>
                                  <div class="thumb-category-desc">
                                      <h4><a href="shop-left-sidebar.html">Bird Food</a></h4>
                                  </div>
                              </div>
                        </div>
                        <div class="thumb-slider-inner">
                              <div class="thumb-category">
                                  <a href="shop-left-sidebar.html">
                                      <img src="assets/images/thumb-image/thumb-2.jpg" alt="thumb-image">
                                  </a>
                                  <div class="thumb-category-desc">
                                      <h4><a href="shop-left-sidebar.html">Dog Food</a></h4>
                                  </div>
                              </div>
                        </div>
                        <div class="thumb-slider-inner">
                              <div class="thumb-category">
                                  <a href="shop-left-sidebar.html">
                                      <img src="assets/images/thumb-image/thumb-3.jpg" alt="thumb-image">
                                  </a>
                                  <div class="thumb-category-desc">
                                      <h4><a href="shop-left-sidebar.html">Cat Food</a></h4>
                                  </div>
                              </div>
                        </div>
                        <div class="thumb-slider-inner">
                              <div class="thumb-category">
                                  <a href="shop-left-sidebar.html">
                                      <img src="assets/images/thumb-image/thumb-1.jpg" alt="thumb-image">
                                  </a>
                                  <div class="thumb-category-desc">
                                      <h4><a href="shop-left-sidebar.html">Wild Bird</a></h4>
                                  </div>
                              </div>
                        </div>
                    </div>        
                </div>
            </div>
        </div> -->
        <!-- Thumb Category Area End --> 
        <!-- Category Tab Slider Area Start -->
        <div class="category-tab-slider-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title">
                            <h2>OUR CATEGORIES</h2>
                            <!-- <p>Browse the collection of our tab products.</p> -->
                        </div>
                        <div class="box-tab ">   
                            <ul class="tab-heading tabs-categorys nav nav-tabs">
                                <?php
                                $category = selectData("SELECT * FROM `category` order by id desc");
                                if(is_array($category) && count($category) >= 1)
                                {
                                    foreach ($category as $row) 
                                    {
                                        ?>
                                        <li>
                                            <a class="active" href="products.php?categoryId=<?php echo $row['id']; ?>">
                                                <span class="img-thumb"><img src="admin/upload_images/<?php echo $row['image']; ?>" alt=""></span>
                                                <span class="pat-name"><?php echo $row['name']; ?></span>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                                <!-- <li>
                                    <a  class="active" data-bs-toggle="pill" href="#tab-1">
                                        <span class="img-thumb"><img src="assets/images/category-image/1.png" alt=""></span>
                                        <span class="pat-name">Dog Food</span>
                                    </a>
                                </li>
                                <li>
                                    <a data-bs-toggle="pill" href="#tab-2">
                                        <span class="img-thumb"><img src="assets/images/category-image/2.png" alt=""></span>
                                        <span class="pat-name">Cat Food</span>
                                    </a>
                                </li>
                                 <li>
                                    <a data-bs-toggle="pill" href="#tab-3">
                                        <span class="img-thumb"><img src="assets/images/category-image/3.png" alt=""></span>
                                        <span class="pat-name">Fish Food</span>
                                    </a>
                                </li>
                                 <li>
                                    <a data-bs-toggle="pill" href="#tab-4">
                                        <span class="img-thumb"><img src="assets/images/category-image/4.png" alt=""></span>
                                        <span class="pat-name">Bird Food</span>
                                    </a>
                                </li> -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Category Tab Slider Area End -->
        <!-- Banner Area Start -->
        <div class="banner-area overflow-hidden pt-100px pb-100px">
            <div class="row">
                <div class="col-md-6 mb-lm-30px">
                    <div class="banner-wrapper">
                        <a href="shop-4-column.html"><img src="assets/images/banner-image/1.jpg" alt="" /></a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="banner-wrapper">
                        <a href="shop-4-column.html"><img src="assets/images/banner-image/2.jpg" alt="" /></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Banner Area End -->
        <!-- Feature Product Area Start -->
        <!-- <div class="feature-atea pb-100px">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title">
                            <h2>FEATURED PRODUCTS</h2>
                            <p>Browse the collection of our Featured products.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-7 col-lg-6 mb-md-50px mb-lm-50px ">
                        <div class="feature-slider-content">
                            <div class="feature-slider-wrapper slider-nav-style-1">
                                <div class="slider-single-item">
                                    <article class="list-product text-left">
                                        <div class="product-inner">
                                            <div class="img-block">
                                                <a href="single-product.html" class="thumbnail">
                                                    <img class="first-img" src="assets/images/product-image/17.jpg" alt="" />
                                                    <img class="second-img" src="assets/images/product-image/18.jpg" alt="" />
                                                </a>
                                                <div class="add-to-link">
                                                    <ul>
                                                        <li>
                                                            <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                                <i class="las la-search"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-decs">
                                                <h2><a href="single-product.html" class="product-link">Trans-Weight Hooded Wind an..</a></h2>
                                                <div class="pricing-meta">
                                                    <ul>
                                                        <li class="old-price">$9.90</li>
                                                        <li class="current-price">$11.00</li>
                                                    </ul>
                                                </div>
                                                <div class="cart-btn">
                                                    <a href="#" class="add-to-curt" title="Add to cart"><i class="las la-baby-carriage"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                    <article class="list-product text-left">
                                        <div class="product-inner">
                                            <div class="img-block">
                                                <a href="single-product.html" class="thumbnail">
                                                    <img class="first-img" src="assets/images/product-image/8.jpg" alt="" />
                                                    <img class="second-img" src="assets/images/product-image/8.jpg" alt="" />
                                                </a>
                                                <div class="add-to-link">
                                                    <ul>
                                                        <li>
                                                            <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                                <i class="las la-search"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-decs">
                                                <h2><a href="single-product.html" class="product-link">Brixton Patrol All Terrain An..</a></h2>
                                                <div class="pricing-meta">
                                                    <ul>
                                                        <li class="current-price">$11.90</li>
                                                    </ul>
                                                </div>
                                                <div class="cart-btn">
                                                    <a href="#" class="add-to-curt" title="Add to cart"><i class="las la-baby-carriage"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                                <div class="slider-single-item">
                                    <article class="list-product text-left">
                                        <div class="product-inner">
                                            <div class="img-block">
                                                <a href="single-product.html" class="thumbnail">
                                                    <img class="first-img" src="assets/images/product-image/12.jpg" alt="" />
                                                    <img class="second-img" src="assets/images/product-image/13.jpg" alt="" />
                                                </a>
                                                <div class="add-to-link">
                                                    <ul>
                                                        <li>
                                                            <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                                <i class="las la-search"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-decs">
                                                <h2><a href="single-product.html" class="product-link">New Balance Fresh Foam LAZ..</a></h2>
                                                <div class="pricing-meta">
                                                    <ul>
                                                        <li class="current-price">$11.90</li>
                                                    </ul>
                                                </div>
                                                <div class="cart-btn">
                                                    <a href="#" class="add-to-curt" title="Add to cart"><i class="las la-baby-carriage"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                    <article class="list-product text-left">
                                        <div class="product-inner">
                                            <div class="img-block">
                                                <a href="single-product.html" class="thumbnail">
                                                    <img class="first-img" src="assets/images/product-image/19.jpg" alt="" />
                                                    <img class="second-img" src="assets/images/product-image/20.jpg" alt="" />
                                                </a>
                                                <div class="add-to-link">
                                                    <ul>
                                                        <li>
                                                            <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                                <i class="las la-search"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-decs">
                                                <h2><a href="single-product.html" class="product-link">Juicy Couture Tricot Logo Strip..</a></h2>
                                                <div class="pricing-meta">
                                                    <ul>
                                                        <li class="current-price">$11.90</li>
                                                    </ul>
                                                </div>
                                                <div class="cart-btn">
                                                    <a href="#" class="add-to-curt" title="Add to cart"><i class="las la-baby-carriage"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                                <div class="slider-single-item">
                                    <article class="list-product text-left">
                                        <div class="product-inner">
                                            <div class="img-block">
                                                <a href="single-product.html" class="thumbnail">
                                                    <img class="first-img" src="assets/images/product-image/3.jpg" alt="" />
                                                    <img class="second-img" src="assets/images/product-image/3.jpg" alt="" />
                                                </a>
                                                <div class="add-to-link">
                                                    <ul>
                                                        <li>
                                                            <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                                <i class="las la-search"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-decs">
                                                <h2><a href="single-product.html" class="product-link">Juicy Couture Solid Sleeve Puff..</a></h2>
                                                <div class="pricing-meta">
                                                    <ul>
                                                        <li class="old-price">$18.90</li>
                                                        <li class="current-price">$17.10</li>
                                                    </ul>
                                                </div>
                                                <div class="cart-btn">
                                                    <a href="#" class="add-to-curt" title="Add to cart"><i class="las la-baby-carriage"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                    <article class="list-product text-left">
                                        <div class="product-inner">
                                            <div class="img-block">
                                                <a href="single-product.html" class="thumbnail">
                                                    <img class="first-img" src="assets/images/product-image/9.jpg" alt="" />
                                                    <img class="second-img" src="assets/images/product-image/10.jpg" alt="" />
                                                </a>
                                                <div class="add-to-link">
                                                    <ul>
                                                        <li>
                                                            <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                                <i class="las la-search"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-decs">
                                                <h2><a href="single-product.html" class="product-link">Calvin Klein Jeans Reflective Lo..</a></h2>
                                                <div class="pricing-meta">
                                                    <ul>
                                                        <li class="old-price">$18.00</li>
                                                        <li class="current-price">$16.00</li>
                                                    </ul>
                                                </div>
                                                <div class="cart-btn">
                                                    <a href="#" class="add-to-curt" title="Add to cart"><i class="las la-baby-carriage"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                                <div class="slider-single-item">
                                    <article class="list-product text-left">
                                        <div class="product-inner">
                                            <div class="img-block">
                                                <a href="single-product.html" class="thumbnail">
                                                    <img class="first-img" src="assets/images/product-image/6.jpg" alt="" />
                                                    <img class="second-img" src="assets/images/product-image/6.jpg" alt="" />
                                                </a>
                                                <div class="add-to-link">
                                                    <ul>
                                                        <li>
                                                            <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                                <i class="las la-search"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-decs">
                                                <h2><a href="single-product.html" class="product-link">New Balance Fresh Foam LAZ..</a></h2>
                                                <div class="pricing-meta">
                                                    <ul>
                                                        <li class="current-price">$29.90</li>
                                                    </ul>
                                                </div>
                                                <div class="cart-btn">
                                                    <a href="#" class="add-to-curt" title="Add to cart"><i class="las la-baby-carriage"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                    <article class="list-product text-left">
                                        <div class="product-inner">
                                            <div class="img-block">
                                                <a href="single-product.html" class="thumbnail">
                                                    <img class="first-img" src="assets/images/product-image/1.jpg" alt="" />
                                                    <img class="second-img" src="assets/images/product-image/2.jpg" alt="" />
                                                </a>
                                                <div class="add-to-link">
                                                    <ul>
                                                        <li>
                                                            <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                                <i class="las la-search"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-decs">
                                                <h2><a href="single-product.html" class="product-link">Trans-Weight Hooded Wind an..</a></h2>
                                                <div class="pricing-meta">
                                                    <ul>
                                                        <li class="current-price">$11.90</li>
                                                    </ul>
                                                </div>
                                                <div class="cart-btn">
                                                    <a href="#" class="add-to-curt" title="Add to cart"><i class="las la-baby-carriage"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 banner-area">
                        <div class="banner-wrapper">
                            <a href="shop-4-column.html"><img src="assets/images/banner-image/3.jpg" alt="" /></a>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- Feature Product Area End -->
        <!-- Banner Area Start -->
        <div class="banner-area overflow-hidden">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-wrapper">
                        <a href="shop-4-column.html"><img src="assets/images/banner-image/16.jpg" alt="" /></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Banner Area End -->
        <!-- Testimonial Product Area Start -->
        <div class="testimonial-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="testi-title">
                            <h2>TESTIMONIALS</h2>
                        </div>
                    </div>
                </div>
                <div class="testimonial-slider-wrapper slider-nav-style-1">
                    <div class="testimonial-slider-item">
                        <div class="content_author">
                            <div class="img_author">
                                <img src="assets/images/testimonial-image/1.png" alt="Image Testimonial">
                            </div>  
                            <div class="content_test">                                      
                                <div class="des_testimonial">All Perfect !! I have three sites with magento , this theme is the best !! Excellent support , advice theme installation package , sorry for English, are Italian but I had no problem !! Thank you !</div>    
                                <div class="des_inner">
                                    <p class="des_namepost"><span>orando BLoom</span></p>   
                                    <p class="des_email">demo@hasthemes.com</p> 
                                </div>          
                            </div>                          
                        </div>
                    </div>
                    <div class="testimonial-slider-item">
                        <div class="content_author">
                            <div class="img_author">
                                <img src="assets/images/testimonial-image/1.png" alt="Image Testimonial">
                            </div>  
                            <div class="content_test">                                      
                                <div class="des_testimonial">All Perfect !! I have three sites with magento , this theme is the best !! Excellent support , advice theme installation package , sorry for English, are Italian but I had no problem !! Thank you !</div>    
                                <div class="des_inner">
                                    <p class="des_namepost"><span>orando BLoom</span></p>   
                                    <p class="des_email">demo@hasthemes.com</p> 
                                </div>          
                            </div>                          
                        </div>
                    </div>
                    <div class="testimonial-slider-item">
                        <div class="content_author">
                            <div class="img_author">
                                <img src="assets/images/testimonial-image/1.png" alt="Image Testimonial">
                            </div>  
                            <div class="content_test">                                      
                                <div class="des_testimonial">All Perfect !! I have three sites with magento , this theme is the best !! Excellent support , advice theme installation package , sorry for English, are Italian but I had no problem !! Thank you !</div>    
                                <div class="des_inner">
                                    <p class="des_namepost"><span>orando BLoom</span></p>   
                                    <p class="des_email">demo@hasthemes.com</p> 
                                </div>          
                            </div>                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Testimonial Product Area End -->
       

        <!-- Blog Area End -->
        <div class="subscribe-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="subscribe-wrapper">
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

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12 mb-lm-100px mb-sm-30px">
                                <div class="quickview-wrapper">
                                     <!-- slider -->
                                      <div class="gallery-top">
                                          <div class="single-slide"> 
                                                <img class="img-responsive m-auto" src="assets/images/product-image/8.jpg" alt="">
                                          </div>
                                          <div class="single-slide"> 
                                                <img class="img-responsive m-auto" src="assets/images/product-image/14.jpg" alt="">
                                          </div>
                                          <div class="single-slide"> 
                                                <img class="img-responsive m-auto" src="assets/images/product-image/15.jpg" alt="">
                                          </div>
                                          <div class="single-slide"> 
                                                <img class="img-responsive m-auto" src="assets/images/product-image/11.jpg" alt="">
                                          </div>
                                          <div class="single-slide"> 
                                                <img class="img-responsive m-auto" src="assets/images/product-image/19.jpg" alt="">
                                          </div>
                                      </div>
                                      <div class=" gallery-thumbs">
                                          <div class="single-slide"> 
                                                <img class="img-responsive m-auto" src="assets/images/product-image/8.jpg" alt="">
                                          </div>
                                          <div class="single-slide"> 
                                                <img class="img-responsive m-auto" src="assets/images/product-image/14.jpg" alt="">
                                          </div>
                                          <div class="single-slide"> 
                                                <img class="img-responsive m-auto" src="assets/images/product-image/15.jpg" alt="">
                                          </div>
                                          <div class="single-slide"> 
                                                <img class="img-responsive m-auto" src="assets/images/product-image/11.jpg" alt="">
                                          </div>
                                          <div class="single-slide"> 
                                                <img class="img-responsive m-auto" src="assets/images/product-image/19.jpg" alt="">
                                          </div>
                                      </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="product-details-content quickview-content">
                                    <h2>Originals Kaval Windbr</h2>
                                    <p class="reference">Reference:<span> demo_17</span></p>
                                    <div class="pro-details-rating-wrap">
                                        <div class="rating-product">
                                            <i class="ion-android-star"></i>
                                            <i class="ion-android-star"></i>
                                            <i class="ion-android-star"></i>
                                            <i class="ion-android-star"></i>
                                            <i class="ion-android-star"></i>
                                        </div>
                                        <span class="read-review"><a class="reviews" href="#">Read reviews (1)</a></span>
                                    </div>
                                    <div class="pricing-meta">
                                        <ul>
                                            <li class="old-price not-cut">€18.90</li>
                                        </ul>
                                    </div>
                                    <p class="quickview-para">Lorem ipsum dolor sit amet, consectetur adipisic elit eiusm tempor incidid ut labore et dolore magna aliqua. Ut enim ad minim venialo quis nostrud exercitation ullamco</p>
                                    <div class="pro-details-size-color">
                                        <div class="pro-details-color-wrap">
                                            <span>Color</span>
                                            <div class="pro-details-color-content">
                                                <ul>
                                                    <li class="blue"></li>
                                                    <li class="maroon active"></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pro-details-quality">
                                        <div class="cart-plus-minus">
                                            <input class="cart-plus-minus-box" type="text" name="qtybutton" value="1" />
                                        </div>
                                        <div class="pro-details-cart btn-hover">
                                            <a href="#"> + Add To Cart</a>
                                        </div>
                                    </div>
                                    <div class="pro-details-wish-com">
                                        <div class="pro-details-wishlist">
                                            <a href="wishlist.html"><i class="las la-heart"></i></a>
                                        </div>
                                        <div class="pro-details-compare">
                                            <a href="compare.html"><i class="las la-sync"></i></a>
                                        </div>
                                    </div>
                                    <div class="pro-details-social-info">
                                        <span>Share</span>
                                        <div class="social-info">
                                            <ul>
                                                <li>
                                                    <a href="#"><i class="ion-social-facebook"></i></a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="ion-social-twitter"></i></a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="ion-social-google"></i></a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="ion-social-instagram"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
