CREATE TABLE `Artworks` (
  `ArtworkId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ArtistId` int(10) unsigned NOT NULL,
  `Name` varchar(255) NOT NULL,
  `UrlName` varchar(255) NOT NULL,
  `CompletedYear` smallint unsigned NULL,
  `CompletedYearIsCirca` boolean NOT NULL DEFAULT FALSE,
  `ImageFilesystemPath` varchar(255) NOT NULL,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Status` enum('unverified', 'approved', 'declined', 'in_use') DEFAULT 'unverified',
  PRIMARY KEY (`ArtworkId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
