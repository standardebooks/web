CREATE TABLE IF NOT EXISTS `TocEntries` (
  `EbookId` int(10) unsigned NOT NULL,
  `TocEntry` text NOT NULL,
  `SortOrder` smallint unsigned NOT NULL,
  KEY `EbookId` (`EbookId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
