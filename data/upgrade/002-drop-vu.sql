ALTER TABLE `addresses` DROP `virtual_user_id`;
ALTER TABLE `addresses` CHANGE `user_id` `user_id` INT(11)  UNSIGNED  NOT NULL;

