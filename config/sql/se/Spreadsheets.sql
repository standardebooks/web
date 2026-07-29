CREATE TABLE IF NOT EXISTS `Spreadsheets` (
  `SpreadsheetId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) NOT NULL,
  `ExternalUrl` varchar(255) NOT NULL,
  `Category` enum('available','help_wanted','incomplete','complete','legacy') NOT NULL,
  `Notes` text DEFAULT NULL,
  `SortOrder` smallint(5) unsigned NOT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`SpreadsheetId`),
  UNIQUE KEY `ExternalUrl` (`ExternalUrl`),
  KEY `Category_SortOrder` (`Category`,`SortOrder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
