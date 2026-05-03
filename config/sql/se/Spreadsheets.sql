CREATE TABLE IF NOT EXISTS `se`.`Spreadsheets` (
  `SpreadsheetId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Title` VARCHAR(255) NOT NULL,
  `ExternalUrl` VARCHAR(255) NOT NULL,
  `Category` ENUM('available', 'help_wanted', 'incomplete', 'complete', 'legacy') NOT NULL,
  `Notes` TEXT NULL DEFAULT NULL,
  `SortOrder` SMALLINT UNSIGNED NOT NULL,
  `Created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `Updated` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`SpreadsheetId`),
  UNIQUE KEY `idxExternalUrl` (`ExternalUrl`),
  KEY `idxCategorySortOrder` (`Category`, `SortOrder`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
