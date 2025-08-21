CREATE TABLE IF NOT EXISTS `EbookDownloadSummaries` (
  `EbookId` int(10) unsigned NOT NULL,
  `Date` date NOT NULL,
  `DownloadCount` int(10) unsigned NOT NULL DEFAULT 0,
  `BotDownloadCount` int(10) unsigned NOT NULL DEFAULT 0,
  `UniqueDownloadCount` int(10) unsigned NOT NULL DEFAULT 0,
  `FeedDownloadCount` int(10) unsigned NOT NULL DEFAULT 0,
  `WebDownloadCount` int(10) unsigned NOT NULL DEFAULT 0,
  UNIQUE INDEX `idxUnique` (`EbookId`, `Date`),
  INDEX `index1` (Date, EbookId, DownloadCount),
  INDEX `index2` (EbookId, DownloadCount)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
