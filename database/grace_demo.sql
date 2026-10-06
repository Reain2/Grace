-- MySQL dump 10.13  Distrib 8.4.11, for Linux (aarch64)
--
-- Host: localhost    Database: laravel
-- ------------------------------------------------------
-- Server version	8.4.11

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
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `actor_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_id` bigint unsigned DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_target_type_target_id_index` (`target_type`,`target_id`),
  KEY `audit_logs_actor_id_created_at_index` (`actor_id`,`created_at`),
  CONSTRAINT `audit_logs_actor_id_foreign` FOREIGN KEY (`actor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,22,'proof.view','App\\Models\\VerificationRequest',13,NULL,'2026-10-06 10:30:45','2026-10-06 10:30:45'),(2,22,'verification.approve','App\\Models\\VerificationRequest',13,NULL,'2026-10-06 10:31:16','2026-10-06 10:31:16'),(3,1,'proof.view','App\\Models\\VerificationRequest',13,NULL,'2026-10-06 10:34:11','2026-10-06 10:34:11');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('super.admin@grace.test|192.168.65.1','i:1;',1791257694),('super.admin@grace.test|192.168.65.1:timer','i:1791257694;',1791257694);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_rsvps`
--

DROP TABLE IF EXISTS `event_rsvps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event_rsvps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'going',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_rsvps_event_id_user_id_unique` (`event_id`,`user_id`),
  KEY `event_rsvps_user_id_foreign` (`user_id`),
  KEY `event_rsvps_event_id_status_created_at_index` (`event_id`,`status`,`created_at`),
  CONSTRAINT `event_rsvps_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `event_rsvps_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_rsvps`
--

