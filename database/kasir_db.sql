-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: kasir_db
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Perabotan Rumah Tangga','perabotan-rumah-tangga','Peralatan dan perlengkapan rumah tangga','2026-10-12 02:19:19','2026-10-12 02:19:19'),(2,'Frozen Food','frozen-food','Makanan beku siap saji','2026-10-12 02:19:19','2026-10-12 02:19:19'),(3,'Peralatan Dapur','peralatan-dapur','Alat masak dan dapur','2026-10-12 02:19:19','2026-10-12 02:19:19');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2024_01_01_000001_create_categories_table',1),(6,'2024_01_01_000002_create_products_table',1),(7,'2024_01_01_000003_create_orders_table',1),(8,'2024_01_01_000004_create_order_items_table',1),(9,'2024_01_01_000005_add_sort_order_to_products_table',2),(10,'2024_01_01_000006_add_delivered_at_to_orders_table',3),(11,'2026_10_13_140044_create_notifications_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES ('10f0669d-2a3e-4dcb-9645-d2a8365deb2c','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',3,'{\"order_id\":5,\"invoice_number\":\"INV-20261014-29CAA\",\"status\":\"diproses\",\"payment_status\":\"belum_bayar\",\"title\":\"Pesanan Sedang Diproses\",\"message\":\"Pesanan INV-20261014-29CAA sedang diproses. Selanjutnya akan segera dikirim.\",\"icon\":\"gear\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/pesanan\\/5\"}','2026-10-14 12:35:26','2026-10-14 01:17:35','2026-10-14 12:35:26'),('2f4a9422-259e-4193-bd5d-7631c2d8aa54','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',6,'{\"order_id\":6,\"invoice_number\":\"INV-20261102-715DC\",\"status\":\"menunggu\",\"payment_status\":\"belum_bayar\",\"title\":\"Pesanan Diterima\",\"message\":\"Pesanan INV-20261102-715DC sudah kami terima dan sedang menunggu konfirmasi.\",\"icon\":\"receipt\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/pesanan\\/6\"}','2026-11-02 02:59:09','2026-11-02 02:53:18','2026-11-02 02:59:09'),('56096d81-140f-4e3e-86e2-9868325ea0a4','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',3,'{\"order_id\":5,\"invoice_number\":\"INV-20261014-29CAA\",\"status\":\"selesai\",\"payment_status\":\"belum_bayar\",\"title\":\"Pesanan Selesai\",\"message\":\"Pesanan INV-20261014-29CAA telah selesai. Terima kasih telah berbelanja!\",\"icon\":\"check-circle\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/pesanan\\/5\"}','2026-10-19 04:41:20','2026-10-19 04:08:24','2026-10-19 04:41:20'),('708d192c-d4e6-463c-8a7f-bb51a426c0da','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',3,'{\"order_id\":5,\"invoice_number\":\"INV-20261014-29CAA\",\"status\":\"selesai\",\"payment_status\":\"lunas\",\"title\":\"Pembayaran Diverifikasi\",\"message\":\"Pembayaran pesanan INV-20261014-29CAA telah diverifikasi (Lunas).\",\"icon\":\"credit-card\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/pesanan\\/5\"}','2026-10-19 04:41:11','2026-10-19 04:08:30','2026-10-19 04:41:11'),('7116afcb-5431-4437-9d59-0c5a4db295aa','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',6,'{\"order_id\":6,\"invoice_number\":\"INV-20261102-715DC\",\"status\":\"selesai\",\"payment_status\":\"lunas\",\"title\":\"Pesanan Selesai\",\"message\":\"Pesanan INV-20261102-715DC telah selesai. Terima kasih telah berbelanja!\",\"icon\":\"check-circle\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/pesanan\\/6\"}','2026-11-02 02:59:09','2026-11-02 02:55:55','2026-11-02 02:59:09'),('840d1fc0-b61d-4bb8-b60f-a8f5bfc802c0','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',6,'{\"order_id\":7,\"invoice_number\":\"INV-20261102-1C970\",\"status\":\"menunggu\",\"payment_status\":\"belum_bayar\",\"title\":\"Pesanan Diterima\",\"message\":\"Pesanan INV-20261102-1C970 sudah kami terima dan sedang menunggu konfirmasi.\",\"icon\":\"receipt\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/pesanan\\/7\"}','2026-11-02 04:38:55','2026-11-02 04:37:46','2026-11-02 04:38:55'),('8c1e5cbd-05fc-4abb-b826-5bf3564ef090','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',6,'{\"order_id\":6,\"invoice_number\":\"INV-20261102-715DC\",\"status\":\"menunggu\",\"payment_status\":\"lunas\",\"title\":\"Pembayaran Diverifikasi\",\"message\":\"Pembayaran pesanan INV-20261102-715DC telah diverifikasi (Lunas).\",\"icon\":\"credit-card\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/pesanan\\/6\"}','2026-11-02 02:59:09','2026-11-02 02:55:48','2026-11-02 02:59:09'),('dd3c79df-6986-48be-a34c-3cdb133f38ef','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',3,'{\"order_id\":5,\"invoice_number\":\"INV-20261014-29CAA\",\"status\":\"menunggu\",\"payment_status\":\"belum_bayar\",\"title\":\"Pesanan Diterima\",\"message\":\"Pesanan INV-20261014-29CAA sudah kami terima dan sedang menunggu konfirmasi.\",\"icon\":\"receipt\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/pesanan\\/5\"}','2026-10-14 01:18:21','2026-10-14 01:16:44','2026-10-14 01:18:21');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `qty` int NOT NULL,
  `subtotal` decimal(14,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (5,6,7,'Nugget Ayam 500gr',35000.00,1,35000.00,'2026-11-02 02:53:16','2026-11-02 02:53:16'),(6,7,8,'Sosis Sapi 500gr',40000.00,1,40000.00,'2026-11-02 04:37:45','2026-11-02 04:37:45'),(7,7,9,'Kentang Goreng 1kg',28000.00,1,28000.00,'2026-11-02 04:37:45','2026-11-02 04:37:45'),(8,7,7,'Nugget Ayam 500gr',35000.00,1,35000.00,'2026-11-02 04:37:45','2026-11-02 04:37:45');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `total` decimal(14,2) NOT NULL DEFAULT '0.00',
  `status` enum('menunggu','diproses','dikirim','selesai','dibatalkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `payment_method` enum('transfer','cod') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'transfer',
  `payment_status` enum('belum_bayar','menunggu_verifikasi','lunas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_bayar',
  `payment_proof` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `delivered_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_invoice_number_unique` (`invoice_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (6,'INV-20261102-715DC',6,'Muhammad Adam Ghaisan Sya\'bana','085935662758','Perumahan Koperpu Karang Satria Blok I 25','Antar secepatnya ya',35000.00,'selesai','cod','lunas',NULL,'2026-11-02 02:55:48','2026-11-02 09:55:55','2026-11-02 02:53:16','2026-11-02 02:55:55'),(7,'INV-20261102-1C970',6,'Muhammad Adam Ghaisan Sya\'bana','085935662758','Perumahan Koperpu Karang Satria Blok I 25',NULL,103000.00,'menunggu','transfer','belum_bayar',NULL,NULL,NULL,'2026-11-02 04:37:45','2026-11-02 04:37:45');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `stock` int NOT NULL DEFAULT '0',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pcs',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '9999',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'Sapu Lantai','sapu-lantai-TPHE','Produk berkualitas Sapu Lantai.',25000.00,100,'pcs','products/product-1.jpg',1,2,'2026-10-12 02:19:19','2026-10-13 11:58:57'),(2,1,'Ember Plastik 20L','ember-plastik-20l-sDlW','Produk berkualitas Ember Plastik 20L.',35000.00,40,'pcs','products/product-2.jpg',1,3,'2026-10-12 02:19:19','2026-10-13 11:58:57'),(3,1,'Keset Kaki','keset-kaki-kGCC','Produk berkualitas Keset Kaki.',30000.00,30,'pcs','products/product-3.jpg',1,4,'2026-10-12 02:19:19','2026-10-13 11:58:57'),(4,3,'Wajan Anti Lengket','wajan-anti-lengket-aa1w','Produk berkualitas Wajan Anti Lengket.',120000.00,20,'pcs','products/product-4.jpg',1,5,'2026-10-12 02:19:19','2026-10-13 11:58:57'),(5,3,'Pisau Dapur Set','pisau-dapur-set-x8CP','Produk berkualitas Pisau Dapur Set.',85000.00,25,'set','products/product-5.jpg',1,6,'2026-10-12 02:19:19','2026-10-13 11:58:57'),(6,3,'Talenan Kayu','talenan-kayu-NQf9','Produk berkualitas Talenan Kayu.',20000.00,60,'pcs','products/product-6.jpg',1,7,'2026-10-12 02:19:19','2026-10-13 11:58:57'),(7,2,'Nugget Ayam 500gr','nugget-ayam-500gr-BIBM','Produk berkualitas Nugget Ayam 500gr.',35000.00,98,'pack','products/product-7.jpg',1,8,'2026-10-12 02:19:19','2026-11-02 04:37:45'),(8,2,'Sosis Sapi 500gr','sosis-sapi-500gr-caYc','Produk berkualitas Sosis Sapi 500gr.',40000.00,79,'pack','products/product-8.jpg',1,9,'2026-10-12 02:19:19','2026-11-02 04:37:45'),(9,2,'Kentang Goreng 1kg','kentang-goreng-1kg-JOPT','Produk berkualitas Kentang Goreng 1kg.',28000.00,89,'pack','products/product-9.jpg',1,10,'2026-10-12 02:19:19','2026-11-02 04:37:45'),(10,2,'Dimsum Ayam 250gr','dimsum-ayam-250gr-KYQN','Produk berkualitas Dimsum Ayam 250gr.',30000.00,70,'pack','products/product-10.jpg',1,11,'2026-10-12 02:19:19','2026-10-13 11:58:57'),(11,1,'Pel Lantai','pel-lantai-GC1Q','Pel lantai putar',25000.00,20,'pcs','products/product-11.jpg',1,1,'2026-10-12 02:21:39','2026-10-19 04:09:43');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','pelanggan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pelanggan',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin Kasir','admin',NULL,NULL,NULL,NULL,'$2y$12$17ibbkVAsctOZDNr5afi0usFR0Soem5swC4gLPCDzSuH9W3up4P16','admin',NULL,NULL,'2026-10-12 02:19:19','2026-11-02 02:24:32'),(6,'Muhammad Adam Ghaisan Sya\'bana','Adam',NULL,'085935662758','Perumahan Koperpu Karang Satria Blok I 25','$2y$12$3CqAzRyQmh88Oc95/f1ll.TApXJPc5G1hm5F4wETQDITOf5GgZLEq',NULL,'pelanggan',NULL,NULL,'2026-11-02 02:47:48','2026-11-02 02:47:48');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'kasir_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-04  8:47:04
