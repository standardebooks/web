CREATE TABLE IF NOT EXISTS `Collections` (
  `CollectionId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `UrlName` varchar(255) NOT NULL,
  `Type` enum('series', 'set') NULL,
  `ArePlaceholdersComplete` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`CollectionId`),
  UNIQUE KEY `idxUnique` (`UrlName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
