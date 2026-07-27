CREATE TABLE if NOT EXISTS `BulkDownloadCollections` (
  `LabelType` enum('subjects', 'collections', 'authors', 'months') NOT NULL,
  `LabelName` varchar(255) NOT NULL,
  `LabelSort` varchar(255) NOT NULL,
  `LabelUrlSegment` varchar(511) NULL,
  `EbookCount` int(10) unsigned NOT NULL DEFAULT 0,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`LabelType`, `LabelName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
