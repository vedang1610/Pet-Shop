-- Pet Shop database (clean copy for GitHub)
-- Table structure for every table, plus demo data for: admin, category, products, product_images.
-- No customer, order, cart, address or reset data is included.
-- Demo admin login: admin@admin.com / admin  (change it after setup!)


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `dt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `dt` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0 = not active, 1 = active',
  `dt` date DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `order_status_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_status_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `changed_by` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `by_order` (`sales_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_type` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `by_email` (`account_type`,`email`,`created_at`),
  KEY `by_ip` (`ip`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pincode_cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pincode_cache` (
  `pincode` char(6) NOT NULL,
  `found` tinyint(1) NOT NULL,
  `data` text NOT NULL,
  `fetched_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`pincode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` double(10,2) NOT NULL DEFAULT 0.00,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 = not active, 1 = active',
  `dt` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `payment_mode` int(11) NOT NULL DEFAULT 0 COMMENT '0= Offline, 1 = Online',
  `payment_status` int(11) NOT NULL DEFAULT 0 COMMENT '0= Pending, 1 = Paid',
  `delivery_status` int(11) DEFAULT NULL COMMENT '0 = pending,\r\n1 = delivered',
  `transaction_id` varchar(255) DEFAULT NULL,
  `delivery_address` text NOT NULL,
  `tax` double(10,2) NOT NULL DEFAULT 0.00,
  `total` double(10,2) NOT NULL DEFAULT 0.00,
  `remark` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0 = pending,\r\n1 = confirmed,\r\n2 = canceled,\r\n3 = canceled by user,\r\n4 = success',
  `dt` date DEFAULT current_timestamp(),
  `delivery_changes` double(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sales_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `price` double(10,2) NOT NULL,
  `total` double(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `alt_phone` varchar(15) DEFAULT NULL,
  `pincode` varchar(6) NOT NULL,
  `locality` varchar(150) NOT NULL,
  `address_line` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(60) NOT NULL,
  `landmark` varchar(150) DEFAULT NULL,
  `address_type` varchar(10) NOT NULL DEFAULT 'home',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `last_used_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `by_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `idproof` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 = not active, 1 = active',
  `dt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','admin@admin.com','admin','2021-12-20 16:21:56');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (5,'Category 1 d','1642608871.jpg',1,'2022-01-17');
INSERT INTO `category` VALUES (6,'Category 2','1643295175.png',1,'2022-01-27');
INSERT INTO `category` VALUES (7,'Fish','demo_cat_fish.png',1,'2026-10-16');
INSERT INTO `category` VALUES (8,'Cats','demo_cat_cats.png',1,'2026-10-16');
INSERT INTO `category` VALUES (9,'Dogs','demo_cat_dogs.png',1,'2026-10-16');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (5,5,'Product 1','1642527228.jpg','Test Description',101.00,1,'2022-01-18');
INSERT INTO `products` VALUES (7,7,'Discus Complete Granules, 100 ml','demo_p3.jpg','Complete daily food for discus and other mid-water and bottom-feeding fish.\nSlow-sinking granules support health, colour and growth.',299.00,1,'2026-10-16');
INSERT INTO `products` VALUES (8,7,'Koi & Goldfish Pellets, 5 kg','demo_p7.jpg','Floating pellets for koi, goldfish and large tropical fish.\nBalanced nutrition for strong growth and bright colours.',899.00,1,'2026-10-16');
INSERT INTO `products` VALUES (9,7,'Tropical Fish Flakes, 202 g','demo_p12.jpg','Daily nutrition for all tropical fish, with added vitamins, minerals and natural colours.',349.00,1,'2026-10-16');
INSERT INTO `products` VALUES (10,7,'Goldfish Granules, 85 g','demo_p13.jpg','Slow-sinking granules for goldfish and other pond fish. Made with natural colours and ingredients.',199.00,1,'2026-10-16');
INSERT INTO `products` VALUES (11,8,'Grain-Free Chicken & Sweet Potato Cat Food, 1.8 kg','demo_p14.jpg','Real chicken is the first ingredient. Grain-free recipe for adult indoor and outdoor cats, with added vitamins, minerals and taurine.',1399.00,1,'2026-10-16');
INSERT INTO `products` VALUES (12,8,'Salmon & Brown Rice Cat Food, 1.8 kg','demo_p11.jpg','Salmon is the number one ingredient. No corn, wheat or soy, and no artificial colours, flavours or preservatives.',1599.00,1,'2026-10-16');
INSERT INTO `products` VALUES (13,8,'Kitten Nibbles Dry Food, 1.4 kg','demo_p10.jpg','Small, easy-to-chew bites made for growing kittens.\nComplete and balanced nutrition with DHA to support healthy development.',599.00,1,'2026-10-16');
INSERT INTO `products` VALUES (14,8,'Original Choice Dry Cat Food, 1.5 kg','demo_p9.jpg','A tasty mix of chicken, turkey, salmon and ocean fish flavours.\n100% complete and balanced nutrition for adult cats.',649.00,1,'2026-10-16');
INSERT INTO `products` VALUES (15,8,'Chicken Pate Wet Cat Food, 156 g','demo_p8.jpg','Smooth chicken pate with an improved texture that cats love. Great as a meal or a topper for dry food.',179.00,1,'2026-10-16');
INSERT INTO `products` VALUES (16,8,'Indoor Chicken, Lentil & Salmon Cat Food, 2 kg','demo_p5.jpg','Super premium food for indoor cats. Real chicken plus salmon, with a superfood blend for weight, digestion and immunity.',1299.00,1,'2026-10-16');
INSERT INTO `products` VALUES (17,9,'Chicken & Barley Senior Dog Food, 370 g','demo_p16.jpg','Wet food for adult dogs aged 7 and up. Tender chicken and barley in a hearty, easy-to-digest entree.',229.00,1,'2026-10-16');
INSERT INTO `products` VALUES (18,9,'Chicken & Vegetable Stew, 370 g','demo_p15.jpg','A comforting stew of chicken and vegetables for dogs. Serve on its own or mixed with dry food.',249.00,1,'2026-10-16');
INSERT INTO `products` VALUES (19,9,'Turkey & Country Vegetables Adult Dog Food, 2 kg','demo_p6.jpg','Dry food for adult dogs of all breeds, made with turkey, chicken and country vegetables. Wheat free.',899.00,1,'2026-10-16');
INSERT INTO `products` VALUES (20,9,'Salmon & Sweet Potato Dry Dog Food, 2 kg','demo_p4.jpg','Real salmon is the first ingredient in this simple 8-ingredient, grain-free recipe. High in animal protein.',1499.00,1,'2026-10-16');
INSERT INTO `products` VALUES (21,9,'Chicken Chewy Twists, 4 sticks','demo_p2.jpg','Tasty, chewy chicken twists your dog will love. With omega 3, vitamins and minerals, and no artificial colours.',149.00,1,'2026-10-16');
INSERT INTO `products` VALUES (22,9,'Daily Dental Care Sticks, 7 pack','demo_p1.jpg','One stick a day helps reduce tartar build-up and clean teeth. For medium dogs weighing 10 to 25 kg.',199.00,1,'2026-10-16');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (3,5,'1642526823_61e6f86750f5a.jpg');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

