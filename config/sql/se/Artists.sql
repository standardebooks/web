CREATE TABLE `Artists` (
  `ArtistId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(191) NOT NULL,
  `UrlName` varchar(255) NOT NULL,
  `DeathYear` smallint unsigned NULL,
  PRIMARY KEY (`ArtistId`),
  UNIQUE KEY `idxUnique` (`UrlName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
