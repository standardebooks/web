CREATE TABLE IF NOT EXISTS `Patrons` (
  `UserId` int(10) unsigned NOT NULL,
  `IsAnonymous` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `AlternateName` varchar(80) DEFAULT NULL,
  `IsSubscribedToEmails` tinyint(1) NOT NULL DEFAULT 1,
  `BaseCost` DECIMAL(5,2) UNSIGNED NULL DEFAULT,
  `CycleType` enum('monthly','yearly','unlimited') NULL DEFAULT NULL,
  `Created` datetime NOT NULL,
  `Ended` datetime DEFAULT NULL,
  KEY `index2` (`IsAnonymous`,`Ended`),
  KEY `index1` (`UserId`,`Ended`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
