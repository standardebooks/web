CREATE TABLE `Tags` (
  `TagId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY (`TagId`),
  UNIQUE KEY `idxUnique` (`Name`),
  FULLTEXT KEY `TagsFullText` (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
