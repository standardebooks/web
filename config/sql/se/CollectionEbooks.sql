CREATE TABLE IF NOT EXISTS `CollectionEbooks` (
  `EbookId` int(10) unsigned NOT NULL,
  `CollectionId` int(10) unsigned NOT NULL,
  `SequenceNumber` int(10) unsigned NULL,
  `SortOrder` tinyint(3) unsigned NOT NULL,
  UNIQUE KEY `idxUnique` (`EbookId`,`CollectionId`),
  KEY `index1` (`CollectionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
