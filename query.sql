ALTER TABLE `quotes_products` CHANGE `sub_category` `sub_category` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `quotes_products` CHANGE `product` `product` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `brand_ids` `brand_ids` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `other_preferred_brand` `other_preferred_brand` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `grade_ids` `grade_ids` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `other_preferred_grade` `other_preferred_grade` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;


-- 4-08-2021
ALTER TABLE `user_addresses` ADD `user_id` INT(11) NOT NULL AFTER `id`;
TRUNCATE `blitznet`.`user_addresses`;
-- end 4-08-2021

-- 5-08-2021
ALTER TABLE `suppliers` CHANGE `pic` `pic` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `suppliers` CHANGE `logo` `logo` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `suppliers` ADD `description` TEXT NULL AFTER `logo`;
ALTER TABLE `suppliers` CHANGE `pic` `catalog` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `suppliers` ADD `contact_person_name` VARCHAR(512) NOT NULL AFTER `address`, ADD `contact_person_email` VARCHAR(512) NOT NULL AFTER `contact_person_name`, ADD `contact_person_phone` VARCHAR(512) NOT NULL AFTER `contact_person_email`;
ALTER TABLE `suppliers` CHANGE `website` `website` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
-- end 4-08-2021


-- 06-08-2021
ALTER TABLE `suppliers` ADD `pricing` VARCHAR(512) NULL AFTER `catalog`, ADD `product` VARCHAR(512) NULL AFTER `pricing`, ADD `commercialCondition` VARCHAR(512) NULL AFTER `product`;
-- end 06-08-2021

--10-08-2021
CREATE TABLE `user_activities` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `activity` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_activities`
--
ALTER TABLE `user_activities`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_activities`
--
ALTER TABLE `user_activities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `quotes_products` CHANGE `reference_number` `reference_number` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;

-- End 10-08-2021

-- 11-08-2021 and  12-08-21

RENAME TABLE `quotes` TO `rfqs`;

RENAME TABLE `user_quotes` TO `user_rfqs`;

RENAME TABLE `quotes_status` TO `rfq_status`;

RENAME TABLE `quotes_products` TO `rfq_products`;

ALTER TABLE `rfqs` ADD `reference_number` VARCHAR(512) NULL AFTER `status_id`;

ALTER TABLE `rfq_products` DROP `reference_number`;

ALTER TABLE `rfq_products` CHANGE `quotes_id` `rfq_id` INT NOT NULL;

ALTER TABLE `user_rfqs` CHANGE `quotes_id` `rfq_id` INT NOT NULL;

-- End 11-08-2021 and  12-08-21
ALTER TABLE `suppliers` ADD `accepted_terms` BOOLEAN NOT NULL DEFAULT TRUE AFTER `commercialCondition`;

-- 13-08-2021
ALTER TABLE `categories_units` ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `unit_id`;
ALTER TABLE `categories_units` ADD `updated_at` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`;
ALTER TABLE `categories_units` ADD `is_deleted` BOOLEAN NOT NULL DEFAULT FALSE AFTER `updated_at`;


CREATE TABLE `other_charges` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(512) NOT NULL , `description` VARCHAR(512) NULL , `type` BOOLEAN NOT NULL DEFAULT FALSE , `charges_value` DECIMAL NOT NULL , `value_on` BOOLEAN NOT NULL DEFAULT FALSE , `status` BOOLEAN NOT NULL DEFAULT FALSE , `addition_substraction` BOOLEAN NOT NULL DEFAULT FALSE , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `is_deleted` BOOLEAN NOT NULL DEFAULT FALSE , PRIMARY KEY (`id`)) ENGINE = InnoDB;
CREATE TABLE `supplier_charges` ( `id` INT NOT NULL AUTO_INCREMENT , `supplier_id` INT(11) NOT NULL , `other_charges_id` INT(11) NOT NULL , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `is_deleted` BOOLEAN NOT NULL DEFAULT FALSE , PRIMARY KEY (`id`)) ENGINE = InnoDB;
CREATE TABLE `quotes` ( `id` INT NOT NULL AUTO_INCREMENT , `quote_number` VARCHAR(512) NOT NULL , `supplier_id` INT(11) NOT NULL , `rfq_id` INT(11) NOT NULL , `delivery_days` INT(11) NOT NULL , `valid_till` DATE NOT NULL , `amount` DECIMAL NOT NULL , `final_amount` DECIMAL NOT NULL , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `is_deleted` BOOLEAN NOT NULL DEFAULT FALSE , PRIMARY KEY (`id`)) ENGINE = InnoDB;
CREATE TABLE `quotes_charges_with_amounts` ( `id` INT NOT NULL AUTO_INCREMENT , `quote_id` INT(11) NOT NULL , `charge_name` VARCHAR(512) NOT NULL , `value_on` BOOLEAN NOT NULL DEFAULT FALSE , `addition_substraction` BOOLEAN NOT NULL DEFAULT FALSE , `type` BOOLEAN NOT NULL DEFAULT FALSE , `charge_value` DECIMAL(11) NOT NULL , `charge_amount` DECIMAL(11) NOT NULL , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `is_deleted` BOOLEAN NOT NULL DEFAULT FALSE , PRIMARY KEY (`id`)) ENGINE = InnoDB;
-- 13-08-2021

-- 17-08-2021


