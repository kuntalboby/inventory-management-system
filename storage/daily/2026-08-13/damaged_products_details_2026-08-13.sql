DROP TABLE IF EXISTS `damaged_products_details`;
CREATE TABLE `damaged_products_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `go_down_product_id` bigint(20) unsigned NOT NULL,
  `packet_prodid` bigint(20) DEFAULT NULL COMMENT 'only for damaged in=2',
  `damaged_in` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1=>goDownProduct ,2=>Packaged Product',
  `damaged_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `quantity` double NOT NULL,
  `unit_id` bigint(20) unsigned NOT NULL,
  `no_of_packet` float(10,2) DEFAULT NULL COMMENT 'only for Packet damaged entry',
  `user_id` bigint(20) unsigned NOT NULL COMMENT 'Who took this action',
  `status` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '0=>Damaged,1=>Cleared from Godown',
  `comment` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `damaged_products_details_go_down_product_id_foreign` (`go_down_product_id`),
  KEY `damaged_products_details_unit_id_foreign` (`unit_id`),
  KEY `damaged_products_details_user_id_foreign` (`user_id`),
  CONSTRAINT `damaged_products_details_go_down_product_id_foreign` FOREIGN KEY (`go_down_product_id`) REFERENCES `go_down_products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `damaged_products_details_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  CONSTRAINT `damaged_products_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

