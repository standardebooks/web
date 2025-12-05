CREATE TABLE IF NOT EXISTS `se`.`DonationDrives` (
  `DonationDriveId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(255) NOT NULL,
  `Start` DATETIME NOT NULL,
  `End` DATETIME NOT NULL,
  `TargetType` ENUM('new_patrons') NOT NULL,
  `Target` INT UNSIGNED NOT NULL,
  `StretchTarget` INT UNSIGNED NULL DEFAULT NULL,
  `Count` INT UNSIGNED NOT NULL DEFAULT 0,
  `Created` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `Updated` TIMESTAMP NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`DonationDriveId`),
  INDEX `idxIsRunning` (`Start` ASC, `End` ASC, `TargetType` ASC) VISIBLE);