CREATE TABLE `rfqs_call` (
  `id` int NOT NULL,
  `rfq_id` int NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB;


-- 18-08-2021 and 19-08-2021
ALTER TABLE `other_charges` CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '1';
ALTER TABLE `supplier_products` CHANGE `description` `description` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `supplier_products` CHANGE `product_terms` `product_terms` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `supplier_products` CHANGE `product_catalog` `product_catalog` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `supplier_products` ADD `discount` VARCHAR(215) NULL AFTER `product_catalog`, ADD `discounted_price` VARCHAR(215) NULL AFTER `discount`;
ALTER TABLE `products` DROP `price`;
ALTER TABLE `products` DROP `min_quantity`;
ALTER TABLE `supplier_product_brands` CHANGE `brand_id` `brand_id` VARCHAR(512) NOT NULL;
ALTER TABLE `supplier_product_grades` CHANGE `grade_id` `grade_id` VARCHAR(512) NOT NULL;
ALTER TABLE `products` ADD `is_verify` BOOLEAN NOT NULL DEFAULT TRUE AFTER `status`;
-- 18-08-2021 end


-- 24-08-2021
ALTER TABLE `supplier_products` DROP `product_catalog`;
ALTER TABLE `supplier_products` DROP `product_terms`;
ALTER TABLE `supplier_products` ADD `product_ref` VARCHAR(512) NULL AFTER `discounted_price`;
-- END 24-08-2021


-- 27-08-2021
CREATE TABLE `orders` ( `id` INT NOT NULL AUTO_INCREMENT , `user_id` INT(11) NOT NULL , `quote_id` INT(11) NOT NULL , `order_number` VARCHAR(215) NOT NULL , `payment_amount` VARCHAR(215) NOT NULL , `payment status` BOOLEAN NOT NULL DEFAULT FALSE , `payment_date` DATETIME NULL DEFAULT NULL , `min_delivery_date` DATE NOT NULL , `max_delivery_date` DATE NOT NULL , `address_line_1` VARCHAR(512) NOT NULL , `address_line_2` VARCHAR(512) NOT NULL , `city` VARCHAR(215) NOT NULL , `pincode` VARCHAR(215) NOT NULL , `state` VARCHAR(215) NOT NULL , `order_status` INT(11) NULL , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `is_deleted` BOOLEAN NOT NULL DEFAULT FALSE , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `orders` CHANGE `payment status` `payment_status` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `orders` CHANGE `order_number` `order_number` VARCHAR(215) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;

CREATE TABLE `order_status` (
  `id` int NOT NULL,
  `name` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`id`, `name`, `description`, `status`, `created_at`, `is_deleted`) VALUES
(1, 'Order Confirmed & Payment Pending ', 'Order Confirmed & Payment Pending ', 1, '2021-08-27 08:27:47', 0),
(2, 'Payment Done', 'Payment Done', 1, '2021-08-27 08:28:49', 0),
(3, 'Under Preparation', 'Under Preparation', 1, '2021-08-27 08:28:49', 0),
(4, 'Ready to Dispatch', 'Ready to Dispatch', 1, '2021-08-27 08:28:49', 0),
(5, 'Order Pickuped', 'Order Pickuped', 1, '2021-08-27 08:28:49', 0),
(6, 'In Transit', 'In Transit', 1, '2021-08-27 08:28:49', 0),
(7, 'Out for Delivery', 'Out for Delivery', 1, '2021-08-27 08:28:49', 0),
(8, 'Delivered', 'Delivered', 1, '2021-08-27 08:28:49', 0),
(9, 'Under QC', 'Under QC', 1, '2021-08-27 08:28:49', 0),
(10, 'QC Failed', 'QC Failed', 1, '2021-08-27 08:28:49', 0),
(11, 'QC Passed', 'QC Passed', 1, '2021-08-27 08:28:49', 0),
(12, 'Order Completed', 'Order Completed', 1, '2021-08-27 08:29:01', 0),
(13, 'Order Returned', 'Order Returned', 1, '2021-08-27 08:29:01', 0),
(14, 'Order Cancelled', 'Order Cancelled', 1, '2021-08-27 08:29:05', 0);

ALTER TABLE `order_status`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `order_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;


-- END 27-08-2021


28-08-2021
CREATE TABLE `order_tracks` ( `id` INT NOT NULL AUTO_INCREMENT , `order_id` INT(11) NOT NULL , `status_id` INT(11) NOT NULL , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `is_deleted` BOOLEAN NOT NULL DEFAULT FALSE , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `order_status` ADD `parent_id` INT(11) NULL AFTER `status`;
UPDATE `order_status` SET `parent_id` = '4' WHERE `order_status`.`id` = 5;
UPDATE `order_status` SET `parent_id` = '6' WHERE `order_status`.`id` = 7;
UPDATE `order_status` SET `parent_id` = '8' WHERE `order_status`.`id` = 9;
UPDATE `order_status` SET `parent_id` = '8' WHERE `order_status`.`id` = 10;
UPDATE `order_status` SET `parent_id` = '8' WHERE `order_status`.`id` = 11;
CREATE TABLE `order_pos` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `po_number` INT(11) NOT NULL , `order_id` INT(11) NOT NULL , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `is_deleted` BOOLEAN NOT NULL DEFAULT FALSE , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `order_pos` CHANGE `po_number` `po_number` INT NULL;
ALTER TABLE `order_pos` CHANGE `po_number` `po_number` VARCHAR(512) NULL DEFAULT NULL;
-- END 28-08-2021


-- 30-08-2021
CREATE TABLE `subscribed_users` (
  `id` int NOT NULL,
  `firstname` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `company_name` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `is_buyer` tinyint(1) NOT NULL DEFAULT '0',
  `is_supplier` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

ALTER TABLE `subscribed_users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `subscribed_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;
-- END 30-08-21
---02-09-21
ALTER TABLE `suppliers` ADD `status` BOOLEAN NOT NULL DEFAULT FALSE AFTER `accepted_terms`;
UPDATE suppliers SET status =1;
---- END 02-09-21


-- 08-09-21
ALTER TABLE `quotes` ADD `certificate` VARCHAR(512) NULL AFTER `note`;
ALTER TABLE `quotes` ADD `comment` TEXT NULL AFTER `certificate`;
-- END 08-09-21


-- 22-09-21
ALTER TABLE `rfq_products` ADD `expected_date` DATE NULL AFTER `unit_id`, ADD `comment` TEXT NULL AFTER `expected_date`;
ALTER TABLE `user_addresses` ADD `address_name` VARCHAR(512) NULL AFTER `user_id`;
-- 22-09-21


-- 03-10-21
ALTER TABLE `users` ADD `profile_pic` VARCHAR(512) NULL AFTER `password`;
ALTER TABLE `companies` ADD `interested_product` TEXT NULL AFTER `name`;
ALTER TABLE `companies` ADD `logo` VARCHAR(512) NULL AFTER `interested_product`;
-- End 03-10-21


-- 12-10-21
UPDATE users SET is_active = 1;

ALTER TABLE `rfqs` ADD `rental_forklift` BOOLEAN NOT NULL DEFAULT FALSE AFTER `reference_number`, ADD `unloading_services` BOOLEAN NOT NULL DEFAULT FALSE AFTER `rental_forklift`;
-- END 12-10-21

-- 18-10-2021
ALTER TABLE `user_activities` ADD `is_activity_shown` BOOLEAN NOT NULL DEFAULT FALSE AFTER `activity`;
-- END 18-10-2021


-- 20-10-2021
UPDATE `order_status` SET `name` = 'Order Picked-up', `description` = 'Order Picked-up' WHERE `order_status`.`id` = 5
ALTER TABLE `quotes_charges_with_amounts` CHANGE `charge_value` `charge_value` DOUBLE NOT NULL;
ALTER TABLE `quotes_charges_with_amounts` CHANGE `charge_amount` `charge_amount` DOUBLE NOT NULL;
ALTER TABLE `quotes` CHANGE `product_amount` `product_amount` DOUBLE NOT NULL;
ALTER TABLE `quotes` CHANGE `final_amount` `final_amount` DOUBLE NOT NULL;
ALTER TABLE `quotes` CHANGE `tax_value` `tax_value` DOUBLE NOT NULL;
ALTER TABLE `quotes` CHANGE `product_price_per_unit` `product_price_per_unit` DOUBLE NOT NULL;
-- END 20-10-2021


-- 22-10-2021
INSERT INTO `ltm_translations` (`status`, `locale`, `group`, `key`, `value`, `created_at`, `updated_at`) VALUES
(0, 'id', 'order', 'Order Received', 'Pesanan Diterima', '2021-10-22 05:46:20', '2021-10-22 05:47:03'),
(0, 'en', 'order', 'Order Received', 'Order Received', '2021-10-22 05:46:48', '2021-10-22 05:47:03');
-- END 22-10-2021


-- 24-10-2021
ALTER TABLE `user_activities` ADD `type` VARCHAR(512) NULL AFTER `is_activity_shown`, ADD `record_id` INT(11) NULL AFTER `type`;
-- END 24-10-2021

-- 28-10-2021
ALTER TABLE `users` ADD `designation` VARCHAR(512) NULL AFTER `remember_token`, ADD `department` VARCHAR(512) NULL AFTER `designation`;

ALTER TABLE `companies` DROP `interested_product`;

ALTER TABLE `companies` ADD `registrantion_NIB` VARCHAR(512) NOT NULL AFTER `logo`, ADD `web_site` VARCHAR(512) NULL AFTER `registrantion_NIB`, ADD `company_email` VARCHAR(512) NOT NULL AFTER `web_site`, ADD `company_phone` VARCHAR(512) NOT NULL AFTER `company_email`, ADD `alternative_email` VARCHAR(512) NULL AFTER `company_phone`, ADD `alternative_phone` VARCHAR(512) NULL AFTER `alternative_email`, ADD `address` TEXT NOT NULL AFTER `alternative_phone`;

CREATE TABLE `company_consumptions` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `annual_consumption` double NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `company_consumptions` ADD PRIMARY KEY (`id`);
ALTER TABLE `company_consumptions` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


CREATE TABLE `designations` (
  `id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `designations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `blitznet`.`departments` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(512) NOT NULL , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `is_deleted` BOOLEAN NOT NULL DEFAULT FALSE , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `users` CHANGE `designation` `designation` INT(11) NULL DEFAULT NULL;
ALTER TABLE `users` CHANGE `department` `department` INT(11) NULL DEFAULT NULL;
ALTER TABLE `suppliers` ADD `interested_in` TEXT NULL AFTER `status`;
ALTER TABLE `company_consumptions` ADD `user_id` INT(11) NOT NULL AFTER `annual_consumption`;
ALTER TABLE `company_consumptions` CHANGE `product_id` `product_cat_id` INT(11) NOT NULL;


INSERT INTO `ltm_translations` (`status`, `locale`, `group`, `key`, `value`, `created_at`, `updated_at`) VALUES
(0, 'id', 'profile', 'registration_nib', 'Pendaftaran # (Dalam ID: NIB)', '2021-10-28 04:39:57', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'registration_nib', 'Registration # (In ID: NIB)', '2021-10-28 04:40:42', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'web_site', 'situs web', '2021-10-28 04:56:11', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'web_site', 'website', '2021-10-28 04:56:20', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'company_email', 'Email Perusahaan', '2021-10-28 04:57:40', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'company_email', 'Company Email', '2021-10-28 04:57:51', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'company_phone', 'Telepon Perusahaan', '2021-10-28 04:58:51', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'company_phone', 'Company Phone', '2021-10-28 04:59:02', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'alternative_email', 'Email Alternatif', '2021-10-28 04:59:44', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'alternative_email', 'Alternative Email', '2021-10-28 04:59:56', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'alternative_phone', 'telepon alternatif', '2021-10-28 05:00:26', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'alternative_phone', 'Alternative Phone', '2021-10-28 05:00:36', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'address', 'Alamat', '2021-10-28 05:01:20', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'address', 'Address', '2021-10-28 05:01:26', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'designation', 'Penamaan', '2021-10-28 05:37:04', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'designation', 'Designation', '2021-10-28 05:37:11', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'department', 'Departemen', '2021-10-28 05:37:58', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'department', 'Department', '2021-10-28 05:38:09', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Admin_Setting', 'Pengaturan Admin', '2021-11-01 05:45:25', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Admin_Setting', 'Admin Setting', '2021-11-01 05:45:33', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Company_Yearly_Consumption_Detail', 'Detail Konsumsi Tahunan Perusahaan', '2021-11-01 05:47:38', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Company_Yearly_Consumption_Detail', 'Company Yearly Consumption Detail', '2021-11-01 05:47:54', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Product', 'Produk', '2021-11-01 05:48:31', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Product', 'Product', '2021-11-01 05:48:33', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Category', 'Kategori', '2021-11-01 05:48:52', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Category', 'Category', '2021-11-01 05:48:55', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Annual_Consumption', 'Konsumsi Tahunan', '2021-11-01 05:49:29', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Annual_Consumption', 'Annual Consumption', '2021-11-01 05:49:43', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Unit', 'Satuan', '2021-11-01 05:50:06', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Unit', 'Unit', '2021-11-01 05:50:10', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Preferences', 'Preferensi', '2021-11-01 05:51:52', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Preferences', 'Preferences', '2021-11-01 05:51:56', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Payment_Term', 'Jangka waktu pembayaran', '2021-11-01 05:52:47', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Payment_Term', 'Payment Term', '2021-11-01 05:52:56', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Language', 'Bahasa', '2021-11-01 05:53:21', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Language', 'Language', '2021-11-01 05:53:24', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'English', 'bahasa Inggris', '2021-11-01 05:53:54', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'English', 'English', '2021-11-01 05:53:58', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Indonesian', 'bahasa Indonesia', '2021-11-01 05:54:25', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Indonesian', 'Indonesian', '2021-11-01 05:54:29', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Currency', 'Mata uang', '2021-11-01 05:54:52', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Currency', 'Currency', '2021-11-01 05:54:59', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'US_Dollar', 'Dolar Amerika', '2021-11-01 05:55:35', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'US_Dollar', 'US Dollar', '2021-11-01 05:55:44', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Indonesian_Rp', 'Indonesia Rp', '2021-11-01 05:56:08', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Indonesian_Rp', 'Indonesian Rp', '2021-11-01 05:56:11', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Save_Changes', 'Simpan perubahan', '2021-11-01 05:56:43', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Save_Changes', 'Save Changes', '2021-11-01 05:56:47', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Group_by', 'Kelompokkan menurut', '2021-11-01 06:02:39', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Group_by', 'Group by', '2021-11-01 06:02:42', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Payment_Method', 'Cara Pembayaran', '2021-11-01 06:03:13', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Payment_Method', 'Payment Method', '2021-11-01 06:03:19', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Detail', 'Detail', '2021-11-01 06:03:37', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Detail', 'Detail', '2021-11-01 06:03:40', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Group', 'Kelompok', '2021-11-01 06:03:55', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Group', 'Group', '2021-11-01 06:03:57', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'PIA', 'PIA', '2021-11-01 06:04:16', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'PIA', 'PIA', '2021-11-01 06:04:18', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Credit', 'Kredit', '2021-11-01 06:05:11', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Credit', 'Credit', '2021-11-01 06:05:14', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Payment_in_Advance', 'Pembayaran di muka', '2021-11-01 06:06:05', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Payment_in_Advance', 'Payment in Advance', '2021-11-01 06:06:08', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Payment_7_day_after_invoice_date', 'Pembayaran 7 hari setelah tanggal faktur', '2021-11-01 06:07:24', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Payment_7_day_after_invoice_date', 'Payment 7 day after invoice date', '2021-11-01 06:07:35', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Payment_10_day_after_invoice_date', 'Pembayaran 10 hari setelah tanggal faktur', '2021-11-01 06:08:31', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Payment_10_day_after_invoice_date', 'Payment 10 day after invoice date', '2021-11-01 06:08:40', '2021-11-01 06:33:12'),
(0, 'id', 'profile', 'Payment_60_day_after_invoice_date', 'Pembayaran 60 hari setelah tanggal faktur', '2021-11-01 06:08:51', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Payment_60_day_after_invoice_date', 'Payment 60 days after invoice date', '2021-11-01 06:09:01', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'PIA	', NULL, '2021-11-01 06:09:12', '2021-11-01 06:09:12'),
(0, 'id', 'profile', 'Add', 'Menambahkan', '2021-11-01 06:28:08', '2021-11-01 06:33:12'),
(0, 'en', 'profile', 'Add', 'Add', '2021-11-01 06:28:10', '2021-11-01 06:33:12');

-- 28-10-2021

-- 16 nov 2021
ALTER TABLE `users` CHANGE `profile_pic` `profile_pic` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `orders` ADD `address_name` VARCHAR(512) NOT NULL AFTER `max_delivery_date`;
ALTER TABLE `companies` ADD `background_logo` VARCHAR(512) NULL AFTER `logo`;

-- 19 - 11 - 2021


ALTER TABLE `order_pos` ADD `comment` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `order_id`;

-- 24 - 11 - 2021
CREATE TABLE `rfq_activity` ( `id` INT(11) NOT NULL AUTO_INCREMENT , PRIMARY KEY (`id`) , `user_id` INT(11) NOT NULL , `rfq_id` INT(11) NOT NULL , `key_name` VARCHAR(512) NOT NULL , `old_value` VARCHAR(512) NOT NULL , `new_value` VARCHAR(512) NOT NULL , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `is_deleted` TINYINT(1) NOT NULL DEFAULT '0' ) ENGINE = InnoDB;


-- 26 - 11 - 2021
ALTER TABLE `rfqs` ADD `is_require_credit` BOOLEAN NOT NULL DEFAULT FALSE AFTER `unloading_services`;

CREATE TABLE `credit_days` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `name` varchar(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `days` int NOT NULL,
 `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
 `status` tinyint(1) NOT NULL DEFAULT '1',
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_credit_days` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `order_id` bigint unsigned DEFAULT NULL,
 `credit_days_id` bigint unsigned DEFAULT NULL,
 `request_days` int NOT NULL DEFAULT '0',
 `approved_days` int DEFAULT '0',
 `notes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
 `status` tinyint NOT NULL DEFAULT '0' COMMENT '0=>pending,1=>approved,2=>rejected',
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`),
 KEY `order_credit_days_order_id_index` (`order_id`),
 KEY `order_credit_days_credit_days_id_index` (`credit_days_id`),
 CONSTRAINT `order_credit_days_credit_days_id_foreign` FOREIGN KEY (`credit_days_id`) REFERENCES `credit_days` (`id`) ON DELETE CASCADE,
 CONSTRAINT `order_credit_days_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `orders` ADD `is_credit` BOOLEAN NOT NULL DEFAULT FALSE AFTER `order_number`;

ALTER TABLE `orders` ADD `payment_due_date` DATE NULL AFTER `payment_amount`;


INSERT INTO `order_status` (`id`, `name`, `description`, `status`, `parent_id`, `show_order_id`, `created_at`, `updated_at`, `is_deleted`) VALUES (NULL, 'Payment Due on %s', 'Payment Due DD/MM/YYYY', '1', NULL, '17', NULL, NULL, '0'),(NULL, 'Credit Approved', 'Credit Approved', '1', NULL, '18', NULL, NULL, '0');

ALTER TABLE `order_status` ADD `credit_sorting` TINYINT NOT NULL DEFAULT '0' AFTER `show_order_id`;

INSERT INTO `order_status` (`id`, `name`, `description`, `status`, `parent_id`, `show_order_id`, `credit_sorting`, `created_at`, `updated_at`, `is_deleted`) VALUES (NULL, 'Credit Rejected', 'Credit Rejected', '1', NULL, '19', '4', NULL, NULL, '0');

UPDATE `order_status` SET `show_order_id` = '1',`credit_sorting` = '1' WHERE `order_status`.`id` = 1;
UPDATE `order_status` SET `show_order_id` = '2',`credit_sorting` = '2' WHERE `order_status`.`id` = 2;
UPDATE `order_status` SET `show_order_id` = '4',`credit_sorting` = '16' WHERE `order_status`.`id` = 3;
UPDATE `order_status` SET `show_order_id` = '5',`credit_sorting` = '5' WHERE `order_status`.`id` = 4;
UPDATE `order_status` SET `show_order_id` = '6',`credit_sorting` = '6' WHERE `order_status`.`id` = 5;
UPDATE `order_status` SET `show_order_id` = '7',`credit_sorting` = '7' WHERE `order_status`.`id` = 6;
UPDATE `order_status` SET `show_order_id` = '8',`credit_sorting` = '8' WHERE `order_status`.`id` = 7;
UPDATE `order_status` SET `show_order_id` = '9',`credit_sorting` = '9' WHERE `order_status`.`id` = 8;
UPDATE `order_status` SET `show_order_id` = '10',`credit_sorting` = '10' WHERE `order_status`.`id` = 9;
UPDATE `order_status` SET `show_order_id` = '11',`credit_sorting` = '11' WHERE `order_status`.`id` = 10;
UPDATE `order_status` SET `show_order_id` = '12',`credit_sorting` = '12' WHERE `order_status`.`id` = 11;
UPDATE `order_status` SET `show_order_id` = '13',`credit_sorting` = '13' WHERE `order_status`.`id` = 12;
UPDATE `order_status` SET `show_order_id` = '14',`credit_sorting` = '14' WHERE `order_status`.`id` = 13;
UPDATE `order_status` SET `show_order_id` = '15',`credit_sorting` = '17' WHERE `order_status`.`id` = 14;
UPDATE `order_status` SET `show_order_id` = '16',`credit_sorting` = '18' WHERE `order_status`.`id` = 15;
UPDATE `order_status` SET `show_order_id` = '17',`credit_sorting` = '19' WHERE `order_status`.`id` = 16;
UPDATE `order_status` SET `show_order_id` = '18',`credit_sorting` = '15' WHERE `order_status`.`id` = 17;
UPDATE `order_status` SET `show_order_id` = '19',`credit_sorting` = '3' WHERE `order_status`.`id` = 18;
UPDATE `order_status` SET `show_order_id` = '3',`credit_sorting` = '4' WHERE `order_status`.`id` = 19;

-- 29 - 11 - 2021
UPDATE `order_status` SET `name` = 'Credit Approved',`description` = 'Credit Approved' WHERE `order_status`.`id` = 18;

-- 30 - 11 - 2021 --


ALTER TABLE `departments` ADD `status` TINYINT(1) NOT NULL DEFAULT '1' AFTER `name`;


ALTER TABLE `departments` ADD `added_by` INT(11) NULL DEFAULT NULL AFTER `is_deleted`,
ADD `updated_by` INT(11) NULL DEFAULT NULL AFTER `added_by`,
ADD `deleted_by` INT(11) NOT NULL DEFAULT '1' AFTER `updated_by`;


ALTER TABLE `designations` ADD `status` TINYINT(1) NOT NULL DEFAULT '1' AFTER `name`;

ALTER TABLE `designations` ADD `added_by` INT(11) NULL DEFAULT NULL AFTER `is_deleted`,
ADD `updated_by` INT(11) NULL DEFAULT NULL AFTER `added_by`,
ADD `deleted_by` INT(11) NULL DEFAULT NULL AFTER `updated_by`;

-- 1 - 12 - 2021
CREATE TABLE `bank_details` (
  `id` bigint UNSIGNED NOT NULL,
  `bank_name` varchar(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ac_name` varchar(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ac_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bank_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `bank_details` (`id`, `bank_name`, `ac_name`, `ac_no`, `bank_code`, `description`, `status`, `created_at`, `is_deleted`) VALUES
(1, 'Mandiri', 'PT. Blitznet Upaya Indonesia', '101-00-1160974-8', '008', NULL, 1, '2021-12-01 08:31:45', 0);

INSERT INTO `credit_days` (`id`, `name`, `days`, `description`, `status`, `created_at`, `is_deleted`) VALUES (NULL, 'For 45 Days', '45', 'For 45 Days', '1', CURRENT_TIMESTAMP, '0');

ALTER TABLE `credit_days` ADD `sort` TINYINT NOT NULL DEFAULT '0' AFTER `description`;

UPDATE `credit_days` SET `sort` = '1' WHERE `credit_days`.`id` = 1;
UPDATE `credit_days` SET `sort` = '2' WHERE `credit_days`.`id` = 4;
UPDATE `credit_days` SET `sort` = '3' WHERE `credit_days`.`id` = 2;
UPDATE `credit_days` SET `sort` = '4' WHERE `credit_days`.`id` = 3;

-- 6 - 12 - 2021
CREATE TABLE `settings` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `key` varchar(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `name` varchar(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
 `value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
 `status` tinyint NOT NULL DEFAULT '1',
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`id`, `key`, `name`, `value`, `status`, `created_at`, `is_deleted`) VALUES (1, 'xendit_test_token', 'Xendit Test Token', 'xnd_development_Umn5tEkfTDRkKDit8mkRKSMHEBVv1sPKNQwHAEk4Ya9ub1niohC4MzigMZPkPF2', '1', CURRENT_TIMESTAMP, '0'), (2, 'xendit_live_token', 'Xendit Live Token', '', '1', CURRENT_TIMESTAMP, '0');
INSERT INTO `settings` (`id`, `key`, `name`, `value`, `status`, `created_at`, `is_deleted`) VALUES (3, 'invoice_valid_days', 'Invoice Valid Days', '3', '1', CURRENT_TIMESTAMP, '0');
INSERT INTO `settings` (`id`, `key`, `name`, `value`, `status`, `created_at`, `updated_at`, `is_deleted`) VALUES (NULL, 'valid_till', 'Quote Validate till', '1,2,3,7,10,14', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '0');
INSERT INTO `settings` (`id`, `key`, `name`, `value`, `status`, `created_at`, `updated_at`, `is_deleted`) VALUES (NULL, 'payment_due', 'Order Payment Due', '1,2,3,4,5,6,10', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '0');

-- 8-12-2021
INSERT INTO `quote_status` (`id`, `name`, `backofflice_name`, `description`, `created_at`, `updated_at`, `is_deleted`) VALUES (NULL, 'Quotation Rejected', 'Quotation Rejected', '', NULL, NULL, '0');
ALTER TABLE `rfqs` CHANGE `pincode` `pincode` VARCHAR(255) NOT NULL;


--9-12-2021
ALTER TABLE `orders` ADD `tax_receipt` TEXT NULL AFTER `state`, ADD `order_latter` TEXT NULL AFTER `tax_receipt`, ADD `invoice` TEXT NULL AFTER `order_latter`;
ALTER TABLE `order_tracks` ADD `user_id` BIGINT NULL DEFAULT '1' AFTER `status_id`;

-- 16-12-2021
-- for local only
UPDATE `quotes` JOIN `orders` ON orders.quote_id = quotes.id SET quotes.status_id = '2'
UPDATE quotes SET status_id = '3' where status_id IS NULL;
-- end local only
INSERT INTO `settings` (`id`, `key`, `name`, `value`, `description`, `status`, `created_at`, `is_deleted`) VALUES (NULL, 'cron_admin_email', 'Cron Admin Email', 'testprocurement@blitznet.co.id', NULL, '1', CURRENT_TIMESTAMP, '0');

-- 24-12-2022
CREATE TABLE `order_activities` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `key_name` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_value` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_value` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
);

--31-12-2021

CREATE TABLE `order_activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `key_name` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_value` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_value` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


UPDATE `settings` SET `key` = 'xendit_invoice',`name` = 'Xendit Invoice Settings' WHERE `settings`.`id` = 3;
UPDATE `settings` SET `value` = '{\"invoice_valid_days\":3,\"invoice_reminder_time_unit\":\"days\",\"invoice_reminder_time\":1}' WHERE `settings`.`id` = 3;

ALTER TABLE `settings` ADD `description` TEXT NULL AFTER `value`;

UPDATE `settings` SET `description` = 'value is string' WHERE `settings`.`id` = 1;
UPDATE `settings` SET `description` = 'value is string' WHERE `settings`.`id` = 2;
UPDATE `settings` SET `description` = 'value must be json_encode array\r\n\r\nkey value example:-\r\n [invoice_valid_days] => 3(integer value)\r\n [invoice_reminder_time_unit] => days(only two options \'days\' or \'hours\')\r\n [invoice_reminder_time] => 3(integer value)\r\n' WHERE `settings`.`id` = 3;
UPDATE `settings` SET `description` = 'value must be json_encode array' WHERE `settings`.`id` = 3;

UPDATE `settings` SET `value` = '{\"max_invoice_generate\":3,\"invoice_valid_days\":2,\"invoice_reminder_time_unit\":\"days\",\"invoice_reminder_time\":1}' WHERE `settings`.`id` = 3;

INSERT INTO `settings` (`id`, `key`, `name`, `value`, `description`, `status`, `created_at`, `is_deleted`) VALUES (6, 'system_date_time_format', 'Default Date Time Format', '{\"date\":\"d-m-Y\",\"time\":\"H:i\"}', 'DD-MM-YYYY 24:00', '1', CURRENT_TIMESTAMP, '0');


CREATE TABLE `order_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `external_id` varchar(512) COLLATE utf8mb4_bin NOT NULL,
  `user_id` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `status` varchar(15) COLLATE utf8mb4_bin NOT NULL,
  `merchant_name` varchar(512) COLLATE utf8mb4_bin DEFAULT NULL,
  `merchant_profile_picture_url` text COLLATE utf8mb4_bin,
  `amount` double NOT NULL,
  `payer_email` varchar(254) COLLATE utf8mb4_bin NOT NULL,
  `expiry_date` timestamp NULL DEFAULT NULL,
  `invoice_url` text COLLATE utf8mb4_bin NOT NULL,
  `should_send_email` tinyint(1) DEFAULT NULL,
  `success_redirect_url` text COLLATE utf8mb4_bin,
  `failure_redirect_url` text COLLATE utf8mb4_bin,
  `created` timestamp NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL,
  `currency` varchar(25) COLLATE utf8mb4_bin NOT NULL,
  `items` mediumtext COLLATE utf8mb4_bin,
  `customer` mediumtext COLLATE utf8mb4_bin,
  `payment_destination` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `bank_code` varchar(25) COLLATE utf8mb4_bin DEFAULT NULL,
  `paid_amount` double DEFAULT NULL,
  `initial_amount` double DEFAULT NULL,
  `fees_paid_amount` double DEFAULT NULL,
  `adjusted_received_amount` double DEFAULT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `payment_channel` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `credit_card_charge_id` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `description` text COLLATE utf8mb4_bin,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_transactions_order_id_foreign` (`order_id`),
  CONSTRAINT `order_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;


ALTER TABLE `other_charges` CHANGE `charges_type` `charges_type` SMALLINT NOT NULL DEFAULT '0';
INSERT INTO `other_charges` (`id`, `name`, `description`, `type`, `charges_value`, `value_on`, `charges_type`, `status`, `addition_substraction`, `created_at`, `updated_at`, `is_deleted`, `added_by`, `updated_by`, `deleted_by`) VALUES
(10, 'Transactions Charges', 'IDR 10,450(Money In 4500 + Money Out 5000) + 10 % Vat', 1, '10450', 0, 2, 1, 1, '2021-12-31 14:26:41', '2021-12-31 14:26:41', 0, 1, NULL, 1);


ALTER TABLE `suppliers` ADD `xen_platform_id` VARCHAR(40) NULL DEFAULT '' AFTER `interested_in`;
UPDATE `suppliers` SET `xen_platform_id` = '619b7b7842acae456a3a52f1' WHERE `suppliers`.`id` = 1;

CREATE TABLE `available_banks` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `can_disburse` tinyint(1) DEFAULT NULL,
  `can_name_validate` tinyint(1) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `suppliers_banks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bank_id` bigint(20) unsigned NOT NULL,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `bank_account_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_account_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `suppliers_banks_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `suppliers_banks_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `batch_disbursements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_dis_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_uploaded_amount` double NOT NULL DEFAULT '0',
  `total_uploaded_count` int(11) NOT NULL DEFAULT '0',
  `approver_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `total_disbursed_amount` double NOT NULL DEFAULT '0',
  `total_disbursed_count` int(11) NOT NULL DEFAULT '0',
  `total_error_amount` double NOT NULL DEFAULT '0',
  `total_error_count` int(11) NOT NULL DEFAULT '0',
  `created` timestamp NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `disbursements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_disbursement_id` bigint(20) unsigned DEFAULT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `disbursement_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `external_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `bank_reference` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valid_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_account_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_account_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disbursement_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_instant` tinyint(1) DEFAULT NULL,
  `failure_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `failure_message` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_to` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_cc` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_bcc` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `disbursements_batch_disbursement_id_foreign` (`batch_disbursement_id`),
  KEY `disbursements_order_id_foreign` (`order_id`),
  CONSTRAINT `disbursements_batch_disbursement_id_foreign` FOREIGN KEY (`batch_disbursement_id`) REFERENCES `batch_disbursements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `disbursements_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `quotes_charges_with_amounts` CHANGE `charge_type` `charge_type` SMALLINT NOT NULL DEFAULT '0';


INSERT INTO `ltm_translations` (`id`, `status`, `locale`, `group`, `key`, `value`, `created_at`, `updated_at`) VALUES
(null, 0, 'en', 'order', 'pay_generate_button', 'Generate Link', '2021-12-27 07:32:49', '2021-12-27 14:17:08'),
(null, 0, 'id', 'order', 'pay_generate_button', 'Hasilkan Tautan', '2021-12-27 07:37:41', '2021-12-27 14:17:08'),
(null, 0, 'en', 'order', 'order_not_found', 'Order not found!', '2021-12-27 09:44:14', '2021-12-27 14:17:08'),
(null, 0, 'id', 'order', 'order_not_found', 'Pesanan tidak ditemukan!', '2021-12-27 10:20:07', '2021-12-27 14:17:08'),
(null, 0, 'en', 'order', 'quotation_validity_expired', 'Validity of quotation expired on %s', '2021-12-27 13:01:00', '2021-12-27 14:17:08'),
(null, 0, 'id', 'order', 'quotation_validity_expired', 'Validitas kutipan berakhir pada %s', '2021-12-27 13:01:46', '2021-12-27 14:17:08'),
(null, 0, 'en', 'order', 'invoice_generate_limit_reached', 'You have reached your limit on invoice generation', '2021-12-27 14:16:09', '2021-12-27 14:17:08'),
(null, 0, 'id', 'order', 'invoice_generate_limit_reached', 'Anda telah mencapai batas pembuatan faktur', '2021-12-27 14:17:02', '2021-12-27 14:17:08'),
(null, 0, 'en', 'order', 'order_print', 'Order', '2021-12-27 14:16:09', '2021-12-27 14:17:08'),
(null, 0, 'id', 'order', 'order_print', 'Memesan', '2021-12-27 14:17:02', '2021-12-27 14:17:08');

--3/1/2022
/*update all charege id as a other charge name */
ALTER TABLE `quotes_charges_with_amounts` ADD `charge_id` BIGINT NULL AFTER `quote_id`;

UPDATE other_charges JOIN quotes_charges_with_amounts ON quotes_charges_with_amounts.charge_name = other_charges.name SET quotes_charges_with_amounts.charge_id = other_charges.id WHERE quotes_charges_with_amounts.charge_id is NULL;

CREATE TABLE `quote_activities` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL DEFAULT '1',
  `quote_id` int NOT NULL,
  `key_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0'
);
ALTER TABLE `quote_activities` ADD PRIMARY KEY (`id`);
ALTER TABLE `quote_activities` MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT; COMMIT;
ALTER TABLE `order_activities` ADD PRIMARY KEY (`id`);
ALTER TABLE `order_activities` MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;COMMIT;

--5/01/2021
ALTER TABLE `quotes` ADD `user_id` INT(11) NULL DEFAULT '1' AFTER `rfq_id`;
ALTER TABLE `quote_activities` CHANGE `created_at` `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP, CHANGE `updated_at` `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `quotes` CHANGE `created_at` `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP, CHANGE `updated_at` `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP;


--4/1/2022
ALTER TABLE `orders` ADD `otp_supplier` VARCHAR(10) NULL AFTER `order_status`;

--7/1/2022
ALTER TABLE `order_tracks` ADD `user_type` VARCHAR(255) NOT NULL AFTER `user_id`;
--run demo cron for change all type php artisan demo:cron

--16/1/2022
UPDATE `settings` SET `value` = '{\"max_invoice_generate\":3,\"invoice_valid_days\":2,\"credit_invoice_extra_days\":7,\"invoice_reminder_time_unit\":\"days\",\"invoice_reminder_time\":1}' WHERE `settings`.`id` = 3;

--25/1/2022
ALTER TABLE `suppliers` CHANGE `xen_platform_id` `xen_platform_id` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '';

CREATE TABLE `xen_sub_accounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `xen_platform_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `public_profile` text COLLATE utf8mb4_unicode_ci,
  `created` timestamp NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `xen_sub_accounts_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `xen_sub_accounts_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 28-01-2022
UPDATE `settings` SET `value` = '{\"max_invoice_generate\":3,\"cash_invoice_valid_hours\":[72,48,48],\"invoice_valid_days\":2,\"credit_invoice_extra_days\":7,\"invoice_reminder_time_unit\":\"days\",\"invoice_reminder_time\":1}' WHERE `settings`.`id` = 3;

--26/01/2022 (Ronak)
CREATE TABLE `user_approval_configs` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `user_id` bigint unsigned NOT NULL,
 `user_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `user_approval_configs_user_id_foreign` (`user_id`),
 CONSTRAINT `user_approval_configs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

--28/01/2021 (Ronak)
ALTER TABLE `users` ADD `security_code` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `department`;

--28/01/2021 (Ronak)
ALTER TABLE `users` CHANGE `security_code` `security_code` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;



--26-1-2022
ALTER TABLE `quotes` ADD `logistic_check` TINYINT(1) NULL DEFAULT '0' AFTER `comment`;
INSERT INTO `quote_status` (`id`, `name`, `backofflice_name`, `description`, `created_at`, `updated_at`, `is_deleted`) VALUES (NULL, 'Partial Quotation Sent', 'Partial Quotation Sent', 'Partial Quotation Sent', NULL, NULL, '0');
ALTER TABLE `users` ADD `first_login` INT NULL DEFAULT '0' AFTER `department`;
ALTER TABLE `quotes` ADD `logistic_provided` INT NULL DEFAULT '0' COMMENT '0 - admin, 1- supplier' AFTER `logistic_check`;


--28-1-2022
CREATE TABLE `user_suppliers` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE `user_suppliers` ADD PRIMARY KEY (`id`);
ALTER TABLE `user_suppliers` MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1; COMMIT;

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`, `is_deleted`) VALUES (3, 'supplier', NULL, NULL, 0);

--04-02-2022 (Ronak)
CREATE TABLE `user_quote_feedbacks` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `user_id` bigint unsigned NOT NULL,
 `rfq_id` bigint unsigned NOT NULL,
 `quote_id` bigint unsigned NOT NULL,
 `security_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `feedback` tinyint NOT NULL DEFAULT '0' COMMENT '0=>Pending, 1=>Accepted, 2=>Rejected',
 `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `user_quote_feedbacks_user_id_foreign` (`user_id`),
 KEY `user_quote_feedbacks_rfq_id_foreign` (`rfq_id`),
 KEY `user_quote_feedbacks_quote_id_foreign` (`quote_id`),
 CONSTRAINT `user_quote_feedbacks_quote_id_foreign` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE,
 CONSTRAINT `user_quote_feedbacks_rfq_id_foreign` FOREIGN KEY (`rfq_id`) REFERENCES `rfqs` (`id`) ON DELETE CASCADE,
 CONSTRAINT `user_quote_feedbacks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
);

