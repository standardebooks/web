CREATE TABLE IF NOT EXISTS `se`.`Spreadsheets` (
  `SpreadsheetId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Title` VARCHAR(255) NOT NULL,
  `ExternalUrl` VARCHAR(255) NOT NULL,
  `Category` ENUM('available', 'help_wanted', 'incomplete', 'complete', 'legacy') NOT NULL,
  `Notes` TEXT NULL DEFAULT NULL,
  `SortOrder` SMALLINT UNSIGNED NOT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`SpreadsheetId`),
  UNIQUE KEY `ExternalUrl` (`ExternalUrl`),
  KEY `Category_SortOrder` (`Category`, `SortOrder`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
