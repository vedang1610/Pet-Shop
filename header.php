<script src="assets/js/theme.js"></script>
<link rel="stylesheet" href="assets/css/dark-mode.css" />
<script src="assets/js/image-knockout.js" defer></script>
<!-- Header Section Start From Here -->
<header class="header-wrapper">
            <!-- Header Nav Start -->
            <div class="header-nav bg-white d-lg-block d-none">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 align-self-center">
                            <div class="header-nav-wrapper">
                                <div class="header-menu-nav">
                                    <div class="menu-nav ">
                                        <div class="contact-wrap"> 
                                            <div class="contact-link">
                                                <div class="data phone">
                                                    <a href="tel:+911234567890"><i class="las la-phone-volume"></i> <span class="hidden-lg-down">Phone:</span> +91 12345 67890</a> 
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="dropdown">
                                            <button type="button" id="dropdownMenuButton-3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <img src="assets/images/flag/1.png" alt="" /> <span>English</span>  <i class="las la-angle-down"></i>
                                            </button>

                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-3">
                                                <li>
                                                    <a class="current" href="#"><img src="assets/images/flag/1.png" alt="" /> English</a>
                                                </li>
                                                <li>
                                                    <a href="#"><img src="assets/images/flag/2.png" alt="" /> Français</a>
                                                </li>
                                            </ul>
                                        </div> -->
                                        <!-- <div class="dropdown">
                                            <button type="button" id="dropdownMenuButton-2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">$ <span>USD</span>  <i class="las la-angle-down"></i></button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-2">
                                                <li><a class="current" href="#">USD $</a></li>
                                                <li><a href="#">EUR €</a></li>
                                            </ul>
                                        </div> -->
                                        <!-- <div class="wishlist-top-wrap">
                                            <div class="wishlist-top">
                                                <a href="wishlist.html"><i class="las la-heart"></i><span class="wishlist-text">Wishlist</span> <span class="wishlist-number">(0)</span></a>
                                            </div>
                                        </div>
                                        <div class="compare-top-wrap">
                                            <div class="compare-top">
                                                <a href="compare.html"><i class="las la-sync"></i><span class="compare-text">compare</span> <span class="compare-number">(0)</span></a>
                                            </div>
                                        </div> -->
                                        <?php
                                        if(isset($_SESSION['userId']))
                                        {
                                            ?>
                                            <div class="dropdown float-right">
                                                <button type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="las la-user-circle"></i> <span>My Account</span> <i class="las la-angle-down"></i></button>

                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><a href="admin/userDashboard.php" target="_blank">My Account</a></li>
                                                    <li><a href="checkout.php">Checkout</a></li>
                                                    <li><a href="userLogout.php">Logout</a></li>
                                                </ul>
                                            </div>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <div class="dropdown float-right">
                                                <a href="register.php">
                                                    <button type="button"><i class="las la-user-circle"></i> <span>Login / Register</span></button>
                                                </a>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <!-- <div class="search-element-wrap">
                                            <a href="javascript:void(0)"><i class="las la-search"></i></a>
                                            <div class="search-element">
                                                <form action="#">
                                                    <input type="text" placeholder="Search entire store here ..." />
                                                    <button><i class="las la-search"></i></button>
                                                </form>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Header Nav End -->
            <div class="header-menu bg-indigo sticky-nav d-lg-block d-none">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 d-flex">
                            <div class="logo align-self-center">
                                <a href="index.php">
                                    <h1 class="text-white">Pet Shop</h1>
                                    <!-- <img class="img-responsive" src="assets/images/logo/logo.png" alt="logo.png" /> -->
                                </a>
                            </div>
                        </div>
                        <div class="col-md-7  d-flex">
                            <div class="header-horizontal-menu align-self-center">
                                <ul class="menu-content">
                                    <li class="active menu-dropdown">
                                        <a href="index.php">Home</a>
                                    </li>
                                    <li><a href="products.php">Products</a></li>
                                    <li><a href="about.php">About Us</a></li>
                                    <li><a href="contact.php">Contact Us</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-2 align-self-center">
                            <div class="header-right-element d-flex justify-content-end">
                                <!--Cart info Start -->
                                <div class="header-tools d-flex">
                                    <button type="button" class="theme-switch" data-theme-toggle aria-label="Switch to dark mode"><i class="las la-moon"></i><i class="las la-sun"></i></button>
                                    <div class="cart-info d-flex align-self-center">
                                        <a title="Cart" href="cart.php">
                                            <i class="las la-baby-carriage"></i>
                                            <!-- <span class="item-total">
                                                $50.00
                                            </span>
                                            <span class="item-count">
                                                3 item(s)
                                            </span> -->
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!--Cart info End -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Header Nav End -->
        </header>
        <!-- Header Section End Here -->    

        <!-- Mobile Header Section Start -->
    <div class="mobile-header d-lg-none sticky-nav bg-indigo ptb-10px">
        <div class="container">
            <div class="row align-items-center">

                <!-- Header Logo Start -->
                <div class="col d-flex">
                    <div class="header-logo">
                            <a href="index.php">
                                    <h1 class="text-white">Pet Shop</h1>
                                    <!-- <img class="img-responsive" src="assets/images/logo/logo.png" alt="logo.png" /> -->
                                </a>
                    </div>
                    <div class="mobile-menu-toggle align-self-center">
                        <a href="#offcanvas-mobile-menu" class="offcanvas-toggle">
                            <i class="las la-bars"></i>
                        </a>
                     </div>
                </div>
                <!-- Header Logo End -->

                <!-- Header Tools Start -->
                <div class="col-auto">
                    <div class="header-tools justify-content-end">
                        <div class="cart-info d-flex align-self-center">
                            <?php
                            if(isset($_SESSION['userId']))
                            {
                                ?>
                                <div class="dropdown float-right">
                                    <button type="button" id="dropdownMenuButton-4" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="las la-user"></i></button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-4">
                                        <li><a href="admin/userDashboard.php">My Account</a></li>
                                        <li><a href="checkout.php">Checkout</a></li>
                                        <li><a href="logout.php">Log Out</a></li>
                                    </ul>
                                </div>
                                <?php
                            }
                            else
                            {
                                ?>
                                <div class="dropdown float-right">
                                    <a href="register.php">
                                        <button type="button">
                                            <i class="las la-user"></i>
                                        </button>
                                    </a>
                                    <!-- <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-4">
                                        <li><a href="my-account.html">My Account</a></li>
                                        <li><a href="checkout.html">Checkout</a></li>
                                        <li><a href="login.html">Log Out</a></li>
                                    </ul> -->
                                </div>
                                <?php
                            }
                            ?>
                            
                            <button type="button" class="theme-switch" data-theme-toggle aria-label="Switch to dark mode"><i class="las la-moon"></i><i class="las la-sun"></i></button>
                            <a title="cart" href="cart.php">
                                    <i class="las la-baby-carriage"></i>
                                    <!-- <span class="item-total">$0.00</span>
                                    <span class="item-count">3</span> -->
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Header Tools End -->

            </div>
        </div>
    </div>

    <!-- Search Category Start -->
    <!-- <div class="mobile-search-area d-lg-none">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="search-element">
                        <form class="d-flex" action="#">
                            <input type="text" placeholder="Enter your search key ... " />
                            <button><i class="las la-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- Search Category End -->
    <!-- Mobile Header Section End -->

    <!-- OffCanvas Cart Start -->
    <!-- <div id="offcanvas-cart" class="offcanvas offcanvas-cart">
        <div class="inner">
            <div class="head">
                <span class="title">Cart</span>
                <button class="offcanvas-close">×</button>
            </div>
            <div class="body customScroll">
                <ul class="minicart-product-list">
                    <li>
                        <a href="single-product.html" class="image"><img src="assets/images/product-image/1.jpg" alt="Cart product Image"></a>
                        <div class="content">
                            <a href="single-product.html" class="title">Walnut Cutting Board</a>
                            <span class="quantity-price">1 x <span class="amount">$34.86</span></span>
                            <a href="#" class="remove">×</a>
                        </div>
                    </li>
                    <li>
                        <a href="single-product.html" class="image"><img src="assets/images/product-image/2.jpg" alt="Cart product Image"></a>
                        <div class="content">
                            <a href="single-product.html" class="title">Lucky Wooden Elephant</a>
                            <span class="quantity-price">1 x <span class="amount">$22.28</span></span>
                            <a href="#" class="remove">×</a>
                        </div>
                    </li>
                    <li>
                        <a href="single-product.html" class="image"><img src="assets/images/product-image/3.jpg" alt="Cart product Image"></a>
                        <div class="content">
                            <a href="single-product.html" class="title">Fish Cut Out Set</a>
                            <span class="quantity-price">1 x <span class="amount">$28.34</span></span>
                            <a href="#" class="remove">×</a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="foot">
                <div class="sub-total">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td class="text-left">Sub-Total :</td>
                                <td class="text-right">$73.30</td>
                            </tr>
                            <tr>
                                <td class="text-left">Eco Tax (-2.00) :</td>
                                <td class="text-right">$4.52</td>
                            </tr>
                            <tr>
                                <td class="text-left">VAT (20%) :</td>
                                <td class="text-right">$94.66</td>
                            </tr>
                            <tr>
                                <td class="text-left">Total :</td>
                                <td class="text-right theme-color">$102.48</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="buttons">
                    <a href="cart.html" class="btn btn-dark btn-hover-primary mb-30px">view cart</a>
                    <a href="checkout.html" class="btn btn-outline-dark current-btn">checkout</a>
                </div>
                <p class="minicart-message">Free Shipping on All Orders Over $100!</p>
            </div>
        </div>
    </div> -->
    <!-- OffCanvas Cart End -->

    <!-- OffCanvas Search Start -->
    <div id="offcanvas-mobile-menu" class="offcanvas offcanvas-mobile-menu">
        <div class="inner customScroll">
            <div class="head">
                <span class="title">&nbsp;</span>
                <button class="offcanvas-close">×</button>
            </div>
            <div class="top-header-mobile">
                <div class="contact-link">
                    <div class="data phone">
                        <a href="tel:+911234567890"><i class="las la-phone-volume"></i> <span class="hidden-lg-down">Phone:</span> +91 12345 67890</a> 
                    </div>
                </div>
                <!-- <div class="wishlist-top">
                    <a href="wishlist.html"><i class="las la-heart"></i><span class="wishlist-text">Wishlist</span> <span class="wishlist-number">(0)</span></a>
                </div>
                <div class="compare-top">
                    <a href="compare.html"><i class="las la-sync"></i><span class="compare-text">compare</span> <span class="compare-number">(0)</span></a>
                </div> -->
            </div>
                <!-- <div class="offcanvas-userpanel m-b-30">
                    <ul>
                        <li class="offcanvas-userpanel__role">
                            <a href="#">USD $ <i class="las la-angle-down"></i></a>
                            <ul class="user-sub-menu">
                                <li><a class="current" href="#">USD $</a></li>
                                <li><a href="#">EURO €</a></li>
                            </ul>
                        </li>
                        <li class="offcanvas-userpanel__role">
                            <a href="#"><img src="assets/images/flag/1.png" alt="">English <i class="las la-angle-down"></i></a>
                            <ul class="user-sub-menu">
                                <li><a class="current" href="#"><img src="assets/images/flag/1.png" alt="">English</a></li>
                                <li><a href="#"><img src="assets/images/flag/2.png" alt=""> Français</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="menu-close"> 
                 menu <i class="las la-arrow-left"></i>
                </div> -->
            <div class="offcanvas-menu">
                <ul>
                    <li class="active menu-dropdown">
                        <a href="index.php">Home</a>
                    </li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>
                         <!-- OffCanvas Menu End -->
            <div class="offcanvas-social mt-30px">
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
                        <a href="#"><i class="ion-social-youtube"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="ion-social-instagram"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>