CREATE TABLE IF NOT EXISTS `se`.`DonationDrives` (
  `DonationDriveId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(255) NOT NULL,
  `StartAt` DATETIME NOT NULL,
  `EndAt` DATETIME NOT NULL,
  `TargetType` ENUM('new_patrons') NOT NULL,
  `Target` INT UNSIGNED NOT NULL,
  `StretchTarget` INT UNSIGNED NULL DEFAULT NULL,
  `Count` INT UNSIGNED NOT NULL DEFAULT 0,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`DonationDriveId`),
  INDEX `StartAt_EndAt_TargetType` (`StartAt` ASC, `EndAt` ASC, `TargetType` ASC) VISIBLE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
