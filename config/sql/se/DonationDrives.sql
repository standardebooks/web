CREATE TABLE IF NOT EXISTS `DonationDrives` (
  `DonationDriveId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `StartAt` datetime NOT NULL,
  `EndAt` datetime NOT NULL,
  `TargetType` enum('new_patrons') NOT NULL,
  `Target` int(10) unsigned NOT NULL,
  `StretchTarget` int(10) unsigned DEFAULT NULL,
  `Count` int(10) unsigned NOT NULL DEFAULT 0,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`DonationDriveId`),
  KEY `StartAt_EndAt_TargetType` (`StartAt`,`EndAt`,`TargetType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
