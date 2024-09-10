CREATE TABLE `Tags` (
  `TagId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `UrlName` varchar(255) NULL,
  `Type` enum('artwork', 'ebook') DEFAULT 'artwork',
  PRIMARY KEY (`TagId`),
  KEY `index1` (`Name`),
  KEY `index2` (`Type`),
  KEY `index3` (`UrlName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
