CREATE TABLE IF NOT EXISTS `EbookDownloadSummaries` (
  `EbookId` int(10) unsigned NOT NULL,
  `Date` date NOT NULL,
  `DownloadCount` int(10) unsigned NOT NULL DEFAULT 0,
  `BotDownloadCount` int(10) unsigned NOT NULL DEFAULT 0,
  `UniqueDownloadCount` int(10) unsigned NOT NULL DEFAULT 0,
  `FeedDownloadCount` int(10) unsigned NOT NULL DEFAULT 0,
  `WebDownloadCount` int(10) unsigned NOT NULL DEFAULT 0,
  UNIQUE KEY `EbookId_Date` (`EbookId`,`Date`),
  KEY `Date_EbookId_DownloadCount` (`Date`,`EbookId`,`DownloadCount`),
  KEY `EbookId_DownloadCount` (`EbookId`,`DownloadCount`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
