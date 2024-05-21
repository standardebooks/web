CREATE TABLE `EbookTags` (
  `EbookTagId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `EbookId` int(10) unsigned NOT NULL,
  `TagId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`EbookTagId`),
  UNIQUE KEY `idxUnique` (`EbookId`,`TagId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
