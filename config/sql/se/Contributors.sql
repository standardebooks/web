CREATE TABLE IF NOT EXISTS `Contributors` (
  `EbookId` int(10) unsigned NOT NULL,
  `Name` varchar(255) NOT NULL,
  `UrlName` varchar(255) NOT NULL,
  `SortName` varchar(255) DEFAULT NULL,
  `WikipediaUrl` varchar(255) DEFAULT NULL,
  `MarcRole` enum('aut','ctb','ill','trl','edt') NOT NULL,
  `FullName` varchar(255) DEFAULT NULL,
  `NacoafUrl` varchar(255) DEFAULT NULL,
  `SortOrder` tinyint(3) unsigned NOT NULL,
  KEY `EbookId` (`EbookId`),
  KEY `UrlName` (`UrlName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
