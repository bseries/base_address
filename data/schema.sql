CREATE TABLE `addresses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `recipient` varchar(250) DEFAULT '',
  `organization` varchar(250) DEFAULT NULL,
  `address_line_1` varchar(250) DEFAULT NULL,
  `address_line_2` varchar(250) DEFAULT NULL,
  `locality` varchar(100) DEFAULT NULL COMMENT 'city',
  `dependent_locality` varchar(100) DEFAULT NULL COMMENT 'neighborhood',
  `postal_code` varchar(100) DEFAULT NULL COMMENT 'zip',
  `sorting_code` varchar(100) DEFAULT NULL COMMENT 'CEDEX',
  `country` char(2) DEFAULT 'DE',
  `administrative_area` varchar(200) DEFAULT NULL,
  `phone` varchar(200) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