LOCK TABLES `event_rsvps` WRITE;
/*!40000 ALTER TABLE `event_rsvps` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_rsvps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tradition_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `starts_at` datetime NOT NULL,
  `ends_at` datetime NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quota` int unsigned DEFAULT NULL,
  `is_interfaith` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `events_created_by_foreign` (`created_by`),
  KEY `events_status_starts_at_index` (`status`,`starts_at`),
  KEY `events_tradition_id_starts_at_index` (`tradition_id`,`starts_at`),
  CONSTRAINT `events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `events_tradition_id_foreign` FOREIGN KEY (`tradition_id`) REFERENCES `traditions` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
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
-- Table structure for table `holy_days`
--

DROP TABLE IF EXISTS `holy_days`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `holy_days` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tradition_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `is_annual` tinyint(1) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `holy_days_tradition_id_date_index` (`tradition_id`,`date`),
  CONSTRAINT `holy_days_tradition_id_foreign` FOREIGN KEY (`tradition_id`) REFERENCES `traditions` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `holy_days`
--

LOCK TABLES `holy_days` WRITE;
/*!40000 ALTER TABLE `holy_days` DISABLE KEYS */;
INSERT INTO `holy_days` VALUES (1,1,'Hari penting Katolik DEMO','2026-12-06',1,'Tanggal contoh untuk pengujian kalender.','Konten demo Grace','2026-10-06 10:14:39','2026-10-06 10:14:39'),(2,2,'Hari penting Kristen Protestan DEMO','2026-12-06',1,'Tanggal contoh untuk pengujian kalender.','Konten demo Grace','2026-10-06 10:14:39','2026-10-06 10:14:39'),(3,3,'Hari penting Buddha DEMO','2026-12-06',1,'Tanggal contoh untuk pengujian kalender.','Konten demo Grace','2026-10-06 10:14:40','2026-10-06 10:14:40'),(4,4,'Hari penting Hindu DEMO','2026-12-06',1,'Tanggal contoh untuk pengujian kalender.','Konten demo Grace','2026-10-06 10:14:41','2026-10-06 10:14:41'),(5,5,'Hari penting Konghucu DEMO','2026-12-06',1,'Tanggal contoh untuk pengujian kalender.','Konten demo Grace','2026-10-06 10:14:42','2026-10-06 10:14:42'),(6,6,'Hari penting Islam DEMO','2026-12-06',1,'Tanggal contoh untuk pengujian kalender.','Konten demo Grace','2026-10-06 10:14:42','2026-10-06 10:14:42');
/*!40000 ALTER TABLE `holy_days` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `journal_entries`
--

DROP TABLE IF EXISTS `journal_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `journal_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `entry_date` date NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `journal_entries_user_id_entry_date_index` (`user_id`,`entry_date`),
  CONSTRAINT `journal_entries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `journal_entries`
--

LOCK TABLES `journal_entries` WRITE;
/*!40000 ALTER TABLE `journal_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `journal_entries` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_10_01_000003_create_traditions_table',1),(5,'2026_10_01_000004_add_grace_fields_to_users_table',1),(6,'2026_10_01_000005_create_verification_requests_table',1),(7,'2026_10_01_000006_create_quotes_tables',1),(8,'2026_10_01_000007_create_reading_plan_tables',1),(9,'2026_10_01_000008_create_reading_progress_tables',1),(10,'2026_10_01_000009_make_proof_path_nullable',1),(11,'2026_10_01_000010_add_active_flag_to_verification_requests',1),(12,'2026_10_01_000011_create_audit_logs_table',1),(13,'2026_10_01_000012_add_reading_plan_versions',1),(14,'2026_10_01_000013_create_reminders_table',1),(15,'2026_10_01_000014_create_events_tables',1),(16,'2026_10_01_000015_create_journal_entries_table',1),(17,'2026_10_01_000016_create_holy_days_table',1),(18,'2026_10_01_000017_create_notifications_table',1),(19,'2026_10_01_000018_create_reminder_deliveries_table',1),(20,'2026_10_01_000019_add_annual_holy_days',1),(21,'2026_10_01_000019_add_reading_items_snapshot',1);
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
INSERT INTO `notifications` VALUES ('2630a88c-7aad-40ca-8a03-d57e36dd3675','App\\Notifications\\GraceDatabaseNotification','App\\Models\\User',26,'{\"title\":\"Verifikasi disetujui\",\"message\":\"Akunmu sudah dapat memakai fitur interaktif Grace.\"}','2026-10-06 10:31:54','2026-10-06 10:31:16','2026-10-06 10:31:54');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
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
-- Table structure for table `quote_bookmarks`
--

DROP TABLE IF EXISTS `quote_bookmarks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quote_bookmarks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `quote_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `quote_bookmarks_user_id_quote_id_unique` (`user_id`,`quote_id`),
  KEY `quote_bookmarks_quote_id_foreign` (`quote_id`),
  CONSTRAINT `quote_bookmarks_quote_id_foreign` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quote_bookmarks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quote_bookmarks`
--

LOCK TABLES `quote_bookmarks` WRITE;
/*!40000 ALTER TABLE `quote_bookmarks` DISABLE KEYS */;
INSERT INTO `quote_bookmarks` VALUES (1,26,6,NULL,NULL);
/*!40000 ALTER TABLE `quote_bookmarks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotes`
--

DROP TABLE IF EXISTS `quotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quotes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tradition_id` bigint unsigned NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheduled_for` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `quotes_tradition_id_scheduled_for_unique` (`tradition_id`,`scheduled_for`),
  KEY `quotes_created_by_foreign` (`created_by`),
  KEY `quotes_tradition_id_is_active_index` (`tradition_id`,`is_active`),
  CONSTRAINT `quotes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `quotes_tradition_id_foreign` FOREIGN KEY (`tradition_id`) REFERENCES `traditions` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotes`
--

LOCK TABLES `quotes` WRITE;
/*!40000 ALTER TABLE `quotes` DISABLE KEYS */;
INSERT INTO `quotes` VALUES (1,1,'Renungan demo untuk tradisi Katolik.','Konten demo Grace',NULL,1,2,'2026-10-06 10:14:38','2026-10-06 10:14:38'),(2,2,'Renungan demo untuk tradisi Kristen Protestan.','Konten demo Grace',NULL,1,6,'2026-10-06 10:14:39','2026-10-06 10:14:39'),(3,3,'Renungan demo untuk tradisi Buddha.','Konten demo Grace',NULL,1,10,'2026-10-06 10:14:40','2026-10-06 10:14:40'),(4,4,'Renungan demo untuk tradisi Hindu.','Konten demo Grace',NULL,1,14,'2026-10-06 10:14:40','2026-10-06 10:14:40'),(5,5,'Renungan demo untuk tradisi Konghucu.','Konten demo Grace',NULL,1,18,'2026-10-06 10:14:41','2026-10-06 10:14:41'),(6,6,'Renungan demo untuk tradisi Islam.','Konten demo Grace',NULL,1,22,'2026-10-06 10:14:42','2026-10-06 10:14:42');
/*!40000 ALTER TABLE `quotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reading_plan_items`
--

DROP TABLE IF EXISTS `reading_plan_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reading_plan_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reading_plan_id` bigint unsigned NOT NULL,
  `day_number` smallint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reading_plan_items_reading_plan_id_day_number_unique` (`reading_plan_id`,`day_number`),
  CONSTRAINT `reading_plan_items_reading_plan_id_foreign` FOREIGN KEY (`reading_plan_id`) REFERENCES `reading_plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reading_plan_items`
--

LOCK TABLES `reading_plan_items` WRITE;
/*!40000 ALTER TABLE `reading_plan_items` DISABLE KEYS */;
INSERT INTO `reading_plan_items` VALUES (1,1,1,'Hari pertama','Rujukan demo 1','2026-10-06 10:14:39','2026-10-06 10:14:39'),(2,1,2,'Hari kedua','Rujukan demo 2','2026-10-06 10:14:39','2026-10-06 10:14:39'),(3,1,3,'Hari ketiga','Rujukan demo 3','2026-10-06 10:14:39','2026-10-06 10:14:39'),(4,2,1,'Hari pertama','Rujukan demo 1','2026-10-06 10:14:39','2026-10-06 10:14:39'),(5,2,2,'Hari kedua','Rujukan demo 2','2026-10-06 10:14:39','2026-10-06 10:14:39'),(6,2,3,'Hari ketiga','Rujukan demo 3','2026-10-06 10:14:39','2026-10-06 10:14:39'),(7,3,1,'Hari pertama','Rujukan demo 1','2026-10-06 10:14:40','2026-10-06 10:14:40'),(8,3,2,'Hari kedua','Rujukan demo 2','2026-10-06 10:14:40','2026-10-06 10:14:40'),(9,3,3,'Hari ketiga','Rujukan demo 3','2026-10-06 10:14:40','2026-10-06 10:14:40'),(10,4,1,'Hari pertama','Rujukan demo 1','2026-10-06 10:14:41','2026-10-06 10:14:41'),(11,4,2,'Hari kedua','Rujukan demo 2','2026-10-06 10:14:41','2026-10-06 10:14:41'),(12,4,3,'Hari ketiga','Rujukan demo 3','2026-10-06 10:14:41','2026-10-06 10:14:41'),(13,5,1,'Hari pertama','Rujukan demo 1','2026-10-06 10:14:42','2026-10-06 10:14:42'),(14,5,2,'Hari kedua','Rujukan demo 2','2026-10-06 10:14:42','2026-10-06 10:14:42'),(15,5,3,'Hari ketiga','Rujukan demo 3','2026-10-06 10:14:42','2026-10-06 10:14:42'),(16,6,1,'Hari pertama','Rujukan demo 1','2026-10-06 10:14:42','2026-10-06 10:14:42'),(17,6,2,'Hari kedua','Rujukan demo 2','2026-10-06 10:14:42','2026-10-06 10:14:42'),(18,6,3,'Hari ketiga','Rujukan demo 3','2026-10-06 10:14:42','2026-10-06 10:14:42');
/*!40000 ALTER TABLE `reading_plan_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reading_plans`
--

DROP TABLE IF EXISTS `reading_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reading_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tradition_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `total_days` smallint unsigned NOT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `version` int unsigned NOT NULL DEFAULT '1',
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reading_plans_created_by_foreign` (`created_by`),
  KEY `reading_plans_tradition_id_is_active_index` (`tradition_id`,`is_active`),
  CONSTRAINT `reading_plans_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `reading_plans_tradition_id_foreign` FOREIGN KEY (`tradition_id`) REFERENCES `traditions` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reading_plans`
--

LOCK TABLES `reading_plans` WRITE;
/*!40000 ALTER TABLE `reading_plans` DISABLE KEYS */;
INSERT INTO `reading_plans` VALUES (1,1,'Rencana awal Katolik','Rencana bacaan contoh untuk pengujian fitur Grace.',3,'Konten demo Grace',1,1,2,'2026-10-06 10:14:38','2026-10-06 10:14:38'),(2,2,'Rencana awal Kristen Protestan','Rencana bacaan contoh untuk pengujian fitur Grace.',3,'Konten demo Grace',1,1,6,'2026-10-06 10:14:39','2026-10-06 10:14:39'),(3,3,'Rencana awal Buddha','Rencana bacaan contoh untuk pengujian fitur Grace.',3,'Konten demo Grace',1,1,10,'2026-10-06 10:14:40','2026-10-06 10:14:40'),(4,4,'Rencana awal Hindu','Rencana bacaan contoh untuk pengujian fitur Grace.',3,'Konten demo Grace',1,1,14,'2026-10-06 10:14:40','2026-10-06 10:14:40'),(5,5,'Rencana awal Konghucu','Rencana bacaan contoh untuk pengujian fitur Grace.',3,'Konten demo Grace',1,1,18,'2026-10-06 10:14:41','2026-10-06 10:14:41'),(6,6,'Rencana awal Islam','Rencana bacaan contoh untuk pengujian fitur Grace.',3,'Konten demo Grace',1,1,22,'2026-10-06 10:14:42','2026-10-06 10:14:42');
/*!40000 ALTER TABLE `reading_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reading_progress`
--

DROP TABLE IF EXISTS `reading_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reading_progress` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_reading_plan_id` bigint unsigned NOT NULL,
  `reading_plan_item_id` bigint unsigned NOT NULL,
  `checked_on` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reading_progress_enrollment_item_unique` (`user_reading_plan_id`,`reading_plan_item_id`),
  KEY `reading_progress_reading_plan_item_id_foreign` (`reading_plan_item_id`),
  KEY `reading_progress_enrollment_date_index` (`user_reading_plan_id`,`checked_on`),
  CONSTRAINT `reading_progress_reading_plan_item_id_foreign` FOREIGN KEY (`reading_plan_item_id`) REFERENCES `reading_plan_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reading_progress_user_reading_plan_id_foreign` FOREIGN KEY (`user_reading_plan_id`) REFERENCES `user_reading_plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reading_progress`
--

LOCK TABLES `reading_progress` WRITE;
/*!40000 ALTER TABLE `reading_progress` DISABLE KEYS */;
/*!40000 ALTER TABLE `reading_progress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reminder_deliveries`
--

DROP TABLE IF EXISTS `reminder_deliveries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reminder_deliveries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reminder_id` bigint unsigned NOT NULL,
  `delivery_date` date NOT NULL,
  `delivery_time` time NOT NULL,
  `channel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reminder_delivery_dedup` (`reminder_id`,`delivery_date`,`delivery_time`,`channel`),
  CONSTRAINT `reminder_deliveries_reminder_id_foreign` FOREIGN KEY (`reminder_id`) REFERENCES `reminders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reminder_deliveries`
--

LOCK TABLES `reminder_deliveries` WRITE;
/*!40000 ALTER TABLE `reminder_deliveries` DISABLE KEYS */;
/*!40000 ALTER TABLE `reminder_deliveries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reminders`
--

DROP TABLE IF EXISTS `reminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reminders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remind_at` time NOT NULL,
  `days` json NOT NULL,
  `channel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web',
  `timezone` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Asia/Jakarta',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reminders_user_id_is_active_index` (`user_id`,`is_active`),
  CONSTRAINT `reminders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reminders`
--

LOCK TABLES `reminders` WRITE;
/*!40000 ALTER TABLE `reminders` DISABLE KEYS */;
/*!40000 ALTER TABLE `reminders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('PJCWYUBWhs5DUDcgCeOF4oKBqWnFm2QSjmbeiOov',26,'192.168.65.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/27.0 Safari/605.1.15','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNUh0dDBXdW14SW9hc0U0bmpONERkV2x6a2lHWGhHTzJZaDc4dU1HUyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly9sb2NhbGhvc3QvYXBwIjtzOjU6InJvdXRlIjtzOjE0OiJ1c2VyLmRhc2hib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI2O3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJlNmQ2YWI1NmY1NWQyYmJlZDMwNTdjNGI4MGExMzAyNmJmNDliN2I5NTVmMjYxM2FjZGM2NDU1MDg1YjM0YzBiIjt9',1791259773);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `traditions`
--

DROP TABLE IF EXISTS `traditions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `traditions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `traditions_name_unique` (`name`),
  UNIQUE KEY `traditions_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `traditions`
--

LOCK TABLES `traditions` WRITE;
/*!40000 ALTER TABLE `traditions` DISABLE KEYS */;
INSERT INTO `traditions` VALUES (1,'Katolik','katolik',1,'2026-10-06 10:14:38','2026-10-06 10:14:38'),(2,'Kristen Protestan','kristen-protestan',1,'2026-10-06 10:14:38','2026-10-06 10:14:38'),(3,'Buddha','buddha',1,'2026-10-06 10:14:38','2026-10-06 10:14:38'),(4,'Hindu','hindu',1,'2026-10-06 10:14:38','2026-10-06 10:14:38'),(5,'Konghucu','konghucu',1,'2026-10-06 10:14:38','2026-10-06 10:14:38'),(6,'Islam','islam',1,'2026-10-06 10:14:38','2026-10-06 10:14:38');
/*!40000 ALTER TABLE `traditions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_reading_plans`
--

DROP TABLE IF EXISTS `user_reading_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_reading_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `reading_plan_id` bigint unsigned NOT NULL,
  `plan_version` int unsigned NOT NULL DEFAULT '1',
  `items_snapshot` json DEFAULT NULL,
  `started_on` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_reading_plans_user_id_reading_plan_id_unique` (`user_id`,`reading_plan_id`),
  KEY `user_reading_plans_reading_plan_id_foreign` (`reading_plan_id`),
  CONSTRAINT `user_reading_plans_reading_plan_id_foreign` FOREIGN KEY (`reading_plan_id`) REFERENCES `reading_plans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_reading_plans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_reading_plans`
--

LOCK TABLES `user_reading_plans` WRITE;
/*!40000 ALTER TABLE `user_reading_plans` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_reading_plans` ENABLE KEYS */;
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
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `tradition_id` bigint unsigned DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `verification_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `timezone` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Asia/Jakarta',
  `consent_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_tradition_id_index` (`role`,`tradition_id`),
  KEY `users_tradition_id_verification_status_index` (`tradition_id`,`verification_status`),
  CONSTRAINT `users_tradition_id_foreign` FOREIGN KEY (`tradition_id`) REFERENCES `traditions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Grace Superadmin DEMO','superadmin@grace.test','2026-10-06 10:14:38','$2y$12$RxeRl.YqB45La4fB9v8o0.H37gIUJW4KhhOJTj1FnBTFdjQI6UqRq','superadmin',NULL,'active','approved','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:38','2026-10-06 10:14:38'),(2,'Admin Katolik DEMO','admin.katolik@grace.test','2026-10-06 10:14:38','$2y$12$ghvW2E6tE/xMYBt.olMtBemebaB/kpFRf/D5hbg/YvGrHrY4cGZDG','religion_admin',1,'active','approved','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:38','2026-10-06 10:14:38'),(3,'User Katolik DEMO','user.katolik@grace.test','2026-10-06 10:14:38','$2y$12$0c7jAwSj/hHKrkXafNb1/e73AKlf6ZEuHwlPBnw4ABckiLOvpjAZe','user',1,'active','approved','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:38','2026-10-06 10:14:38'),(4,'Pending Katolik DEMO','pending.katolik@grace.test','2026-10-06 10:14:39','$2y$12$rJ/zfDaYa7z73B5ueDtPqOn/lwbCTqIwZnVBbk1CvAxGCRTK7tTu.','user',1,'active','pending','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:39','2026-10-06 10:14:39'),(5,'Rejected Katolik DEMO','rejected.katolik@grace.test','2026-10-06 10:14:39','$2y$12$vbkrXraWOs0YpwIwZvPMD.nZyUwIF3cU8bLsafsSZ8Ja82tFU6Tbe','user',1,'active','rejected','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:39','2026-10-06 10:14:39'),(6,'Admin Kristen Protestan DEMO','admin.kristen-protestan@grace.test','2026-10-06 10:14:39','$2y$12$ViQoILQdwcyCsRycOBZcYOclzTkMiCNYc7pgMez.NST5I1TwrEgP.','religion_admin',2,'active','approved','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:39','2026-10-06 10:14:39'),(7,'User Kristen Protestan DEMO','user.kristen-protestan@grace.test','2026-10-06 10:14:39','$2y$12$5llzqPaBGrRVHxgXDYFAgu9O0n18Yb56T1pl0fvAG.a8i6jFgEb5m','user',2,'active','approved','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:39','2026-10-06 10:14:39'),(8,'Pending Kristen Protestan DEMO','pending.kristen-protestan@grace.test','2026-10-06 10:14:39','$2y$12$a9XFulikJKNXo6NLMeX4bu7ta3iYCsQjiWVByJT86jhG4YMwln/Om','user',2,'active','pending','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:39','2026-10-06 10:14:39'),(9,'Rejected Kristen Protestan DEMO','rejected.kristen-protestan@grace.test','2026-10-06 10:14:39','$2y$12$GmLpU8pkl8nxa8Rjbik5VOTRRBHPrYy92m9bJDBGm091jUluYSSZ2','user',2,'active','rejected','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:39','2026-10-06 10:14:39'),(10,'Admin Buddha DEMO','admin.buddha@grace.test','2026-10-06 10:14:40','$2y$12$OKCXUAXf/OW8/5i4qNaq8OgetXbVG3SGFNo9zis0x8KT//dSB5H6i','religion_admin',3,'active','approved','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:40','2026-10-06 10:14:40'),(11,'User Buddha DEMO','user.buddha@grace.test','2026-10-06 10:14:40','$2y$12$k6fxPJD7Jx/5fie4xtbFbecf/j8IUbKforytWObJIpfVLKQumPpya','user',3,'active','approved','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:40','2026-10-06 10:14:40'),(12,'Pending Buddha DEMO','pending.buddha@grace.test','2026-10-06 10:14:40','$2y$12$k70LAInnnvBvs8eZbskWJ.h6RExK6iPxXL52Qf86m5U1sLcDGpCW.','user',3,'active','pending','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:40','2026-10-06 10:14:40'),(13,'Rejected Buddha DEMO','rejected.buddha@grace.test','2026-10-06 10:14:40','$2y$12$WEUp5qYHqG/oyI5/DSCmL.cZAVpvZkdmr93UDX.1trRbe1yMWeCLC','user',3,'active','rejected','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:40','2026-10-06 10:14:40'),(14,'Admin Hindu DEMO','admin.hindu@grace.test','2026-10-06 10:14:40','$2y$12$ydc9.yFRK8qH3wQvgcyTDOeb61ItDqfaNWqbE9UUvInt8xrom8jSK','religion_admin',4,'active','approved','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:40','2026-10-06 10:14:40'),(15,'User Hindu DEMO','user.hindu@grace.test','2026-10-06 10:14:41','$2y$12$GmcntJVoLLEorZiK2NvymeFF8JFfsGNuLFnuTrIfZpjrW/2LCVm0W','user',4,'active','approved','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:41','2026-10-06 10:14:41'),(16,'Pending Hindu DEMO','pending.hindu@grace.test','2026-10-06 10:14:41','$2y$12$4PQKS46HgWUwuGYS1Jrfg.VNwg5RCQF6VSEhch75MiDDJY7DCUKfy','user',4,'active','pending','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:41','2026-10-06 10:14:41'),(17,'Rejected Hindu DEMO','rejected.hindu@grace.test','2026-10-06 10:14:41','$2y$12$c21wiKuheVDRvWUsfSgSK.NrVBl5rbTl8.Q4yZJHxorC5VV5HVA0q','user',4,'active','rejected','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:41','2026-10-06 10:14:41'),(18,'Admin Konghucu DEMO','admin.konghucu@grace.test','2026-10-06 10:14:41','$2y$12$kt.GMnxbomSYX7C/cfTEP.BQsKVNZPuVQ.l3Px5v1z3uLXQWy3b42','religion_admin',5,'active','approved','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:41','2026-10-06 10:14:41'),(19,'User Konghucu DEMO','user.konghucu@grace.test','2026-10-06 10:14:41','$2y$12$CkhhFj/F.WjYDKSRyWFpYe23d2oebBxhBTZX4ZhaNDShyZ4C4lTgC','user',5,'active','approved','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:41','2026-10-06 10:14:41'),(20,'Pending Konghucu DEMO','pending.konghucu@grace.test','2026-10-06 10:14:41','$2y$12$W5FQXYDJQG9rDRmjwttMOeIk4k4Lzvt2U76HC5jHzR2EQT0xya4eu','user',5,'active','pending','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:41','2026-10-06 10:14:41'),(21,'Rejected Konghucu DEMO','rejected.konghucu@grace.test','2026-10-06 10:14:42','$2y$12$bN1UJXR44e3kgFIShqAzKu.gre0/FSbXiVdzi8dl9xvYogB3yEK/6','user',5,'active','rejected','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:42','2026-10-06 10:14:42'),(22,'Admin Islam DEMO','admin.islam@grace.test','2026-10-06 10:14:42','$2y$12$z7IV60ikRoFhgxH14Tt1OecDIN26jsx4ZZpaBPwwhQoB9ByKlgaX.','religion_admin',6,'active','approved','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:42','2026-10-06 10:14:42'),(23,'User Islam DEMO','user.islam@grace.test','2026-10-06 10:14:42','$2y$12$TIkLrXkGADH7lPRnbzf6m.D6rzQi6FWf7vP3zBHtENo77YRu1k1/G','user',6,'active','approved','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:42','2026-10-06 10:14:42'),(24,'Pending Islam DEMO','pending.islam@grace.test','2026-10-06 10:14:42','$2y$12$Fep6iZhWidWNwhfW144Zlu8KjWV61DX5Zw2oqhtCelOgPbI9ujTMu','user',6,'active','pending','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:42','2026-10-06 10:14:42'),(25,'Rejected Islam DEMO','rejected.islam@grace.test','2026-10-06 10:14:42','$2y$12$bJA6JFXiFKvRqq8LgQRFwuZp2ISi5z2u2zeNhtE9XgnH9EWM6tpzu','user',6,'active','rejected','Asia/Jakarta',NULL,NULL,'2026-10-06 10:14:42','2026-10-06 10:14:42'),(26,'gustian','gustian@gmail.com',NULL,'$2y$12$ChZKB/wPmaWtKpc2vcxudOD7ifr/SqH48tBHegLVWZdaPO1zCTR1e','user',6,'active','approved','Asia/Jakarta','2026-10-06 10:30:15',NULL,'2026-10-06 10:30:15','2026-10-06 10:31:16');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verification_requests`
