CREATE TABLE `TocEntries` (
  `EbookId` int(10) unsigned NOT NULL,
  `TocEntry` text NOT NULL,
  KEY `index1` (`EbookId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
