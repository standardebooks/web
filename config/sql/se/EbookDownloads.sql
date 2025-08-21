CREATE TABLE IF NOT EXISTS `EbookDownloads` (
  `EbookId` int(10) unsigned NOT NULL,
  `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IpAddress` inet6 NULL,
  `UserAgent` mediumtext NULL,
  `Source` enum('feed', 'download') NULL,
  INDEX `idxCreated` (`Created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
