CREATE TABLE `se`.`DonationCounters` (
  `DonationCounterId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Start` datetime NOT NULL,
  `End` datetime NOT NULL,
  `MatchAmount` int(10) unsigned NOT NULL,
  `Count` int(10) unsigned NOT NULL,
  `ExternalUrl` varchar(255) DEFAULT NULL,
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`DonationCounterId`),
  KEY `idxRunning` (`Start`,`End`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
