CREATE TABLE `AlternateSpellings` (
  `ArtistId` int(10) unsigned NOT NULL,
  `AlternateSpelling` varchar(255) NOT NULL,
  UNIQUE KEY `idxUnique` (`ArtistId`,`AlternateSpelling`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
