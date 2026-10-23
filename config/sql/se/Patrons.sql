CREATE TABLE IF NOT EXISTS `Patrons` (
  `PatronId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserId` int(10) unsigned NOT NULL,
  `SubscriptionId` int(10) unsigned DEFAULT NULL,
  `QualifyingPaymentId` int(10) unsigned DEFAULT NULL,
  `IsAnonymous` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `AlternateName` varchar(80) DEFAULT NULL,
  `EndedAt` datetime DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`PatronId`),
  KEY `IsAnonymous_EndedAt` (`IsAnonymous`,`EndedAt`),
  KEY `UserId_EndedAt` (`UserId`,`EndedAt`),
  KEY `SubscriptionId_EndedAt` (`SubscriptionId`,`EndedAt`),
  KEY `QualifyingPaymentId` (`QualifyingPaymentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
