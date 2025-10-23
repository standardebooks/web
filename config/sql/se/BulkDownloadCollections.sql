CREATE TABLE if NOT EXISTS `BulkDownloadCollections` (
  `LabelType` enum('subjects', 'collections', 'authors', 'months') NOT NULL,
  `LabelName` varchar(255) NOT NULL,
  `LabelSort` varchar(255) NOT NULL,
  `LabelUrlSegment` varchar(511) NULL,
  `EbookCount` int(10) unsigned NOT NULL DEFAULT 0,
  `Updated` timestamp NOT NULL,
  PRIMARY KEY (`LabelType`, `LabelName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
