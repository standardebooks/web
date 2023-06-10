CREATE TABLE `Tags` (
  `TagId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(80) NOT NULL,
  `UrlName` varchar(80) NOT NULL,
  PRIMARY KEY (`TagId`),
  UNIQUE KEY `idxUnique` (`UrlName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
