GRANT ALL PRIVILEGES ON *.* TO 'docker';


CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_seen` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user` (`id`, `name`, `surname`, `last_seen`) VALUES
	(1, 'Jacek', 'Testowy', '2021-03-16 11:42:22'),
	(2, 'Piotr', 'Ostrowski', '2022-02-16 14:23:34'),
	(3, 'Alfred', 'Ostrowski', '2023-02-08 21:05:01'),
	(4, 'Cyprian', 'Kwiatkowski', '2020-12-24 21:37:28');
