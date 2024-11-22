CREATE TABLE IF NOT EXISTS `EbookPlaceholders` (
  `EbookId` int(10) unsigned NOT NULL,
  `YearPublished` smallint unsigned NULL,
  `Status` enum('wanted', 'in_progress') NOT NULL DEFAULT 'wanted',
  `Difficulty` enum('beginner', 'intermediate', 'advanced') NULL,
  `TranscriptionUrl` varchar(511) NULL,
  `IsWanted` boolean NOT NULL DEFAULT FALSE,
  `IsPatron` boolean NOT NULL DEFAULT FALSE,
  `Notes` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`EbookId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
