CREATE TABLE IF NOT EXISTS `PendingPayments` (
  `Processor` enum('fractured_atlas') NOT NULL,
  `TransactionId` varchar(80) NOT NULL,
  `ProcessedAt` datetime DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  KEY `ProcessedAt` (`ProcessedAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
