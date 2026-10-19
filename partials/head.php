<?php
// partials/head.php - shared <head> for the storefront. Set $pageTitle before including.
$pageTitle = isset($pageTitle) ? $pageTitle . " | Pet Shop" : "Pet Shop | Food, Toys & Care for Happy Pets";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="Pet Shop, Vadodara: healthy food, treats and everyday essentials for dogs, cats and more, delivered to your door.">
    <meta name="theme-color" content="#fbf6ee">
    <link rel="icon" href="assets/images/favicon/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Nunito:wght@400;600;700;800&display=swap">
    <link rel="stylesheet" href="assets/css/vendor/vendor.min.css">
    <link rel="stylesheet" href="assets/css/theme.css">
</head>
<body>
