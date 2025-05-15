CREATE TABLE IF NOT EXISTS `EbookDownloads` (
  `EbookId` int(10) unsigned NOT NULL,
  `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IpAddr` inet6 NULL,
  `UserAgent` varchar(255) NULL,
  INDEX `idxEbookIdCreated` (`EbookId`, `Created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
