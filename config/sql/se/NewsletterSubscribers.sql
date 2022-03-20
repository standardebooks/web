CREATE TABLE `NewsletterSubscribers` (
  `NewsletterSubscriberId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Email` varchar(80) NOT NULL,
  `Uuid` char(36) NOT NULL,
  `FirstName` varchar(80) DEFAULT NULL,
  `LastName` varchar(80) DEFAULT NULL,
  `IsConfirmed` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `IsSubscribedToNewsletter` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `IsSubscribedToSummary` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `Timestamp` datetime NOT NULL,
  PRIMARY KEY (`NewsletterSubscriberId`),
  UNIQUE KEY `Uuid_UNIQUE` (`Uuid`),
  UNIQUE KEY `Email_UNIQUE` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
