CREATE TABLE `ArtistAlternateSpellings` (
  `ArtistId` int(10) unsigned NOT NULL,
  `Name` varchar(255) NOT NULL,
  `UrlName` varchar(255) NOT NULL,
  UNIQUE KEY `idxUnique` (`ArtistId`,`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
