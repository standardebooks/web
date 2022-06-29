CREATE TABLE `Polls` (
  `PollId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Created` datetime NOT NULL,
  `Name` varchar(255) NOT NULL,
  `UrlName` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `Start` datetime DEFAULT NULL,
  `End` datetime DEFAULT NULL,
  PRIMARY KEY (`PollId`),
  UNIQUE KEY `idxUnique` (`UrlName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
