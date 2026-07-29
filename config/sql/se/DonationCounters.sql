CREATE TABLE IF NOT EXISTS `DonationCounters` (
  `DonationCounterId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `StartAt` datetime NOT NULL,
  `EndAt` datetime NOT NULL,
  `MatchAmount` int(10) unsigned NOT NULL,
  `Count` int(10) unsigned NOT NULL,
  `ExternalUrl` varchar(255) DEFAULT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`DonationCounterId`),
  KEY `StartAt_EndAt` (`StartAt`,`EndAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
