CREATE TABLE IF NOT EXISTS `Tags` (
  `TagId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY (`TagId`),
  UNIQUE KEY `idxUnique` (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
