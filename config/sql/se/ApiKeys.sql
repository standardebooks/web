CREATE TABLE `ApiKeys` (
  `UserId` int(10) unsigned NOT NULL,
  `Created` datetime NOT NULL,
  `Ended` datetime DEFAULT NULL,
  `Notes` text DEFAULT NULL,
  KEY `idxUserId` (`UserId`,`Ended`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
