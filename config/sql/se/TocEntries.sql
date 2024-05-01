CREATE TABLE `TocEntries` (
  `TocEntryId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `EbookId` int(10) unsigned NOT NULL,
  `TocEntry` text NOT NULL,
  PRIMARY KEY (`TocEntryId`),
  KEY `index1` (`EbookId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
