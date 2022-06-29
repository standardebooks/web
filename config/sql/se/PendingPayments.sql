CREATE TABLE `PendingPayments` (
  `Created` datetime NOT NULL,
  `ChannelId` tinyint(4) unsigned NOT NULL,
  `TransactionId` varchar(80) NOT NULL,
  `ProcessedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
