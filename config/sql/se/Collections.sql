CREATE TABLE `Collections` (
  `EbookId` int(10) unsigned NOT NULL,
  `Name` varchar(255) NOT NULL,
  `UrlName` varchar(255) NOT NULL,
  `SequenceNumber` int(10) unsigned NULL,
  `Type` varchar(255) NULL,
  KEY `index1` (`EbookId`),
  KEY `index2` (`UrlName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
