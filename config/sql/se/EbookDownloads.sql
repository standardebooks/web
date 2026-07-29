CREATE TABLE IF NOT EXISTS `EbookDownloads` (
  `EbookId` int(10) unsigned NOT NULL,
  `IpAddress` inet6 DEFAULT NULL,
  `UserAgent` mediumtext DEFAULT NULL,
  `Source` enum('feed','download') DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  KEY `CreatedAt` (`CreatedAt`),
  KEY `IpAddress_CreatedAt` (`IpAddress`,`CreatedAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
