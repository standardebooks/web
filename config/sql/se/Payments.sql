CREATE TABLE IF NOT EXISTS `Payments` (
  `PaymentId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserId` int(10) unsigned DEFAULT NULL,
  `SubscriptionId` int(10) unsigned DEFAULT NULL,
  `Processor` enum('fractured_atlas','stripe') NOT NULL,
  `TransactionId` varchar(80) NOT NULL,
  `Amount` decimal(8,2) unsigned NOT NULL,
  `Fee` decimal(8,2) unsigned DEFAULT NULL,
  `IsRecurring` tinyint(1) unsigned NOT NULL,
  `IsMatchingDonation` tinyint(1) unsigned NOT NULL,
  `IsRecordedExternally` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `RefundedAt` datetime DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`PaymentId`),
  UNIQUE KEY `TransactionId` (`TransactionId`),
  KEY `SubscriptionId_CreatedAt` (`SubscriptionId`,`CreatedAt`),
  KEY `UserId_Amount_CreatedAt_IsRecurring` (`UserId`,`Amount`,`CreatedAt`,`IsRecurring`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
