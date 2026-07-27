CREATE TABLE IF NOT EXISTS `Payments` (
  `PaymentId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserId` int(10) unsigned DEFAULT NULL,
  `Processor` enum('fractured_atlas') NOT NULL,
  `TransactionId` varchar(80) NOT NULL,
  `Amount` decimal(7,2) unsigned NOT NULL,
  `Fee` decimal(7,2) unsigned NOT NULL DEFAULT 0.00,
  `IsRecurring` tinyint(1) unsigned NOT NULL,
  `IsMatchingDonation` tinyint(1) unsigned NOT NULL,
  `RefundedAt` datetime NULL DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`PaymentId`),
  KEY `UserId_Amount_CreatedAt_IsRecurring` (`UserId`,`Amount`,`CreatedAt`,`IsRecurring`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
