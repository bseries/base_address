ALTER TABLE `addresses` ADD `administrative_area` VARCHAR(200)  NULL  DEFAULT NULL  AFTER `zip`;
ALTER TABLE `addresses` CHANGE `city` `locality` VARCHAR(100)  NULL  DEFAULT NULL  COMMENT 'city';
ALTER TABLE `addresses` ADD `dependent_locality` VARCHAR(100)  NULL  DEFAULT NULL  COMMENT 'neighborhood'  AFTER `locality`;
ALTER TABLE `addresses` CHANGE `zip` `postal_code` VARCHAR(100)  NULL  DEFAULT NULL  COMMENT 'zip';
ALTER TABLE `addresses` CHANGE `street` `address_line_1` VARCHAR(250)  NULL  DEFAULT NULL;
ALTER TABLE `addresses` ADD `address_line_2` VARCHAR(250)  NULL  DEFAULT NULL  AFTER `address_line_1`;
ALTER TABLE `addresses` ADD `sorting_code` VARCHAR(100)  NULL  DEFAULT NULL  COMMENT 'CEDEX'  AFTER `postal_code`;
ALTER TABLE `addresses` CHANGE `company` `organization` VARCHAR(250)  NULL  DEFAULT NULL;
ALTER TABLE `addresses` MODIFY COLUMN `administrative_area` VARCHAR(200) DEFAULT NULL AFTER `country`;
ALTER TABLE `addresses` CHANGE `name` `recipient` VARCHAR(250)  NULL  DEFAULT '';


