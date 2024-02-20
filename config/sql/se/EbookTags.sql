CREATE TABLE `EbookTags` (
  `EbookId` int(10) unsigned NOT NULL,
  `TagId` int(10) unsigned NOT NULL,
  UNIQUE KEY `idxUnique` (`EbookId`,`TagId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
