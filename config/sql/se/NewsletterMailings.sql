CREATE TABLE IF NOT EXISTS `NewsletterMailings` (
  `NewsletterMailingId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NewsletterId` int(10) unsigned NOT NULL,
  `Status` enum('queued','processing','completed','failed','canceled') NOT NULL DEFAULT 'queued',
  `Subject` varchar(255) NOT NULL,
  `BodyHtml` text NOT NULL,
  `BodyText` text NOT NULL,
  `FromName` varchar(255) DEFAULT NULL,
  `FromEmail` varchar(255) NOT NULL,
  `SendOn` datetime NOT NULL,
  `RecipientCount` int(10) unsigned DEFAULT NULL,
  `OpenCount` int(10) unsigned DEFAULT NULL,
  `InternalName` varchar(255) DEFAULT NULL,
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Updated` timestamp NOT NULL DEFAULT current_timestamp() on update current_timestamp(),
  PRIMARY KEY (`NewsletterMailingId`),
  KEY `idxStatus` (`Status`,`SendOn`)
) ENGINE=InnoDB AUTO_INCREMENT=190 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
