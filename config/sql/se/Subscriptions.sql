CREATE TABLE IF NOT EXISTS `Subscriptions` (
  `SubscriptionId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserId` int(10) unsigned NOT NULL,
  `CreatedAt` datetime NOT NULL,
  `NextPaymentAt` datetime DEFAULT NULL,
  `EndedAt` datetime DEFAULT NULL,
  `Processor` enum('stripe','fractured_atlas') NOT NULL,
  `ProcessorSubscriptionId` varchar(80) DEFAULT NULL,
  `Amount` decimal(8,2) unsigned NOT NULL DEFAULT 0.00,
  `CycleType` enum('monthly','yearly') NOT NULL DEFAULT 'monthly',
  PRIMARY KEY (`SubscriptionId`),
  UNIQUE KEY `Processor_ProcessorSubscriptionId` (`Processor`,`ProcessorSubscriptionId`),
  KEY `UserId_EndedAt` (`UserId`,`EndedAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
