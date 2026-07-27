CREATE TABLE IF NOT EXISTS `Polls` (
  `PollId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `UrlName` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `StartAt` datetime NOT NULL,
  `EndAt` datetime NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`PollId`),
  UNIQUE KEY `UrlName` (`UrlName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
