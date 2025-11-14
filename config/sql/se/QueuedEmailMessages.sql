CREATE TABLE IF NOT EXISTS `QueuedEmailMessages` (
  `QueuedEmailMessageId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Timestamp` datetime NOT NULL,
  `Email` varchar(80) NOT NULL,
  `From` varchar(80) DEFAULT NULL,
  `FromName` varchar(80) DEFAULT NULL,
  `ReplyTo` varchar(80) DEFAULT NULL,
  `Subject` varchar(255) DEFAULT NULL,
  `BodyHtml` longtext DEFAULT NULL,
  `BodyText` longtext DEFAULT NULL,
  `Priority` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `EmailType` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `UnsubscribeUrl` text DEFAULT NULL,
  PRIMARY KEY (`QueuedEmailMessageId`),
  KEY `idxStatus` (`Priority`,`QueuedEmailMessageId`),
  KEY `idxEmailType` (`EmailType`,`Priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
