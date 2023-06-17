CREATE TABLE `Payments` (
  `PaymentId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserId` int(10) unsigned DEFAULT NULL,
  `Created` datetime NOT NULL,
  `ChannelId` tinyint(4) unsigned NOT NULL,
  `TransactionId` varchar(80) NOT NULL,
  `Amount` decimal(7,2) unsigned NOT NULL,
  `Fee` decimal(7,2) unsigned NOT NULL DEFAULT 0.00,
  `IsRecurring` tinyint(1) unsigned NOT NULL,
  `IsMatchingDonation` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`PaymentId`),
  KEY `index2` (`UserId`,`Amount`,`Created`,`IsRecurring`)
) ENGINE=InnoDB AUTO_INCREMENT=828 DEFAULT CHARSET=utf8mb4;
