CREATE TABLE `Artworks` (
  `ArtworkId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ArtistId` int(10) unsigned NOT NULL,
  `Name` varchar(255) NOT NULL,
  `UrlName` varchar(255) NOT NULL,
  `CompletedYear` smallint unsigned NULL,
  `CompletedYearIsCirca` boolean NOT NULL DEFAULT FALSE,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Status` enum('unverified', 'approved', 'declined', 'in_use') DEFAULT 'unverified',
  `MuseumPage` varchar(255) NULL,
  `PublicationYear` smallint unsigned NULL,
  `PublicationYearPage` varchar(255) NULL,
  `CopyrightPage` varchar(255) NULL,
  `ArtworkPage` varchar(255) NULL,
  `EbookWwwFilesystemPath` varchar(255) NULL,
  PRIMARY KEY (`ArtworkId`),
  KEY `index1` (`Status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
