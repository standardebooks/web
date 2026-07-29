CREATE TABLE IF NOT EXISTS `CollectionEbooks` (
  `EbookId` int(10) unsigned NOT NULL,
  `CollectionId` int(10) unsigned NOT NULL,
  `SequenceNumber` int(10) unsigned DEFAULT NULL,
  `SortOrder` tinyint(3) unsigned NOT NULL,
  `TitleInCollection` varchar(255) DEFAULT NULL,
  KEY `EbookId_CollectionId` (`EbookId`,`CollectionId`),
  KEY `CollectionId_SequenceNumber_EbookId` (`CollectionId`,`SequenceNumber`,`EbookId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
