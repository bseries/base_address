CREATE TABLE `addresses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `virtual_user_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(250) DEFAULT '',
  `company` varchar(250) DEFAULT NULL,
  `street` varchar(250) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `zip` varchar(100) DEFAULT NULL,
  `country` char(2) DEFAULT 'DE',
  `phone` varchar(200) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `virtual_user_id` (`virtual_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

