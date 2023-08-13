CREATE TABLE `Artists` (
  `ArtistId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(191) NOT NULL,
  `UrlName` varchar(255) NOT NULL,
  `DeathYear` smallint(5) unsigned DEFAULT NULL,
  `PhoneticName` varchar(255) NOT NULL,
  PRIMARY KEY (`ArtistId`),
  UNIQUE KEY `idxUnique` (`Name`,`DeathYear`),
  FULLTEXT KEY `ArtistsFullText` (`Name`,`UrlName`,`PhoneticName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
