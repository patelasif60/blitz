ALTER TABLE `categories` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `sub_categories` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `products` DROP `unit_id`;

ALTER TABLE `products` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
