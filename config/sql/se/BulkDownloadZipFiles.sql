CREATE TABLE if NOT EXISTS `BulkDownloadZipFiles` (
  `LabelType` enum('subjects', 'collections', 'authors', 'months') NOT NULL,
  `LabelName` varchar(255) NOT NULL,
  `Format` enum('epub', 'azw3', 'kepub', 'xhtml', 'epub-advanced') NOT NULL,
  `DownloadUrl` varchar(511) NOT NULL,
  `DownloadByteCount` bigint unsigned NOT NULL,
  PRIMARY KEY (`LabelType`, `LabelName`, `Format`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
