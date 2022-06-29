CREATE TABLE `Users` (
  `UserId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Email` varchar(80) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Created` datetime NOT NULL,
  `Uuid` char(36) NOT NULL,
  PRIMARY KEY (`UserId`),
  UNIQUE KEY `idxEmail` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=281 DEFAULT CHARSET=utf8mb4;
