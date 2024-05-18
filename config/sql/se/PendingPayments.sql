CREATE TABLE `PendingPayments` (
  `Created` datetime NOT NULL,
  `Processor` enum('fractured_atlas') NOT NULL,
  `TransactionId` varchar(80) NOT NULL,
  `ProcessedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
