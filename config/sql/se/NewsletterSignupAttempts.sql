CREATE TABLE IF NOT EXISTS `NewsletterSignupAttempts` (
  `NewsletterSignupAttemptId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `NewsletterId` int(10) unsigned NOT NULL,
  `Email` varchar(80) NOT NULL,
  `IpAddress` varbinary(16) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`NewsletterSignupAttemptId`),
  UNIQUE KEY `Email_NewsletterId` (`Email`,`NewsletterId`),
  UNIQUE KEY `IpAddress_NewsletterId` (`IpAddress`,`NewsletterId`),
  KEY `CreatedAt` (`CreatedAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
