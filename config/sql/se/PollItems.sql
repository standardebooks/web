CREATE TABLE IF NOT EXISTS `PollItems` (
  `PollItemId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `PollId` int(10) unsigned NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `SortOrder` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`PollItemId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
