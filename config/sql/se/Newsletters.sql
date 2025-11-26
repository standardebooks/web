CREATE TABLE IF NOT EXISTS `Newsletters` (
  `NewsletterId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(80) NOT NULL,
  `UrlName` varchar(80) NOT NULL,
  `Description` mediumtext DEFAULT NULL,
  PRIMARY KEY (`NewsletterId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
