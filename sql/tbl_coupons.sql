CREATE TABLE `coupons` (
  `coupon_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_name` varchar(100) DEFAULT NULL,
  `client_name` varchar(100) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `title_ar` varchar(255) DEFAULT NULL,
  `code` varchar(20) DEFAULT NULL,
  `status` varchar(15) DEFAULT NULL,
  `tyoe` varchar(15) DEFAULT NULL,
  `discount` int(2) unsigned DEFAULT NULL,
  `category` varchar(75) DEFAULT NULL,
  `tag` varchar(75) DEFAULT NULL,
  `created_on` timestamp NULL DEFAULT current_timestamp(),
  `emp_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`coupon_id`)
);
