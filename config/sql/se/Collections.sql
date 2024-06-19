CREATE TABLE `Collections` (
  `CollectionId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `UrlName` varchar(255) NOT NULL,
  `Type` varchar(255) NULL,
  PRIMARY KEY (`CollectionId`),
  UNIQUE KEY `idxUnique` (`UrlName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