ALTER TABLE `jobs` ADD PRIMARY KEY (`id`), ADD KEY `jobs_queue_index` (`queue`);
ALTER TABLE `jobs` MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1; COMMIT;

--7-2-2022
CREATE TABLE `countries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso2` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso3` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `domain` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fips` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iso_numeric` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `geo_name_id` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `e164` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `continent` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capital` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_zone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `currency` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language_codes` varchar(90) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `languages` varchar(490) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area_km2` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=241 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `users` ADD `phone_code` VARCHAR(6) NULL DEFAULT '' AFTER `email_verified_at`;

ALTER TABLE `companies` ADD `nib_file` VARCHAR(512) NULL DEFAULT '' AFTER `registrantion_NIB`;
ALTER TABLE `companies` ADD `npwp` VARCHAR(25) NULL DEFAULT '' AFTER `nib_file`;
ALTER TABLE `companies` ADD `npwp_file` VARCHAR(512) NULL DEFAULT '' AFTER `npwp`;

ALTER TABLE `suppliers` ADD `c_phone_code` VARCHAR(6) NULL DEFAULT '' COMMENT 'company phone code' AFTER `email`;
ALTER TABLE `suppliers` ADD `cp_phone_code` VARCHAR(6) NULL DEFAULT '' COMMENT 'contact person phone code' AFTER `contact_person_email`;
ALTER TABLE `suppliers` ADD `alternate_email` VARCHAR(255) NULL DEFAULT '' AFTER `contact_person_phone`;
ALTER TABLE `suppliers` ADD `nib` VARCHAR(20) NULL DEFAULT '' AFTER `logo`;
ALTER TABLE `suppliers` ADD `nib_file` VARCHAR(512) NULL DEFAULT '' AFTER `nib`;
ALTER TABLE `suppliers` ADD `npwp` VARCHAR(25) NULL DEFAULT '' AFTER `nib_file`;
ALTER TABLE `suppliers` ADD `npwp_file` VARCHAR(512) NULL DEFAULT '' AFTER `npwp`;

