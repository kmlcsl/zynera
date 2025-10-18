-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versi server:                 8.0.30 - MySQL Community Server - GPL
-- OS Server:                    Win64
-- HeidiSQL Versi:               12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Membuang struktur basisdata untuk zynera_v2_db
CREATE DATABASE IF NOT EXISTS `zynera_v2_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `zynera_v2_db`;

-- membuang struktur untuk table zynera_v2_db.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.cache: ~0 rows (lebih kurang)
DELETE FROM `cache`;

-- membuang struktur untuk table zynera_v2_db.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.cache_locks: ~0 rows (lebih kurang)
DELETE FROM `cache_locks`;

-- membuang struktur untuk table zynera_v2_db.carts
CREATE TABLE IF NOT EXISTS `carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_user_id_foreign` (`user_id`),
  KEY `carts_product_id_foreign` (`product_id`),
  CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.carts: ~0 rows (lebih kurang)
DELETE FROM `carts`;

-- membuang struktur untuk table zynera_v2_db.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.categories: ~3 rows (lebih kurang)
DELETE FROM `categories`;
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Minyak Jelantah Grade A', 'minyak-jelantah-grade-a', 'Minyak jelantah berkualitas tinggi, sudah difilter dan siap olah ulang', 'categories/grade-a.jpg', 1, '2025-10-17 21:21:04', '2025-10-17 21:21:04'),
	(2, 'Minyak Jelantah Grade B', 'minyak-jelantah-grade-b', 'Minyak jelantah kualitas standar, cocok untuk biodiesel dan industri', 'categories/grade-b.jpg', 1, '2025-10-17 21:21:04', '2025-10-17 21:21:04');

-- membuang struktur untuk table zynera_v2_db.deliveries
CREATE TABLE IF NOT EXISTS `deliveries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `courier_id` bigint unsigned DEFAULT NULL,
  `assigned_by` bigint unsigned DEFAULT NULL,
  `status` enum('assigned','picked_up','in_transit','delivered','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'assigned',
  `assigned_at` datetime DEFAULT NULL,
  `picked_up_at` datetime DEFAULT NULL,
  `delivered_at` datetime DEFAULT NULL,
  `duration` decimal(8,2) DEFAULT NULL COMMENT 'Delivery duration in hours',
  `pickup_time` datetime DEFAULT NULL COMMENT 'Actual pickup time',
  `delivery_time` datetime DEFAULT NULL COMMENT 'Actual delivery time',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `deliveries_order_id_foreign` (`order_id`),
  KEY `deliveries_courier_id_foreign` (`courier_id`),
  KEY `deliveries_assigned_by_index` (`assigned_by`),
  CONSTRAINT `deliveries_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `deliveries_courier_id_foreign` FOREIGN KEY (`courier_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `deliveries_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.deliveries: ~0 rows (lebih kurang)
DELETE FROM `deliveries`;

-- membuang struktur untuk table zynera_v2_db.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
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

-- Membuang data untuk tabel zynera_v2_db.failed_jobs: ~0 rows (lebih kurang)
DELETE FROM `failed_jobs`;

-- membuang struktur untuk table zynera_v2_db.feedbacks
CREATE TABLE IF NOT EXISTS `feedbacks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `review_id` bigint unsigned NOT NULL,
  `suggestion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `faq_answer` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feedbacks_review_id_foreign` (`review_id`),
  CONSTRAINT `feedbacks_review_id_foreign` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.feedbacks: ~0 rows (lebih kurang)
DELETE FROM `feedbacks`;

-- membuang struktur untuk table zynera_v2_db.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.jobs: ~0 rows (lebih kurang)
DELETE FROM `jobs`;
INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
	(1, 'default', '{"uuid":"98e5ee2f-4a80-416e-bbba-97e9dfa32d60","displayName":"App\\\\Events\\\\DeliveryStatusUpdated","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"failOnTimeout":false,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"Illuminate\\\\Broadcasting\\\\BroadcastEvent","command":"O:38:\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\":15:{s:5:\\"event\\";O:32:\\"App\\\\Events\\\\DeliveryStatusUpdated\\":3:{s:8:\\"delivery\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:19:\\"App\\\\Models\\\\Delivery\\";s:2:\\"id\\";i:5;s:9:\\"relations\\";a:2:{i:0;s:5:\\"order\\";i:1;s:7:\\"courier\\";}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:9:\\"oldStatus\\";s:9:\\"picked_up\\";s:9:\\"newStatus\\";s:10:\\"in_transit\\";}s:5:\\"tries\\";N;s:7:\\"timeout\\";N;s:7:\\"backoff\\";N;s:13:\\"maxExceptions\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:12:\\"messageGroup\\";N;s:5:\\"delay\\";N;s:11:\\"afterCommit\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;}"},"createdAt":1760775481,"delay":null}', 0, NULL, 1760775481, 1760775481),
	(2, 'default', '{"uuid":"80b94754-b5a5-47c2-bc47-37cc1c4ac2bc","displayName":"App\\\\Events\\\\DeliveryStatusUpdated","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"failOnTimeout":false,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"Illuminate\\\\Broadcasting\\\\BroadcastEvent","command":"O:38:\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\":15:{s:5:\\"event\\";O:32:\\"App\\\\Events\\\\DeliveryStatusUpdated\\":3:{s:8:\\"delivery\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:19:\\"App\\\\Models\\\\Delivery\\";s:2:\\"id\\";i:5;s:9:\\"relations\\";a:2:{i:0;s:5:\\"order\\";i:1;s:7:\\"courier\\";}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:9:\\"oldStatus\\";s:10:\\"in_transit\\";s:9:\\"newStatus\\";s:9:\\"delivered\\";}s:5:\\"tries\\";N;s:7:\\"timeout\\";N;s:7:\\"backoff\\";N;s:13:\\"maxExceptions\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:12:\\"messageGroup\\";N;s:5:\\"delay\\";N;s:11:\\"afterCommit\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;}"},"createdAt":1760775538,"delay":null}', 0, NULL, 1760775538, 1760775538);

-- membuang struktur untuk table zynera_v2_db.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.job_batches: ~0 rows (lebih kurang)
DELETE FROM `job_batches`;

-- membuang struktur untuk table zynera_v2_db.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.migrations: ~1 rows (lebih kurang)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_07_08_065047_create_permission_tables', 1),
	(5, '2025_07_08_065121_add_fields_to_users_table', 1),
	(6, '2025_07_08_065128_create_categories_table', 1),
	(7, '2025_07_08_065134_create_products_table', 1),
	(8, '2025_07_08_065140_create_orders_table', 1),
	(9, '2025_07_08_065147_create_order_items_table', 1),
	(10, '2025_07_08_065153_create_payments_table', 1),
	(11, '2025_07_08_065201_create_deliveries_table', 1),
	(12, '2025_07_08_065211_create_reviews_table', 1),
	(13, '2025_07_08_065217_create_feedbacks_table', 1),
	(14, '2025_07_08_065224_create_nutrition_labels_table', 1),
	(15, '2025_07_08_065237_create_recipes_table', 1),
	(16, '2025_07_08_065243_create_carts_table', 1),
	(17, '2025_07_10_020322_create_user_permissions_table', 1),
	(18, '2025_07_13_064141_add_missing_columns_to_orders_table', 1),
	(19, '2025_07_13_084913_add_assigned_by_to_deliveries_table', 1),
	(20, '2025_07_26_134217_create_otps_table', 1),
	(21, '2025_07_26_205406_make_courier_id_nullable_in_deliveries_table', 1),
	(22, '2025_07_29_102001_update_recipes_table_add_missing_columns', 1),
	(23, '2025_08_02_091946_create_regions_table', 1),
	(24, '2025_08_02_092021_add_region_id_to_users_and_products_table', 1),
	(25, '2025_08_09_075917_add_profile_fields_to_users_table', 1),
	(26, '2025_08_26_163235_create_temp_registrations_table', 1),
	(27, '2025_10_18_061700_add_missing_columns_to_orders_table', 2),
	(28, '2025_10_18_062200_update_payment_method_enum', 3),
	(29, '2024_01_01_000002_update_payments_method_enum_final', 4),
	(30, '2025_10_18_063729_add_shipping_method_to_orders_table', 5),
	(31, '2025_10_18_074644_make_shipping_address_nullable_in_orders_table', 6),
	(32, '2025_10_18_083000_add_bank_info_to_payments_table', 7),
	(33, '2025_01_18_091358_add_duration_to_deliveries_table', 8),
	(34, '2025_10_18_092942_drop_expired_date_from_products_table', 8),
	(35, '2025_10_18_094313_add_address_fields_to_products_table', 9),
	(36, '2025_10_18_094719_drop_unnecessary_address_fields_from_products_table', 10),
	(37, '2025_10_18_095834_drop_recipes_table', 11);

-- membuang struktur untuk table zynera_v2_db.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.model_has_permissions: ~0 rows (lebih kurang)
DELETE FROM `model_has_permissions`;

-- membuang struktur untuk table zynera_v2_db.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.model_has_roles: ~0 rows (lebih kurang)
DELETE FROM `model_has_roles`;

-- membuang struktur untuk table zynera_v2_db.nutrition_labels
CREATE TABLE IF NOT EXISTS `nutrition_labels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `nutritionist_id` bigint unsigned NOT NULL,
  `labels` json NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nutrition_labels_product_id_foreign` (`product_id`),
  KEY `nutrition_labels_nutritionist_id_foreign` (`nutritionist_id`),
  CONSTRAINT `nutrition_labels_nutritionist_id_foreign` FOREIGN KEY (`nutritionist_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nutrition_labels_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.nutrition_labels: ~0 rows (lebih kurang)
DELETE FROM `nutrition_labels`;

-- membuang struktur untuk table zynera_v2_db.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `service_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_items` int NOT NULL DEFAULT '0',
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_method` enum('pickup','courier') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'courier',
  `status` enum('pending','paid','processing','shipped','delivered','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.orders: ~0 rows (lebih kurang)
DELETE FROM `orders`;

-- membuang struktur untuk table zynera_v2_db.order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.order_items: ~0 rows (lebih kurang)
DELETE FROM `order_items`;

-- membuang struktur untuk table zynera_v2_db.otps
CREATE TABLE IF NOT EXISTS `otps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `otps_email_index` (`email`),
  KEY `otps_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.otps: ~0 rows (lebih kurang)
DELETE FROM `otps`;

-- membuang struktur untuk table zynera_v2_db.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.password_reset_tokens: ~0 rows (lebih kurang)
DELETE FROM `password_reset_tokens`;

-- membuang struktur untuk table zynera_v2_db.payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `method` enum('manual','midtrans') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` enum('pending','paid','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_holder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_order_id_foreign` (`order_id`),
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.payments: ~0 rows (lebih kurang)
DELETE FROM `payments`;

-- membuang struktur untuk table zynera_v2_db.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.permissions: ~0 rows (lebih kurang)
DELETE FROM `permissions`;

-- membuang struktur untuk table zynera_v2_db.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `stock` int NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `images` json NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `village_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `weight` decimal(8,2) DEFAULT NULL,
  `ingredients` json DEFAULT NULL,
  `alamat_asal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_user_id_foreign` (`user_id`),
  KEY `products_village_id_index` (`village_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_village_id_foreign` FOREIGN KEY (`village_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.products: ~4 rows (lebih kurang)
DELETE FROM `products`;

-- membuang struktur untuk table zynera_v2_db.recipes
IF NOT EXISTS ;

-- Membuang data untuk tabel zynera_v2_db.recipes: ~0 rows (lebih kurang)
DELETE FROM `recipes`;
-- membuang struktur untuk table zynera_v2_db.regions
CREATE TABLE IF NOT EXISTS `regions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `regions_parent_id_foreign` (`parent_id`),
  KEY `regions_type_parent_id_index` (`type`,`parent_id`),
  KEY `regions_is_active_index` (`is_active`),
  CONSTRAINT `regions_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=336 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.regions: ~0 rows (lebih kurang)
DELETE FROM `regions`;
INSERT INTO `regions` (`id`, `type`, `name`, `code`, `parent_id`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'kabupaten', 'Aceh Barat', '1103', NULL, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(2, 'kecamatan', 'Johan Pahlawan', NULL, 1, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(3, 'desa', 'Blang Beurandang', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(4, 'desa', 'Drien Rampak', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(5, 'desa', 'Gampa', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(6, 'desa', 'Kampung Belakang', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(7, 'desa', 'Kampung Darat', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(8, 'desa', 'Kampung Pasir', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(9, 'desa', 'Kuta Padang', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(10, 'desa', 'Lapang', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(11, 'desa', 'Leuhan', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(12, 'desa', 'Padang Seurahet', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(13, 'desa', 'Panggong', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(14, 'desa', 'Pasar Aceh', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(15, 'desa', 'Rundeng', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(16, 'desa', 'Seuneubok', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(17, 'desa', 'Suak Nie', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(18, 'desa', 'Suak Raya', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(19, 'desa', 'Suak Ribee', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(20, 'desa', 'Suak Sigadeng', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(21, 'desa', 'Suak Indrapuri', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(22, 'desa', 'Ujong Baroh', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(23, 'desa', 'Ujung Kalak', NULL, 2, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(24, 'kecamatan', 'Samatiga', NULL, 1, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(25, 'desa', 'Alue Raya', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(26, 'desa', 'Cot Amun', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(27, 'desa', 'Cot Darat', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(28, 'desa', 'Cot Lampise', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(29, 'desa', 'Cot Mesjid', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(30, 'desa', 'Cot Pluh', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(31, 'desa', 'Cot Seulamat', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(32, 'desa', 'Cot Seumeureung', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(33, 'desa', 'Deuah', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(34, 'desa', 'Gampong Cot', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(35, 'desa', 'Gampong Ladang', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(36, 'desa', 'Gampong Teungoh', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(37, 'desa', 'Keureuseng', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(38, 'desa', 'Krueng Tinggai', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(39, 'desa', 'Kuala Bubon', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(40, 'desa', 'Leubok', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(41, 'desa', 'Leuken', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(42, 'desa', 'Lhok Bubon', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(43, 'desa', 'Mesjid Baro', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(44, 'desa', 'Pange', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(45, 'desa', 'Paya Lumpat', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(46, 'desa', 'Pinem', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(47, 'desa', 'Pucok Leung', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(48, 'desa', 'Rangkileh', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(49, 'desa', 'Reusak', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(50, 'desa', 'Suak Geudeubang', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(51, 'desa', 'Suak Pandan', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(52, 'desa', 'Suak Pante Breuh', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(53, 'desa', 'Suak Seuke', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(54, 'desa', 'Suak Seumaseh', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(55, 'desa', 'Suak Timah', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(56, 'desa', 'Ujong Nga', NULL, 24, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(57, 'kecamatan', 'Bubon', NULL, 1, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(58, 'desa', 'Alue Bakong', NULL, 57, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(59, 'desa', 'Alue Lhok', NULL, 57, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(60, 'desa', 'Beurawang', NULL, 57, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(61, 'desa', 'Blang Sibeutong', NULL, 57, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(62, 'desa', 'Cot Keumuneng', NULL, 57, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(63, 'desa', 'Cot Lada', NULL, 57, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(64, 'desa', 'Gunong Panah', NULL, 57, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(65, 'desa', 'Kuala Pling', NULL, 57, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(66, 'desa', 'Kuta Padang', NULL, 57, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(67, 'desa', 'Layung', NULL, 57, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(68, 'desa', 'Liceh', NULL, 57, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(69, 'desa', 'Peulanteu', NULL, 57, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(70, 'desa', 'Rambong', NULL, 57, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(71, 'desa', 'Seumuleng', NULL, 57, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(72, 'desa', 'Seuneubok Trap', NULL, 57, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(73, 'desa', 'Suak Pangkat', NULL, 57, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(74, 'desa', 'Ulee Blang', NULL, 57, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(75, 'kecamatan', 'Arongan Lambalek', NULL, 1, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(76, 'desa', 'Alue Bagok', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(77, 'desa', 'Alue Batee', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(78, 'desa', 'Alue Sundak', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(79, 'desa', 'Arongan', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(80, 'desa', 'Cot Buloh', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(81, 'desa', 'Cot Juru Mudi', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(82, 'desa', 'Cot Kumbang', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(83, 'desa', 'Drien Rampak', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(84, 'desa', 'Gunong Pulo', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(85, 'desa', 'Karang Hampa', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(86, 'desa', 'Keub', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(87, 'desa', 'Kubu', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(88, 'desa', 'Pante Mutia', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(89, 'desa', 'Panton Bahagia', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(90, 'desa', 'Panton Makmur', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(91, 'desa', 'Peulanteu', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(92, 'desa', 'Peuribu', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(93, 'desa', 'Rimba Langgeh', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(94, 'desa', 'Seuneubok Lhong', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(95, 'desa', 'Seuneubok Teungoh', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(96, 'desa', 'Simpang Peut', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(97, 'desa', 'Suak Bidok', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(98, 'desa', 'Suak Ie Beuso', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(99, 'desa', 'Suak Keumudai', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(100, 'desa', 'Teupin Peuraho', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(101, 'desa', 'Ujong Beusa', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(102, 'desa', 'Ujong Simpang', NULL, 75, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(103, 'kecamatan', 'Woyla', NULL, 1, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(104, 'desa', 'Alue Blang', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(105, 'desa', 'Alue Panyang', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(106, 'desa', 'Alue Sikaya', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(107, 'desa', 'Alue Sundak', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(108, 'desa', 'Aron Baroh', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(109, 'desa', 'Aron Tunong', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(110, 'desa', 'Bakat', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(111, 'desa', 'Blang Me', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(112, 'desa', 'Cot Keumundai', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(113, 'desa', 'Cot Lagan Bubon', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(114, 'desa', 'Cot Murong', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(115, 'desa', 'Cot Situah', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(116, 'desa', 'Darul Huda', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(117, 'desa', 'Drien Mangko', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(118, 'desa', 'Gempa Raya', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(119, 'desa', 'Glee Siblah', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(120, 'desa', 'Gunong Rambong', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(121, 'desa', 'Gunung Hampa', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(122, 'desa', 'Ie Itam Baroh', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(123, 'desa', 'Ie Itam Tunong', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(124, 'desa', 'Jawa', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(125, 'desa', 'Jawi', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(126, 'desa', 'Keuleumbah', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(127, 'desa', 'Kuala Bhee', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(128, 'desa', 'Lueng Buloh', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(129, 'desa', 'Lueng Jawa', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(130, 'desa', 'Lueng Tanoh Tho', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(131, 'desa', 'Lueng Teungku Yah', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(132, 'desa', 'Padang Jawa', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(133, 'desa', 'Panton', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(134, 'desa', 'Pasi Aceh', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(135, 'desa', 'Pasi Ara Kuala Batee', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(136, 'desa', 'Pasi Birah', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(137, 'desa', 'Pasi Lunak', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(138, 'desa', 'Pasi Pandan', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(139, 'desa', 'Paya Dua', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(140, 'desa', 'Paya Luah', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(141, 'desa', 'Pulo Ie', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(142, 'desa', 'Ranto Panyang', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(143, 'desa', 'Seumantok', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(144, 'desa', 'Suak Trieng', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(145, 'desa', 'Teumarom', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(146, 'desa', 'Tingkeum Panyang', NULL, 103, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(147, 'kecamatan', 'Woyla Barat', NULL, 1, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(148, 'desa', 'Alue Keumuning', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(149, 'desa', 'Alue Leuhop', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(150, 'desa', 'Alue Permen', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(151, 'desa', 'Blang Cot Mameh', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(152, 'desa', 'Blang Cot Rubek', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(153, 'desa', 'Blang Luah Leuhop Mameh', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(154, 'desa', 'Cot Lagan Leuhop Mameh', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(155, 'desa', 'Cot Rambong', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(156, 'desa', 'Ie Sayang', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(157, 'desa', 'Karak', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(158, 'desa', 'Kulam Kaju', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(159, 'desa', 'Lhok Malee', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(160, 'desa', 'Lubuk Pasi Ara', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(161, 'desa', 'Lueng Baro', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(162, 'desa', 'Mon Pasong', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(163, 'desa', 'Napai', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(164, 'desa', 'Pasi Jeut', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(165, 'desa', 'Pasi Malee', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(166, 'desa', 'Pasi Mali', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(167, 'desa', 'Pasi Panyang', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(168, 'desa', 'Peuleukeung', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(169, 'desa', 'Simpang Keumaron', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(170, 'desa', 'Ule Pulo', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(171, 'desa', 'Ulei Pasi Ara', NULL, 147, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(172, 'kecamatan', 'Woyla Timur', NULL, 1, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(173, 'desa', 'Alue Bilie', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(174, 'desa', 'Alue Empeuk', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(175, 'desa', 'Alue Kuyun', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(176, 'desa', 'Alue Meuganda', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(177, 'desa', 'Alue Seuralen', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(178, 'desa', 'Blang Dalam', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(179, 'desa', 'Blang Luah', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(180, 'desa', 'Blang Makmur', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(181, 'desa', 'Bukit Meugajah', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(182, 'desa', 'Cot Punti', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(183, 'desa', 'Gampong Baro', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(184, 'desa', 'Gampong Baro Woyla Timur', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(185, 'desa', 'Gunong Payang', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(186, 'desa', 'Keubeu Capang', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(187, 'desa', 'Lubuk Panyang', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(188, 'desa', 'Pasi Ara', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(189, 'desa', 'Pasi Janeng', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(190, 'desa', 'Paya Baro', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(191, 'desa', 'Paya Meugenderang', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(192, 'desa', 'Rambong', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(193, 'desa', 'Rambong Pinto', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(194, 'desa', 'Seuneubok Dalam', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(195, 'desa', 'Seuradeuk', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(196, 'desa', 'Tangkeh', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(197, 'desa', 'Teumiket Ranom', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(198, 'desa', 'Tuwi Empeuk', NULL, 172, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(199, 'kecamatan', 'Kaway XVI', NULL, 1, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(200, 'desa', 'Alue Lhee', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(201, 'desa', 'Alue Lhok', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(202, 'desa', 'Alue On', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(203, 'desa', 'Alue Peudeung', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(204, 'desa', 'Alue Tampak', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(205, 'desa', 'Babah Meulaboh', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(206, 'desa', 'Beureugang', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(207, 'desa', 'Blang Dalam', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(208, 'desa', 'Blang Geunang', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(209, 'desa', 'Batu Jaya', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(210, 'desa', 'Drien Caleue', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(211, 'desa', 'Kampong Mesjid', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(212, 'desa', 'Keude Aron', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(213, 'desa', 'Keude Tanjong', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(214, 'desa', 'Keuramat', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(215, 'desa', 'Marek', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(216, 'desa', 'Meunasah Ara', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(217, 'desa', 'Meunasah Buloh', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(218, 'desa', 'Meunasah Gantung', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(219, 'desa', 'Meunasah Rambot', NULL, 199, 1, '2025-10-18 00:41:38', '2025-10-18 00:41:38'),
	(220, 'desa', 'Meunasah Rayeuk', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(221, 'desa', 'Meunuang Tanjong', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(222, 'desa', 'Muko', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(223, 'desa', 'Padang Mancang', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(224, 'desa', 'Padang Sikabu', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(225, 'desa', 'Palimbungan', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(226, 'desa', 'Pasi Ara', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(227, 'desa', 'Pasi Jambu', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(228, 'desa', 'Pasi Jeumpa', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(229, 'desa', 'Pasi Kumbang', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(230, 'desa', 'Pasi Meugat', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(231, 'desa', 'Pasi Teungoh', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(232, 'desa', 'Peunia', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(233, 'desa', 'Pucok Pungkie', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(234, 'desa', 'Pungkie', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(235, 'desa', 'Putim', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(236, 'desa', 'Puuk', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(237, 'desa', 'Sawang Teubei', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(238, 'desa', 'Simpang', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(239, 'desa', 'Tanjong Bungong', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(240, 'desa', 'Tanjong Meulaboh', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(241, 'desa', 'Teladan', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(242, 'desa', 'Teupin Panah', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(243, 'desa', 'Tumpok Ladang', NULL, 199, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(244, 'kecamatan', 'Meureubo', NULL, 1, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(245, 'desa', 'Balee', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(246, 'desa', 'Bukit Jaya', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(247, 'desa', 'Buloh', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(248, 'desa', 'Gunong Kleng', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(249, 'desa', 'Langung', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(250, 'desa', 'Mesjid Tuha', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(251, 'desa', 'Meureubo', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(252, 'desa', 'Pasi Aceh Baroh', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(253, 'desa', 'Pasi Aceh Tunong', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(254, 'desa', 'Pasi Mesjid', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(255, 'desa', 'Pasi Pinang', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(256, 'desa', 'Paya Baro Ranto Panyang', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(257, 'desa', 'Paya Peunaga', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(258, 'desa', 'Peunaga Cut Ujong', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(259, 'desa', 'Peunaga Pasi', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(260, 'desa', 'Peunaga Rayeuk', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(261, 'desa', 'Pucok Reudeup', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(262, 'desa', 'Pulo Teungoh Ranto Panyang', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(263, 'desa', 'Ranto Panyang Barat', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(264, 'desa', 'Ranto Panyang Timur', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(265, 'desa', 'Ranub Dong', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(266, 'desa', 'Reudeup', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(267, 'desa', 'Sumber Batu', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(268, 'desa', 'Ujong Drien', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(269, 'desa', 'Ujong Tanjung', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(270, 'desa', 'Ujong Tanoh Darat', NULL, 244, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(271, 'kecamatan', 'Pante Ceureumen', NULL, 1, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(272, 'desa', 'Alue Keumang', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(273, 'desa', 'Babah Iseung', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(274, 'desa', 'Babah Krueng Tep Lep', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(275, 'desa', 'Babah Lueng', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(276, 'desa', 'Berdikari', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(277, 'desa', 'Canggai', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(278, 'desa', 'Gunong Tarok', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(279, 'desa', 'Jambak', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(280, 'desa', 'Keutambang', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(281, 'desa', 'Keude Suak Awe', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(282, 'desa', 'Krueng Beukah', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(283, 'desa', 'Lango', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(284, 'desa', 'Lawet', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(285, 'desa', 'Lhok Guci', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(286, 'desa', 'Lhok Sari', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(287, 'desa', 'Manjeng', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(288, 'desa', 'Meunaung Kinco', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(289, 'desa', 'Pante Ceureumen', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(290, 'desa', 'Pulo Teungoh Manyang', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(291, 'desa', 'Sawang Rambot', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(292, 'desa', 'Seumantok', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(293, 'desa', 'Seumara', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(294, 'desa', 'Sikundo', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(295, 'desa', 'Suak Awe', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(296, 'desa', 'Tegal Sari', NULL, 271, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(297, 'kecamatan', 'Panton Reu', NULL, 1, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(298, 'desa', 'Antong', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(299, 'desa', 'Babah Krueng Manggie', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(300, 'desa', 'Baro Paya', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(301, 'desa', 'Blang Balee', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(302, 'desa', 'Blang Teungoh', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(303, 'desa', 'Cot Manggie', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(304, 'desa', 'Gampong Baro', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(305, 'desa', 'Gunung Mata Ie', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(306, 'desa', 'Kuala Manye', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(307, 'desa', 'Lek-Lek', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(308, 'desa', 'Manggie', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(309, 'desa', 'Meutulang', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(310, 'desa', 'Mugo Cut', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(311, 'desa', 'Mugo Rayeuk', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(312, 'desa', 'Paya Baro Meuko', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(313, 'desa', 'Sibintang', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(314, 'desa', 'Tamping', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(315, 'desa', 'Tuwi Buya', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(316, 'desa', 'Ujong Raja', NULL, 297, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(317, 'kecamatan', 'Sungai Mas', NULL, 1, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(318, 'desa', 'Drien Sibak', NULL, 317, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(319, 'desa', 'Gaseu', NULL, 317, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(320, 'desa', 'Geudong', NULL, 317, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(321, 'desa', 'Gleung', NULL, 317, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(322, 'desa', 'Gunong Buloh', NULL, 317, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(323, 'desa', 'Kajeung', NULL, 317, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(324, 'desa', 'Lancong', NULL, 317, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(325, 'desa', 'Lubok Beutong', NULL, 317, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(326, 'desa', 'Lueng Baro', NULL, 317, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(327, 'desa', 'Pungki', NULL, 317, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(328, 'desa', 'Ramitie', NULL, 317, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(329, 'desa', 'Sakuy', NULL, 317, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(330, 'desa', 'Sarah Perlak', NULL, 317, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(331, 'desa', 'Sipot', NULL, 317, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(332, 'desa', 'Tanoh Mirah', NULL, 317, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(333, 'desa', 'Tungkop', NULL, 317, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(334, 'desa', 'Tutut', NULL, 317, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39'),
	(335, 'desa', 'Tuwi Saya', NULL, 317, 1, '2025-10-18 00:41:39', '2025-10-18 00:41:39');

-- membuang struktur untuk table zynera_v2_db.reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `rating` int NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  KEY `reviews_product_id_foreign` (`product_id`),
  KEY `reviews_order_id_foreign` (`order_id`),
  CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.reviews: ~0 rows (lebih kurang)
DELETE FROM `reviews`;

-- membuang struktur untuk table zynera_v2_db.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.roles: ~0 rows (lebih kurang)
DELETE FROM `roles`;

-- membuang struktur untuk table zynera_v2_db.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.role_has_permissions: ~0 rows (lebih kurang)
DELETE FROM `role_has_permissions`;

-- membuang struktur untuk table zynera_v2_db.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.sessions: ~1 rows (lebih kurang)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('i7A0p7M7dqzmyLe1SeDCvieuOXbrWkhTbC16QVVr', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic0hmRGQ4QlQ2NTMwc01OdWFVM0R3a05Ja25wZGdUa0tiTEQ0bVFFYSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9lZHVjYXRpb24iO319', 1760782329),
	('o4xsCsMGFpp2aqkjKugGpWthlHOjLDXoJIZ5WZdC', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSkIxODREZWJKeU4wQXJxY2NLamVMVkhvbW5BRU1ncDk3NGs2OW1FMyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1760779863),
	('U7cSmT92s0XalMLaYM4l46HFDQObULdVvCVaL8G6', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicUVGZEVHM1VEN1Npdmp2Y1NEVGczeUlMZTR2bkVmaHJqUWMzV3hKbyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wcm9kdWN0cy9jcmVhdGUiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1760781350);

-- membuang struktur untuk table zynera_v2_db.temp_registrations
CREATE TABLE IF NOT EXISTS `temp_registrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registration_data` json NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `temp_registrations_email_unique` (`email`),
  KEY `temp_registrations_email_index` (`email`),
  KEY `temp_registrations_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.temp_registrations: ~0 rows (lebih kurang)
DELETE FROM `temp_registrations`;

-- membuang struktur untuk table zynera_v2_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `village` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `village_id` bigint unsigned DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` enum('konsumen','produsen','admin','kurir') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'konsumen',
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notification_preferences` json DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `profile_completed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_village_id_index` (`village_id`),
  KEY `users_google_id_index` (`google_id`),
  CONSTRAINT `users_village_id_foreign` FOREIGN KEY (`village_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.users: ~4 rows (lebih kurang)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `phone`, `address`, `village`, `district`, `village_id`, `avatar`, `user_type`, `is_verified`, `google_id`, `login_provider`, `notification_preferences`, `last_login_at`, `profile_completed`) VALUES
	(1, 'Administrator Zynera', 'admin@zynera.com', NULL, '$2y$12$KfOV7NEpP0xHxjlB3EOQQebLf2q5oi6/er3CsYLKUh4jZG/KQ0.Z6', 'aERVfZOCijcgBT8NpTk5kU3HYhvxk26oXOT9kQ9orzrRyBqkUCV8bB7Aobei', '2025-10-17 21:20:29', '2025-10-17 21:20:29', '+62812-3456-7890', 'Kantor Pusat Zynera, Jakarta', NULL, NULL, NULL, NULL, 'admin', 1, NULL, NULL, NULL, NULL, 0),
	(2, 'Penjual Minyak Zynera', 'penjual@zynera.com', NULL, '$2y$12$PnNou3isTP8gGCGqOyXaPePDpN7X2pTDJye990wMemA4pXxlbyeJ6', 'S5XF3OPjFZtxtBiFoRYcXPmFU0O4F7f6a4bNFy43YHm8ln8MBFUQFB0wEsI1', '2025-10-17 21:20:29', '2025-10-17 21:20:29', '+62813-4567-8901', 'Supplier Minyak Jelantah Jakarta', NULL, NULL, NULL, NULL, 'produsen', 1, NULL, NULL, NULL, NULL, 0),
	(3, 'Kurir Pengiriman Zynera', 'kurir@zynera.com', NULL, '$2y$12$oHSuoWXgNPrwKiUV7.Eqm.fhvgfZSTXI/JbHBAkwVqva3DnynHpba', NULL, '2025-10-17 21:20:29', '2025-10-17 21:20:29', '+62814-5678-9012', 'Driver Zynera Express', NULL, NULL, NULL, NULL, 'kurir', 1, NULL, NULL, NULL, NULL, 0),
	(4, 'Pembeli Zynera', 'pembeli@zynera.com', NULL, '$2y$12$gqlP1ykDomcieEISUOdIzeZZ7iAkQLYLkLU01bmjeasCqTL.HsiN6', NULL, '2025-10-17 21:20:29', '2025-10-17 21:20:29', '+62815-6789-0123', 'Customer Zynera Indonesia', NULL, NULL, NULL, NULL, 'konsumen', 1, NULL, NULL, NULL, NULL, 0);

-- membuang struktur untuk table zynera_v2_db.user_permissions
CREATE TABLE IF NOT EXISTS `user_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `route_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_allowed` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_route_unique` (`user_id`,`route_name`),
  KEY `user_permissions_user_id_route_name_index` (`user_id`,`route_name`),
  CONSTRAINT `user_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel zynera_v2_db.user_permissions: ~0 rows (lebih kurang)
DELETE FROM `user_permissions`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
