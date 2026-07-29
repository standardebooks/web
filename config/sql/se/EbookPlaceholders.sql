CREATE TABLE IF NOT EXISTS `EbookPlaceholders` (
  `EbookId` int(10) unsigned NOT NULL,
  `YearPublished` smallint(5) unsigned DEFAULT NULL,
  `Difficulty` enum('beginner','intermediate','advanced') DEFAULT NULL,
  `TranscriptionUrl` varchar(511) DEFAULT NULL,
  `IsWanted` tinyint(1) NOT NULL DEFAULT 0,
  `IsInProgress` tinyint(1) NOT NULL DEFAULT 0,
  `Notes` text DEFAULT NULL,
  PRIMARY KEY (`EbookId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