--

DROP TABLE IF EXISTS `verification_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `verification_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `tradition_id` bigint unsigned NOT NULL,
  `proof_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dummy_image',
  `proof_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `attempts` tinyint unsigned NOT NULL DEFAULT '0',
  `claimed_by` bigint unsigned DEFAULT NULL,
  `claimed_at` timestamp NULL DEFAULT NULL,
  `decided_by` bigint unsigned DEFAULT NULL,
  `decided_at` timestamp NULL DEFAULT NULL,
  `reject_reason` text COLLATE utf8mb4_unicode_ci,
  `proof_delete_at` timestamp NULL DEFAULT NULL,
  `proof_deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `verification_requests_claimed_by_foreign` (`claimed_by`),
  KEY `verification_requests_decided_by_foreign` (`decided_by`),
  KEY `verification_requests_tradition_id_status_index` (`tradition_id`,`status`),
  KEY `verification_requests_status_created_at_index` (`status`,`created_at`),
  KEY `verification_requests_user_id_index` (`user_id`),
  KEY `verification_user_active_index` (`user_id`,`is_active`),
  CONSTRAINT `verification_requests_claimed_by_foreign` FOREIGN KEY (`claimed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `verification_requests_decided_by_foreign` FOREIGN KEY (`decided_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `verification_requests_tradition_id_foreign` FOREIGN KEY (`tradition_id`) REFERENCES `traditions` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `verification_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verification_requests`
--

LOCK TABLES `verification_requests` WRITE;
/*!40000 ALTER TABLE `verification_requests` DISABLE KEYS */;
INSERT INTO `verification_requests` VALUES (1,4,1,'dummy_image','verification-proofs/seed-pending-katolik.png','pending',1,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-06 10:14:39','2026-10-06 10:14:39'),(2,5,1,'dummy_image','verification-proofs/seed-rejected-katolik.png','rejected',1,1,NULL,NULL,NULL,NULL,'Demo rejection untuk menguji resubmit.',NULL,NULL,'2026-10-06 10:14:39','2026-10-06 10:14:39'),(3,8,2,'dummy_image','verification-proofs/seed-pending-kristen-protestan.png','pending',1,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-06 10:14:39','2026-10-06 10:14:39'),(4,9,2,'dummy_image','verification-proofs/seed-rejected-kristen-protestan.png','rejected',1,1,NULL,NULL,NULL,NULL,'Demo rejection untuk menguji resubmit.',NULL,NULL,'2026-10-06 10:14:39','2026-10-06 10:14:39'),(5,12,3,'dummy_image','verification-proofs/seed-pending-buddha.png','pending',1,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-06 10:14:40','2026-10-06 10:14:40'),(6,13,3,'dummy_image','verification-proofs/seed-rejected-buddha.png','rejected',1,1,NULL,NULL,NULL,NULL,'Demo rejection untuk menguji resubmit.',NULL,NULL,'2026-10-06 10:14:40','2026-10-06 10:14:40'),(7,16,4,'dummy_image','verification-proofs/seed-pending-hindu.png','pending',1,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-06 10:14:41','2026-10-06 10:14:41'),(8,17,4,'dummy_image','verification-proofs/seed-rejected-hindu.png','rejected',1,1,NULL,NULL,NULL,NULL,'Demo rejection untuk menguji resubmit.',NULL,NULL,'2026-10-06 10:14:41','2026-10-06 10:14:41'),(9,20,5,'dummy_image','verification-proofs/seed-pending-konghucu.png','pending',1,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-06 10:14:41','2026-10-06 10:14:41'),(10,21,5,'dummy_image','verification-proofs/seed-rejected-konghucu.png','rejected',1,1,NULL,NULL,NULL,NULL,'Demo rejection untuk menguji resubmit.',NULL,NULL,'2026-10-06 10:14:42','2026-10-06 10:14:42'),(11,24,6,'dummy_image','verification-proofs/seed-pending-islam.png','pending',1,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-06 10:14:42','2026-10-06 10:14:42'),(12,25,6,'dummy_image','verification-proofs/seed-rejected-islam.png','rejected',1,1,NULL,NULL,NULL,NULL,'Demo rejection untuk menguji resubmit.',NULL,NULL,'2026-10-06 10:14:42','2026-10-06 10:14:42'),(13,26,6,'dummy_image','verification-proofs/5PWNn5t3s85B4N3tm1QnjotsAryFje4D9CA8f5Gf.jpg','approved',0,0,22,'2026-10-06 10:31:08',22,'2026-10-06 10:31:16',NULL,'2026-10-13 10:31:16',NULL,'2026-10-06 10:30:15','2026-10-06 10:31:16');
/*!40000 ALTER TABLE `verification_requests` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-06  4:39:36
