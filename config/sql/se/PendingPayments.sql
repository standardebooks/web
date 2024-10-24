CREATE TABLE IF NOT EXISTS `PendingPayments` (
  `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Processor` enum('fractured_atlas') NOT NULL,
  `TransactionId` varchar(80) NOT NULL,
  `ProcessedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
