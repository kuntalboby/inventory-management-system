DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` tinytext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `parent_id` bigint(20) unsigned DEFAULT 0,
  `bulk_for_backend` enum('0','1') NOT NULL DEFAULT '0' COMMENT '1=>will not show to frontend users',
  `status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=>Active,1=>Inactive',
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`,`name`,`slug`,`description`,`image`,`parent_id`,`bulk_for_backend`,`status`,`is_deleted`,`created_at`,`updated_at`) VALUES ('1','Bulk Products','bulk-products','The products  if this category will not be visible in frontend.',NULL,NULL,'1','0','0','2025-08-17 08:05:05','2025-08-18 13:27:11');
INSERT INTO `categories` (`id`,`name`,`slug`,`description`,`image`,`parent_id`,`bulk_for_backend`,`status`,`is_deleted`,`created_at`,`updated_at`) VALUES ('3','Rice & Rice Products','rice-rice-products',NULL,NULL,NULL,'0','0','0','2025-08-18 08:33:29','2025-08-18 13:26:43');
INSERT INTO `categories` (`id`,`name`,`slug`,`description`,`image`,`parent_id`,`bulk_for_backend`,`status`,`is_deleted`,`created_at`,`updated_at`) VALUES ('4','MASALA','masala',NULL,NULL,NULL,'0','0','0','2025-08-18 08:34:23','2025-08-18 08:34:23');
INSERT INTO `categories` (`id`,`name`,`slug`,`description`,`image`,`parent_id`,`bulk_for_backend`,`status`,`is_deleted`,`created_at`,`updated_at`) VALUES ('5','Dal & Pulse','dal-pulse',NULL,NULL,NULL,'0','0','0','2025-08-18 11:35:46','2025-08-18 13:14:01');
INSERT INTO `categories` (`id`,`name`,`slug`,`description`,`image`,`parent_id`,`bulk_for_backend`,`status`,`is_deleted`,`created_at`,`updated_at`) VALUES ('6','GURO MASALA','guro-masala',NULL,NULL,NULL,'0','0','0','2025-08-18 11:37:14','2025-08-18 11:37:14');
INSERT INTO `categories` (`id`,`name`,`slug`,`description`,`image`,`parent_id`,`bulk_for_backend`,`status`,`is_deleted`,`created_at`,`updated_at`) VALUES ('7','Tel & Ghee','tel-ghee',NULL,NULL,NULL,'0','0','0','2025-08-18 11:37:55','2025-08-18 18:00:11');
INSERT INTO `categories` (`id`,`name`,`slug`,`description`,`image`,`parent_id`,`bulk_for_backend`,`status`,`is_deleted`,`created_at`,`updated_at`) VALUES ('8','Aata , Flours & Sooji','aata-flours-sooji',NULL,NULL,NULL,'0','0','0','2025-08-18 11:38:53','2025-08-18 13:23:03');
INSERT INTO `categories` (`id`,`name`,`slug`,`description`,`image`,`parent_id`,`bulk_for_backend`,`status`,`is_deleted`,`created_at`,`updated_at`) VALUES ('9','Salt , Sugar &  Jaggery','salt-sugar-jaggery',NULL,NULL,NULL,'0','0','0','2025-08-18 13:22:10','2025-08-18 13:22:10');
INSERT INTO `categories` (`id`,`name`,`slug`,`description`,`image`,`parent_id`,`bulk_for_backend`,`status`,`is_deleted`,`created_at`,`updated_at`) VALUES ('10','Tea & Drink','tea-drink',NULL,NULL,NULL,'0','0','0','2025-08-18 17:59:02','2025-08-18 17:59:50');
INSERT INTO `categories` (`id`,`name`,`slug`,`description`,`image`,`parent_id`,`bulk_for_backend`,`status`,`is_deleted`,`created_at`,`updated_at`) VALUES ('11','Miscellaneous','miscellaneous',NULL,NULL,NULL,'0','0','0','2025-09-01 08:40:27','2025-09-01 08:40:27');
