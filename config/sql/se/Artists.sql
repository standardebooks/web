CREATE TABLE `Artists` (
  `ArtistId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `UrlName` varchar(255) NOT NULL,
  `DeathYear` smallint unsigned NULL,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ArtistId`),
  UNIQUE KEY `idxUnique` (`UrlName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
