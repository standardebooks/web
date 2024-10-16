CREATE TABLE `CollectionEbooks` (
  `CollectionEbookId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `EbookId` int(10) unsigned NOT NULL,
  `CollectionId` int(10) unsigned NOT NULL,
  `SequenceNumber` int(10) unsigned NULL,
  PRIMARY KEY (`CollectionEbookId`),
  UNIQUE KEY `idxUnique` (`EbookId`,`CollectionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
