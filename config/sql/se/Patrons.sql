CREATE TABLE `Patrons` (
  `UserId` int(10) unsigned NOT NULL,
  `IsAnonymous` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `AlternateName` varchar(80) DEFAULT NULL,
  `IsSubscribedToEmails` tinyint(1) NOT NULL DEFAULT 1,
  `Timestamp` datetime NOT NULL,
  `DeactivatedTimestamp` datetime DEFAULT NULL,
  PRIMARY KEY (`UserId`),
  KEY `index2` (`IsAnonymous`,`DeactivatedTimestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
