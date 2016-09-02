CREATE TABLE IF NOT EXISTS `dt_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `attempts` smallint(6) NOT NULL,
  `attempt_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attempt` (`user_email`,`ip_address`,`attempt_time`),
  KEY `email_ip` (`user_email`,`ip_address`),
  KEY `email_time` (`user_email`,`attempt_time`),
  KEY `ip_time` (`ip_address`,`attempt_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `dt_registration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `activate_code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `reg_date` datetime NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;