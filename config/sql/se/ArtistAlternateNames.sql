CREATE TABLE IF NOT EXISTS `ArtistAlternateNames` (
  `ArtistId` int(10) unsigned NOT NULL,
  `Name` varchar(255) NOT NULL,
  `UrlName` varchar(255) NOT NULL,
  UNIQUE KEY `ArtistId_Name` (`ArtistId`,`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
