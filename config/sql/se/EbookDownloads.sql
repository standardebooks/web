CREATE TABLE IF NOT EXISTS `EbookDownloads` (
  `EbookId` int(10) unsigned NOT NULL,
  `IpAddress` inet6 NULL,
  `UserAgent` mediumtext NULL,
  `Source` enum('feed', 'download') NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  INDEX `CreatedAt` (`CreatedAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
