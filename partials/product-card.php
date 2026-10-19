<?php // partials/product-card.php - one product tile; expects $productsRow (optionally with 'categoryName') ?>
<article class="product-card">
    <a class="product-media" href="productDetails.php?getData=<?php echo $productsRow['id']; ?>">
        <img src="admin/upload_images/<?php echo $productsRow['image']; ?>" alt="<?php echo htmlspecialchars($productsRow['name']); ?>" loading="lazy">
    </a>
    <div class="product-body">
        <?php if (!empty($productsRow['categoryName'])) { ?>
            <span class="product-cat"><?php echo htmlspecialchars($productsRow['categoryName']); ?></span>
        <?php } ?>
        <a class="product-title" href="productDetails.php?getData=<?php echo $productsRow['id']; ?>"><?php echo htmlspecialchars($productsRow['name']); ?></a>
        <div class="product-foot">
            <span class="price"><small>Rs.</small><?php echo $productsRow['price']; ?></span>
            <a class="add-btn" href="cart.php?addProduct=<?php echo $productsRow['id']; ?>&qty=1" title="Add to cart" aria-label="Add <?php echo htmlspecialchars($productsRow['name']); ?> to cart"><i class="las la-plus"></i></a>
        </div>
    </div>
</article>
