CREATE TABLE IF NOT EXISTS `EbookDownloadSummaries` (
  `EbookId` int(10) unsigned NOT NULL,
  `Date` date NOT NULL,
  `DownloadCount` int(10) unsigned NOT NULL DEFAULT 0,
  `BotDownloadCount` int(10) unsigned NOT NULL DEFAULT 0,
  `UniqueDownloadCount` int(10) unsigned NOT NULL DEFAULT 0,
  `FeedDownloadCount` int(10) unsigned NOT NULL DEFAULT 0,
  `WebDownloadCount` int(10) unsigned NOT NULL DEFAULT 0,
  UNIQUE INDEX `EbookId_DownloadedOn` (`EbookId`, `Date`),
  INDEX `DownloadedOn_EbookId_DownloadCount` (`Date`, `EbookId`, `DownloadCount`),
  INDEX `EbookId_DownloadCount` (`EbookId`, `DownloadCount`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
