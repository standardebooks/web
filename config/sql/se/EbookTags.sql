CREATE TABLE IF NOT EXISTS `EbookTags` (
  `EbookId` int(10) unsigned NOT NULL,
  `TagId` int(10) unsigned NOT NULL,
  `SortOrder` tinyint(3) unsigned NOT NULL,
  UNIQUE KEY `EbookId_TagId` (`EbookId`,`TagId`),
  KEY `TagId` (`TagId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
