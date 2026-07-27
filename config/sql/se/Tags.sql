CREATE TABLE IF NOT EXISTS `Tags` (
  `TagId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `UrlName` varchar(255) NOT NULL,
  `Type` enum('artwork', 'ebook') DEFAULT 'artwork',
  PRIMARY KEY (`TagId`),
  KEY `Name` (`Name`),
  KEY `Type` (`Type`),
  KEY `UrlName` (`UrlName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