--09-02-2022 (Ronak)
ALTER TABLE `user_quote_feedbacks` ADD `resend_mail` BOOLEAN NOT NULL DEFAULT FALSE COMMENT '0=>FirstTime, 1=>Resend' AFTER `feedback`;

--10-02-2022 (Ronak)
ALTER TABLE `companies`  ADD `approval_process` TINYINT(1) NOT NULL DEFAULT '0'  AFTER `address`;

ALTER TABLE `companies` ADD `c_phone_code` VARCHAR(6) NULL DEFAULT '' AFTER `company_email`;
ALTER TABLE `companies` ADD `a_phone_code` VARCHAR(6) NULL DEFAULT '' AFTER `alternative_email`;

ALTER TABLE `quotes` ADD `supplier_final_amount` INT(1) NULL DEFAULT '0' AFTER `logistic_provided`, ADD `supplier_tex_value` INT(1) NULL DEFAULT '0' AFTER `supplier_final_amount`;

ALTER TABLE `rfqs` ADD `phone_code` VARCHAR(6) NULL DEFAULT '' AFTER `lastname`;

-- 15-2-2022
INSERT INTO `settings` (`id`, `key`, `name`, `value`, `description`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES (8, 'supplier_transaction_charge', 'Supplier Transaction Charges', '25000', 'value must be integer', '1', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
CREATE TABLE `supplier_transaction_charges` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `disbursement_id` bigint(20) unsigned DEFAULT NULL,
  `xen_transfer_id` bigint(20) unsigned DEFAULT NULL,
  `paid_date` date NOT NULL,
  `paid_amount` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `supplier_transaction_charges_supplier_id_foreign` (`supplier_id`),
  KEY `supplier_transaction_charges_disbursement_id_foreign` (`disbursement_id`),
  KEY `supplier_transaction_charges_xen_transfer_id_foreign` (`xen_transfer_id`),
  CONSTRAINT `supplier_transaction_charges_disbursement_id_foreign` FOREIGN KEY (`disbursement_id`) REFERENCES `disbursements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `supplier_transaction_charges_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `supplier_transaction_charges_xen_transfer_id_foreign` FOREIGN KEY (`xen_transfer_id`) REFERENCES `xen_balance_transfers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 17-2-2022
INSERT INTO `settings` (`id`, `key`, `name`, `value`, `description`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES (9, 'xendit_main_account', 'Xendit Main Account', '61408058278d147e1e558100', 'Xendit Account Id', '1', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- 22-02-2022 (Ronak)
CREATE TABLE `airwaybill_number` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `order_id` bigint unsigned NOT NULL,
 `airwaybill_number` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `airwaybill_status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `airwaybill_number_order_id_foreign` (`order_id`),
 CONSTRAINT `airwaybill_number_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


-- 25-2-2022
ALTER TABLE `user_addresses` ADD `sub_district` VARCHAR(256) NULL DEFAULT '' AFTER `city`;
ALTER TABLE `user_addresses` ADD `district` VARCHAR(256) NULL DEFAULT '' AFTER `sub_district`;

ALTER TABLE `rfqs` ADD `address_name` VARCHAR(512) NULL DEFAULT '' AFTER `billing_tax_option`;
ALTER TABLE `rfqs` ADD `address_line_1` VARCHAR(512) NULL DEFAULT '' AFTER `address_name`;
ALTER TABLE `rfqs` ADD `address_line_2` VARCHAR(512) NULL DEFAULT '' AFTER `address_line_1`;
ALTER TABLE `rfqs` ADD `city` VARCHAR(256) NULL DEFAULT '' AFTER `address_line_2`;
ALTER TABLE `rfqs` ADD `sub_district` VARCHAR(256) NULL DEFAULT '' AFTER `city`;
ALTER TABLE `rfqs` ADD `district` VARCHAR(256) NULL DEFAULT '' AFTER `sub_district`;
ALTER TABLE `rfqs` ADD `state` VARCHAR(256) NULL DEFAULT '' AFTER `district`;

ALTER TABLE `orders` ADD `sub_district` VARCHAR(256) NULL DEFAULT '' AFTER `city`;
ALTER TABLE `orders` ADD `district` VARCHAR(256) NULL DEFAULT '' AFTER `sub_district`;

ALTER TABLE `quotes` ADD `address_line_1` VARCHAR(512) NULL DEFAULT '' AFTER `supplier_tex_value`;
ALTER TABLE `quotes` ADD `address_line_2` VARCHAR(512) NULL DEFAULT '' AFTER `address_line_1`;
ALTER TABLE `quotes` ADD `city` VARCHAR(256) NULL DEFAULT '' AFTER `address_line_2`;
ALTER TABLE `quotes` ADD `sub_district` VARCHAR(256) NULL DEFAULT '' AFTER `city`;
ALTER TABLE `quotes` ADD `district` VARCHAR(256) NULL DEFAULT '' AFTER `sub_district`;
ALTER TABLE `quotes` ADD `provinces` VARCHAR(256) NULL DEFAULT '' AFTER `district`;
ALTER TABLE `quotes` ADD `pincode` VARCHAR(256) NULL DEFAULT '' AFTER `provinces`;
ALTER TABLE `quotes` ADD `weights` VARCHAR(256) NULL DEFAULT '' AFTER `pincode`;
ALTER TABLE `quotes` ADD `dimensions` VARCHAR(256) NULL DEFAULT '' AFTER `weights`;

-- 23-02-2022 (Ronak)
ALTER TABLE `orders` ADD `pickup_date` date NULL AFTER `invoice`;
ALTER TABLE `orders` ADD `pickup_time` time NULL AFTER `pickup_date`;

-- 24-02-2022 (Ronak)
CREATE TABLE `quincus_order_tracking` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `airwaybill_number` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `blitznet_status_id` bigint unsigned NOT NULL,
 `quincus_status_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `process_status` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `quincus_status_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
 `quincus_status_stage` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `process_datetime` timestamp NOT NULL,
 `process_location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `process_signature` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
 `process_photo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
 `process_latitude` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `process_longitude` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `process_maps_location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
 `process_received_by` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `process_received_relation` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `quincus_order_tracking_blitznet_status_id_foreign` (`blitznet_status_id`),
 CONSTRAINT `quincus_order_tracking_blitznet_status_id_foreign` FOREIGN KEY (`blitznet_status_id`) REFERENCES `order_status` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

-- 25-02-2022 (Ronak)
ALTER TABLE `users` ADD `approval_invite` BOOLEAN NOT NULL DEFAULT 0 COMMENT '0=>Signup, 1=>Invites' AFTER `security_code`;

-- 01-03-2022 (Ronak)
ALTER TABLE `orders` ADD `customer_reference_id` VARCHAR(512) NULL DEFAULT '' AFTER `pickup_time`;

--8-3-2022 Arun

CREATE TABLE `groups` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_number` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `subCategory_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `end_date` date NOT NULL,
  `reached_quantity` double NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `location_code` text COLLATE utf8mb4_unicode_ci,
  `price` double NOT NULL,
  `added_by` bigint DEFAULT NULL,
  `updated_by` bigint DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
);
ALTER TABLE `groups` ADD PRIMARY KEY (`id`);
ALTER TABLE `groups` MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

CREATE TABLE `group_images` (
  `id` bigint UNSIGNED NOT NULL,
  `group_id` bigint NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci,
  `added_by` bigint DEFAULT NULL,
  `updated_by` bigint DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
);

ALTER TABLE `group_images` ADD PRIMARY KEY (`id`);
ALTER TABLE `group_images` MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

CREATE TABLE `group_suppliers` (
  `id` bigint UNSIGNED NOT NULL,
  `supplier_id` bigint NOT NULL,
  `group_id` bigint NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
);
ALTER TABLE `group_suppliers` ADD PRIMARY KEY (`id`);
ALTER TABLE `group_suppliers` MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

CREATE TABLE `group_supplier_discount_options` (
  `id` bigint UNSIGNED NOT NULL,
  `group_supplier_id` bigint NOT NULL,
  `min_quantity` double NOT NULL,
  `max_quantity` double NOT NULL,
  `unit_id` bigint NOT NULL,
  `discount` double NOT NULL,
  `added_by` bigint DEFAULT NULL,
  `updated_by` bigint DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
);
ALTER TABLE `group_supplier_discount_options` ADD PRIMARY KEY (`id`);
ALTER TABLE `group_supplier_discount_options` MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

-- 08-03-2022 (Ronak)
ALTER TABLE `rfqs` ADD `attached_document` TEXT NULL AFTER `is_require_credit`;

-- 15-03-2022 (Ronak)
INSERT INTO `rfq_status` (`id`, `name`, `backofflice_name`, `description`, `is_deleted`, `created_at`) VALUES (NULL, 'RFQ In Progress', 'RFQ In Progress', '', '0', CURRENT_TIMESTAMP);
-- 14-03-2022 -- (EKTA - GROUP )
ALTER TABLE `group_supplier_discount_options` ADD `group_id` BIGINT NOT NULL AFTER `group_supplier_id`;

-- 12-03-2022 (Ekta)
CREATE TABLE `invite_buyer` (
  `id` bigint UNSIGNED NOT NULL,
  `supplier_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `user_email` varchar(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('0','1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0=>pending,1=>active,2=>link expired',
  `resend_count` int NOT NULL DEFAULT '0',
  `added_by` bigint UNSIGNED NOT NULL DEFAULT '1',
  `token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `invite_buyer` ADD PRIMARY KEY (`id`);
ALTER TABLE `invite_buyer` MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

CREATE TABLE `group_tags` (
  `id` bigint UNSIGNED NOT NULL,
  `group_id` bigint NOT NULL,
  `tag` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `group_tags` ADD PRIMARY KEY (`id`);
ALTER TABLE `group_tags` MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

-- 15-03-2022 (Ronak)
CREATE TABLE `supplier_addresses` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `supplier_id` bigint unsigned NOT NULL,
 `address_name` varchar(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `address_line_1` varchar(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `address_line_2` varchar(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `pincode` int NOT NULL,
 `city` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `state` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `sub_district` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `district` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `default_address` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
 `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `supplier_addresses_supplier_id_foreign` (`supplier_id`),
 CONSTRAINT `supplier_addresses_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

-- 17-03-2022 (Ronak)
ALTER TABLE `quotes` ADD COLUMN `address_name` text NOT NULL AFTER `supplier_tex_value`;

-- 16-03-2022 - Munir
UPDATE `other_charges` SET `charges_value` = '4995' WHERE `other_charges`.`id` = 10;
INSERT INTO `settings` (`id`, `key`, `name`, `value`, `description`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES (10, 'supplier_disbursement_charge', 'Supplier Disbursement Charge', '5550', 'value must be integer', '1', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

CREATE TABLE `bulk_payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bulk_payment_number` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `order_transaction_id` bigint(20) unsigned DEFAULT NULL,
  `total_amount` double NOT NULL,
  `total_discounted_amount` double NOT NULL,
  `payable_amount` double NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bulk_payments_user_id_foreign` (`user_id`),
  KEY `bulk_payments_supplier_id_foreign` (`supplier_id`),
  KEY `bulk_payments_order_transaction_id_foreign` (`order_transaction_id`),
  CONSTRAINT `bulk_payments_order_transaction_id_foreign` FOREIGN KEY (`order_transaction_id`) REFERENCES `order_transactions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bulk_payments_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bulk_payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `bulk_order_payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bulk_payment_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `quote_id` bigint(20) unsigned NOT NULL,
  `rfq_id` bigint(20) unsigned NOT NULL,
  `discounted_amount` double NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bulk_order_payments_bulk_payments_id_foreign` (`bulk_payment_id`),
  KEY `bulk_order_payments_order_id_foreign` (`order_id`),
  KEY `bulk_order_payments_quote_id_foreign` (`quote_id`),
  KEY `bulk_order_payments_rfq_id_foreign` (`rfq_id`),
  CONSTRAINT `bulk_order_payments_bulk_payments_id_foreign` FOREIGN KEY (`bulk_payment_id`) REFERENCES `bulk_payments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bulk_order_payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bulk_order_payments_quote_id_foreign` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bulk_order_payments_rfq_id_foreign` FOREIGN KEY (`rfq_id`) REFERENCES `rfqs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `order_transactions` ADD `bulk_payment_id` TEXT NULL AFTER `external_id`;

ALTER TABLE order_transactions DROP FOREIGN KEY order_transactions_order_id_foreign, MODIFY order_id bigint UNSIGNED null;

ALTER TABLE order_transactions ADD CONSTRAINT  `order_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

UPDATE `settings` SET `value` = '{\"max_invoice_generate\":3,\"cash_invoice_valid_hours\":[72,48,48],\"invoice_valid_days\":2,\"bulk_invoice_valid_hours\":24,\"credit_invoice_extra_days\":7,\"invoice_reminder_time_unit\":\"days\",\"invoice_reminder_time\":1}' WHERE `settings`.`id` = 3;

-- 21-03-2022 (Ekta)
ALTER TABLE `users` ADD COLUMN `google_id` VARCHAR(512)  NULL AFTER `first_login`;
ALTER TABLE `users` ADD COLUMN `fb_id` VARCHAR(512)  NULL AFTER `google_id`;
ALTER TABLE `users` ADD COLUMN `linkedin_id` VARCHAR(512)  NULL AFTER `fb_id`;

-- 25-03-2022 (Ronak Makwana)
CREATE TABLE `group_members` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `group_id` bigint unsigned NOT NULL,
 `user_id` bigint unsigned NOT NULL,
 `company_id` bigint unsigned NOT NULL,
 `rfq_id` bigint unsigned NOT NULL,
 `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `group_members_group_id_foreign` (`group_id`),
 KEY `group_members_user_id_foreign` (`user_id`),
 KEY `group_members_company_id_foreign` (`company_id`),
 KEY `group_members_rfq_id_foreign` (`rfq_id`),
 CONSTRAINT `group_members_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
 CONSTRAINT `group_members_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
 CONSTRAINT `group_members_rfq_id_foreign` FOREIGN KEY (`rfq_id`) REFERENCES `rfqs` (`id`) ON DELETE CASCADE,
 CONSTRAINT `group_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

-- 24-03-2021 munir
CREATE TABLE `xendit_request_responses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `xendit_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

--07-04-2022 (Ronak Makwana)
INSERT INTO `settings` (`id`, `key`, `name`, `value`, `description`, `status`, `is_deleted`, `created_at`) VALUES (NULL, 'load_more_groups', 'Load More Groups', '6', 'Count of groups for group trading module', '1', '0', CURRENT_TIMESTAMP);


-- 01-04-2022 Ronak Bhabhor
ALTER TABLE `users` ADD `salutation` VARCHAR(20) AFTER `id`;
ALTER TABLE `suppliers` ADD `salutation` VARCHAR(20) AFTER `address`;
ALTER TABLE `suppliers` ADD `contact_person_last_name` VARCHAR(255) AFTER `contact_person_name`;

-- 04-04-2022 Ronak Bhabhor
-- ALTER TABLE `user_addresses` ADD `salutation` VARCHAR(20) AFTER `id`; // ignore

-- 04-04-2022 Ronak Bhabhor
ALTER TABLE `user_addresses` ADD `default_address` tinyint(1) DEFAULT 0 AFTER `state`

-- 25-04-2022 Ronak Makwana
ALTER TABLE `groups` ADD `achieved_quantity` double NOT NULL AFTER `reached_quantity`;

-- 28-04-2022 Ronak Makwana
ALTER TABLE `rfqs` ADD CONSTRAINT  `rfqs_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`);
-- 18-04-2022 Ekta Patel
ALTER TABLE `suppliers` ADD `group_margin` DOUBLE DEFAULT 0 AFTER `xen_platform_id`
UPDATE `suppliers` SET `group_margin` = '0' WHERE `is_deleted` = 0;

-- 19-04-2022 Ekta Patel
ALTER TABLE `supplier_products` ADD `max_quantity` int  AFTER `min_quantity`
UPDATE `supplier_products` SET max_quantity = min_quantity WHERE  max_quantity = 0

CREATE TABLE `supplier_product_discount_ranges` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `supplier_product_id` bigint unsigned NOT NULL,
 `product_id` bigint unsigned NOT NULL,
 `supplier_id` bigint unsigned NOT NULL,
 `min_qty` int NOT NULL,
 `max_qty` int NOT NULL,
 `unit_id` bigint unsigned DEFAULT NULL,
 `discount` varchar(215) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
 `discounted_price` varchar(215) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
 `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `supplier_product_discount_ranges_supplier_product_id_foreign` (`supplier_product_id`),
 KEY `supplier_product_discount_ranges_product_id_foreign` (`product_id`),
 KEY `supplier_product_discount_ranges_supplier_id_foreign` (`supplier_id`),
 KEY `supplier_product_discount_ranges_unit_id_foreign` (`unit_id`),
 CONSTRAINT `supplier_product_discount_ranges_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
 CONSTRAINT `supplier_product_discount_ranges_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
 CONSTRAINT `supplier_product_discount_ranges_supplier_product_id_foreign` FOREIGN KEY (`supplier_product_id`) REFERENCES `supplier_products` (`id`) ON DELETE CASCADE,
 CONSTRAINT `supplier_product_discount_ranges_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

-- 05-05-2022 Ekta Patel Group All Query
CREATE TABLE `groups` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `name` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `group_number` varchar(255) NOT NULL,
 `category_id` bigint unsigned DEFAULT NULL,
 `subCategory_id` bigint unsigned DEFAULT NULL,
 `product_id` bigint unsigned DEFAULT NULL,
 `unit_id` bigint unsigned DEFAULT NULL,
 `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
 `end_date` date NOT NULL,
 `reached_quantity` double NOT NULL,
 `status` int NOT NULL DEFAULT '0',
 `group_status` bigint NOT NULL DEFAULT '1',
 `location_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
 `price` double NOT NULL,
 `min_order_quantity` double DEFAULT NULL,
 `max_order_quantity` double DEFAULT NULL,
 `group_margin` double NOT NULL DEFAULT '0',
 `target_quantity` double NOT NULL DEFAULT '0',
 `social_token` varchar(255) NOT NULL,
 `added_by` bigint DEFAULT NULL,
 `updated_by` bigint DEFAULT NULL,
 `deleted_by` bigint DEFAULT NULL,
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `deleted_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

==========================================================================================

CREATE TABLE `group_images` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `group_id` bigint NOT NULL,
 `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
 `added_by` bigint DEFAULT NULL,
 `updated_by` bigint DEFAULT NULL,
 `deleted_by` bigint DEFAULT NULL,
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `deleted_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

==============================================================================================

CREATE TABLE `group_members` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `group_id` bigint unsigned NOT NULL,
 `user_id` bigint unsigned NOT NULL,
 `company_id` bigint unsigned NOT NULL,
 `rfq_id` bigint unsigned NOT NULL,
 `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `group_members_group_id_foreign` (`group_id`),
 KEY `group_members_user_id_foreign` (`user_id`),
 KEY `group_members_company_id_foreign` (`company_id`),
 KEY `group_members_rfq_id_foreign` (`rfq_id`),
 CONSTRAINT `group_members_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
 CONSTRAINT `group_members_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
 CONSTRAINT `group_members_rfq_id_foreign` FOREIGN KEY (`rfq_id`) REFERENCES `rfqs` (`id`) ON DELETE CASCADE,
 CONSTRAINT `group_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

=================================================================================================

CREATE TABLE `group_activities` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `user_id` bigint unsigned NOT NULL DEFAULT '1',
 `group_id` bigint unsigned NOT NULL,
 `key_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
 `old_value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
 `new_value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
 `user_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
 `is_deleted` tinyint NOT NULL DEFAULT '0',
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `group_activities_user_id_foreign` (`user_id`),
 KEY `group_activities_group_id_foreign` (`group_id`),
 CONSTRAINT `group_activities_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE,
 CONSTRAINT `group_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

==========================================================================================

CREATE TABLE `group_status` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `name` varchar(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `description` varchar(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `status` tinyint(1) NOT NULL DEFAULT '1',
 `show_order_id` int DEFAULT NULL,
 `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


INSERT INTO `group_status` (`id`, `name`, `description`, `status`, `show_order_id`, `is_deleted`, `created_at`) VALUES
(1, 'Open', 'Open', 1, 1, 0, '2022-04-06 09:32:23'),
(2, 'Hold', 'Hold', 1, 2, 0, '2022-04-06 09:48:35'),
(3, 'Close', 'Close', 1, 3, 0, '2022-04-06 09:49:17'),
(4, 'Expire', 'Expire', 1, 4, 0, '2022-04-06 09:49:17');

==========================================================================================

CREATE TABLE `group_suppliers` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `supplier_id` bigint NOT NULL,
 `group_id` bigint NOT NULL,
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `deleted_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

==========================================================================================

CREATE TABLE `group_supplier_discount_options` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `group_supplier_id` bigint NOT NULL,
 `group_id` bigint NOT NULL,
 `min_quantity` double NOT NULL,
 `max_quantity` double NOT NULL,
 `unit_id` bigint NOT NULL,
 `discount` double NOT NULL,
 `discount_price` double NOT NULL DEFAULT '0',
 `added_by` bigint DEFAULT NULL,
 `updated_by` bigint DEFAULT NULL,
 `deleted_by` bigint DEFAULT NULL,
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `deleted_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

==========================================================================================

CREATE TABLE `group_tags` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `group_id` bigint NOT NULL,
 `tag` text COLLATE utf8mb4_unicode_ci,
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `deleted_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 22-04-2022 munir
UPDATE `settings` SET `value` = '{\"max_invoice_generate\":3,\"cash_invoice_valid_hours\":[72,48,48],\"admin_create_invoice_valid_hours\":48,\"bulk_invoice_valid_hours\":1,\"credit_invoice_extra_days\":7,\"invoice_reminder_time_unit\":\"days\",\"invoice_reminder_time\":1}' WHERE `settings`.`id` = 3;

-- 13-05-2022 Ronak Makwana
INSERT INTO `other_charges` (`id`, `name`, `description`, `type`, `charges_value`, `value_on`, `charges_type`, `status`, `addition_substraction`, `is_deleted`, `added_by`, `updated_by`, `deleted_by`, `created_at`) VALUES (NULL, 'Group Discount', 'Group Discount will be applicable when order placed quantity will be fall under discount range', '0', '0', '0', '0', '1', '0', '0', '1', '1', NULL, CURRENT_TIMESTAMP);
-- 12-05-2022 Ronak K
CREATE TABLE `contact_us` (
  `id` bigint(20) NOT NULL ,
  `fullname` varchar(255) NULL,
  `company_name` varchar(255) NULL,
  `mobile` varchar(255) NULL,
  `email` varchar(255) NULL,
  `message` varchar(255) NULL
);

--12-05-2022 Vrutika
ALTER TABLE `subscribed_users` DROP `firstname`,DROP `lastname`;

ALTER table `subscribed_users` ADD `fullname` varchar(255) AFTER `id`;
ALTER table `subscribed_users` ADD `mobile` varchar(255) AFTER `email`;

--19-05-2022 Vrutika
ALTER table `companies` ADD `termsconditions_file` varchar(512) AFTER `npwp_file`;
ALTER table `rfqs` ADD `termsconditions_file` varchar(512) AFTER `attached_document`;

--23-05-2022 Vrutika
CREATE TABLE `terms_conditions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `buyer_default_tcdoc` varchar(255) NULL,
  `supplier_default_tcdoc` varchar(255) NULL,
);
--25-05-2022 Vrutika
ALTER table `quotes` ADD `termsconditions_file` varchar(512) AFTER `certificate`;

---02-05-2022 Mittal
ALTER TABLE users ADD CONSTRAINT  `users_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`);
ALTER TABLE users ADD CONSTRAINT  `users_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);


-- 23-05-2022 Munir
INSERT INTO `settings` (`id`, `key`, `name`, `value`, `description`, `status`, `is_deleted`, `created_at`) VALUES (12, 'xendit_group_invoice', 'Xendit Group Invoice', '{\"invoice_valid_hours\":4,\"invoice_reminder_time_unit\":\"days\",\"invoice_reminder_time\":1}', 'value must be json_encode array', '1', '0', CURRENT_TIMESTAMP);

-- 27-05-2022 Munir
INSERT INTO `settings` (`id`, `key`, `name`, `value`, `description`, `status`, `is_deleted`, `created_at`) VALUES (13, 'xendit_dev_token', 'Xendit Gamma Token', 'xnd_public_development_uf5bTy9f2CqpFNnT6yRbCBOQ2UJUti8hYUKZzlDwZXChHX1LIVRQYacFVhPVw', 'value is string', '1', '0', CURRENT_TIMESTAMP);
-- 30-5-2022 Munir
UPDATE `settings` SET `value` = 'xnd_development_KgbLx0e45zuobS7sL8xeNiU2nzQLAOHLfnSbvqUVizVSwax4VC106ThKJx9SO' WHERE `settings`.`id` = 13;

-- 03-06-2022 this query change by Munir
INSERT INTO `settings` (`id`, `key`, `name`, `value`, `description`, `status`, `is_deleted`, `created_at`) VALUES (14, 'load_more_groups', 'Load More Groups', '6', 'Count of groups for group trading module', '1', '0', CURRENT_TIMESTAMP);

-- 03-06-2022 by Munir
UPDATE `settings` SET `key` = 'disbursement_charge' WHERE `settings`.`id` = 10;
UPDATE `settings` SET `name` = 'Disbursement Charge' WHERE `settings`.`id` = 10;


-- 15-07-2022 Arun Caht Query
UPDATE `chat_quick_message` SET `header_type`="Rfq" WHERE `role_id` in (1,2,3)
INSERT INTO `chat_quick_message` (`message`, `role_id`, `status`, `header_type`, `created_at`, `updated_at`, `deleted_at`) VALUES
('I have sent you the best possible Quote.', 3, 1, 'Quote', '2022-07-13 05:58:38', '2022-07-13 05:58:38', NULL),
('Please place an Order.', 3, 1, 'Quote', '2022-07-13 05:58:38', '2022-07-13 05:58:51', NULL),
('Will you please extend your expected delivery date?', 3, 1, 'Quote', '2022-07-13 05:58:38', '2022-07-13 05:59:27', NULL),
('I have my own logistic.', 3, 1, 'Quote', '2022-07-13 05:58:38', '2022-07-13 05:59:27', NULL),
('Our team is working on your request, we will get back to you soon.', 1, 1, 'Quote', '2022-07-13 05:58:38', '2022-07-13 06:01:36', NULL),
('Can you negotiate with the Quote?', 2, 1, 'Quote', '2022-07-13 05:58:38', '2022-07-13 06:03:47', NULL),
('Can you deliver the product sooner?', 2, 1, 'Quote', '2022-07-13 05:58:38', '2022-07-13 06:03:47', NULL),
('Is this your best offer?', 2, 1, 'Quote', '2022-07-13 05:58:38', '2022-07-13 06:03:47', NULL),
('Thank you for your Quote.', 2, 1, 'Quote', '2022-07-13 05:58:38', '2022-07-13 06:03:47', NULL),
('No problem I will wait for your better deal.', 2, 1, 'Quote', '2022-07-13 05:58:38', '2022-07-13 06:03:47', NULL),
('I will check your Quote and come back to you soon.', 2, 1, 'Quote', '2022-07-13 05:58:38', '2022-07-13 06:09:56', NULL);

-- 13-07-2022 by Vrutika
INSERT INTO `settings` (`id`, `key`, `name`, `value`, `description`, `status`, `is_deleted`, `created_at`) VALUES (NULL, 'multiple_rfq_max_added_product', 'Maximum Added Product', '7', NULL, '1', '0', CURRENT_TIMESTAMP);



INSERT INTO `ltm_translations` (`status`, `locale`, `group`, `key`, `value`, `created_at`, `updated_at`) VALUES
(0, 'id', 'admin', 'more_info', 'Info lebih lanjut', '2022-07-11 13:03:43', '2022-07-11 13:03:47'),
(0, 'en', 'admin', 'more_info', 'More Info', '2022-07-11 13:03:08', '2022-07-11 13:03:47'),
(0, 'id', 'admin', 'quote_info', 'Info Kutipan', '2022-07-13 11:23:45', '2022-07-13 11:23:56'),
(0, 'en', 'admin', 'quote_info', 'Quote Info', '2022-07-13 11:23:00', '2022-07-13 11:23:56'),
(0, 'en', 'admin', 'back', 'Back', '2022-07-14 12:40:16', '2022-07-14 12:40:30'),
(0, 'id', 'admin', 'back', 'Kembali', '2022-07-14 12:39:47', '2022-07-14 12:40:30'),
(0, 'id', 'admin', 'invite_for_login', 'Undang Untuk Masuk', '2022-07-15 06:04:08', '2022-07-15 06:04:34'),
(0, 'en', 'admin', 'invite_for_login', 'Invite For Login', '2022-07-15 06:02:45', '2022-07-15 06:04:34');
UPDATE `ltm_translations` SET `value` = 'Hi there!' WHERE `locale` = 'en' and `key`='need_help_msg';
UPDATE `ltm_translations` SET `value` = 'Hai, yang di sana!' WHERE `locale` = 'id' and `key`='need_help_msg';
UPDATE `ltm_translations` SET `value` = 'No Chat Found' WHERE `locale` = 'en' and `key`='no_chat_found';
UPDATE `ltm_translations` SET `value` = 'Tidak Ada Obrolan Ditemukan' WHERE `locale` = 'id' and `key`='no_chat_found';

INSERT INTO `ltm_translations` (`status`, `locale`, `group`, `key`, `value`, `created_at`, `updated_at`) VALUES
(0, 'en', 'admin', 'blitznet_team', 'blitznet Team', '2022-07-15 07:49:41', '2022-07-15 07:59:32'),
(0, 'id', 'admin', 'blitznet_team', 'blitznet Team', '2022-07-15 07:49:22', '2022-07-15 07:59:32'),
(0, 'en', 'admin', 'no_quote_chat_group', 'No Quotes matched your search.', '2022-07-15 08:39:24', '2022-07-15 08:39:42'),
(0, 'id', 'admin', 'no_quote_chat_group', 'Tidak ada Kutipan yang cocok dengan pencarian Anda.', '2022-07-15 08:39:00', '2022-07-15 08:39:42'),
(0, 'en', 'admin', 'no_rfq_chat_group', 'No RFQs matched your search', '2022-07-15 08:38:10', '2022-07-15 08:39:42'),
(0, 'id', 'admin', 'no_rfq_chat_group', 'Tidak ada RFQ yang cocok dengan pencarian Anda', '2022-07-15 08:37:33', '2022-07-15 08:39:42'),
(0, 'en', 'admin', 'chat_history', 'Chat History', '2022-07-15 11:13:12', '2022-07-15 11:13:26'),
(0, 'id', 'admin', 'chat_history', 'Riwayat Obrolan', '2022-07-15 11:12:48', '2022-07-15 11:13:26');

--- 26/07/22 by Vrutika ---
INSERT INTO `settings` (`id`, `key`, `name`, `value`, `description`, `status`, `is_deleted`, `created_at`) VALUES (NULL, 'multiple_rfq_attachments', 'Maximum Rfq Attachments', '3', 'Number of Added attachments while posting RFQ', '1', '0', '2022-07-26 16:06:25');
