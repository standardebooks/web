CREATE TABLE `EbookSources` (
  `EbookId` int(10) unsigned NOT NULL,
  `Type` tinyint(4) unsigned NOT NULL,
  `Url` varchar(255) NOT NULL,
  KEY `index1` (`EbookId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
